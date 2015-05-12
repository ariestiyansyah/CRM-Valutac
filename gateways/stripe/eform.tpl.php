<?php
  /**
   * Stripe Form
   *
   * @package Membership Manager Pro
   * @author wojoscripts.com, David Souza
   * @copyright 2010
   * @version $Id: form.tpl.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');
?>
<div class="wojo form segment">
  <form method="post" id="stripe_form">
    <div class="field">
      <label>Card Number</label>
      <label class="input"> <i class="icon-append icon asterisk"></i>
        <input type="text" autocomplete="off" name="card-number" placeholder="Card Number">
      </label>
    </div>
    <div class="three fields">
      <div class="field">
        <label>CVC</label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" autocomplete="off" name="card-cvc" placeholder="CVC">
        </label>
      </div>
      <div class="field">
        <label>Expiration Month</label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" autocomplete="off" name="card-expiry-month" placeholder="MM">
        </label>
      </div>
      <div class="field">
        <label>Expiration Year</label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" autocomplete="off" name="card-expiry-year" placeholder="YYYY">
        </label>
      </div>
    </div>
    <button class="wojo positive right labeled icon button" id="dostripe" name="dostripe" type="button"><i class="right arrow icon"></i> Submit Payment</button>
    <input type="hidden" name="amount" value="<?php echo $amount;?>" />
    <input type="hidden" name="item_name" value="Project Invoice - <?php echo cleanOut($row2->title);?>" />
    <input type="hidden" name="item_number" value="<?php echo $row2->id;?>" />
    <input type="hidden" name="currency_code" value="<?php echo ($user->currency ? $user->currency : ($row->extra2 ? $row->extra2 : $core->currency));?>" />
    <input type="hidden" name="processStripePayment" value="1" />
  </form>
</div>
<div id="msgholder"></div>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function () {
    $('#dostripe').on('click', function () {
        var str = $("#stripe_form").serialize();
        $.ajax({
            type: "post",
            dataType: 'json',
            url: "gateways/stripe/eipn.php",
            data: str,
            beforeSend: showLoader(),
            success: function (json) {
                hideLoader();
                if (json.type == "success") {
                    window.location.href = SITEURL + '/estimator.php?id=<?php echo $row2->id;?>&order=ok';
                } else {
                    $("#msgholder").html(json.message);
                }
            }
        });
        return false;
    });
});
// ]]>
</script>
