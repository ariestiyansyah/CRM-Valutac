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
	  list($inv_id, $sesid) = explode("_", $_POST['custom']);
	  
      $mb_email = getValue("extra", "gateways", "name = 'moneybookers'");
	  $invrow = $db->first("SELECT * FROM invoices WHERE id = " . (int)$inv_id);
	  
	  if ($receiver_email == $mb_email && $invrow) {
			$edata = array(
				  'project_id' => $invrow->project_id, 
				  'invoice_id' => $invrow->id, 
				  'amount' => floatval($mc_gross), 
				  'created' => "NOW()", 
				  'method' => "MoneyBookers",
				  'description' => "Payment via MoneyBookers"
			);

			$db->insert("invoice_payments", $edata);

			$row = $db->first("SELECT SUM(amount) as amtotal FROM invoice_payments WHERE invoice_id = '" . $edata['invoice_id'] . "' GROUP BY invoice_id");
			
			$data['amount_paid'] = $row->amtotal;
			$pdata['b_status'] = $data['amount_paid'];

			$db->update("invoices", $data, "id='" . $edata['invoice_id'] . "'");
			$db->update(Content::pTable, $pdata, "id='" . $edata['project_id'] . "'");
			
			$row2 = $db->first("SELECT amount_total, amount_paid FROM invoices WHERE id = '" . $edata['invoice_id'] . "'");
			$idata['status'] = ($row2->amount_total == $row2->amount_paid) ? 'Paid' : 'Unpaid' ;
			$db->update("invoices", $idata, "id='" . $edata['invoice_id'] . "'");
	
			/* == Notify User == */
			require_once(BASEPATH . "lib/class_mailer.php");
			$mailer = Mailer::sendMail();
			
			$userdata = $db->first("SELECT i.*," 
			. "\n p.title as ptitle, CONCAT(u.fname,' ',u.lname) as fullname, u.username, u.email, u.address, u.city, u.zip, u.state, u.phone, u.company" 
			. "\n FROM invoices as i" 
			. "\n LEFT JOIN " . Content::pTable . " as p ON p.id = i.project_id" 
			. "\n LEFT JOIN " . Users::uTable . " as u ON u.id = i.client_id" 
			. "\n WHERE i.id = '" . $edata['invoice_id']. "'");

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
?>