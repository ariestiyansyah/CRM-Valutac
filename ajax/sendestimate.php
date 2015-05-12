<?php
  /**
   * Send Estimate
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: sendform.php, v3.00 2014-07-10 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  require_once("../init.php");

  require_once(BASEPATH . "lib/class_estimator.php");
  Registry::set('Estimator',new Estimator());
?>
<?php
  if (isset($_POST['processForm'])):
     Registry::get("Estimator")->sendForm();
  endif;
?>