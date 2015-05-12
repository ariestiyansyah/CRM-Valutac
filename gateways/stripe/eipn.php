<?php
  /**
   * Stripe IPN
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2013
   * @version $Id: ipn.php, v2.00 2013-05-08 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);

  require_once ("../../init.php");
  require_once (dirname(__file__) . '/lib/Stripe.php');

  ini_set('log_errors', true);
  ini_set('error_log', dirname(__file__) . '/ipn_errors.log');
  
  if (isset($_POST['processStripePayment'])) {
	  if (empty($_POST['fname']))
		  Filter::$msgs['fname'] = lang('FNAME');

	  if (empty($_POST['lname']))
		  Filter::$msgs['lname'] = lang('LNAME');

	  if (empty($_POST['email']))
		  Filter::$msgs['email'] = lang('EMAIL_R1');

      if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $_POST['email']))
          Filter::$msgs['email'] = lang('EMAIL_R3');

      if (empty($_POST['card-number']))
          Filter::$msgs['card-number'] = 'Please enter your Credit Card number';

      if (empty($_POST['card-cvc']))
          Filter::$msgs['card-cvc'] = 'Please enter your 3/4 digit CVV code';

      if (empty($_POST['card-expiry-month']))
          Filter::$msgs['card-expiry-month'] = 'Please enter Expiration Month';
		  
      if (empty($_POST['card-expiry-year']))
          Filter::$msgs['card-expiry-year'] = 'Please enter Expiration Year';

      if (empty(Filter::$msgs)) {
		  $key = $db->first("SELECT * FROM gateways WHERE name = 'stripe'");
		  $stripe = array("secret_key" => $key->extra, "publishable_key" => $key->extra3);
		  Stripe::setApiKey($stripe['secret_key']);
	
		  try {
			  $charge = Stripe_Charge::create(array(
				  "amount" => round($_POST['amount'] * 100, 0), // amount in cents, again
				  "currency" => $_POST['currency_code'],
				  "card" => array(
					  "number" => $_POST['card-number'],
					  "exp_month" => $_POST['card-expiry-month'],
					  "exp_year" => $_POST['card-expiry-year'],
					  "cvc" => $_POST['card-cvc'],
					  ),
				  "description" => $_POST['item_name']));
			  $json = json_decode($charge);
			  $amount_charged = round(($json->{'amount'} / 100), 2);
  
			  /* == Payment Completed == */
			  $form_id = $_POST['item_number'];
			  $formrow = $db->first("SELECT * FROM estimator WHERE id = " . (int)$form_id);
			  
			  if($usr = $db->first("SELECT * FROM users WHERE id = " . $user->uid)) {
				  $first_last = $usr->fname . ' ' . $usr->fname;
				  $payer_email = $usr->email;
			  } else {
				  $first_last = sanitize($_POST['fname'] . ' ' . $_POST['lname']);
				  $payer_email = isset($_POST['email']) ? $_POST['email'] : false;
			  }
			  
			  if ($form_id) {
				$edata = array(
					'txn_id' => $json->{'card'}->{'fingerprint'},
					'form_id' => $formrow->id,
					'user' => $first_last,
					'email' => $payer_email,
					'price' => floatval($amount_charged),
					'currency' => strtoupper(sanitize($json->{'currency'})),
					'created' => "NOW()",
					'pp' => "Stripe",
					'status' => 1
					);
	
				  $db->insert("payments", $edata);
				  $db->delete("cart", "sesid = '" . $user->sesid . "'");
	
				  $jn['type'] = 'success';
				  $jn['message'] = 'Thank you payment completed';
				  print json_encode($jn);
			  
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
		  catch (Stripe_CardError $e) {
			  //$json = json_decode($e);
			  $body = $e->getJsonBody();
			  $err = $body['error'];
			  $json['type'] = 'error';
			  Filter::$msgs['status'] = 'Status is:' . $e->getHttpStatus() . "\n";
			  Filter::$msgs['type'] = 'Type is:' . $err['type'] . "\n";
			  Filter::$msgs['code'] = 'Code is:' . $err['code'] . "\n";
			  Filter::$msgs['param'] = 'Param is:' . $err['param'] . "\n";
			  Filter::$msgs['msg'] = 'Message is:' . $err['message'] . "\n";
			  $json['message'] = Filter::msgStatus();
			  print json_encode($json);
		  }
		  catch (Stripe_InvalidRequestError $e) {}
		  catch (Stripe_AuthenticationError $e) {}
		  catch (Stripe_ApiConnectionError $e) {}
		  catch (Stripe_Error $e) {}
		  catch (exception $e) {}
	  
	  } else {
		  $json['message'] = Filter::msgStatus();
		  print json_encode($json);
	  }
  }
?>