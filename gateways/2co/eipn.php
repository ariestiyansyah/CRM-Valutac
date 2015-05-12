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
	  
  ini_set('log_errors', true);
  ini_set('error_log', dirname(__file__) . '/ipn_errors.log');

  if (isset($_POST['cart_order_id'])) {
      $twoco = $db->first("SELECT * FROM gateways WHERE name = '2co'");
/*
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
      $hash_order = $_POST['order_number'];
      if (!$twoco->live) {
          $hash_order = "1";
      }

      $hash = md5($twoco->extra3 . $twoco->extra . $hash_order . $_POST['total']);
      $hash = strtoupper($hash);

      if ($hash == $_POST['key']) {
		  list($user_id, $form_id, $sesid) = explode('_', $_POST['custom_form_data']);
		  $formrow = $db->first("SELECT * FROM estimator WHERE id = " . (int)$form_id);
          $payer_email = '';
		  if($usr = $db->first("SELECT * FROM users WHERE id = " . (int)$user_id)) {
			  $first_last = $usr->fname . ' ' . $usr->fname;
			  $payer_email = $usr->email;
		  } else {
			  $first_last = sanitize($_POST['card_holder_name']);
		  }
          if ($formrow) {
              $edata = array(
                  'txn_id' => sanitize($_POST['order_number']),
                  'form_id' => $formrow->id,
                  'user' => $first_last,
                  'email' => $payer_email,
                  'price' => floatval($_POST['total']),
                  'currency' => sanitize($_POST['mc_currency']),
                  'created' => "NOW()",
                  'pp' => "2CO",
                  'status' => 1);

              $db->insert("payments", $edata);
              $db->delete("cart", "sesid = '" . $sesid . "'");


			  /* == Notify User == */
			   $form_data = getValue("form_data", "estimator_data", "sesid = '" . $sesid . "'");
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
							->setFrom(array(Registry::get("Core")
							->site_email => Registry::get("Core")->company))
							->setBody($body, 'text/html');
				  $mailer->send($msg);
			  }

          } else {
			  die("Invalid payment received. Please contact site adimistrator.");
		  }


          echo "<html><head>\n";
          echo "  <meta http-equiv=\"Refresh\" content=\"0; url=" . SITEURL . '/estimator.php?id=' . $formrow->id . '&order=ok' . "\">\n";
          echo "</head><body>\n";
          echo "  <p>Please follow <a href=\"" . SITEURL . '/estimator.php?id=' . $formrow->id . '&order=ok' . "\">link</a>!</p>\n";
          echo "</body></html>\n";
          exit();
      } else {
          echo "The response from 2checkout.com can't be parsed. Contact site adimistrator, please!";

      }
  }
?>