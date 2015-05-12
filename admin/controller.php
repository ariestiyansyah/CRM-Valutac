<?php
  /**
   * Controller
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: controller.php, v3.00 2014-05-08 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  
  require_once("init.php");
  if (!$user->is_Admin())
    redirect_to("login.php");

  $delete = (isset($_POST['delete']))  ? $_POST['delete'] : null;
?>
<?php
  switch ($delete):
  /* == Delete User == */
  case "deleteUser":
	if (Filter::$id == 1):
		$json['type'] = 'error';
		$json['title'] = Lang::$word->ERROR;
		$json['message'] = Lang::$word->STAFF_DELUSER_ERR1;
	else:
		if($avatar = getValueById("avatar", Users::uTable, Filter::$id)):
			unlink(UPLOADS . 'avatars/' . $avatar);
		endif;
		$res = $db->delete(Users::uTable, "id=" . Filter::$id);
		$db->delete(Content::pTable, "client_id=" . Filter::$id);

		if ($projects = $db->fetch_all("SELECT id FROM " . Content::pTable . " WHERE client_id = " . Filter::$id)):
			foreach ($projects as $row):
				$db->delete(Content::idTable, "project_id=" . $row->id);
				$db->delete(Content::ipTable, "project_id=" . $row->id);
				$db->delete(Content::iTable , "project_id=" . $row->id);
				$db->delete("project_files", "project_id=" . $row->id);
				$db->delete(Content::sTable, "project_id=" . $row->id);
				$db->delete(Content::tTable, "project_id=" . $row->id);
				$db->delete(Content::tbTable, "project_id=" . $row->id);
			endforeach;
		endif;

		$title = sanitize($_POST['title']);
		if($res) :
			$json['type'] = 'success';
			$json['title'] = Lang::$word->SUCCESS;
			$json['message'] =  str_replace("[USERNAME]", $title, Lang::$word->STAFF_DELUSER_OK);
			else :
			$json['type'] = 'warning';
			$json['title'] = Lang::$word->ALERT;
			$json['message'] = Lang::$word->NOPROCCESS;
		endif;
	endif;
		print json_encode($json);
  break;

  /* == Delete Project == */
  case "deleteProject":
	if($filename = getValueById("filename", "project_files", Filter::$id)):
		unlink(UPLOADS . 'avatars/' . $filename);
	endif;
	$res = $db->delete(Content::pTable, "id=" . Filter::$id);
	$db->delete("permissions", "project_id=" . Filter::$id);
	$db->delete(Content::tTable, "project_id=" . Filter::$id);
	$db->delete(Content::sTable, "project_id=" . Filter::$id);
	$db->delete(Content::iTable , "project_id=" . Filter::$id);
	$db->delete(Content::idTable, "project_id=" . Filter::$id);
	$db->delete(Content::ipTable, "project_id=" . Filter::$id);
	$db->delete(Content::tbTable, "project_id=" . Filter::$id);
	$db->delete("project_files", "project_id=" . Filter::$id);

	$title = sanitize($_POST['title']);
	if($res) :
		$json['type'] = 'success';
		$json['title'] = Lang::$word->SUCCESS;
		$json['message'] =  str_replace("[PROJECT]", $title, Lang::$word->PROJ_DELETE_OK);
		else :
		$json['type'] = 'warning';
		$json['title'] = Lang::$word->ALERT;
		$json['message'] = Lang::$word->NOPROCCESS;
	endif;

	print json_encode($json);
  break;

  /* == Delete Project Type == */
  case "deleteProjectType":
	$res = $db->delete("project_types", "id=" . Filter::$id);
	$title = sanitize($_POST['title']);
	if($res) :
		$json['type'] = 'success';
		$json['title'] = Lang::$word->SUCCESS;
		$json['message'] =  str_replace("[TYPE]", $title, Lang::$word->TYPE_DELTYPE_OK);
		else :
		$json['type'] = 'warning';
		$json['title'] = Lang::$word->ALERT;
		$json['message'] = Lang::$word->NOPROCCESS;
	endif;

	print json_encode($json);
  break;

  /* == Delete Project Task == */
  case "deleteProjectTask":
	$res = $db->delete(Content::tTable, "id=" . Filter::$id);
	$title = sanitize($_POST['title']);
	if($res) :
		$json['type'] = 'success';
		$json['title'] = Lang::$word->SUCCESS;
		$json['message'] =  str_replace("[TASK]", $title, Lang::$word->TASK_DELTASK_OK);
		else :
		$json['type'] = 'warning';
		$json['title'] = Lang::$word->ALERT;
		$json['message'] = Lang::$word->NOPROCCESS;
	endif;

	print json_encode($json);
  break;
  
  /* == Delete Task Template == */
  case "deleteTaskTemplate":
	$res = $db->delete("task_templates", "id=" . Filter::$id);
	$title = sanitize($_POST['title']);
	if($res) :
		$json['type'] = 'success';
		$json['title'] = Lang::$word->SUCCESS;
		$json['message'] =  str_replace("[TEMPLATE]", $title, Lang::$word->TTASK_DELTEMPLATE_OK);
		else :
		$json['type'] = 'warning';
		$json['title'] = Lang::$word->ALERT;
		$json['message'] = Lang::$word->NOPROCCESS;
	endif;

	print json_encode($json);
  break;

  /* == Delete Project File == */
  case "deleteProjectFile":
	if($filename = getValueById("filename", "project_files", Filter::$id)):
		unlink(UPLOADS . 'data/' . $filename);
	endif;
	$res = $db->delete("project_files", "id=" . Filter::$id);
	$title = sanitize($_POST['title']);
	if($res) :
		$json['type'] = 'success';
		$json['title'] = Lang::$word->SUCCESS;
		$json['message'] =  str_replace("[FILE]", $title, Lang::$word->FILE_DELFILE_OK);
		else :
		$json['type'] = 'warning';
		$json['title'] = Lang::$word->ALERT;
		$json['message'] = Lang::$word->NOPROCCESS;
	endif;

	print json_encode($json);
  break;

  /* == Delete Project Submission == */
  case "deleteSubmission":
	$res = $db->delete(Content::sTable, "id=" . Filter::$id);
	$title = sanitize($_POST['title']);
	if($res) :
		$json['type'] = 'success';
		$json['title'] = Lang::$word->SUCCESS;
		$json['message'] =  str_replace("[SUBMISSION]", $title, Lang::$word->SUBS_DELETE_OK);
		else :
		$json['type'] = 'warning';
		$json['title'] = Lang::$word->ALERT;
		$json['message'] = Lang::$word->NOPROCCESS;
	endif;

	print json_encode($json);
  break;

  /* == Delete Custom Field == */
  case "deleteField":
	$res = $db->delete(Content::cfTable, "id=" . Filter::$id);
	$title = sanitize($_POST['title']);
	if($res) :
		$json['type'] = 'success';
		$json['title'] = Lang::$word->SUCCESS;
		$json['message'] =  str_replace("[NAME]", $title, Lang::$word->CUSF_DELETE_OK);
		else :
		$json['type'] = 'warning';
		$json['title'] = Lang::$word->ALERT;
		$json['message'] = Lang::$word->NOPROCCESS;
	endif;

	print json_encode($json);
  break;

  /* == Delete News == */
  case "deleteNews":
	$res = $db->delete(Content::nTable, "id=" . Filter::$id);
	$title = sanitize($_POST['title']);
	if($res) :
		$json['type'] = 'success';
		$json['title'] = Lang::$word->SUCCESS;
		$json['message'] =  str_replace("[NEWS]", $title, Lang::$word->NEWS_DELETE_OK);
		else :
		$json['type'] = 'warning';
		$json['title'] = Lang::$word->ALERT;
		$json['message'] = Lang::$word->NOPROCCESS;
	endif;

	print json_encode($json);
  break;

  /* == Delete Backup == */
  case "deleteBackup":
      $title = sanitize($_POST['title']);
	  $action = false;

	  if(file_exists(BASEPATH . 'admin/backups/'.sanitize($_POST['file']))) :
		$action = unlink(BASEPATH . 'admin/backups/'.sanitize($_POST['file']));
	  endif;
				  
	  if($action) :
		  $json['type'] = 'success';
		  $json['title'] = Lang::$word->SUCCESS;
		  $json['message'] = str_replace("[DBNAME]", $title, Lang::$word->DB_DELETE_OK);
	  else :
		$json['type'] = 'warning';
		$json['title'] = Lang::$word->ALERT;
		$json['message'] = Lang::$word->NOPROCCESS;
	  endif;
	  print json_encode($json);
   break;

  /* == Delete Invoice Record == */
  case "deleteInvoiceRecord":
	list($project_id, $invoice_id) = explode(':', $_POST['extra']);

	$res = $db->delete(Content::ipTable, "id=" . Filter::$id);
	$row = $db->first("SELECT SUM(amount) as amtotal FROM " . Content::ipTable . " WHERE invoice_id = " . (int)$invoice_id . " GROUP BY invoice_id");

	$idata['amount_paid'] = ($row) ? $row->amtotal : 0.00;
	$idata['status'] = 'Unpaid';
	$pdata['b_status'] = ($row) ? $row->amtotal : 0.00;

	$db->update(Content::iTable , $idata, "id=" . (int)$invoice_id);
	$db->update(Content::pTable, $pdata, "id=" . (int)$project_id);
	
	$title = sanitize($_POST['title']);
	if($res) :
		$json['type'] = 'success';
		$json['title'] = Lang::$word->SUCCESS;
		$json['message'] =  str_replace("[RECORD]", $title, Lang::$word->INVC_DELRECORD_OK);
		else :
		$json['type'] = 'warning';
		$json['title'] = Lang::$word->ALERT;
		$json['message'] = Lang::$word->NOPROCCESS;
	endif;

	print json_encode($json);
   break;
   
  /* == Delete Invoice == */
  case "deleteInvoice":
	$pid = intval($_POST['extra']);
  
	$res = $db->delete(Content::iTable , "id=" . Filter::$id);
	$db->delete(Content::ipTable, "invoice_id=" . Filter::$id);
	$db->delete(Content::idTable, "invoice_id=" . Filter::$id);
	$row = $db->first("SELECT SUM(amount_paid) as amtotal FROM invoices WHERE project_id = $pid GROUP BY project_id");
	
	$pdata['b_status'] = ($row) ? $row->amtotal : 0.00;
	$db->update(Content::pTable, $pdata, "id=" . $pid);
  
	$title = sanitize($_POST['title']);
	if($res) :
		$json['type'] = 'success';
		$json['title'] = Lang::$word->SUCCESS;
		$json['message'] =  str_replace("[INVOICE]", $title, Lang::$word->INVC_DELETEINV_OK);
		else :
		$json['type'] = 'warning';
		$json['title'] = Lang::$word->ALERT;
		$json['message'] = Lang::$word->NOPROCCESS;
	endif;

	print json_encode($json);
   break;

  /* == Delete Invoice Entry == */
  case "deleteInvoiceEntry":
	list($project_id, $invoice_id) = explode(':', $_POST['extra']);
  
	$res = $db->delete(Content::idTable, "id=" . Filter::$id);
	$row = $db->first("SELECT SUM(amount) as amtotal, SUM(tax) as itax FROM invoice_data WHERE invoice_id = '" . (int)$invoice_id . "' GROUP BY invoice_id");

	$data = array(
		  'amount_total' => ($row) ? $row->amtotal + $row->itax : 0.00, 
		  'tax' => ($row) ? $row->itax : 0.00
	);
	$pdata['cost'] = $data['amount_total'];
	$title = sanitize($_POST['title']);

	$db->update(Content::iTable , $data, "id=" . (int)$invoice_id);
	$db->update(Content::pTable, $pdata, "id=" . (int)$project_id);
  
	$title = sanitize($_POST['title']);
	if($res) :
		$json['type'] = 'success';
		$json['title'] = Lang::$word->SUCCESS;
		$json['message'] =  str_replace("[ENTRY]", $title, Lang::$word->INVC_DELENTRY_OK);
		else :
		$json['type'] = 'warning';
		$json['title'] = Lang::$word->ALERT;
		$json['message'] = Lang::$word->NOPROCCESS;
	endif;

	print json_encode($json);
   break;

  /* == Delete Time Billing Record == */
  case "deleteTimeBillingRecord":
	$res = $db->delete(Content::tbTable, "id=" . Filter::$id);
	
	$title = sanitize($_POST['title']);
	if($res) :
		$json['type'] = 'success';
		$json['title'] = Lang::$word->SUCCESS;
		$json['message'] =  str_replace("[TIMEBILL]", $title, Lang::$word->BILL_DELETE_OK);
		else :
		$json['type'] = 'warning';
		$json['title'] = Lang::$word->ALERT;
		$json['message'] = Lang::$word->NOPROCCESS;
	endif;

	print json_encode($json);
   break;

  /* == Delete Time Billing == */
  case "deleteTimeBilling":
	$res = $db->delete(Content::tbTable, "project_id=" . Filter::$id);
	
	$title = sanitize($_POST['title']);
	if($res) :
		$json['type'] = 'success';
		$json['title'] = Lang::$word->SUCCESS;
		$json['message'] =  str_replace("[TIMEBILL]", $title, Lang::$word->BILL_DELETE_OK);
		else :
		$json['type'] = 'warning';
		$json['title'] = Lang::$word->ALERT;
		$json['message'] = Lang::$word->NOPROCCESS;
	endif;

	print json_encode($json);
   break;

  /* == Delete Quote == */
  case "deleteQuote":
	$res = $db->delete("quotes", "id=" . Filter::$id);
	$db->delete("quotes_data", "quote_id=" . Filter::$id);
	
	$title = sanitize($_POST['title']);
	if($res) :
		$json['type'] = 'success';
		$json['title'] = Lang::$word->SUCCESS;
		$json['message'] =  str_replace("[QUOTE]", $title, Lang::$word->QUTS_DELETEINV_OK);
		else :
		$json['type'] = 'warning';
		$json['title'] = Lang::$word->ALERT;
		$json['message'] = Lang::$word->NOPROCCESS;
	endif;

	print json_encode($json);
   break;

  /* == Delete Message == */
  case "deleteMessage":
    $uid = intval($_POST['extra']);
	
	$res = $db->delete("messages", "id=" . Filter::$id);
	$db->delete("messages", "uid1=" . $uid);
	
	$title = sanitize($_POST['title']);
	if($res) :
		$json['type'] = 'success';
		$json['title'] = Lang::$word->SUCCESS;
		$json['message'] =  str_replace("[MESSAGE]", $title, Lang::$word->MSG_DELETE_OK);
		else :
		$json['type'] = 'warning';
		$json['title'] = Lang::$word->ALERT;
		$json['message'] = Lang::$word->NOPROCCESS;
	endif;

	print json_encode($json);
   break;

  /* == Delete Support Ticket == */
  case "deleteSupportTicket":
    $uid = intval($_POST['extra']);
	
	$res = $db->delete("support_tickets", "id=" . Filter::$id);
	$db->delete("support_responses", "ticket_id=" . Filter::$id);
	
	$title = sanitize($_POST['title']);
	if($res) :
		$json['type'] = 'success';
		$json['title'] = Lang::$word->SUCCESS;
		$json['message'] =  str_replace("[TICKET]", $title, Lang::$word->SUP_DELTICKET_OK);
		else :
		$json['type'] = 'warning';
		$json['title'] = Lang::$word->ALERT;
		$json['message'] = Lang::$word->NOPROCCESS;
	endif;

	print json_encode($json);
   break;

  /* == Delete Support Reply == */
  case "deleteSupportReply":	
	$res = $db->delete("support_responses", "id=" . Filter::$id);
	
	$title = sanitize($_POST['title']);
	if($res) :
		$json['type'] = 'success';
		$json['title'] = Lang::$word->SUCCESS;
		$json['message'] =  str_replace("[REPLY]", $title, Lang::$word->SUP_DELETE_OK);
		else :
		$json['type'] = 'warning';
		$json['title'] = Lang::$word->ALERT;
		$json['message'] = Lang::$word->NOPROCCESS;
	endif;

	print json_encode($json);
   break;

  /* == Delete Visual Form == */
  case "deleteVisualForm":	
	$res = $db->delete("forms", "id=" . Filter::$id);
	$db->delete("forms_data", "form_id=" . Filter::$id);
	
	$title = sanitize($_POST['title']);
	if($res) :
		$json['type'] = 'success';
		$json['title'] = Lang::$word->SUCCESS;
		$json['message'] =  str_replace("[FORM]", $title, Lang::$word->FORM_DELFORM_OK);
		else :
		$json['type'] = 'warning';
		$json['title'] = Lang::$word->ALERT;
		$json['message'] = Lang::$word->NOPROCCESS;
	endif;

	print json_encode($json);
   break;

  /* == Delete Visual Form Data == */
  case "deleteFormData":	
	$res = $db->delete("forms_data", "id=" . Filter::$id);
	
	$title = sanitize($_POST['title']);
	if($res) :
		$json['type'] = 'success';
		$json['title'] = Lang::$word->SUCCESS;
		$json['message'] =  str_replace("[FORMDATA]", $title, Lang::$word->FORM_DELDATA_OK);
		else :
		$json['type'] = 'warning';
		$json['title'] = Lang::$word->ALERT;
		$json['message'] = Lang::$word->NOPROCCESS;
	endif;

	print json_encode($json);
   break;

  /* == Delete Estimator Form == */
  case "deleteEstimator":	
	$res = $db->delete("estimator", "id=" . Filter::$id);
	$db->delete("estimator_data", "form_id=" . Filter::$id);
	
	$title = sanitize($_POST['title']);
	if($res) :
		$json['type'] = 'success';
		$json['title'] = Lang::$word->SUCCESS;
		$json['message'] =  str_replace("[FORM]", $title, Lang::$word->FORM_DELFORM_OK);
		else :
		$json['type'] = 'warning';
		$json['title'] = Lang::$word->ALERT;
		$json['message'] = Lang::$word->NOPROCCESS;
	endif;

	print json_encode($json);
   break;

  /* == Delete Estimator Form Data == */
  case "deleteEstimatorData":	
	$res = $db->delete("estimator_data", "id=" . Filter::$id);
	
	$title = sanitize($_POST['title']);
	if($res) :
		$json['type'] = 'success';
		$json['title'] = Lang::$word->SUCCESS;
		$json['message'] =  str_replace("[FORMDATA]", $title, Lang::$word->FORM_DELDATA_OK);
		else :
		$json['type'] = 'warning';
		$json['title'] = Lang::$word->ALERT;
		$json['message'] = Lang::$word->NOPROCCESS;
	endif;

	print json_encode($json);
   break;

  /* == Delete Estimator Transaction Record == */
  case "deleteEstimatroTransaction":	
	$res = $db->delete("payments", "id=" . Filter::$id);
	
	$title = sanitize($_POST['title']);
	if($res) :
		$json['type'] = 'success';
		$json['title'] = Lang::$word->SUCCESS;
		$json['message'] =  str_replace("[TRANSID]", $title, Lang::$word->ESTM_DELTRANS_OK);
		else :
		$json['type'] = 'warning';
		$json['title'] = Lang::$word->ALERT;
		$json['message'] = Lang::$word->NOPROCCESS;
	endif;

	print json_encode($json);
   break;
   
  endswitch;
