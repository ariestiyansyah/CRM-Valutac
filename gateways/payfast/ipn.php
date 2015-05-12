<?php

  /**
   * PayFast IPN
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: ipn.php, 2014-08-30 21:12:05 gewa Exp $
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
	
		  $invrow = $db->first("SELECT * FROM invoices WHERE id = " . (int)$inv_id);
		  
		  $edata = array(
			  'project_id' => $invrow->project_id,
			  'invoice_id' => $invrow->id,
			  'amount' => floatval($mc_gross),
			  'recurring' => ($invrow->recurring) ? 1 : 0,
			  'created' => "NOW()",
			  'method' => "PayFast",
			  'description' => "Payment via PayFast"
			  );

		  $db->insert("invoice_payments", $edata);
		  
		  $row = $db->first("SELECT SUM(amount) as amtotal FROM invoice_payments WHERE invoice_id = {$invrow->id} GROUP BY invoice_id");
		  $db->update("invoices", array('amount_paid' => $row->amtotal), "id=" . $invrow->id);
		  $db->update(Content::pTable, array('b_status' => $row->amtotal), "id=" . $invrow->project_id);

		  $row2 = $db->first("SELECT amount_total, amount_paid FROM invoices WHERE id = " . $invrow->id );
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
		  . "\n WHERE i.id = " . $invrow->id);

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
		  
		  
		  pflog("Email Notification sent successfuly");

      }

  }

?>