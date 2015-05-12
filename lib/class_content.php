<?php
  /**
   * Content Class
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: class_content.php, v3.00 2014-06-05 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  class Content
  {
	  const cfTable = "custom_fields";
	  const nTable = "news";
	  const pTable = "projects";
	  const iTable = "invoices";
	  const ipTable = "invoice_payments";
	  const idTable = "invoice_data";
	  const tTable = "tasks";
	  const tbTable = "time_billing";
	  const sTable = "submissions";
	  const gwTable = "gateways";
	  
      private static $db;


      /**
       * Content::__construct()
       * 
       * @return
       */
      public function __construct()
      {
          self::$db = Registry::get("Database");
      }

public function gewa() {
	
}
      /**
       * Content::getGateways()
       * 
       * @param bool $active
       * @return
       */
      public function getGateways($active = false)
      {
          $where = ($active) ? "WHERE active = '1'" : null;
          $sql = "SELECT * FROM gateways" 
		  . "\n " . $where . "\n ORDER BY name";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;

      }

      /**
       * Content::processGateway()
       * 
       * @return
       */
      public function processGateway()
      {
          Filter::checkPost('displayname', Lang::$word->GATE_NAME);

          if (empty(Filter::$msgs)) {
              $data = array(
					'displayname' => sanitize($_POST['displayname']), 
					'extra' => sanitize($_POST['extra']), 
					'extra2' => sanitize($_POST['extra2']), 
					'extra3' => sanitize($_POST['extra3']), 
					'live' => intval($_POST['live']), 
					'active' => intval($_POST['active'])
			  );

              self::$db->update("gateways", $data, "id=" . Filter::$id);
			  
			  if(self::$db->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk(Lang::$word->GATE_UPDATED, false);
			  } else {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
				  
			  }
			  print json_encode($json); 
			    
		  } else {
			  $json['message'] = Filter::msgStatus();
			  print json_encode($json);
		  }
      }

      /**
       * Content::getNews()
       * 
       * @return
       */
      public function getNews()
      {
          $sql = "SELECT *, DATE_FORMAT(created, '" . Registry::get("Core")->long_date . "') as start" 
		  . "\n FROM news" 
		  . "\n ORDER BY created ASC";

          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Content::processNews()
       * 
       * @return
       */
      public function processNews()
      {
          Filter::checkPost('title', Lang::$word->NEWS_NAME);
          Filter::checkPost('body', Lang::$word->NEWS_BODY);

          if (empty(Filter::$msgs)) {
              $data = array(
					'title' => sanitize($_POST['title']), 
					'body' => $_POST['body'], 
					'author' => Registry::get("Users")->name, 
					'created' => sanitize($_POST['created_submit']), 
					'active' => intval($_POST['active'])
			  );

              (Filter::$id) ? self::$db->update(self::nTable, $data, "id=" . Filter::$id) : self::$db->insert(self::nTable, $data);
              $message = (Filter::$id) ? Lang::$word->NEWS_UPDATED : Lang::$word->NEWS_ADDED;

			  
			  if(Registry::get("Database")->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk($message, false);
			  } else {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
			  }
			  print json_encode($json);
			  
		  } else {
			  $json['message'] = Filter::msgStatus();
			  print json_encode($json);
		  }
      }
      /**
       * Content::getCustomFields()
       * 
       * @return
       */
      public function getCustomFields()
      {
          $sql = "SELECT * FROM " . self::cfTable 
		  . "\n ORDER BY sorting, section";

          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Content::fieldSection()
       * 
       * @return
       */
      public static function fieldSection($section)
      {
          switch($section) {
			  case "p":
			  return Lang::$word->CUSF_SECTION_P;
			  break;

			  case "c":
			  return Lang::$word->CUSF_SECTION_C;
			  break;

			  case "s":
			  return Lang::$word->CUSF_SECTION_S;
			  break;

			  case "t":
			  return Lang::$word->CUSF_SECTION_T;
			  break;
			  
			  case "sb":
			  return Lang::$word->CUSF_SECTION_SB;
			  break;
		  }
      }

      /**
       * Content::getFieldSection()
       * 
       * @return
       */
      public static function getFieldSection($section = false)
      {
		  
          $arr = array(
				 'p' => Lang::$word->CUSF_SECTION_P,
				 'c' => Lang::$word->CUSF_SECTION_C,
				 's' => Lang::$word->CUSF_SECTION_S,
				 't' => Lang::$word->CUSF_SECTION_T,
				 'sb' => Lang::$word->CUSF_SECTION_SB
		  );

          $html = '';
          foreach ($arr as $key => $val) {
              if ($key == $section) {
                  $html .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
              } else
                  $html .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
          }
          unset($val);
          return $html;
      }
	  
      /**
       * Content::rendertCustomFields()
       * 
       * @return
       */
      public function rendertCustomFields($section, $data, $wrap = "two")
      {
		  $html = '';
          if($fdata = self::$db->fetch_all("SELECT * FROM " . self::cfTable . " WHERE section = '" . self::$db->escape($section) . "' ORDER BY sorting")) {
			  $value = ($data) ? explode("::", $data) : null;
			  $group_nr = 1;
			  $last_row = count($fdata) - 1;
			  $wrapper = wordsToNumber($wrap);
			  
			  foreach ($fdata as $id => $cfrow) {
				  if ($id % $wrapper == 0) {
					  $html .= '<div class="' . $wrap . ' fields">';
					  $i = 0;
					  $group_nr++;
				  }
				  
	              $tip = ($cfrow->tooltip) ? $cfrow->tooltip : $cfrow->title;
				  $html .= '<div class="field">';
				  $html .= '<label>' . $cfrow->title . '</label>';
					  
				  $html .= '<label class="input">';
				  if ($cfrow->req) {
					  $html .= '<i class="icon-append icon asterisk"></i>';
				  }
				  $html .= '<input name="custom[]" type="text" placeholder="' . $tip . '" value="' . $value[$id] . '">';
				  $html .= '</label>';
				  $html .= '</div>';
	
				  $i++;
				  if ($i == $wrapper || $id == $last_row)
					  $html .= '</div>';
			  }
			  unset($cfrow);
		  }

          return $html;
      }

      /**
       * Content::rendertCustomFieldsView()
       * 
       * @return
       */
      public function rendertCustomFieldsView($section, $data)
      {
		  $html = '';
          if($fdata = self::$db->fetch_all("SELECT * FROM custom_fields WHERE section = '" . self::$db->escape($section) . "' ORDER BY sorting")) {
			  $value = ($data) ? explode("::", $data) : null;
			  foreach ($fdata as $k => $cfrow) {
				  $html .= '<tr>';
				  $html .= '<td>' . $cfrow->title . ':</td>';
				  $html .= '<td>' . $value[$k] . '</td>';
				  $html .= '</tr>';
			  }
		  }

          return $html;
      }
	  
      /**
       * Content::processField()
       * 
       * @return
       */
      public function processField()
      {
          Filter::checkPost('title', Lang::$word->CUSF_NAME);

          if (empty(Filter::$msgs)) {
              $data = array(
					'title' => sanitize($_POST['title']), 
					'section' => sanitize($_POST['section'])
			  );

              (Filter::$id) ? self::$db->update(Content::cfTable, $data, "id=" . Filter::$id) : self::$db->insert(Content::cfTable, $data);
              $message = (Filter::$id) ? Lang::$word->CUSF_UPDATED : Lang::$word->CUSF_ADDED;
			  
			  if(Registry::get("Database")->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk($message, false);
			  } else {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
			  }
			  print json_encode($json);
			  
			  
		  } else {
			  $json['message'] = Filter::msgStatus();
			  print json_encode($json);
		  }
      }
	  
      /**
       * Content::processEmail()
       * 
       * @return
       */
      public function processEmail()
      {
          Filter::checkPost('subject', Lang::$word->MAIL_REC_SUJECT);
          Filter::checkPost('body', Lang::$word->MAIL_BODY);

          if ($_POST['recipient'] == 'multiple') {
			  if (empty($_POST['multilist']) or !is_array($_POST['multilist'])) 
                  Filter::$msgs['multilist'] = Lang::$word->MAIL_REC_R;
		  }
			  
          if (empty(Filter::$msgs)) {
              $to = sanitize($_POST['recipient']);
              $subject = cleanOut($_POST['subject']);
              $body = cleanOut($_POST['body']);
			  $numSent = 0;
			  $failedRecipients = array();
			  
			  if(file_exists(UPLOADS.'print_logo.png')) {
				  $logo = '<img src="'.UPLOADURL . 'print_logo.png" alt="'. Registry::get("Core")->company.'" style="border:0"/>';
			  } elseif(Registry::get("Core")->logo) {
				  $logo = '<img src="'.UPLOADURL . Registry::get("Core")->logo . '" alt="'. Registry::get("Core")->company.'" style="border:0"/>';
			  } else {
				$logo = Registry::get("Core")->company;
			  }

              switch ($to) {
                  case "all":
                      require_once (BASEPATH . "lib/class_mailer.php");
                      $mailer = Mailer::sendMail();
                      $mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin(100,30));

                      $sql = "SELECT email, CONCAT(fname,' ',lname) as name FROM " . Users::uTable . " WHERE id != 1";
                      $userrow = self::$db->fetch_all($sql);

                      $replacements = array();
                      if ($userrow) {
                          foreach ($userrow as $cols) {
                              $replacements[$cols->email] = array(
									'[COMPANY]' => Registry::get("Core")->company, 
									'[LOGO]' => $logo, 
									'[NAME]' => $cols->name, 
									'[URL]' => SITEURL, 
									'[YEAR]' => date('Y')
							  );
                          }

                          $decorator = new Swift_Plugins_DecoratorPlugin($replacements);
                          $mailer->registerPlugin($decorator);

                          $message = Swift_Message::newInstance()
								  ->setSubject($subject)
								  ->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->company))
								  ->setBody($body, 'text/html');

						  foreach ($userrow as $row) {
							  $message->setTo(array($row->email => $row->name));
							  $numSent++;
							  $mailer->send($message, $failedRecipients);
						  }
						  unset($row);

                      }
                      break;

                  case "clients":
                      require_once (BASEPATH . "lib/class_mailer.php");
                      $mailer = Mailer::sendMail();
                      $mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin(100,30));

                      $sql = "SELECT email, CONCAT(fname,' ',lname) as name FROM " . Users::uTable . " WHERE userlevel = 1";
                      $userrow = self::$db->fetch_all($sql);

                      $replacements = array();
                      if ($userrow) {
                          foreach ($userrow as $cols) {
                              $replacements[$cols->email] = array(
									'[COMPANY]' => Registry::get("Core")->company, 
									'[LOGO]' => $logo, 
									'[NAME]' => $cols->name, 
									'[URL]' => SITEURL, 
									'[YEAR]' => date('Y')
							  );
                          }

                          $decorator = new Swift_Plugins_DecoratorPlugin($replacements);
                          $mailer->registerPlugin($decorator);

                          $message = Swift_Message::newInstance()
								  ->setSubject($subject)
								  ->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->company))
								  ->setBody($body, 'text/html');

						  foreach ($userrow as $row) {
							  $message->setTo(array($row->email => $row->name));
							  $numSent++;
							  $mailer->send($message, $failedRecipients);
						  }
						  unset($row);
                      }
                      break;

                  case "staff":
                      require_once (BASEPATH . "lib/class_mailer.php");
                      $mailer = Mailer::sendMail();
                      $mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin(100,30));

                      $sql = "SELECT email, CONCAT(fname,' ',lname) as name FROM " . Users::uTable . " WHERE userlevel = 5";
                      $userrow = self::$db->fetch_all($sql);

                      $replacements = array();
                      if ($userrow) {
                          foreach ($userrow as $cols) {
                              $replacements[$cols->email] = array(
									'[COMPANY]' => Registry::get("Core")->company, 
									'[LOGO]' => $logo, 
									'[NAME]' => $cols->name, 
									'[URL]' => SITEURL, 
									'[YEAR]' => date('Y')
							  );
                          }

                          $decorator = new Swift_Plugins_DecoratorPlugin($replacements);
                          $mailer->registerPlugin($decorator);

                          $message = Swift_Message::newInstance()
								  ->setSubject($subject)
								  ->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->company))
								  ->setBody($body, 'text/html');

						  foreach ($userrow as $row) {
							  $message->setTo(array($row->email => $row->name));
							  $numSent++;
							  $mailer->send($message, $failedRecipients);
						  }
						  unset($row);

                      }
                      break;

                  case "multiple":
                      require_once (BASEPATH . "lib/class_mailer.php");
                      $mailer = Mailer::sendMail();
                      $mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin(100,30));
					  $matches = sanitize($_POST['multilist']);
					  $matches = implode(',', $_POST['multilist']);

                      $sql = "SELECT email, CONCAT(fname,' ',lname) as name FROM " . Users::uTable . " WHERE id IN(" . $matches . ")";
                      $userrow = self::$db->fetch_all($sql);

                      $replacements = array();
                      if ($userrow) {
                          foreach ($userrow as $cols) {
                              $replacements[$cols->email] = array(
									'[COMPANY]' => Registry::get("Core")->company, 
									'[LOGO]' => $logo, 
									'[NAME]' => $cols->name, 
									'[URL]' => SITEURL, 
									'[YEAR]' => date('Y')
							  );
                          }

                          $decorator = new Swift_Plugins_DecoratorPlugin($replacements);
                          $mailer->registerPlugin($decorator);

                          $message = Swift_Message::newInstance()
								  ->setSubject($subject)
								  ->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->company))
								  ->setBody($body, 'text/html');

						  foreach ($userrow as $row) {
							  $message->setTo(array($row->email => $row->name));
							  $numSent++;
							  $mailer->send($message, $failedRecipients);
						  }
						  unset($row);
                      }
                      break;
					  
                  default:
                      require_once (BASEPATH . "lib/class_mailer.php");
                      $mailer = Mailer::sendMail();
                      $row = self::$db->first("SELECT email, CONCAT(fname,' ',lname) as name FROM " . Users::uTable . " WHERE email LIKE '%" . sanitize($to) . "%'");
                      if ($row) {
                          $newbody = str_replace(
						  array('[COMPANY]', '[LOGO]', '[NAME]', '[URL]', '[YEAR]'), 
						  array(Registry::get("Core")->company, $logo, $row->name, SITEURL, date('Y')), $body);

                          $message = Swift_Message::newInstance()
								  ->setSubject($subject)
								  ->setTo(array($to => $row->name))
								  ->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->company))
								  ->setBody($newbody, 'text/html');
								  
						  if (!empty($_FILES['attach']['name'])) {
							  move_uploaded_file($_FILES['attach']['tmp_name'], UPLOADS . 'tempfiles/' . $_FILES['attach']['name']);
							  $message->attach(Swift_Attachment::fromPath(UPLOADS . 'tempfiles/' . $_FILES['attach']['name']));
						  }
						  
						  $numSent++;
						  $mailer->send($message, $failedRecipients);
                      }
                      break;
              }

			  if ($numSent) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk($numSent . ' ' . Lang::$word->MAIL_SENT, false);
			  } else {
				  $json['type'] = 'error';
				  $res = '';
				  $res .= '<ul>';
				  foreach ($failedRecipients as $failed) {
					  $res .= '<li>' . $failed . '</li>';
				  }
				  $res .= '</ul>';
				  $json['message'] = Filter::msgAlert(Lang::$word->MAIL_ALERT . $res, false);
	
				  unset($failed);
			  }
			  print json_encode($json);
			  
		  } else {
			  $json['message'] = Filter::msgStatus();
			  print json_encode($json);
		  }
	
	  }

      /**
       * Content::getProjects()
       * 
       * @return
       */
      public function getProjects()
      {
          $sort = sanitize(get('sort'));
		  $uid = Registry::get("Users")->uid;

		  if (isset($_GET['letter']) and $sort) {
			  $letter = sanitize($_GET['letter'], 2);
			  if (Registry::get("Users")->userlevel == 5) {
				  $where = "WHERE FIND_IN_SET($uid, p.staff_id) AND p.title REGEXP '^" . self::$db->escape($letter) . "' AND p.client_id = " . (int)$sort; 
				  $q = "SELECT COUNT(*) FROM " . self::pTable . " WHERE FIND_IN_SET($uid, staff_id) AND title REGEXP '^" . self::$db->escape($letter) . "' AND client_id = " . (int)$sort . " LIMIT 1";
			  } else {
				  $where = "WHERE p.title REGEXP '^" . self::$db->escape($letter) . "' AND p.client_id = " . (int)$sort; 
				  $q = "SELECT COUNT(*) FROM " . self::pTable . " WHERE title REGEXP '^" . self::$db->escape($letter) . "' AND client_id = " . (int)$sort . " LIMIT 1"; 
			  }
		  } elseif ($sort) {
			  if (Registry::get("Users")->userlevel == 5) {
				  $where = "WHERE FIND_IN_SET($uid, p.staff_id) AND p.client_id = " . (int)$sort; 
				  $q = "SELECT COUNT(*) FROM " . self::pTable . " WHERE FIND_IN_SET($uid, staff_id) AND client_id = " . (int)$sort . " LIMIT 1";
			  } else {
				  $where = "WHERE p.client_id = " . (int)$sort; 
				  $q = "SELECT COUNT(*) FROM " . self::pTable . " WHERE client_id = " . (int)$sort . " LIMIT 1"; 
			  }			  
		  } elseif(isset($_GET['letter'])) {
			  $letter = sanitize($_GET['letter'], 2);
			  if (Registry::get("Users")->userlevel == 5) {
				  $where = "WHERE FIND_IN_SET($uid, p.staff_id) AND p.title REGEXP '^" . self::$db->escape($letter) . "'"; 
				  $q = "SELECT COUNT(*) FROM " . self::pTable . " WHERE FIND_IN_SET($uid, staff_id) AND title REGEXP '^" . self::$db->escape($letter) . "' LIMIT 1";
			  } else {
				  $where = "WHERE p.title REGEXP '^" . self::$db->escape($letter) . "'"; 
				  $q = "SELECT COUNT(*) FROM " . self::pTable . " WHERE title REGEXP '^" . self::$db->escape($letter) . "' LIMIT 1"; 
			  }
		  } else {
			  if (Registry::get("Users")->userlevel == 5) {
				  $where = "WHERE FIND_IN_SET($uid, p.staff_id)"; 
				  $q = "SELECT COUNT(*) FROM " . self::pTable . " WHERE FIND_IN_SET($uid, staff_id) LIMIT 1";
			  } else {
				  $where = null; 
				  $q = "SELECT COUNT(*) FROM " . self::pTable . " LIMIT 1"; 
			  }
		  }

          $record = self::$db->query($q);
          $total = self::$db->fetchrow($record);
          $counter = $total[0];
		  
		  $pager = Paginator::instance();
		  $pager->items_total = $counter;
		  $pager->default_ipp = Registry::get("Core")->perpage;
		  $pager->paginate();	

          $sql = "SELECT p.id as pid, p.title, p.p_status, p.b_status, p.cost, p.start_date, u.id as uid," 
		  . "\n CONCAT(u.fname,' ',u.lname) as fullname, u.username," 
		  . "\n (SELECT CONCAT(fname,' ',lname) FROM " . Users::uTable . " WHERE id = p.staff_id) as staffname, i.recurring" 
		  . "\n FROM " . self::pTable . " as p" 
		  . "\n LEFT JOIN " . Users::uTable ." as u ON u.id = p.client_id" 
		  . "\n LEFT JOIN " . self::iTable . " as i ON i.project_id = p.id" 
		  . "\n $where" 
		  . "\n GROUP BY p.id ORDER BY p.start_date DESC" . $pager->limit;
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Content::processProject()
       * 
       * @return
       */
      public function processProject()
      {
          Filter::checkPost('title', Lang::$word->PROJ_NAME);
		  
		  Filter::checkPost('project_type', Lang::$word->PROJ_TYPE);
		  Filter::checkPost('client_id', Lang::$word->INVC_CLIENTSELECT);
		  Filter::checkPost('staff_id', Lang::$word->PROJ_MANAGER);
			  
		  if (empty($_POST['cost']) or $_POST['cost'] == 0 or !is_numeric($_POST['cost']))
              Filter::$msgs['cost'] = Lang::$word->PROJ_PRICE;

          if (empty(Filter::$msgs)) {
              $progress = str_replace("%", "", $_POST['p_status']);

              $data = array(
					'title' => sanitize($_POST['title']), 
					'client_id' => intval($_POST['client_id']), 
					'staff_id' => Core::_implode($_POST['staff_id']), 
					'project_type' => intval($_POST['project_type']), 
					'body' => $_POST['body'], 
					'start_date' => sanitize($_POST['start_date_submit']), 
					'end_date' => sanitize($_POST['end_date_submit']), 
					'cost' => (float)$_POST['cost'], 
					'p_status' => intval($progress)
			  );

              $pdata['staff_id'] = $data['staff_id'];

			  if (isset($_POST['custom'])) {
				  $fields = $_POST['custom'];
				  $total = count($fields);
				  if (is_array($fields)) {
					  $fielddata = '';
					  foreach ($fields as $fid) {
						  $fielddata .= $fid . "::";
					  }
				  }
				  $data['custom_fields'] = $fielddata;
			  } 
				  
              if (Filter::$id) {
                  $res = self::$db->update(self::pTable, $data, "id=" . Filter::$id);
                  self::$db->update("permissions", $pdata, "project_id=" . Filter::$id);
              } else {
                  $res = self::$db->insert(self::pTable, $data);
                  $lastid = self::$db->insertid();
                  $pdata['project_id'] = (int)$lastid;
                  self::$db->insert("permissions", $pdata);
              }

              $message = (Filter::$id) ? Lang::$word->PROJ_UPDATED : Lang::$word->PROJ_ADDED;

			  if($res) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk($message, false);
			  } else {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
				  
			  }
			  print json_encode($json); 
			  
              if (isset($_POST['notify_staff']) && $_POST['notify_staff'] == 1) {
				  $userrow = self::$db->fetch_all("SELECT email, CONCAT(fname,' ',lname) as fullname, username FROM " . Users::uTable . " WHERE id IN(" . $data['staff_id'] . ")");
                  require_once (BASEPATH . "lib/class_mailer.php");
                  $mailer = Mailer::sendMail();
				  $mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin(20,10));
                  $row = $this->getAllInfo($lastid);
                  $subject = Lang::$word->PROJ_ESUBJECT . cleanOut($data['title']);

                  ob_start();
                  require_once (BASEPATH . 'mailer/Project_From_Admin.tpl.php');
                  $html_message = ob_get_contents();
                  ob_end_clean();

				  if (file_exists(UPLOADS . 'print_logo.png')) {
					  $logo = '<img src="' . UPLOADURL . 'print_logo.png" alt="' . Registry::get("Core")->company . '" style="border:0"/>';
				  } elseif (Registry::get("Core")->logo) {
					  $logo = '<img src="' . UPLOADURL . Registry::get("Core")->logo . '" alt="' . Registry::get("Core")->company . '" style="border:0"/>';
				  } else {
					  $logo = Registry::get("Core")->company;
				  }
			  
				  $replacements = array();
				  if($userrow) {
					  foreach ($userrow as $cols) {
						  $replacements[$cols->email] = array(
								'[LOGO]' => $logo, 
								'[COMPANY]' => Registry::get("Core")->company, 
								'[SITEURL]' => SITEURL, 
								'[DATE]' => date('Y'), 
								'[STAFF_NAME]' => Core::renderName($cols), 
								'[TITLE]' => $row->title, 
								'[NAME]' => Core::renderName($row),
								'[START]' => Filter::dodate("short_date", $row->start_date), 
								'[END]' => Filter::dodate("short_date", $row->end_date),
								'[MSG]' => cleanOut($data['body'])
						  );
			
					  }
				  }
				  $decorator = new Swift_Plugins_DecoratorPlugin($replacements);
				  $mailer->registerPlugin($decorator);
				  
                  $msg = Swift_Message::newInstance()
						  ->setSubject($subject)
						  ->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->company))
						  ->setBody($html_message, 'text/html');

						  foreach ($userrow as $urow) {
							  $msg->setTo($urow->email, Core::renderName($urow));
							  $mailer->send($msg);
						  }
						  unset($urow);
						  
              }
			  
          } else {
              $json['message'] = Filter::msgStatus();
              print json_encode($json);
          }
      }

      /**
       * Content::getProjectList()
       * 
       * @return
       */
      public function getProjectList()
      {
		  $where = (Registry::get("Users")->userlevel == 5) ? "WHERE FIND_IN_SET(" . Registry::get("Users")->uid . ", staff_id)" : null;
		  
          $sql = "SELECT * FROM " . self::pTable . " $where";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Content::getProjectTypes()
       * 
       * @return
       */
      public function getProjectTypes()
      {
          $sql = "SELECT * FROM project_types";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Content::processProjectType()
       * 
       * @return
       */
      public function processProjectType()
      {

          Filter::checkPost('title', Lang::$word->TYPE_NAME);

          if (empty(Filter::$msgs)) {
              $data = array('title' => sanitize($_POST['title']), 'description' => $_POST['description']);

              (Filter::$id) ? self::$db->update("project_types", $data, "id=" . Filter::$id) : self::$db->insert("project_types", $data);
              $message = (Filter::$id) ? Lang::$word->TYPE_UPDATED : Lang::$word->TYPE_ADDED;

			  if(self::$db->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk($message, false);
			  } else {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
			  }
			  print json_encode($json); 
			  
          } else {
              $json['message'] = Filter::msgStatus();
              print json_encode($json);
          }
      }

      /**
       * Content::getProjectFiles()
       * 
       * @return
       */
      public function getProjectFiles($limit = false, $order = false)
      {
          $where = (Registry::get("Users")->userlevel == 5) ? "WHERE f.staff_id = '" . Registry::get("Users")->uid . "'" : $where = null;
		  $sort = ($order) ? "p.title" : "f.created";
		  $limiter = ($limit) ? " LIMIT 0,5" : "";

          $sql = "SELECT f.*, p.title as ptitle, p.id as pid" 
		  . "\n FROM project_files as f" 
		  . "\n LEFT JOIN " . self::pTable . " as p ON p.id = f.project_id" 
		  . "\n $where"
		  . "\n ORDER BY $sort $limiter";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Content::getFilesById()
       * 
       * @return
       */
      public function getFilesById()
      {
          $and = (Registry::get("Users")->userlevel == 5) ? "AND staff_id = '" . Registry::get("Users")->uid . "'" : null;
		  
          $sql = "SELECT * FROM project_files " 
		  . "\n WHERE id = " . Filter::$id 
		  . "\n $and";
          $row = self::$db->first($sql);

          return ($row) ? $row : 0;
      }
	  
      /**
       * Content::getFilesByProject()
       * 
       * @param bool $project_id
       * @return
       */
      public function getFilesByProject($project_id = false)
      {
          $id = ($project_id) ? $project_id : Filter::$id;
          $sql = "SELECT * FROM project_files " 
		  . "\n WHERE project_id = " . $id 
		  . "\n ORDER BY title";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Content::processProjectFile()
       * 
       * @return
       */
      public function processProjectFile()
      {

          Filter::checkPost('title', Lang::$word->FILE_NAME);
          Filter::checkPost('project_id', Lang::$word->FILE_SELPROJ);

          if (!Filter::$id and empty($_FILES['filename']['name']))
              Filter::$msgs['filename'] = Lang::$word->FILE_ATTACH_R;

          $upl = Uploader::instance(Registry::get("Core")->file_max, Registry::get("Core")->file_types);
          if (!empty($_FILES['filename']['name']) and empty(Filter::$msgs)) {
              $dir = UPLOADS . 'data/';
              $upl->upload('filename', $dir);
          }

          if (empty(Filter::$msgs)) {
              $data = array(
					'title' => sanitize($_POST['title']), 
					'filedesc' => $_POST['filedesc'], 
					'created' => "NOW()", 
					'project_id' => intval($_POST['project_id']), 
					'staff_id' => Registry::get("Users")->uid, 
					'version' => sanitize($_POST['version'])
			  );

              $file = getValue("filename", "project_files", "id = " . Filter::$id);
              if (!empty($_FILES['filename']['name'])) {
                  if ($file and is_file(UPLOADS . 'data/' . $file)) {
                      unlink(UPLOADS . 'data/' . $file);
                  }
                  $data['filename'] = $upl->fileInfo['fname'];
                  $data['filesize'] = $upl->fileInfo['size'];
              } else {
                  $data['filename'] = $file;
              }

              (Filter::$id) ? self::$db->update("project_files", $data, "id='" . Filter::$id . "'") : self::$db->insert("project_files", $data);
              $message = (Filter::$id) ? Lang::$word->FILE_UPDATED : Lang::$word->FILE_ADDED;

			  if(self::$db->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk($message, false);
			  } else {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
			  }
			  print json_encode($json); 
			  
          } else {
              $json['message'] = Filter::msgStatus();
              print json_encode($json);
          }
      }
	  
      /**
       * Content::getProjectTasks()
       * 
       * @return
       */
      public function getProjectTasks()
      {
          if (Registry::get("Users")->userlevel == 5) {
			  if (isset($_GET['sort'])) {
				  if ($_GET['sort'] == 'completed') {
					  $q = "SELECT COUNT(*) FROM " . self::tTable . " WHERE progress = 100 AND staff_id = '" . Registry::get("Users")->uid . "' LIMIT 1";
					  $access = "WHERE t.staff_id='" . Registry::get("Users")->uid . "' AND progress = 100";
				  } elseif($_GET['sort'] == 'pending') {
					  $q = "SELECT COUNT(*) FROM " . self::tTable . " WHERE progress <> 100 AND staff_id = '" . Registry::get("Users")->uid . "' LIMIT 1";
					  $access = "WHERE t.staff_id='" . Registry::get("Users")->uid . "' AND progress <> 100";
				  }
			  } else {
				  $q = "SELECT COUNT(*) FROM " . self::tTable . " WHERE staff_id = '" . Registry::get("Users")->uid . "' LIMIT 1";
				  $access = "WHERE t.staff_id='" . Registry::get("Users")->uid . "'";
			  }

          } else {
			  if (isset($_GET['sort'])) {
				  if ($_GET['sort'] == 'completed') {
					  $q = "SELECT COUNT(*) FROM " . self::tTable . " WHERE progress = 100 LIMIT 1";
					  $access = "WHERE progress = 100";
				  } elseif($_GET['sort'] == 'pending') {
					  $q = "SELECT COUNT(*) FROM " . self::tTable . " WHERE progress <>100 LIMIT 1";
					  $access = "WHERE progress <> 100";
				  }
			  } else {
				  $q = "SELECT COUNT(*) FROM " . self::tTable . " LIMIT 1";
				  $access = null;
			  }
				  
          }

		  $record = Registry::get("Database")->query($q);
		  $total = Registry::get("Database")->fetchrow($record);
		  $counter = $total[0];
			  
          $pager = Paginator::instance();
          $pager->items_total = $counter;
          $pager->default_ipp = Registry::get("Core")->perpage;
          $pager->paginate();

          $sql = "SELECT t.*, p.title as ptitle, p.id as pid," 
		  . "\n DATE_FORMAT(t.created, '" . Registry::get("Core")->short_date . "') as start" 
		  . "\n FROM " . self::tTable . " as t" 
		  . "\n LEFT JOIN " . self::pTable . " as p ON p.id = t.project_id" 
		  . "\n $access" 
		  . "\n ORDER BY p.title, t.created DESC" . $pager->limit;
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Content::getTasksByProject()
       * 
       * @return
       */
      public function getTasksByProject()
      {
          $access = (Registry::get("Users")->userlevel == 5) ? "AND t.staff_id='" . Registry::get("Users")->uid . "'" : null;

          $sql = "SELECT t.*," 
		  . "\n DATE_FORMAT(t.created, '" . Registry::get("Core")->short_date . "') as start" 
		  . "\n FROM " . self::tTable . " as t" 
		  //. "\n LEFT JOIN permissions as pp ON pp.project_id = t.project_id" 
		  . "\n WHERE t.project_id = '" . Filter::$id . "'" 
		  . "\n $access" 
		  . "\n ORDER BY t.title";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Content::processProjectTask()
       * 
       * @return
       */
      public function processProjectTask()
      {
          Filter::checkPost('title', Lang::$word->TASK_NAME);
          Filter::checkPost('project_id', Lang::$word->TASK_SELPROJ);

          if (empty(Filter::$msgs)) {
              $progress = str_replace("%", "", $_POST['progress']);
              $data = array(
					'project_id' => intval($_POST['project_id']), 
					'staff_id' => intval($_POST['staff_id']), 
					'client_access' => intval($_POST['client_access']), 
					'author_id' => Registry::get("Users")->uid, 
					'title' => sanitize($_POST['title']), 
					'details' => $_POST['details'], 
					'duedate' => sanitize($_POST['duedate_submit']) . ' ' . date('H:i:s'), 
					'created' => sanitize($_POST['created_submit']) . ' ' . date('H:i:s'), 
					'progress' => intval($progress)
			  );

			  if (isset($_POST['custom'])) {
				  $fields = $_POST['custom'];
				  $total = count($fields);
				  if (is_array($fields)) {
					  $fielddata = '';
					  foreach ($fields as $fid) {
						  $fielddata .= $fid . "::";
					  }
				  }
				  $data['custom_fields'] = $fielddata;
			  } 
			  
              (Filter::$id) ? self::$db->update(self::tTable, $data, "id=" . Filter::$id) : self::$db->insert(self::tTable, $data);
              $message = (Filter::$id) ? Lang::$word->TASK_UPDATED : Lang::$word->TASK_ADDED;

			  if(self::$db->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk($message, false);
			  } else {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
			  }
			  print json_encode($json); 
			  
			  
              if (isset($_POST['notify_staff']) && $_POST['notify_staff'] == 1) {
				  $user = self::$db->first("SELECT email, CONCAT(fname,' ',lname) as fullname, username FROM " . Users::uTable . " WHERE id = " . $data['staff_id']);
                  require_once (BASEPATH . "lib/class_mailer.php");
                  $mailer = Mailer::sendMail();
                  $row = $this->getAllInfo($data['project_id']);
                  $subject = Lang::$word->TASK_ESUBJECT . cleanOut($data['title']);

                  ob_start();
                  require_once (BASEPATH . 'mailer/Task_From_Admin.tpl.php');
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
					  '[TTITLE]',
					  '[NAME]',
					  '[CONTENT]',
					  '[DATE]',
					  '[COMPANY]',
					  '[SITEURL]'), array(
					  $logo,
					  Core::renderName($user),
					  $row->title,
					  $data['title'],
					  Core::renderName($row),
					  cleanOut($data['details']),
					  date('Y'),
					  Registry::get("Core")->company,
					  SITEURL), $html_message);
				  
                  $msg = Swift_Message::newInstance()
						  ->setSubject($subject)
						  ->setTo(array($user->email => Core::renderName($user)))
						  ->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->company))
						  ->setBody($body, 'text/html');

                  $numSent = $mailer->send($msg);
              }
			  
          } else {
              $json['message'] = Filter::msgStatus();
              print json_encode($json);
          }
      }

      /**
       * Content::getTaskTemplates()
       * 
       * @return
       */
      public function getTaskTemplates()
      {

          $sql = "SELECT * FROM task_templates ORDER BY title";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Content::processTaskTemplate()
       * 
       * @return
       */
      public function processTaskTemplate()
      {
          Filter::checkPost('title', Lang::$word->TTASK_NAME);

          if (empty(Filter::$msgs)) {
              $data = array(
					'title' => sanitize($_POST['title']), 
					'details' => $_POST['details']
			  );

              (Filter::$id) ? self::$db->update("task_templates", $data, "id='" . Filter::$id . "'") : self::$db->insert("task_templates", $data);
              $message = (Filter::$id) ? Lang::$word->TTASK_UPDATED : Lang::$word->TTASK_ADDED;

			  if(self::$db->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk($message, false);
			  } else {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
			  }
			  print json_encode($json); 
			  
          } else {
              $json['message'] = Filter::msgStatus();
              print json_encode($json);
          }
      }
	  
      /**
       * Content::getProjectSubmissions()
       * 
       * @param bool $all
       * @return
       */
      public function getProjectSubmissions($all = true)
      {
          $where = ($all) ? "project_id = '" . Filter::$id . "'" : "id = '" . Filter::$id . "'";

          $sql = "SELECT *, DATE_FORMAT(created, '" . Registry::get("Core")->long_date . "') as sdate," 
		  . "\n DATE_FORMAT(review_date, '" . Registry::get("Core")->long_date . "') as rdate" 
		  . "\n FROM " . self::sTable
		  . "\n WHERE $where" 
		  . "\n ORDER BY created";

          $row = ($all) ? self::$db->fetch_all($sql) : self::$db->first($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Content::processProjectSubmission()
       * 
       * @return
       */
      public function processProjectSubmission()
      {
          Filter::checkPost('title', Lang::$word->SUBS_NAME);
          Filter::checkPost('project_id', Lang::$word->INVC_PROJCSELETC);
          Filter::checkPost('description', Lang::$word->SUBS_NOTE);

          if (!empty($_FILES['filename']['name'])) {
              $upl = Uploader::instance(Registry::get("Core")->file_max, Registry::get("Core")->file_types);
              $dir = UPLOADS . 'data/';
              $upl->upload('filename', $dir);
          }

          if (empty(Filter::$msgs)) {
              $data = array(
					'project_id' => intval($_POST['project_id']), 
					'staff_id' => intval($_POST['staff_id']), 
					'title' => sanitize($_POST['title']), 
					'description' => $_POST['description'], 
					's_type' => sanitize($_POST['s_type']), 
					'status' => (isset($_POST['revsend']) && $_POST['revsend'] == 1) ? 1 : 0
			  );
              if (!Filter::$id) {
                  $data['created'] = "NOW()";
              }

			  if (isset($_POST['custom'])) {
				  $fields = $_POST['custom'];
				  $total = count($fields);
				  if (is_array($fields)) {
					  $fielddata = '';
					  foreach ($fields as $fid) {
						  $fielddata .= $fid . "::";
					  }
				  }
				  $data['custom_fields'] = $fielddata;
			  } 
			  
              (Filter::$id) ? self::$db->update(self::sTable, $data, "id='" . Filter::$id . "'") : $lastid = self::$db->insert(self::sTable, $data);
              $message = (Filter::$id) ? Lang::$word->SUBS_UPDATED : Lang::$word->SUBS_ADDED;

              if (!empty($_FILES['filename']['name'])) {
                  $fdata = array(
						'title' => (empty($_POST['filetitle'])) ? sanitize($_POST['title']) : sanitize($_POST['filetitle']), 
						'created' => "NOW()", 
						'project_id' => intval($_POST['project_id']), 
						'staff_id' => intval($_POST['staff_id']), 
						'filename' => $upl->fileInfo['fname'], 
						'filesize' => $upl->fileInfo['size']
				  );
                  self::$db->insert("project_files", $fdata);
              }

              if (isset($_POST['revsend']) && $_POST['revsend'] == 1) {
                  require_once (BASEPATH . "lib/class_mailer.php");
                  $mailer = Mailer::sendMail();
                  $row = $this->getAllInfo($data['project_id']);
                  $subject = Lang::$word->SUBS_SUBJECT . cleanOut($data['title']);

                  ob_start();
                  require_once (BASEPATH . 'mailer/Submission_From_Admin.tpl.php');
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
					  '[NAME]',
					  '[PTITLE]',
					  '[STITLE]',
					  '[STAFF_NAME]',
					  '[CONTENT]',
					  '[DATE]',
					  '[COMPANY]',
					  '[SITEURL]'), array(
					  $logo,
					  Core::renderName($row),
					  $row->title,
					  $data['title'],
					  $row->staffname,
					  cleanOut($data['description']),
					  date('Y'),
					  Registry::get("Core")->company,
					  SITEURL), $html_message);
						  
                  $msg = Swift_Message::newInstance()
						  ->setSubject($subject)
						  ->setTo(array($row->email => Core::renderName($row)))
						  ->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->company))
						  ->setBody($body, 'text/html');

                  $numSent = $mailer->send($msg);
              }

			  
			  if(self::$db->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk($message, false);
			  } else {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
			  }
			  print json_encode($json); 
			  
          } else {
              $json['message'] = Filter::msgStatus();
              print json_encode($json);
          }
      }

      /**
       * Content::getProjectInvoices()
       * 
       * @return
       */
      public function getProjectInvoices()
      {
          $where = (Filter::$id) ? "WHERE project_id = '" . Filter::$id . "'" : null;

          $sql = "SELECT i.*," 
		  . "\n p.title as ptitle, CONCAT(u.fname,' ',u.lname) as fullname, u.fname, u.lname, u.username" 
		  . "\n FROM " . self::iTable . " as i" 
		  . "\n LEFT JOIN " . self::pTable . " as p ON p.id = i.project_id" 
		  . "\n LEFT JOIN " . Users::uTable . " as u ON u.id = i.client_id" 
		  . "\n $where" 
		  . "\n ORDER BY i.created";

          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Content::getProjectInvoiceById()
       * 
       * @return
       */
      public function getProjectInvoiceById()
      {
          $sql = "SELECT i.*," 
		  . "\n p.title as ptitle, CONCAT(u.fname,' ',u.lname) as name, u.fname, u.lname, u.username, u.email, u.address, u.city, u.zip, u.state, u.phone, u.company, u.vat, u.currency"
		  . "\n FROM " . self::iTable . " as i" 
		  . "\n LEFT JOIN " . self::pTable . " as p ON p.id = i.project_id" 
		  . "\n LEFT JOIN " . Users::uTable . " as u ON u.id = i.client_id" 
		  . "\n WHERE i.id = '" . Filter::$id . "'";

          $row = self::$db->first($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Content::getProjectInvoiceData()
       * 
       * @param bool $invid
       * @return
       */
      public function getProjectInvoiceData($invid = false)
      {
          $id = ($invid) ? intval($invid) : Filter::$id;

          $sql = "SELECT * FROM " . self::idTable . " WHERE invoice_id = '" . (int)$id . "' ORDER BY id";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Content::getInvoiceData()
       * 
       * @return
       */
      public function getInvoiceData()
      {

          $sql = "SELECT * FROM " . self::idTable . " ORDER BY title";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
      /**
       * Content::getProjectInvoicePayments()
       * 
       * @param bool $invid
       * @return
       */
      public function getProjectInvoicePayments($invid = false)
      {
          $id = ($invid) ? intval($invid) : Filter::$id;

          $sql = "SELECT *," 
		  . "\n DATE_FORMAT(created, '" . Registry::get("Core")->short_date . "') as cdate" 
		  . "\n FROM " . self::ipTable
		  . "\n WHERE invoice_id = '" . (int)$id . "'";

          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Content::updateInvoice()
       * 
       * @return
       */
      public function updateInvoice()
      {
          Filter::checkPost('title', Lang::$word->INVC_NAME);

          if (empty(Filter::$msgs)) {
              $data = array(
					'title' => sanitize($_POST['title']), 
					'duedate' => sanitize($_POST['duedate_submit']), 
					'method' => sanitize($_POST['method']), 
					'status' => sanitize($_POST['status']),
					'notes' => sanitize($_POST['notes']),
					'onhold' => isset($_POST['onhold']) ? intval($_POST['onhold']) : 0,
					'comment' => sanitize($_POST['comment'])
			  );
			  
			  if($_POST['status'] == 'Paid') {
				  $data['amount_paid'] = floatval($_POST['amount_total']);
				  $row = self::$db->first("SELECT SUM(amount_total-tax) as total, project_id FROM " . self::iTable . " WHERE id = " . Filter::$id . " GROUP BY id");
				  
				  $edata = array(
						'invoice_id' => Filter::$id, 
						'project_id' => intval($row->project_id), 
						'method' => $data['method'], 
						'amount' => floatval($row->total),
						'created' => "NOW()",
						'description' => "Payment added by admin"
				  );
				  $pdata['b_status'] = $data['amount_paid'];
				  self::$db->insert(self::ipTable, $edata);
				  self::$db->update(self::pTable, $pdata, "id=" . $edata['project_id']);
				  
			  }
			  
              self::$db->update(self::iTable, $data, "id=" . Filter::$id);

			  if(self::$db->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk(Lang::$word->INVC_UPDATED, false);
			  } else {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
			  }
			  print json_encode($json);
			  

          } else {
              $json['message'] = Filter::msgStatus();
              print json_encode($json);
          }
      }

      /**
       * Content::addInvoice()
       * 
       * @return
       */
      public function addInvoice()
      {
          Filter::checkPost('title', Lang::$word->INVC_NAME);
          Filter::checkPost('project_id', Lang::$word->INVC_PROJCSELETC);
          Filter::checkPost('client_id', Lang::$word->INVC_CLIENTSELECT);
          Filter::checkPost('duedate', Lang::$word->INVC_DUEDATE);
          
		  $dtitle = array_filter($_POST['dtitle'], 'strlen');
          if (empty($dtitle))
              Filter::$msgs['dtitle'] = Lang::$word->INVC_ENTRYTITLE_R;
			  
          $amount = array_filter($_POST['amount'], 'is_numeric');
          if (!$amount or array_sum($_POST['amount']) == 0)
              Filter::$msgs['amount'] = Lang::$word->INVC_ENTRYAMOUNT_R;

          if (empty(Filter::$msgs)) {
			  
              $amount_total = array_sum($_POST['amount']);
              if (intval($_POST['tax']) == 1 and Registry::get("Core")->enable_tax) {
                  $tax = (floatval($amount_total) * Registry::get("Core")->tax_rate);
                  $amount_total = ($amount_total + $tax);
              } else {
                  $tax = 0;
              }
              $data = array(
					'title' => sanitize($_POST['title']), 
					'project_id' => intval($_POST['project_id']), 
					'client_id' => intval($_POST['client_id']), 
					'created' => (empty($_POST['submit_created'])) ? "NOW()" : sanitize($_POST['submit_created']), 
					'duedate' => sanitize($_POST['submit_duedate']), 
					'amount_total' => $amount_total,
					'amount_paid' => 0, 
					'recurring' => intval($_POST['recurring']), 
					'method' => sanitize($_POST['method']), 
					'notes' => $_POST['notes'],
					'comment' => sanitize($_POST['comment']),
					'tax' => $tax, 
					'onhold' => intval($_POST['onhold']),
					'status' => 'Unpaid'
			  );

              $lastid = self::$db->insert(self::iTable, $data);
              //(self::$db->affected()) ? Filter::msgOk(Lang::$word->INVC_ADDED) : Filter::msgAlert(Lang::$word->NOPROCCESS);

			  if(self::$db->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk(Lang::$word->INVC_ADDED, false);
			  } else {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
			  }
			  print json_encode($json); 
			  
			  foreach ($_POST['amount'] as $key => $val) {
				  $edata = array(
						'title' => sanitize($_POST['dtitle'][$key]), 
						'invoice_id' => $lastid, 
						'description' => sanitize($_POST['description'][$key]), 
						'amount' => floatval($_POST['amount'][$key]), 
						'recurring' => intval($_POST['recurring']),
						'days' => intval($_POST['days']),
						'period' => sanitize($_POST['period']),
						'tax' => (intval($_POST['tax']) == 1 and Registry::get("Core")->enable_tax) ? (floatval($_POST['amount'][$key]) * Registry::get("Core")->tax_rate) : 0
				  );
				  self::$db->insert(self::idTable, $edata);
			  }
			  
			  $row = self::$db->first("SELECT SUM(amount) as amtotal, SUM(tax) as itax FROM " . self::idTable . " WHERE invoice_id = '" . $edata['invoice_id'] . "' GROUP BY invoice_id");
			  $idata = array('amount_total' => $row->amtotal + $row->itax, 'tax' => $row->itax);
			  $pdata['cost'] = $idata['amount_total'];
			  $pdata['b_status'] = -1.00;

			  self::$db->update(self::iTable, $idata, "id='" . $edata['invoice_id'] . "'");
			  self::$db->update(self::pTable, $pdata, "id='" . $data['project_id'] . "'");

          } else {
              $json['message'] = Filter::msgStatus();
              print json_encode($json);
          }
      }

      /**
       * Content::sendInvoice()
       * 
       * @param mixed $id
       * @return
       */
      public function sendInvoice($id)
      {
          $row = self::$db->first("SELECT i.*, i.created as cdate, i.duedate as ddate," 
		  . "\n p.title as ptitle, CONCAT(u.fname,' ',u.lname) as fullname, u.fname, u.vat, u.lname, u.username, u.email, u.address, u.city, u.company, u.zip, u.state, u.phone" 
		  . "\n FROM " . self::iTable . " as i" 
		  . "\n LEFT JOIN " . self::pTable . " as p ON p.id = i.project_id" 
		  . "\n LEFT JOIN " . Users::uTable . " as u ON u.id = i.client_id" 
		  . "\n WHERE i.id = '" . (int)$id . "'");
		  
          if ($row) {
              $invdata = self::$db->fetch_all("SELECT i.*, i.created as cdate, i.duedate as ddate," 
			  . "\n id.title as idtitle, id.description, id.amount,id.tax" 
			  . "\n FROM " . self::iTable . " as i" 
			  . "\n LEFT JOIN " . self::idTable . " as id ON id.invoice_id = i.id" 
			  . "\n WHERE i.id = '" . (int)$id . "'");

              $paydata = self::$db->fetch_all("SELECT *, created as cdate" 
			  . "\n FROM " . self::ipTable 
			  . "\n WHERE invoice_id = '" . (int)$id . "'");
              
			  Filter::$id = $id;
			  ob_start();
			  require_once(BASEPATH . 'admin/print_pdf.php');
			  $pdf_html = ob_get_contents();
			  ob_end_clean();
			  
			  require_once(BASEPATH . 'lib/mPdf/mpdf.php');
			  $mpdf = new mPDF('utf-8', Registry::get("Core")->pagesize);
			  $mpdf->SetTitle($row->title);
			  $mpdf->SetAutoFont();
			  $mpdf->WriteHTML($pdf_html);
			  $pdf_content = $mpdf->Output($row->title . ".pdf", "S");
	  
              require_once (BASEPATH . "lib/class_mailer.php");
              $mailer = Mailer::sendMail();
              $subject = Lang::$word->INVC_SUBJECT . cleanOut($row->ptitle);

              ob_start();
              require_once (BASEPATH . 'mailer/Email_Invoice.tpl.php');
              $html_message = ob_get_contents();
              ob_end_clean();

			  if (file_exists(UPLOADS . 'print_logo.png')) {
				  $logo = '<img src="' . UPLOADURL . 'print_logo.png" alt="' . Registry::get("Core")->company . '" style="border:0"/>';
			  } elseif (Registry::get("Core")->logo) {
				  $logo = '<img src="' . UPLOADURL . Registry::get("Core")->logo . '" alt="' . Registry::get("Core")->company . '" style="border:0"/>';
			  } else {
				  $logo = Registry::get("Core")->company;
			  }
			  
			  $invoice = '';
			  if ($invdata) {
				  foreach ($invdata as $irow) {
					  $invoice .= '
					  <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-bottom-width: 4px; border-left-width: 1px; border-bottom-style: solid; border-left-style: solid; border-bottom-color: #7C8083; border-left-color: #7C8083;font-family: Helvetica Neue,Helvetica,Arial, sans-serif; font-size:13px;">
					  <tr>
						<td valign="top" style="padding:5px;border-right-width: 1px; border-right-style: solid; border-right-color: #7C8083; border-top-width: 1px; border-top-style: solid; border-top-color: #7C8083;"><h4 style="margin:0px;padding:0px;font-size: 14px;">' . $irow->title . '</h4>
						  ' . $irow->description . '</td>
						<td width="250" align="right" valign="top" style="padding:5px;border-right-width: 1px; border-right-style: solid; border-right-color: #7C8083; border-top-width: 1px; border-top-style: solid; border-top-color: #7C8083;">' . $irow->amount . '</td>
					  </tr>';
				  }
				  if ($row->tax) {
					  $invoice .= '
					  <tr>
						<td align="right" valign="top" style="padding:5px;border-right-width: 1px; border-right-style: solid; border-right-color: #7C8083; border-top-width: 1px; border-top-style: solid; border-top-color: #7C8083;">' . Registry::get("Core")->tax_name . ':</td>
						<td align="right" valign="top" style="padding:5px;border-right-width: 1px; border-right-style: solid; border-right-color: #7C8083; border-top-width: 1px; border-top-style: solid; border-top-color: #7C8083;">' . $row->tax . '</td>
					  </tr>';
				  }
				  $invoice .= '
				  <tr>
					<td align="right" valign="top" style="padding:5px;border-right-width: 1px; border-right-style: solid; border-right-color: #7C8083; border-top-width: 1px; border-top-style: solid; border-top-color: #7C8083;"><strong>' . Lang::$word->INVC_TOTAL . ':</strong></td>
					<td align="right" valign="top" style="padding:5px;border-right-width: 1px; border-right-style: solid; border-right-color: #7C8083; border-top-width: 1px; border-top-style: solid; border-top-color: #7C8083;">' . $row->amount_total . '</td>
				  </tr>
				  </table>';
			  }				  
			  $payments = '';

			  if ($paydata) {
				  $payments .= '
				  <div style="height:20px"></div>
				  <h3 style="font-size: 15px; margin: 0px; padding: 0px;">' . Lang::$word->INVC_PRECORD . '</h3>
				  <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-bottom-width: 4px; border-left-width: 1px; border-bottom-style: solid; border-left-style: solid; border-bottom-color: #7C8083; border-left-color: #7C8083;">
					<tr>
					  <td valign="top" style="background-color:#7C8083;padding:5px;border-right-width: 1px; border-right-style: solid; border-right-color: #7C8083; border-top-width: 1px; border-top-style: solid; border-top-color: #7C8083;"><strong style="color:white">' . Lang::$word->INVC_PRECORD . '</strong></td>
					  <td align="right" valign="top" style="background-color:#7C8083;padding:5px;border-right-width: 1px; border-right-style: solid; border-right-color: #7C8083; border-top-width: 1px; border-top-style: solid; border-top-color: #7C8083;"><strong style="color:white">' . Lang::$word->TRANS_PAYAMOUNT . '</strong></td>
					</tr>';
				  foreach ($paydata as $prow) {
					  $payments .= '
					  <tr>
						<td valign="top" style="padding:5px;border-right-width: 1px; border-right-style: solid; border-right-color: #7C8083; border-top-width: 1px; border-top-style: solid; border-top-color: #7C8083;"><h4 style="margin:0px;padding:0px;font-size: 14px;">' . Lang::$word->INVC_PREF . ': ' . $prow->cdate . '</h4>
						  ' . $prow->description . '</td>
						<td width="250" align="right" valign="top" style="padding:5px;border-right-width: 1px; border-right-style: solid; border-right-color: #7C8083; border-top-width: 1px; border-top-style: solid; border-top-color: #7C8083;">' . $prow->amount . '</td>
					  </tr>';
				  }
			
			  }  
			  
			  $body = str_replace(array(
				  '[LOGO]',
				  '[INVID]',
				  '[DATE]',
				  '[COMPANY]',
				  '[ADDRESS]',
				  '[ADDRESS2]',
				  '[PHONE]',
				  '[FAX]',
				  '[NAME]',
				  '[CCOMPANY]',
				  '[CADDRESS]',
				  '[CADDRESS2]',
				  '[CPHONE]',
				  '[TAXNUMBER]',
				  '[VAT]',
				  '[AMTOUNT]',
				  '[TOTAL]',
				  '[PAID]',
				  '[PDATE]',
				  '[INVOICEDATA]',
				  '[PAYMENTDATA]',
				  '[NOTES]',
				  '[SITEURL]'), array(
				  $logo,
				  Registry::get("Core")->invoice_number . $row->id,
				  date('M d Y'),
				  Registry::get("Core")->company,
				  Registry::get("Core")->address,
				  Registry::get("Core")->city.', '.Registry::get("Core")->state.' '.Registry::get("Core")->zip,
				  (Registry::get("Core")->phone) ? Lang::$word->CONF_PHONE. ': ' . Registry::get("Core")->phone : '',
				  (Registry::get("Core")->fax) ? Lang::$word->CONF_FAX. ': ' . Registry::get("Core")->fax : '',
				  $row->name,
				  $row->company,
				  $row->address,
				  $row->city . ', ' . $row->state . ' ' . $row->zip,
				  ($row->phone) ? Lang::$word->CONF_PHONE. ': ' . $row->phone : '',
				  Registry::get("Core")->tax_number,
				  ($row->vat) ? Lang::$word->UVAT. ': ' . $row->vat : '',
				  (compareFloatNumbers($row->amount_paid, $row->amount_total, "=") == true) ? Lang::$word->INVC_PAID : '<span style="color:#F00000">' . Lang::$word->INVC_DUE . '</span>',
				  $row->amount_total,
				  (compareFloatNumbers($row->amount_paid, $row->amount_total, "=") == true) ? $row->amount_paid : '<span style="color:#F00000">'. $row->amount_paid . '</span>',
				  Filter::dodate("short_date", $row->duedate),
				  $invoice,
				  $payments,
				  cleanOut($row->notes),
				  SITEURL), $html_message);

              $msg = Swift_Message::newInstance()
					  ->setSubject($subject)
					  ->setTo(array($row->email => $row->name))
					  ->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->company))
					  ->setBody($body, 'text/html');
					  
              $msg->attach(Swift_Attachment::newInstance($pdf_content, cleanOut(preg_replace("/[^a-zA-Z0-9\s]/", "", $row->title)) . '.pdf', 'application/pdf'));
			  
			  if($mailer->send($msg)) {
				  $json['type'] = 'success';
				  $json['title'] = Lang::$word->SUCCESS;
				  $json['message'] =  Lang::$word->INVC_SENT_OK;
			  } else {
				  $json['type'] = 'error';
				  $json['title'] = Lang::$word->ERROR;
				  $json['message'] = Lang::$word->INVC_SENT_ERR;
			  }
			  
			  print json_encode($json);
		
          }
      }

      /**
       * Content::loadInvoiceEntries()
       * 
       * @param mixed $invid
       * @return
       */
	  public function loadInvoiceEntries($invid)
	  {
		  $invdata = $this->getProjectInvoiceData($invid);
		  print '
			<table cellpadding="0" cellspacing="0" class="display">
			  <thead>
				<tr>
				  <th width="20">#</th>
				  <th width="20%" nowrap="nowrap" class="left">' .Lang::$word->INVC_ENTRYTITLE . '</th>
				  <th width="40%" class="left">' . Lang::$word->DESC . '</th>
				  <th class="left">' . Lang::$word->AMOUNT . '</th>
				  <th>' . Lang::$word->EDIT . '</th>
				  <th>' . Lang::$word->DELETE . '</th>
				</tr>
			  </thead>';
		  if (!$invdata) {
			  print '
				<tr>
				  <td colspan="6">' . Filter::msgInfo(Lang::$word->INVC_NOENTRY, false) . '</td>
				</tr>';
		  } else {
			  foreach ($invdata as $irow) {
				  print '
					<tr>
					  <th align="center">' . $irow->id . '.</th>
					  <td>' . $irow->title . '</td>
					  <td>' . $irow->description . '</td>
					  <td>' . $irow->amount . '</td>
					  <td align="center"><a href="index.php?do=invoices&amp;action=editentry&amp;id=' . $irow->id . '">'
					  . '<img src="../images/edit.png" alt="" class="tooltip img-wrap2" title="' . Lang::$word->EDIT.': '.$irow->title . '"/></a></td>
					  <td align="center"><a href="javascript:void(0);" class="delete" id="item_' . $irow->id.':'.$irow->project_id.':'.$irow->invoice_id . '" rel="' . $irow->title . '">'
					  . '<img src="../images/delete.png" alt="" class="tooltip img-wrap2" title="' . Lang::$word->DELETE.': '.$irow->title . '" /></a></td>
					</tr>';
			  }
			  unset($irow);
		  }
		  print '
			</table>';
	  }

	  /**
	   * Content::processInvoiceEntry()
	   * 
	   * @return
	   */
	  public function processInvoiceEntry()
	  {
		  Filter::checkPost('etitle', Lang::$word->INVC_ENTRYTITLE_R);
		  if (empty($_POST['eamount']) or !is_numeric($_POST['eamount']))
			  Filter::$msgs['eamount'] = Lang::$word->INVC_ENTRYAMOUNT_R;
	
		  if (empty(Filter::$msgs)) {
			  $edata = array(
				  'title' => sanitize($_POST['etitle']),
				  'project_id' => intval($_POST['project_id']),
				  'invoice_id' => intval($_POST['invoice_id']),
				  'description' => sanitize($_POST['edesc']),
				  'amount' => floatval($_POST['eamount']),
				  'tax' => (intval($_POST['etax']) == 1 and Registry::get("Core")->enable_tax) ? floatval($_POST['eamount']) * Registry::get("Core")->tax_rate : 0.00);
	
			  (Filter::$id) ? self::$db->update(self::idTable, $edata, "id=" . Filter::$id) : $last_id = self::$db->insert(self::idTable, $edata);
			  
			  $res = self::$db->affected();

			  $row = self::$db->first("SELECT SUM(amount) as amtotal, SUM(tax) as itax FROM " . self::idTable . " WHERE invoice_id = " . $edata['invoice_id'] . " GROUP BY invoice_id");
			  $data = array('amount_total' => $row->amtotal + $row->itax, 'tax' => $row->itax);
			  $pdata['cost'] = $data['amount_total'];
	
			  self::$db->update(self::iTable, $data, "id=" . $edata['invoice_id']);
			  $res = self::$db->update(self::pTable, $pdata, "id=" . $edata['project_id']);
	
			  if (isset($_POST['add_entry'])) {
				  $html = '
					<tr>
					  <td>' . $edata['title'] . '</td>
					  <td>' . $edata['description'] . '</td>
					  <td>' . $edata['amount'] . '</td>
					  <td><a href="index.php?do=invoices&amp;action=editentry&amp;pid=' . $edata['project_id'] . '&amp;id=' . $last_id . '"><i class="rounded inverted success icon pencil link"></i></a> <a class="delete" data-title="' . Lang::$word->INVC_DELENTRY . '" data-extra="' . $edata['project_id'] . ':' . $edata['invoice_id'] . '" data-option="deleteInvoiceEntry" data-id="' . $last_id . '" data-name="' . $edata['title'] . '"><i class="rounded danger inverted remove icon link"></i></a></td>
					</tr>';
	
				  $json['type'] = 'success';
				  $json['message'] = $html;
				  $json['info'] = Filter::msgOk(Lang::$word->INVC_ENTRY_ADDED, false);
				  print json_encode($json);
			  } else {
				  if($res) {
					  $json['type'] = 'success';
					  $json['message'] = Filter::msgOk(Lang::$word->INVC_ENTRY_UPDATED, false);
				  } else {
					  $json['type'] = 'success';
					  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
				  }
				  print json_encode($json);
			  }
		  } else {
			  $json['message'] = Filter::msgStatus();
			  print json_encode($json);
		  }

	  }

	  /**
	   * Content::loadInvoiceRecords()
	   * 
	   * @param mixed $invid
	   * @return
	   */
	  public function loadInvoiceRecords($invid)
	  {
		  $paydata = $this->getProjectInvoicePayments($invid);
		  print '
			<table cellpadding="0" cellspacing="0" class="display">
			  <thead>
				<tr>
				  <th width="20">#</th>
				  <th width="20%" nowrap="nowrap" class="left">' . Lang::$word->INVC_RECPAID . '</th>
				  <th width="40%" class="left">' . Lang::$word->DESC . '</th>
				  <th class="left">' . Lang::$word->AMOUNT . '</th>
				  <th>' . Lang::$word->EDIT . '</th>
				  <th>' . Lang::$word->DELETE . '</th>
				</tr>
			  </thead>';
		  if (!$paydata) {
			  print '
				<tr>
				  <td colspan="6">' . Filter::msgInfo(Lang::$word->INVC_NORECORD, false) . '</td>
				</tr>';
		  } else {
			  foreach ($paydata as $prow) {
				  print '
					<tr>
					  <th align="center">' . $prow->id . '.</th>
					  <td>' . $prow->cdate . '</td>
					  <td>' . $prow->description . '</td>
					  <td>' . $prow->amount . '</td>
					  <td align="center"><a href="index.php?do=invoices&amp;action=editentry&amp;id=' . $prow->id . '">'
					  . '<img src="../images/edit.png" alt="" class="tooltip img-wrap2" title="' . Lang::$word->EDIT . '"/></a></td>
					  <td align="center"><a href="javascript:void(0);" class="delete" rel="' . $prow->cdate . '" id="item_' . $prow->id . ':' . $prow->project_id . ':' . $prow->invoice_id . '">'
					  . '<img src="../images/delete.png" alt="" class="tooltip img-wrap2" title="' . Lang::$word->DELETE . '" /></a></td>
					</tr>';
			  }
			  unset($prow);
		  }
		  print '
			</table>';
	  }

	  /**
	   * Content::processInvoiceRecord()
	   * 
	   * @return
	   */
	  public function processInvoiceRecord()
	  {
		  if (empty($_POST['ramount']) or !is_numeric($_POST['ramount']))
			  Filter::$msgs['ramount'] = Lang::$word->INVC_RECAMOUNT_T;

		  if (empty(Filter::$msgs)) {
			  $edata = array(
					'project_id' => intval($_POST['project_id']), 
					'invoice_id' => intval($_POST['invoice_id']), 
					'description' => sanitize($_POST['rdesc']), 
					'amount' => floatval($_POST['ramount']), 
					'created' => sanitize($_POST['rcreated_submit']), 
					'method' => sanitize($_POST['method'])
			  );

			  (Filter::$id) ? $res = self::$db->update(self::ipTable, $edata, "id=" . Filter::$id) : $last_id = self::$db->insert(self::ipTable, $edata);

			  $row = self::$db->first("SELECT SUM(amount) as amtotal FROM " . self::ipTable . " WHERE invoice_id = " . $edata['invoice_id'] . " GROUP BY invoice_id");
			  $data['amount_paid'] = $row->amtotal;
			  $pdata['b_status'] = $data['amount_paid'];

			  self::$db->update(self::iTable, $data, "id=" . $edata['invoice_id']);
			  self::$db->update(self::pTable, $pdata, "id=" . $edata['project_id']);

			  $row2 = self::$db->first("SELECT amount_total, amount_paid FROM " . self::iTable . " WHERE id = " . $edata['invoice_id']);
			  $idata['status'] = ($row2->amount_total == $row2->amount_paid) ? 'Paid' : 'Unpaid';
			  self::$db->update(self::iTable, $idata, "id=" . $edata['invoice_id']);
			  
			  if (isset($_POST['add_record'])) {
				  $html = '
					<tr>
					  <td>' . Filter::dodate("short_date", $edata['created']) . '</td>
					  <td>' . $edata['description'] . '</td>
					  <td>' . $edata['amount'] . '</td>
					  <td><a href="index.php?do=invoices&amp;action=editrecord&amp;pid=' . $edata['project_id'] . '&amp;id=' . $last_id . '"><i class="rounded inverted success icon pencil link"></i></a> <a class="delete" data-title="' . Lang::$word->INVC_DELRECORD . '" data-extra="' . $edata['project_id'] . ':' . $edata['invoice_id'] . '" data-option="deleteInvoiceRecord" data-id="' . $last_id . '" data-name="' . sanitize($_POST['ptitle']) . '"><i class="rounded danger inverted remove icon link"></i></a></td>
					</tr>';
	
				  $json['type'] = 'success';
				  $json['message'] = $html;
				  $json['info'] = Filter::msgOk(Lang::$word->INVC_REC_ADDED, false);
				  print json_encode($json);
			  } else {
				  if($res) {
					  $json['type'] = 'success';
					  $json['message'] = Filter::msgOk(Lang::$word->INVC_REC_UPDATED, false);
				  } else {
					  $json['type'] = 'success';
					  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
				  }
				  print json_encode($json);
			  }
		  } else {
			  $json['message'] = Filter::msgStatus();
			  print json_encode($json);
		  }
	  }

	  /**
	   * Content::getInvoicesByStatus()
	   * 
	   * @return
	   */
	  public function getInvoicesByStatus()
	  {

		  if (isset($_POST['fromdate_submit']) && $_POST['fromdate_submit'] <> "" || isset($from) && $from != '') {
			  $enddate = date("Y-m-d");
			  $fromdate = (empty($from)) ? $_POST['fromdate_submit'] : $from;
			  if (isset($_POST['enddate_submit']) && $_POST['enddate_submit'] <> "") {
				  $enddate = $_POST['enddate_submit'];
			  }
			  $q = "SELECT COUNT(*) FROM " . self::iTable . " WHERE duedate BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'";
			  $where = " WHERE i.duedate BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'";
			  
		  } else {
			  $q = "SELECT COUNT(*) FROM " . self::iTable . " LIMIT 1";
			  $where = null;
		  }
		  
          $record = self::$db->query($q);
          $total = self::$db->fetchrow($record);
          $counter = $total[0];
		  
		  $pager = Paginator::instance();
		  $pager->items_total = $counter;
		  $pager->default_ipp = Registry::get("Core")->perpage;
		  $pager->paginate();
		  
		  $sql = "SELECT i.*, CONCAT(u.fname,' ',u.lname) as name" 
		  . "\n FROM " . self::iTable . " as i" 
		  . "\n LEFT JOIN " . Users::uTable . " as u ON u.id = i.client_id" 
		  . "\n $where" 
		  . "\n ORDER BY i.created DESC" . $pager->limit;
		  $row = self::$db->fetch_all($sql);

		  return ($row) ? $row : 0;
	  }

      /**
       * Content::addQuote()
       * 
       * @return
       */
      public function addQuote()
      {
          Filter::checkPost('title', Lang::$word->QUTS_NAME);
          Filter::checkPost('client_id', Lang::$word->INVC_CLIENTSELECT);
          Filter::checkPost('expire_submit', Lang::$word->QUTS_EXPIRE);
          
		  $dtitle = array_filter($_POST['dtitle'], 'strlen');
          if (empty($dtitle))
              Filter::$msgs['dtitle'] = Lang::$word->INVC_ENTRYTITLE_R;
			  
          $amount = array_filter($_POST['amount'], 'is_numeric');
          if (!$amount or array_sum($_POST['amount']) == 0)
              Filter::$msgs['amount'] = Lang::$word->INVC_ENTRYAMOUNT_R;

          if (empty(Filter::$msgs)) {
			  
              $amount_total = array_sum($_POST['amount']);
              if (intval($_POST['tax']) == 1 and Registry::get("Core")->enable_tax) {
                  $tax = (floatval($amount_total) * Registry::get("Core")->tax_rate);
                  $amount_total = ($amount_total + $tax);
              } else {
                  $tax = 0;
              }
              $data = array(
					'title' => sanitize($_POST['title']), 
					'project_id' => intval($_POST['project_id']), 
					'client_id' => intval($_POST['client_id']), 
					'created' => (empty($_POST['created_submit'])) ? "NOW()" : sanitize($_POST['created_submit']), 
					'expire' => sanitize($_POST['expire_submit']), 
					'amount_total' => $amount_total,
					'method' => sanitize($_POST['method']), 
					'notes' => $_POST['notes'],
					'comment' => sanitize($_POST['comment']),
					'tax' => $tax, 
					'status' => 'Pending'
			  );

              $lastid = self::$db->insert("quotes", $data);

			  if(self::$db->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk(Lang::$word->QUTS_ADDED, false);
			  } else {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
			  }
			  print json_encode($json);
				  
			  foreach ($_POST['amount'] as $key => $val) {
				  $edata = array(
						'title' => sanitize($_POST['dtitle'][$key]), 
						'project_id' => $data['project_id'], 
						'quote_id' => $lastid, 
						'description' => sanitize($_POST['description'][$key]), 
						'amount' => floatval($_POST['amount'][$key]), 
						'tax' => (intval($_POST['tax']) == 1 and Registry::get("Core")->enable_tax) ? (floatval($_POST['amount'][$key]) * Registry::get("Core")->tax_rate) : 0
				  );
				  self::$db->insert("quotes_data", $edata);
			  }
			  
			  $row = self::$db->first("SELECT SUM(amount) as amtotal, SUM(tax) as itax FROM quotes_data WHERE quote_id = '" . $edata['quote_id'] . "' GROUP BY quote_id");
			  $idata = array('amount_total' => $row->amtotal + $row->itax, 'tax' => $row->itax);

			  self::$db->update("quotes", $idata, "id='" . $lastid . "'");

		  } else {
			  $json['message'] = Filter::msgStatus();
			  print json_encode($json);
		  }
	  }

      /**
       * Content::convertQuote()
       * 
       * @return
       */
      public function convertQuote()
      {
          Filter::checkPost('title', Lang::$word->QUTS_NAME);
          Filter::checkPost('project_id', Lang::$word->INVC_PROJCSELETC);
          
          if (empty(Filter::$msgs)) {
			  $row = Registry::get("Core")->getRowById("quotes", Filter::$id);
			  
              $data = array(
					'title' => $row->title, 
					'project_id' => intval($_POST['project_id']), 
					'client_id' => $row->client_id, 
					'created' => "NOW()", 
					'duedate' => sanitize($_POST['expire_submit']), 
					'amount_total' => $row->amount_total,
					'amount_paid' => 0, 
					'recurring' => 0, 
					'method' => sanitize($_POST['method']), 
					'notes' => $row->notes,
					'comment' => $row->comment,
					'tax' => $row->tax, 
					'onhold' => 0,
					'status' => 'Unpaid'
			  );

              $lastid = self::$db->insert(self::iTable, $data);

			  if(self::$db->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk(Lang::$word->QUTS_CONVERTED, false);
			  } else {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
			  }
			  print json_encode($json);
				  
			  $quotedata = $this->getQuoteEntries($qid = false);
			  foreach ($quotedata as $val) {
				  $edata = array(
						'title' => $val->title, 
						'invoice_id' => $lastid, 
						'project_id' => intval($_POST['project_id']), 
						'description' => $val->description, 
						'amount' => $val->amount, 
						'tax' => $val->tax
				  );
				  self::$db->insert(self::idTable, $edata);
			  }
			  
			  self::$db->delete("quotes", "id='" . $row->id . "'");
			  self::$db->delete("quotes_data", "quote_id='" . $row->id . "'");

		  } else {
			  $json['message'] = Filter::msgStatus();
			  print json_encode($json);
		  }
	  }
	  
      /**
       * Content::getQuotes()
       * 
       * @return
       */
      public function getQuotes()
      {

          $sql = "SELECT q.*, CONCAT(u.fname,' ',u.lname) as fullname, u.username" 
		  . "\n FROM quotes as q" 
		  . "\n LEFT JOIN " . Users::uTable . " as u ON u.id = q.client_id" 
		  . "\n ORDER BY q.created";

          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Content::getQuoteById()
       * 
       * @return
       */
      public function getQuoteById()
      {

          $sql = "SELECT q.*, CONCAT(u.fname,' ',u.lname) as fullname, u.username, u.email, u.address, u.city, u.zip, u.state, u.phone, u.company, u.vat" 
		  . "\n FROM quotes as q" 
		  . "\n LEFT JOIN " . Users::uTable . " as u ON u.id = q.client_id" 
		  . "\n WHERE q.id = " . Filter::$id;

          $row = self::$db->first($sql);

          return ($row) ? $row : 0;
      }
		  
      /**
       * Content::getQuoteEntries()
       * 
       * @return
       */
      public function getQuoteEntries($qid = false)
      {
		  $id = ($qid) ? intval($qid) : Filter::$id;

          $sql = "SELECT * FROM quotes_data" 
		  . "\n WHERE quote_id = " . $id 
		  . "\n ORDER BY id";

          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
		  
		  
      /**
       * Content::loadQuoteEntries()
       * 
       * @param mixed $invid
       * @return
       */
	  public function loadQuoteEntries($qid)
	  {
		  $quotedata = $this->getQuoteEntries($qid);
		  $html =  '
			<table class="wojo basic table">
			  <thead>
				<tr>
				  <th>#</th>
				  <th>' .Lang::$word->INVC_ENTRYTITLE . '</th>
				  <th>' . Lang::$word->DESC . '</th>
				  <th>' . Lang::$word->AMOUNT . '</th>
				</tr>
			  </thead>';
		  
			  foreach ($quotedata as $irow) {
				  $html .= '
					<tr>
					  <td>' . $irow->id . '.</td>
					  <td>' . $irow->title . '</td>
					  <td>' . $irow->description . '</td>
					  <td>' . $irow->amount . '</td>
					</tr>';
			  }
			  unset($irow);

		  $html .= '
			</table>';
			
			return $html;
	  }

      /**
       * Content::sendQuote()
       * 
       * @param mixed $id
       * @return
       */
      public function sendQuote($id)
      {
		  Filter::$id = $id;
          $row = $this->getQuoteById();
		  
          if ($row) {
              $quotedata = $this->getQuoteEntries($id);
			  
			  ob_start();
			  require_once(BASEPATH . 'admin/print_quote_pdf.php');
			  $pdf_html = ob_get_contents();
			  ob_end_clean();

			  require_once(BASEPATH . 'lib/mPdf/mpdf.php');
			  $mpdf = new mPDF('utf-8', Registry::get("Core")->pagesize);
			  $mpdf->SetTitle($title);
			  $mpdf->SetAutoFont();
			  $mpdf->WriteHTML($pdf_html);
			  $pdf_content = $mpdf->Output($title . ".pdf", "S");
	  
              require_once (BASEPATH . "lib/class_mailer.php");
              $mailer = Mailer::sendMail();
              $subject = Lang::$word->QUTS_SUBJECT . cleanOut($row->title);

              ob_start();
              require_once (BASEPATH . 'mailer/Email_Quote.tpl.php');
              $html_message = ob_get_contents();
              ob_end_clean();

			  if (file_exists(UPLOADS . 'print_logo.png')) {
				  $logo = '<img src="' . UPLOADURL . 'print_logo.png" alt="' . Registry::get("Core")->company . '" style="border:0"/>';
			  } elseif (Registry::get("Core")->logo) {
				  $logo = '<img src="' . UPLOADURL . Registry::get("Core")->logo . '" alt="' . Registry::get("Core")->company . '" style="border:0"/>';
			  } else {
				  $logo = Registry::get("Core")->company;
			  }

			  $quote = '';
			  if ($quotedata) {
				  $quote .= '
				  <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-bottom-width: 4px; border-left-width: 1px; border-bottom-style: solid; border-left-style: solid; border-bottom-color: #7C8083; border-left-color: #7C8083;font-family: Helvetica Neue,Helvetica,Arial, sans-serif; font-size:13px;">';
				  foreach ($quotedata as $irow) {
					  $quote .= ' 
					  <tr>
						<td valign="top" style="padding:5px;border-right-width: 1px; border-right-style: solid; border-right-color: #7C8083; border-top-width: 1px; border-top-style: solid; border-top-color: #7C8083;"><h4 style="margin:0px;padding:0px;font-size: 14px;">' . $irow->title . '</h4>
						  ' . $irow->description . '</td>
						<td width="250" align="right" valign="top" style="padding:5px;border-right-width: 1px; border-right-style: solid; border-right-color: #7C8083; border-top-width: 1px; border-top-style: solid; border-top-color: #7C8083;">' . $irow->amount . '</td>
					  </tr>';
				  }
				  if ($row->tax) {
					  $quote .= '
					  <tr>
						<td align="right" valign="top" style="padding:5px;border-right-width: 1px; border-right-style: solid; border-right-color: #7C8083; border-top-width: 1px; border-top-style: solid; border-top-color: #7C8083;">' . Registry::get("Core")->tax_name . ':</td>
						<td align="right" valign="top" style="padding:5px;border-right-width: 1px; border-right-style: solid; border-right-color: #7C8083; border-top-width: 1px; border-top-style: solid; border-top-color: #7C8083;">' . $row->tax . '</td>
					  </tr>';
				  }
				  $quote .= '
				  <tr>
					<td align="right" valign="top" style="padding:5px;border-right-width: 1px; border-right-style: solid; border-right-color: #7C8083; border-top-width: 1px; border-top-style: solid; border-top-color: #7C8083;"><strong>Quote Total:</strong></td>
					<td align="right" valign="top" style="padding:5px;border-right-width: 1px; border-right-style: solid; border-right-color: #7C8083; border-top-width: 1px; border-top-style: solid; border-top-color: #7C8083;">' . $row->amount_total . '</td>
				  </tr>
				  </table>';
			  }
    
			  $body = str_replace(array(
				  '[LOGO]',
				  '[QUTID]',
				  '[DATE]',
				  '[COMPANY]',
				  '[ADDRESS]',
				  '[ADDRESS2]',
				  '[PHONE]',
				  '[FAX]',
				  '[NAME]',
				  '[CCOMPANY]',
				  '[CADDRESS]',
				  '[CADDRESS2]',
				  '[CPHONE]',
				  '[TAXNUMBER]',
				  '[VAT]',
				  '[AMTOUNT]',
				  '[EXPIRE]',
				  '[QUOTEDATA]',
				  '[NOTES]',
				  '[SITEURL]'), array(
				  $logo,
				  Registry::get("Core")->quote_number . $row->id,
				  Filter::dodate("short_date", $row->created),
				  Registry::get("Core")->company,
				  Registry::get("Core")->address,
				  Registry::get("Core")->city.', '.Registry::get("Core")->state.' '.Registry::get("Core")->zip,
				  (Registry::get("Core")->phone) ? Lang::$word->CONF_PHONE. ': ' . Registry::get("Core")->phone : '',
				  (Registry::get("Core")->fax) ? Lang::$word->CONF_FAX. ': ' . Registry::get("Core")->fax : '',
				  $row->name,
				  $row->company,
				  $row->address,
				  $row->city . ', ' . $row->state . ' ' . $row->zip,
				  ($row->phone) ? Lang::$word->CONF_PHONE. ': ' . $row->phone : '',
				  Registry::get("Core")->tax_number,
				  ($row->vat) ? Lang::$word->UVAT. ': ' . $row->vat : '',
				  $row->amount_total,
				  Filter::dodate("short_date", $row->expire),
				  $quote,
				  cleanOut($row->notes),
				  SITEURL), $html_message);
				    
              $msg = Swift_Message::newInstance()
					  ->setSubject($subject)
					  ->setTo(array($row->email => $row->name))
					  ->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->company))
					  ->setBody($body, 'text/html');
					  
              $msg->attach(Swift_Attachment::newInstance($pdf_content, cleanOut(preg_replace("/[^a-zA-Z0-9\s]/", "", $row->title)) . '.pdf', 'application/pdf'));
			  
			  if($mailer->send($msg)) {
				  $json['type'] = 'success';
				  $json['title'] = Lang::$word->SUCCESS;
				  $json['message'] =  Lang::$word->QUTS_SENT_OK;
			  } else {
				  $json['type'] = 'error';
				  $json['title'] = Lang::$word->ERROR;
				  $json['message'] = Lang::$word->QUTS_SENT_ERR;
			  }
			  
			  print json_encode($json);
          }
      }
	  
	  /**
	   * Content::getTimeBilling()
	   * 
	   * @return
	   */
	  public function getTimeBilling()
	  {
		  if (Registry::get("Users")->userlevel == 5) {
			  $q = "SELECT COUNT(*) FROM " . self::tbTable . "  WHERE staff_id = " . Registry::get("Users")->uid . " GROUP BY project_id LIMIT 1";
			  $access = "WHERE pp.staff_id='" . Registry::get("Users")->uid . "'";
		  } else {
			  $q = "SELECT COUNT(*) FROM " . self::tbTable . " GROUP BY project_id LIMIT 1";
			  $access = null;
		  }
		  $record = Registry::get("Database")->query($q);
		  $total = Registry::get("Database")->fetchrow($record);
		  $counter = $total[0];

		  $pager = Paginator::instance();
		  $pager->items_total = $counter;
		  $pager->default_ipp = Registry::get("Core")->perpage;
		  $pager->paginate();


		  $sql = "SELECT t.*, CONCAT(u.fname,' ',u.lname) as fullname, u.id as uid, p.title as ptitle, p.id as pid," 
		  . "\n COUNT(t.project_id) as totalprojects," 
		  . "\n SUM(t.hours) as totalhours" 
		  . "\n FROM " . self::tbTable . " as t" 
		  . "\n LEFT JOIN " . Users::uTable . " as u ON u.id = t.client_id" 
		  . "\n LEFT JOIN " . self::pTable . " as p ON p.id = t.project_id" 
		  . "\n LEFT JOIN permissions as pp ON pp.project_id = t.project_id" 
		  . "\n $access" 
		  . "\n GROUP BY t.project_id" . $pager->limit;
		  $row = self::$db->fetch_all($sql);

		  return ($row) ? $row : 0;
	  }

	  /**
	   * Content::getTimeBillingByProjectId()
	   * 
	   * @param bool $project_id
	   * @return
	   */
	  public function getTimeBillingByProjectId($project_id = false)
	  {
		  $id = ($project_id) ? $project_id : Filter::$id;

		  $sql = "SELECT tb.*, t.title as taskname, t.id as tid," 
		  . "\n DATE_FORMAT(tb.created, '" . Registry::get("Core")->short_date . "') as cdate" 
		  . "\n FROM " . self::tbTable . " as tb" 
		  . "\n LEFT JOIN " . self::tTable . " as t ON t.id = tb.task_id" 
		  . "\n WHERE tb.project_id = " . (int)$id . "" 
		  . "\n ORDER BY tb.created DESC";
		  $row = self::$db->fetch_all($sql);

		  return ($row) ? $row : 0;
	  }

	  /**
	   * Content::getTimeBillingById()
	   * 
	   * @param bool $billing_id
	   * @return
	   */
	  public function getTimeBillingById($billing_id = false)
	  {
		  $id = ($billing_id) ? $billing_id : Filter::$id;

		  $sql = "SELECT tb.*, t.title as taskname, t.id as tid, p.title as ptitle, p.id as pid, " 
		  . "\n CONCAT(uc.fname,' ',uc.lname) as cfullname, uc.fname, uc.lname, uc.username, CONCAT(us.fname,' ',us.lname) as sfullname" 
		  . "\n FROM " . self::tbTable . " as tb" 
		  .  "\n LEFT JOIN " . self::tTable . " as t ON t.id = tb.task_id" 
		  . "\n LEFT JOIN " . self::pTable . " as p ON p.id = tb.project_id" 
		  . "\n LEFT JOIN " . Users::uTable . " as uc ON uc.id = tb.client_id" 
		  . "\n LEFT JOIN " . Users::uTable . " as us ON us.id = tb.staff_id" 
		  . "\n WHERE tb.id = " . (int)$id;
		  $row = self::$db->first($sql);

		  return ($row) ? $row : 0;
	  }

	  /**
	   * Content::processTimeRecord()
	   * 
	   * @return
	   */
	  public function processTimeRecord()
	  {
		  Filter::checkPost('title', Lang::$word->INVC_ENTRYTITLE_R);
		  Filter::checkPost('client_id', Lang::$word->INVC_CLIENTSELECT);
		  Filter::checkPost('project_id', Lang::$word->INVC_PROJCSELETC);

		  if (empty(Filter::$msgs)) {
			  $data = array(
					'staff_id' => intval($_POST['staff_id']), 
					'client_id' => intval($_POST['client_id']), 
					'project_id' => intval($_POST['project_id']), 
					'task_id' => intval($_POST['task_id']), 
					'title' => sanitize($_POST['title']), 
					'description' => $_POST['description'], 
					'hours' => floatval($_POST['hours']), 
					'created' => sanitize($_POST['created_submit']) . ' ' . date('H:i:s')
			  );

			  (Filter::$id) ? self::$db->update(self::tbTable, $data, "id=" . Filter::$id) : self::$db->insert(self::tbTable, $data);
			  $message = (Filter::$id) ? Lang::$word->BILL_UPDATED : Lang::$word->BILL_ADDED;

			  if(self::$db->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk($message, false);
			  } else {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
			  }
			  print json_encode($json);
			  
		  } else {
			  $json['message'] = Filter::msgStatus();
			  print json_encode($json);
		  }
	  }

	  /**
	   * Content::getPaymentTransactions()
	   * 
	   * @param bool $from
	   * @return
	   */
	  public function getPaymentTransactions($from = false)
	  {

		  if (isset($_POST['fromdate_submit']) && $_POST['fromdate_submit'] <> "" || isset($from) && $from != '') {
			  $enddate = date("Y-m-d");
			  $fromdate = (empty($from)) ? $_POST['fromdate_submit'] : $from;
			  if (isset($_POST['enddate_submit']) && $_POST['enddate_submit'] <> "") {
				  $enddate = $_POST['enddate_submit'];
			  }
			  $q = "SELECT COUNT(*) FROM " . self::ipTable . " WHERE created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'";
			  $where = " WHERE ip.created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'";
			  
		  } else {
			  $q = "SELECT COUNT(*) FROM " . self::ipTable . " LIMIT 1";
			  $where = null;
		  }
		  
          $record = self::$db->query($q);
          $total = self::$db->fetchrow($record);
          $counter = $total[0];
		  
		  $pager = Paginator::instance();
		  $pager->items_total = $counter;
		  $pager->default_ipp = Registry::get("Core")->perpage;
		  $pager->paginate();	
		  
		  $sql = "SELECT ip.*," 
		  . "\n p.title as ptitle, i.title as ititle" 
		  . "\n FROM " . self::ipTable . " as ip" 
		  . "\n LEFT JOIN " . self::pTable . " as p ON p.id = ip.project_id" 
		  . "\n LEFT JOIN " . self::iTable . " as i ON i.id = ip.invoice_id" 
		  . "\n $where"
		  . "\n ORDER BY ip.created DESC" . $pager->limit;

		  $row = self::$db->fetch_all($sql);

		  return ($row) ? $row : 0;
	  }

      /**
       * Content::getSupportTickets()
       * 
       * @return
       */
      public function getSupportTickets()
      {
          if (Registry::get("Users")->userlevel == 5) {
              $access = "WHERE t.staff_id='" . Registry::get("Users")->uid . "'";
              $counter = countEntries("support_tickets", "staff_id", Registry::get("Users")->uid);
          } else {
              $counter = countEntries("support_tickets");
			  $access = null;
          }

          $pager = Paginator::instance();
          $pager->items_total = $counter;
          $pager->default_ipp = Registry::get("Core")->perpage;
          $pager->paginate();

		  if (isset($_GET['sort'])) {
			  $data = explode("-", $_GET['sort']);
			  if (count($data) > 1) {
				  $sort = sanitize($data[0]);
				  $order = sanitize($data[1]);
				  if (in_array($sort, array("staff_id", "client_id", "priority", "status", "created"))) {
					  $ord = ($order == 'DESC') ? " DESC" : " ASC";
					  $sorting = " t." . $sort . $ord;
				  } else
					  $sorting = " t.created DESC";
			  } else
				  $sorting = " t.created DESC";
		  } else
			  $sorting = " t.created DESC";
			  
          $sql = "SELECT t.*, CONCAT(us.fname,' ',us.lname) as staffname, CONCAT(uc.fname,' ',uc.lname) as fullname, uc.username" 
		  . "\n FROM support_tickets as t" 
		  . "\n LEFT JOIN " . Users::uTable . " as uc ON uc.id = t.client_id" 
		  . "\n LEFT JOIN " . Users::uTable . " as us ON us.id = t.staff_id"
		  . "\n $access" 
		  . "\n ORDER BY " . $sorting . $pager->limit;
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Content::getSupportTicketById()
       * 
       * @return
       */
      public function getSupportTicketById()
      {
          $sql = "SELECT t.*, CONCAT(uc.fname,' ',uc.lname) as fullname, uc.username, p.title as ptitle" 
		  . "\n FROM support_tickets as t" 
		  . "\n LEFT JOIN " . Users::uTable . " as uc ON uc.id = t.client_id"
		  . "\n LEFT JOIN " . self::pTable . " as p ON p.id = t.project_id" 
		  . "\n WHERE t.id = " . Filter::$id;
          $row = self::$db->first($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Content::getResponseByTicketId()
       * 
       * @return
       */
      public function getResponseByTicketId()
      {
          $sql = "SELECT r.*, CONCAT(u.fname,' ',u.lname) as name" 
		  . "\n FROM support_responses as r" 
		  . "\n LEFT JOIN " . Users::uTable . " as u ON u.id = r.author_id" 
		  . "\n WHERE r.ticket_id = " . Filter::$id
		  . "\n ORDER BY r.created DESC";
          
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

	  /**
	   * Content::processSupportTicket()
	   * 
	   * @return
	   */
	  public function processSupportTicket()
	  {
		  $data = array(
				'priority' => sanitize($_POST['priority']), 
				'staff_id' => intval($_POST['staff_id']), 
				'status' => sanitize($_POST['status'])
		  );

		  self::$db->update("support_tickets", $data, "id=" . Filter::$id);

		  if(self::$db->affected()) {
			  $json['type'] = 'success';
			  $json['message'] = Filter::msgOk(Lang::$word->SUP_UPDATED, false);
		  } else {
			  $json['type'] = 'success';
			  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
		  }
		  print json_encode($json);

	  }

	  /**
	   * Content::replySupportTicket()
	   * 
	   * @return
	   */
	  public function replySupportTicket()
	  {
		  Filter::checkPost('body', Lang::$word->SUP_DETAIL);

		  if (empty(Filter::$msgs)) {
		  
			  $sql = "SELECT t.*, CONCAT(uc.fname,' ',uc.lname) as fullname, uc.email" 
			  . "\n FROM support_tickets as t" 
			  . "\n LEFT JOIN " . Users::uTable . " as uc ON uc.id = t.client_id" 
			  . "\n WHERE t.id = " . Filter::$id;
			  $row = self::$db->first($sql);
			  
			  $data = array(
					'ticket_id' => $row->id, 
					'author_id' => Registry::get("Users")->uid, 
					'user_type' => 'staff',
					'created' => "NOW()",
					'body' => $_POST['body']
			  );
	
			  self::$db->insert("support_responses", $data);

			  require_once (BASEPATH . "lib/class_mailer.php");
			  $mailer = Mailer::sendMail();
			  $subject = Lang::$word->SUP_ESUBJECT . cleanOut($row->subject);
	
			  ob_start();
			  require_once (BASEPATH . 'mailer/Reply_Ticket_From_Admin.tpl.php');
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
				  '[NAME]',
				  '[ID]',
				  '[MSG]',
				  '[ATTACHMENT]',
				  '[DATE]',
				  '[COMPANY]',
				  '[SITEURL]'), array(
				  $logo,
				  Core::renderName($row),
				  $row->tid,
				  cleanOut($data['body']),
				  (!empty($data['attachment'])) ? '<a href="' . UPLOADURL . 'tempfiles/' .$data['attachment'] . '">' . Lang::$word->FORM_DOWNLOAD . '</a>' : null,
				  date('Y'),
				  Registry::get("Core")->company,
				  SITEURL), $html_message);
				  
			  $msg = Swift_Message::newInstance()
					  ->setSubject($subject)
					  ->setTo(array($row->email => Core::renderName($row)))
					  ->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->company))
					  ->setBody($body, 'text/html');
	
			  $numSent = $mailer->send($msg);
	
			  (self::$db->affected()) ? Filter::msgOk(Lang::$word->SUP_SENTOK) : Filter::msgAlert(Lang::$word->NOPROCCESS);

		  } else
			  print Filter::msgStatus();
	  }

	  /**
	   * Content::addSupportTicket()
	   * 
	   * @return
	   */
	  public function addSupportTicket()
	  {
		  Filter::checkPost('subject', Lang::$word->SUP_SUBJECT);
		  Filter::checkPost('body', Lang::$word->SUP_DETAIL);
		  Filter::checkPost('project_id', Lang::$word->TASK_SELPROJ);
		  Filter::checkPost('client_id', Lang::$word->INVC_CLIENTSELECT);

		  if (empty(Filter::$msgs)) {
			  $data = array(
			        'staff_id' => intval($_POST['staff_id']),
			        'tid' => 'T_' . strtoupper(substr(md5(microtime()),rand(0,26),5)),
					'client_id' => intval($_POST['client_id']),
					'project_id' => intval($_POST['project_id']),
					'department' => 'Support', 
					'priority' => sanitize($_POST['priority']), 
					'subject' => sanitize($_POST['subject']), 
					'body' => sanitize($_POST['body']), 
					'status' => 'Open',
					'created' => "NOW()"
			  );

			  self::$db->insert("support_tickets", $data);
			  
			  if (isset($_POST['notify_c']) && intval($_POST['notify_c']) == 1) {
				  $row = Registry::get("Core")->getRowById("users", $data['client_id']);
				  
				  require_once (BASEPATH . "lib/class_mailer.php");
				  $mailer = Mailer::sendMail();
				  $subject = Lang::$word->SUP_ESUBJECT . cleanOut($data['subject']);
		
				  ob_start();
				  require_once (BASEPATH . 'mailer/New_Ticket_From_Admin.tpl.php');
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
					  '[NAME]',
					  '[SUBJECT]',
					  '[CONTENT]',
					  '[DATE]',
					  '[COMPANY]',
					  '[SITEURL]'), array(
					  $logo,
					  Core::renderName($row),
					  cleanOut($data['subject']),
					  cleanOut($data['body']),
					  date('Y'),
					  Registry::get("Core")->company,
					  SITEURL), $html_message);
						  
				  $msg = Swift_Message::newInstance()
						  ->setSubject($subject)
						  ->setTo(array($row->email => Core::renderName($row)))
						  ->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->company))
						  ->setBody($body, 'text/html');
		
				  $numSent = $mailer->send($msg);
			  }

			  if (isset($_POST['notify_s']) && intval($_POST['notify_s']) == 1) {
				  $row = Registry::get("Core")->getRowById("users", $data['staff_id']);
				  
				  require_once (BASEPATH . "lib/class_mailer.php");
				  $mailer = Mailer::sendMail();
				  $subject = Lang::$word->SUP_ESUBJECT . cleanOut($data['subject']);
					  
				  ob_start();
				  require_once (BASEPATH . 'mailer/New_Ticket_To_Staff.tpl.php');
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
					  '[NAME]',
					  '[PRIORITY]',
					  '[SUBJECT]',
					  '[CONTENT]',
					  '[DATE]',
					  '[COMPANY]',
					  '[SITEURL]'), array(
					  $logo,
					  Core::renderName($row),
					  $data['priority'],
					  cleanOut($data['subject']),
					  cleanOut($data['body']),
					  date('Y'),
					  Registry::get("Core")->company,
					  SITEURL), $html_message);
					  
				  $msg = Swift_Message::newInstance()
						  ->setSubject($subject)
						  ->setTo(array($row->email => Core::renderName($row)))
						  ->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->company))
						  ->setBody($body, 'text/html');
		
				  $numSent = $mailer->send($msg);
			  }

			  if(self::$db->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk(Lang::$word->SUP_SENTOK1, false);
			  } else {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
			  }
			  print json_encode($json);
			  
		  } else {
			  $json['message'] = Filter::msgStatus();
			  print json_encode($json);
		  }
	  }
	  
	  /**
	   * Content::getMessages()
	   * 
	   * @return
	   */
	  public function getMessages($paginate = true)
	  {

          if($paginate) {
			  $q = "SELECT COUNT(messages.uid1) as total"
			  . "\n FROM messages, " . Users::uTable . ""
			  . "\n WHERE ((messages.user1='" . Registry::get("Users")->uid . "'" 
			  . "\n AND " . Users::uTable . ".id=messages.user2) OR (messages.user2='" . Registry::get("Users")->uid . "'" 
			  . "\n AND " . Users::uTable . ".id=messages.user1))" 
			  . "\n AND messages.uid2='1'" 
			  . "\n LIMIT 1";
			  
			  $record = self::$db->query($q);
			  $total = self::$db->fetchrow($record);
			  $counter = $total[0];
	
	
			  $pager = Paginator::instance();
			  $pager->items_total = $counter;
			  $pager->default_ipp = Registry::get("Core")->perpage;
			  $pager->paginate();
			  
			  $pagination = $pager->limit;
		  } else {
			  $pagination = null;
		  }

		  $sql = "SELECT m1.uid1,m1.id, m1.msgsubject, m1.created, m1.user1read, m1.user2read, m1.user1, m1.user2, m1.uid2, m1.attachment," 
		  . "\n CONCAT(" . Users::uTable . ".fname,' '," . Users::uTable . ".lname) as fullname, " . Users::uTable . ".username," 
		  . "\n COUNT(m2.uid1) as replies, " . Users::uTable . ".id as userid," 
		  . "\n " . Users::uTable . ".username FROM messages as m1, messages as m2," . Users::uTable . "" 
		  . "\n WHERE ((m1.user1='" . Registry::get("Users")->uid . "'" 
		  . "\n AND " . Users::uTable . ".id=m1.user2) OR (m1.user2='" . Registry::get("Users")->uid . "'" 
		  . "\n AND " . Users::uTable . ".id=m1.user1))" 
		  . "\n AND m1.uid2='1'" 
		  . "\n AND m2.uid1=m1.uid1" 
		  . "\n GROUP BY m1.uid1" 
		  . "\n ORDER BY m1.created DESC" . $pagination;
		  $row = self::$db->fetch_all($sql);
		  
		  return ($row) ? $row : 0;
	  }

	  /**
	   * Content::processMessage()
	   * 
	   * @return
	   */
	  public function processMessage()
	  {
	
		  Filter::checkPost('recipient', Lang::$word->MSG_RECEPIENT);
		  Filter::checkPost('msgsubject', Lang::$word->MSG_SUBJECT);
		  Filter::checkPost('body', Lang::$word->MSG_MESSAGE);

		  $upl = Uploader::instance(Registry::get("Core")->file_max, Registry::get("Core")->file_types);
		  if (!empty($_FILES['attachment']['name']) and empty(Filter::$msgs)) {
			  $dir = UPLOADS . 'tempfiles/';
			  $upl->upload('attachment', $dir, "ATT_");
		  }
	  
		  if (empty(Filter::$msgs)) {
			  if (Filter::$id and isset($_POST['update'])) {
				  $data = array(
					  'uid1' => Filter::$id,
					  'uid2' => intval($_POST['uid2']),
					  'msgsubject' => "",
					  'user1' => Registry::get("Users")->uid,
					  'user2' => 0,
					  'body' => $_POST['body'],
					  'attachment' => !empty($_FILES['attachment']['name']) ? $upl->fileInfo['fname'] : "NULL",
					  'created' => "NOW()",
					  'user1read' => "yes",
					  'user2read' => "no",
					  );
				  self::$db->insert("messages", $data);
	
				  $data2 = array('user' . intval($_POST['userp']) . 'read' => "no");
				  self::$db->update("messages", $data2, "uid1='" . Filter::$id . "' AND uid2 = '1'");

				  $sql = "SELECT email, CONCAT(fname,' ',lname) as fullname, username FROM " . Users::uTable . " WHERE id = " . (int)$_POST['recipient'];
				  $row = self::$db->first($sql);

				  require_once (BASEPATH . "lib/class_mailer.php");
				  $mailer = Mailer::sendMail();
				  $subject = Lang::$word->MSG_ESUBJECT . cleanOut($_POST['msgsubject']);
		
				  ob_start();
				  require_once (BASEPATH . 'mailer/Reply_Message_From_Admin.tpl.php');
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
					  '[NAME]',
					  '[MSG]',
					  '[DATE]',
					  '[COMPANY]',
					  '[SITEURL]'), array(
					  $logo,
					  Core::renderName($row),
					  cleanOut($_POST['body']),
					  date('Y'),
					  Registry::get("Core")->company,
					  SITEURL), $html_message);
					  
					  $msg = Swift_Message::newInstance()
							->setSubject($subject)
							->setTo(array($row->email => Core::renderName($row)))
							->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->company))
							->setBody($body, 'text/html');
		
				  $numSent = $mailer->send($msg);

			  } else {
				  $single = self::$db->first("SELECT COUNT(id) as recip, id as recipid, (SELECT COUNT(*) FROM messages) as newmsg FROM " . Users::uTable . " where id='" . intval($_POST['recipient']) . "'");
				  $data = array(
					  'uid1' => intval($single->newmsg + 1),
					  'uid2' => 1,
					  'msgsubject' => sanitize($_POST['msgsubject']),
					  'user1' => Registry::get("Users")->uid,
					  'user2' => intval($single->recipid),
					  'body' => $_POST['body'],
					  'attachment' => !empty($_FILES['attachment']['name']) ? $upl->fileInfo['fname'] : "NULL",
					  'created' => "NOW()",
					  'user1read' => "yes",
					  'user2read' => "no",
					  );
					  
				  self::$db->insert("messages", $data);
				  
				  $sql = "SELECT email, CONCAT(fname,' ',lname) as fullname, username FROM " . Users::uTable . " WHERE id = " . (int)$_POST['recipient'];
				  $row = self::$db->first($sql);

				  require_once (BASEPATH . "lib/class_mailer.php");
				  $mailer = Mailer::sendMail();
				  $subject = Lang::$word->MSG_ESUBJECT . cleanOut($_POST['msgsubject']);
		
				  ob_start();
				  require_once (BASEPATH . 'mailer/Reply_Message_From_Admin.tpl.php');
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
					  '[NAME]',
					  '[MSG]',
					  '[DATE]',
					  '[COMPANY]',
					  '[SITEURL]'), array(
					  $logo,
					  Core::renderName($row),
					  cleanOut($_POST['body']),
					  date('Y'),
					  Registry::get("Core")->company,
					  SITEURL), $html_message);
							
				  $msg = Swift_Message::newInstance()
						->setSubject($subject)
						->setTo(array($row->email => Core::renderName($row)))
						->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->company))
						->setBody($body, 'text/html');
		
				  $numSent = $mailer->send($msg);

			  }
			  
			  if(self::$db->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk(Lang::$word->MSG_SENTOK, false);
			  } else {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
			  }
			  print json_encode($json);
			  
		  } else {
			  $json['message'] = Filter::msgStatus();
			  print json_encode($json);
		  }
	  }

      /**
       * Content::renderMessages()
       * 
       * @return
       */
      public function renderMessages()
      {
		  $sql = "SELECT m.created, m.body, m.attachment, u.id as userid, u.username, u.fname, u.lname, u.avatar" 
		  . "\n FROM messages as m, " . Users::uTable . " AS u"
		  . "\n WHERE m.uid1='" . Filter::$id. "'" 
		  . "\n AND u.id=m.user1" 
		  . "\n ORDER BY m.uid2";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
      /**
       * Content::getMessageById()
       * 
       * @return
       */
      public function getMessageById()
      {
		  
		  $sql = "SELECT msgsubject, user1, user2 FROM messages WHERE uid1='" . Filter::$id . "' AND uid2=1";
          $row = self::$db->first($sql);

          return ($row) ? $row : Filter::error("You have selected an Invalid Id","Content::getMessageById()");
      }
	  
	  /**
	   * Content::updateMessageStatus()
	   * 
	   * @param int $user_id
	   * @return
	   */
	  public function updateMessageStatus($user_id)
	  {
		  if ($user_id == Registry::get("Users")->uid) {
			  $data['user1read'] = "yes";
			  self::$db->update("messages", $data, "uid1='" . Filter::$id . "' AND uid2 = '1'");
			  return 2;
		  } else {
			  $data['user2read'] = "yes";
			  self::$db->update("messages", $data, "uid1='" . Filter::$id . "' AND uid2 = '1'");
			  return 1;
		  }
	  }
	  
	  /**
	   * Content::progressBarStatus()
	   * 
	   * @param mixed $number
	   * @return
	   */
	  public function progressBarStatus($number)
	  {
		  
		  if($number == 100) {
			  return '<div class="wojo active striped success progress"><div class="bar" style="width:' . $number . '%;">' . $number . '%</div></div>';
		  } else {
			  if($number == 0) {
				 return Lang::$word->NOTSTARTED;
			  } else {
				return '<div class="wojo active striped warning progress"><div class="bar" style="width:' . $number . '%;">' . $number . '%</div></div>';
			  }
		  }
		  
	  }

	  /**
	   * Content::progressBarBilling()
	   * 
	   * @param mixed $paid
	   * @param mixed $total
	   * @return
	   */
	  public function progressBarBilling($paid, $total)
	  {
		  if($paid == -1) {
			  return Lang::$word->UNPAID;
		  } else {
			  if($total == 0) {
				 return '<div class="wojo active striped danger progress"><div class="bar">100%</div></div>';
			  } else {
				$percent = number_format(($paid * 100) / $total);
				return ($paid == 0) ? Lang::$word->NOTBILLED : '<div class="wojo active striped info progress"><div class="bar" style="width:' . $percent . '%;">' . $percent . '%</div></div>';
			  }
		  }
	  }

	  /**
	   * Content::progressBarRecurring()
	   * 
	   * @return
	   */
	  public function progressBarRecurring()
	  {
		  return '<div class="wojo active striped success progress"><div class="bar" style="width:100%;">' . Lang::$word->INVC_RECURRING_PAY . '</div></div>';
		  
	  }
	  
	  /**
	   * Content::invoiceStatusList()
	   * 
	   * @param string $selected
	   * @return
	   */
	  public function invoiceStatusList($selected = '')
	  {
		  $arr = array('Paid' => Lang::$word->PAID, 'Unpaid' => Lang::$word->UNPAID, 'Overdue' => Lang::$word->OVERDUE);

		  $list = '';
		  foreach ($arr as $key => $val) {
			  $sel = ($key == $selected) ? ' selected="selected"' : '';
			  $list .= "<option value=\"" . $key . "\"" . $sel . ">" . $val . "</option>\n";
		  }
		  unset($val);
		  return $list;
	  }

	  /**
	   * Content::billingStatusList()
	   * 
	   * @param string $selected
	   * @return
	   */
	  public function billingStatusList($selected = '')
	  {
		  $arr = array('Not Yet Billed' => Lang::$word->NOTBILLED, 'Paid' => Lang::$word->PAID, 'Unpaid' => Lang::$word->UNPAID, 'Overdue' => Lang::$word->OVERDUE);

		  $list = '';
		  foreach ($arr as $key => $val) {
			  $sel = ($key == $selected) ? ' selected="selected"' : '';
			  $list .= "<option value=\"" . $key . "\"" . $sel . ">" . $val . "</option>\n";
		  }
		  unset($val);
		  return $list;
	  }

	  /**
	   * Content::projectStatusList()
	   * 
	   * @param string $selected
	   * @return
	   */
	  public function projectStatusList($selected = '')
	  {
		  $arr = array('Not Yet Started' => Lang::$word->NOTSTARTED, 'In Progress' => Lang::$word->INPROGRESS, 'Completed' => Lang::$word->COMPLETED);

		  $list = '';
		  foreach ($arr as $key => $val) {
			  $sel = ($key == $selected) ? ' selected="selected"' : '';
			  $list .= "<option value=\"" . $key . "\"" . $sel . ">" . $val . "</option>\n";
		  }
		  unset($val);
		  return $list;
	  }

	  /**
	   * Content::projectSubmissionList()
	   * 
	   * @param string $selected
	   * @return
	   */
	  public function projectSubmissionList($selected = '')
	  {
		  $arr = array('New Concept' => Lang::$word->NEW_CONCEPT, 'Revision' => Lang::$word->REVISION, 'Draft' => Lang::$word->DRAFT, 'Final' => Lang::$word->FINAL);

		  $list = '';
		  foreach ($arr as $key => $val) {
			  $sel = ($key == $selected) ? ' selected="selected"' : '';
			  $list .= "<option value=\"" . $key . "\"" . $sel . ">" . $val . "</option>\n";
		  }
		  unset($val);
		  return $list;
	  }

	  /**
	   * Content::ticketPriorityList()
	   * 
	   * @param string $selected
	   * @return
	   */
	  public function ticketPriorityList($selected = '')
	  {
		  $arr = array('High' => Lang::$word->HIGH, 'Medium' => Lang::$word->MEDIUM, 'Low' => Lang::$word->LOW);

		  $list = '';
		  foreach ($arr as $key => $val) {
			  $sel = ($key == $selected) ? ' selected="selected"' : '';
			  $list .= "<option value=\"" . $key . "\"" . $sel . ">" . $val . "</option>\n";
		  }
		  unset($val);
		  return $list;
	  }

	  /**
	   * Content::renderTicketPriorityList()
	   * 
	   * @param string $attr
	   * @return
	   */
	  public static function renderTicketPriorityList($attr)
	  {
		  switch ($attr) {
			  case 'High':
			  return Lang::$word->HIGH;
			  
			  case 'Medium':
			  return Lang::$word->MEDIUM;

			  case 'Low':
			  return Lang::$word->LOW;
		  }

	  }
	  
	  /**
	   * Content::ticketStatusList()
	   * 
	   * @param string $selected
	   * @return
	   */
	  public function ticketStatusList($selected = '')
	  {
		  $arr = array('Open' => Lang::$word->OPEN, 'Pending' => Lang::$word->PENDING, 'Closed' => Lang::$word->CLOSED);

		  $list = '';
		  foreach ($arr as $key => $val) {
			  $sel = ($key == $selected) ? ' selected="selected"' : '';
			  $list .= "<option value=\"" . $key . "\"" . $sel . ">" . $val . "</option>\n";
		  }
		  unset($val);
		  return $list;
	  }

	  /**
	   * Content::renderTicketStatusList()
	   * 
	   * @param string $attr
	   * @return
	   */
	  public static function renderTicketStatusList($attr)
	  {
		  switch ($attr) {
			  case 'Open':
			  return Lang::$word->OPEN;

			  case 'Pending':
			  return Lang::$word->PENDING;
			  
			  case 'Closed':
			  return Lang::$word->CLOSED;
		  }

	  }

	  /**
	   * Content::submissionStatus()
	   * 
	   * @param string $attr
	   * @return
	   */
	  public static function submissionStatus($attr)
	  {
		  switch ($attr) {
			  case 0:
			  return Lang::$word->OPEN;

			  case 1:
			  return Lang::$word->FDASH_ACTION1;
			  
			  case 2:
			  return Lang::$word->CLOSED;
		  }

	  }
	  
	  /**
	   * Content::getAllInfo()
	   * 
	   * @param mixed $project_id
	   * @return
	   */
	  public function getAllInfo($project_id)
	  {
		  $sql = "SELECT p.id as pid, p.title, p.p_status, p.b_status, p.cost, u.id as uid, u.email," 
		  . "\n CONCAT(u.fname,' ',u.lname) as fullname, u.username," 
		  . "\n (SELECT CONCAT(fname,' ',lname) FROM " . Users::uTable . " WHERE id = p.staff_id) as staffname" 
		  . "\n FROM " . self::pTable . " as p" 
		  . "\n LEFT JOIN " . Users::uTable . " as u ON u.id = p.client_id" 
		  . "\n WHERE p.id = '" . (int)$project_id . "'";
		  $row = self::$db->first($sql);

		  return ($row) ? $row : 0;
	  }

	  /**
	   * Content::getProjectsForClient()
	   * 
	   * @param mixed $user_id
	   * @return
	   */
	  public function getProjectsForClient($user_id)
	  {
		  $sql = "SELECT p.id as pid, p.title, p.p_status, p.b_status, p.cost, p.start_date," 
		  . "\n (SELECT CONCAT(fname,' ',lname) FROM " . Users::uTable . " WHERE id = p.staff_id) as staffname, i.recurring" 
		  . "\n FROM " . self::pTable . " as p" 
		  . "\n LEFT JOIN " . Users::uTable . " as u ON u.id = p.client_id" 
		  . "\n LEFT JOIN " . self::iTable . " as i ON i.project_id = p.id" 
		  . "\n WHERE p.client_id = '" . (int)$user_id . "' GROUP BY p.id";
		  $row = self::$db->fetch_all($sql);

		  return ($row) ? $row : 0;
	  }

	  /**
	   * Content::getInvoiceForClient()
	   * 
	   * @param mixed $user_id
	   * @return
	   */
	  public function getInvoiceForClient($user_id)
	  {
		  $sql = "SELECT *," 
		  . "\n DATE_FORMAT(created, '" . Registry::get("Core")->short_date . "') as start" 
		  . "\n FROM " . self::iTable 
		  . "\n WHERE client_id = '" . (int)$user_id . "'";
		  $row = self::$db->fetch_all($sql);

		  return ($row) ? $row : 0;
	  }

	  /**
	   * Content::getFormsList()
	   * 
	   * @return
	   */
	  public function getFormsList()
	  {
		  $sql = "SELECT id, title" 
		  . "\n FROM forms" 
		  . "\n WHERE active = 1";
		  $row = self::$db->fetch_all($sql);

		  return ($row) ? $row : 0;
	  }

	  /**
	   * Content::getEstimatorList()
	   * 
	   * @return
	   */
	  public function getEstimatorList()
	  {
		  $sql = "SELECT id, title" 
		  . "\n FROM estimator" 
		  . "\n WHERE active = 1";
		  $row = self::$db->fetch_all($sql);

		  return ($row) ? $row : 0;
	  }

	  /**
	   * Content::getMessageFilter()
	   * 
	   * @return
	   */
	  public function getMailerTemplates()
	  {
          $files = glob(BASEPATH . "mailer/*.{tpl.php}", GLOB_BRACE); 
		  
          return $files;
	  }
	  
      /**
       * Content::getCountryList()
       * 
       * @param bool $parent_id
       * @return
       */
      public function getCountryList($parent_id = false)
	  {	
          ($parent_id) ? $parent_id : 0;
		  
          $sql = "SELECT *"
		  ."\n FROM countries"
		  ."\n WHERE parent_id = '" . (int)$parent_id . "'"
		  ."\n ORDER BY name ASC";
         
		 $row = self::$db->fetch_all($sql);
          
          return ($row) ? $row : 0;
      }

      /**
       * Content::renderMessages()
       * 
       * @return
       */
      public function getTitleById($table)
      {
		  $sql = "SELECT title FROM $table WHERE id = " . Filter::$id;
          $row = self::$db->first($sql);

          return ($row) ? $row->title : 0;
      }
	  
	  /**
	   * Content::getPaymentFilter()
	   * 
	   * @return
	   */
	  public function getPaymentFilter()
	  {
		  $arr = array(
				'project_id-ASC' => Lang::$word->PROJ_NAME . ' &uarr;', 
				'project_id-DESC' => Lang::$word->PROJ_NAME . ' &darr;', 
				'invoice_id-ASC' => Lang::$word->INVC_NAME . ' &uarr;', 
				'invoice_id-DESC' => Lang::$word->INVC_NAME . ' &darr;', 
				'method-ASC' => Lang::$word->PAYMETHOD . ' &uarr;', 
				'method-DESC' => Lang::$word->PAYMETHOD . ' &darr;', 
				'amount-ASC' => Lang::$word->TRANS_PAYAMOUNT . ' &uarr;', 
				'amount-DESC' => Lang::$word->TRANS_PAYAMOUNT . ' &darr;', 
				'created-ASC' => Lang::$word->TRANS_PAYDATE . ' &uarr;', 
				'created-DESC' => Lang::$word->TRANS_PAYDATE . ' &darr;'
		  );

		  $filter = '';
		  foreach ($arr as $key => $val) {
			  if ($key == get('sort')) {
				  $filter .= "<option selected=\"selected\" value=\"$key\">$val</option>\n";
			  } else
				  $filter .= "<option value=\"$key\">$val</option>\n";
		  }
		  unset($val);
		  return $filter;
	  }

	  /**
	   * Content::getTicketFilter()
	   * 
	   * @return
	   */
	  public function getTicketFilter()
	  {
		  $arr = array(
				'staff_id-ASC' => Lang::$word->SUP_STAFF . ' &uarr;', 
				'staff_id-DESC' => Lang::$word->SUP_STAFF . ' &darr;', 
				'client_id-ASC' => Lang::$word->INVC_CNAME . ' &uarr;', 
				'client_id-DESC' => Lang::$word->INVC_CNAME . ' &darr;', 
				'priority-ASC' => Lang::$word->SUP_PRIORITY1 . ' &uarr;', 
				'priority-DESC' => Lang::$word->SUP_PRIORITY1 . ' &darr;', 
				'status-ASC' => Lang::$word->SUP_STATUS . ' &uarr;', 
				'status-DESC' => Lang::$word->SUP_STATUS . ' &darr;', 
				'created-ASC' => Lang::$word->SUP_CREATED . ' &uarr;', 
				'created-DESC' => Lang::$word->SUP_CREATED . ' &darr;'
		  );

		  $filter = '';
		  foreach ($arr as $key => $val) {
			  if ($key == get('sort')) {
				  $filter .= "<option selected=\"selected\" value=\"$key\">$val</option>\n";
			  } else
				  $filter .= "<option value=\"$key\">$val</option>\n";
		  }
		  unset($val);
		  return $filter;
	  }

	  /**
	   * Content::getMessageFilter()
	   * 
	   * @return
	   */
	  public function getMessageFilter()
	  {
		  $arr = array(
				'sender-ASC' => Lang::$word->MSG_SENDER.' &uarr;', 
				'sender-DESC' => Lang::$word->MSG_SENDER.' &darr;', 
				'status_r-ASC' => Lang::$word->MSG_STATUS.' &uarr;', 
				'status_r-DESC' => Lang::$word->MSG_STATUS.' &darr;', 
				'created-ASC' => Lang::$word->MSG_DATESENT.' &uarr;', 
				'created-DESC' => Lang::$word->MSG_DATESENT.' &darr;'
		  );

		  $filter = '';
		  foreach ($arr as $key => $val) {
			  if ($key == get('sort')) {
				  $filter .= "<option selected=\"selected\" value=\"$key\">$val</option>\n";
			  } else
				  $filter .= "<option value=\"$key\">$val</option>\n";
		  }
		  unset($val);
		  return $filter;
	  }
	  
	  /**
	   * Content::projectSubmissionStatus()
	   * 
	   * @param mixed $status
	   * @return
	   */
	  public function projectSubmissionStatus($status)
	  {
		  switch ($status) {
			  case 0:
				  return '<span class="wojo label black">' . Lang::$word->SUBS_STATUS1 . '</span>';
				  break;

			  case 1:
				  return '<span class="wojo label info">' . Lang::$word->SUBS_STATUS2 . '</span>';
				  break;

			  case 2:
				  return '<span class="wojo label positive">' . Lang::$word->SUBS_STATUS3 . '</span>';
				  break;
				  
			  case 3:
				  return '<span class="wojo label negative">' . Lang::$word->SUBS_STATUS4 . '</span>';
				  break;
		  }

	  }
	  
  }
?>