?>

<?php
  /* == Proccess Configuration == */
  if (isset($_POST['processConfig'])):
      $core->processConfig();
  endif;

  /* == Proccess Gateway == */
  if (isset($_POST['processGateway'])):
      $content->processGateway();
  endif;

  /* == Proccess News == */
  if (isset($_POST['processNews'])):
      $content->processNews();
  endif;

  /* == Proccess Email == */
  if (isset($_POST['processEmail'])):
      $content->processEmail();
  endif;

  /* == Proccess Project == */
  if (isset($_POST['processProject'])):
      $content->processProject();
  endif;

  /* == Proccess Project Type == */
  if (isset($_POST['processProjectType'])):
      $content->processProjectType();
  endif;
  
  /* == Proccess Project Task == */
  if (isset($_POST['processProjectTask'])):
      $content->processProjectTask();
  endif;

  /* == Load Task Templates == */
  if (isset($_GET['getTaskTemplateList'])):
      if ($row = $db->first("SELECT * FROM task_templates WHERE id = " . Filter::$id)):
	      $json['title'] = $row->title;
		  $json['details'] = cleanOut($row->details);
          print json_encode($json);
      else:
          print 0;
      endif;
  endif;

  /* == Proccess Task Templates == */
  if (isset($_POST['processTaskTemplate'])):
      $content->processTaskTemplate();
  endif;

  /* == Proccess Project Submission == */
  if (isset($_POST['processSubmission'])):
      $content->processProjectSubmission();
  endif;

  /* == Proccess Project File == */
  if (isset($_POST['processProjectFile'])):
      $content->processProjectFile();
  endif;

  /* == Update Invoice == */
  if (isset($_POST['updateInvoice'])):
      $content->updateInvoice();
  endif;

  /* == Add Invoice == */
  if (isset($_POST['addInvoice'])):
      $content->addInvoice();
  endif;

  /* == Send Invoice == */
  if (isset($_POST['sendInvoice'])):
      $content->sendInvoice(Filter::$id);
  endif;

  /* == Load Invoice Entries == */
  if (isset($_POST['loadInvoiceEntries'])):
      $id = intval($_POST['loadInvoiceEntries']);
      $content->loadInvoiceEntries($id);
  endif;

  /* == Process Invoice Entry == */
  if (isset($_POST['processInvoiceEntry'])):
      $content->processInvoiceEntry();
  endif;

  /* == Load Invoice Records == */
  if (isset($_POST['loadInvoiceRecords'])):
      $id = intval($_POST['loadInvoiceRecords']);
      $content->loadInvoiceRecords($id);
  endif;

  /* == Process Invoice Record == */
  if (isset($_POST['processInvoiceRecord'])):
      $content->processInvoiceRecord();
  endif;

  /* == Load Invoice Data == */
  if (isset($_GET['getInvoiceData'])):
      if($row = Core::getRowById(Content::idTable, Filter::$id)) :
	     $json['status'] = "success";
	     $json['title'] = $row->title;
		 $json['amount'] = $row->amount;
		 $json['desc'] = $row->description;
	  else :
	  $json['status'] = "error";
	  $json['message'] = Lang::$word->INVALID ;
	  endif;
	  print json_encode($json);
  endif;
  
  /* == Add Quote == */
  if (isset($_POST['addQuote'])):
      $content->addQuote();
  endif;

  /* == Convert Quote == */
  if (isset($_POST['convertQuote'])):
      $content->convertQuote();
  endif;

  /* == Send Quote == */
  if (isset($_POST['sendQuote'])):
      $content->sendQuote(intval($_POST['sendQuote']));
  endif;
  
  /* == Proccess User == */
  if (isset($_POST['processUser'])):
      $user->processUser();
  endif;

  /* == Load Projects == */
  if (isset($_POST['loadProjects'])):
      $plist = $content->getProjectList();
	   $html = '<div class="wojo grid">';
	   $html .= '<div id="pList" class="two columns">';
	   $i = 1;
          if ($plist):
			foreach ($plist as $prow):
			  $html .= '<div class="row">';
			  $html .= '<a data-id="' . $prow->id . '">' . $i++ . '. ' . $prow->title . '</a>';
			  $html .= '</div>';
			endforeach;
			unset($prow);
          endif;
		$html .= '</div>';
		$html .= '</div>';
		print $html;
  endif;
  
  /* == Add Client Funds == */
  if (isset($_POST['addClientFunds'])):
      $amount = floatval($_POST['amount']);
	  $userid = intval($_POST['addClientFunds']);
	  $totalnow = getValueById("credit", Users::uTable, $userid);
	  
	  $data['credit'] = ($totalnow == 0.00 and $amount < 1) ? '0.00' : floatval($totalnow + $amount);
	  $db->update("users", $data, "id='" . $userid . "'");
	  print $data['credit'];
  
  endif;
  
  /* == User Search == */
  if (isset($_POST['userSearch'])):
      $string = sanitize($_POST['userSearch'], 15);
      if (strlen($string) > 3):
          $sql = "SELECT id, username, email, created, avatar, CONCAT(fname,' ',lname) as name" 
		  . "\n FROM " . Users::uTable 
		  . "\n WHERE MATCH (fname) AGAINST ('" . $db->escape($string) . "*' IN BOOLEAN MODE)" 
		  . "\n OR MATCH (lname) AGAINST ('" . $db->escape($string) . "*' IN BOOLEAN MODE)" 
		  . "\n AND userlevel = 1"
		  . "\n ORDER BY username LIMIT 10";

          $html = '';
          if ($result = $db->fetch_all($sql)):
              $html .= '<div id="search-results" class="wojo positive basic segment celled list">';
              foreach ($result as $row):
                  $thumb = ($row->avatar) ? '<img src="' . UPLOADURL . 'avatars/' . $row->avatar . '" alt="" class="wojo image avatar"/>' : '<img src="' . UPLOADURL . 'avatars/blank.png" alt="" class="wojo image avatar"/>';
                  $link = 'index.php?do=users&amp;action=edit&amp;id=' . $row->id;
                  $html .= '<div class="item">' . $thumb;
                  $html .= '<div class="items">';
                  $html .= '<div class="header"><a href="' . $link . '">' . $row->name . '</a> <small>(' . $row->username . ')</small></div>';
                  $html .= '<p>' . Filter::dodate('short_date', $row->created) . '</p>';
                  $html .= '<p><a href="index.php?do=email&amp;emailid=' . urlencode($row->email) . '">' . $row->email . '</a></p>';
                  $html .= '</div>';
                  $html .= '</div>';
              endforeach;
              $html .= '</div>';
              print $html;
          endif;
      endif;
  endif;
  
  /* == Get User Info == */
  if (isset($_POST['getUserInfo'])):
      Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
      if ($pp_email = getValueById("pp_email", Users::uTable, Filter::$id)):
          print $pp_email;
	  endif;
  endif;
  
  /* == Staff Pay == */
  if (isset($_POST['MassPay']) and $user->userlevel == 9):
      $user->staffPay();
  endif;
