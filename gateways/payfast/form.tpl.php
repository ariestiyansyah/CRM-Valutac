<?php
  /**
   * PayFast Form
   *
   * @package Digital Downloads Pro
   * @author wojoscripts.com
   * @copyright 2010
   * @version $Id: form.tpl.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php $url = ($row->live) ? 'www.payfast.co.za' : 'sandbox.payfast.co.za';?>
<div class="wojo basic button">
  <form action="https://<?php echo $url;?>/eng/process" class="xform" method="post" id="pf_form" name="pf_form">
    <input type="image" src="gateways/payfast/payfast_big.png" name="submit" style="vertical-align:middle;border:0;width:209px;margin-right:10px" title="Pay With PayFast" alt="" onclick="document.pp_form.submit();"/><span class="wojo info label"><?php echo Lang::$word->AMOUNT;?>: <?php echo $core->formatClientCurrency($amount, $user->currency);?></span>
<?php
  $html = '';
  $string = '';
  
  $array = array(
      'merchant_id' => $row->extra,
      'merchant_key' => $row->extra2,
      'return_url' => SITEURL . '/account.php?do=billing',
      'cancel_url' => SITEURL . '/account.php?do=billing',
      'notify_url' => SITEURL . '/gateways/' . $row->dir . '/ipn.php',
      'name_first' => $user->fname,
      'name_last' => $user->lname,
      'email_address' => $user->email,
      'm_payment_id' => $row2->id,
      'amount' => $amount,
      'item_name' => 'Project Invoice - ' . cleanOut($row2->ptitle),
	  'item_description' => 'Payment for - ' . cleanOut($row2->ptitle),
      'custom_int1' => $user->uid,
      'custom_str1' => $user->sesid

      );

  foreach ($array as $k => $v) {
      $html .= '<input type="hidden" name="' . $k . '" value="' . $v . '" />';
      $string .= $k . '=' . urlencode($v) . '&';
  }
  $string = substr($string, 0, -1);
  $sig = md5($string);
  $html .= '<input type="hidden" name="signature" value="' . $sig . '" />';

  print $html;
?>
  </form>
</div>