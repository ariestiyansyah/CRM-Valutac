<?php
  /**
   * Invoices
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: invoices.php, v3.00 2014-05-08 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Filter::$action): case "edit": ?>
<?php if($user->userlevel == 5): print Filter::msgInfo(Lang::$word->ADMINONLY, false); return; endif;?>
<?php $row = $content->getProjectInvoiceById();?>
<?php $invdata = $content->getProjectInvoiceData();?>
<?php $paydata = $content->getProjectInvoicePayments();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->INVC_INFO . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?> </div>
</div>
<?php if(!$row):?>
<?php echo Filter::msgError( Lang::$word->INVC_ERR, false);?>
<?php else:?>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->INVC_SUB . $row->ptitle;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->INVC_NAME;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="title" value="<?php echo $row->title;?>" placeholder="<?php echo Lang::$word->INVC_NAME;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->INVC_CNAME;?></label>
        <label class="input">
          <input type="text" name="name" value="<?php echo Core::renderName($row);?>" readonly disabled="disabled" placeholder="<?php echo Lang::$word->INVC_CNAME;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->INVC_CEMAIL;?></label>
        <label class="input">
          <input type="text" name="email" value="<?php echo $row->email;?>" readonly disabled="disabled" placeholder="<?php echo Lang::$word->INVC_CEMAIL;?>">
        </label>
      </div>
    </div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->INVC_TOTAL;?></label>
        <label class="input"><i class="icon-append icon dollar"></i>
          <input type="text" name="amount_total" value="<?php echo $row->amount_total;?>" readonly placeholder="<?php echo Lang::$word->INVC_TOTAL;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->INVC_PAID;?></label>
        <label class="input"><i class="icon-append icon dollar"></i>
          <input type="text" name="amount_paid" value="<?php echo $row->amount_paid;?>" readonly placeholder="<?php echo Lang::$word->INVC_PAID;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->FBILL_PENDING;?></label>
        <label class="input"><i class="icon-append icon dollar"></i>
          <input type="text" name="amount_due" value="<?php echo number_format($row->amount_total - $row->amount_paid,2);?>" readonly disabled="disabled" placeholder="<?php echo Lang::$word->FBILL_PENDING;?>">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->INVC_DUEDATE;?></label>
        <label class="input">
          <input type="text" data-datepicker="true" name="duedate" data-value="<?php echo $row->duedate;?>" value="<?php echo $row->duedate;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo $core->tax_name;?></label>
        <label class="input">
          <input type="text" name="tax" value="<?php echo $row->tax;?>" readonly disabled="disabled" placeholder="<?php echo $core->tax_name;?>">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->PAYMETHOD;?></label>
        <select name="method">
          <option value="Offline"<?php if($row->method == 'Offline') echo ' selected="selected"';?>><?php echo Lang::$word->OFFLINE;?></option>
          <?php foreach ($content->getGateways() as $grow):?>
          <option value="<?php echo $grow->displayname;?>"<?php if($row->method == $grow->displayname) echo ' selected="selected"';?>><?php echo $grow->displayname;?></option>
          <?php endforeach;?>
          <?php unset($grow);?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->STATUS;?></label>
        <select name="status">
          <?php echo $content->invoiceStatusList($row->status);?>
        </select>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <?php if($row->status == "Unpaid"):?>
        <label><?php echo Lang::$word->INVC_ONHOLD;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" value="1" name="onhold" <?php getChecked($row->onhold, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" value="0" name="onhold" <?php getChecked($row->onhold, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
        <?php endif;?>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->INVC_INVNUMBER;?></label>
        <label class="input">
          <input type="text" name="invoice_number" value="<?php echo $core->invoice_number . $row->id;?>" readonly disabled="disabled" placeholder="<?php echo Lang::$word->INVC_INVNUMBER;?>">
        </label>
      </div>
    </div>
    <label><?php echo Lang::$word->ACTIONS;?></label>
    <div class="wojo divider"></div>
    <div class="field"> <a class="sendinvoice wojo info button" data-id="<?php echo $row->id;?>"><i class="icon reply mail all"></i><?php echo Lang::$word->INVC_EMAIL_T;?></a> <a href="print_invoice.php?id=<?php echo Filter::$id;?>" target="_blank" class="wojo warning button"><i class="icon print"></i><?php echo Lang::$word->INVC_PRINT_T;?></a> <a href="controller.php?dopdf=<?php echo $row->id;?>&amp;title=<?php echo urlencode($row->title);?>" class="wojo danger button"><i class="icon pdf outline"></i><?php echo Lang::$word->INVC_PDF_T;?></a> </div>
    <div class="wojo divider"></div>
    <div class="wojo header"><?php echo Lang::$word->INVC_NOTES;?></div>
    <div class="field">
      <label class="label"><?php echo Lang::$word->INVC_NOTE;?></label>
      <textarea class="altpost" name="notes"><?php echo ($row->notes) ? $row->notes : $core->invoice_note;?></textarea>
      <p class="wojo success"><?php echo Lang::$word->INVC_NOTE_T;?></p>
    </div>
    <div class="field">
      <label class="label"><?php echo Lang::$word->INVC_COMMENT;?></label>
      <textarea class="altpost" name="comment"><?php echo $row->comment;?></textarea>
      <p class="wojo success"><?php echo Lang::$word->INVC_COMMENT_T;?></p>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->INVC_UPDATE;?></button>
    <a href="index.php?do=invoices&amp;id=<?php echo $row->project_id;?>" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="updateInvoice" type="hidden" value="1">
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
  </form>
</div>
<div id="msgholder"></div>
<div class="wojo basic block segment"> <a class="wojo basic button push-right" onclick="$('#newentry').slideToggle();"><i class="icon add"></i> <?php echo Lang::$word->INVC_ADDENTRY;?></a>
  <h2 class="wojo left floated header"><i class="reorder icon"></i><?php echo Lang::$word->INVC_SUBENTRY;?></h2>
</div>
<div id="invoice-entries">
  <?php if(!$invdata):?>
  <div class="norecord"><?php echo Filter::msgSingleInfo(Lang::$word->INVC_NOENTRY);?></div>
  <?php endif;?>
  <table class="wojo basic four cols table">
    <thead>
      <tr>
        <th><?php echo Lang::$word->INVC_ENTRYTITLE;?></th>
        <th><?php echo Lang::$word->DESC;?></th>
        <th><?php echo Lang::$word->AMOUNT;?></th>
        <th><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if($invdata):?>
      <?php foreach ($invdata as $irow):?>
      <tr>
        <td><?php echo $irow->title;?></td>
        <td><?php echo $irow->description;?></td>
        <td><?php echo $irow->amount;?></td>
        <td><a href="index.php?do=invoices&amp;action=editentry&amp;pid=<?php echo $irow->project_id;?>&amp;id=<?php echo $irow->id;?>"><i class="rounded inverted success icon pencil link"></i></a> <a class="delete" data-title="<?php echo Lang::$word->INVC_DELENTRY;?>" data-extra="<?php echo $irow->project_id.':'.$irow->invoice_id;?>" data-option="deleteInvoiceEntry" data-id="<?php echo $irow->id;?>" data-name="<?php echo $irow->title;?>"><i class="rounded danger inverted remove icon link"></i></a></td>
      </tr>
      <?php endforeach;?>
      <?php unset($irow);?>
      <?php endif;?>
    </tbody>
  </table>
</div>
<div id="newentry" class="wojo segment" style="display:none">
  <div class="wojo header"><?php echo Lang::$word->INVC_SUBENTRY1;?></div>
  <form class="wojo small form" id="entry_form" method="post">
    <div class="three fields">
      <div class="field">
        <label class="label"><?php echo Lang::$word->INVC_ENTRYTITLE;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="etitle" placeholder="<?php echo Lang::$word->INVC_ENTRYTITLE;?>">
        </label>
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->AMOUNT;?></label>
        <label class="input"> <i class="icon-prepend icon asterisk"></i> <i class="icon-append icon dollar"></i>
          <input type="text" name="eamount" placeholder="<?php echo Lang::$word->AMOUNT;?>">
        </label>
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->DESC;?></label>
        <label class="input">
          <input type="text" name="edesc" placeholder="<?php echo Lang::$word->DESC;?>">
        </label>
      </div>
    </div>
    <div class="three fields">
      <div class="field">
        <label class="label"><?php echo Lang::$word->TAXABLE;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="etax" value="1" >
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="etax" value="0" checked="checked">
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label class="label">&nbsp;</label>
        <button class="wojo black button" id="doentry" type="button"><?php echo Lang::$word->INVC_ADDENTRY;?></button>
      </div>
    </div>
  </form>
  <div class="emsg"></div>
</div>
<div class="wojo basic block segment"> <a class="wojo basic button push-right" onclick="$('#newrecord').slideToggle();"><i class="icon add"></i> <?php echo Lang::$word->INVC_ADDRECORD;?></a>
  <h2 class="wojo left floated header"><i class="reorder icon"></i><?php echo Lang::$word->INVC_SUBRECORD;?></h2>
</div>
<div id="invoice-records">
  <?php if(!$paydata):?>
  <div class="norecord"><?php echo Filter::msgSingleInfo(Lang::$word->INVC_NORECORD);?></div>
  <?php endif;?>
  <table class="wojo basic four cols table">
    <thead>
      <tr>
        <th><?php echo Lang::$word->INVC_RECPAID;?></th>
        <th><?php echo Lang::$word->DESC;?></th>
        <th><?php echo Lang::$word->AMOUNT;?></th>
        <th><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if($paydata):?>
      <?php foreach ($paydata as $prow):?>
      <tr>
        <td><?php echo Filter::dodate("short_date", $prow->created);?></td>
        <td><?php echo $prow->description;?></td>
        <td><?php echo $prow->amount;?></td>
        <td><a href="index.php?do=invoices&amp;action=editrecord&amp;pid=<?php echo $prow->project_id;?>&amp;id=<?php echo $prow->id;?>"><i class="rounded inverted success icon pencil link"></i></a> <a class="delete" data-title="<?php echo Lang::$word->INVC_DELRECORD;?>" data-extra="<?php echo $prow->project_id.':'.$prow->invoice_id;?>" data-option="deleteInvoiceRecord" data-id="<?php echo $prow->id;?>" data-name="<?php echo $row->title;?>"><i class="rounded danger inverted remove icon link"></i></a></td>
      </tr>
      <?php endforeach;?>
      <?php unset($prow);?>
      <?php endif;?>
    </tbody>
  </table>
</div>
<div id="newrecord" class="wojo segment" style="display:none">
  <div class="wojo header"><?php echo Lang::$word->INVC_SUBRECORD1;?></div>
  <form class="wojo small form" id="record_form" method="post">
    <div class="three fields">
      <div class="field">
        <label class="label"><?php echo Lang::$word->INVC_DUEDATE;?></label>
        <label class="input"> <i class="icon-append icon calendar"></i>
          <input type="text" data-datepicker="true" name="rcreated" data-value="<?php echo date('Y-m-d');?>" value="<?php echo date('Y-m-d');?>">
        </label>
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->AMOUNT;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i> <i class="icon-prepend icon dollar"></i>
          <input type="text" name="ramount" placeholder="<?php echo Lang::$word->AMOUNT;?>">
        </label>
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->DESC;?></label>
        <label class="input">
          <input type="text" name="rdesc" placeholder="<?php echo Lang::$word->DESC;?>">
        </label>
      </div>
    </div>
    <div class="three fields">
      <div class="field">
        <label class="label"><?php echo Lang::$word->PAYMETHOD;?></label>
        <select name="method">
          <option value="Offline"><?php echo Lang::$word->OFFLINE;?></option>
          <?php foreach ($content->getGateways() as $grow):?>
          <option value="<?php echo $grow->displayname;?>"><?php echo $grow->displayname;?></option>
          <?php endforeach;?>
          <?php unset($grow);?>
        </select>
      </div>
      <div class="field">
        <label class="label">&nbsp;</label>
        <button class="wojo black button" id="dorecord" type="button"><?php echo Lang::$word->INVC_ADDRECORD;?></button>
      </div>
    </div>
  </form>
  <div class="rmsg"></div>
</div>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function () {
    // Add Entry
	$('#doentry').on('click', function () {
		$but = $(this);
		$but.addClass('loading');
        var str = $("#entry_form").serialize();
        str += '&processInvoiceEntry=1';
        str += '&invoice_id=<?php echo Filter::$id;?>';
		str += '&add_entry=1';
		str += '&project_id=<?php echo $row->project_id;?>';
        $.ajax({
            type: "post",
            dataType: 'json',
            url: "controller.php",
            data: str,
            success: function (json) {
                if (json.type == "success") {
					var rowCount = $('#invoice-entries > tbody > tr').length;
					if(rowCount == 0) {
						$(json.message).appendTo('#invoice-entries tbody');
					} else {
						$(json.message).insertAfter('#invoice-entries tbody tr:last');
					}
					$("#newentry .norecord").remove();
                    $("#newentry .emsg").html(json.info);
                } else {
                    $("#newentry .emsg").html(json.message);
                }
				
				$but.removeClass('loading');
            }
        });
        return false;
    });
    // Add Record
	$('#dorecord').on('click', function () {
		$but = $(this);
		$but.addClass('loading');
        var str = $("#record_form").serialize();
        str += '&processInvoiceRecord=1';
        str += '&invoice_id=<?php echo Filter::$id;?>';
		str += '&ptitle=<?php echo urlencode($row->title);?>';
		str += '&add_record=1';
		str += '&project_id=<?php echo $row->project_id;?>';
        $.ajax({
            type: "post",
            dataType: 'json',
            url: "controller.php",
            data: str,
            success: function (json) {
                if (json.type == "success") {
					var rowCount = $('#invoice-records > tbody > tr').length;
					if(rowCount == 0) {
						$(json.message).appendTo('#invoice-records tbody');
					} else {
						$(json.message).insertAfter('#invoice-records tbody tr:last');
					}
					$("#newrecord .norecord").remove();
                    $("#newrecord .rmsg").html(json.info);
                } else {
                    $("#newrecord .rmsg").html(json.message);
                }
				$but.removeClass('loading');
            }
        });
        return false;
    });
    // Send Invoice
    $('a.sendinvoice').click(function () {
        var id = $(this).data('id')
        var text = "<div class=\"messi-warning\"><i class=\"massive icon warn warning sign\"></i></p><p><?php echo Lang::$word->INVC_SEND_T;?></p></div>";
        new Messi(text, {
            title: "<?php echo Lang::$word->INVC_EMAIL_T2;?>",
            modal: true,
            closeButton: true,
            buttons: [{
                id: 0,
                label: "<?php echo Lang::$word->SEND;?>",
				class: 'negative',
                val: 'Y'
            }],
            callback: function (val) {
                if (val === "Y") {
					$.ajax({
						type: 'post',
						dataType: 'json',
						url: "controller.php",
						data: {
							sendInvoice:1,
							id: id
							},
						beforeSend: function () {
							$("body").addClass('loading');
						},
						success: function (json) {
							$("body").removeClass('loading')
							$.sticky(decodeURIComponent(json.message), {
								type: json.type,
								title: json.title
							});
						}
					});
                }
            }
        })
    });
});
// ]]>
</script>
<?php endif;?>
<?php break;?>
<?php case"editentry": ?>
<?php $row = Core::getRowById("invoice_data", Filter::$id);?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Lang::$word->INVC_ENTRYINFO2;?><br>
    <?php echo Lang::$word->REQFIELD1;?> * <?php echo Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->INVC_ENTRYSUB2 . $row->title;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->INVC_ENTRYTITLE;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="etitle" value="<?php echo $row->title;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->AMOUNT;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i> <i class="icon-prepend icon dollar"></i>
          <input type="text" name="eamount" value="<?php echo $row->amount;?>" >
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->DESC;?></label>
        <label class="input">
          <input type="text" name="edesc" value="<?php echo $row->description;?>" >
        </label>
      </div>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->TAXABLE;?></label>
      <div class="inline-group">
        <label class="radio">
          <input type="radio" name="etax" value="1" <?php if($row->tax <> 0) echo 'checked="checked"'; ?>>
          <i></i><?php echo Lang::$word->YES;?></label>
        <label class="radio">
          <input type="radio" name="etax" value="0" <?php if($row->tax == 0) echo 'checked="checked"'; ?>>
          <i></i><?php echo Lang::$word->NO;?></label>
      </div>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->INVC_ENTRYUPDATE;?></button>
    <a href="index.php?do=invoices&amp;action=edit&amp;pid=<?php echo $row->project_id;?>&amp;id=<?php echo $row->invoice_id;?>" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processInvoiceEntry" type="hidden" value="1">
    <input name="invoice_id" type="hidden" value="<?php echo $row->invoice_id;?>" />
    <input name="project_id" type="hidden" value="<?php echo $row->project_id;?>" />
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php case"editrecord": ?>
<?php $row = Core::getRowById("invoice_payments", Filter::$id);?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Lang::$word->INVC_RECINFO2;?><br>
    <?php echo Lang::$word->REQFIELD1;?> * <?php echo Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->INVC_RECSUB2 . getValueById("title","invoices",$row->invoice_id);?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->INVC_DUEDATE;?></label>
        <label class="input"> <i class="icon-append icon calendar"></i>
          <input type="text" name="rcreated" data-datepicker="true" data-value="<?php echo $row->created;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->AMOUNT;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i> <i class="icon-prepend icon dollar"></i>
          <input type="text" name="ramount" value="<?php echo $row->amount;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->DESC;?></label>
        <label class="input">
          <input type="text" name="rdesc" value="<?php echo $row->description;?>">
        </label>
      </div>
    </div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->PAYMETHOD;?></label>
        <select name="method">
          <option value="Offline"<?php if($row->method == 'Offline') echo ' selected="selected"';?>><?php echo Lang::$word->OFFLINE;?></option>
          <?php foreach ($content->getGateways() as $grow):?>
          <option value="<?php echo $grow->displayname;?>"><?php echo $grow->displayname;?></option>
          <?php endforeach;?>
          <?php unset($grow);?>
        </select>
      </div>
      <div class="field"></div>
      <div class="field"></div>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->INVC_RECUPDATE;?></button>
    <a href="index.php?do=invoices&amp;action=edit&amp;pid=<?php echo $row->project_id;?>&amp;id=<?php echo $row->invoice_id;?>" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processInvoiceRecord" type="hidden" value="1">
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
    <input name="invoice_id" type="hidden" value="<?php echo $row->invoice_id;?>" />
    <input name="project_id" type="hidden" value="<?php echo $row->project_id;?>" />
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php case"add": ?>
<?php if($user->userlevel == 5): print Filter::msgInfo(Lang::$word->ADMINONLY, false); return; endif;?>
<?php if(!Filter::$id or !$title = getValueById("title","projects", Filter::$id)): redirect_to("index.php?do=projects"); endif;?>
<?php $prodrow = $content->getProjectList();?>
<?php $userlist = $user->getUserList(1);?>
<?php $invdata = $content->getInvoiceData();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->INVC_INFO2 . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?> </div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->INVC_SUB2 . $title;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->INVC_NAME;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" placeholder="<?php echo Lang::$word->INVC_NAME;?>" name="title">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PROJ_NAME;?></label>
        <select name="project_id">
          <option value="">--- <?php echo Lang::$word->INVC_PROJCSELETC;?> ---</option>
          <?php if($prodrow):?>
          <?php foreach ($prodrow as $prow):?>
          <option value="<?php echo $prow->id;?>"<?php if($prow->id == Filter::$id) echo ' selected="selected"';?>><?php echo $prow->title;?></option>
          <?php endforeach;?>
          <?php unset($srow);?>
          <?php endif;?>
        </select>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->INVC_CLIENTSELECT;?></label>
        <select name="client_id">
          <option value="">--- <?php echo Lang::$word->INVC_CLIENTSELECT;?> ---</option>
          <?php if($userlist):?>
          <?php foreach ($userlist as $srow):?>
          <option value="<?php echo $srow->id;?>"<?php if($srow->id == getValueById("client_id",Content::pTable,Filter::$id)) echo ' selected="selected"';?>><?php echo Core::renderName($srow);?></option>
          <?php endforeach;?>
          <?php unset($srow);?>
          <?php endif;?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PAYMETHOD;?></label>
        <select name="method">
          <option value="Offline"><?php echo Lang::$word->OFFLINE;?></option>
          <?php foreach ($content->getGateways() as $grow):?>
          <option value="<?php echo $grow->displayname;?>"><?php echo $grow->displayname;?></option>
          <?php endforeach;?>
          <?php unset($grow);?>
        </select>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->INVC_RECURRING_PAY;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="recurring" value="1">
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="recurring" value="0" checked="checked">
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <div class="two fields">
          <div class="field">
            <label><?php echo Lang::$word->INVC_RECURRING_PER;?></label>
            <select name="period">
              <option value="D"><?php echo Lang::$word->INVC_REC_DAYS;?></option>
              <option value="W"><?php echo Lang::$word->INVC_REC_WEEKS;?></option>
              <option value="M"><?php echo Lang::$word->INVC_REC_MONTHS;?></option>
              <option value="Y"><?php echo Lang::$word->INVC_REC_YEARS;?></option>
            </select>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->INVC_RECURRING_PER;?></label>
            <label class="input">
              <input type="text" name="days" placeholder="<?php echo Lang::$word->INVC_RECURRING_PER;?>">
            </label>
          </div>
        </div>
      </div>
    </div>
    <div class="four fields">
      <div class="field">
        <label><?php echo Lang::$word->TAXABLE;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="tax" value="1">
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="tax" value="0" checked="checked">
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->INVC_ONHOLD;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="onhold" value="1">
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="onhold" value="0" checked="checked">
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CREATED;?></label>
        <label class="input"><i class="icon-append icon calendar"></i>
          <input type="text" data-datepicker="true" data-value="<?php echo date('Y-m-d');?>" value="<?php echo date('Y-m-d');?>" name="created">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->INVC_DUEDATE;?></label>
        <label class="input"><i class="icon-append icon calendar"></i>
          <input type="text" data-datepicker="true" data-value="<?php echo date('Y-m-d', strtotime("+30 days"));?>" value="<?php echo date('Y-m-d', strtotime("+30 days"));?>" name="duedate">
        </label>
      </div>
    </div>
    <div class="wojo divider"></div>
    <?php if($invdata):?>
    <div class="small wojo form">
      <div class="three fields">
        <div class="field">
          <div class="wojo header"><?php echo Lang::$word->INVC_SUBENTRY;?></div>
        </div>
        <div class="field"></div>
        <div class="field">
          <select id="inventries">
            <option value="0"><?php echo Lang::$word->INVC_SUBENTRY2;?></option>
            <?php foreach($invdata as $irow):?>
            <option value="<?php echo $irow->id;?>"><?php echo $irow->title;?></option>
            <?php endforeach;?>
          </select>
        </div>
      </div>
    </div>
    <?php endif;?>
    <div id="inventrylist" class="wojo segment">
      <div class="three fields clonedInput" id="container1">
        <div class="field">
          <label><?php echo Lang::$word->INVC_ENTRYTITLE;?></label>
          <label class="input"> <i class="icon-append icon asterisk"></i>
            <input type="text" name="dtitle[]" placeholder="<?php echo Lang::$word->INVC_ENTRYTITLE;?>">
          </label>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->AMOUNT;?></label>
          <label class="input"> <i class="icon-append icon asterisk"></i> <i class="icon-prepend icon dollar"></i>
            <input type="text" name="amount[]" placeholder="<?php echo Lang::$word->AMOUNT;?>">
          </label>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->DESC;?></label>
          <label class="input">
            <input type="text" name="description[]" placeholder="<?php echo Lang::$word->DESC;?>">
          </label>
        </div>
      </div>
    </div>
    <div class="field">
      <div class="wojo tiny icon buttons">
        <button class="wojo button" id="btnAdd" type="button"><i class="icon add"></i></button>
        <button class="wojo button" id="btnDel" type="button"><i class="icon minus"></i></button>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="field">
      <label class="label"><?php echo Lang::$word->INVC_NOTE;?></label>
      <textarea class="altpost" name="notes"><?php echo $core->invoice_note;?></textarea>
      <p class="wojo success"><?php echo Lang::$word->INVC_NOTE_T;?></p>
    </div>
    <div class="field">
      <label class="label"><?php echo Lang::$word->INVC_COMMENT;?></label>
      <textarea class="altpost" name="comment" rows="5"></textarea>
      <p class="wojo success"><?php echo Lang::$word->INVC_COMMENT_T;?></p>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->INVC_ADD;?></button>
    <a href="index.php?do=invoices&amp;id=<?php echo Filter::$id;?>" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="addInvoice" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<script type="text/javascript">
$(document).ready(function() {
    $('#inventries').change(function() {
        var option = $(this).val();
        if (option != '') {
            $.ajax({
                type: 'get',
                url: "controller.php",
                dataType: 'json',
                data: ({
                    getInvoiceData: 1,
                    id: option,
                }),
                success: function(json) {
                    if (json.status == "success") {
                        var num = $('.clonedInput').length;
                        var newNum = new Number(num + 1);
						var html = '';
                        html += '<div class="three fields clonedInput" id="container' + newNum + '">';
                        html += '<div class="field">';
                        html += '<label><?php echo Lang::$word->INVC_ENTRYTITLE;?></label>';
                        html += '<label class="input"> <i class="icon-append icon asterisk"></i>';
                        html += '<input type="text" name="dtitle[]" value="' + json.title + '">';
                        html += '</label>';
                        html += '</div>';
                        html += '<div class="field">';
                        html += '<label><?php echo Lang::$word->AMOUNT;?></label>';
                        html += '<label class="input"> <i class="icon-append icon asterisk"></i> <i class="icon-prepend icon dollar"></i>';
                        html += '<input type="text" name="amount[]" value="' + json.amount + '">';
                        html += '</label>';
                        html += '</div>';
                        html += '<div class="field">';
                        html += '<label><?php echo Lang::$word->DESC;?></label>';
                        html += '<label class="input">';
                        html += '<input type="text" name="description[]" value="' + json.desc + '">';
                        html += '</label>';
                        html += '</div>';
                        $("#inventrylist").append(html)
                    } else {
                        $("#msgholder").html(json.message);
                    }
                }
            });
        }
    });
    $('#btnAdd').click(function() {
        var num = $('.clonedInput').length;
        var newNum = new Number(num + 1);
        var newElem = $('#container' + num).clone().attr('id', 'container' + newNum);
        $('#container' + num).after(newElem);
        $('#btnDel').attr('disabled', false);
        if (newNum == 15) $('#btnAdd').attr('disabled', 'disabled');
    });
    $('#btnDel').click(function() {
        var num = $('.clonedInput').length;
        $('#container' + num).remove();
        $('#btnAdd').attr('disabled', false);
        if (num - 1 == 1) $('#btnDel').attr('disabled', 'disabled');
    });
    $('#btnDel').attr('disabled', 'disabled');
});
</script>
<?php break;?>
<?php default:?>
<?php if($user->userlevel == 5): print Filter::msgInfo(Lang::$word->ADMINONLY, false); return; endif;?>
<?php if(!Filter::$id or !getValueById("title","projects", Filter::$id)): redirect_to("index.php?do=projects"); endif;?>
<?php $invrow = $content->getProjectInvoices();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Lang::$word->INVC_INFO3;?></div>
</div>
<div class="wojo basic block segment"> <a class="wojo basic button push-right" href="index.php?do=invoices&amp;action=add&amp;id=<?php echo Filter::$id;?>"><i class="icon add"></i> <?php echo Lang::$word->INVC_ADD;?></a>
  <h2 class="wojo left floated header"><i class="file text icon"></i><?php echo Lang::$word->INVC_SUB3 . getValueById("title","projects", Filter::$id);?></h2>
</div>
<table class="wojo sortable table">
  <thead>
    <tr>
      <th data-sort="string">#</th>
      <th data-sort="string"><?php echo Lang::$word->INVC_NAME;?></th>
      <th data-sort="string"><?php echo Lang::$word->INVC_CNAME;?></th>
      <th data-sort="inc"><?php echo Lang::$word->CREATED;?> / <?php echo Lang::$word->INVC_DUEDATE;?></th>
      <th data-sort="inc"><?php echo Lang::$word->TOTAL;?> / <?php echo Lang::$word->PAID;?></th>
      <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
    </tr>
  </thead>
  <?php if(!$invrow):?>
  <tr>
    <td colspan="6"><?php echo Filter::msgSingleInfo(Lang::$word->INVC_NOINVOICE);?></td>
  </tr>
  <?php else:?>
  <?php foreach ($invrow as $row):?>
  <tr>
    <td><?php echo ($core->invoice_number . $row->id);?></td>
    <td><?php echo $row->title;?></td>
    <td><?php echo Core::renderName($row);?></td>
    <td data-sort-value="<?php echo strtotime($row->created);?>"><?php echo Filter::dodate("short_date", $row->created);?> / <?php echo Filter::dodate("short_date", $row->duedate);?></td>
    <td><?php echo $row->amount_total;?> / <?php echo $row->amount_paid;?></td>
    <td><a data-content="<?php echo Lang::$word->STATUS . ' ' . $row->status;?>"><i class="rounded inverted icon <?php echo ($row->status == "Paid") ? "info check" : "warning time";?> link"></i></a> <a href="index.php?do=invoices&amp;action=edit&amp;pid=<?php echo $row->project_id;?>&amp;id=<?php echo $row->id;?>"><i class="rounded inverted success icon pencil link"></i></a> <a class="delete" data-title="<?php echo Lang::$word->INVC_DELETEINV;?>" data-option="deleteInvoice" data-extra="<?php echo $row->project_id;?>" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->title;?>"><i class="rounded danger inverted remove icon link"></i></a></td>
  </tr>
  <?php endforeach;?>
  <?php unset($row);?>
  <?php endif;?>
</table>
<?php break;?>
<?php endswitch;?>