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
      $payer_status = $_POST['payer_status'];

      list($user_id, $sesid) = explode('_', $_POST['custom']);
      $mc_gross = $_POST['mc_gross'];
      $form_id = $_POST['item_number'];
	  $txn_id = $_POST['txn_id'];
	  if($usr = $db->first("SELECT * FROM users WHERE id = " . (int)$user_id)) {
		  $first_last = $usr->fname . ' ' . $usr->fname;
		  $payer_email = $usr->email;
	  } else {
		  $first_last = sanitize($_POST['first_name'] . ' ' . $_POST['last_name']);
		  $payer_email = isset($_POST['payer_email']) ? $_POST['payer_email'] : false;
	  }

      $pp_email = getValue("extra", "gateways", "name = 'paypal'");
      $formrow = $db->first("SELECT * FROM estimator WHERE id = " . (int)$form_id);

      if ($ppver) {
          if ($_POST['payment_status'] == 'Completed') {
              if ($receiver_email == $pp_email && $formrow) {
                  $edata = array(
				      'txn_id' => $txn_id,
                      'form_id' => $formrow->id,
                      'user' => $first_last,
					  'email' => $payer_email,
                      'price' => floatval($mc_gross),
                      'currency' => sanitize($_POST['mc_currency']),
                      'created' => "NOW()",
                      'pp' => "PayPal",
                      'status' => 1
					  );

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
								->setFrom(array(Registry::get("Core")
								->site_email => Registry::get("Core")->company))
								->setBody($body, 'text/html');
					  $mailer->send($msg);
				  }

              }
          }
      }
  }
?>