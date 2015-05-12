<?php
  /**
   * Controller
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2010
   * @version $Id: controller.php, v1.00 2011-11-10 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  require_once("../init.php");

  if (!$user->logged_in)
      exit;
?>
<?php
  /* == Proccess User == */
  if (isset($_POST['processUser'])):
      if (intval($_POST['processUser']) == 0 || empty($_POST['processUser'])):
		exit;
	  endif;
      $user->updateProfile();
  endif;
?>
<?php
  /* == Proccess Submission == */
  if (isset($_POST['processSubmissionRecord'])):
      if (intval($_POST['processSubmissionRecord']) == 0 || empty($_POST['processSubmissionRecord'])):
          exit;
      endif;

      $id = intval($_POST['processSubmissionRecord']);
      $data = array(
			'status' => intval($_POST['status']), 
			'review' => sanitize($_POST['review']), 
			'reviewed' => intval($_POST['status']) == 3 ? 0 : 1,
			'review_date' => "NOW()"
	  );

      $db->update("submissions", $data, "id='" . (int)$id . "'");
      print ($db->affected()) ? Filter::msgOk(Lang::$word->FPRO_SUBSENTOK) : Filter::msgAlert(Lang::$word->NOPROCCESS);;

	  require_once (BASEPATH . "lib/class_mailer.php");
	  $row = $user->getSingleSubmissionsById($id );
	  $mailer = Mailer::sendMail();
	  $subject = Lang::$word->FPRO_SUBESUBJECT . cleanOut($row->title);

	  ob_start();
	  require_once (BASEPATH . 'mailer/Submission_From_Client.tpl.php');
	  $html_message = ob_get_contents();
	  ob_end_clean();

	  if (file_exists(UPLOADS . 'print_logo.png')) {
		  $logo = '<img src="' . UPLOADURL . 'print_logo.png" alt="' . Registry::get("Core")->company . '" style="border:0"/>';
	  } elseif (Registry::get("Core")->logo) {
		  $logo = '<img src="' . UPLOADURL . Registry::get("Core")->logo . '" alt="' . Registry::get("Core")->company . '" style="border:0"/>';
	  } else {
		  $logo = Registry::get("Core")->company;
	  }

	  $body = str_replace(array(
		  '[LOGO]',
		  '[STAFF_NAME]',
		  '[PTITLE]',
		  '[STITLE]',
		  '[NAME]',
		  '[CONTENT]',
		  '[DATE]',
		  '[COMPANY]',
		  '[SITEURL]'), array(
		  $logo,
		  Core::renderName($row),
		  $row->ptitle,
		  $row->title,
		  Core::renderName($user),
		  cleanOut($data['review']),
		  date('Y'),
		  Registry::get("Core")->company,
		  SITEURL), $html_message);
				  
	  $msg = Swift_Message::newInstance()
			  ->setSubject($subject)
			  ->setTo(array($row->email => Core::renderName($row)))
			  ->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->company))
			  ->setBody($body, 'text/html');

	  $mailer->send($msg);

  endif;
?>
<?php
  /* == Proccess Project File == */
  if (isset($_POST['processProjectFile'])):
      if (intval($_POST['processProjectFile']) == 0 || empty($_POST['processProjectFile'])):
          die();
      endif;
      $user->processProjectFile();
  endif;
