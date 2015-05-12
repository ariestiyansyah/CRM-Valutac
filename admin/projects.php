<?php
  /**
   * Projects
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: projects.php, v3.00 2014-05-08 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Filter::$action): case "edit": ?>
<?php $row = Core::getRowById("projects", Filter::$id);?>
<?php if(!$user->checkProjectAccess($row->id)): print Filter::msgInfo(Lang::$word->NOACCESS, false); return; endif;?>
<?php $ptype = $content->getProjectTypes();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->PROJ_INFO . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?> </div>
</div>
<div class="wojo basic block segment">
  <ul class="wojo tabs" id="tabs">
    <li><a data-tab="#general"><?php echo Lang::$word->PROJECT;?></a></li>
    <li><a data-tab="#tasks"><?php echo Lang::$word->TASKS;?></a></li>
    <li><a data-tab="#submissions"><?php echo Lang::$word->SUBMISSIONS;?></a></li>
    <li><a data-tab="#files"><?php echo Lang::$word->FILES;?></a></li>
  </ul>
  <h2 class="wojo left floated header"><?php echo Lang::$word->PROJ_SUB . $row->title;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div id="general" class="wojo tab content">
      <div class="two fields">
        <div class="field">
          <label><?php echo Lang::$word->PROJ_NAME;?></label>
          <label class="input"><i class="icon-append icon asterisk"></i>
            <input type="text" value="<?php echo $row->title;?>" name="title">
          </label>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->PROJ_TYPE;?></label>
          <select name="project_type">
            <option value=""><?php echo Lang::$word->PROJ_TYPE_SEL;?></option>
            <?php if ($ptype):?>
            <?php foreach ($ptype as $prow):?>
            <option value="<?php echo $prow->id;?>"<?php if($prow->id == $row->project_type) echo ' selected="selected"';?>><?php echo $prow->title;?></option>
            <?php endforeach;?>
            <?php unset($prow);?>
            <?php endif;?>
          </select>
        </div>
      </div>
      <div class="three fields">
        <div class="field">
          <label><?php echo Lang::$word->PROJ_PRICE;?></label>
          <label class="input"><i class="icon-append icon asterisk"></i><i class="icon-prepend icon dollar"></i>
            <input type="text" value="<?php echo ($user->userlevel == 9)? $row->cost : '-/-';?>" name="cost">
          </label>
          <?php if($user->userlevel == 5):?>
          <input type="hidden" name="cost" value="<?php echo $row->cost;?>">
          <?php endif;?>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->PROJ_START;?></label>
          <label class="input"><i class="icon-append icon calendar"></i>
            <input type="text" data-datepicker="true" data-value="<?php echo $row->start_date;?>" value="<?php echo $row->start_date;?>" name="start_date">
          </label>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->PROJ_END;?></label>
          <label class="input"><i class="icon-append icon calendar"></i>
            <input type="text" data-datepicker="true" data-value="<?php echo $row->end_date;?>" value="<?php echo $row->end_date;?>" name="end_date">
          </label>
        </div>
      </div>
      <div class="wojo divider"></div>
      <?php echo $content->rendertCustomFields('p', $row->custom_fields);?>
      <div class="field">
        <textarea class="bodypost" name="body"><?php echo $row->body;?></textarea>
      </div>
      <div class="three fields">
        <div class="field">
          <label><?php echo Lang::$word->PROJ_BILLSTSTUS;?></label>
          <?php echo $content->progressBarBilling($row->b_status, $row->cost);?> </div>
        <div class="field">
          <label><?php echo Lang::$word->PROJ_COMPSTSTUS;?></label>
          <input type="text" class="slrange" name="p_status" value="<?php echo $row->p_status;?>">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->PROJ_MANAGER;?></label>
          <select name="staff_id[]" multiple="multiple">
            <?php foreach ($user->getUserList("9' or userlevel = '5") as $srow):?>
            <option value="<?php echo $srow->id; ?>"<?php if(in_array($srow->id, Core::_explode($row->staff_id))):?> selected="selected"<?php endif; ?>><?php echo $srow->fullname;?> </option>
            <?php endforeach;?>
          </select>
        </div>
      </div>
    </div>
    <div id="tasks" class="wojo tab content">
      <h3><i class="icon reorder"></i> <?php echo Lang::$word->TASK_SUB2;?></h3>
      <?php $taskrow = $content->getTasksByProject();?>
      <?php if(!$taskrow):?>
      <?php echo Filter::msgSingleInfo(Lang::$word->TASK_NOTASKS);?>
      <?php else:?>
      <table class="wojo basic table">
        <thead>
          <tr>
            <th><?php echo Lang::$word->TASK_NAME;?></th>
            <th><?php echo Lang::$word->TASK_PROGRESS;?></th>
            <th><?php echo Lang::$word->CREATED;?></th>
            <th><?php echo Lang::$word->ACTIONS;?></th>
          </tr>
        </thead>
        <?php foreach ($taskrow as $trow):?>
        <tr>
          <td><?php echo $trow->title;?></td>
          <td><?php echo $content->progressBarStatus($trow->progress);?></td>
          <td><?php echo Filter::dodate("short_date", $trow->created);?></td>
          <td><a href="index.php?do=tasks&amp;action=edit&amp;id=<?php echo $trow->id;?>"><i class="rounded inverted success icon pencil link"></i></a></td>
        </tr>
        <?php endforeach;?>
        <?php unset($trow);?>
      </table>
      <?php endif;?>
    </div>
    <div id="submissions" class="wojo tab content">
      <h3><i class="icon reorder"></i> <?php echo Lang::$word->SUBS_SUB21;?></h3>
      <?php $subrow = $content->getProjectSubmissions();?>
      <?php if(!$subrow):?>
      <?php echo Filter::msgSingleInfo(Lang::$word->SUBS_NOSUBS);?>
      <?php else:?>
      <table class="wojo basic table">
        <thead>
          <tr>
            <th><?php echo Lang::$word->SUBS_NAME;?></th>
            <th><?php echo Lang::$word->SUBS_TYPE;?></th>
            <th><?php echo Lang::$word->STATUS;?></th>
            <th><?php echo Lang::$word->ACTIONS;?></th>
          </tr>
        </thead>
        <?php foreach ($subrow as $brow):?>
        <tr>
          <td><?php echo $brow->title;?></td>
          <td><span class="wojo black label"><?php echo $brow->s_type;?></span></td>
          <td><?php echo $content->projectSubmissionStatus($brow->status);?></td>
          <td><a href="index.php?do=submissions&amp;action=edit&amp;id=<?php echo $brow->id;?>"><i class="rounded inverted success icon pencil link"></i></a></td>
        </tr>
        <?php endforeach;?>
        <?php unset($brow);?>
      </table>
      <?php endif;?>
    </div>
    <div id="files" class="wojo tab content">
      <h3><i class="icon reorder"></i> <?php echo Lang::$word->FILE_SUB2;?></h3>
      <?php $subrow = $content->getProjectSubmissions();?>
      <?php $filerow = $content->getFilesByProject();?>
      <?php if(!$filerow):?>
      <?php echo Filter::msgSingleInfo(Lang::$word->FILE_NOFILES);?>
      <?php else:?>
      <table class="wojo basic table">
        <thead>
          <tr>
            <th><?php echo Lang::$word->FILE_NAME;?></th>
            <th><?php echo Lang::$word->FILESIZE;?></th>
            <th><?php echo Lang::$word->ACTIONS;?></th>
          </tr>
        </thead>
        <?php foreach ($filerow as $frow):?>
        <?php $file_info = is_file(UPLOADS . 'data/' . $frow->filename) ? getimagesize(UPLOADS . 'data/' . $frow->filename) : null;?>
        <?php $is_image = (!empty($file_info)) ? true : false;?>
        <tr>
          <td><?php echo $frow->title;?></td>
          <td><?php echo getSize($frow->filesize);?></td>
          <td><?php if($is_image):?>
            <a href="<?php echo UPLOADURL . 'data/' . $frow->filename;?>" class="lightbox" data-content="<?php echo Lang::$word->VIEW.': '.$frow->title;?>"><i class="rounded inverted success icon photo link"></i></a>
            <?php else:?>
            <a href="<?php echo UPLOADURL . 'data/' . $frow->filename;?>" data-content="<?php echo Lang::$word->DOWNLOAD;?>"><i class="rounded inverted info icon download cloud link"></i></a>
            <?php endif;?>
            <a href="iindex.php?do=files&amp;action=edit&amp;id=<?php echo $frow->id;?>"><i class="rounded inverted success icon pencil link"></i></a></td>
        </tr>
        <?php endforeach;?>
        <?php unset($frow);?>
      </table>
      <?php endif;?>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->PROJ_UPDATE;?></button>
    <a href="index.php?do=projects" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processProject" type="hidden" value="1">
    <input name="client_id" type="hidden" value="<?php echo $row->client_id;?>" />
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
  </form>
</div>
<div id="msgholder"></div>
<script type="text/javascript"> 
// <![CDATA[
  $(document).ready(function() {
    $("input[name=p_status]").ionRangeSlider({
		min: 0,
		max: 100,
        step: 5,
		postfix: " %",
        type: 'single',
        hasGrid: true
    });
});
// ]]>
</script>
<?php break;?>
<?php case"add":?>
<?php if($user->userlevel == 5): print Filter::msgInfo(Lang::$word->PROJ_NOPERM, false); return; endif;?>
<?php $ptype = $content->getProjectTypes();?>
<?php $userlist = $user->getUserList(1);?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->PROJ_INFO1 . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?> </div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->PROJ_SUB1;?></h2>
</div>
<div class="wojo form segment">
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->PROJ_NAME;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" placeholder="<?php echo Lang::$word->PROJ_NAME;?>" name="title">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->INVC_CNAME;?></label>
        <select name="client_id">
          <option value="">--- <?php echo Lang::$word->INVC_CLIENTSELECT;?> ---</option>
          <?php if($userlist):?>
          <?php foreach ($userlist as $crow):?>
          <option value="<?php echo $crow->id;?>"><?php echo Core::renderName($crow);?></option>
          <?php endforeach;?>
          <?php unset($crow);?>
          <?php endif;?>
        </select>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->PROJ_TYPE;?></label>
        <select name="project_type">
          <option value=""><?php echo Lang::$word->PROJ_TYPE_SEL;?></option>
          <?php if ($ptype):?>
          <?php foreach ($ptype as $prow):?>
          <option value="<?php echo $prow->id;?>"><?php echo $prow->title;?></option>
          <?php endforeach;?>
          <?php unset($prow);?>
          <?php endif;?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PROJ_PRICE;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i><i class="icon-prepend icon dollar"></i>
          <input type="text" placeholder="<?php echo Lang::$word->PROJ_PRICE;?>" name="cost">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->PROJ_START;?></label>
        <label class="input"><i class="icon-append icon calendar"></i>
          <input type="text" data-datepicker="true" data-value="<?php echo date('Y-m-d');?>" value="<?php echo date('Y-m-d');?>" name="start_date">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PROJ_END;?></label>
        <label class="input"><i class="icon-append icon calendar"></i>
          <input type="text" data-datepicker="true" data-value="<?php echo date('Y-m-d');?>" value="<?php echo date('Y-m-d');?>" name="end_date">
        </label>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <?php echo $content->rendertCustomFields('p', false);?>
    <div class="field">
      <textarea class="bodypost" name="body"></textarea>
    </div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->PROJ_COMPSTSTUS;?></label>
        <input class="slrange" type="text" id="p_status" name="p_status">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PROJ_MANAGER;?></label>
        <select name="staff_id[]" multiple="multiple">
          <?php foreach ($user->getUserList("9' or userlevel = '5") as $srow):?>
          <option value="<?php echo $srow->id; ?>"><?php echo $srow->fullname;?> </option>
          <?php endforeach;?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PROJ_NOTIFY;?></label>
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
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->PROJ_ADD;?></button>
    <a href="index.php?do=projects" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processProject" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<script type="text/javascript">
  $(document).ready(function() {
    $("input[name=p_status]").ionRangeSlider({
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
<?php case"details":?>
<?php $row = Core::getRowById("projects", Filter::$id);?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->PROJ_INFO2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><i class="users icon"></i><?php echo Lang::$word->PROJ_SUB2 . $row->title;?></h2>
</div>
<table class="wojo basic table">
  <tr>
    <td><?php echo Lang::$word->PROJ_NAME;?>:</td>
    <td><?php echo $row->title;?></td>
  </tr>
  <tr>
    <td><?php echo Lang::$word->PROJ_TYPE;?>:</td>
    <td><?php echo getValue("title","project_types","id = '".$row->project_type."'");?></td>
  </tr>
  <?php if($user->userlevel == 9):?>
  <tr>
    <td><?php echo Lang::$word->PROJ_PRICE;?>:</td>
    <td><?php echo $core->formatMoney($row->cost);?></td>
  </tr>
  <?php endif;?>
  <tr>
    <td><?php echo Lang::$word->PROJ_START;?>:</td>
    <td><?php echo Filter::dodate("short_date", $row->start_date);?></td>
  </tr>
  <tr>
    <td><?php echo Lang::$word->PROJ_END;?>:</td>
    <td><?php echo Filter::dodate("short_date", $row->end_date);?></td>
  </tr>
  <tr>
    <td><?php echo Lang::$word->PROJ_DESC;?>:</td>
    <td><?php echo cleanOut($row->body);?></td>
  </tr>
  <?php echo $content->rendertCustomFieldsView('p', $row->custom_fields);?>
  <tr>
    <td><?php echo Lang::$word->PROJ_STATUS;?>:</td>
    <td><?php echo $content->progressBarStatus($row->p_status);?></td>
  </tr>
  <tr>
    <td><?php echo Lang::$word->PROJ_BILLSTSTUS;?>:</td>
    <td><?php echo $content->progressBarBilling($row->b_status, $row->cost);?></td>
  </tr>
  <tr>
    <td><?php echo Lang::$word->PROJ_MANAGER;?>:</td>
    <td><?php echo $user->doUserNames($row->staff_id);?></td>
  </tr>
</table>
<div class="wojo block header"><?php echo Lang::$word->PROJ_TASKS;?></div>
<?php $taskrow = $content->getTasksByProject();?>
<table class="wojo basic table">
  <thead>
    <tr>
      <th><?php echo Lang::$word->TASK_NAME;?></th>
      <th><?php echo Lang::$word->TASK_PROGRESS;?></th>
    </tr>
  </thead>
  <?php if(!$taskrow):?>
  <tr>
    <td colspan="2"><?php echo Filter::msgSingleInfo(Lang::$word->TASK_NOTASKS);?></td>
  </tr>
  <?php else:?>
  <?php foreach ($taskrow as $trow):?>
  <tr>
    <td><a href="index.php?do=tasks&amp;action=edit&amp;id=<?php echo $trow->id;?>"><?php echo $trow->title;?></a></td>
    <td><?php echo $content->progressBarStatus($trow->progress);?></td>
  </tr>
  <?php endforeach;?>
  <?php unset($trow);?>
  <?php endif;?>
</table>
<div class="wojo block header"><?php echo Lang::$word->PROJ_SUBS;?></div>
<?php $subrow = $content->getProjectSubmissions();?>
<table class="wojo basic table">
  <thead>
    <tr>
      <th><?php echo Lang::$word->SUBS_NAME;?></th>
      <th><?php echo Lang::$word->SUBS_TYPE;?></th>
    </tr>
  </thead>
  <?php if(!$subrow):?>
  <tr>
    <td colspan="2"><?php echo Filter::msgSingleInfo(Lang::$word->SUBS_NOSUBS);?></td>
  </tr>
  <?php else:?>
  <?php foreach ($subrow as $brow):?>
  <tr>
    <td><a href="index.php?do=submissions&amp;action=edit&amp;id=<?php echo $brow->id;?>"><?php echo $brow->title;?></a></td>
    <td><?php echo $brow->s_type;?></td>
  </tr>
  <?php endforeach;?>
  <?php unset($brow);?>
  <?php endif;?>
</table>
<div class="wojo block header"><?php echo Lang::$word->PROJ_FILES;?></div>
<?php $filerow = $content->getFilesByProject();?>
<table class="wojo basic table">
  <thead>
    <tr>
      <th><?php echo Lang::$word->FILE_NAME;?></th>
      <th><?php echo Lang::$word->FILESIZE;?></th>
      <th><?php echo Lang::$word->DOWNLOAD;?></th>
    </tr>
  </thead>
  <?php if(!$filerow):?>
  <tr>
    <td colspan="3"><?php echo Filter::msgSingleInfo(Lang::$word->FILE_NOFILES);?></td>
  </tr>
  <?php else:?>
  <?php foreach ($filerow as $frow):?>
  <?php $file_info = is_file(UPLOADS . 'data/' . $frow->filename) ? getimagesize(UPLOADS . 'data/' . $frow->filename) : null;?>
  <?php $is_image = (!empty($file_info)) ? true : false;?>
  <tr>
    <td><a href="index.php?do=files&amp;action=edit&amp;id=<?php echo $frow->id;?>"><?php echo $frow->title;?></a></td>
    <td><?php echo getSize($frow->filesize);?></td>
    <td><?php if($is_image):?>
      <a href="<?php echo UPLOADURL . 'data/' . $frow->filename;?>" class="lightbox" data-content="<?php echo Lang::$word->VIEW.': '.$frow->title;?>"><i class="rounded inverted success icon photo link"></i></a>
      <?php else:?>
      <a href="<?php echo UPLOADURL . 'data/' . $frow->filename;?>" data-content="<?php echo Lang::$word->DOWNLOAD;?>"><i class="rounded inverted info icon download cloud link"></i></a>
      <?php endif;?></td>
  </tr>
  <?php endforeach;?>
  <?php unset($frow);?>
  <?php endif;?>
</table>
<?php break;?>
<?php default: ?>
<?php $projectrow = $content->getProjects();?>
<?php $userlist = ($user->userlevel == 9) ? $user->getUserList(1) : $user->getUserListForStaff();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->PROJ_INFO3;?></div>
</div>
<div class="wojo basic block segment">
  <?php if($user->userlevel == 9):?>
  <a class="wojo basic button push-right" href="index.php?do=projects&amp;action=add"><i class="icon add"></i> <?php echo Lang::$word->PROJ_ADD;?></a>
  <?php endif;?>
  <h2 class="wojo left floated header"><i class="send icon"></i><?php echo Lang::$word->PROJ_SUB3;?></h2>
</div>
<div class="wojo small form basic segment">
  <div class="two fields">
    <div class="field">
      <select name="select" id="clientfilter">
        <option value="NA">--- <?php echo Lang::$word->INVC_CLIENTSELECT;?> ---</option>
        <?php if($userlist):?>
        <?php foreach ($userlist as $crow):?>
        <option value="<?php echo $crow->id;?>"<?php if($crow->id == get('sort')) echo 'selected="selected"';?>><?php echo Core::renderName($crow);?></option>
        <?php endforeach;?>
        <?php unset($crow);?>
        <?php endif;?>
      </select>
    </div>
    <div class="field">
      <div class="two fields">
        <div class="field"> <?php echo $pager->items_per_page();?> </div>
        <div class="field"> <?php echo $pager->jump_menu();?> </div>
      </div>
    </div>
  </div>
  <div class="wojo divider"></div>
  <div id="abc" class="content-center"> <?php echo alphaBits('index.php?do=projects', "letter");?> </div>
  <div class="wojo fitted divider"></div>
</div>
<table class="wojo sortable table">
  <thead>
    <tr>
      <th data-sort="string"><?php echo Lang::$word->PROJ_NAME;?></th>
      <th data-sort="string"><?php echo Lang::$word->INVC_CNAME;?></th>
      <th data-sort="string"><?php echo Lang::$word->PROJ_MANAGER;?></th>
      <th data-sort="int"><?php echo Lang::$word->CREATED;?></th>
      <th class="disabled"><?php echo Lang::$word->STATUS;?></th>
      <th class="disabled"><?php echo Lang::$word->BILLING;?></th>
      <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
    </tr>
  </thead>
  <tbody>
    <?php if(!$projectrow):?>
    <tr>
      <td colspan="7"><?php echo Filter::msgSingleInfo(Lang::$word->PROJ_NOPROJECT);?></td>
    </tr>
    <?php else:?>
    <?php foreach ($projectrow as $row):?>
    <tr>
      <td><?php echo $row->title;?></td>
      <td><?php echo Core::renderName($row);?></td>
      <td><?php echo $row->staffname;?></td>
      <td data-sort-value="<?php echo strtotime($row->start_date);?>"><?php echo Filter::dodate("short_date", $row->start_date);?></td>
      <td><?php echo $content->progressBarStatus($row->p_status);?></td>
      <td><?php if($row->recurring):?>
        <?php echo $content->progressBarRecurring();?>
        <?php else:?>
        <?php echo $content->progressBarBilling($row->b_status, $row->cost);?>
        <?php endif;?></td>
      <td><div class="wojo right pointing dropdown"> <i class="rounded inverted info settings link icon"></i>
          <div class="menu"> <a class="item" href="index.php?do=projects&amp;action=details&amp;id=<?php echo $row->pid;?>"><i class="icon suitcase"></i><?php echo Lang::$word->PROJ_DETAILS;?></a>
            <?php if($user->userlevel == 9):?>
            <a class="item" href="index.php?do=overview&amp;id=<?php echo $row->uid;?>"><i class="icon user"></i><?php echo Lang::$word->PROJ_CLOVERVIEW;?></a>
            <?php if ($row->b_status == $row->cost):?>
            <a class="item" href="index.php?do=invoices&amp;id=<?php echo $row->pid;?>"><i class="icon check"></i><?php echo Lang::$word->PROJ_PAID;?></a>
            <?php else:?>
            <a class="item" href="index.php?do=invoices&amp;action=add&amp;id=<?php echo $row->pid;?>"><i class="icon bookmark"></i><?php echo Lang::$word->PROJ_INVOICE;?></a>
            <?php endif;?>
            <a class="item" href="index.php?do=timebilling&amp;action=add&amp;id=<?php echo $row->pid;?>"><i class="icon time"></i><?php echo Lang::$word->BILL_ADD_B;?></a>
            <?php endif;?>
            <a class="item" href="index.php?do=submissions&amp;id=<?php echo $row->pid;?>" data-content=""><i class="icon plus"></i><?php echo Lang::$word->PROJ_SUBS;?></a> </div>
        </div>
        <a href="index.php?do=projects&amp;action=edit&amp;id=<?php echo $row->pid;?>"><i class="rounded inverted success icon pencil link"></i></a>
        <?php if($user->userlevel == 9):?>
        <a class="delete" data-title="<?php echo Lang::$word->PROJ_DELETE;?>" data-option="deleteProject" data-id="<?php echo $row->pid;?>" data-name="<?php echo $row->title;?>"><i class="rounded danger inverted remove icon link"></i></a>
        <?php endif;?></td>
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
    $("#searchfield").on('keyup', function () {
        var srch_string = $(this).val();
        var data_string = 'projectSearch=' + srch_string;
        if (srch_string.length > 4) {
            $.ajax({
                type: "post",
                url: "controller.php",
                data: data_string,
                success: function (res) {
                    $('#suggestions').html(res).show();
                    $("input").blur(function () {
                        $('#suggestions').fadeOut();
                    });
                }
            });
        }
        return false;
    });
    $('#clientfilter').change(function () {
		var res = $("#clientfilter option:selected").val();
		(res == "NA" ) ? window.location.href = 'index.php?do=projects' : window.location.href = 'index.php?do=projects&sort=' + res;
    })
});
// ]]>
</script>
<?php break;?>
<?php endswitch;?>