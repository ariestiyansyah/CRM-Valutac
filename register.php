<?php
  /**
   * Register
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: register.php, v3.00 2014-07-10 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  require_once("init.php");
  
  if(!$core->enable_reg)
      redirect_to("index.php");
	  
  if ($user->logged_in)
      redirect_to("account.php");
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
var SITEURL = "<?php echo SITEURL; ?>";
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
  <div id="rpanel2">
    <div class="inner">
      <div id="login-date"><span><?php echo strftime("%B");?></span> <?php echo strftime("%Y");?></div>
      <?php $newsrow = $user->getLatestNews();?>
      <?php if($newsrow):?>
      <div id="latest-news">
        <div class="inner">
          <?php foreach ($newsrow as $row):?>
          <div class="date"><em><?php echo $row->day;?></em><span class="month"><?php echo strftime("%B", strtotime($row->created));?></span></div>
          <?php echo cleanOut($row->body);?>
          <?php endforeach;?>
          <?php unset($row);?>
        </div>
      </div>
      <?php endif;?>
      <div id="cityloc">
        <div class="wojo icon transparent message"> <i class="icon map marker"></i>
          <div class="content">
            <div class="header"> <?php echo $core->city;?> </div>
            <p><?php echo $core->country;?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="lpanel2">
    <div class="wrapper"> <a href="index.php"><?php echo ($core->logo) ? '<img src="'.SITEURL.'/uploads/'.$core->logo.'" alt="'.$core->company.'" class="logo"/>': $core->company;?></a>
      <h1 class="wojo header"><?php echo Core::sayHello();?></h1>
      <div class="rquote"> <?php print Core::randomQuotes('i');?> </div>
      <div class="wojo segment inverted form">
        <form id="wojo_form" name="wojo_form" method="post">
          <p class="wojo info"><?php echo Lang::$word->REG_INFO;?></p>
          <div class="wojo inverted divider"></div>
          <div class="two fields">
            <div class="field">
              <label class="input"> <i class="icon-prepend icon user"></i> <i class="icon-append icon asterisk"></i>
                <input  type="text" name="username" placeholder="<?php echo Lang::$word->USERNAME;?>">
              </label>
            </div>
            <div class="field">
              <label class="input"> <i class="icon-prepend icon mail"></i> <i class="icon-append icon asterisk"></i>
                <input  type="text" name="email" placeholder="<?php echo Lang::$word->EMAIL;?>">
              </label>
            </div>
          </div>
          <div class="two fields">
            <div class="field">
              <label class="input"> <i class="icon-prepend icon lock"></i> <i class="icon-append icon asterisk"></i>
                <input type="password"  name="pass" placeholder="<?php echo Lang::$word->PASSWORD;?>">
              </label>
            </div>
            <div class="field">
              <label class="input"> <i class="icon-prepend icon lock"></i> <i class="icon-append icon asterisk"></i>
                <input type="password"  name="pass2" placeholder="<?php echo Lang::$word->PASSWORD2;?>">
              </label>
            </div>
          </div>
          <div class="two fields">
            <div class="field">
              <label class="input"> <i class="icon-prepend icon user"></i> <i class="icon-append icon-asterisk"></i>
                <input type="text" name="fname" placeholder="<?php echo Lang::$word->FNAME;?>">
              </label>
            </div>
            <div class="field">
              <label class="input"> <i class="icon-prepend icon user"></i> <i class="icon-append icon-asterisk"></i>
                <input type="text" name="lname" placeholder="<?php echo Lang::$word->LNAME;?>">
              </label>
            </div>
          </div>
          <div class="wojo fitted inverted divider"></div>
          <div class="two fields">
            <div class="field">
              <select name="country">
                <option disabled="" selected="" value="0"><?php echo Lang::$word->COUNTRY;?></option>
                <?php foreach($content->getCountryList() as $country):?>
                <option value="<?php echo $country->id;?>"><?php echo $country->name;?></option>
                <?php endforeach;?>
                <?php unset($country);?>
              </select>
            </div>
            <div class="field">
              <label class="input"> <i class="icon-prepend icon flag"></i>
                <input type="text" name="city" placeholder="<?php echo Lang::$word->CITY;?>">
              </label>
            </div>
          </div>
          <div class="two fields">
            <div class="field">
              <label class="input"> <i class="icon-prepend icon pin"></i>
                <input type="text" name="zip" placeholder="<?php echo Lang::$word->ZIP;?>">
              </label>
            </div>
            <div class="field">
              <label class="input"> <i class="icon-prepend icon building"></i>
                <input type="text" name="company" placeholder="<?php echo Lang::$word->COMPANY;?>">
              </label>
            </div>
          </div>
          <div class="two fields">
            <div class="field">
              <label class="input"> <i class="icon-prepend icon map marker"></i>
                <input type="text" name="address" placeholder="<?php echo Lang::$word->ADDRESS;?>">
              </label>
            </div>
            <div class="field">
              <label class="input"> <i class="icon-prepend icon umbrella"></i>
                <input type="text" name="state" placeholder="<?php echo Lang::$word->STATE;?>">
              </label>
            </div>
          </div>
          <div class="field">
            <button type="button" data-url="/ajax/user.php" name="dosubmit" class="wojo button"><?php echo Lang::$word->REG_SUBMIT;?></button>
            <a href="<?php echo SITEURL;?>/index.php" class="inline left-space"><?php echo Lang::$word->REG_BACK;?></a> </div>
          <input name="doRegister" type="hidden" value="1" />
        </form>
      </div>
      <div id="msgholder"></div>
    </div>
  </div>
</div>
<script type="text/javascript"> 
// <![CDATA[  
$(document).ready(function () {
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