?>
<?php
  /* == Proccess Contact Request == */
  if (isset($_POST['processContact'])):
      if (intval($_POST['processContact']) == 0 || empty($_POST['processContact'])):
          exit;
      endif;

      Filter::checkPost('msgsubject', Lang::$word->FMSG_MSGERR1);
      Filter::checkPost('message', Lang::$word->FMSG_MSGERR2);

	  $upl = Uploader::instance($core->file_max, $core->file_types);
	  if (!empty($_FILES['attachment']['name']) and empty(Filter::$msgs)) {
		  $dir = UPLOADS . 'tempfiles/';
		  $upl->upload('attachment', $dir, "ATT_");
	  }
		   
      if (empty(Filter::$msgs)) {
		  if (Filter::$id) {
			  $data = array(
				  'uid1' => Filter::$id,
				  'uid2' => intval($_POST['uid2']),
				  'msgsubject' => "",
				  'user1' => Registry::get("Users")->uid,
				  'user2' => 0,
				  'body' => $_POST['message'],
				  'attachment' => "",
				  'created' => "NOW()",
				  'user1read' => "yes",
				  'user2read' => "no",
				  );
			  $db->insert("messages", $data);

			  $data2 = array('user' . intval($_POST['userp']) . 'read' => "no");
			  $db->update("messages", $data2, "uid1='" . Filter::$id . "' AND uid2 = '1'");
			  
			  $sql = "SELECT email, CONCAT(fname,' ',lname) as fullname, username FROM " . Users::uTable . " WHERE id = " . (int)$_POST['recipient'];
			  $row = $db->first($sql);

			  require_once (BASEPATH . "lib/class_mailer.php");
			  $mailer = Mailer::sendMail();
			  $subject = Lang::$word->MSG_ESUBJECT . cleanOut($_POST['msgsubject']);
	
			  ob_start();
			  require_once (BASEPATH . 'mailer/Reply_Message_From_Client.tpl.php');
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
				  '[STAFF_NAME]',
				  '[NAME]',
				  '[MSG]',
				  '[ATTACHMENT]',
				  '[DATE]',
				  '[COMPANY]',
				  '[SITEURL]'), array(
				  $logo,
				  Core::renderName($row),
				  Core::renderName($user),
				  cleanOut($_POST['message']),
				  (!empty($data['attachment'])) ? '<a href="' . UPLOADURL . 'tempfiles/' .$data['attachment'] . '">' . Lang::$word->FORM_DOWNLOAD . '</a>' : null,
				  date('Y'),
				  Registry::get("Core")->company,
				  SITEURL), $html_message);
					  
			  $msg = Swift_Message::newInstance()
					  ->setSubject($subject)
					  ->setTo(array($row->email => Core::renderName($row)))
					  ->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->company))
					  ->setBody($body, 'text/html');
	

			if ($db->affected() and $mailer->send($msg)) {
				$json['status'] = 'success';
				$json['message'] = Filter::msgOk(Lang::$word->FMSG_MSGOK, false);
			} else {
				$json['status'] = 'success';
				$json['message'] = Filter::msgError(Lang::$word->NOPROCCESS, false);
			}
			print json_encode($json);

		  } else {
			  $single = $db->first("SELECT COUNT(id) as recip, id as recipid, (SELECT COUNT(*) FROM messages) as newmsg FROM users where id='" . intval($_POST['recipient']) . "'");
			  $data = array(
				  'uid1' => intval($single->newmsg + 1),
				  'uid2' => 1,
				  'msgsubject' => sanitize($_POST['msgsubject']),
				  'user1' => Registry::get("Users")->uid,
				  'user2' => intval($single->recipid),
				  'body' => $_POST['message'],
				  'attachment' => !empty($_FILES['attachment']['name']) ? $upl->fileInfo['fname'] : "",
				  'created' => "NOW()",
				  'user1read' => "yes",
				  'user2read' => "no",
				  );
				  
			  $db->insert("messages", $data);
			  
			  $sql = "SELECT email, CONCAT(fname,' ',lname) as fullname, username FROM users WHERE id = " . (int)$_POST['recipient'];
			  $row = $db->first($sql);

			  require_once (BASEPATH . "lib/class_mailer.php");
			  $mailer = Mailer::sendMail();
			  $subject = Lang::$word->MSG_ESUBJECT . cleanOut($_POST['msgsubject']);
	
			  ob_start();
			  require_once (BASEPATH . 'mailer/Reply_Message_From_Client.tpl.php');
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
				  '[STAFF_NAME]',
				  '[NAME]',
				  '[MSG]',
				  '[ATTACHMENT]',
				  '[DATE]',
				  '[COMPANY]',
				  '[SITEURL]'), array(
				  $logo,
				  Core::renderName($row),
				  Core::renderName($user),
				  cleanOut($_POST['message']),
				  (!empty($data['attachment'])) ? '<a href="' . UPLOADURL . 'tempfiles/' .$data['attachment'] . '">' . Lang::$word->FORM_DOWNLOAD . '</a>' : null,
				  date('Y'),
				  Registry::get("Core")->company,
				  SITEURL), $html_message);
				  
			  $msg = Swift_Message::newInstance()
					  ->setSubject($subject)
					  ->setTo(array($row->email => Core::renderName($row)))
					  ->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->company))
					  ->setBody($body, 'text/html');

			if ($db->affected() and $mailer->send($msg)) {
				$json['status'] = 'success';
				$json['message'] = Filter::msgOk(Lang::$word->FMSG_MSGOK, false);
			} else {
				$json['status'] = 'success';
				$json['message'] = Filter::msgError(Lang::$word->NOPROCCESS, false);
			}
			print json_encode($json);
		  }

	  } else {
		  $json['message'] = Filter::msgStatus();
		  print json_encode($json);
	  }
  endif;

  /* == Get Messages == */
  if (isset($_GET['getMessages']) and Filter::$id):

      $single = $content->getMessageById();
      if ($single->user1 == $user->uid or $single->user2 == $user->uid):
          $userp = $content->updateMessageStatus($single->user1);
          $msgdata = $content->renderMessages();

          $html ='';
          $html .= '
		  <div class="wojo divided list">';
				foreach ($msgdata as $row):
					$html .= '<div class="item">';
					
					if ($row->attachment):
						$html .= '<div class="right floated teal wojo label"><i class="icon download cloud"></i> 
						<a href="' . UPLOADURL . 'tempfiles/' . $row->attachment . '">' . Lang::$word->FORM_DOWNLOAD . '</a></div>';
					endif;
					$html .= '<img src="' . UPLOADURL . 'avatars/';
					$html .= ($row->avatar) ? $row->avatar : "blank.png";
					$html .= '" alt="" class="wojo image avatar">';
					
					$html .= '<div class="content">';
					$html .= '<div class="header">';
					if($user->uid == $row->userid) :
						$html .= '<span class="wojo label positive">' . Lang::$word->FROM . ': ' . Lang::$word->YOU . '</span>';
				    else :
						$html .= '<span class="wojo label info">' . Lang::$word->FROM . ': ' . Core::renderName($row) . '</span>';
					endif;
					$html .= '</div>';
					$html .= cleanOut($row->body) . ' | <small>' . Filter::dodate("long_date", $row->created) . '</small> ';
					$html .= '</div>';
					
		  $html .= '</div>';
          endforeach;
          unset($row);
          $html .= '</div>';
		  $html .= '<div class="wojo double fitted divider"></div>';
		  $html .= '<div class="clearfix">';
		  $html .= '<a href="account.php?do=contact&amp;action=view&amp;id=' . intval($_GET['mid']) . '" class="wojo basic button">' . Lang::$word->REPLY . '</a>';
		  $html .= '<a class="delmessage push-right" data-extra="' . intval($_GET['mid']) . '" data-id="' . intval($_GET['did']) . '"><i class="rounded inverted danger icon remove link"></i></a>';
		  $html .= '</div>';

      else:
          $html .= Filter::msgSingleInfo(Lang::$word->MSG_NOMSG2, false);
      endif;
	  
	  $json['message'] = $html;
	  print json_encode($json);

  endif;
  
  /* == Delete Message == */
  if (isset($_POST['delete']) and $_POST['delete'] == "deleteMessage"):

	  $uid = intval($_POST['extra']);
	  
	  $res = $db->delete("messages", "id=" . Filter::$id);
	  $db->delete("messages", "uid1=" . $uid);
	  
	  if($res) :
		  $json['type'] = 'success';
		  $json['title'] = Lang::$word->SUCCESS;
		  $json['message'] =  Lang::$word->MSG_DELETE_OK;
		  else :
		  $json['type'] = 'warning';
		  $json['title'] = Lang::$word->ALERT;
		  $json['message'] = Lang::$word->NOPROCCESS;
	  endif;
  
	  print json_encode($json);

  endif;
