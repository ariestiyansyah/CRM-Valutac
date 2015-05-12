<?php
  /**
   * Account Profile
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: account.php, v3.00 2014-08-10 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  require_once("init.php");
  
  if (!$user->logged_in)
      redirect_to("index.php");

   $formlist = $content->getFormsList();
   $estlist = $content->getEstimatorList();
?>
<?php include("header.php");?>
<div class="wojo-grid">
  <?php if(isset($_GET['msg']) and $_GET['msg'] == 1) Filter::msgAlert('Selected File does not exist!');?>
  <div class="panel-wrap">
    <div class="menu-wrap">
      <?php include_once("menu.php");?>
    </div>
    <div class="main-wrap">
      <header>
        <div class="wojo secondary menu"> <a href="account.php?do=contact" class="item"> <i class="mail icon"></i> <?php echo Lang::$word->FMENU_MSGS;?> <span class="wojo label"><?php echo $core->countMessages();?></span></a> <a href="account.php?do=support" class="item"> <i class="support icon"></i> <?php echo Lang::$word->FMENU_SUPPORT;?> <span class="wojo label"><?php echo $core->countFrontTickets();?></span></a>
          <?php if($formlist or $estlist):?>
          <div class="wojo pointing dropdown link item"> <?php echo Lang::$word->FMENU_FORMS;?> <i class="icon dropdown"></i>
            <div class="menu">
              <?php foreach ($formlist as $flist):?>
              <a class="item" href="account.php?do=forms&amp;id=<?php echo $flist->id;?>"><i class="icon check"></i><?php echo $flist->title;?></a>
              <?php endforeach;?>
              <?php if($estlist):?>
              <?php foreach ($estlist as $elist):?>
              <a class="item" href="account.php?do=estimator&amp;id=<?php echo $elist->id;?>"><i class="icon check"></i><?php echo $elist->title;?></a>
              <?php endforeach;?>
              <?php endif;?>
            </div>
          </div>
          <?php endif;?>
          <div class="right menu">
            <div class="wojo dropdown link item"> <i class="icon language"></i> <?php echo Lang::$word->CONF_LANG;?> <i class="icon dropdown"></i>
              <div id="langmenu" class="menu">
                <?php foreach(Lang::fetchLanguage() as $lang):?>
                <?php if($core->lang == $lang):?>
                <a class="item active">
                <div class="wojo label"><?php echo strtoupper($lang);?></div>
                <?php echo Lang::$word->MENU_LANG;?></a>
                <?php else:?>
                <a class="item langchange" href="account.php?<?php echo $_SERVER['QUERY_STRING'];?>" data-lang="<?php echo $lang;?>">
                <div class="wojo label"><?php echo strtoupper($lang);?></div>
                <?php echo Lang::$word->MENU_LANG_C;?></a>
                <?php endif?>
                <?php endforeach;?>
              </div>
            </div>
            <a href="<?php echo SITEURL;?>/logout.php" class="wojo negative button item"> <i class="icon off "></i> <?php echo Lang::$word->MENU_LOGOUT;?> </a> </div>
        </div>
      </header>
      <?php switch(Filter::$do): case "projects": ?>
      <?php (file_exists("projects.php")) ? include("projects.php") : include("main.php");?>
      <?php break;?>
      <?php case"billing":?>
      <?php (file_exists("billing.php")) ? include("billing.php") : include("main.php");?>
      <?php break;?>
      <?php case"profile":?>
      <?php (file_exists("profile.php")) ? include("profile.php") : include("main.php");?>
      <?php break;?>
      <?php case"contact":?>
      <?php (file_exists("contact.php")) ? include("contact.php") : include("main.php");?>
      <?php break;?>
      <?php case"support":?>
      <?php (file_exists("support.php")) ? include("support.php") : include("main.php");?>
      <?php break;?>
      <?php case"forms":?>
      <?php (file_exists("forms.php")) ? include("forms.php") : include("main.php");?>
      <?php break;?>
      <?php case"estimator":?>
      <?php (file_exists("estimator.php")) ? include("estimator.php") : include("main.php");?>
      <?php break;?>
      <?php default:?>
      <?php include("main.php");?>
      <?php break;?>
      <?php endswitch;?>
    </div>
  </div>
</div>
<?php include("footer.php");?>