<?php
  /**
   * Time Billing
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: timebilling.php, v3.00 2014-05-08 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');  
?>
<?php switch(Filter::$action): case "view": ?>
<?php $prow = Core::getRowById(Content::pTable, Filter::$id);?>
<?php if(!$user->checkProjectAccess($prow->id)): print Filter::msgAlert(Lang::$word->ADMINONLY, false); return; endif;?>
<?php $billingrow = $content->getTimeBillingByProjectId();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->BILL_INFO;?></div>
</div>
<div class="wojo basic block segment">
  <div class="wojo buttons push-right">
    <?php if($user->userlevel == 9):?>
    <a class="wojo info button" href="index.php?do=invoices&amp;action=add&amp;id=<?php echo $prow->id;?>"><i class="icon add"></i> <?php echo Lang::$word->BILL_ADD_I;?></a>
    <?php endif;?>
    <a class="wojo warning button" href="index.php?do=timebilling&amp;action=add&amp;id=<?php echo $prow->id;?>"><i class="icon add"></i> <?php echo Lang::$word->BILL_ADD_B;?></a> </div>
  <h2 class="wojo left floated header"><i class="time icon"></i><?php echo Lang::$word->BILL_SUB . $prow->title;?></h2>
</div>
<table class="wojo sortable table">
  <thead>
    <tr>
      <th data-sort="string"><?php echo Lang::$word->BILL_TNAME;?></th>
      <th data-sort="string"><?php echo Lang::$word->TASK;?></th>
      <th data-sort="int"><?php echo Lang::$word->CREATED;?></th>
      <th data-sort="int"><?php echo Lang::$word->HOURS;?></th>
      <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
    </tr>
  </thead>
  <?php if(!$billingrow):?>
  <tr>
    <td colspan="5"><?php echo Filter::msgSingleInfo(Lang::$word->BILL_NOBILL);?></td>
  </tr>
  <?php else:?>
  <?php foreach ($billingrow as $row):?>
  <tr>
    <td><?php echo $row->title;?></td>
    <td><a href="index.php?do=tasks&amp;action=edit&amp;id=<?php echo $row->tid;?>"><?php echo $row->taskname;?></a></td>
    <td data-sort-value="<?php echo strtotime($row->created);?>"><?php echo Filter::dodate("short_date", $row->created);?></td>
    <td><div class="wojo black label"><?php echo $row->hours;?></div></td>
    <td><a href="index.php?do=timebilling&amp;action=edit&amp;pid=<?php echo $prow->id;?>&amp;id=<?php echo $row->id;?>"><i class="rounded inverted success icon pencil link"></i></a> <a class="delete" data-title="<?php echo Lang::$word->BILL_DELETE;?>" data-option="deleteTimeBillingRecord" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->title;?>"><i class="rounded danger inverted remove icon link"></i></a></td>
  </tr>
  <?php endforeach;?>
  <?php unset($row);?>
  <?php endif;?>
</table>
<?php break;?>
<?php case"edit": ?>
<?php $row = (Filter::$id) ? $content->getTimeBillingById() : Filter::error("You have selected an Invalid Id", "Content::getTimeBillingById()");?>
<?php $plist = $content->getProjectList();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->BILL_INFO1 . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?> </div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->BILL_SUB1 . $row->title;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->BILL_ENTRY;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="title" value="<?php echo $row->title;?>" placeholder="<?php echo Lang::$word->BILL_ENTRY;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->INVC_CNAME;?></label>
        <label class="input">
          <input type="text" name="cfullname" disabled="disabled" readonly value="<?php echo Core::renderName($row);?>" placeholder="<?php echo Lang::$word->INVC_CNAME;?>">
        </label>
      </div>
    </div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->TASK_NAME;?></label>
        <label class="input">
          <input type="text" name="taskname" value="<?php echo $row->taskname;?>" readonly disabled="disabled" placeholder="<?php echo Lang::$word->TASK_NAME;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PROJ_MANAGER;?></label>
        <label class="input">
          <input type="text" name="sfullname" disabled="disabled" readonly value="<?php echo $row->sfullname;?>" placeholder="<?php echo Lang::$word->PROJ_MANAGER;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->BILL_DOWORK;?></label>
        <label class="input"> <i class="icon-prepend icon calendar"></i>
          <input type="text" data-datepicker="true" name="created" data-value="<?php echo $row->created;?>" value="<?php echo $row->created;?>">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->INVC_PROJCSELETC;?></label>
        <select name="project_id">
          <option value="">--- <?php echo Lang::$word->INVC_PROJCSELETC;?> ---</option>
          <?php if ($plist):?>
          <?php foreach ($plist as $prow):?>
          <option value="<?php echo $prow->id;?>"<?php if($prow->id == $row->project_id) echo ' selected="selected"';?>><?php echo $prow->title;?></option>
          <?php endforeach;?>
          <?php unset($prow);?>
          <?php endif;?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->BILL_LOWORK;?></label>
        <label class="input"> <i class="icon-prepend icon time"></i>
          <input type="text" name="hours" value="<?php echo $row->hours;?>">
        </label>
      </div>
    </div>
    <div class="field">
      <textarea class="bodypost" name="description"><?php echo $row->description;?></textarea>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->BILL_UPDATE;?></button>
    <a href="index.php?do=timebilling&amp;action=view&amp;id=<?php echo $row->pid;?>" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="staff_id" type="hidden" value="<?php echo $row->staff_id;?>" />
    <input name="client_id" type="hidden" value="<?php echo $row->client_id;?>" />
    <input name="task_id" type="hidden" value="<?php echo $row->task_id;?>" />
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
    <input name="processTimeRecord" type="hidden" value="1" />
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php case"add": ?>
<?php $plist = $content->getProjectList();?>
<?php $userlist = $user->getUserList(1);?>
<?php $tlist = $content->getTasksByProject();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->BILL_INFO2 . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?> </div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->BILL_SUB2;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->BILL_ENTRY;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="title" placeholder="<?php echo Lang::$word->BILL_ENTRY;?>">
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
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->INVC_PROJCSELETC;?></label>
        <select name="project_id">
          <option value="">--- <?php echo Lang::$word->INVC_PROJCSELETC;?> ---</option>
          <?php if ($plist):?>
          <?php foreach ($plist as $prow):?>
          <option value="<?php echo $prow->id;?>"<?php if($prow->id == Filter::$id) echo ' selected="selected"';?>><?php echo $prow->title;?></option>
          <?php endforeach;?>
          <?php unset($prow);?>
          <?php endif;?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->TASK_NAME;?></label>
        <select name="task_id">
          <?php if(!$tlist):?>
          <option value="0">--- <?php echo Lang::$word->BILL_NOTASK;?> ---</option>
          <?php else:?>
          <?php foreach ($tlist as $trow):?>
          <option value="<?php echo $trow->id;?>"><?php echo $trow->title;?></option>
          <?php endforeach;?>
          <?php unset($trow);?>
          <?php endif;?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PROJ_MANAGER;?></label>
        <select name="staff_id">
          <?php foreach ($user->getUserList("9' or userlevel = '5") as $srow):?>
          <option value="<?php echo $srow->id;?>"><?php echo Core::renderName($srow);?></option>
          <?php endforeach;?>
          <?php unset($srow);?>
        </select>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->BILL_LOWORK;?></label>
        <label class="input"> <i class="icon-prepend icon time"></i>
          <input type="text" name="hours" <?php echo (isset($_COOKIE['FM_TBIL'])) ? 'value="' . number_format(intval($_COOKIE['FM_TBIL']) / 100, 2, ".", "") . '"'  : 'placeholder="' .Lang::$word->BILL_LOWORK . '"';?>>
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->BILL_DOWORK;?></label>
        <label class="input"> <i class="icon-prepend icon calendar"></i>
          <input type="text" name="created" data-datepicker="true" data-value="<?php echo date('Y-m-d');?>" value="<?php echo date('Y-m-d');?>">
        </label>
      </div>
    </div>
    <div class="field">
      <textarea class="bodypost" name="description"></textarea>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->BILL_ADD;?></button>
    <a href="index.php?do=timebilling&amp;action=view&amp;id=<?php echo Filter::$id;?>" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processTimeRecord" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php default: ?>
<?php $timerow = $content->getTimeBilling();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->BILL_INFO3;?></div>
</div>
<div class="wojo basic block segment">
  <?php if($user->userlevel == 9):?>
  <a class="wojo basic button push-right" href="controller.php?action=createTimeReport"><i class="icon table"></i> <?php echo Lang::$word->BILL_REPORT;?></a>
  <?php endif;?>
  <h2 class="wojo left floated header"><i class="time icon"></i><?php echo Lang::$word->BILL_SUB3;?></h2>
</div>
<table class="wojo sortable table">
  <thead>
    <tr>
      <th data-sort="string"><?php echo Lang::$word->PROJ_NAME;?></th>
      <th data-sort="string"><?php echo Lang::$word->INVC_CNAME;?></th>
      <th data-sort="string"><?php echo Lang::$word->BILL_RECORDS;?></th>
      <th data-sort="int"><?php echo Lang::$word->HOURS;?></th>
      <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
    </tr>
  </thead>
  <?php if(!$timerow):?>
  <tr>
    <td colspan="5"><?php echo Filter::msgSingleInfo(Lang::$word->BILL_NORECORDS);?></td>
  </tr>
  <?php else:?>
  <?php foreach ($timerow as $row):?>
  <tr>
    <td><a href="index.php?do=projects&amp;action=edit&amp;id=<?php echo $row->pid;?>"><?php echo $row->ptitle;?></a></td>
    <td><a href="index.php?do=clients&amp;action=edit&amp;id=<?php echo $row->uid;?>"><?php echo $row->fullname;?></a></td>
    <td><div class="wojo info label"><?php echo $row->totalprojects;?></div></td>
    <td><div class="wojo black label"><?php echo $row->totalhours;?></div></td>
    <td><a href="index.php?do=timebilling&amp;action=view&amp;id=<?php echo $row->pid;?>"><i class="rounded inverted success icon pencil link"></i></a> <a class="delete" data-title="<?php echo Lang::$word->BILL_DELETE1;?>" data-option="deleteTimeBilling" data-id="<?php echo $row->pid;?>" data-name="<?php echo $row->ptitle;?>"><i class="rounded danger inverted remove icon link"></i></a></td>
  </tr>
  <?php endforeach;?>
  <?php endif;?>
  <?php unset($row);?>
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