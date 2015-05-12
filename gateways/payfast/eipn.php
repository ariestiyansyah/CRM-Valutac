<?php

  /**
   * PayFast IPN
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: eipn.php, 2014-08-30 21:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  define("_PIPN", true);

  ini_set('log_errors', true);
  ini_set('error_log', dirname(__file__) . '/ipn_errors.log');

  include_once dirname(__file__) . '/pf.inc.php';

  if (isset($_POST['payment_status'])) {
      require_once ("../../init.php");

      $pf = Core::getRow(Content::gwTable, "name", "payfast");
      //$passPhrase = $pf->extra3;
      $pfHost = ($pf->live) ? 'https://www.payfast.co.za' : 'https://sandbox.payfast.co.za';
      $error = false;


      pflog('ITN received from payfast.co.za');


      if (!pfValidIP($_SERVER['REMOTE_ADDR'])) {
          pflog('REMOTE_IP mismatch: ');
          $error = true;
          return false;
      }

      $data = pfGetData();

      pflog('POST received from payfast.co.za: ' . print_r($data, true));

      if ($data === false) {
          pflog('POST is empty: ' . print_r($data, true));
          $error = true;
          return false;
      }

      if (!pfValidSignature($data, $pf->extra3)) {
          pflog('Signature mismatch on POST');
          $error = true;
          return false;
      }

      pflog('Signature OK');


      $itnPostData = array();
      $itnPostDataValuePairs = array();

      foreach ($_POST as $key => $value) {
          if ($key == 'signature')
              continue;

          $value = urlencode(stripslashes($value));
          $value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i', '${1}%0D%0A${3}', $value);

          $itnPostDataValuePairs[] = "$key=$value";
      }

      $itnVerifyRequest = implode('&', $itnPostDataValuePairs);

      if (!pfValidData($pfHost, $itnVerifyRequest, "$pfHost/eng/query/validate")) {
          pflog("ITN mismatch for $itnVerifyRequest\n");
          pflog('ITN not OK');
          $error = true;
          return false;
      }

      pflog('ITN OK');
      pflog("ITN verified for $itnVerifyRequest\n");

      if ($error == false and $_POST['payment_status'] == "COMPLETE") {

          $user_id = intval($_POST['custom_int1']);
          $sesid = sanitize($_POST['custom_str1']);
          $mc_gross = $_POST['amount_gross'];
          $inv_id = $_POST['m_payment_id'];
		  $txn_id = sanitize($_POST['pf_payment_id']);

          $form_id = intval($_POST['m_payment_id']);
          $formrow = $db->first("SELECT * FROM estimator WHERE id = " . (int)$form_id);

          if ($usr = $db->first("SELECT * FROM users WHERE id = " . (int)$user_id)) {
              $first_last = $usr->fname . ' ' . $usr->fname;
              $payer_email = $usr->email;
          } else {
              $first_last = sanitize($_POST['name_first'] . ' ' . $_POST['name_last']);
              $payer_email = isset($_POST['email_address']) ? $_POST['email_address'] : false;
          }

          $edata = array(
              'txn_id' => $txn_id,
              'form_id' => $formrow->id,
              'user' => $first_last,
              'email' => $payer_email,
              'price' => floatval($mc_gross),
              'currency' => sanitize($_POST['mc_currency']),
              'created' => "NOW()",
              'pp' => "PayFast",
              'status' => 1);

          $db->insert("payments", $edata);
          $db->delete("cart", "sesid = '" . $sesid . "'");


          /* == Notify User == */
          $form_data = getValue("form_data", "estimator_data", "sesid = '" . $user->sesid . "'");
          if ($payer_email and $form_data) {
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
			  pflog("Email Notification sent successfuly");
			  
          }
          
      }

  }
?>