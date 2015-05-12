<?php
  /**
   * Project Tasks
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: tasks.php, v3.00 2014-05-08 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
?>
<?php switch(Filter::$action): case "edit": ?>
<?php $row = Core::getRowById("tasks", Filter::$id);?>
<?php if($user->userlevel == 5 and $user->uid != $row->staff_id): print Filter::msgInfo(Lang::$word->ADMINONLY); return; endif;?>
<?php $plist = $content->getProjectList(true);?>
<?php $ulevel = ($user->userlevel == 5) ? 5 : "9' or userlevel = '5";?>
<?php $stafflist = $user->getUserList($ulevel, true);?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->TASK_INFO . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->TASK_SUB . $row->title;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="field">
      <label><?php echo Lang::$word->TASK_NAME;?></label>
      <label class="input"><i class="icon-append icon asterisk"></i>
        <input type="text" value="<?php echo $row->title;?>" name="title">
      </label>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->TASK_START;?></label>
        <label class="input"><i class="icon-append icon calendar"></i>
          <input type="text" data-datepicker="true" data-value="<?php echo $row->created;?>" value="<?php echo $row->created;?>" name="created">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->TASK_DUE;?></label>
        <label class="input"><i class="icon-append icon calendar"></i>
          <input type="text" data-datepicker="true" data-value="<?php echo $row->duedate;?>" value="<?php echo $row->duedate;?>" name="duedate">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->TASK_ASSIGNED;?></label>
        <select name="staff_id">
          <?php foreach ($stafflist as $srow):?>
          <option value="<?php echo $srow->id;?>"<?php if($srow->id == $row->staff_id)echo ' selected="selected"';?>><?php echo Core::renderName($srow);?></option>
          <?php endforeach;?>
          <?php unset($srow);?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->TASK_SELPROJ;?></label>
        <select name="project_id">
          <option value="">--- <?php echo Lang::$word->TASK_SELPROJ;?> ---</option>
          <?php if ($plist):?>
          <?php foreach ($plist as $prow):?>
          <option value="<?php echo $prow->id;?>"<?php if($prow->id == $row->project_id) echo ' selected="selected"';?>><?php echo $prow->title;?></option>
          <?php endforeach;?>
          <?php unset($prow);?>
          <?php endif;?>
        </select>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->TASK_VISIBLE;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="client_access" value="1" <?php getChecked($row->client_access, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="client_access" value="0" <?php getChecked($row->client_access, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->TASK_COMPLETED;?></label>
        <label class="input">
          <input type="text" class="slrange" name="progress" value="<?php echo $row->progress;?>">
        </label>
      </div>
    </div>
    <?php echo $content->rendertCustomFields('t', $row->custom_fields);?>
    <div class="field">
      <textarea class="bodypost" name="details"><?php echo $row->details;?></textarea>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->TASK_UPDATE;?></button>
    <a href="index.php?do=tasks" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processProjectTask" type="hidden" value="1">
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
  </form>
</div>
<div id="msgholder"></div>
<script type="text/javascript">
  $(document).ready(function() {
    $("input[name=progress]").ionRangeSlider({
		min: 0,
		max: 100,
        step: 5,
		postfix: " %",
        type: 'single',
        hasGrid: true
    });
});
</script>
<?php break;?>
<?php case"add": ?>
<?php $plist = $content->getProjectList(true);?>
<?php $templist = $content->getTaskTemplates();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->TASK_INFO1 . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->TASK_SUB1;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->MENU_TT;?></label>
        <select name="select" id="templatelist">
          <option value="0">--- <?php echo Lang::$word->TASK_TEMPLATES;?> ---</option>
          <?php if($templist):?>
          <?php foreach($templist as $tlist):?>
          <option value="<?php echo $tlist->id;?>"><?php echo $tlist->title;?></option>
          <?php endforeach;?>
          <?php endif;?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->TASK_NAME;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" placeholder="<?php echo Lang::$word->TASK_NAME;?>" name="title">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->TASK_START;?></label>
        <label class="input"><i class="icon-append icon calendar"></i>
          <input type="text" data-datepicker="true" data-value="<?php echo date('Y-m-d');?>" value="<?php echo date('Y-m-d');?>" name="created">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->TASK_DUE;?></label>
        <label class="input"><i class="icon-append icon calendar"></i>
          <input type="text" data-datepicker="true" data-value="<?php echo date('Y-m-d');?>" value="<?php echo date('Y-m-d');?>" name="duedate">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->TASK_ASSIGNED;?></label>
        <select name="staff_id">
          <?php $ulevel = ($user->userlevel == 5) ? 5 : "9' or userlevel = '5";?>
          <?php $stafflist = $user->getUserList($ulevel, true);?>
          <?php foreach ($stafflist as $srow):?>
          <option value="<?php echo $srow->id;?>"><?php echo Core::renderName($srow);?></option>
          <?php endforeach;?>
          <?php unset($srow);?>
        </select>
      </div>
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
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->TASK_VISIBLE;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="client_access" value="1">
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="client_access" value="0" checked="checked">
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->TASK_NOTIFY;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="notify_staff" value="1">
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="notify_staff" value="0" checked="checked">
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->TASK_COMPLETED;?></label>
      <label class="input">
        <input type="text" class="slrange" name="progress" value="0">
      </label>
    </div>
    <div class="wojo divider"></div>
    <?php echo $content->rendertCustomFields('t', false);?>
    <div class="field">
      <textarea class="bodypost" name="details"></textarea>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->TASK_ADD;?></button>
    <a href="index.php?do=tasks" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processProjectTask" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<script type="text/javascript">
// <![CDATA[  
$(document).ready(function () {
	$("input[name=progress]").ionRangeSlider({
		min: 0,
		max: 100,
		step: 5,
		postfix: " %",
		type: 'single',
		hasGrid: true
	});

	$('#templatelist').change(function () {
		var option = $(this).val();
		$.ajax({
			type: "get",
			url: "controller.php",
			data: {
				'getTaskTemplateList': 1,
				id:option
				},
			dataType: "json",
			success: function (json) {
				if (json == false) {
					$("input[name=title]").val('');
					$('.bodypost').redactor('set', "");
				} else {
					$("input[name=title]").val(json.title);
					$('.bodypost').redactor('set', json.details);

				}
			}
		});
	});
});
// ]]>
</script>
<?php break;?>
<?php default: ?>
<?php $taskrow = $content->getProjectTasks();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->TASK_INFO2;?></div>
</div>
<div class="wojo basic block segment">
  <?php if($user->userlevel == 9):?>
  <a class="wojo basic button push-right" href="index.php?do=tasks&amp;action=add"><i class="icon add"></i> <?php echo Lang::$word->TASK_ADD;?></a>
  <?php endif;?>
  <h2 class="wojo left floated header"><i class="tasks icon"></i><?php echo Lang::$word->TASK_SUB2;?></h2>
</div>
<div class="wojo small form basic segment">
  <div class="two fields">
    <div class="field">
      <select name="select" id="taskfilter">
        <option value="NA">--- <?php echo Lang::$word->TASK_VIEWALL;?> ---</option>
        <option value="pending"<?php if(get('sort') == 'pending') echo ' selected="selected"';?>><?php echo Lang::$word->TASK_PENDING;?></option>
        <option value="completed"<?php if(get('sort') == 'completed') echo ' selected="selected"';?>><?php echo Lang::$word->TASK_COMPLETED;?></option>
      </select>
    </div>
    <div class="field">
      <div class="two fields">
        <div class="field"> <?php echo $pager->items_per_page();?> </div>
        <div class="field"> <?php echo $pager->jump_menu();?> </div>
      </div>
    </div>
  </div>
</div>
<table class="wojo sortable table">
  <thead>
    <tr>
      <th data-sort="string"><?php echo Lang::$word->TASK_NAME;?></th>
      <th data-sort="string"><?php echo Lang::$word->PROJ_NAME;?></th>
      <th data-sort="string"><?php echo Lang::$word->TASK_PROGRESS;?></th>
      <th data-sort="int"><?php echo Lang::$word->CREATED;?></th>
      <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
    </tr>
  </thead>
  <?php if(!$taskrow):?>
  <tr>
    <td colspan="5"><?php echo Filter::msgSingleInfo(Lang::$word->TASK_NOTASKS);?></td>
  </tr>
  <?php else:?>
  <?php foreach ($taskrow as $row):?>
  <tr>
    <td><?php echo $row->title;?></td>
    <td><a href="index.php?do=projects&amp;action=edit&amp;id=<?php echo $row->pid;?>"><?php echo $row->ptitle;?></a></td>
    <td><?php echo $content->progressBarStatus($row->progress);?></td>
    <td data-sort-value="<?php echo strtotime($row->created);?>"><?php echo Filter::dodate("short_date", $row->created);?></td>
    <td><a href="index.php?do=tasks&amp;action=edit&amp;id=<?php echo $row->id;?>"><i class="rounded inverted success icon pencil link"></i></a> <a class="delete" data-title="<?php echo Lang::$word->TASK_DELTASK;?>" data-option="deleteProjectTask" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->title;?>"><i class="rounded danger inverted remove icon link"></i></a></td>
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
    $('#taskfilter').change(function () {
		var res = $("#taskfilter option:selected").val();
		(res == "NA" ) ? window.location.href = 'index.php?do=tasks' : window.location.href = 'index.php?do=tasks&sort=' + res;
    })
});
// ]]>
</script>
<?php break;?>
<?php endswitch;?>