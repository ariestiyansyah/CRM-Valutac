<?php
  error_reporting(E_ALL);
  define("_VALID_PHP", true);

  $BASEPATH = str_replace("plugin_install.php", "", realpath(__file__));
  define("BASEPATH", $BASEPATH);

  $configFile = BASEPATH . "lib/config.ini.php";
  if (file_exists($configFile)) {
      require_once ($configFile);
  } else {
      exit("Configuration file is missing. Upgrade can not continue");
  }

  require_once (BASEPATH . "lib/class_db.php");
  $db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
  $db->connect();
  $ver = $db->first("SELECT crmv FROM settings");


  /**
   * redirect_to()
   * 
   * @param mixed $location
   * @return
   */
  function redirect_to($location)
  {
      if (!headers_sent()) {
          header('Location: ' . $location);
          exit;
      } else {
          echo '<script type="text/javascript">';
          echo 'window.location.href="' . $location . '";';
          echo '</script>';
          echo '<noscript>';
          echo '<meta http-equiv="refresh" content="0;url=' . $location . '" />';
          echo '</noscript>';
      }
  }
  
  if (isset($_POST['submit'])) {
	  $db->query("  
		  CREATE TABLE IF NOT EXISTS `plug_faq` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`question` varchar(100) DEFAULT NULL,
			`answer` text,
			`user_id` int(11) NOT NULL DEFAULT '0',
			`created` datetime DEFAULT '0000-00-00 00:00:00',
			`sorting` smallint(3) DEFAULT '0',
			PRIMARY KEY (`id`)
		  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
	  ");
	  
      redirect_to("plugin_install.php?install=done");
  }
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>FM Plugin Install</title>
<style type="text/css">
@import url(http://fonts.googleapis.com/css?family=Roboto:400,100,300,700);
body{font-family:Roboto, Arial, Helvetica, sans-serif;font-size:14px;line-height:1.3em;color:#FFF;background-color:#222;font-weight:300;margin:0;padding:0}
#wrap{width:700px;margin-top:150px;margin-right:auto;margin-left:auto;background-color:#208ed3;box-shadow:2px 2px 2px 2px rgba(0,0,0,0.1);border:2px solid #111;border-radius:3px}
header{background-color:#145983;font-size:26px;font-weight:200;padding:35px}
.line{height:2px;background:linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 47%, rgba(255,255,255,0) 100%)}
.line2{position:absolute;left:200px;height:360px;width:2px;background:linear-gradient(to bottom, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 47%, rgba(255,255,255,0) 100%); display: block}
#content{position:relative;padding:45px 20px}
#content .left{float:left;width:200px;height:400px;background-image:url(assets/images/installer.png);background-repeat:no-repeat;background-position:10px center}
#content .right{margin-left:200px}
h4{font-size:18px;font-weight:300;margin:0 0 40px;padding:0}
p.info{background-color:#383838;border-radius:3px;box-shadow:1px 1px 1px 1px rgba(0,0,0,0.1);padding:10px}
p.info span{ display: block; float: left;padding:10px;background:rgba(255,255,255,0.1);margin-left:-10px;margin-top:-10px;border-radius:3px 0 0 3px;margin-right:5px;border-right:1px solid rgba(255,255,255,0.05) }
footer{background-color:#383838;padding:20px}
form{display:inline-block;float:right;margin:0;padding:0}
.button{border:2px solid #222;font-family:Roboto, Arial, Helvetica, sans-serif;font-size:14px;color:#FFF;background-color:#208ED3;text-align:center;cursor:pointer;font-weight:400;-webkit-transition:all .35s ease;-moz-transition:all .35s ease;-o-transition:all .35s ease;transition:all .35s ease;outline:none;margin:0;padding:5px 20px}
.button:hover{background-color:#222;-webkit-transition:all .55s ease;-moz-transition:all .55s ease;-o-transition:all .35s ease;transition:all .55s ease;outline:none}
.clear{font-size:0;line-height:0;clear:both;height:0}
a{text-decoration:none;float:right}
</style>
</head>
<body>
<div id="wrap">
  <header>Welcome to Freelance Manager Plugin Installer</header>
  <div class="line"></div>
  <div id="content">
    <div class="left">
      <div class="line2"></div>
    </div>
    <div class="right">
      <h4>F.A.Q. Manager Install Wizard</h4>
      <?php if(isset($_GET['install']) && $_GET['install'] == "done"):?>
      <p class="info"><span>Success!</span>Installation Completed. Please delete plugin_install.php</p>
      <?php else:?>
      <p class="info"><span>Warning!</span>Please make sure you have performed database backup!!!</p>
      <p style="margin-top:60px">When ready click Install button.</p>
      <p><span>Please be patient, and<strong> DO NOT</strong> Refresh your browser.<br>
        This process might take a while</span>.</p>
      <?php endif;?>
    </div>
  </div>
  <div class="clear"></div>
  <footer> <small>plugin v.1.0 | fm <?php echo $ver->crmv;?></small>
    <?php if(isset($_GET['install']) && $_GET['install'] == "done"):?>
    <a href="admin/index.php" class="button">Back to admin panel</a>
    <?php else:?>
    <form action="#" method="post" name="upgrade_form">
      <input name="submit" type="submit" class="button" value="Install Plugin" id="submit" />
    </form>
    <?php endif;?>
    <div class="clear"></div>
  </footer>
</div>
</body>
</html>