?>
<?php
  /* == Proccess Time Billing Record == */
  if (isset($_POST['processTimeRecord'])):
      $content->processTimeRecord();
  endif;
  
  /* == Create Time Billing Report == */
  if (isset($_GET['action']) and $_GET['action'] == "createTimeReport"):

	  $sql = "SELECT tb.*,"
	  . "\n tb.created as cdate,"
	  . "\n p.title as ptitle, ts.title as tasktitle, CONCAT(u.fname,' ',u.lname) as fullname"
	  . "\n FROM " . Content::tbTable . " as tb"
	  . "\n LEFT JOIN " . Content::pTable . " as p ON p.id = tb.project_id"
	  . "\n LEFT JOIN tasks as ts ON ts.id = tb.task_id"
	  . "\n LEFT JOIN users as u ON u.id = tb.client_id";
	  
	  $result = $db->fetch_all($sql);
	  
      $type = "vnd.ms-excel";
	  $date = date('m-d-Y H:i');
	  $title = "Exported from the " . $core->company . " on $date";
	  
      header("Pragma: public");
      header("Expires: 0");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Content-Type: application/force-download");
      header("Content-Type: application/octet-stream");
      header("Content-Type: application/download");
	  header("Content-Type: application/$type");
      header("Content-Disposition: attachment;filename=temp_" . time() . ".xls");
      header("Content-Transfer-Encoding: binary ");
	  
	  print '
	  <table width="100%" cellpadding="1" cellspacing="2" border="1">
	  <caption>' . $title . '</caption>
	    <thead>
		<tr>
		  <td>#</th>
		  <td>' . Lang::$word->BILL_RECNAME . '</td>
		  <td>' . Lang::$word->PROJ_NAME . '</td>
		  <td>' . Lang::$word->INVC_CNAME . '</td>
		  <td>' . Lang::$word->TASK_NAME . '</td>
		  <td>' . Lang::$word->CREATED . '</td>
		  <td>' . Lang::$word->HOURS . '</td>
		  <td>' . Lang::$word->DESC . '</td>
		</tr>
		</thead>';
		foreach ($result as $v) {
			print '<tr>
			  <td>'.$v->id.'</td>
			  <td>'.$v->title.'</td>
			  <td>'.$v->ptitle.'</td>
			  <td>'.$v->fullname.'</td>
			  <td>'.$v->tasktitle.'</td>
			  <td>'.Filter::dodate("long_date", $v->cdate).'</td>
			  <td>'.$v->hours.'</td>
			  <td>'.sanitize($v->description).'</td>
			</tr>';
		}
	  print '</table>';
	  unset($v);
	  exit();
  endif;
