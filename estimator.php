<?php
  /**
   * Estimator
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: forms.php, v3.00 2014-07-10 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  require_once(BASEPATH . "lib/class_estimator.php");
  Registry::set('Estimator',new Estimator());
  $estimator = Registry::get("Estimator");
  
  $row = $estimator->getSingleForms();
?>
<?php if(!$row):?>
<?php echo Filter::msgError(Lang::$word->FFORM_ERR);?>
<?php else:?>
<?php switch(Filter::$action): case "checkout": ?>
<div class="wojo tertiary segment">
  <h3 class="wojo header"><?php echo Lang::$word->FEST_CHECKOUT;?></h3>
  <?php $single = $estimator->getFormbBySesid();?>
  <?php if(!$single):?>
  <?php Filter::msgSingleError(Lang::$word->FEST_ORDER_TOTAL_ERR);?>
  <?php else:?>
  <?php echo cleanOut($single->form_data);?> </div>
<?php $gaterow = Registry::get("Estimator")->getGateways($row->gateways);?>
<div class="wojo success notice message"><i class="pin icon"></i> <?php echo Lang::$word->FEST_CHECKOUT_INFO;?></div>
<div class="wojo basic segment">
  <?php foreach ($gaterow as $grow):?>
  <a class="load-gateway" data-id="<?php echo $grow->id;?>"> <img src="gateways/<?php echo $grow->name.'/'.$grow->name.'.png';?>" alt="" class="tooltip" title="<?php echo $grow->displayname;?>"/></a>
  <?php endforeach;?>
</div>
<div id="show-result"></div>
<script type="text/javascript">
// <![CDATA[
$("a.load-gateway").on("click", function () {
  var parent = $(this);
  gdata = 'loadgateway=' + $(this).data('id');
  gdata += '&doservice=1';
  gdata += '&id=<?php echo $row->id;?>';
  gdata += '&amount=' + <?php echo $single->price;?>;
  $.ajax({
	  type: "POST",
	  url: "ajax/user.php",
	  data: gdata,
	  success: function (msg) {
		  $("#show-result").html(msg);
	  }
  });
  return false;
});
// ]]>
</script>
<?php endif;?>
<?php break;?>
<?php default: ?>
<div class="wojo tertiary segment">
  <h3 class="wojo header"><?php echo $row->title;?></h3>
  <div class="wojo message">
    <p><i class="pin icon"></i> <?php echo $row->description . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
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
    <?php if($row->is_service):?>
    <div class="wojo two fluid buttons">
      <div class="wojo warning button"><?php echo Lang::$word->FEST_TPRICE;?>: <?php echo $core->cur_symbol;?><span id="price">0.00</span><?php echo $core->currency;?></div>
      <?php if($row->showhours):?>
      <div class="wojo info button"><?php echo Lang::$word->FEST_TIME;?>: <span id="days">0</span> <?php echo Lang::$word->FEST_DAYS;?></div>
      <?php endif;?>
    </div>
    <p class="wojo note"><?php echo Lang::$word->FEST_CHECKOUT_C;?></p>
    <?php endif;?>
    <input name="totalPrice" type="hidden" id="totalPrice" value="0" />
    <div class="wojo double fitted divider"></div>
    <button type="button" data-url="/ajax/sendestimate.php" name="doestimate" class="wojo button"><?php echo ($row->is_service) ? Lang::$word->CONTINUE : $row->submit_btn;?></button>
    <input name="id" type="hidden" value="<?php echo $row->id;?>" />
    <input name="processForm" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<script type="text/javascript" src="assets/calculator.js"></script> 
<script type="text/javascript">
// <![CDATA[
  $(document).ready(function () {
	  $('body').on('click', 'button[name=doestimate]', function() {
		  posturl = $(this).data('url')

		  function showResponse(json) {
			  if (json.status == "success") {
				  $(".wojo.form").removeClass("loading").slideUp();
				  $("#msgholder").html(json.message);
			  } else if(json.status == "redirect") {
				  window.location.href = SITEURL + '/account.php?do=estimator&id=<?php echo $row->id;?>&action=checkout';
			  } else {
				  $(".wojo.form").removeClass("loading");
				  $("#msgholder").html(json.message);
			  }
		  }

		  function showLoader() {
			  $(".wojo.form").addClass("loading");
		  }
		  var options = {
			  target: "#msgholder",
			  beforeSubmit: showLoader,
			  success: showResponse,
			  type: "post",
			  url: SITEURL + posturl,
			  dataType: 'json'
		  };

		  $('#wojo_form').ajaxForm(options).submit();
	  });
	  
	  <?php if(isset($_GET['order']) and $_GET['order'] == "ok"):?>
	  new Messi("<p class=\"wojo info message\" style=\"width:500px\"><?php echo Lang::$word->FEST_CHECKOUT_DONE;?></p>", {
		  title: "<?php echo Lang::$word->FEST_CCOMPLETE;?>",
		  modal: true,
		  closeButton: true
	  });
	<?php endif;?>
    <?php if($row->is_service):?>
      var priceUpdate = 0;
      $(".wojo.form input[type=checkbox], .wojo.form input[type=radio]").each(function () {
          if ($(this).is(':checked')) {
              var check = ($(this).attr('data-price'));
              priceUpdate += parseFloat(check);
          }
      });
      $("#price").html(priceUpdate.toFixed(2));
	  $("input#totalPrice").val(priceUpdate.toFixed(2));

	  <?php if($row->showhours):?>
	  $("#days").html(Math.round(priceUpdate/<?php echo $row->dpd;?>));
	  <?php endif;?>
      $(".wojo.form").costEstimatr({
          dollarsPerDay: <?php echo $row->dpd;?>
      });
	  <?php endif;?>
  });
// ]]>
</script>
<?php break;?>
<?php endswitch;?>
<?php endif;?>