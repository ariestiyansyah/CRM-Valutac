<?php
  /**
   * Quotes
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: quotes.php, v3.00 2014-05-08 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Filter::$action): case "edit": ?>
<?php if($user->userlevel == 5): print Filter::msgInfo(Lang::$word->ADMINONLY, false); return; endif;?>
<?php $row = $content->getQuoteById();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Lang::$word->QUTS_INFO2;?><br>
    <?php echo Lang::$word->REQFIELD1;?> * <?php echo Lang::$word->REQFIELD2;?></div>
</div>
<?php if(!$row):?>
<?php echo Filter::msgSingleError(Lang::$word->INVC_ERR);?>
<?php else:?>
<?php $prodrow = $content->getProjectList();?>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->QUTS_SUB2 . $row->title;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->QUTS_NAME;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="title" value="<?php echo $row->title;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->INVC_CNAME;?></label>
        <label class="input">
          <input type="text" name="name" value="<?php echo Core::renderName($row);?>" disabled="disabled" readonly>
        </label>
      </div>
    </div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->QUTS_TOTAL;?></label>
        <label class="input"><i class="icon-prepend icon dollar"></i>
          <input type="text" name="amount_total" value="<?php echo $row->amount_total;?>" disabled="disabled" readonly >
        </label>
      </div>
      <div class="field">
        <label><?php echo $core->tax_name;?></label>
        <label class="input">
          <input type="text" name="tax" value="<?php echo $row->tax;?>" disabled="disabled" readonly>
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PROJ_NAME;?></label>
        <select name="project_id">
          <option value="">--- <?php echo Lang::$word->INVC_PROJCSELETC;?> ---</option>
          <?php if($prodrow):?>
          <?php foreach ($prodrow as $prow):?>
          <option value="<?php echo $prow->id;?>"<?php if($prow->id == $row->project_id) echo 'selected="selected"';?>><?php echo $prow->title;?></option>
          <?php endforeach;?>
          <?php unset($srow);?>
          <?php endif;?>
        </select>
      </div>
    </div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->QUTS_QTNUMBER;?></label>
        <label class="input">
          <input type="text" name="quote_number" value="<?php echo $core->quote_number . $row->id;?>" disabled="disabled" readonly placeholder="<?php echo Lang::$word->QUTS_QTNUMBER;?>">
        </label>
      </div>
      <div class="field">
        <label class="input">
        <label><?php echo Lang::$word->INVC_DUEDATE;?></label>
        <input type="text" data-datepicker="true" data-value="<?php echo $row->expire;?>" value="<?php echo $row->expire;?>" name="expire">
        </label>
      </div>
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
    </div>
    <label><?php echo Lang::$word->ACTIONS;?></label>
    <div class="wojo divider"></div>
    <div class="field"> <a class="sendinvoice wojo info button" data-id="<?php echo $row->id;?>"><i class="icon reply mail all"></i><?php echo Lang::$word->QUTS_EMAIL_T;?></a> <a href="print_quote.php?id=<?php echo $row->id;?>" target="_blank" class="wojo warning button"><i class="icon print"></i><?php echo Lang::$word->QUTS_PRINT_T;?></a> <a href="controller.php?doquotepdf=<?php echo $row->id;?>&amp;title=<?php echo urlencode($row->title);?>" class="wojo danger button"><i class="icon pdf outline"></i><?php echo Lang::$word->QUTS_PDF_T;?></a> </div>
    <div class="wojo divider"></div>
    <div class="wojo header"><?php echo Lang::$word->INVC_NOTES;?></div>
    <div class="field">
      <label class="label"><?php echo Lang::$word->QUTS_NOTE;?></label>
      <textarea class="altpost" name="notes"><?php echo ($row->notes) ? $row->notes : $core->invoice_note;?></textarea>
      <p class="wojo success"><?php echo Lang::$word->QUTS_NOTE_T;?></p>
    </div>
    <div class="field">
      <label class="label"><?php echo Lang::$word->QUTS_COMMENT;?></label>
      <textarea class="altpost" name="comment"><?php echo $row->comment;?></textarea>
      <p class="wojo success"><?php echo Lang::$word->QUTS_COMMENT_T;?></p>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->QUTS_UPDATE;?></button>
    <a href="index.php?do=quotes" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="convertQuote" type="hidden" value="1">
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
  </form>
</div>
<div id="msgholder"></div>
<div class="wojo header"><?php echo Lang::$word->QUTS_SUBENTRY;?></div>
<?php echo $content->loadQuoteEntries($row->id);?> 
<script type="text/javascript">
// <![CDATA[
$(document).ready(function () {
    // Send Invoice
	$('a.sendinvoice').click(function () {
        var id = $(this).data('id')
		var text = "<div class=\"messi-warning\"><i class=\"massive icon warn warning sign\"></i></p><p><?php echo Lang::$word->QUTS_SEND_T;?></p></div>";
        new Messi(text, {
            title: "<?php echo Lang::$word->QUTS_EMAIL_T2;?>",
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
						data: 'sendQuote=' + id,
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
        });
    });
});
// ]]>
</script>
<?php endif;?>
<?php break;?>
<?php case"add": ?>
<?php if($user->userlevel == 5): print Filter::msgInfo(Lang::$word->ADMINONLY, false); return; endif;?>
<?php $prodrow = $content->getProjectList();?>
<?php $userlist = $user->getUserList(1);?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Lang::$word->QUTS_INFO3;?><br>
    <?php echo Lang::$word->REQFIELD1;?> * <?php echo Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->QUTS_SUB3;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->QUTS_NAME;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="title" placeholder="<?php echo Lang::$word->QUTS_NAME;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PROJ_NAME;?></label>
        <select name="project_id">
          <option value="">--- <?php echo Lang::$word->INVC_PROJCSELETC;?> ---</option>
          <?php if($prodrow):?>
          <?php foreach ($prodrow as $prow):?>
          <option value="<?php echo $prow->id;?>"><?php echo $prow->title;?></option>
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
          <option value="<?php echo $srow->id;?>"><?php echo Core::renderName($srow);?></option>
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
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->CREATED;?></label>
        <label class="input"> <i class="icon-prepend icon asterisk"></i> <i class="icon-append icon calendar"></i>
          <input type="text" data-datepicker="true" data-value="<?php echo date('Y-m-d');?>" value="<?php echo date('Y-m-d');?>" name="created">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->QUTS_EXPIRE;?></label>
        <label class="input"> <i class="icon-prepend icon asterisk"></i> <i class="icon-append icon calendar"></i>
          <input type="text" data-datepicker="true" data-value="<?php echo date('Y-m-d', strtotime("+30 days"));?>" value="<?php echo date('Y-m-d', strtotime("+30 days"));?>" name="expire">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->TAXABLE;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="tax" value="1" >
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="tax" value="0" checked="checked">
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="wojo divider"></div>
    <div class="wojo header"><?php echo Lang::$word->QUTS_INITIALENTRY;?></div>
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
    <div class="wojo divider"></div>
    <div class="field">
      <div class="wojo tiny icon buttons">
        <button class="wojo button" id="btnAdd" type="button"><i class="icon add"></i></button>
        <button class="wojo button" id="btnDel" type="button"><i class="icon minus"></i></button>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="field">
      <label class="label"><?php echo Lang::$word->QUTS_NOTES;?></label>
      <textarea class="altpost" name="notes"><?php echo $core->invoice_note;?></textarea>
      <p class="wojo success"><?php echo Lang::$word->QUTS_NOTE_T;?></p>
    </div>
    <div class="field">
      <label class="label"><?php echo Lang::$word->QUTS_COMMENT;?></label>
      <textarea class="altpost" name="comment" rows="5"></textarea>
      <p class="wojo success"><?php echo Lang::$word->QUTS_COMMENT_T;?></p>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->QUTS_ADD;?></button>
    <a href="index.php?do=quotes" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="addQuote" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<script type="text/javascript">
$(document).ready(function () {
    $('#btnAdd').click(function () {
        var num = $('.clonedInput').length;
        var newNum = new Number(num + 1);
        var newElem = $('#container' + num).clone().attr('id', 'container' + newNum);
        $('#container' + num).after(newElem);
        $('#btnDel').attr('disabled', false);
        if (newNum == 15) $('#btnAdd').attr('disabled', 'disabled');
    });
    $('#btnDel').click(function () {
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
<?php $quoterow = $content->getQuotes();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Lang::$word->QUTS_INFO;?></div>
</div>
<div class="wojo basic block segment">
  <?php if($user->userlevel == 9):?>
  <a class="wojo basic button push-right" href="index.php?do=quotes&amp;action=add"><i class="icon add"></i> <?php echo Lang::$word->QUTS_NEW;?></a>
  <?php endif;?>
  <h2 class="wojo left floated header"><i class="file text outline icon"></i><?php echo Lang::$word->QUTS_SUB;?></h2>
</div>
<table class="wojo sortable table">
  <thead>
    <tr>
      <th data-sort="string">#</th>
      <th data-sort="string"><?php echo Lang::$word->INVC_CNAME;?></th>
      <th data-sort="int"><?php echo Lang::$word->CREATED;?></th>
      <th data-sort="int"><?php echo Lang::$word->QUTS_EXPIRE;?></th>
      <th data-sort="int"><?php echo Lang::$word->TOTAL;?></th>
      <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
    </tr>
  </thead>
  <?php if(!$quoterow):?>
  <tr>
    <td colspan="6"><?php echo Filter::msgSingleInfo(Lang::$word->QUTS_NOQUOTES);?></td>
  </tr>
  <?php else:?>
  <?php foreach ($quoterow as $row):?>
  <tr>
    <td><?php echo ($core->quote_number . $row->id);?></td>
    <td><?php echo Core::renderName($row);?></td>
    <td data-sort-value="<?php echo strtotime($row->created);?>"><?php echo Filter::dodate("short_date", $row->created);?></td>
    <td data-sort-value="<?php echo strtotime($row->expire);?>"><?php echo Filter::dodate("short_date", $row->expire);?></td>
    <td><?php echo $row->amount_total;?></td>
    <td><a href="index.php?do=quotes&amp;action=edit&amp;id=<?php echo $row->id;?>"><i class="rounded inverted success icon pencil link"></i></a> <a class="delete" data-title="<?php echo Lang::$word->QUTS_DELETEINV;?>" data-option="deleteQuote" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->title;?>"><i class="rounded danger inverted remove icon link"></i></a></td>
  </tr>
  <?php endforeach;?>
  <?php unset($row);?>
  <?php endif;?>
</table>
<?php break;?>
<?php endswitch;?>