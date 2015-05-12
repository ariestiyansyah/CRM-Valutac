<?php
  /**
   * AlertPay Form
   *
   * @package Digital Downloads Pro
   * @author wojoscripts.com
   * @copyright 2010
   * @version $Id: form.tpl.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php $url = ($row->live) ? 'secure.payza.com/checkout' : 'sandbox.Payza.com/sandbox/payprocess.aspx';?>
<div class="wojo basic button">
  <form action="https://<?php echo $url;?>" method="post" class="xform" id="ap_form" name="ap_form">
    <input type="image" src="gateways/payza/payza_big.png" name="submit" style="vertical-align:middle;border:0;width:171px;margin-right:10px" title="Pay With Payza" alt="" onclick="document.ap_form.submit();"/>
    <span class="wojo info label"><?php echo Lang::$word->AMOUNT;?>: <?php echo $core->formatClientCurrency($amount, $user->currency);?></span>
    <input type="hidden" name="ap_purchasetype" value="item"/>
    <input type="hidden" name="ap_merchant" value="<?php echo $row->extra;?>" />
    <input type="hidden" name="ap_returnurl" value="<?php echo SITEURL;?>/account.php?do=billing" />
    <input type="hidden" name="ap_currency" value="<?php echo ($user->currency ? $user->currency : ($row->extra2 ? $row->extra2 : $core->currency));?>" />
    <input type="hidden" name="apc_1" value="<?php echo $user->uid.'_'.$user->sesid;?>" />
    <input type="hidden" name="ap_itemname" value="Project Invoice - <?php echo cleanOut($row2->ptitle);?>" />
    <input type="hidden" name="ap_itemcode" value="<?php echo $row2->id;?>" />
    <input type="hidden" name="ap_amount" value="<?php echo $amount;?>" />
  </form>
</div>