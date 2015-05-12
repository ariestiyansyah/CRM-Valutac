<?php
  /**
   * MoneyBookers IPN
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2010
   * @version $Id: ipn.php,<?php echo  2010-08-10 21:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  
  require_once("../../init.php");
  
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
  
  /* Check for mandatory fields */
  $r_fields = array(
		'status', 
		'md5sig', 
		'merchant_id', 
		'pay_to_email', 
		'mb_amount', 
		'mb_transaction_id', 
		'currency', 
		'amount', 
		'transaction_id', 
		'pay_from_email', 
		'mb_currency'
  );
  $mb_secret = getValue("extra3", "gateways", "name = 'moneybookers'");
  
  foreach ($r_fields as $f)
      if (!isset($_POST[$f]))
          die();
  
  /* Check for MD5 signature */
  $md5 = strtoupper(md5($_POST['merchant_id'] . $_POST['transaction_id'] . strtoupper(md5($mb_secret)) . $_POST['mb_amount'] . $_POST['mb_currency'] . $_POST['status']));
  if ($md5 != $_POST['md5sig'])
      die();
  
  if (intval($_POST['status']) == 2) {
	  
	  $payer_email = $_POST['pay_from_email'];
	  $receiver_email = $_POST['pay_to_email'];
      $mb_currency = $_POST['mb_currency'];
	  $mc_gross = $_POST['amount'];
	  $txn_id = $_POST['transaction_id'];
	  list($user_id, $form_id, $sesid) = explode("_", $_POST['custom']);
	  
      $mb_email = getValue("extra", "gateways", "name = 'moneybookers'");
	  $formrow = $db->first("SELECT * FROM estimator WHERE id = " . (int)$form_id);

	  if($usr = $db->first("SELECT * FROM users WHERE id = " . (int)$user_id)) {
		  $first_last = $usr->fname . ' ' . $usr->fname;
		  $payer_email = $usr->email;
	  } else {
		  $first_last = sanitize($_POST['firstname'] . ' ' . $_POST['lastname']);
		  $payer_email = isset($_POST['pay_from_email']) ? $_POST['pay_from_email'] : false;
	  }
	  
	  if ($receiver_email == $mb_email && $formrow) {
			$edata = array(
				'txn_id' => $txn_id,
				'form_id' => $formrow->id,
				'user' => $first_last,
				'email' => $payer_email,
				'price' => floatval($mc_gross),
				'currency' => sanitize($mb_currency),
				'created' => "NOW()",
				'pp' => "Skrill",
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
?>