?>
<?php
  /* == Proccess Client Credit == */
  if (isset($_POST['docredit'])):
      if (intval($_POST['docredit']) == 0 || empty($_POST['docredit'])):
          exit;
      endif;

      $invrow = $user->getInvoiceById(intval($_POST['invid']));
      if ($invrow):
          $amount = $invrow->amount_total - $invrow->amount_paid;
          $credit = getValue("credit", "users", "id = " . $user->uid);

          if (number_format($credit, 2, '.', '') >= number_format($amount, 2, '.', '')):
              $edata = array(
                  'project_id' => $invrow->project_id,
                  'invoice_id' => $invrow->id,
                  'amount' => floatval($amount),
                  'recurring' => ($invrow->recurring) ? 1 : 0,
                  'created' => "NOW()",
                  'method' => "Credit",
                  'description' => "Payment via Available Credit");

              $db->insert(Content::ipTable, $edata);
              $row = $db->first("SELECT SUM(amount) as amtotal FROM " . Content::ipTable . " WHERE invoice_id = '" . $edata['invoice_id'] . "' GROUP BY invoice_id");

              $data['amount_paid'] = $row->amtotal;
              $pdata['b_status'] = $data['amount_paid'];

              $db->update(Content::iTable, $data, "id=" . $edata['invoice_id']);
              $db->update(Content::pTable, $pdata, "id=" . $edata['project_id']);

              $row2 = $db->first("SELECT amount_total, amount_paid FROM " . Content::iTable . " WHERE id = " . $edata['invoice_id']);
              $idata['status'] = ($row2->amount_total == $row2->amount_paid) ? 'Paid' : 'Unpaid';
              $db->update(Content::iTable, $idata, "id=" . $edata['invoice_id']);

			  $udata['credit'] = number_format($credit, 2, '.', '') - number_format($amount, 2, '.', '');
			  $db->update("users", $udata, "id='" . $user->uid . "'");
			  
              $json['type'] = 'success';
              $json['info'] = 'full';
			  $json['paid'] = $amount;
              $json['credit'] = number_format($credit, 2, '.', '') - number_format($amount, 2, '.', '');
			  $json['message'] = Filter::msgOk(Lang::$word->FBILL_PAYINFULL, false);
              print json_encode($json);

          else:
              $edata = array(
                  'project_id' => $invrow->project_id,
                  'invoice_id' => $invrow->id,
                  'amount' => floatval($credit),
                  'recurring' => ($invrow->recurring) ? 1 : 0,
                  'created' => "NOW()",
                  'method' => "Credit",
                  'description' => "Payment via Available Credit");
				  
              $db->insert(Content::ipTable, $edata);
              $row = $db->first("SELECT SUM(amount) as amtotal FROM " . Content::ipTable . " WHERE invoice_id = '" . $edata['invoice_id'] . "' GROUP BY invoice_id");

              $data['amount_paid'] = $row->amtotal;
              $pdata['b_status'] = $data['amount_paid'];

              $db->update(Content::iTable, $data, "id='" . $edata['invoice_id'] . "'");
              $db->update(Content::pTable, $pdata, "id='" . $edata['project_id'] . "'");

              $row2 = $db->first("SELECT amount_total, amount_paid FROM " . Content::iTable . " WHERE id = " . $edata['invoice_id']);
              $idata['status'] = ($row2->amount_total == $row2->amount_paid) ? 'Paid' : 'Unpaid';
              $db->update(Content::iTable, $idata, "id='" . $edata['invoice_id'] . "'");
			  
			  $udata['credit'] = 0.00;
			  $db->update("users", $udata, "id=" . $user->uid);
			  
			  $total = $user->getInvoiceById(intval($_POST['invid']));

              $json['type'] = 'success';
              $json['info'] = 'partial';
			  $json['paid'] = number_format($total->amount_paid,2);
              $json['invpending'] = number_format($total->amount_total, 2) - number_format($total->amount_paid, 2);
			  $json['message'] = Filter::msgOk(Lang::$word->FBILL_PAYPARTIAL, false);
              print json_encode($json);

          endif;

      else:
	      $json['type'] = 'error';
		  $json['message'] = 'invalid invoice id';
          print json_encode($json);
      endif;

  endif;
