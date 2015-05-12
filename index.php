<?php
  /**
   * Index
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: index.php, v3.00 2014-07-10 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  require_once ("init.php");

  if ($user->logged_in)
      redirect_to("account.php");

  if (isset($_POST['doLogin'])):
      $result = $user->login($_POST['username'], $_POST['password']);

      /* Login Successful */
      if ($result):
          redirect_to("account.php");
      endif;
  endif;
?>
<!DOCTYPE html>
<head>
<meta charset="utf-8">
<title><?php echo $core->company;?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="<?php echo Core::protocol();?>://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700" rel="stylesheet" type="text/css">
<link href="<?php echo THEMEURL;?>/css/base.css" rel="stylesheet" type="text/css" />
<link href="<?php echo THEMEURL;?>/css/button.css" rel="stylesheet" type="text/css" />
<link href="<?php echo THEMEURL;?>/css/icon.css" rel="stylesheet" type="text/css" />
<link href="<?php echo THEMEURL;?>/css/segment.css" rel="stylesheet" type="text/css" />
<link href="<?php echo THEMEURL;?>/css/message.css" rel="stylesheet" type="text/css" />
<link href="<?php echo THEMEURL;?>/css/divider.css" rel="stylesheet" type="text/css" />
<link href="<?php echo THEMEURL;?>/css/form.css" rel="stylesheet" type="text/css" />
<link href="<?php echo THEMEURL;?>/css/utility.css" rel="stylesheet" type="text/css" />
<link href="<?php echo THEMEURL;?>/css/login.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
var SITEURL = "<?php echo SITEURL;?>";
</script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.js"></script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/modernizr.mq.js"></script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/global.js"></script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/editor.js"></script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.ui.touch-punch.js"></script>
<script type="text/javascript" src="<?php echo THEMEURL;?>/js/master.js"></script>
</head>
<body id="login-body">
<div class="clearfix">
  <div id="rpanel">
    <div class="inner">
      <div id="login-date"><span><?php echo strftime("%B");?></span> <?php echo strftime("%Y");?></div>
      <?php $newsrow = $user->getLatestNews();?>
      <?php if($newsrow):?>
      <div id="latest-news">
      </div>
      <?php endif;?>
      <div id="cityloc">
        <div class="wojo icon transparent message"> <i class="icon map marker"></i>
          <div class="content">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="lpanel">
    <div class="wrapper"> <a href="index.php"><?php echo ($core->logo) ? '<img src="'.SITEURL.'/uploads/'.$core->logo.'" alt="'.$core->company.'" class="logo"/>': $core->company;?></a>
      <h1 class="wojo header"><?php echo Core::sayHello();?></h1>
      <div class="rquote"> <?php print Core::randomQuotes('i');?> </div>
      <div class="wojo inverted segment form">
        <form method="post" id="login_form" name="login_form">
          <p class="wojo info"><?php echo Lang::$word->LOGIN_INFO;?></p>
          <div class="wojo inverted divider"></div>
          <div class="field">
            <label class="input"> <i class="icon-prepend icon user"></i> <i class="icon-append icon asterisk"></i>
              <input  type="text" name="username" placeholder="<?php echo Lang::$word->USERNAME;?>">
            </label>
          </div>
          <div class="field">
            <label class="input"> <i class="icon-prepend icon lock"></i> <i class="icon-append icon asterisk"></i>
              <input type="password"  name="password" placeholder="**********">
            </label>
          </div>
          <div class="field">
            <button type="submit" name="doLogin" class="wojo button"><?php echo Lang::$word->LOGIN_BUT_NOW;?></button>
            <a id="passreset" class="inline left-space"><?php echo Lang::$word->LOGIN_PASS_RESET;?></a> </div>
        </form>
        <form id="wojo_form" name="wojo_form" method="post" style="display:none;">
          <p class="wojo info"><?php echo Lang::$word->LOGIN_INFO1;?></p>
          <div class="wojo inverted divider"></div>
          <div class="field">
            <label class="input"> <i class="icon-prepend icon user"></i> <i class="icon-append icon asterisk"></i>
              <input  type="text" name="uname" placeholder="<?php echo Lang::$word->USERNAME;?>">
            </label>
          </div>
          <div class="field">
            <label class="input"> <i class="icon-prepend icon envelope"></i> <i class="icon-append icon asterisk"></i>
              <input  type="text" name="email" placeholder="<?php echo Lang::$word->EMAIL;?>">
            </label>
          </div>
          <div class="field">
            <label class="input"> <img src="lib/captcha.php" alt="" class="captcha-append" /> <i class="icon-prepend icon unhide"></i>
              <input type="text" name="captcha" placeholder="<?php echo Lang::$word->CAPTCHA;?>">
            </label>
          </div>
          <div class="field">
            <button type="button" data-url="/ajax/user.php" name="dosubmit" class="wojo button"><?php echo Lang::$word->LOGIN_SUBMIT;?></button>
            <a id="backto" class="inline left-space"><?php echo Lang::$word->REG_BACK;?></a> </div>
            <input name="passReset" type="hidden" value="1">
        </form>
      </div>
      <div id="msgholder"><?php print Filter::$showMsg;?></div>
    </div>
  </div>
</div>
<div id="ifooter">
<script type="text/javascript">
// <![CDATA[  
$(document).ready(function () {
    $('#backto').click(function () {
        $("#wojo_form").slideUp("slow");
        $("#login_form").slideDown("slow");
    });
    $('#passreset').click(function () {
        $("#login_form").slideUp("slow");
        $("#wojo_form").slideDown("slow");
    });
    $.Master({
		weekstart: <?php echo ($core->weekstart - 1);?>,
        lang: {
            button_text: "<?php echo Lang::$word->BROWSE;?>",
            empty_text: "<?php echo Lang::$word->NOFILE;?>",
            monthsFull: [<?php echo Core::monthList(false);?>],
            monthsShort: [<?php echo Core::monthList(false, false);?>],
			weeksFull : [<?php echo Core::weekList(false);?>],
			weeksShort : [<?php echo Core::weekList(false, false);?>],
			today : "<?php echo Lang::$word->DASH_TODAY;?>",
			clear : "<?php echo Lang::$word->CLEAR;?>",
			delBtn : "<?php echo Lang::$word->DELETE_REC;?>",
			selProject : "<?php echo Lang::$word->INVC_PROJCSELETC;?>",
            delMsg1: "<?php echo Lang::$word->DELCONFIRM;?>",
            delMsg2: "<?php echo Lang::$word->DELCONFIRM1;?>",
            working: "<?php echo Lang::$word->WORKING;?>"
        }
    });
});
// ]]>
</script>

</body>
</html>
