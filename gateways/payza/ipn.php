<?php
  /**
   * Payza IPN
   *
   * @package Digital Downloads Pro
   * @author wojoscripts.com
   * @copyright 2010
   * @version $Id: ipn.php,<?php echo  2010-08-10 21:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  ini_set('log_errors', true);
  ini_set('error_log', dirname(__file__) . '/ipn_errors.log');
  
  require_once ("../../init.php");

  /* only for debuggin purpose. Create logfile.txt and chmot to 0777
  ob_start();
  echo '<pre>';
  print_r($_POST);
  echo '</pre>';
  $logInfo = ob_get_contents();
  ob_end_clean();
  
  $file = fopen('logfile.txt', 'a');
  fwrite($file, $logInfo);
  fclose($file);
  */

  $ap_code = getValue("extra3", "gateways", "name = 'payza'");

  /* Check for Valid Ipn Code */
  if ($ap_code != $_POST['ap_securitycode'])
      die();

  if (preg_match('/Success/', $_POST['ap_status'])) {

      $payer_email = $_POST['ap_custemailaddress'];
      $receiver_email = $_POST['ap_merchant'];
      $ap_currency = $_POST['ap_currency'];
      $mc_gross = $_POST['ap_totalamount'];
      $txn_id = $_POST['ap_referencenumber'];
      list($user_id, $sesid) = explode("_", $_POST['apc_1']);

      if (isset($_POST['apc_2'])) {
		  $form_id = intval($_POST['ap_itemcode']);
          $formrow = $db->first("SELECT * FROM estimator WHERE id = " . (int)$form_id);
          if ($usr = $db->first("SELECT * FROM users WHERE id = " . (int)$user_id)) {
              $first_last = $usr->fname . ' ' . $usr->fname;
              $payer_email = $usr->email;
          } else {
              $first_last = sanitize($_POST['ap_custfirstname'] . ' ' . $_POST['ap_custlastname']);
              $payer_email = isset($_POST['ap_custemailaddress']) ? $_POST['ap_custemailaddress'] : false;
          }

          if ($receiver_email == $ap_email && $form_id) {
              $edata = array(
                  'txn_id' => $txn_id,
                  'form_id' => $formrow->id,
                  'user' => $first_last,
                  'email' => $payer_email,
                  'price' => floatval($mc_gross),
                  'currency' => sanitize($_POST['mc_currency']),
                  'created' => "NOW()",
                  'pp' => "Payza",
                  'status' => 1);


              $db->insert("payments", $edata);
              $db->delete("cart", "sesid = '" . $sesid . "'");

			  /* == Notify User == */
			   $form_data = getValue("form_data", "estimator_data", "sesid = '" . $user->sesid . "'");
			   if($payer_email and $form_data) {
                  require_once (BASEPATH . "lib/class_mailer.php");
                  $mailer = Mailer::sendMail();

                  ob_start();
                  require_once (BASEPATH . 'mailer/Email_Service_Payment.tpl.php');
                  $html_message = ob_get_contents();
                  ob_end_clean();

				  if (file_exists(UPLOADS . 'print_logo.png')) {
					  $logo = '<img src="' . UPLOADURL . 'print_logo.png" alt="' . Registry::get("Core")->company . '" />';
				  } elseif (Registry::get("Core")->logo) {
					  $logo = '<img src="' . UPLOADURL . Registry::get("Core")->logo . '" alt="' . Registry::get("Core")->company . '" />';
				  } else {
					  $logo = Registry::get("Core")->company;
				  }

				  $body = str_replace(array(
					  '[LOGO]',
					  '[NAME]',
					  '[FORMDATA]',
					  '[CCOMPANY]',
					  '[SITEURL]'), array(
					  $logo,
					  $first_last,
					  cleanOut($form_data),
					  Registry::get("Core")->company,
					  SITEURL), $html_message);
				  
				  $subject = Lang::$word->STAFF_PAYCOMPLETE_OK . $formrow->title;

                  $msg = Swift_Message::newInstance()
						  ->setSubject($subject)
						  ->setTo(array($payer_email => $first_last))
						  ->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->company))
						  ->setBody($body, 'text/html');
                  $mailer->send($msg);
              }
          }

      } else {
          $inv_id = intval($_POST['ap_itemcode']);
          $ap_email = getValue("extra", "gateways", "name = 'payza'");
          $invrow = $db->first("SELECT * FROM invoices WHERE id = " . (int)$inv_id);

          if ($receiver_email == $ap_email && $invrow) {
              $edata = array(
                  'project_id' => $invrow->project_id,
                  'invoice_id' => $invrow->id,
                  'amount' => floatval($mc_gross),
                  'created' => "NOW()",
                  'method' => "AlertPay",
                  'description' => "Payment via Payza");

              $db->insert("invoice_payments", $edata);

              $row = $db->first("SELECT SUM(amount) as amtotal FROM invoice_payments WHERE invoice_id = '" . $edata['invoice_id'] . "' GROUP BY invoice_id");

              $data['amount_paid'] = $row->amtotal;
              $pdata['b_status'] = $data['amount_paid'];

              $db->update("invoices", $data, "id=" . $edata['invoice_id']);
              $db->update(Content::pTable, $pdata, "id=" . $edata['project_id']);

              $row2 = $db->first("SELECT amount_total, amount_paid FROM invoices WHERE id = " . $edata['invoice_id']);
              $idata['status'] = ($row2->amount_total == $row2->amount_paid) ? 'Paid' : 'Unpaid';
              $db->update("invoices", $idata, "id='" . $edata['invoice_id'] . "'");


              /* == Notify User == */
              require_once (BASEPATH . "lib/class_mailer.php");
              $mailer = Mailer::sendMail();

              $userdata = $db->first("SELECT i.*," 
			  . "\n p.title as ptitle, CONCAT(u.fname,' ',u.lname) as fullname, u.username, u.email, u.address, u.city, u.zip, u.state, u.phone, u.company" 
			  . "\n FROM invoices as i" 
			  . "\n LEFT JOIN " . Content::pTable . " as p ON p.id = i.project_id" 
			  . "\n LEFT JOIN " . Users::uTable . " as u ON u.id = i.client_id" 
			  . "\n WHERE i.id = '" . $edata['invoice_id'] . "'");

              ob_start();
              require_once (BASEPATH . 'mailer/Email_Payment.tpl.php');
              $html_message = ob_get_contents();
              ob_end_clean();

			  if (file_exists(UPLOADS . 'print_logo.png')) {
				  $logo = '<img src="' . UPLOADURL . 'print_logo.png" alt="' . Registry::get("Core")->company . '" />';
			  } elseif (Registry::get("Core")->logo) {
				  $logo = '<img src="' . UPLOADURL . Registry::get("Core")->logo . '" alt="' . Registry::get("Core")->company . '" />';
			  } else {
				  $logo = Registry::get("Core")->company;
			  }
			  
			  $body = str_replace(array(
				  '[LOGO]',
				  '[UNAME]',
				  '[PTITLE]',
				  '[INVID]',
				  '[TITLE]',
				  '[GROSS]',
				  '[TOTAL]',
				  '[DATE]',
				  '[METHOD]',
				  '[CCOMPANY]',
				  '[SITEURL]'), array(
				  $logo,
				  Core::renderName($userdata),
				  $userdata->ptitle,
				  Registry::get("Core")->invoice_number . $inv_id,
				  $userdata->title,
				  $mc_gross,
				  $userdata->amount_total - $userdata->amount_paid,
				  date('Y-m-d'),
				  $edata['method'],
				  Registry::get("Core")->company,
				  SITEURL), $html_message);
				  
              $subject = Lang::$word->STAFF_PAYCOMPLETE_OK . $userdata->ptitle;
			  
              $msg = Swift_Message::newInstance()
					  ->setSubject($subject)
					  ->setTo(array($userdata->email => Core::renderName($userdata)))
					  ->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->company))
					  ->setBody($body, 'text/html');

              $mailer->send($msg);
          }
      }
  }
?>