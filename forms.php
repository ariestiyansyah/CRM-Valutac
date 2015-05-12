<?php
  /**
   * Forms
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: forms.php, v3.00 2014-07-10 10:12:05 gewa Exp $
   */

  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  require_once(BASEPATH . "lib/class_forms.php");
  Registry::set('Forms',new Forms());
  $forms = Registry::get("Forms");
  
  $row = $forms->getSingleForms();
?>
<div class="wojo tertiary segment">
  <?php if(!$row):?>
  <?php echo Filter::msgError(Lang::$word->FFORM_ERR);?>
  <?php else:?>
  <h3 class="wojo header"><?php echo  $row->title;?></h3>
  <div class="wojo message">
    <p><i class="pin icon"></i> <?php echo Lang::$word->FFORM_INFO . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
  </div>
  <div class="wojo half divider"></div>
  <form id="wojo_form" name="wojo_form" class="wojo form" method="post">
    <?php echo cleanOut($row->form_html);?>
    <?php if($row->captcha):?>
    <div class="field">
      <label class="label"><?php echo Lang::$word->CAPTCHA;?></label>
      <div class="inline-group">
        <label class="input"> <img src="<?php echo SITEURL;?>/lib/captcha.php" alt="" class="captcha-append" /> <i class="icon-prepend icon unhide"></i>
          <input name="captcha" placeholder="Captcha" type="text">
        </label>
      </div>
    </div>
    <input name="has_captcha" type="hidden" value="1" />
    <?php endif;?>
    <div class="wojo double fitted divider"></div>
    <button type="button" data-url="/ajax/sendform.php" name="dosubmit" class="wojo button"><?php echo $row->submit_btn;?></button>
    <input name="id" type="hidden" value="<?php echo $row->id;?>" />
    <input name="processForm" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<?php endif;?>