<?php
  /**
   * Task Templates
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: task_template.php, v3.00 2014-05-08 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php if($user->userlevel == 5): print Filter::msgInfo(Lang::$word->ADMINONLY, false); return; endif;?>
<?php switch(Filter::$action): case "edit": ?>
<?php $row = Core::getRowById("task_templates", Filter::$id);?>
<?php if($user->userlevel == 5 and $user->uid != $row->staff_id): print Filter::msgInfo(Lang::$word->ADMINONLY, false); return; endif;?>
<?php $stafflist = $user->getUserList("9' or userlevel = '5");?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->TTASK_INFO . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->TTASK_SUB . $row->title;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="field">
      <label><?php echo Lang::$word->TTASK_NAME;?></label>
      <label class="input"><i class="icon-append icon asterisk"></i>
        <input type="text" value="<?php echo $row->title;?>" name="title">
      </label>
    </div>
    <div class="field">
      <textarea class="bodypost" name="details"><?php echo $row->details;?></textarea>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->TTASK_UPDATE;?></button>
    <a href="index.php?do=task_template" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processTaskTemplate" type="hidden" value="1">
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php case"add": ?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->TTASK_INFO1 . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->TTASK_SUB1;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="field">
      <label><?php echo Lang::$word->TTASK_NAME;?></label>
      <label class="input"><i class="icon-append icon asterisk"></i>
        <input type="text" placeholder="<?php echo Lang::$word->TTASK_NAME;?>" name="title">
      </label>
    </div>
    <div class="field">
      <textarea class="bodypost" name="details"></textarea>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->TTASK_ADD;?></button>
    <a href="index.php?do=task_template" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processTaskTemplate" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php default: ?>
<?php $templaterow = $content->getTaskTemplates();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->TTASK_INFO2;?></div>
</div>
<div class="wojo basic block segment">
  <?php if($user->userlevel == 9):?>
  <a class="wojo basic button push-right" href="index.php?do=task_template&amp;action=add"><i class="icon add"></i> <?php echo Lang::$word->TTASK_ADD;?></a>
  <?php endif;?>
  <h2 class="wojo left floated header"><i class="cubes icon"></i><?php echo Lang::$word->TTASK_TITLE2;?></h2>
</div>
<table class="wojo sortable table">
  <thead>
    <tr>
      <th data-sort="string"><?php echo Lang::$word->TTASK_NAME;?></th>
      <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
    </tr>
  </thead>
  <?php if(!$templaterow):?>
  <tr>
    <td colspan="2"><?php echo Filter::msgSingleInfo(Lang::$word->TTASK_NOTEMPLATES);?></td>
  </tr>
  <?php else:?>
  <?php foreach ($templaterow as $row):?>
  <tr>
    <td><?php echo $row->title;?></td>
    <td><a href="index.php?do=task_template&amp;action=edit&amp;id=<?php echo $row->id;?>"><i class="rounded inverted success icon pencil link"></i></a> <a class="delete" data-title="<?php echo Lang::$word->TTASK_DELTEMPLATE;?>" data-option="deleteTaskTemplate" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->title;?>"><i class="rounded danger inverted remove icon link"></i></a></td>
  </tr>
  <?php endforeach;?>
  <?php unset($row);?>
  <?php endif;?>
</table>
<?php break;?>
<?php endswitch;?>