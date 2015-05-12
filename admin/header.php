<?php
  /**
   * Header
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2011
   * @version $Id: header.php, v2.00 2013-05-08 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<!DOCTYPE html>
<head>
<meta charset="utf-8">
<title><?php echo $core->company;?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="http://fonts.googleapis.com/css?family=Roboto:400,300,500,700" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Roboto+Condensed:300,400,700" rel="stylesheet" type="text/css">
<link href="<?php echo THEMEU . '/cache/' . Minify::cache(array('css/base.css','css/button.css','css/image.css','css/icon.css','css/breadcrumb.css','css/popup.css','css/form.css','css/input.css','css/table.css','css/label.css','css/segment.css','css/message.css','css/divider.css','css/dropdown.css','css/list.css','css/progress.css','css/header.css','css/menu.css','css/datepicker.css','css/editor.css','css/utility.css','css/style.css'),'css');?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../assets/jquery.js"></script>
<script type="text/javascript" src="../assets/jquery-ui.js"></script>
<script src="../assets/modernizr.mq.js"></script>
<script type="text/javascript" src="../assets/global.js"></script>
<script type="text/javascript" src="../assets/editor.js"></script>
<script src="../assets/jquery.ui.touch-punch.js"></script>
<script type="text/javascript" src="assets/js/master.js"></script>
<?php if(file_exists("../plugins/" . Filter::$do . "/theme/" . $core->theme . "/style.css")):?>
<link href="../plugins/<?php echo Filter::$do;?>/theme/<?php echo $core->theme;?>/style.css" rel="stylesheet" type="text/css" />
<?php endif;?>
</head>
<body class="animated">
<div id="helpbar" class="wojo wide floating info right sidebar"></div>
<?php $countTickets = $core->countTickets();?>
<?php $countMsgs = $core->countMessages();?>
<?php $countTasks = $core->countTasks();?>
<div class="wojo-grid">
  <header>
    <div class="columns">
      <div class="screen-40 tablet-50 phone-100"> <a href="index.php"><?php echo ($core->logo) ? '<img src="'.SITEURL.'/uploads/'.$core->logo.'" alt="'.$core->company.'" class="logo"/>': $core->company;?></a> </div>
      <!-- Timer -->
      <div class="screen-20 hide-tablet hide-phone">
        <div class="timer push-center">
          <div id="stopwatch"> <span id="sw_h">00</span><em>:</em> <span id="sw_m">00</span><em>:</em> <span id="sw_s">00</span><em>:</em> <span id="sw_ms">00</span> </div>
          <a id="sw_start" data-content="<?php echo Lang::$word->START;?>"><i class="icon play"></i></a> <a id="sw_pause" data-content="<?php echo Lang::$word->PAUSE;?>"><i class="icon pause"></i></a> <a id="sw_stop" data-content="<?php echo Lang::$word->STOP;?>"><i class="icon stop"></i></a> <a id="sw_reset" data-content="<?php echo Lang::$word->RESET;?>"><i class="icon circle ban"></i></a> <a id="sw_make" data-content="<?php echo Lang::$word->BILL_ADD_B;?>"><i class="icon add"></i></a> </div>
      </div>
      <!-- Timer /--> 
      
      <!-- User Panel -->
      <div class="screen-40 tablet-50 phone-100">
        <div id="userpanel" class="push-right">
          <div class="wojo top right pointing dropdown">
            <div class="alt text"><?php echo Lang::$word->MENU_WELCOME;?> <?php echo $user->username;?>!<br>
              <?php echo Lang::$word->STAFF_LASTLOGIN . ' ' . Filter::dodate("long_date", $user->last);?></div>
            <div class="text"><img src="<?php echo UPLOADURL;?>/avatars/<?php echo ($user->avatar) ? $user->avatar : "blank.png";?>" alt="" class="wojo image avatar"></div>
            <div class="wojo vertical menu"> <a class="item" href="index.php?do=users&amp;action=edit&amp;id=<?php echo $user->uid;?>"><i class="pencil icon"></i><?php echo Lang::$word->SETTINGS;?></a> <a class="item" href="index.php?do=support">
              <div class="wojo label"><?php echo $countTickets;?></div>
              <?php echo Lang::$word->TICKETS;?></a> <a class="item" href="index.php?do=messages">
              <div class="wojo label"><?php echo $countMsgs;?></div>
              <?php echo Lang::$word->MENU_MSG;?></a> <a class="item" href="index.php?do=tasks">
              <div class="wojo label"><?php echo $countTasks;?></div>
              <?php echo Lang::$word->TASKS;?></a>
              <?php foreach(Lang::fetchLanguage() as $lang):?>
              <?php if($core->lang == $lang):?>
              <a class="item active">
              <div class="wojo label"><?php echo strtoupper($lang);?></div>
              <?php echo Lang::$word->MENU_LANG;?></a>
              <?php else:?>
              <a class="item langchange" href="index.php?<?php echo $_SERVER['QUERY_STRING'];?>" data-lang="<?php echo $lang;?>">
              <div class="wojo label"><?php echo strtoupper($lang);?></div>
              <?php echo Lang::$word->MENU_LANG_C;?></a>
              <?php endif?>
              <?php endforeach;?>
              <a class="item" href="logout.php"><i class="off icon"></i><?php echo Lang::$word->MENU_LOGOUT;?></a> </div>
          </div>
        </div>
      </div>
      <!-- User Panel /--> 
      
    </div>
  </header>
  <!-- Main Menu -->
  <nav class="clearfix">
    <ul>
      <li><a<?php if (Filter::$do == 'users') echo ' class="active"';?> href="index.php?do=users"><i class="icon user"></i><?php echo Lang::$word->MENU_STAFF;?></a></li>
      <?php if($user->userlevel == 9):?>
      <li><a<?php if (Filter::$do == 'clients') echo ' class="active"';?> href="index.php?do=clients"><i class="icon users"></i><?php echo Lang::$word->MENU_CLIENTS;?></a></li>
      <?php endif;?>
      <li class="submenu"> <a<?php if (in_array(Filter::$do, array('projects','tasks','types','task_template','files','submissions'))) echo ' class="active"';?>><i class="icon send"></i><?php echo Lang::$word->MENU_PM;?><i class="icon triangle down"></i></a>
        <ul>
          <li><a class="item" href="index.php?do=projects"><i class="icon send"></i><?php echo Lang::$word->MENU_MP;?></a></li>
          <?php if($user->userlevel == 9):?>
          <li><a class="item" href="index.php?do=types"><i class="icon briefcase"></i><?php echo Lang::$word->MENU_PTYPES;?></a></li>
          <?php endif;?>
          <li><a class="item" href="index.php?do=tasks"><i class="icon tasks"></i><?php echo Lang::$word->MENU_PTASKS;?></a></li>
          <li><a class="item" href="index.php?do=task_template"><i class="cubes icon"></i><?php echo Lang::$word->MENU_TT;?></a></li>
          <li><a class="item" href="index.php?do=files"><i class="icon file"></i><?php echo Lang::$word->MENU_PFILES;?></a></li>
          <?php if($user->userlevel == 9):?>
          <li><a class="item" href="index.php?do=projects&amp;action=add"><i class="icon add"></i><?php echo Lang::$word->PROJ_ADD;?></a></li>
          <?php endif;?>
        </ul>
      </li>
      <li class="submenu"> <a<?php if (in_array(Filter::$do, array('news','email','mtemplates','language'))) echo ' class="active"';?>><i class="icon paste"></i><?php echo Lang::$word->MENU_CM;?><i class="icon triangle down"></i></a>
        <ul>
          <li><a class="item" href="index.php?do=news"><i class="icon attachment"></i><?php echo Lang::$word->MENU_SN;?></a></li>
          <li><a class="item" href="index.php?do=email"><i class="icon mail"></i><?php echo Lang::$word->MENU_EM;?></a></li>
          <li><a class="item" href="index.php?do=mtemplates"><i class="icon layout block"></i><?php echo Lang::$word->MENU_MT;?></a></li>
          <li><a class="item" href="index.php?do=language"><i class="icon language"></i><?php echo Lang::$word->MENU_LG;?></a></li>
        </ul>
      </li>
      <?php if($user->userlevel == 9):?>
      <li class="submenu"><a<?php if (in_array(Filter::$do, array('timebilling','invoices','invstatus','quotes','transactions'))) echo ' class="active"';?>><i class="icon payment"></i><?php echo Lang::$word->MENU_BM;?><i class="icon triangle down"></i></a>
        <ul>
          <li><a class="item" href="index.php?do=timebilling"><i class="icon time"></i><?php echo Lang::$word->MENU_TB;?></a></li>
          <li><a class="item" href="index.php?do=invstatus"><i class="icon file text"></i><?php echo Lang::$word->MENU_VI;?></a></li>
          <li><a class="item" href="index.php?do=quotes"><i class="icon file text outline"></i><?php echo Lang::$word->MENU_VQ;?></a></li>
          <li><a class="item" href="index.php?do=transactions"><i class="icon exchange"></i><?php echo Lang::$word->MENU_VT;?></a></li>
          <li><a class="item" href="controller.php?action=createReport"><i class="icon excel outline"></i><?php echo Lang::$word->TRANS_REPORT;?></a></li>
        </ul>
      </li>
      <?php endif;?>
      <?php if($user->userlevel == 9):?>
      <li class="submenu"><a<?php if (in_array(Filter::$do, array('config','gateways','fields','backup','system'))) echo ' class="active"';?>><i class="icon sliders"></i><?php echo Lang::$word->MENU_SETUP;?><i class="icon triangle down"></i></a>
        <ul>
          <li><a class="item" href="index.php?do=config"><i class="icon sliders"></i><?php echo Lang::$word->MENU_SC;?></a></li>
          <li><a class="item" href="index.php?do=gateways"><i class="icon exchange"></i><?php echo Lang::$word->MENU_PG;?></a></li>
          <li><a class="item" href="index.php?do=fields"><i class="icon asterisk"></i><?php echo Lang::$word->MENU_CF;?></a></li>
          <li><a class="item" href="index.php?do=system"><i class="icon information"></i><?php echo Lang::$word->MENU_SI;?></a></li>
          <li><a class="item" href="index.php?do=backup"><i class="icon database"></i><?php echo Lang::$word->MENU_DB;?></a></li>
        </ul>
      </li>
      <?php endif;?>
      <li class="submenu"> <a<?php if (in_array(Filter::$do, array('forms','estimator','calendar','support'))) echo ' class="active"';?>><i class="icon setting"></i><?php echo Lang::$word->MANAGE;?><i class="icon triangle down"></i></a>
        <ul>
          <?php if($user->userlevel == 9):?>
          <li><a class="item" href="index.php?do=forms"><i class="icon layout block"></i><?php echo Lang::$word->MENU_VFORMS;?></a></li>
          <li><a class="item" href="index.php?do=forms&amp;action=add"><i class="icon plus"></i><?php echo Lang::$word->FORM_ADDV;?></a></li>
          <li><a class="item" href="index.php?do=estimator"><i class="icon layout block"></i><?php echo Lang::$word->MENU_VEST;?></a></li>
          <li><a class="item" href="index.php?do=estimator&amp;action=add"><i class="icon plus"></i><?php echo Lang::$word->ESTM_ADD;?></a></li>
          <?php endif;?>
          <li><a class="item" href="index.php?do=calendar"><i class="icon calendar"></i><?php echo Lang::$word->MENU_CAL;?></a></li>
          <li><a class="item" href="index.php?do=support"><i class="icon support"></i><?php echo Lang::$word->MENU_SUPPORT;?></a></li>
        </ul>
      </li>
      <?php if(Core::fetchPluginInfo()):?>
      <li class="submenu"> <a<?php if(get('plugin')) echo ' class="active"';?>><i class="icon puzzle piece"></i><?php echo Lang::$word->PLUGINS;?><i class="icon triangle down"></i></a>
        <ul>
          <?php foreach(Core::fetchPluginInfo() as $i => $prow):?>
          <li><a class="item" href="index.php?do=<?php echo $prow->admin_url;?>&amp;plugin=<?php echo $prow->dir;?>"><i class="<?php echo $prow->icon;?>"></i><?php echo $prow->{'title_' . $core->lang};?></a></li>
          <?php endforeach;?>
        </ul>
      </li>
      <?php endif;?>
    </ul>
  </nav>
  <!-- Main Menu  /-->
  <div class="crumbnav">
    <?php include_once("helper.php");?>
    <div class="wojo breadcrumb">
      <?php include_once("crumbs.php");?>
    </div>
  </div>
</div>
