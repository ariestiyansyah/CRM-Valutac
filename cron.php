<?php
  /**
   * cron
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: cron.php, v3.00 2014-07-10 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  require_once("init.php");
?>
<?php
	$sql = "SELECT i.*," 
	. "\n p.title as ptitle, CONCAT(u.fname,' ',u.lname) as fullname, u.username, u.email" 
	. "\n FROM invoices as i" 
	. "\n LEFT JOIN projects as p ON p.id = i.project_id" 
	. "\n LEFT JOIN users as u ON u.id = i.client_id" 
	. "\n WHERE TO_DAYS(i.duedate) - TO_DAYS(NOW()) = '".(int)$core->invdays."'" 
	. "\n AND i.status <> 'Paid'" 
	. "\n AND i.onhold = 0"
	. "\n ORDER BY i.created";

	$userrow = $db->fetch_all($sql);

	require_once (BASEPATH . "lib/class_mailer.php");
	$mailer = Mailer::sendMail();
	$mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin(100,30));

	ob_start();
	require_once (BASEPATH . 'mailer/Invoice_Cron.tpl.php');
	$html_message = ob_get_contents();
	ob_end_clean();

	if (file_exists(UPLOADS . 'print_logo.png')) {
		$logo = '<img src="' . UPLOADURL . 'print_logo.png" alt="' . Registry::get("Core")->company . '" />';
	} elseif (Registry::get("Core")->logo) {
		$logo = '<img src="' . UPLOADURL . Registry::get("Core")->logo . '" alt="' . Registry::get("Core")->company . '" />';
	} else {
		$logo = Registry::get("Core")->company;
	}
			  			  	  			  
	$replacements = array();
	if ($userrow) {
		foreach ($userrow as $cols) {
			$replacements[$cols->email] = array(
			      '[LOGO]' => $logo, 
				  '[COMPANY]' => Registry::get("Core")->company, 
				  '[SITEURL]' => SITEURL, 
				  '[DATE]' => date('Y'), 
				  '[INVDAYS]' => Registry::get("Core")->invdays, 
				  '[PROJECTNAME]' => $cols->ptitle, 
				  '[INVNAME]' => $cols->title,
				  '[CLIENTNAME]' => Core::renderName($cols), 
				  '[AMOUNT]' => $core->formatClientCurrency($cols->amount_total - $cols->amount_paid, $user->currency),
				  '[METHOD]' => $cols->method,
				  '[DUEDATE]' => Filter::dodate("short_date", $cols->duedate)
			);
		}

		$decorator = new Swift_Plugins_DecoratorPlugin($replacements);
		$mailer->registerPlugin($decorator);

		$message = Swift_Message::newInstance()
				->setSubject(Lang::$word->INVC_CRONSUBJECT)
				->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->company))
				->setBody($html_message, 'text/html');

		foreach ($userrow as $row) {
			$message->setTo($row->email, Core::renderName($row));
			$mailer->send($message);
		}
		unset($row);


	}    
?>