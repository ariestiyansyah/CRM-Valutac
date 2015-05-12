<?php
  /**
   * Paypal Form
   *
   * @package Digital Downloads Pro
   * @author wojoscripts.com
   * @copyright 2010
   * @version $Id: form.tpl.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php $url = ($row->live) ? 'www.paypal.com' : 'www.sandbox.paypal.com';?>
<div class="wojo basic button">
  <form action="https://<?php echo $url;?>/cgi-bin/webscr" class="xform" method="post" id="pp_form" name="pp_form">
    <input type="image" src="gateways/paypal/paypal_big.png" name="submit" style="vertical-align:middle;border:0;width:244px;margin-right:10px" title="Pay With Paypal" alt="" onclick="document.pp_form.submit();"/>
    <span class="wojo info label"><?php echo Lang::$word->AMOUNT;?>: <?php echo $core->formatClientCurrency($amount, $user->currency);?></span>
    <?php if($row2->irecurring == 1):?>
    <input type="hidden" name="cmd" value="_xclick-subscriptions" />
    <input type="hidden" name="a3" value="<?php echo $amount;?>" />
    <input type="hidden" name="p3" value="<?php echo $row2->days;?>" />
    <input type="hidden" name="t3" value="<?php echo $row2->period;?>" />
    <input type="hidden" name="src" value="1" />
    <input type="hidden" name="sr1" value="1" />
    <?php else:?>
    <input type="hidden" name="cmd" value="_xclick"/>
    <input type="hidden" name="amount" value="<?php echo $amount;?>" />
    <?php endif;?>
    <input type="hidden" name="business" value="<?php echo $row->extra;?>" />
    <input type="hidden" name="notify_url" value="<?php echo SITEURL.'/gateways/'.$row->dir;?>/ipn.php" />
    <input type="hidden" name="return" value="<?php echo SITEURL;?>/account.php?do=billing" />
    <input type="hidden" name="currency_code" value="<?php echo ($user->currency ? $user->currency : ($row->extra2 ? $row->extra2 : $core->currency));?>" />
    <input type="hidden" name="custom" value="<?php echo $user->uid.'_'.$user->sesid;?>" />
    <input type="hidden" name="no_note" value="0" />
    <input type="hidden" name="rm" value="2" />
    <input type="hidden" name="item_name" value="Project Invoice - <?php echo cleanOut($row2->ptitle);?>" />
    <input type="hidden" name="item_number" value="<?php echo $row2->id;?>" />
  </form>
</div>
