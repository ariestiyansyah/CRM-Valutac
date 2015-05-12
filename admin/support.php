<?php
  /**
   * Support Tickets
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: support.php, v3.00 2014-05-08 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Filter::$action): case "edit": ?>
<?php $row = $content->getSupportTicketById();?>
<?php $resrow = $content->getResponseByTicketId();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->SUP_INFO . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
</div>
<?php if(!$row):?>
<?php echo Filter::msgInfo(Lang::$word->SUP_NOTICKET,false);?>
<?php else:?>
<div class="wojo basic block segment"> <small class="wojo positive label push-right"><?php echo $row->tid;?></small>
  <h2 class="wojo left floated header"><?php echo Lang::$word->SUP_SUB . $row->subject;?><p><small><?php echo $row->ptitle;?></small></p></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->INVC_CNAME;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="client_id" disabled="disabled" readonly value="<?php echo Core::renderName($row);?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->SUP_PRIORITY;?></label>
        <select name="priority">
          <?php echo $content->ticketPriorityList($row->priority);?>
        </select>
      </div>
    </div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->SUP_SUBJECT;?></label>
        <label class="input">
          <input type="text" name="subject" disabled="disabled" readonly value="<?php echo $row->subject;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->SUP_ASSIGNED;?></label>
        <select name="staff_id">
          <?php foreach ($user->getUserList("9' or userlevel = '5") as $srow):?>
          <option value="<?php echo $srow->id;?>"<?php if($srow->id == $row->staff_id) echo ' selected="selected"';?>><?php echo Core::renderName($srow);?></option>
          <?php endforeach;?>
          <?php unset($srow);?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->SUP_STATUS;?></label>
        <select name="status">
          <?php echo $content->ticketStatusList($row->status);?>
        </select>
      </div>
    </div>
    <?php if($row->attachment):?>
    <div class="field"> <i class="icon download cloud"></i> <a href="<?php echo UPLOADURL . 'tempfiles/' . $row->attachment;?>"><?php echo Lang::$word->FORM_DOWNLOAD;?></a> </div>
    <?php endif;?>
    <div class="field">
      <div class="wojo segment"><?php echo cleanOut($row->body);?></div>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->SUP_UPDATE_S;?></button>
    <a href="index.php?do=support" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processSupportTicket" type="hidden" value="1">
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
  </form>
</div>
<div id="msgholder"></div>
<?php endif;?>
<form class="wojo form" method="post" id="reply_form" name="reply_form">
  <div class="wojo header"><?php echo Lang::$word->SUP_REPLYTO . $row->subject;?></div>
  <div class="field">
    <textarea class="bodypost" name="body"></textarea>
  </div>
  <div class="wojo double fitted divider"></div>
  <button type="button" id="send-reply" name="doreply" class="wojo basic button"><?php echo Lang::$word->REPLY;?></button>
  <input name="replySupportTicket" type="hidden" value="1" />
  <input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
</form>
<div id="replydata" class="wojo celled list">
  <?php if($resrow):?>
  <?php foreach($resrow as $trow):?>
  <div class="wojo basic message">
    <div class="wojo top right black attached label"><a class="delete" data-title="<?php echo Lang::$word->SUP_DELETE;?>" data-option="deleteSupportReply" data-id="<?php echo $trow->id;?>" data-name="<?php echo Filter::dodate("long_date", $trow->created);?>"><i class="remove icon link"></i></a></div>
    <span class="wojo small black label"><?php echo Filter::dodate("long_date", $trow->created);?></span> <span class="wojo small <?php echo ($trow->user_type == "client") ? 'warning' : 'positive';?> label"><?php echo Lang::$word->AUTHOR;?>: <?php echo $trow->name;?> (<?php echo $trow->user_type;?>)</span>
    <div><?php echo cleanOut($trow->body);?></div>
  </div>
  <?php endforeach;?>
  <?php endif;?>
</div>
<script type="text/javascript">
// <![CDATA[
function loadEntryList() {
	$.ajax({
		type: 'post',
		url: "controller.php",
		data: {
			loadReplyEntries :1,
			id: $.url(true).param('id')
		},
		cache: false,
		success: function (html) {
			$("#replydata").html(html);
		}
	});
}
  $(document).ready(function() {
	  $('#send-reply').click(function () {
		  var str = $("#reply_form").serialize();
		  $.ajax({
			  type: 'post',
			  url: "controller.php",
			  data: str,
			  success: function (res) {
				  $("#msgholder").html(res);
				  setTimeout(function () {
					  $(loadEntryList()).fadeIn("slow");
				  }, 2000);
			  }
		  });
		  return false;
	  });
  });
// ]]>
</script>
<?php break;?>
<?php case"add": ?>
<?php $plist = $content->getProjectList();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->SUP_INFO2 . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->SUP_SUB2;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->INVC_CLIENTSELECT;?></label>
        <select name="client_id">
        <option value="">--- <?php echo Lang::$word->INVC_CLIENTSELECT;?> ---</option>
          <?php foreach ($user->getUserList("1") as $crow):?>
          <option value="<?php echo $crow->id;?>"><?php echo Core::renderName($crow);?></option>
          <?php endforeach;?>
          <?php unset($crow);?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->SUP_PRIORITY;?></label>
        <select name="priority">
          <?php echo $content->ticketPriorityList();?>
        </select>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->SUP_SUBJECT;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="subject" placeholder="<?php echo Lang::$word->SUP_SUBJECT;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->SUP_ASSIGNED;?></label>
        <select name="staff_id">
          <?php foreach ($user->getUserList("9' or userlevel = '5") as $srow):?>
          <option value="<?php echo $srow->id;?>"><?php echo Core::renderName($srow);?></option>
          <?php endforeach;?>
          <?php unset($srow);?>
        </select>
      </div>
    </div>
    <div class="four fields">
      <div class="field">
        <label><?php echo Lang::$word->TASK_SELPROJ;?></label>
        <select name="project_id">
          <option value="">--- <?php echo Lang::$word->TASK_SELPROJ;?> ---</option>
          <?php if ($plist):?>
          <?php foreach ($plist as $prow):?>
          <option value="<?php echo $prow->id;?>"><?php echo $prow->title;?></option>
          <?php endforeach;?>
          <?php unset($prow);?>
          <?php endif;?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->SUP_STATUS;?></label>
        <select name="status">
          <?php echo $content->ticketStatusList();?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->SUP_NOTIFY_C;?></label>
        <label class="checkbox">
          <input type="checkbox" name="notify_c" value="1">
          <i></i><?php echo Lang::$word->YES;?></label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PROJ_NOTIFY;?></label>
        <label class="checkbox">
          <input type="checkbox" name="notify_s" value="1">
          <i></i><?php echo Lang::$word->YES;?></label>
      </div>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->SUP_DETAIL;?></label>
      <textarea class="bodypost" name="body" placeholder="<?php echo Lang::$word->SUP_DETAIL;?>"></textarea>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->SUP_CREATE_TICKET;?></button>
    <a href="index.php?do=support" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="addSupportTicket" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php default: ?>
<?php $ticketrow = $content->getSupportTickets();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->SUP_INFO1;?></div>
</div>
<div class="wojo basic block segment"> <a class="wojo basic button push-right" href="index.php?do=support&amp;action=add"><i class="icon add"></i> <?php echo Lang::$word->SUP_NEWTICKET;?></a>
  <h2 class="wojo left floated header"><i class="support icon"></i><?php echo Lang::$word->SUP_SUB1;?></h2>
</div>
<div class="vspace">
  <div class="wojo negative circular label"></div>
  <small><?php echo Lang::$word->SUP_OPEN;?></small>
  <div class="wojo positive circular label"></div>
  <small><?php echo Lang::$word->SUP_CLOSED;?></small>
  <div class="wojo warning circular label"></div>
  <small><?php echo Lang::$word->SUP_PENDING;?></small> </div>
<table class="wojo sortable table">
  <thead>
    <tr>
      <th data-sort="string"></th>
      <th data-sort="string"><?php echo Lang::$word->SUP_SUBJECT;?></th>
      <th data-sort="string"><?php echo Lang::$word->INVC_CNAME;?></th>
      <th data-sort="string"><?php echo Lang::$word->SUP_ASSIGNED;?></th>
      <th data-sort="int"><?php echo Lang::$word->CREATED;?></th>
      <th data-sort="string"><?php echo Lang::$word->SUP_PRIORITY1;?></th>
      <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
    </tr>
  </thead>
  <tbody>
    <?php if(!$ticketrow):?>
    <tr>
      <td colspan="7"><?php echo Filter::msgSingleInfo(Lang::$word->SUP_NOTICKET);?></td>
    </tr>
    <?php else:?>
    <?php foreach ($ticketrow as $row):?>
    <tr>
      <td class="<?php echo ($row->status == "Open" ? "negative" : ($row->status == "Closed" ? "positive" : "warning"));?>"><span class="wojo black label"><?php echo substr($row->fullname, 0, 1);?></span> <small><?php echo $row->tid;?></small></td>
      <td><?php echo $row->subject;?></td>
      <td><a href="index.php?do=clients&amp;action=edit&amp;id=<?php echo $row->client_id;?>"><?php echo Core::renderName($row);?></a></td>
      <td><?php echo (!$row->staffname) ? Lang::$word->SUP_NOASSIGNED : $row->staffname;?></td>
      <td data-sort-value="<?php echo strtotime($row->created);?>"><?php echo Filter::dodate("long_date", $row->created);?></td>
      <td><span class="wojo <?php echo ($row->priority == "High" ? "negative" : ($row->priority == "Medium" ? "warning" : "positive"));?> label"><?php echo $row->priority;?></span></td>
      <td><a href="index.php?do=support&amp;action=edit&amp;id=<?php echo $row->id;?>"><i class="rounded inverted success icon pencil link"></i></a> <a class="delete" data-title="<?php echo Lang::$word->SUP_DELTICKET;?>" data-option="deleteSupportTicket" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->subject;?>"><i class="rounded danger inverted remove icon link"></i></a></td>
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
<?php break;?>
<?php endswitch;?>