<?php
  /**
   * Custom Fields
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: fields.php, v3.00 2014-05-08 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php if($user->userlevel == 5): print Filter::msgInfo(Lang::$word->ADMINONLY, false); return; endif;?>
<?php switch(Filter::$action): case "edit": ?>
<?php $row = Core::getRowById(Content::cfTable, Filter::$id);?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->CUSF_INFO1 . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->CUSF_SUB1 . $row->title;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CUSF_NAME;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" value="<?php echo $row->title;?>" name="title">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CUSF_SECTION;?></label>
        <select name="section">
          <?php echo Content::getFieldSection($row->section);?>
        </select>
      </div>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->CUSF_UPDATE;?></button>
    <a href="index.php?do=fields" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processField" type="hidden" value="1">
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php case"add": ?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->CUSF_INFO2 . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->CUSF_SUB2;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CUSF_NAME;?></label>
        <label class="input"><i class="icon-append icon-asterisk"></i>
          <input type="text" placeholder="<?php echo Lang::$word->CUSF_NAME;?>" name="title">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CUSF_SECTION;?></label>
        <select name="section">
          <?php echo Content::getFieldSection();?>
        </select>
      </div>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->CUSF_ADD;?></button>
    <a href="index.php?do=fields" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processField" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php default: ?>
<?php $fieldrow = $content->getCustomFields();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->CUSF_INFO;?></div>
</div>
<div class="wojo basic block segment">
  <?php if($user->userlevel == 9):?>
  <a class="wojo basic button push-right" href="index.php?do=fields&amp;action=add"><i class="icon add"></i> <?php echo Lang::$word->CUSF_ADDNEW;?></a>
  <?php endif;?>
  <h2 class="wojo left floated header"><i class="asterisk icon"></i><?php echo Lang::$word->CUSF_SUB;?></h2>
</div>
<table class="wojo sortable table">
  <thead>
    <tr>
      <th class="disabled"></th>
      <th data-sort="string"><?php echo Lang::$word->CUSF_NAME;?></th>
      <th data-sort="string"><?php echo Lang::$word->CUSF_SECTION;?></th>
      <th data-sort="int"><?php echo Lang::$word->CUSF_POSITION;?></th>
      <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
    </tr>
  </thead>
  <tbody>
    <?php if(!$fieldrow):?>
    <tr>
      <td colspan="5"><?php echo Filter::msgSingleInfo(Lang::$word->CUSF_NOFIELDS);?></td>
    </tr>
    <?php else:?>
    <?php foreach ($fieldrow as $row):?>
    <tr id="node-<?php echo $row->id;?>">
      <td class="id-handle"><i class="icon reorder"></i></td>
      <td><?php echo $row->title;?></td>
      <td><?php echo Content::fieldSection($row->section);?></td>
      <td><span class="wojo black label"><?php echo $row->sorting;?></span></td>
      <td><a href="index.php?do=fields&amp;action=edit&amp;id=<?php echo $row->id;?>"><i class="rounded inverted success icon pencil link"></i></a> <a class="delete" data-title="<?php echo Lang::$word->CUSF_DELETE;?>" data-option="deleteField" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->title;?>"><i class="rounded danger inverted remove icon link"></i></a></td>
    </tr>
    <?php endforeach;?>
    <?php unset($row);?>
    <?php endif;?>
  </tbody>
</table>
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function () {
    $(".wojo.table tbody").sortable({
        helper: 'clone',
        handle: '.id-handle',
        placeholder: 'placeholder',
        opacity: .6,
        update: function (event, ui) {
            serialized = $(".wojo.table tbody").sortable('serialize');
            $.ajax({
                type: "POST",
                url: "controller.php?sortfields",
                data: serialized,
                success: function (msg) {}
            });
        }
    });
});
// ]]>
</script>
<?php break;?>
<?php endswitch;?>