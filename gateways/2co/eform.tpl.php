<?php
  /**
   * 2CO Form
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2013
   * @version $Id: form.tpl.php, v2.00 2013-06-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php $url = 'https://www.2checkout.com/checkout/spurchase';?>
<div class="wojo basic button">
<form action="<?php echo $url;?>" class="xform" method="post" id="2co_form" name="2co_form">
  <input type="image" src="gateways/2co/2co_big.png" name="submit" style="vertical-align:middle;border:0;width:200px;margin-right:10px" title="Pay With 2Checkout" alt="" onclick="document.2co_form.submit();"/>
  <span class="wojo info label"><?php echo Lang::$word->AMOUNT;?>: <?php echo $core->formatClientCurrency($amount, $user->currency);?></span>
  <input type="hidden" name="sid" value="<?php echo $row->extra;?>"/>
  <input type="hidden" name="total" value="<?php echo $amount;?>" />
  <input type="hidden" name="cart_order_id" value="<?php echo cleanOut($row2->title);?>" />
  <input type="hidden" name="x_receipt_link_url" value="<?php echo SITEURL.'/gateways/'.$row->dir;?>/eipn.php" />
  <input type="hidden" value="1" name="c_prod">
  <input type="hidden" value="Service Order" name="c_name">
  <input type="hidden" value="<?php echo cleanOut($row2->title);?>" name="c_description">
  <input type="hidden" value="<?php echo $amount;?>" name="c_price">
  <input type="hidden" value="1" name="id_type">
  <input type="hidden" value="<?php echo $user->uid . '_' . $row2->id . '_' .$user->sesid;?>" name="custom_form_data">
  <input type="hidden" value="<?php echo ($user->currency ? $user->currency : ($row->extra2 ? $row->extra2 : $core->currency));?>" name="tco_currency">
  <input type="hidden" value="<?php echo $user->name;?>" name="card_holder_name">
  <input type="hidden" value="<?php echo $user->email;?>" name="email">
  <?php if(!$row->live):?>
  <input type="hidden" name="demo" value="Y" />
  <?php endif;?>
</form>
</div>