?>
<?php
  /* == Create Project Transaction Report == */
  if (isset($_GET['action']) and $_GET['action'] == "createReport"):
  
	  $sql = "SELECT ip.*,"
	  . "\n ip.created as cdate,"
	  . "\n p.title as ptitle, i.title as ititle, CONCAT(u.fname,' ',u.lname) as fullname"
	  . "\n FROM " . Content::ipTable . " as ip"
	  . "\n LEFT JOIN " . Content::pTable . " as p ON p.id = ip.project_id"
	  . "\n LEFT JOIN invoices as i ON i.id = ip.invoice_id"
	  . "\n LEFT JOIN users as u ON u.id = i.client_id";
	  
	  $result = $db->fetch_all($sql);
	  
      $type = "vnd.ms-excel";
	  $date = date('m-d-Y H:i');
	  $title = "Exported from the " . $core->company . " on $date";

      header("Pragma: public");
      header("Expires: 0");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Content-Type: application/force-download");
      header("Content-Type: application/octet-stream");
      header("Content-Type: application/download");
	  header("Content-Type: application/$type");
      header("Content-Disposition: attachment;filename=temp_" . time() . ".xls");
      header("Content-Transfer-Encoding: binary ");
	  
	  print '
	  <table width="100%" cellpadding="1" cellspacing="2" border="1">
	  <caption>' . $title . '</caption>
	    <thead>
		<tr>
		  <td>#</th>
		  <td>' . Lang::$word->PROJ_NAME . '</td>
		  <td>' . Lang::$word->INVC_CNAME . '</td>
		  <td>#' . Lang::$word->TRANS_INVOICE . '</td>
		  <td>' . Lang::$word->TRANS_PAYDATE . '</td>
		  <td>' . Lang::$word->PAYMETHOD . '</td>
		  <td>' . Lang::$word->AMOUNT . '</td>
		  <td>' . Lang::$word->INFO . '</td>
		</tr>
		</thead>';
		foreach ($result as $v) {
			print '<tr>
			  <td>'.$v->id.'</td>
			  <td>'.$v->ptitle.'</td>
			  <td>'.$v->fullname.'</td>
			  <td>'.($core->invoice_number . $v->invoice_id).'</td>
			  <td>'.Filter::dodate("long_date", $v->cdate).'</td>
			  <td>'.$v->method.'</td>
			  <td>'.$v->amount.'</td>
			  <td>'.$v->description.'</td>
			</tr>';
		}
	  print '</table>';
	  unset($v);
	  exit();
  endif;
  
  /* == Create Service Transaction Report == */
  if (isset($_GET['action']) and $_GET['action'] == "createServiceReport"):
  
	  $sql = "SELECT *"
	  . "\n FROM payments "
	  . "\n ORDER BY created";
	  
	  $result = $db->fetch_all($sql);
	  
      $type = "vnd.ms-excel";
	  $date = date('m-d-Y H:i');
	  $title = "Exported from the " . $core->company . " on $date";

      header("Pragma: public");
      header("Expires: 0");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Content-Type: application/force-download");
      header("Content-Type: application/octet-stream");
      header("Content-Type: application/download");
	  header("Content-Type: application/$type");
      header("Content-Disposition: attachment;filename=temp_" . time() . ".xls");
      header("Content-Transfer-Encoding: binary ");
	  
	  print '
	  <table width="100%" cellpadding="1" cellspacing="2" border="1">
	  <caption>' . $title . '</caption>
	    <thead>
		<tr>
		  <td>#</th>
		  <td>' . Lang::$word->FROM . '</td>
		  <td>' . Lang::$word->EMAIL . '</td>
		  <td>' . Lang::$word->AMOUNT . '</td>
		  <td>' . Lang::$word->FDASH_METHOD . '</td>
		  <td>' . Lang::$word->CREATED . '</td>
		</tr>
		</thead>';
		foreach ($result as $v) {
			print '<tr>
			  <td>'.$v->txn_id.'</td>
			  <td>'.$v->user.'</td>
			  <td>'.$v->email.'</td>
			  <td>'.$v->price.'</td>
			  <td>'.$v->pp.'</td>
			  <td>'.Filter::dodate("long_date", $v->created).'</td>
			</tr>';
		}
	  print '</table>';
	  unset($v);
	  exit();
  endif;
