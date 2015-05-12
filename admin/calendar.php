<?php
  /**
   * Event Calendar
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: calendar.php, v3.00 2014-05-08 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  require_once(BASEPATH . "lib/class_calendar.php");
  Registry::set('Calendar',new Calendar());
?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->CAL_INFO;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><i class="calendar icon"></i><?php echo Lang::$word->CAL_SUB2;?></h2>
</div>
<div class="vspace">
  <div class="wojo positive circular label"></div>
  <small><?php echo Lang::$word->INVOICES;?></small>
  <div class="wojo info circular label"></div>
  <small><?php echo Lang::$word->PROJECTS;?></small>
  <div class="wojo warning circular label"></div>
  <small><?php echo Lang::$word->TASKS;?></small> </div>
<div id="calendar" class="wojo segment">
  <?php Registry::get("Calendar")->renderCalendar();?>
</div>
<script type="text/javascript">
// <![CDATA[
  $(document).ready(function () {
	  $("#calendar").on("click", "a.changedate", function () {
          $('#calendar').addClass('loading');
          var caldata = $(this).data('id');
          var month = caldata.split(":")[0];
          var year = caldata.split(":")[1];
		  $.ajax({
			  type: "POST",
			  url: "controller.php",
              data: {
                  'year': year,
                  'month': month,
                  'getcal': 1
              },
              success: function (data) {
                  $("#calendar").fadeIn("slow", function () {
                      $(this).html(data);
                  });
				  $('#calendar').removeClass('loading');
              }
		  });
		  return false;
	  });
  });
// ]]>
</script>