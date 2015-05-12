<?php
  /**
   * Help
   *
   * @package  Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: help.php,v 3.00 2014-01-10 21:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);

  require_once ("../init.php");
  if (!$user->is_Admin())
      exit;
?>
<div id="staff-help">
  <div class="header">
    <p><?php echo Lang::$word->STAFF_HELP;?></p>
    <a class="helper"><i class="icon reorder"></i></a></div>
  <div class="wojo-content" id="help-items">
    <div class="item">
      <h5><?php echo Lang::$word->PASSWORD;?></h5>
      <?php echo Lang::$word->PASSWORD_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->PPEMAIL;?></h5>
      <?php echo Lang::$word->PPEMAIL_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->STAFF_ACCLEVEL;?></h5>
      <?php echo Lang::$word->STAFF_ACCLEVEL_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->STAFF_NOTIFY;?></h5>
      <?php echo Lang::$word->STAFF_NOTIFY_T;?> </div>
  </div>
</div>
<div id="clients-help">
  <div class="header">
    <p><?php echo Lang::$word->STAFF_HELP;?></p>
    <a class="helper"><i class="icon reorder"></i></a></div>
  <div class="wojo-content" id="help-items">
    <div class="item">
      <h5><?php echo Lang::$word->PASSWORD;?></h5>
      <?php echo Lang::$word->PASSWORD_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->PPEMAIL;?></h5>
      <?php echo Lang::$word->PPEMAIL_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->STAFF_ACCLEVEL;?></h5>
      <?php echo Lang::$word->STAFF_ACCLEVEL_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->CONF_CURRENCY;?></h5>
      <?php echo Lang::$word->CLIENT_CURRENCY_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->STAFF_NOTIFY;?></h5>
      <?php echo Lang::$word->STAFF_NOTIFY_T;?> </div>
  </div>
</div>
<div id="config-help">
  <div class="header">
    <p><?php echo Lang::$word->CONF_HELP;?></p>
    <a class="helper"><i class="icon reorder"></i></a></div>
  <div class="wojo-content" id="help-items">
    <div class="item">
      <h5><?php echo Lang::$word->CONF_COMPANY;?></h5>
      <?php echo Lang::$word->CONF_COMPANY_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->CONF_URL;?></h5>
      <?php echo Lang::$word->CONF_URL_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->CONF_DIR;?></h5>
      <?php echo Lang::$word->CONF_DIR_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->CONF_EMAIL;?></h5>
      <?php echo Lang::$word->CONF_EMAIL_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->CONF_INVNUMBER;?></h5>
      <?php echo Lang::$word->CONF_INVNUMBER_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->CONF_QTYNUMBER;?></h5>
      <?php echo Lang::$word->CONF_QTYNUMBER_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->CONF_DELLOGO;?></h5>
      <?php echo Lang::$word->CONF_DELLOGO_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->CONF_PLOGO;?></h5>
      <?php echo Lang::$word->CONF_PLOGO_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->CONF_LOCALES;?></h5>
      <?php echo Lang::$word->CONF_LOCALES_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->CONF_REGYES;?></h5>
      <?php echo Lang::$word->CONF_REGYES_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->CONF_TAXNO;?></h5>
      <?php echo Lang::$word->CONF_TAXNO_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->CONF_UNVSFN;?></h5>
      <?php echo Lang::$word->CONF_UNVSFN_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->CONF_AFT;?></h5>
      <?php echo Lang::$word->CONF_AFT_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->CONF_MFS;?></h5>
      <?php echo Lang::$word->CONF_MFS_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->CONF_IPP;?></h5>
      <?php echo Lang::$word->CONF_IPP_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->CONF_CURRENCY;?></h5>
      <?php echo Lang::$word->CONF_CURRENCY_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->CONF_CURSYMBOL;?></h5>
      <?php echo Lang::$word->CONF_CURSYMBOL_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->CONF_INVREMINDER;?></h5>
      <?php echo Lang::$word->CONF_INVREMINDER_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->CONF_MFS;?></h5>
      <?php echo Lang::$word->CONF_MAILER_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->CONF_SMTP_HOST;?></h5>
      <?php echo Lang::$word->CONF_SMTP_HOST_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->CONF_SMTP_SSL;?></h5>
      <?php echo Lang::$word->CONF_SMTP_SSL_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->CONF_SMTP_PORT;?></h5>
      <?php echo Lang::$word->CONF_SMTP_PORT_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->CONF_SMAILPATH;?></h5>
      <?php echo Lang::$word->CONF_SMAILPATH_T;?> </div>
  </div>
</div>
<div id="gateway-help">
  <div class="header">
    <p><?php echo Lang::$word->GATE_HELP;?></p>
    <a class="helper"><i class="icon reorder"></i></a></div>
  <div class="wojo-content" id="help-items">
    <div class="item">
      <h5><?php echo Lang::$word->GATE_NAME;?></h5>
      <?php echo Lang::$word->GATE_NAME_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->GATE_LIVE;?></h5>
      <?php echo Lang::$word->GATE_LIVE_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->GATE_ACTIVE;?></h5>
      <?php echo Lang::$word->GATE_ACTIVE_T;?> </div>
  </div>
</div>
<div id="estimator-help">
  <div class="header">
    <p><?php echo Lang::$word->ESTM_HELP;?></p>
    <a class="helper"><i class="icon reorder"></i></a></div>
  <div class="wojo-content" id="help-items">
    <div class="item">
      <h5><?php echo Lang::$word->ESTM_DPD;?></h5>
      <?php echo Lang::$word->ESTM_DPD_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->ESTM_ORDER;?></h5>
      <?php echo Lang::$word->ESTM_ORDER_T;?> </div>
  </div>
</div>
<div id="news-help">
  <div class="header">
    <p><?php echo Lang::$word->NEWS_HELP;?></p>
    <a class="helper"><i class="icon reorder"></i></a></div>
  <div class="wojo-content" id="help-items">
    <div class="item">
      <h5><?php echo Lang::$word->NEWS_START;?></h5>
      <?php echo Lang::$word->NEWS_START_T;?> </div>
  </div>
</div>
<div id="invoices-help">
  <div class="header">
    <p><?php echo Lang::$word->INVC_HELP;?></p>
    <a class="helper"><i class="icon reorder"></i></a></div>
  <div class="wojo-content" id="help-items">
    <div class="item">
      <h5><?php echo Lang::$word->INVC_ONHOLD;?></h5>
      <?php echo Lang::$word->INVC_ONHOLD_T;?> </div>
    <div class="item">
      <h5><?php echo Lang::$word->INVC_RECURRING_PER;?></h5>
      <?php echo Lang::$word->INVC_RECURRING_PER_T;?> </div>
  </div>
</div>
<div id="submissions-help">
  <div class="header">
    <p><?php echo Lang::$word->SUBS_HELP;?></p>
    <a class="helper"><i class="icon reorder"></i></a></div>
  <div class="wojo-content" id="help-items">
    <div class="item">
      <h5><?php echo Lang::$word->SUBS_SENDREVIEW;?></h5>
      <?php echo Lang::$word->SUBS_SENDREVIEW_T;?> </div>
  </div>
</div>
<div id="language-help">
  <div class="header">
    <p><?php echo Lang::$word->LM_HELP;?></p>
    <a class="helper"><i class="icon reorder"></i></a></div>
  <div class="wojo-content" id="help-items">
    <div class="item">
      <h5><?php echo Lang::$word->LM_ADDNEW;?></h5>
      <?php echo Lang::$word->LM_ADDNEW_T;?> </div>
  </div>
</div>
