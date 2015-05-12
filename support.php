<?php
  /**
   * Support
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: support.php, v3.00 2014-05-08 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Filter::$action): case "view": ?>
<?php $row = $user->getSupportTicketById();?>
<?php $resrow = $user->getResponseByTicketId();?>
<div class="wojo tertiary segment">
  <?php if(!$row):?>
  <?php echo Filter::msgSingleInfo(Lang::$word->SUP_NOTICKET);?>
  <?php else:?>
  <h3 class="wojo header"><?php echo Lang::$word->FSUP_SUB;?><small class="wojo positive label push-right"><?php echo $row->tid;?></small></h3>
  <div class="wojo basic message"> <i class="pin icon"></i> <?php echo Lang::$word->FSUP_INFO;?> </div>
  <table class="wojo basic table">
    <tr>
      <td><?php echo Lang::$word->SUP_PRIORITY;?>: </td>
      <td><?php echo Content::renderTicketPriorityList($row->priority);?></td>
    </tr>
    <tr>
      <td><?php echo Lang::$word->SUP_STATUS;?>:</td>
      <td><?php if($row->status == "Open"):?>
        <div id="ticket-status"><?php echo Lang::$word->OPEN;?> <i class="icon angle right"></i> <a id="close-ticket"><?php echo Lang::$word->FSUP_OPENCLOSE;?></a></div>
        <?php else:?>
        <?php Content::renderTicketStatusList($row->status);?>
        <?php endif;?></td>
    </tr>
    <tr>
      <td><?php echo Lang::$word->SUP_ASSIGNED;?>:</td>
      <td><?php echo Core::renderName($row);?></td>
    </tr>
    <tr>
      <td><?php echo Lang::$word->PROJ_NAME;?>:</td>
      <td><?php echo $row->ptitle;?></td>
    </tr>
    <tr>
      <td><?php echo Lang::$word->SUP_SUBJECT;?>:</td>
      <td><?php echo $row->subject;?></td>
    </tr>
    <tr>
      <td><?php echo Lang::$word->SUP_DETAIL;?>:</td>
      <td><?php echo cleanOut($row->body);?></td>
    </tr>
    <tr>
      <td colspan="2"><a href="account.php?do=support" class="wojo basic button"><?php echo Lang::$word->CANCEL;?></a></td>
    </tr>
  </table>
</div>
<div class="wojo black inverted segment">
  <div id="replydata" class="scrollbox">
    <?php if(!$resrow):?>
    <div class="emptyticket"></div>
    <?php else:?>
    <?php foreach($resrow as $trow):?>
    <div class="wojo notice black message"> <span class="wojo small positive label"><?php echo Filter::dodate("long_date", $trow->created);?></span> <span class="wojo small <?php echo ($trow->user_type == "client") ? 'warning' : 'purple';?> label"><?php echo Lang::$word->AUTHOR;?>: <?php echo $trow->name;?> (<?php echo $trow->user_type;?>)</span>
      <div><?php echo cleanOut($trow->body);?></div>
    </div>
    <?php endforeach;?>
    <?php endif;?>
  </div>
</div>
<div id="rform" class="wojo form segment">
  <div class="field">
    <p><?php echo Lang::$word->FSUP_SUB1 . $row->subject;?></p>
  </div>
  <form class="wojo form" method="post" id="reply_form" name="reply_form">
    <div class="field">
      <textarea class="bodypost" name="body"></textarea>
    </div>
    <button type="button" id="send-reply" name="doreply" class="wojo button"><?php echo Lang::$word->FSUP_BTNSEND;?></button>
    <input name="replySupportTicket" type="hidden" value="1" />
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
  </form>
</div>
<div id="msgholder"></div>
<script type="text/javascript">
// <![CDATA[
function loadEntryList() {
	$.ajax({
		type: 'post',
		url: SITEURL + "/ajax/controller.php",
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
	  $('#close-ticket').click(function () {
		  var str = 'closeTicket=' + <?php echo Filter::$id;?>;
		  $.ajax({
			  type: 'post',
			  url: SITEURL + "/ajax/controller.php",
			  data: str,
			  success: function () {
				  $("#ticket-status").html("<?php echo Lang::$word->CLOSED;?>");
			  }
		  });
		  return false;
		  
      });
		  
	  $('#send-reply').click(function () {
		  var str = $("#reply_form").serialize();
		  $("#rform").addClass('loading');
		  $.ajax({
			  type: 'post',
			  url: SITEURL + "/ajax/controller.php",
			  data: str,
			  success: function (res) {
				  $("#msgholder").html(res);
				  $("#rform").removeClass('loading');
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
<?php endif;?>
<?php break;?>
<?php case "add": ?>
<?php $plist = $user->getProjects();?>
<div class="wojo tertiary segment form">
  <h3 class="wojo header"><?php echo Lang::$word->FSUP_SUB2;?></h3>
  <p class="wojo basic message"> <i class="pin icon"></i> <?php echo Lang::$word->FSUP_INFO1;?></p>
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->SUP_SUBJECT;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="subject" placeholder="<?php echo Lang::$word->SUP_SUBJECT;?>">
        </label>
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
        <label><?php echo Lang::$word->FILE_ATTACH;?></label>
        <label class="input">
          <input type="file" name="attachment" class="filefield">
        </label>
        <p class="wojo info"><?php echo $core->file_types;?> | <?php echo Lang::$word->CONF_MFS.': '.$core->file_max/1048576;?>mb</p>
      </div>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->SUP_DETAIL;?></label>
      <textarea class="bodypost" name="body" placeholder="<?php echo Lang::$word->SUP_DETAIL;?>"></textarea>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" data-url="/ajax/controller.php" name="dosubmit" class="wojo button"><?php echo Lang::$word->FSUP_SENDBTN;?></button>
    <a href="account.php?do=support" class="wojo basic button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processSupportTicket" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php default: ?>
<?php $ticketrow = $user->getSupportTickets();?>
<div class="wojo tertiary segment">
  <h3 class="wojo header"><?php echo Lang::$word->FSUP_SUB3;?></h3>
  <p class="wojo basic message"> <i class="pin icon"></i> <?php echo Lang::$word->FSUP_INFO2;?></p>
  <a href="account.php?do=support&amp;action=add" class="push-right" data-content="<?php echo Lang::$word->SUP_NEWTICKET;?>"><i class="rounded inverted black icon link add"></i></a>
  <?php if(!$ticketrow):?>
  <?php else:?>
  <div class="vspace">
    <div class="wojo negative circular label"></div>
    <small><?php echo Lang::$word->SUP_OPEN;?></small>
    <div class="wojo positive circular label"></div>
    <small><?php echo Lang::$word->SUP_CLOSED;?></small>
    <div class="wojo warning circular label"></div>
    <small><?php echo Lang::$word->SUP_PENDING;?></small> </div>
  <table class="wojo basic table">
    <thead>
      <tr>
        <th></th>
        <th><?php echo Lang::$word->SUP_SUBJECT;?></th>
        <th><?php echo Lang::$word->SUP_ASSIGNED;?></th>
        <th><?php echo Lang::$word->CREATED;?></th>
        <th><?php echo Lang::$word->SUP_PRIORITY1;?></th>
        <th><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($ticketrow as $row):?>
      <tr>
        <td class="<?php echo ($row->status == "Open" ? "negative" : ($row->status == "Closed" ? "positive" : "warning"));?>"><span class="wojo black label"><?php echo strtoupper(substr(($core->unvsfn) ? $row->username : $row->fullname, 0, 1));?></span> <small><?php echo $row->tid;?></small></td>
        <td><?php echo $row->subject;?></td>
        <td><?php echo (!$row->fullname) ? Lang::$word->SUP_NOASSIGNED : Core::renderName($row);?></td>
        <td><?php echo Filter::doDate("long_date", $row->created);?></td>
        <td><span class="wojo <?php echo ($row->priority == "High" ? "negative" : ($row->priority == "Medium" ? "warning" : "positive"));?> label"><?php echo $row->priority;?></span></td>
        <td><a href="account.php?do=support&amp;action=view&amp;id=<?php echo $row->id;?>"><i class="rounded inverted black icon laptop link"></i></a></td>
      </tr>
      <?php endforeach;?>
      <?php unset($row);?>
    </tbody>
  </table>
  <?php endif;?>
</div>
<?php break;?>
<?php endswitch;?>