?>
<?php
  /* == Proccess Support Ticket == */
  if (isset($_POST['processSupportTicket'])):
      $content->processSupportTicket();
  endif;

  /* == Reply Support Ticket == */
  if (isset($_POST['replySupportTicket'])):
      $content->replySupportTicket();
  endif;

  /* == Add Support Ticket == */
  if (isset($_POST['addSupportTicket'])):
      $content->addSupportTicket();
  endif;
  
  /* == Load Support Ticket == */
  if (isset($_POST['loadReplyEntries'])):
      $resrow = $content->getResponseByTicketId();
      $html ='';
      if ($resrow):
          foreach ($resrow as $trow):
              $class = ($trow->user_type == "client") ? 'warning' : 'positive';
              $html .= '
			  <div class="wojo basic message">
				<div class="wojo top right black attached label"><a class="delete" data-title="' . Lang::$word->SUP_DELETE . '" data-option="deleteSupportReply" data-id="' . $trow->id . '" data-name="' . Filter::dodate("long_date",$trow->created) . '"><i class="remove icon link"></i></a></div>
				<span class="wojo small black label">' . Filter::dodate("long_date", $trow->created) . '</span> <span class="wojo small ' . $class . ' label">' . Lang::$word->AUTHOR . ': ' . $trow->name . ' (' . $trow->user_type . ')</span>
				<div>' . cleanOut($trow->body) . '</div>
			  </div>';
          endforeach;
      print $html;
      endif;
  endif;
?>
<?php
  /* == Load Calendar == */
  if (isset($_POST['getcal'])):
      require_once (BASEPATH . "lib/class_calendar.php");
      Registry::set('Calendar', new Calendar());
      Registry::get("Calendar")->renderCalendar();
  endif;

  /* == Restore SQL Backup == */
  if (isset($_POST['restoreBackup'])):
      require_once (BASEPATH . "lib/class_dbtools.php");
      Registry::set('dbTools', new dbTools());
      $tools = Registry::get("dbTools");

      if ($tools->doRestore($_POST['restoreBackup'])):
          $json['type'] = 'success';
          $json['title'] = Lang::$word->SUCCESS;
          $json['message'] = str_replace("[DBFILE]", $_POST['restoreBackup'], Lang::$word->DB_RESTORED);
      else:
          $json['type'] = 'warning';
          $json['title'] = Lang::$word->ALERT;
          $json['message'] = Lang::$word->NOPROCCESS;
      endif;
          print json_encode($json);
  endif;
