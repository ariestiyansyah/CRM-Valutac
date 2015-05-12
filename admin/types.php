<?php
  /**
   * Project Types
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: types.php, v3.00 2014-05-08 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php if($user->userlevel == 5): print Filter::msgInfo(Lang::$word->ADMINONLY); return; endif;?>
<?php switch(Filter::$action): case "edit": ?>
<?php $row = Core::getRowById("project_types", Filter::$id);?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->TYPE_INFO . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->TYPE_SUB . $row->title;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="field">
      <label><?php echo Lang::$word->TYPE_NAME;?></label>
      <label class="input"><i class="icon-append icon asterisk"></i>
        <input type="text" value="<?php echo $row->title;?>" name="title">
      </label>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->TYPE_DESC;?></label>
      <label class="textarea">
        <textarea name="description"><?php echo $row->description;?></textarea>
      </label>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->TYPE_UPDATE;?></button>
    <a href="index.php?do=types" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processProjectType" type="hidden" value="1">
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php case"add": ?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->TYPE_INFO1 . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->TYPE_SUB1;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="field">
      <label><?php echo Lang::$word->TYPE_NAME;?></label>
      <label class="input"><i class="icon-append icon asterisk"></i>
        <input type="text" placeholder="<?php echo Lang::$word->TYPE_NAME;?>" name="title">
      </label>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->TYPE_DESC;?></label>
      <label class="textarea">
        <textarea name="description" placeholder="<?php echo Lang::$word->TYPE_DESC;?>"></textarea>
      </label>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->TYPE_ADD;?></button>
    <a href="index.php?do=types" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processProjectType" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php default: ?>
<?php $typerow = $content->getProjectTypes();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->TYPE_INFO2;?></div>
</div>
<div class="wojo basic block segment">
  <?php if($user->userlevel == 9):?>
  <a class="wojo basic button push-right" href="index.php?do=types&amp;action=add"><i class="icon add"></i> <?php echo Lang::$word->TYPE_ADD;?></a>
  <?php endif;?>
  <h2 class="wojo left floated header"><i class="briefcase icon"></i><?php echo Lang::$word->TYPE_SUB2;?></h2>
</div>
<table class="wojo sortable table">
  <thead>
    <tr>
      <th data-sort="string"><?php echo Lang::$word->TYPE_NAME;?></th>
      <th data-sort="string"><?php echo Lang::$word->TYPE_DESC;?></th>
      <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
    </tr>
  </thead>
  <?php if(!$typerow):?>
  <tr>
    <td colspan="3"><?php echo Filter::msgSingleInfo(Lang::$word->TYPE_NOTYPES);?></td>
  </tr>
  <?php else:?>
  <?php foreach ($typerow as $row):?>
  <tr>
    <td><?php echo $row->title;?></td>
    <td><?php echo cleanOut($row->description);?></td>
    <td><a href="index.php?do=types&amp;action=edit&amp;id=<?php echo $row->id;?>"><i class="rounded inverted success icon pencil link"></i></a> <a class="delete" data-title="<?php echo Lang::$word->TYPE_DELTYPE;?>" data-option="deleteProjectType" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->title;?>"><i class="rounded danger inverted remove icon link"></i></a></td>
  </tr>
  <?php endforeach;?>
  <?php unset($row);?>
  <?php endif;?>
</table>
<?php break;?>
<?php endswitch;?>