<?php
  /**
   * 2CO IPN
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2013
   * @version $Id: ipn.php, v2.00 2013-05-08 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  define("_PIPN", true);

  require_once ("../../init.php");
  if (!$user->logged_in)
      exit;  
	  
  ini_set('log_errors', true);
  ini_set('error_log', dirname(__file__) . '/ipn_errors.log');

  if (isset($_POST['cart_order_id'])) {
      $twoco = $db->first("SELECT * FROM gateways WHERE name = '2co'");

      $hash_order = $_POST['order_number'];
      if (!$twoco->live) {
          $hash_order = "1";
      }

      $hash = md5($twoco->extra3 . $twoco->extra . $hash_order . $_POST['total']);
      $hash = strtoupper($hash);

      if ($hash == $_POST['key']) {
          $inv_id = str_replace("INV", "", $_POST['cart_order_id']);
          $invrow = $db->first("SELECT * FROM invoices WHERE id = " . (int)$inv_id);

          if ($invrow) {
              $edata = array(
                  'project_id' => $invrow->project_id,
                  'invoice_id' => $invrow->id,
                  'amount' => floatval($_POST['total']),
                  'recurring' => 0,
                  'created' => "NOW()",
                  'method' => "2CO",
                  'description' => "Payment via 2co");

              $db->insert("invoice_payments", $edata);
              $row = $db->first("SELECT SUM(amount) as amtotal FROM invoice_payments WHERE invoice_id = '" . $edata['invoice_id'] . "' GROUP BY invoice_id");

              $data['amount_paid'] = $row->amtotal;
              $pdata['b_status'] = $data['amount_paid'];
			  $mc_gross = $edata['amount'];

              $db->update("invoices", $data, "id='" . $edata['invoice_id'] . "'");
              $db->update(Content::pTable, $pdata, "id='" . $edata['project_id'] . "'");

              $row2 = $db->first("SELECT amount_total, amount_paid FROM invoices WHERE id = '" . $edata['invoice_id'] . "'");
              $idata['status'] = ($row2->amount_total == $row2->amount_paid) ? 'Paid' : 'Unpaid';
              $db->update("invoices", $idata, "id='" . $edata['invoice_id'] . "'");

              /* == Notify User == */
              require_once (BASEPATH . "lib/class_mailer.php");
			  $mailer = Mailer::sendMail();

              $userdata = $db->first("SELECT i.*," 
			  . "\n p.title as ptitle, CONCAT(u.fname,' ',u.lname) as fullname, u.username, u.email, u.address, u.city, u.zip, u.state, u.phone, u.company" 
			  . "\n FROM invoices as i" 
			  . "\n LEFT JOIN projects as p ON p.id = i.project_id" 
			  . "\n LEFT JOIN " . Users::uTable . " as u ON u.id = i.client_id" 
			  . "\n WHERE i.id = '" . $edata['invoice_id'] . "'"
			  );

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

          } else {
			  die("Invalid payment received. Please contact site adimistrator.");
		  }


          echo "<html><head>\n";
          echo "  <meta http-equiv=\"Refresh\" content=\"0; url=" . SITEURL . '/account.php?do=billing&msg=6' . "\">\n";
          echo "</head><body>\n";
          echo "  <p>Please follow <a href=\"" . SITEURL . '/account.php?do=billing&msg=6' . "\">link</a>!</p>\n";
          echo "</body></html>\n";
          exit();
      } else {
          echo "The response from 2checkout.com can't be parsed. Contact site adimistrator, please!";

      }


  }
?>