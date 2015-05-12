<?php
  /**
   * Main
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: main.php, v3.00 2014-05-08 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  $row = $core->getYearlySummary();
  $row2 = $core->getYearlySummaryServices();
  $clients = $core->countUsers(1);
  $staff = $core->countUsers(5);
  $projects = $core->countProjects();
  $invoices = $core->countInvoices();
  $reports = $core->yearlyStats();
  $reports3 = $core->yearlyStatsServices();
  $reports2 = $core->projectStats();
?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><i class="dashboard icon"></i><?php echo Lang::$word->DASH_STATS;?></h2>
</div>
<div class="columns small-gutters">
  <div class="screen-33 tablet-50 phone-100"> <a href="index.php?do=clients">
    <div class="wojo positive basic segment">
      <div class="columns">
        <div class="screen-30"><i class="huge dimmed users icon"></i> </div>
        <div class="screen-70">
        
          <div class="wojo caps"><?php echo Lang::$word->DASH_ACTCLIENTS;?></div>
          <div class="wojo big font"><?php echo ($clients) ? $clients : '0';?></div>
        </div>
      </div>
    </div>
    </a> </div>
  <div class="screen-33 tablet-50 phone-100"> <a href="index.php?do=users">
    <div class="wojo warning basic segment">
      <div class="columns">
        <div class="screen-30"><i class="huge dimmed user icon"></i> </div>
        <div class="screen-70">
          <div class="wojo caps"><?php echo Lang::$word->DASH_STAFFMEMBER;?></div>
          <div class="wojo big font"><?php echo ($staff) ? $staff : '0';?></div>
        </div>
      </div>
    </div>
    </a> </div>
  <div class="screen-33 tablet-50 phone-100"> <a href="index.php?do=projects">
    <div class="wojo info basic segment">
      <div class="columns">
        <div class="screen-30"><i class="huge dimmed send icon"></i> </div>
        <div class="screen-70">
          <div class="wojo caps"><?php echo Lang::$word->FPRO_TITLE3;?></div>
          <div class="wojo big font"><?php echo $projects;?></div>
        </div>
      </div>
    </div>
    </a> </div>
  <div class="screen-33 tablet-50 phone-100"> <a href="index.php?do=support">
    <div class="wojo danger basic segment">
      <div class="columns">
        <div class="screen-30"><i class="huge dimmed ticket icon"></i> </div>
        <div class="screen-70">
          <div class="wojo caps"><?php echo Lang::$word->DASH_UTICKETS;?></div>
          <div class="wojo big font"><?php echo $countTickets;?></div>
        </div>
      </div>
    </div>
    </a> </div>
  <div class="screen-33 tablet-50 phone-100"><a href="index.php?do=invstatus">
    <div class="wojo purple basic segment">
      <div class="columns">
        <div class="screen-30"><i class="huge dimmed file text icon"></i> </div>
        <div class="screen-70">
          <div class="wojo caps"><?php echo Lang::$word->DASH_UINVOICE;?></div>
          <div class="wojo big font"><?php echo $invoices;?></div>
        </div>
      </div>
    </div>
    </a> </div>
  <div class="screen-33 tablet-50 phone-100"> <a href="index.php?do=messages">
    <div class="wojo teal basic segment">
      <div class="columns">
        <div class="screen-30"><i class="huge dimmed mail icon"></i> </div>
        <div class="screen-70">
          <div class="wojo caps"><?php echo Lang::$word->DASH_IMESSAGE;?></div>
          <div class="wojo big font"><?php echo $countMsgs;?></div>
        </div>
      </div>
    </div>
    </a> </div>
</div>
<?php if($user->userlevel == 9):?>
<div class="wojo divider"></div>
<div class="wojo basic block segment">
  <div class="wojo selection dropdown push-right" data-select-range="true">
    <div class="text"><?php echo Lang::$word->ACTIONS;?></div>
    <i class="dropdown icon"></i>
    <div class="menu">
      <div class="item" data-value="day"><?php echo Lang::$word->DASH_TODAY;?></div>
      <div class="item" data-value="week"><?php echo Lang::$word->DASH_WEEK;?></div>
      <div class="item" data-value="month"><?php echo Lang::$word->DASH_MONTH;?></div>
      <div class="item" data-value="year"><?php echo Lang::$word->DASH_YEAR;?></div>
    </div>
    <input name="range" type="hidden" value="">
  </div>
  <h2 class="wojo left floated header"><i class="payment icon"></i><?php echo Lang::$word->DASH_TOTALPRJ;?></h2>
</div>
<div id="chart" style="height:400px;overflow:hidden"></div>
<div class="wojo divider"></div>
<?php if(!$reports):?>
<?php echo Filter::msgInfo(Lang::$word->DASH_ERR2);?>
<?php else:?>
<!-- Start Revenue List-->
<table class="wojo basic table">
  <thead>
    <tr>
      <th><?php echo Lang::$word->DASH_MONTHYEAR;?></th>
      <th>#<?php echo Lang::$word->TRANS;?></th>
      <th><?php echo Lang::$word->DASH_TOTALPRJ;?></th>
    </tr>
  </thead>
  <?php foreach($reports as $report):?>
  <tr>
    <td><?php echo utf8_encode(strftime('%b', strtotime(date("M", mktime(0, 0, 0, $report->month, 10))))).' / '.$core->year;?></td>
    <td><?php echo $report->total;?></td>
    <td><?php echo $core->formatMoney($report->totalprice);?></td>
  </tr>
  <?php endforeach ?>
  <?php unset($report);?>
  <tr class="warning">
    <td><i class="icon calendar"></i> <?php echo Lang::$word->DASH_TOTALTEAR;?></td>
    <td><i class="icon refresh"></i> <?php echo $row->total;?></td>
    <td><i class="icon money"></i> <?php echo $core->formatMoney($row->totalprice);?></td>
  </tr>
</table>
<!-- End Revenue List-->
<?php endif;?>
<?php if(!$reports3):?>
<?php echo Filter::msgSingleInfo(Lang::$word->DASH_ERR3);?>
<?php else:?>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><i class="payment icon"></i><?php echo Lang::$word->DASH_TOTALSER;?></h2>
</div>
<!-- Start Service List-->
<table class="wojo basic table">
  <thead>
    <tr>
      <th><?php echo Lang::$word->DASH_MONTHYEAR;?></th>
      <th>#<?php echo Lang::$word->TRANS;?></th>
      <th><?php echo Lang::$word->DASH_TOTALSER;?></th>
    </tr>
  </thead>
  <?php foreach($reports3 as $report):?>
  <tr>
    <td><?php echo utf8_encode(strftime('%b', strtotime(date("M", mktime(0, 0, 0, $report->month, 10))))).' / '.$core->year;?></td>
    <td><?php echo $report->total;?></td>
    <td><?php echo $core->formatMoney($report->totalprice);?></td>
  </tr>
  <?php endforeach ?>
  <?php unset($report);?>
  <tr class="positive">
    <td><i class="icon calendar"></i> <?php echo Lang::$word->DASH_TOTALTEAR;?></td>
    <td><i class="icon refresh"></i> <?php echo $row2->total;?></td>
    <td><i class="icon money"></i> <?php echo $core->formatMoney($row2->totalprice);?></td>
  </tr>
</table>
<!-- End Service List-->
<?php endif;?>
<?php endif;?>
<!-- Start Pending Projects -->
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><i class="briefcase icon"></i><?php echo Lang::$word->DASH_PENDING;?></h2>
</div>
<table class="wojo basic table">
  <thead>
    <tr>
      <th><?php echo Lang::$word->PROJ_NAME;?></th>
      <th><?php echo Lang::$word->CREATED;?></th>
      <th><?php echo Lang::$word->STATUS;?></th>
    </tr>
  </thead>
  <?php if(!$reports2):?>
  <tr>
    <td colspan="3"><?php echo Filter::msgSingleInfo(Lang::$word->DASH_NOPENDING);?></td>
  </tr>
  <?php else:?>
  <?php foreach($reports2 as $rep):?>
  <tr>
    <td><a href="index.php?do=projects&amp;action=details&amp;id=<?php echo $rep->id;?>"><?php echo $rep->title;?></a></td>
    <td><?php echo Filter::doDate("short_date", $rep->start_date);?></td>
    <td><?php echo $content->progressBarStatus($rep->p_status);?></td>
  </tr>
  <?php endforeach ?>
  <?php endif;?>
  <?php unset($rep);?>
</table>
<!-- End Pending Projects /-->
<div class="wojo double fitted divider"></div>
<div class="columns small-horizontal-gutters">
  <div class="screen-50 phone-100">
    <div class="wojo black basic message">
      <div class="wojo fluid input top attached">
        <input placeholder="<?php echo Lang::$word->SEARCH2;?>" type="text" name="address" id="address">
      </div>
      <div id="gmap" style="width:100%;height:350px;"></div>
    </div>
  </div>
  <div class="screen-50 phone-100">
    <div class="wojo black basic message">
      <div class="wojo fluid input top attached">
        <input placeholder="<?php echo Lang::$word->SEARCH2;?>" type="text" name="location" id="location">
      </div>
      <div id="weather">
        <div class="weather-body clearfix">
          <div class="push-left weather-left">
            <div class="today-img content-center"></div>
            <h1 class="text-center today-temp">-</h1>
          </div>
          <div class="push-right weather-place">
            <h1 class="weather-city">-</h1>
            <div class="weather-region"></div>
            <h3 class="weather-currently">-</h3>
          </div>
        </div>
        <div class="weather-footer clearfix">
          <div class="columns">
            <div class="screen-25 weather-footer-block">
              <div class="1-days-day"></div>
              <div class="1-days-image"></div>
              <div class="1-days-temp"></div>
            </div>
            <div class="screen-25 weather-footer-block">
              <div class="2-days-day"></div>
              <div class="2-days-image"></div>
              <div class="2-days-temp"></div>
            </div>
            <div class="screen-25 weather-footer-block">
              <div class="3-days-day"></div>
              <div class="3-days-image"></div>
              <div class="3-days-temp"></div>
            </div>
            <div class="screen-25 weather-footer-block">
              <div class="4-days-day"></div>
              <div class="4-days-image"></div>
              <div class="4-days-temp"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="../assets/jquery.flot.js"></script> 
<script type="text/javascript" src="../assets/flot.resize.js"></script> 
<script type="text/javascript" src="../assets/excanvas.min.js"></script> 
<script type="text/javascript" src="assets/js/location.js"></script> 
<script type="text/javascript" src="http://maps.google.com/maps/api/js?v=3&amp;sensor=false"></script> 
<script type="text/javascript" src="assets/js/home.js"></script> 
<script type="text/javascript">
// <![CDATA[
$(document).ready(function () {
    $.Home({
	  lat:43.652527,
	  long:-79.381961,
	  temp: 'c',
	  city: 'Toronto',
    });
});
// ]]>
</script> 
