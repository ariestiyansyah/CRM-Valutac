<?php
  /**
   * Moneybookers Form
   *
   * @package Digital Downloads Pro
   * @author wojoscripts.com
   * @copyright 2010
   * @version $Id: form.tpl.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<div class="wojo basic button">
  <form action="https://www.moneybookers.com/app/payment.pl" method="post" class="xform" id="mb_form" name="mb_form">
    <input type="image" src="gateways/moneybookers/moneybookers_big.png" style="vertical-align:middle;border:0;width:160px;margin-right:10px" name="submit" title="Pay With Moneybookers/Skrill" alt="" onclick="document.mb_form.submit();"/>
    <span class="wojo info label"><?php echo Lang::$word->AMOUNT;?>: <?php echo $core->formatClientCurrency($amount, $user->currency);?></span>
    <input type="hidden" name="pay_to_email" value="<?php echo $row->extra;?>" />
    <input type="hidden" name="recipient_description" value="<?php echo $core->company;?>" />
    <input type="hidden" name="transaction_id" value="<?php echo rand(10,99)?>A<?php echo rand(10,999)?>Z<?php echo rand(10,9999)?>" />
    <input type="hidden" name="language" value="EN" />
    <input type="hidden" name="logo_url" value="<?php echo ($core->logo) ? SITEURL.'/uploads/'.$core->logo : $core->company;?>" />
    <input type="hidden" name="return_url" value="<?php echo SITEURL;?>/estimator.php?id=<?php echo $row2->id;?>&amp;order=ok" />
    <input type="hidden" name="return_url" value="<?php echo SITEURL;?>/estimator.php?id=<?php echo $row2->id;?>" />
    <input type="hidden" name="status_url" value="<?php echo SITEURL.'/gateways/'.$row->dir;?>/eipn.php" />
    <input type="hidden" name="merchant_fields" value="session_id, item, custom" />
    <input type="hidden" name="item" value="Purchase from <?php echo $core->company; ?>" />
    <input type="hidden" name="session_id" value="<?php echo md5(time())?>" />
    <input type="hidden" name="custom" value="<?php echo $user->uid.'_'.$row2->id.'_'.$user->sesid;?>" />
    <input type="hidden" name="amount" value="<?php echo $amount;?>" />
    <input type="hidden" name="currency" value="<?php echo ($user->currency ? $user->currency : ($row->extra2 ? $row->extra2 : $core->currency));?>" />
    <input type="hidden" name="detail1_text" value="<?php echo cleanOut($row2->title);?>" />
    <input type="hidden" name="detail1_description" value="Service Payment:">
  </form>
</div>