<?php
  /**
   * Calendar Class
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2010
   * @version $Id: class_calendar.php, v1.00 2011-12-20 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  class Calendar
  {

      public $weekDayNameLength;
	  public $monthNameLength;
      private $arrWeekDays = array();
      private $arrMonths = array();
      private $pars = array();
      private  $today = array();
      private $prevYear = array();
      private $nextYear = array();
      private $prevMonth = array();
      private $nextMonth = array();
	  protected $eventInvoice;
	  protected $eventProject;
	  protected $eventTask;


      /**
       * Calendar::__construct()
       * 
       * @return
       */
      function __construct()
      {
		  $this->weekStartedDay = $this->setWeekStart();
		  $this->weekDayNameLength = "long";
		  $this->monthNameLength = "long";
		  $this->init();
          $this->eventInvoice = $this->getCalDataInvoice();
		  $this->eventProject = $this->getCalDataProject();
		  $this->eventTask = $this->getCalDataTask();
      }

	  /**
	   * Calendar::init()
	   * 
	   * @return
	   */
	  private function init()
	  {
          $year = (isset($_POST['year']) && $this->checkYear($_POST['year'])) ? intval($_POST['year']) : date("Y");
          $month = (isset($_POST['month']) && $this->checkMonth($_POST['month'])) ? intval($_POST['month']) : date("m");
          $day = (isset($_POST['day']) && $this->checkDay($_POST['day'])) ? intval($_POST['day']) : date("d");
		  $ldim = $this->calcDays($month, $day);
		  
		  if($day > $ldim) {
		  	$day = $ldim;
		  }
		  
          $cdate = getdate(mktime(0, 0, 0, $month, $day, $year));

          $this->pars["year"] = $cdate['year'];
          $this->pars["month"] = $this->toDecimal($cdate['mon']);
          $this->pars["nmonth"] = $cdate['mon'];
          $this->pars["month_full_name"] = $cdate['month'];
          $this->pars["day"] = $day;
          $this->today = getdate();

          $this->prevYear = getdate(mktime(0, 0, 0, $this->pars['month'], $this->pars["day"], $this->pars['year'] - 1));
          $this->nextYear = getdate(mktime(0, 0, 0, $this->pars['month'], $this->pars["day"], $this->pars['year'] + 1));
          $this->prevMonth = getdate(mktime(0, 0, 0, $this->pars['month'] - 1, $this->calcDays($this->pars['month']-1,$this->pars["day"]), $this->pars['year']));
          $this->nextMonth = getdate(mktime(0, 0, 0, $this->pars['month'] + 1, $this->calcDays($this->pars['month']+1,$this->pars["day"]), $this->pars['year']));
		  

          $this->arrWeekDays[0] = array("mini" => "S", "short" => "Sun", "long" => Lang::$word->SUN);
          $this->arrWeekDays[1] = array("mini" => "M", "short" => "Mon", "long" => Lang::$word->MON);
          $this->arrWeekDays[2] = array("mini" => "T", "short" => "Tue", "long" => Lang::$word->TUE);
          $this->arrWeekDays[3] = array("mini" => "W", "short" => "Wed", "long" => Lang::$word->WED);
          $this->arrWeekDays[4] = array("mini" => "T", "short" => "Thu", "long" => Lang::$word->THU);
          $this->arrWeekDays[5] = array("mini" => "F", "short" => "Fri", "long" => Lang::$word->FRI);
          $this->arrWeekDays[6] = array("mini" => "S", "short" => "Sat", "long" => Lang::$word->SAT);
		  
		  $this->arrMonths[1] = array("short" => Lang::$word->JAN, "long" => Lang::$word->JAN_L);
		  $this->arrMonths[2] = array("short" => Lang::$word->FEB, "long" => Lang::$word->FEB_L);
		  $this->arrMonths[3] = array("short" => Lang::$word->MAR, "long" => Lang::$word->MAR_L);
		  $this->arrMonths[4] = array("short" => Lang::$word->APR, "long" => Lang::$word->APR_L);
		  $this->arrMonths[5] = array("short" => Lang::$word->MAY, "long" => Lang::$word->MAY_L);
		  $this->arrMonths[6] = array("short" => Lang::$word->JUN, "long" => Lang::$word->JUN_L);
		  $this->arrMonths[7] = array("short" => Lang::$word->JUL, "long" => Lang::$word->JUL_L);
		  $this->arrMonths[8] = array("short" => Lang::$word->AUG, "long" => Lang::$word->AUG_L);
		  $this->arrMonths[9] = array("short" => Lang::$word->SEP, "long" => Lang::$word->SEP_L);
		  $this->arrMonths[10] = array("short" => Lang::$word->OCT, "long" => Lang::$word->OCT_L);
		  $this->arrMonths[11] = array("short" => Lang::$word->NOV, "long" => Lang::$word->NOV_L);
		  $this->arrMonths[12] = array("short" => Lang::$word->DEC, "long" => Lang::$word->DEC_L);
	  }
	  
      /**
       * Calendar::renderCalendar()
       * 
       * @return
       */
      public function renderCalendar()
      {
		  $this->drawMonth();
      }


      /**
       * Calendar::checkInvoiceData()
       * 
       * @param mixed $day
       * @return
       */
      private function checkInvoiceData($day)
      {
          if ($this->eventInvoice) {
              foreach ($this->eventInvoice as $v) {
                  if ($day == $v->eday) {
                      return true;
                  }
              }

              return false;
          }
      }

      /**
       * Calendar::checkProjectData()
       * 
       * @param mixed $day
       * @return
       */
      private function checkProjectData($day)
      {
          if ($this->eventProject) {
              foreach ($this->eventProject as $v) {
                  if ($day == $v->eday) {
                      return true;
                  }
              }

              return false;
          }
      }

      /**
       * Calendar::checkTaskData()
       * 
       * @param mixed $day
       * @return
       */
      private function checkTaskData($day)
      {
          if ($this->eventTask) {
              foreach ($this->eventTask as $v) {
                  if ($day == $v->eday) {
                      return true;
                  }
              }

              return false;
          }
      }
	  

      /**
       * Calendar::getCalDataInvoice()
       * 
       * @return
       */
      private function getCalDataInvoice()
      {
		  
		  $sql = "SELECT *, DAY(duedate) as eday, title, DAY(duedate) as eday"
		  . "\n FROM invoices"
		  . "\n WHERE YEAR(duedate) = " . $this->pars['year']
		  . "\n AND MONTH(duedate) = " . $this->pars['month']
		  . "\n AND status = 'Unpaid' AND recurring = 0"
		  . "\n ORDER BY duedate ASC";
		  $row = Registry::get("Database")->fetch_all($sql);
		  
		  return ($row) ? $row : 0;

      }

      /**
       * Calendar::getCalDataProject()
       * 
       * @return
       */
      private function getCalDataProject()
      {
		  $and = (Registry::get("Users")->userlevel == 5) ? "AND staff_id = '" . Registry::get("Users")->uid . "'" : null;
		  
		  $sql = "SELECT *, DAY(end_date) as eday, title, DAY(end_date) as eday"
		  . "\n FROM projects"
		  . "\n WHERE YEAR(end_date) = " . $this->pars['year']
		  . "\n AND MONTH(end_date) = " . $this->pars['month']
		  . "\n AND p_status <> 100"
		  . "\n $and"
		  . "\n ORDER BY end_date ASC";
		  $row = Registry::get("Database")->fetch_all($sql);
		  
		  return ($row) ? $row : 0;

      }

      /**
       * Calendar::getCalDataTask()
       * 
       * @return
       */
      private function getCalDataTask()
      {
		  $and = (Registry::get("Users")->userlevel == 5) ? "AND staff_id = '" . Registry::get("Users")->uid . "'" : null;
		  
		  $sql = "SELECT *, DAY(duedate) as eday, title, DAY(duedate) as eday"
		  . "\n FROM tasks"
		  . "\n WHERE YEAR(duedate) = " . $this->pars['year']
		  . "\n AND MONTH(duedate) = " . $this->pars['month']
		  . "\n AND progress <> 100"
		  . "\n $and"
		  . "\n ORDER BY duedate ASC";
		  $row = Registry::get("Database")->fetch_all($sql);
		  
		  return ($row) ? $row : 0;

      }
	  
      /**
       * Calendar::drawMonth()
       * 
       * @return
       */
	  private function drawMonth()
	  {
		  $is_day = 0;
		  $first_day = getdate(mktime(0, 0, 0, $this->pars['month'], 1, $this->pars['year']));
		  $last_day = getdate(mktime(0, 0, 0, $this->pars['month'] + 1, 0, $this->pars['year']));

		  echo "<div class=\"calnav clearfix\">";
		  echo "<h3><span class=\"month\">" . $this->arrMonths[$this->pars['nmonth']][$this->monthNameLength] . "</span><span class=\"year\">" . $this->pars['year'] . "</span></h3>";
		  echo "<nav>";
		  echo "<a data-id=\"" . $this->toDecimal($this->prevMonth['mon']) . ":" . $this->prevMonth['year'] . "\" class=\"changedate prev\"><i class=\"icon left arrow\"></i></a>";
		  echo "<a data-id=\"" . $this->toDecimal($this->nextMonth['mon']) . ":" . $this->nextMonth['year'] . "\" class=\"changedate next\"><i class=\"icon right arrow\"></i></a>";
		  echo "</nav>";
		  echo "</div>";
		  
		  echo "<div class=\"calheader clearfix\">";
		  for ($i = $this->weekStartedDay - 1; $i < $this->weekStartedDay + 6; $i++) {
			  echo "<div>" . $this->arrWeekDays[($i % 7)][$this->weekDayNameLength] . "</div>";
		  }
		  echo "</div>";
		  echo "<div class=\"calbody clearfix\">";

		  if ($first_day['wday'] == 0) {
			  $first_day['wday'] = 7;
		  }
		  
		 $max_days = $first_day['wday'] - ($this->weekStartedDay - 1);
		 
		  if ($max_days < 7) {
			  echo "<section class=\"section clearfix\">";
			  for ($j = 1; $j <= $max_days; $j++) {
				  echo "<div class=\"empty\">&nbsp;</div>";
			  }
			  $is_day = 0;
			  for ($k = $max_days + 1; $k <= 7; $k++) {
				  $is_day++;
				  $class = '';
				  $tclass = '';
				  $align = '';
				  if (($is_day == $this->today['mday']) && ($this->today['mon'] == $this->pars["month"])) {
					  $tclass = " today";
					  $display = $is_day;
				  }
				  $res = '';
				  if ($this->checkInvoiceData($is_day) or $this->checkProjectData($is_day) or $this->checkTaskData($is_day)) {
					  $data = '';
					  if ($this->checkInvoiceData($is_day) and Registry::get("Users")->userlevel == 9) {
						  foreach ($this->eventInvoice as $erow) {
							  if ($erow->eday == $is_day) {
								  $eurl = "index.php?do=invoices&amp;action=edit&amp;pid=" . $erow->project_id . "&amp;id=" . $erow->id;
								  $res .= "<div><a href=\"" . $eurl . "\" data-content=\"" . $erow->title . "\" class=\"wojo positive label\">" . sanitize($erow->title, 25) . "</a></div>\n";
	
							  }
	
						  }
					  }
					  if ($this->checkProjectData($is_day)) {
						  foreach ($this->eventProject as $prow) {
							  if ($prow->eday == $is_day) {
								  $purl = "index.php?do=projects&amp;action=edit&amp;id=" . $prow->id;
								  $res .= "<div><a href=\"" . $purl . "\" data-content=\"" . $prow->title . "\" class=\"wojo info label\">" . sanitize($prow->title, 25) . "</a></div>\n";
	
							  }
	
						  }
					  }
					  if ($this->checkTaskData($is_day)) {
						  foreach ($this->eventTask as $trow) {
							  if ($trow->eday == $is_day) {
								  $turl = "index.php?do=tasks&amp;action=edit&amp;id=" . $trow->id;
								  $res .= "<div><a href=\"" . $turl . "\" data-content=\"" . $trow->title . "\" class=\"wojo warning label\">" . sanitize($trow->title, 25) . "</a></div>\n";
	
							  }
	
						  }
					  }
	
					  $display = $data . $is_day;
					  $class = " content";
				  } else {
					  $display = $is_day;
				  }
				  $curweek = $this->arrWeekDays[$k-1][$this->weekDayNameLength];
				  echo "<div class=\"caldata" . $class . $tclass . "\"><span class=\"date\">" . $display ."</span><span class=\"weekday\">" . $curweek . "</span>$res</div>";
			  }
			  echo "</section>";
		  }
	
		  $fullWeeks = floor(($last_day['mday'] - $is_day) / 7);
	
		  for ($i = 0; $i < $fullWeeks; $i++) {
			  echo "<section class=\"section clearfix\">";
			  for ($j = 0; $j < 7; $j++) {
				  $is_day++;
				  $class = '';
				  $tclass = '';
				  $align = '';
				  if (($is_day == $this->today['mday']) && ($this->today['mon'] == $this->pars["month"])) {
					  $tclass = " today";
					  $display = $is_day;
				  }
				  $res = '';
				  if ($this->checkInvoiceData($is_day) or $this->checkProjectData($is_day) or $this->checkTaskData($is_day)) {
					  $data = '';
					  if ($this->checkInvoiceData($is_day)) {
						  foreach ($this->eventInvoice as $erow) {
							  if ($erow->eday == $is_day) {
								  $eurl = "index.php?do=invoices&amp;action=edit&amp;pid=" . $erow->project_id . "&amp;id=" . $erow->id;
								  $res .= "<div><a href=\"" . $eurl . "\" data-content=\"" . $erow->title . "\" class=\"wojo positive label\">" . sanitize($erow->title, 25) . "</a></div>\n";
	
							  }
	
						  }
					  }
					  if ($this->checkProjectData($is_day)) {
						  foreach ($this->eventProject as $prow) {
							  if ($prow->eday == $is_day) {
								  $purl = "index.php?do=projects&amp;action=edit&amp;id=" . $prow->id;
								  $res .= "<div><a href=\"" . $purl . "\" data-content=\"" . $prow->title . "\" class=\"wojo info label\">" . sanitize($prow->title, 25) . "</a></div>\n";
	
							  }
	
						  }
					  }
					  if ($this->checkTaskData($is_day)) {
						  foreach ($this->eventTask as $trow) {
							  if ($trow->eday == $is_day) {
								  $turl = "index.php?do=tasks&amp;action=edit&amp;id=" . $trow->id;
								  $res .= "<div><a href=\"" . $turl . "\" data-content=\"" . $trow->title . "\" class=\"wojo warning label\">" . sanitize($trow->title, 25) . "</a></div>\n";
	
							  }
	
						  }
					  }
	
					  $display = $data . $is_day;
					  $class = " calcontent";
				  } else {
					  $display = $is_day;
				  }
				  $curweek = $this->arrWeekDays[($j)][$this->weekDayNameLength];
				  echo "<div class=\"caldata" . $class . $tclass . "\"><span class=\"date\">" . $display ."</span><span class=\"weekday\">" . $curweek . "</span>$res</div>";
			  }
			  echo "</section>";
		  }
	
		  if ($is_day < $last_day['mday']) {
			  echo "<section class=\"section clearfix\">";
			  for ($i = 0; $i < 7; $i++) {
				  $is_day++;
				  $class = '';
				  $tclass = '';
				  $align = '';
				  if (($is_day == $this->today['mday']) && ($this->today['mon'] == $this->pars["month"])) {
					  $tclass = " today";
					  $display = $is_day;
				  }
				  
				  $res = '';
				  if ($this->checkInvoiceData($is_day) or $this->checkProjectData($is_day) or $this->checkTaskData($is_day)) {
					  $data = '';
					  if ($this->checkInvoiceData($is_day)) {
						  foreach ($this->eventInvoice as $erow) {
							  if ($erow->eday == $is_day) {
								  $eurl = "index.php?do=invoices&amp;action=edit&amp;pid=" . $erow->project_id . "&amp;id=" . $erow->id;
								  $res .= "<div><a href=\"" . $eurl . "\" data-content=\"" . $erow->title . "\" class=\"wojo positive label\">" . sanitize($erow->title, 25) . "</a></div>\n";
	
							  }
	
						  }
					  }
					  if ($this->checkProjectData($is_day)) {
						  foreach ($this->eventProject as $prow) {
							  if ($prow->eday == $is_day) {
								  $purl = "index.php?do=projects&amp;action=edit&amp;id=" . $prow->id;
								  $res .= "<div><a href=\"" . $purl . "\" data-content=\"" . $prow->title . "\" class=\"wojo info label\">" . sanitize($prow->title, 25) . "</a></div>\n";
	
							  }
	
						  }
					  }
					  if ($this->checkTaskData($is_day)) {
						  foreach ($this->eventTask as $trow) {
							  if ($trow->eday == $is_day) {
								  $turl = "index.php?do=tasks&amp;action=edit&amp;id=" . $trow->id;
								  $res .= "<div><a href=\"" . $turl . "\" data-content=\"" . $trow->title . "\" class=\"wojo warning label\">" . sanitize($trow->title, 25) . "</a></div>\n";
	
							  }
	
						  }
					  }
	
					  $display = $data . $is_day;
					  $class = " calcontent";
				  } else {
					  $display = $is_day;
				  }

				$curweek = $this->arrWeekDays[$i][$this->weekDayNameLength]; 
				echo ($is_day <= $last_day['mday']) ? "<div class=\"caldata" . $class . $tclass . "\"><span class=\"date\">" . $display . "</span><span class=\"weekday\">$curweek</span>$res</div>" : "<div class=\"empty\">&nbsp;</div>";  
			  }
			  echo "</section>";
		  }

		  echo "</div>";
	  }

      /**
       * eventManager::setWeekStart()
       * 
       * @return
       */
      private function setWeekStart()
      {
		  
		  return Registry::get("Core")->weekstart;
      }
	  
	/**
	 * Calendar::calcDays()
	 * 
	 * @param string $month
	 * @param string $day
	 * @return
	 */
	  private function calcDays($month, $day)
	  {
		  if ($day < 29) {
			  return $day;
		  } elseif ($day == 29) {
			  return ((int)$month == 2) ? 28 : 29;
		  } elseif ($day == 30) {
			  return ((int)$month != 2) ? 30 : 28;
		  } elseif ($day == 31) {
			  return ((int)$month == 2 ? 28 : ((int)$month == 4 || (int)$month == 6 || (int)$month == 9 || (int)$month == 11 ? 30 : 31));
		  } else {
			  return 30;
		  }
	
	  }
	  
      /**
       * Calendar::toDecimal()
       * 
       * @param mixed $number
       * @return
       */
      private static function toDecimal($number)
      {
          return (($number < 10) ? "0" : "") . $number;
      }
	  
      /**
       * Calendar::checkYear()
       * 
       * @param string $year
       * @return
       */
      private static function checkYear($year)
      {
          return (strlen($year) == 4 or ctype_digit($year)) ? true : false;
      }


      /**
       * Calendar::checkMonth()
       * 
       * @param string $month
       * @return
       */
      private static function checkMonth($month)
      {
          return ((strlen($month) == 2) or ctype_digit($month) or ($month < 12)) ? true : false;
      }


      /**
       * Calendar::checkDay()
       * 
       * @param string $day
       * @return
       */
      private static function checkDay($day)
      {
          return ((strlen($day) == 2) or ctype_digit($day) or ($day < 31)) ? true : false;
      }
  }
?>