?>
<?php
  /* == Load Gateways == */
  if (isset($_POST['loadgateway'])):
      if (intval($_POST['loadgateway']) == 0 || empty($_POST['loadgateway'])):
          die();
      endif;
	  $gate_id = intval($_POST['loadgateway']);
	  $inv_id = intval($_POST['invoice_id']);
	  $amount = floatval($_POST['amount']);
	  $pamount = floatval($_POST['pamount']);
	  if ($amount == 0 or (empty($amount)) or $amount > $pamount) {
		  print Filter::msgError(Lang::$word->FBILL_ERR1);
	  } else {
		  if ($gate_id == 100) {
			  print '<div class="wojo info message">' . cleanOut($core->offline_info) . '<div>';
		  } else {
			  $row = $core->getRowById("gateways", $gate_id, false, false);
			  $row2 = $user->getInvoiceById($inv_id);

			  $form_url = BASEPATH . "gateways/" . $row->dir . "/form.tpl.php";
			  (file_exists($form_url)) ? include ($form_url) : Filter::msgError(Lang::$word->FBILL_ERR2);
		  }
	  }
  endif;
?>
<?php
  /* == View Task Data == */
  if (isset($_POST['viewTaskData'])):
      if (intval($_POST['viewTaskData']) == 0 || empty($_POST['viewTaskData'])):
          exit;
      endif;
      $tid = intval($_POST['tid']); 
      $row = $user->getTaskByProjectId($tid);
	  print '<div style="max-width:450px">' . cleanOut($row->details) . '</div>';
  endif;