?>
<?php
  /* == Visual Form Options == */
  if (isset($_POST['doVisualForm'])):
      require_once (BASEPATH . "lib/class_forms.php");
      Registry::set('Forms', new Forms());

      /* == Load Form Fields == */
      if (isset($_POST['loadFormFields'])):
          $availfields = Registry::get("Forms")->getAllFields();
          if ($availfields):
              print '<div id="allfields" class="wojo two fluid items">';
              foreach ($availfields as $arow):
                  print '<div class="item">';
                  print '<a class="insertfield" data-id="' . $arow->id . '" data-name="' . $arow->title . '">' . $arow->title . '</a>';
                  print '</div>';
              endforeach;
              print '</div>';
          endif;
      endif;

      /* == Save Forms Data == */
      if (isset($_POST['saveFormData'])):
          Registry::get("Forms")->saveFormData();
      endif;

      /* == Add New Field == */
      if (isset($_POST['addField']) and !empty($_POST['type'])):
          $type = sanitize($_POST['type']);
          $html = '';
          $html .= '
		    <div class="wojo small block header">' . Lang::$word->FORM_F_NEWCREATE . ' <i class="icon double angle right"></i> ' . sanitize($_POST['ftitle']) . '</div>
			<div class="two fields">
			  <div class="field">
				<label>' . Lang::$word->FORM_EL_FLDTITLE . '</label>
				<label class="input"> <i class="icon-append icon asterisk"></i>
				  <input type="text" name="title" placeholder="' . Lang::$word->FORM_EL_FLDTITLE . '">
				</label>
			  </div>
			  <div class="field">
				<label>' . Lang::$word->FORM_EL_FLDLABEL . '</label>
				<label class="input"> <i class="icon-append icon asterisk"></i>
				  <input type="text" name="desc" placeholder="' . Lang::$word->FORM_EL_FLDLABEL . '">
				</label>
			  </div>
			</div>';

          if ($type != "labelfield" and $type != "parafield" and $type != "hr"):
              $html .= '
			  <div class="wojo small block header">' . Lang::$word->FORM_EL_OPTATTRIB . '</div>
			  <div class="two fields">
				<div class="field">
				  <label>' . Lang::$word->FORM_EL_ERRORMSG . '</label>
				  <label class="input">
					<input type="text" name="msgerror" placeholder="' . Lang::$word->FORM_EL_ERRORMSG . '">
				  </label>
				</div>
				<div class="field">
				  <label>' . Lang::$word->FORM_EL_TOOLTIP . '</label>
				  <label class="input">
					<input type="text" name="tooltip" placeholder="' . Lang::$word->FORM_EL_TOOLTIP . '">
				  </label>
				</div>
			  </div>';
          endif;
          $html .= Forms::addFormField($type);
          $html .= '<div class="field">';
          $html .= '<button class="wojo positive button push-right" name="dofields" type="button">' . Lang::$word->FORM_F_NEWCREATE2 . '</button>';
          $html .= '</div>';
          $html .= '<input name="processFormField" type="hidden" value="1">';
          $html .= '<input name="doVisualForm" type="hidden" value="1">';
          $html .= '<input name="type" type="hidden" value="' . $type . '">';
          $json['data'] = $html;
          print json_encode($json);
      endif;

      /* == Edit Field == */
      if (isset($_POST['editField']) and !empty(Filter::$id)):
          $row = Core::getRowById(Forms::fTable, Filter::$id);
          $html = '';
          $html .= '
		  <div class="wojo small block header">' . Lang::$word->FORM_F_EDIT_FIELD . ' <i class="icon double angle right"></i> ' . $row->title . '</div>
		  <div class="two fields">
			<div class="field">
			  <label>' . Lang::$word->FORM_EL_FLDTITLE . '</label>
			  <label class="input"> <i class="icon-append icon asterisk"></i>
				<input type="text" name="title" placeholder="' . $row->title . '" value="' . $row->title . '">
			  </label>
			</div>
			<div class="field">
			  <label>' . Lang::$word->FORM_EL_FLDLABEL . '</label>
			  <label class="input"> <i class="icon-append icon asterisk"></i>
				<input type="text" name="desc" placeholder="' . $row->desc . '" value="' . $row->desc . '">
			  </label>
			</div>
		  </div>';

          if ($row->type != "labelfield" and $row->type != "parafield" and $row->type != "hr"):
              $html .= '
			  <div class="wojo small block header">' . Lang::$word->FORM_EL_OPTATTRIB . '</div>
			  <div class="two fields">
				<div class="field">
				  <label>' . Lang::$word->FORM_EL_ERRORMSG . '</label>
				  <label class="input">
					<input type="text" name="msgerror' . Lang::$lang . '" placeholder="' . $row->msgerror . '" value="' . $row->msgerror . '">
				  </label>
				</div>
				<div class="field">
				  <label>' . Lang::$word->FORM_EL_TOOLTIP . '</label>
				  <label class="input">
					<input type="text" name="tooltip" placeholder="' . $row->tooltip . '" value="' . $row->tooltip . '">
				  </label>
				</div>
			  </div>';
          endif;
          $html .= Forms::loadFormField($row);
          $html .= '<div class="field">';
          $html .= '<button class="wojo positive button push-right" name="dofields" type="button">' . Lang::$word->FORM_F_UPDATE_FIELD . '</button>';
          $html .= '</div>';
          $html .= '<input type="hidden" name="type" value="' . $row->type . '">';
          $html .= '<input type="hidden" name="id" value="' . $row->id . '">';
          $html .= '<input name="processFormField" type="hidden" value="1">';
          $html .= '<input name="doVisualForm" type="hidden" value="1">';
          $json['data'] = $html;
          print json_encode($json);

      endif;

      /* == Process Form Field == */
      if (isset($_POST['processFormField'])):
          Registry::get("Forms")->processField();
      endif;

      /* == Process Form == */
      if (isset($_POST['processVisualForm'])):
          Registry::get("Forms")->processForm();
      endif;

      /* == View Form Data  == */
      if (isset($_POST['viewFormData'])):
          $html = getValueById("form_data", Forms::dTable, Filter::$id);
		  print '<div style="width:500px">' . cleanOut($html) . '</div>';
      endif;

  endif;
