<?php

  define("_VALID_PHP", true);

  $BASEPATH = str_replace("upgrade.php", "", realpath(__file__));
  define("BASEPATH", $BASEPATH);

  $configFile = BASEPATH . "lib/config.ini.php";
  if (file_exists($configFile)) {
      require_once ($configFile);
  } else {
      exit("Configuration file is missing. Upgrade can not continue");
  }
  
  $script_path = dirname($_SERVER['SCRIPT_NAME']);

  require_once (BASEPATH . "lib/class_registry.php");

  require_once (BASEPATH . "lib/class_db.php");
  Registry::set('Database', new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE));
  $db = Registry::get("Database");
  $db->connect();
  
  require_once(BASEPATH . "lib/functions.php");
  require_once(BASEPATH . "lib/class_filter.php");

  class Core
  {

      public static $language;
	  
      public function __construct()
      {
          $this->getSettings();

      }

      private function getSettings()
      {
          $sql = "SELECT * FROM settings";
          $row = Registry::get("Database")->first($sql);

          $this->currency = $row->currency;
          $this->cur_symbol = $row->cur_symbol;
		  $this->tsep = $row->tsep;
		  $this->dsep = $row->dsep;
		  $this->lang  = $row->lang;

      }
	  
      public function formatMoney($amount)
      {
		  return $this->cur_symbol . number_format($amount, 2, $this->dsep, $this->tsep) . $this->currency;
      }

  }
  
  Registry::set('Core',new Core());
  
  require_once(BASEPATH . "lib/class_language.php");
  Registry::set('Lang',new Lang());

  require_once(BASEPATH . "lib/class_content.php");
  Registry::set('Content',new Content());
  
  $ver = $db->first("SELECT crmv FROM settings");


  
  if (isset($_POST['submit'])) {
      $db->query("
		  ALTER TABLE `custom_fields` 
			  ADD COLUMN `req` tinyint(1)   NOT NULL DEFAULT 0 after `title` , 
			  ADD COLUMN `tooltip` varchar(100)  COLLATE utf8_general_ci NULL after `req` , 
			  DROP COLUMN `fsize`;
	  ");

      $db->query("
		  ALTER TABLE `settings` 
			  ADD COLUMN `site_dir` varchar(50)  COLLATE utf8_general_ci NULL after `site_url` , 
			  ADD COLUMN `country` varchar(100)  COLLATE utf8_general_ci NULL after `zip` , 
			  ADD COLUMN `locale` varchar(100)  COLLATE utf8_general_ci NULL after `weekstart` , 
			  ADD COLUMN `unvsfn` tinyint(1)   NOT NULL DEFAULT 1 after `theme` , 
			  ADD COLUMN `pagesize` varchar(10)  COLLATE utf8_general_ci NOT NULL DEFAULT 'LETTER' after `invdays`;
	  ");

      $db->query("
		  ALTER TABLE `support_tickets` 
			  ADD COLUMN `project_id` int(11)   NOT NULL DEFAULT 0 after `client_id`;
	  ");


      $db->query("
		  INSERT INTO `gateways` (`name`, `displayname`, `dir`, `live`, `extra_txt`, `extra_txt2`, `extra_txt3`, `extra`, `extra2`, `extra3`, `info`, `active`) VALUES
		  ('payfast', 'PayFast', 'payfast', 0, 'Merchant ID', 'Merchant Key', 'PassPhrase', '10000100', '46f0cd694581a', '', '&lt;p&gt;&lt;a href=&quot;https://www.payfast.co.za/&quot; title=&quot;https://www.payfast.co.za/&quot; rel=&quot;nofollow&quot;&gt;Click here to setup an account with PayFast&lt;/a&gt;&lt;/p&gt;\r\n&lt;p&gt;&lt;strong&gt;Gateway Name&lt;/strong&gt; - Enter the name of the payment provider here.&lt;/p&gt;\r\n&lt;p&gt;&lt;strong&gt;Active&lt;/strong&gt; - Select Yes to make this payment provider active. &lt;br&gt;\r\n&lt;p&gt;&lt;strong&gt;PassPhrase&lt;/strong&gt; - ONLY INSERT A VALUE INTO THE SECURE PASSPHRASE IF YOU HAVE SET THIS ON THE INTEGRATION PAGE OF THE LOGGED IN AREA OF THE PAYFAST WEBSITE!!!!!.&lt;/p&gt;\r\nIf this box is not checked, the payment provider will not show up in the payment provider list during checkout.\r\n&lt;/p&gt;\r\n&lt;p&gt;&lt;strong&gt;Set Live Mode&lt;/strong&gt; - To test PayFast, use your test keys instead of live ones.&lt;/p&gt;\r\n&lt;p&gt;&lt;strong&gt;API Keys&lt;/strong&gt; - To obtain your API Keys:&lt;/p&gt;\r\n&lt;ul&gt;\r\n  &lt;li&gt; 1. Log into the Dashboard at &lt;a href=&quot;https://www.payfast.co.za/user/login&quot; target=&quot;_blank&quot;&gt;https://www.payfast.co.za/user/login&lt;/a&gt;&lt;/li&gt;\r\n  &lt;li&gt; 2. Select Account Settings under Your Account in the main menu on the left &lt;/li&gt;\r\n  &lt;li&gt; 3. Click API Keys&lt;/li&gt;\r\n  &lt;li&gt; 4. Your keys will be displayed &lt;/li&gt;\r\n  &lt;p&gt;You should use test keys first to verify, that everything is working smoothly, before going live.&lt;/p&gt;\r\n  &lt;p&gt;&lt;strong&gt;IPN Url&lt;/strong&gt; - This option it''s not being used.&lt;/p&gt;\r\n&lt;/ul&gt;', 1);
	  ");

      require_once (BASEPATH . "lib/class_estimator.php");
      Registry::set('Estimator', new Estimator());

      $efields = Registry::get('Estimator')->getAllFields();
      if ($efields) {
          foreach ($efields as $row) {
              Registry::get('Estimator')->processEstimatorFieldTemp($row);
          }

          unset($row);
      }

      $eformrows = Registry::get('Estimator')->getForms();
      if ($eformrows) {
          foreach ($eformrows as $row) {
              $edata['form_data'] = "NULL";
              $db->update(Estimator::mTable, $edata);
          }
          unset($row);
      }

      require_once (BASEPATH . "lib/class_forms.php");
      Registry::set('Forms', new Forms());

      $fields = Registry::get('Forms')->getAllFields();
      if ($fields) {
          foreach ($fields as $row) {
              Registry::get('Forms')->processFormsFieldTemp($row);
          }

          unset($row);
      }
	  
	  $formrows = Registry::get('Forms')->getForms();
      if ($formrows) {
          foreach ($formrows as $row) {
              $fdata['form_data'] = "NULL";
              $db->update(Forms::mTable, $fdata);
          }
          unset($row);
      }
	  
      $setdata['site_dir'] = str_replace("/", "", $script_path);
	  $setdata['pagesize'] = 'LETTER';
	  $setdata['crmv'] = '3.00';
      $db->update("settings", $setdata);

      redirect_to("upgrade.php?update=done");
  }
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>FM Upgrade</title>
<style type="text/css">
@import url(http://fonts.googleapis.com/css?family=Raleway:400,100,300,600,700);
body {
  font-family: Raleway, Arial, Helvetica, sans-serif;
  font-size: 14px;
  line-height: 1.3em;
  color: #FFF;
  background-color: #222;
  font-weight: 300;
  margin: 0;
  padding: 0
}
#wrap {
  width: 800px;
  margin-top: 150px;
  margin-right: auto;
  margin-left: auto;
  background-color: #862b27;
  box-shadow: 2px 2px 2px 2px rgba(0,0,0,0.1);
  border: 2px solid #270d0c;
  border-radius: 3px
}
header {
  background-color: #5e1f1c;
  font-size: 26px;
  font-weight: 200;
  padding: 35px
}
.line {
  height: 2px;
  background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.5) 50%, rgba(255,255,255,0) 100%)
}
.line2 {
  position: absolute;
  left: 200px;
  height: 360px;
  width: 2px;
  background: linear-gradient(to bottom, rgba(255,255,255,0) 0%, rgba(255,255,255,0.5) 50%, rgba(255,255,255,0) 100%);
  display: block
}
#content {
  position: relative;
  padding: 45px 20px
}
#content .left {
  float: left;
  width: 200px;
  height: 400px;
  background-image: url(assets/images/installer.png);
  background-repeat: no-repeat;
  background-position: 10px center
}
#content .right {
  margin-left: 200px
}
h4 {
  font-size: 18px;
  font-weight: 300;
  margin: 0 0 40px;
  padding: 0
}
p.info {
  background-color: #270d0c;
  border-radius: 3px;
  box-shadow: 1px 1px 1px 1px rgba(0,0,0,0.1);
  padding: 10px
}
p.info span {
  display: block;
  float: left;
  padding: 10px;
  background: rgba(255,255,255,0.1);
  margin-left: -10px;
  margin-top: -10px;
  border-radius: 3px 0 0 3px;
  margin-right: 5px;
  border-right: 1px solid rgba(255,255,255,0.05)
}
footer {
  background-color: #270d0c;
  padding: 20px
}
form {
  display: inline-block;
  float: right;
  margin: 0;
  padding: 0
}
.button {
  border: 2px solid #9f322d;
  font-family: Raleway, Arial, Helvetica, sans-serif;
  font-size: 14px;
  color: #FFF;
  background-color: #270d0c;
  text-align: center;
  cursor: pointer;
  font-weight: 500;
  -webkit-transition: all .35s ease;
  -moz-transition: all .35s ease;
  -o-transition: all .35s ease;
  transition: all .35s ease;
  outline: none;
  border-radius: 2px;
  margin: 0;
  padding: 5px 20px
}
.button:hover {
  background-color: #9f322d;
  -webkit-transition: all .55s ease;
  -moz-transition: all .55s ease;
  -o-transition: all .35s ease;
  transition: all .55s ease;
  outline: none
}
.clear {
  font-size: 0;
  line-height: 0;
  clear: both;
  height: 0
}
.clearfix:after {
  content: ".";
  display: block;
  height: 0;
  clear: both;
  visibility: hidden;
}
a {
  text-decoration: none;
  float: right
}
</style>
</head>
<body>
<div id="wrap">
  <header>Welcome to FM Upgrade Wizard</header>
  <div class="line"></div>
  <div id="content">
    <div class="left">
      <div class="line2"></div>
    </div>
    <div class="right">
      <h4>Freelance Manager Upgrade v3.00</h4>
      <?php if(isset($_GET['update']) && $_GET['update'] == "done"):?> 
     <p class="info"><span>Success!</span>Installation Completed. Please delete upgrade.php</p>
      <?php else:?>
      <?php if($ver->crmv != 2.02):?>
      <p class="info"><span>Warning!</span>You need at least FM v2.02 in order to continue.</p>
      <?php else:?>
      <p class="info"><span>Warning!</span>Please make sure you have performed database backup!!!</p>
      <p style="margin-top:60px">When ready click Install button.</p>
      <p><span>Please be patient, and<strong> DO NOT</strong> Refresh your browser.<br>
        This process might take a while</span>.</p>
      <?php endif;?>
      <?php endif;?>
    </div>
  </div>
  <div class="clear"></div>
  <footer class="clearfix"> <small>current <b>fm v.<?php echo $ver->crmv;?></b></small>
    <?php if(isset($_GET['update']) && $_GET['update'] == "done"):?>
    <a href="admin/index.php" class="button">Back to admin panel</a>
    <?php else:?>
    <form method="post" name="upgrade_form">
      <?php if($ver->crmv == 2.02):?>
      <input name="submit" type="submit" class="button" value="Upgrade FM" id="submit" />
      <?php endif;?>
    </form>
    <?php endif;?>
  </footer>
</div>
</body>
</html>