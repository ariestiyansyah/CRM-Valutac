<?php
  /**
   * PayPal IPN
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2010
   * @version $Id: ipn.php, 2013-04-10 21:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  define("_PIPN", true);

  ini_set('log_errors', true);
  ini_set('error_log', dirname(__file__) . '/ipn_errors.log');

  if (isset($_POST['payment_status'])) {
      require_once ("../../init.php");

      include (BASEPATH . 'lib/class_pp.php');
	  $listener = new IpnListener();
      $live = getValue("live", "gateways", "name = 'paypal'");

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
      
      $listener->use_live = $live;
      $listener->use_ssl = true;
      $listener->use_curl = false;

      try {
          $listener->requirePostMethod();
          $ppver = $listener->processIpn();
      }
      catch (exception $e) {
          error_log($e->getMessage());
          exit(0);
      }
	   
      $payment_status = $_POST['payment_status'];
      $receiver_email = $_POST['business'];
      $payer_email = $_POST['payer_email'];
      $payer_status = $_POST['payer_status'];

      list($user_id, $sesid) = explode('_', $_POST['custom']);
      $mc_gross = $_POST['mc_gross'];
      $inv_id = $_POST['item_number'];

      $pp_email = getValue("extra", "gateways", "name = 'paypal'");
      $invrow = $db->first("SELECT * FROM invoices WHERE id = " . (int)$inv_id);

      if ($ppver) {
          if ($_POST['payment_status'] == 'Completed') {
              if ($receiver_email == $pp_email && $invrow) {
                  $edata = array(
                      'project_id' => $invrow->project_id,
                      'invoice_id' => $invrow->id,
                      'amount' => floatval($mc_gross),
                      'recurring' => ($invrow->recurring) ? 1 : 0,
                      'created' => "NOW()",
                      'method' => "PayPal",
                      'description' => "Payment via Paypal");

                  $db->insert("invoice_payments", $edata);

                  $row = $db->first("SELECT SUM(amount) as amtotal FROM invoice_payments WHERE invoice_id = {$invrow->id} GROUP BY invoice_id");

				  $db->update("invoices", array('amount_paid' => $row->amtotal), "id=" . $invrow->id);
				  $db->update(Content::pTable, array('b_status' => $row->amtotal), "id=" . $invrow->project_id);

                  $row2 = $db->first("SELECT amount_total, amount_paid FROM invoices WHERE id = " . $invrow->id);
                  $idata['status'] = ($row2->amount_total == $row2->amount_paid) ? 'Paid' : 'Unpaid';
                  $db->update("invoices", $idata, "id=" . $invrow->id);

                  /* == Notify User == */
                  require_once (BASEPATH . "lib/class_mailer.php");
                  $mailer = Mailer::sendMail();

                  $userdata = $db->first("SELECT i.*," 
				  . "\n p.title as ptitle, CONCAT(u.fname,' ',u.lname) as fullname, u.username, u.email, u.address, u.city, u.zip, u.state, u.phone, u.company" 
				  . "\n FROM invoices as i" 
				  . "\n LEFT JOIN " . Content::pTable . " as p ON p.id = i.project_id" 
				  . "\n LEFT JOIN " . Users::uTable . " as u ON u.id = i.client_id" 
				  . "\n WHERE i.id = " . $edata['invoice_id']);

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
  }
?>