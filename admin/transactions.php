<?php
  /**
   * Payment Transactions
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: transactions.php, v3.00 2014-05-08 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php if($user->userlevel == 5): print Filter::msgInfo(Lang::$word->ADMINONLY, false); return; endif;?>
<?php $transrow = $content->getPaymentTransactions();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->TRANS_INFO;?></div>
</div>
<div class="wojo basic block segment">
  <div class="wojo right pointing dropdown icon basic button push-right"> <i class="settings icon"></i>
    <div class="menu"> <a class="item" href="controller.php?action=createReport"><i class="icon table"></i> <?php echo Lang::$word->TRANS_REPORT;?></a> <a class="item" href="controller.php?action=createServiceReport"><i class="icon table"></i> <?php echo Lang::$word->TRANS_REPORT2;?></a> </div>
  </div>
  <h2 class="wojo left floated header"><i class="exchange icon"></i><?php echo Lang::$word->TRANS_SUB;?></h2>
</div>
<div class="wojo small form basic segment">
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="three fields">
      <div class="field">
        <div class="wojo input"> <i class="icon-prepend icon calendar"></i>
          <input name="fromdate" type="text" data-datepicker="true" placeholder="<?php echo Lang::$word->FROM;?>" id="fromdate">
        </div>
      </div>
      <div class="field">
        <div class="wojo action input"> <i class="icon-prepend icon calendar"></i>
          <input name="enddate" type="text" data-datepicker="true" placeholder="<?php echo Lang::$word->TO;?>" id="enddate">
          <a id="doDates" class="wojo icon button"><?php echo Lang::$word->FIND;?></a> </div>
      </div>
      <div class="field">
        <div class="two fields">
          <div class="field"> <?php echo $pager->items_per_page();?> </div>
          <div class="field"> <?php echo $pager->jump_menu();?> </div>
        </div>
      </div>
    </div>
  </form>
  <div class="wojo divider"></div>
</div>
<table class="wojo sortable table">
  <thead>
    <tr>
      <th data-sort="string"><?php echo Lang::$word->PROJ_NAME;?></th>
      <th data-sort="string">#<?php echo Lang::$word->TRANS_INVOICE;?></th>
      <th data-sort="int"><?php echo Lang::$word->TRANS_PAYDATE;?></th>
      <th data-sort="string"><?php echo Lang::$word->PAYMETHOD;?></th>
      <th data-sort="int"><?php echo Lang::$word->AMOUNT;?></th>
      <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
    </tr>
  </thead>
  <?php if(!$transrow):?>
  <tr>
    <td colspan="6"><?php echo Filter::msgSingleInfo(Lang::$word->TRANS_NOTRANS);?></td>
  </tr>
  <?php else:?>
  <?php foreach ($transrow as $row):?>
  <tr>
    <td><a href="index.php?do=projects&amp;action=edit&amp;id=<?php echo $row->project_id;?>"><?php echo $row->ptitle;?></a></td>
    <td><a href="index.php?do=invoices&amp;action=edit&amp;pid=<?php echo $row->project_id;?>&amp;id=<?php echo $row->invoice_id;?>"><?php echo ($core->invoice_number . $row->invoice_id);?></a></td>
    <td data-sort-value="<?php echo strtotime($row->created);?>"><?php echo Filter::dodate("short_date", $row->created);?></td>
    <td><?php echo $row->method;?></td>
    <td><?php echo $row->amount;?></td>
    <td><a data-content="<?php echo sanitize($row->description);?>"><i class="rounded inverted info icon information link"></i></a> <a class="delete" data-title="<?php echo Lang::$word->TRANS_DELETE;?>" data-option="deleteInvoiceRecord" data-extra="<?php echo $row->project_id . ':' . $row->invoice_id;?>" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->ptitle;?>"><i class="rounded danger inverted remove icon link"></i></a></td>
  </tr>
  <?php endforeach;?>
  <?php unset($row);?>
  <?php endif;?>
</table>
<div class="wojo fitted divider"></div>
<div class="two columns horizontal-gutters">
  <div class="row"> <span class="wojo black label"><?php echo Lang::$word->TOTAL . ': ' . $pager->items_total;?> / <?php echo Lang::$word->CURPAGE . ': ' . $pager->current_page . ' ' . Lang::$word->OF . ' ' . $pager->num_pages;?></span> </div>
  <div class="row">
    <div class="push-right"><?php echo $pager->display_pages();?></div>
  </div>
</div>
<script type="text/javascript"> 
// <![CDATA[  
$(document).ready(function () {
	/* == From/To range == */
	var from_$input = $('input[name=fromdate]').pickadate({formatSubmit: 'yyyy-mm-dd'}),
		from_picker = from_$input.pickadate('picker')
	
	var to_$input = $('input[name=enddate]').pickadate({formatSubmit: 'yyyy-mm-dd'}),
		to_picker = to_$input.pickadate('picker')
	
	if ( from_picker.get('value') ) {
	  to_picker.set('min', from_picker.get('select'))
	}
	if ( to_picker.get('value') ) {
	  from_picker.set('max', to_picker.get('select'))
	}
	
	from_picker.on('set', function(event) {
	  if ( event.select ) {
		to_picker.set('min', from_picker.get('select'))    
	  }
	  else if ( 'clear' in event ) {
		to_picker.set('min', false)
	  }
	})
	to_picker.on('set', function(event) {
	  if ( event.select ) {
		from_picker.set('max', to_picker.get('select'))
	  }
	  else if ( 'clear' in event ) {
		from_picker.set('max', false)
	  }
	})
});
// ]]>
</script>