?>
<?php
  /* == Visual Estimator Options == */
  if (isset($_POST['doVisualEstimator'])):
      require_once (BASEPATH . "lib/class_estimator.php");
      Registry::set('Estimator', new Estimator());

      /* == Proccess Estimator == */
      if (isset($_POST['processEstimator'])):
          Registry::get("Estimator")->processEstimator();
      endif;

      /* == Save Forms Data == */
      if (isset($_POST['saveEstimatorData'])):
          Registry::get("Estimator")->saveEstimatorData();
      endif;

      /* == Load Estimator Fields == */
      if (isset($_POST['loadEstimatorFields'])):
          $availfields = Registry::get("Estimator")->getAllFields();
          if ($availfields):
              print '<div id="allfields" class="wojo two fluid items">';
              foreach ($availfields as $arow):
                  print '<div class="item">';
                  print '<a class="insertfield" data-id="' . $arow->id . '" data-name="' . $arow->title . '">' . $arow->title . '</a>';
                  print '</div>';
              endforeach;
              unset($arow);
              print '</div>';
          endif;
      endif;

      /* == View Estimator Data  == */
      if (isset($_POST['viewFormData'])):
          $html = getValueById("form_data", Estimator::dTable, Filter::$id);
          print '<div style="width:500px">' . cleanOut($html) . '</div>';
      endif;

      /* == Process Estimator Field == */
      if (isset($_POST['processEstimatorField'])):
          Registry::get("Estimator")->processEstimatorField();
      endif;

      /* == Add New Field == */
      if (isset($_POST['addField']) and !empty($_POST['type'])):
          $type = sanitize($_POST['type']);
          $html = '';
          $html .= '
		  <div class="wojo small block header">' . Lang::$word->FORM_F_NEWCREATE . ' <i class="icon double angle right"></i> ' . sanitize($_POST['ftitle']) . '</div>
			<div class="two fields">
			  <div class="field">
				<label>' . Lang::$word->FORM_EL_FLDTITLE . '</label>
				<label class="input"> <i class="icon-append icon asterisk"></i>
				  <input type="text" name="title" placeholder="' . Lang::$word->FORM_EL_FLDTITLE . '">
				</label>
			  </div>
			  <div class="field">
				<label>' . Lang::$word->FORM_EL_FLDLABEL . '</label>
				<label class="input"> <i class="icon-append icon asterisk"></i>
				  <input type="text" name="desc" placeholder="' . Lang::$word->FORM_EL_FLDLABEL . '">
				</label>
			  </div>
			</div>';
          if ($type != "labelfield" and $type != "parafield" and $type != "hr"):
              $html .= '
			  <div class="wojo small block header">' . Lang::$word->FORM_EL_OPTATTRIB . '</div>
			  <div class="two fields">
				<div class="field">
				  <label>' . Lang::$word->FORM_EL_ERRORMSG . '</label>
				  <label class="input">
					<input type="text" name="msgerror" placeholder="' . Lang::$word->FORM_EL_ERRORMSG . '">
				  </label>
				</div>
				<div class="field">
				  <label>' . Lang::$word->FORM_EL_TOOLTIP . '</label>
				  <label class="input">
					<input type="text" name="tooltip" placeholder="' . Lang::$word->FORM_EL_TOOLTIP . '">
				  </label>
				</div>
			  </div>';
          endif;
		  $html .= Estimator::addFormField($type);
          $html .= '<div class="field">';
          $html .= '<button class="wojo positive button push-right" name="dofields" type="button">' . Lang::$word->FORM_F_NEWCREATE2 . '</button>';
          $html .= '</div>';
          $html .= '<input name="processEstimatorField" type="hidden" value="1">';
          $html .= '<input name="doVisualEstimator" type="hidden" value="1">';
          $html .= '<input name="type" type="hidden" value="' . $type . '">';
          $json['data'] = $html;
          print json_encode($json);
      endif;

      /* == Update Existing Field == */
      if (isset($_POST['editField']) and !empty(Filter::$id)):
          $row = Core::getRowById(Estimator::fTable, Filter::$id);
          $html = '';
          $html .= '
		  <div class="wojo small block header">' . Lang::$word->FORM_F_EDIT_FIELD . ' <i class="icon double angle right"></i> ' . $row->title . '</div>
		  <div class="two fields">
			<div class="field">
			  <label>' . Lang::$word->FORM_EL_FLDTITLE . '</label>
			  <label class="input"> <i class="icon-append icon asterisk"></i>
				<input type="text" name="title" placeholder="' . $row->title . '" value="' . $row->title . '">
			  </label>
			</div>
			<div class="field">
			  <label>' . Lang::$word->FORM_EL_FLDLABEL . '</label>
			  <label class="input"> <i class="icon-append icon asterisk"></i>
				<input type="text" name="desc" placeholder="' . $row->desc . '" value="' . $row->desc . '">
			  </label>
			</div>
		  </div>';
          if ($row->type != "labelfield" and $row->type != "parafield" and $row->type != "hr"):
              $html .= '
			  <div class="wojo small block header">' . Lang::$word->FORM_EL_OPTATTRIB . '</div>
			  <div class="two fields">
				<div class="field">
				  <label>' . Lang::$word->FORM_EL_ERRORMSG . '</label>
				  <label class="input">
					<input type="text" name="msgerror' . Lang::$lang . '" placeholder="' . $row->msgerror . '" value="' . $row->msgerror . '">
				  </label>
				</div>
				<div class="field">
				  <label>' . Lang::$word->FORM_EL_TOOLTIP . '</label>
				  <label class="input">
					<input type="text" name="tooltip" placeholder="' . $row->tooltip . '" value="' . $row->tooltip . '">
				  </label>
				</div>
			  </div>';
          endif;
		  $html .= Estimator::loadFormField($row);
          $html .= '<div class="field">';
          $html .= '<button class="wojo positive button push-right" name="dofields" type="button">' . Lang::$word->FORM_F_UPDATE_FIELD . '</button>';
          $html .= '</div>';
          $html .= '<input type="hidden" name="type" value="' . $row->type . '">';
          $html .= '<input type="hidden" name="id" value="' . $row->id . '">';
          $html .= '<input name="processEstimatorField" type="hidden" value="1">';
          $html .= '<input name="doVisualEstimator" type="hidden" value="1">';
          $json['data'] = $html;
          print json_encode($json);
      endif;
  endif;
?>

