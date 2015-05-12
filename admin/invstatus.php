<?php
  /**
   * Invoice Status
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: invstatus.php, v3.00 2014-05-08 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php if($user->userlevel == 5): print Filter::msgInfo(Lang::$word->ADMINONLY, false); return; endif;?>
<?php $invrow = $content->getInvoicesByStatus();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->INVC_INFO4;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><i class="file text icon"></i><?php echo Lang::$word->INVC_SUB4;?></h2>
</div>
<div class="wojo small form basic segment">
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="three fields">
      <div class="field">
        <div class="wojo input"> <i class="icon-prepend icon calendar"></i>
          <input name="fromdate" type="text" data-datepicker="true" placeholder="<?php echo Lang::$word->INVC_DUEFROM;?>" id="fromdate">
        </div>
      </div>
      <div class="field">
        <div class="wojo action input"> <i class="icon-prepend icon calendar"></i>
          <input name="enddate" type="text" data-datepicker="true" placeholder="<?php echo Lang::$word->INVC_DUETO;?>" id="enddate">
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
      <th data-sort="string">#</th>
      <th data-sort="string"><?php echo Lang::$word->INVC_NAME;?></th>
      <th data-sort="string"><?php echo Lang::$word->INVC_CNAME;?></th>
      <th data-sort="int"><?php echo Lang::$word->CREATED;?> / <?php echo Lang::$word->INVC_DUEDATE;?></th>
      <th data-sort="int"><?php echo Lang::$word->TOTAL;?> / <?php echo Lang::$word->PENDING;?></th>
      <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
    </tr>
  </thead>
  <tbody>
    <?php if(!$invrow):?>
    <tr>
      <td colspan="6"><?php echo Filter::msgSingleInfo(Lang::$word->INVC_NOINVOICE2);?></td>
    </tr>
    <?php else:?>
    <?php foreach ($invrow as $row):?>
    <tr>
      <td><?php echo $core->invoice_number . $row->id;?></td>
      <td><?php echo $row->title;?></td>
      <td><?php echo $row->name;?></td>
      <td data-sort-value="<?php echo strtotime($row->created);?>"><?php echo Filter::dodate("short_date", $row->created);?> / <?php echo Filter::dodate("short_date", $row->duedate);?></td>
      <td><?php echo number_format($row->amount_total,2);?> / <?php echo number_format($row->amount_total - $row->amount_paid,2);?></td>
      <td><a data-content="<?php echo Lang::$word->STATUS . ' ' . $row->status;?>"><i class="rounded inverted icon <?php echo ($row->status == "Paid") ? "info check" : "warning time";?> link"></i></a> <a href="index.php?do=invoices&amp;action=edit&amp;pid=<?php echo $row->project_id;?>&amp;id=<?php echo $row->id;?>"><i class="rounded inverted success icon pencil link"></i></a> <a class="delete" data-title="<?php echo Lang::$word->INVC_DELETEINV;?>" data-option="deleteInvoice" data-extra="<?php echo $row->project_id;?>" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->title;?>"><i class="rounded danger inverted remove icon link"></i></a></td>
    </tr>
    <?php endforeach;?>
    <?php unset($row);?>
    <?php endif;?>
  </tbody>
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