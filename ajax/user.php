<?php
  /**
   * User
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: user.php, v3.00 2014-07-20 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  require_once("../init.php");
?>
<?php
  /* Registration */
  if (isset($_POST['doRegister'])):
      if (intval($_POST['doRegister']) == 0 || empty($_POST['doRegister'])):
          exit;
      endif;
      $user->register();
  endif;
?>
<?php
  /* Password Reset */
  if (isset($_POST['passReset'])):
      if (intval($_POST['passReset']) == 0 || empty($_POST['passReset'])):
          exit;
      endif;
      $user->passReset();
  endif;
?>
<?php
  /* == Load Gateways == */
  if (isset($_POST['loadgateway'])):
	  if (isset($_POST['doservice'])):
          $gate_id = intval($_POST['loadgateway']);
		  $amount = floatval($_POST['amount']);
		  $row = $core->getRowById("gateways", $gate_id, false, false);
		  $row2 = $core->getRowById("estimator", FIlter::$id, false, false);
		  $form_url = BASEPATH . "gateways/" . $row->dir . "/eform.tpl.php";
		  (file_exists($form_url)) ? include ($form_url) : Filter::msgSingleError(Lang::$word->FBILL_ERR2);
	  endif;
 endif;
?>