<?php
  /* == File Picker == */
  if (isset($_POST['pickFile'])):
      require_once (BASEPATH . "lib/class_fm.php");
      Registry::set('FileManager', new FileManager());
      if ($_POST['ext'] == "images"):
          $ext = array("jpg","png","gif");
      elseif ($_POST['ext'] == "audio"):
          $ext = array("mp3");
      elseif ($_POST['ext'] == "archive"):
          $ext = array("zip","rar");
      else:
          $ext = false;
      endif;
      print FileManager::getPickerFiles(UPLOADS, $ext);
  endif;
  
  /* == Proccess Message == */
  if (isset($_POST['processMessage'])):
      $content->processMessage();
  endif;

  /* == Reorder Fields == */
  if (isset($_GET['sortfields'])):
      foreach ($_POST['node'] as $k => $v):
          $p = $k + 1;
          $data['sorting'] = intval($p);
          $db->update(Content::cfTable, $data, "id=" . (int)$v);
      endforeach;
  endif;

  /* == Proccess Field == */
  if (isset($_POST['processField'])):
      $content->processField();
  endif;

  /* == Update Phrase== */
  if (isset($_POST['quickedit'])):
      if ($_POST['type'] == "language"):
          if (empty($_POST['title'])):
              print '--- EMPTY STRING ---';
              exit;
          endif;

          if (file_exists(BASEPATH . Lang::langdir . Core::$language . "/" . $_POST['path'] . ".xml")):
		      $xmlel = simplexml_load_file(BASEPATH . Lang::langdir . Core::$language . "/" . $_POST['path'] . ".xml");
              $node = $xmlel->xpath("/language/phrase[@data = '" . $_POST['key'] . "']");
			  $title = cleanOut($_POST['title']);
			  $title = strip_tags($title);
              $node[0][0] = $title;
              $xmlel->asXML(BASEPATH . Lang::langdir . Core::$language . "/" . $_POST['path'] . ".xml");
          endif;
      endif;
	  
	  print $title;
  endif;
		  
		  
  /* == Load Language File== */
  if (isset($_POST['loadLanguage'])):
      if (file_exists(BASEPATH . Lang::langdir . Core::$language . "/" . $_POST['filename'] . ".xml")):
          $xmlel = simplexml_load_file(BASEPATH . Lang::langdir . Core::$language . "/" . $_POST['filename'] . ".xml");
          $data = new stdClass();
          $i = 1;
          $html = '';
          foreach ($xmlel as $pkey):
              $html .= '<div class="row">';
              $html .= '<div contenteditable="true" data-path="' . $_POST['filename'] . '" data-edit-type="language" data-id="' . $i++ . '" data-key="' . $pkey['data'] . '" class="wojo phrase">' . $pkey . '</div>';
              $html .= '</div>';
          endforeach;
          $json['status'] = 'success';
          $json['message'] = $html;
      else:
          $json['status'] = 'error';
		  $json['title'] = Lang::$word->ERROR;
          $json['message'] = Lang::$word->LM_LOADERR;
      endif;
          print json_encode($json);
  endif;
  
  /* == Load Mailer File== */
  if (isset($_GET['getMailerTemplate'])):
      $file = sanitize($_GET['filename']);
      if (file_exists(BASEPATH  . "/mailer/" . $file)):
		  $html = file_get_contents(BASEPATH . "mailer/" . $file);
          $json['status'] = 'success';
		  $json['title'] = substr(str_replace("_", " ",$file), 0, -8);
          $json['message'] = $html;
      else:
          $json['status'] = 'error';
          $json['message'] = Lang::$word->MTPL_ERROR;
      endif;
          print json_encode($json);
  endif;
  
  /* == Process Mailer File== */
  if (isset($_POST['processMtemplate'])):
      $file = sanitize($_POST['filename']);
      $path = BASEPATH . "/mailer/" . $file;
      if (is_file($path)):
          if (isset($_POST['backup']) and $_POST['backup'] == 1):
              if ($data = file_get_contents($path)):
                  file_put_contents($path . '.bak', $data);
              endif;
          endif;
          $doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
          if (!file_put_contents($path,  cleanOut($_POST['body']))):
              $json['type'] = 'success';
              $json['message'] = Filter::msgError(Lang::$word->MTPL_ERROR2, false);
          else:
              $json['type'] = 'success';
              $json['message'] = Filter::msgOk(Lang::$word->MTPL_OK, false);
          endif;

      else:
		  $json['type'] = 'success';
		  $json['message'] = Filter::msgError(Lang::$word->MTPL_ERROR2, false);
	  
      endif;
          print json_encode($json);
  endif;
  
?>
<?php
  /* == Latest Sales Stats == */
  if (isset($_GET['getSaleStats'])):
      if (intval($_GET['getSaleStats']) == 0 || empty($_GET['getSaleStats'])):
          die();
      endif;
	
  $range = (isset($_GET['timerange'])) ? sanitize($_GET['timerange']) : 'month';	  
  $data = array();
  $data['order'] = array();
  $data['xaxis'] = array();
  $data['order']['label'] = Lang::$word->DASH_TOTALPRJ;
  
  switch ($range) {
	  case 'day':
	  $date = date('Y-m-d');
		  for ($i = 0; $i < 24; $i++) {
			  $query = $db->first("SELECT COUNT(*) AS total FROM " . Content::ipTable 
			  . "\n WHERE DATE(created) = '" . $db->escape($date) . "'" 
			  . "\n AND HOUR(created) = '" . (int)$i . "'" 
			  . "\n GROUP BY HOUR(created) ORDER BY created ASC");
  
			  ($query) ? $data['order']['data'][] = array($i, (int)$query->total) : $data['order']['data'][] = array($i, 0);
			  $data['xaxis'][] = array($i, date('H', mktime($i, 0, 0, date('n'), date('j'), date('Y'))));
		  }
		  break;
	  case 'week':
		  $date_start = strtotime('-' . date('w') . ' days');
  
		  for ($i = 0; $i < 7; $i++) {
			  $date = date('Y-m-d', $date_start + ($i * 86400));
			  $query = $db->first("SELECT COUNT(*) AS total FROM " . Content::ipTable
			  . "\n WHERE DATE(created) = '" . $db->escape($date) . "'"
			  . "\n GROUP BY DATE(created)");
  
			  ($query) ? $data['order']['data'][] = array($i, (int)$query->total) : $data['order']['data'][] = array($i, 0);
			  $data['xaxis'][] = array($i, date('D', strtotime($date)));
		  }
  
		  break;
	  default:
	  case 'month':
		  for ($i = 1; $i <= date('t'); $i++) {
			  $date = date('Y') . '-' . date('m') . '-' . $i;
			  $query = $db->first("SELECT COUNT(*) AS total FROM " . Content::ipTable
			  . "\n WHERE (DATE(created) = '" . $db->escape($date) . "')"
			  . "\n GROUP BY DAY(created)");
  
			  ($query) ? $data['order']['data'][] = array($i, (int)$query->total) : $data['order']['data'][] = array($i, 0);
			  $data['xaxis'][] = array($i, date('j', strtotime($date)));
		  }
		  break;
	  case 'year':
		  for ($i = 1; $i <= 12; $i++) {
			  $query = $db->first("SELECT COUNT(*) AS total FROM " . Content::ipTable
			  . "\n WHERE YEAR(created) = '" . date('Y') . "'"
			  . "\n AND MONTH(created) = '" . $i . "'"
			  . "\n GROUP BY MONTH(created)");
  
			  ($query) ? $data['order']['data'][] = array($i, (int)$query->total) : $data['order']['data'][] = array($i, 0);
			  $data['xaxis'][] = array($i, date('M', mktime(0, 0, 0, $i, 1, date('Y'))));
		  }
		  break;
  }

   print json_encode($data);
  endif;
  
?>
<?php
  /* == Make Pdf == */
  if (isset($_GET['dopdf'])):
	  Filter::$id = intval($_GET['dopdf']);
	  $title = cleanOut(preg_replace("/[^a-zA-Z0-9\s]/", "", $_GET['title']));
	  ob_start();
	  require_once(BASEPATH . 'admin/print_pdf.php');
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
  
  /* == Make Quote Pdf == */
  if (isset($_GET['doquotepdf'])):
	  Filter::$id = intval($_GET['doquotepdf']);
	  $title = cleanOut(preg_replace("/[^a-zA-Z0-9\s]/", "", $_GET['title']));
	  ob_start();
	  require_once(BASEPATH . 'admin/print_quote_pdf.php');
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