?>
<?php
  /* == View Filedescription Data == */
  if (isset($_POST['viewFileDescData'])):
      if (intval($_POST['viewFileDescData']) == 0 || empty($_POST['viewFileDescData'])):
          exit;
      endif;
      $details = getValueById("filedesc", "project_files", Filter::$id);
	  print '<p style="max-width:450px">' . cleanOut($details) . '</p>';
  endif;
?>
<?php
  /* == Reply Support Ticket == */
  if (isset($_POST['replySupportTicket'])):
      if (intval($_POST['replySupportTicket']) == 0 || empty($_POST['replySupportTicket'])):
          exit;
      endif;
      $user->replySupportTicket();
  endif;
    
  /* == Load Support Ticket == */
  if (isset($_POST['loadReplyEntries'])):
      $resrow = $content->getResponseByTicketId();
      $html ='';
      if ($resrow):
          foreach ($resrow as $trow):
              $class = ($trow->user_type == "client") ? 'warning' : 'purple';
              $html .= '<div class="wojo notice black message">';
              $html .= '
      <span class="wojo small positive label">' . Filter::dodate("long_date", $trow->created) . '</span> <span class="wojo small ' . $class. '  label">' . Lang::$word->AUTHOR . ': ' . $trow->name. '  (' . $trow->user_type . ')</span>';
              $html .= '
				<div>' . cleanOut($trow->body) . '</div>
			  </div>';
          endforeach;
      print $html;
      endif;
  endif;
        
        
  /* == Close Ticket Status == */
  if (isset($_POST['closeTicket'])):
      if (intval($_POST['closeTicket']) == 0 || empty($_POST['closeTicket'])):
          exit;
      endif;
	  print 'ok';
      $id = intval($_POST['closeTicket']); 
	  $data['status'] = 'Closed';
      $db->update("support_tickets", $data, "id = " . $id);
  endif;
  
  /* == Proccess Ticket == */
  if (isset($_POST['processSupportTicket'])):
      if (intval($_POST['processSupportTicket']) == 0 || empty($_POST['processSupportTicket'])):
          exit;
      endif;
      $user->processSupportTicket();
  endif;
?>
<?php
  /* == Make Pdf == */
  if (isset($_GET['dopdf'])):
      if (intval($_GET['dopdf']) == 0 || empty($_GET['dopdf'])):
          exit;
      endif;

	  Filter::$id = intval($_GET['dopdf']);
	  $title = cleanOut(preg_replace("/[^a-zA-Z0-9\s]/", "", $_GET['title']));
	  ob_start();
	  require_once(BASEPATH . 'print_pdf.php');
	  $pdf_html = ob_get_contents();
	  ob_end_clean();
	  
	  require_once(BASEPATH . 'lib/mPdf/mpdf.php');
	  $mpdf=new mPDF('utf-8', $core->pagesize);
	  $mpdf->SetTitle($title);
	  $mpdf->SetAutoFont();
	  $mpdf->WriteHTML($pdf_html);
	  $mpdf->Output($title . ".pdf", "D");
	  exit;
	  
  endif;
?>
<?php
  /* == Client Invoices == */
  if (isset($_GET['getaInvoices'])):
  
      $data = array();
      $data['order'] = array();
      $data['xaxis'] = array();
	  
      $data['order']['label'] = Lang::$word->FBILL_SUB5;

      $query = $db->fetch_all("SELECT SUM(amount_total) as total, YEAR(created) as year FROM " . Content::iTable
	  . "\n WHERE client_id = " . $user->uid 
	  . "\n AND status ='Paid'" 
	  . "\n AND onhold = 0" 
	  . "\n GROUP BY YEAR(created)");

      foreach ($query as $i => $row):
          $i++;
          ($query) ? $data['order']['data'][] = array($i, $row->total) : $data['order']['data'][] = array($i, 0);
          $data['xaxis'][] = array($i, $row->year);
      endforeach;

      print json_encode($data);

  endif;
  
  if (isset($_GET['getpInvoices'])):

      $data = array();
      $data['order'] = array();
      $data['xaxis'] = array();
      $data['order']['label'] = Lang::$word->FDASH_SUB1;

      $query = $db->fetch_all("SELECT amount_total as total, DATE(duedate) as date FROM " . Content::iTable
	  . "\n WHERE client_id = " . $user->uid 
	  . "\n AND status <>'Paid'" 
	  . "\n AND onhold = 0");

      foreach ($query as $i => $row):
          $i++;
          ($query) ? $data['order']['data'][] = array($i, $row->total) : $data['order']['data'][] = array($i, 0);
          $data['xaxis'][] = array($i, $row->date);
      endforeach;

      print json_encode($data);

  endif;
?>