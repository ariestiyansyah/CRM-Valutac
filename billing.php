<?php
  /**
   * Billing
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: billing.php, v3.00 2014-06-05 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(isset($_GET['msg']) and $_GET['msg'] == 6) Filter::msgOk(Lang::$word->FBILL_PAYOK);
?>
<?php switch(Filter::$action): case "invoice": ?>
<?php $row = $user->getInvoiceById();?>
<?php if(!$row):?>
<?php echo Filter::msgSingleError(Lang::$word->FBILL_ERR);?>
<?php else:?>
<?php $gaterow = $content->getGateways(true);?>
<?php $amount = $row->amount_total - $row->amount_paid;?>
<div class="wojo tertiary segment">
  <h3 class="wojo header"><?php echo Lang::$word->FBILL_SUB . $row->title;;?></h3>
  <div class="wojo basic message"> <i class="pin icon"></i> <?php echo Lang::$word->FBILL_INFO1;?> </div>
  <table class="wojo basic table">
    <tr>
      <td><?php echo Lang::$word->INVC_NAME;?></td>
      <td><?php echo $row->title;?></td>
    </tr>
    <tr>
      <td><?php echo Lang::$word->INVC_INVNUMBER;?></td>
      <td><?php echo $core->invoice_number . $row->id;?></td>
    </tr>
    <tr>
      <td><?php echo Lang::$word->INVC_DUEDATE;?></td>
      <td><?php echo Filter::doDate("short_date", $row->duedate);?></td>
    </tr>
    <tr>
      <td><?php echo Lang::$word->INVC_TOTAL;?></td>
      <td><?php echo $row->amount_total;?></td>
    </tr>
    <?php if($row->recurring):?>
    <tr>
      <td><?php echo Lang::$word->INVC_RECURRING_PER;?></td>
      <td><?php echo $row->days . ' ' .$core->getPeriod($row->period);?></td>
    </tr>
    <?php endif;?>
    <tr>
      <td><?php echo Lang::$word->INVC_PAID;?></td>
      <td><?php echo $row->amount_paid;?></td>
    </tr>
    <tr>
      <td><?php echo $core->tax_name;?></td>
      <td><?php echo $row->tax;?></td>
    </tr>
    <tr>
      <td><?php echo Lang::$word->FBILL_PENDING;?></td>
      <td><span class="invpending wojo label <?php echo ($row->amount_total - $row->amount_paid <> 0) ? 'negative' : 'positive' ;?>"><?php echo $core->formatClientCurrency($row->amount_total - $row->amount_paid, $user->currency);?></span></td>
    </tr>
  </table>
  <?php $credit = getValueById("credit", "users", $user->uid);?>
  <div class="wojo form">
    <form id="wojo_form" name="wojo_form" method="post">
      <?php if($credit <> 0):?>
      <div class="wojo double fitted divider"></div>
      <div class="four fields" id="show-credit">
        <div class="field"> <?php echo Lang::$word->FBILL_CREDIT;?> </div>
        <div class="field">
          <label class="input"> <i class="icon-prepend icon dollar"></i>
            <input type="text" name="amount" id="total_credit" disabled="disabled" readonly value="<?php echo $credit;?>">
          </label>
        </div>
        <div class="field">
          <label class="checkbox">
            <input name="usecredit" type="checkbox" value="1" id="docredit" />
            <i></i><?php echo Lang::$word->FBILL_USE_CREDIT;?></label>
        </div>
        <div class="field"> <span id="show-confirm"></span> </div>
      </div>
      <p class="wojo info"><?php echo Lang::$word->FBILL_CREDIT_T;?></p>
      <?php endif;?>
      <div class="wojo double fitted divider"></div>
      <div class="four fields">
        <div class="field"> <?php echo Lang::$word->FBILL_PAYNOW;?> </div>
        <div class="field">
          <label class="input"> <i class="icon-prepend icon dollar"></i>
            <input type="text" name="amount" id="total_amount" <?php if($row->recurring):?>readonly="readonly"<?php endif;?> value="<?php echo $amount;?>">
          </label>
        </div>
        <div class="field"></div>
        <div class="field"></div>
      </div>
      <div class="wojo double fitted divider"></div>
      <div class="right-space push-left"> <a href="account.php?do=billing" class="wojo basic button"><?php echo Lang::$word->CANCEL;?></a> </div>
      <?php if ($gaterow):?>
      <?php foreach ($gaterow as $grow):?>
      <a class="load-gateway" data-id="<?php echo $grow->id;?>"> <img src="gateways/<?php echo $grow->dir.'/'.$grow->dir.'.png';?>" alt="" data-content="<?php echo $grow->displayname;?>"/></a>
      <?php endforeach;?>
      <?php endif;?>
      <?php if ($core->enable_offline):?>
      <a class="load-gateway" data-id="100"> <img src="assets/images/check.png" alt="" data-content="<?php echo Lang::$word->OFFLINE;?>"/></a>
      <?php endif;?>
    </form>
  </div>
</div>
<div id="show-result"></div>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function () {
    $("#docredit").on("change", function () {
		if($(this).is(":checked")) {
			$("#show-confirm").html("<a class=\"wojo button\" id=\"processCredit\">Confirm</a>");
		} else {
			$("#show-confirm a").remove();
		}
        return false;
    });
	
    $("body").on("click", "#processCredit", function () {
        $.ajax({
            type: "POST",
            url: "ajax/controller.php",
			dataType: 'json',
			data: {
				'docredit': 1,
				'invid' : <?php echo $row->id;?>
			},
            success: function (json) {
			  if (json.type == "success") {
                  if(json.info == "full") {
					  $(".invpaid").html(json.paid);
					  $(".invpending").html("0.00");
					  $("#docredit").val(json.credit);
					  $("#total_amount").val("0.00");
					  $(".gatedata").remove();
					  $("#show-result").html(json.message);
				  } else {
					  $(".invpaid").html(json.paid);
					  $(".invpending").html(json.invpending);
					  $("#show-credit").remove();
					  $("#total_amount").val(json.invpending);
					  $("#show-result").html(json.message);
				  }
				  
			  } else if (json.type == "error") {
				  $("#show-result").html(json.message);
			  }

            }
        });
        return false;
    });
	
    $("a.load-gateway").on("click", function () {
        var parent = $(this);
        gdata = 'loadgateway=' + $(this).data('id');
        gdata += '&invoice_id=<?php echo $row->id;?>';
		gdata += '&amount=' + $("#total_amount").val();
		gdata += '&pamount=<?php echo $amount;?>';
        $.ajax({
            type: "POST",
            url: "ajax/controller.php",
            data: gdata,
            success: function (msg) {
                $("#show-result").html(msg);
            }
        });
        return false;
    });
});
// ]]>
</script>
<?php endif;?>
<?php break;?>
<?php case "viewinvoice": ?>
<?php
  $row = $user->getProjectInvoiceById();
  $invdata = $user->getProjectInvoiceData();
  $paydata = $user->getProjectInvoicePayments();
?>
<?php if(!$row):?>
<?php echo Filter::msgSingleError(Lang::$word->FBILL_ERR);?>
<?php else:?>
<div class="wojo tertiary segment">
  <h3 class="wojo header"><?php echo Lang::$word->FBILL_SUB2 . $row->ptitle;?></h3>
  <table class="wojo basic table">
    <tr>
      <td><?php echo Lang::$word->INVC_NAME;?>:</td>
      <td><?php echo $row->title;?></td>
    </tr>
    <tr>
      <td><?php echo Lang::$word->INVC_INVNUMBER;?>:</td>
      <td><?php echo $core->invoice_number . $row->id;?></td>
    </tr>
    <tr>
      <td><?php echo Lang::$word->INVC_DUEDATE;?>:</td>
      <td><?php echo Filter::doDate("short_date", $row->duedate);?></td>
    </tr>
    <tr>
      <td><?php echo Lang::$word->INVC_TOTAL;?>:</td>
      <td><?php echo $row->amount_total;?></td>
    </tr>
    <tr>
      <td><?php echo Lang::$word->INVC_PAID;?>:</td>
      <td><?php echo $row->amount_paid;?></td>
    </tr>
    <tr>
      <td><?php echo $core->tax_name;?>:</td>
      <td><?php echo $row->tax;?></td>
    </tr>
    <tr>
      <td><?php echo Lang::$word->FBILL_PENDING;?>:</td>
      <td><span class="wojo label <?php echo ($row->amount_total - $row->amount_paid <> 0) ? 'negative' : 'positive' ;?>"><?php echo $core->formatClientCurrency($row->amount_total - $row->amount_paid, $user->currency);?></span></td>
    </tr>
  </table>
</div>
<div class="wojo buttons"> <a href="print_invoice.php?id=<?php echo $row->id;?>" target="_blank" class="wojo warning button"><i class="icon print"></i><?php echo Lang::$word->INVC_PRINT_T;?></a> <a href="ajax/controller.php?dopdf=<?php echo $row->id;?>&amp;title=<?php echo urlencode($row->title);?>" class="wojo danger button"><i class="icon pdf outline"></i><?php echo Lang::$word->INVC_PDF_T;?></a> </div>
<?php if(!$invdata):?>
<?php echo Filter::msgSingleInfo(Lang::$word->INVC_NOENTRY);?>
<?php else:?>
<div class="wojo tertiary segment">
  <h3 class="wojo header"><?php echo Lang::$word->FBILL_SUB3 . $row->ptitle;?></h3>
  <table class="wojo basic table">
    <thead>
      <tr>
        <th><?php echo Lang::$word->BILL_ENTRY;?></th>
        <th><?php echo Lang::$word->DESC;?></th>
        <th><?php echo Lang::$word->AMOUNT;?></th>
        <th><?php echo Lang::$word->TAX;?></th>
      </tr>
    </thead>
    <?php foreach ($invdata as $irow):?>
    <tr>
      <td><?php echo $irow->title;?></td>
      <td><?php echo $irow->description;?></td>
      <td><?php echo $irow->amount;?></td>
      <td><?php echo $irow->tax;?></td>
    </tr>
    <?php endforeach;?>
    <?php unset($irow);?>
  </table>
</div>
<?php endif;?>
<?php if(!$paydata):?>
<?php echo Filter::msgSingleInfo(Lang::$word->INVC_NORECORD);?>
<?php else:?>
<div class="wojo tertiary segment">
  <h3 class="wojo header"><?php echo Lang::$word->FBILL_SUB4 . $row->ptitle;?></h3>
  <table class="wojo basic table">
    <thead>
      <tr>
        <th><?php echo Lang::$word->INVC_RECPAID;?></th>
        <th><?php echo Lang::$word->DESC;?></th>
        <th><?php echo Lang::$word->AMOUNT;?></th>
        <th><?php echo Lang::$word->FDASH_METHOD;?></th>
      </tr>
    </thead>
    <?php foreach ($paydata as $prow):?>
    <tr>
      <td><?php echo Filter::dodate("short_date", $prow->created);?></td>
      <td><?php echo $prow->description;?></td>
      <td><?php echo $prow->amount;?></td>
      <td><?php echo $prow->method;?></td>
    </tr>
    <?php endforeach;?>
    <?php unset($prow);?>
  </table>
</div>
<?php endif;?>
<?php endif;?>
<?php break;?>
<?php default: ?>
<?php $invactive = $user->getClientInvoices("<> 'Paid'");?>
<?php $invarchive = $user->getClientInvoices("='Paid'");?>
<div class="top-space">
  <div class="columns gutters">
    <div class="screen-50 tablet-50 phone-100">
      <div id="chart2" style="height:200px;"></div>
    </div>
    <div class="screen-50 tablet-50 phone-100">
      <div id="chart1" style="height:200px;"></div>
    </div>
  </div>
</div>
<div class="wojo tertiary segment">
  <h3 class="wojo header"><?php echo Lang::$word->FDASH_SUB1;?></h3>
  <div class="wojo basic message"> <i class="pin icon"></i> <?php echo Lang::$word->FBILL_INFO;?> </div>
  <?php if(!$invactive):?>
  <?php echo Filter::msgSingleInfo(Lang::$word->FBILL_NOPENDING);?>
  <?php else:?>
  <table class="wojo basic table">
    <thead>
      <tr>
        <th>#</th>
        <th><?php echo Lang::$word->INVC_NAME;?></th>
        <th><?php echo Lang::$word->INVC_DUEDATE;?></th>
        <th><?php echo Lang::$word->TOTAL;?> / <?php echo Lang::$word->PAID;?></th>
        <th><?php echo Lang::$word->FDASH_METHOD;?></th>
        <th><?php echo Lang::$word->ACTION;?></th>
      </tr>
    </thead>
    <?php foreach ($invactive as $row):?>
    <tr>
      <td><small><?php echo $core->invoice_number . $row->id;?></small></td>
      <td><a href="account.php?do=billing&amp;action=viewinvoice&amp;id=<?php echo $row->id;?>"><?php echo $row->title;?></a></td>
      <td><?php echo Filter::doDate("short_date", $row->duedate);?></td>
      <td><?php echo $row->amount_total;?> / <?php echo $row->amount_paid;?></td>
      <td><?php echo $row->method;?></td>
      <td><a href="account.php?do=billing&amp;action=invoice&amp;id=<?php echo $row->id;?>"><span class="wojo negative label"><?php echo Lang::$word->FDASH_PAY;?></span></a></td>
    </tr>
    <?php endforeach;?>
    <?php unset($row);?>
  </table>
</div>
<?php endif;?>
<?php if(!$invarchive):?>
<?php echo Filter::msgInfo(Lang::$word->FBILL_NOPAIDINV,false);?>
<?php else:?>
<div class="wojo tertiary segment">
  <h3 class="wojo header"><?php echo Lang::$word->FBILL_SUB5;?></h3>
  <table class="wojo basic table">
    <thead>
      <tr>
        <th>#</th>
        <th><?php echo Lang::$word->INVC_NAME;?></th>
        <th><?php echo Lang::$word->INVC_RECPAID;?></th>
        <th><?php echo Lang::$word->TOTAL;?> / <?php echo Lang::$word->PAID;?></th>
        <th><?php echo Lang::$word->FDASH_METHOD;?></th>
        <th><?php echo Lang::$word->ACTION;?></th>
      </tr>
    </thead>
    <?php foreach ($invarchive as $row):?>
    <tr>
      <td><small><?php echo $core->invoice_number . $row->id;?></small></td>
      <td><a href="account.php?do=billing&amp;action=viewinvoice&amp;id=<?php echo $row->id;?>"><?php echo $row->title;?></a></td>
      <td><?php echo Filter::doDate("short_date", $row->duedate);?></td>
      <td><?php echo $row->amount_total;?> / <?php echo $row->amount_paid;?></td>
      <td><?php echo $row->method;?></td>
      <td><a href="print_invoice.php?id=<?php echo $row->id;?>" target="_blank"><span class="wojo positive label"><?php echo Lang::$word->INVC_PRINT_T;?></span></a></td>
    </tr>
    <?php endforeach;?>
    <?php unset($row);?>
  </table>
</div>
<?php endif;?>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.flot.js"></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/flot.resize.js"></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/excanvas.min.js"></script> 
<script type="text/javascript">
  $(document).ready(function() {
      $.ajax({
          type: 'GET',
          url: 'ajax/controller.php?getaInvoices=1',
          dataType: 'json',
          async: false,
          success: function(json) {
              var option = {
                  shadowSize: 0,
                  bars: {
                      show: true,
                      fill: false,
					  lineWidth: 3,
					  align:'center',
					  fillColor: "rgba(230,200,56,0.5)"
                  },
                  points: {
                      show: true
                  },
                  grid: {
                      hoverable: true,
                      clickable: true,
                      borderColor: {
                          top: "rgba(255,255,255,0.2)",
                          left: "rgba(255,255,255,0.2)"
                      }
                  },
                  xaxis: {
                      ticks: json.xaxis,
                      font: {
                          color: "#fff"
                      }
                  },
                  yaxis: {
                      color: "rgba(255,255,255,0.2)",
                      tickDecimals: 2,
                      font: {
                          color: "#fff"
                      }
                  },
				  labelColor: 'rgb(255,255,0)',
                  legend: {
                      backgroundColor: "#FFF",
                      backgroundOpacity: .5
                  },
                  label: {
                      color: "green",
                      backgroundOpacity: .5
                  }
              }
              $.plot($('#chart1'), [json.order], option);
          }
      });
      $.ajax({
          type: 'GET',
          url: 'ajax/controller.php?getpInvoices=1',
          dataType: 'json',
          async: false,
          success: function(json) {
              var option = {
                  shadowSize: 0,
                  lines: {
                      show: true,
                      fill: true,
					  lineWidth: 3,
					  fillColor: "rgba(230,200,56,0.5)"
                  },
                  points: {
                      show: true
                  },
                  grid: {
                      hoverable: true,
                      clickable: true,
                      borderColor: {
                          top: "rgba(255,255,255,0.2)",
                          left: "rgba(255,255,255,0.2)"
                      }
                  },
                  xaxis: {
                      ticks: json.xaxis,
                      font: {
                          color: "#fff"
                      }
                  },
                  yaxis: {
                      color: "rgba(255,255,255,0.2)",
                      tickDecimals: 2,
                      font: {
                          color: "#fff"
                      }
                  },
                  legend: {
                      backgroundColor: "#FFF",
                      backgroundOpacity: .5,
                  }
              }
              $.plot($('#chart2'), [json.order], option);
          }
		  
      });
  });
</script>
<?php break;?>
<?php endswitch;?>