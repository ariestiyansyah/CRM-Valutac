<?php
  /**
   * Login
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: login.php, v3.00 2014-05-08 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  require_once("init.php");

  if ($user->is_Admin())
      redirect_to("index.php");
	  
	  if (isset($_POST['submit'])) : 
		$result = $user->login($_POST['username'], $_POST['password']);
	  
	  //Login successful 
	  if ($result) : 
		redirect_to("index.php");
	  endif;
  endif;
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $core->company;?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="http://fonts.googleapis.com/css?family=Roboto:400,300,500,700" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Roboto+Condensed:300,400,700" rel="stylesheet" type="text/css">
<link href="assets/css/login.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../assets/jquery.js"></script>
</head>
<body>
<a href="../index.php" class="backto">Back to Front</a> <?php echo ($core->logo) ? '<img src="'.SITEURL.'/uploads/'.$core->logo.'" alt="'.$core->company.'" class="logo"/>': '';?>
<div id="container">
  <header>
    <p><?php echo $core->company;?> - Admin Panel</p>
  </header>
  <form id="admin_form" name="admin_form" method="post" class="loginform">
    <div class="body">
      <div class="row">
        <input placeholder="<?php echo Lang::$word->USERNAME;?>" name="username">
        <div class="divider"></div>
        <input placeholder="**********" type="password" name="password">
      </div>
    </div>
    <button name="submit" class="button"><?php echo Lang::$word->LOGIN;?></button>
  </form>
  <div id="message-login-box"><?php print Filter::$showMsg;?> </div>
</div>
</body>
</html>