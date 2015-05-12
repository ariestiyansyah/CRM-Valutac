<?php
  /**
   * Form Builder
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: forms.php, v3.00 2014-05-08 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  require_once(BASEPATH . "lib/class_forms.php");
  Registry::set('Forms',new Forms());
  $forms = Registry::get("Forms");
?>
<?php if($user->userlevel == 5): print Filter::msgInfo(Lang::$word->ADMINONLY, false); return; endif;?>
<?php switch(Filter::$action): case "edit": ?>
<?php $row = Core::getRowById("forms", Filter::$id);?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->FORM_INFO . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->FORM_SUB . $row->title;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->FORM_NAME;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="title" value="<?php echo $row->title;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->FORM_EMAIL;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="mailto" value="<?php echo $row->mailto;?>">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->FORM_BTN;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="submit_btn" value="<?php echo $row->submit_btn;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->FORM_MSG;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="sendmessage" value="<?php echo $row->sendmessage;?>">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->FORM_CAPTCHA;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="captcha" value="1" <?php getChecked($row->captcha, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="captcha" value="0" <?php getChecked($row->captcha, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <div class="inline-group">
          <label><?php echo Lang::$word->FORM_ACTIVE;?></label>
          <label class="radio">
            <input type="radio" name="active" value="1" <?php getChecked($row->active, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="active" value="0" <?php getChecked($row->active, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->FORM_UPDATE;?></button>
    <a href="index.php?do=forms" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processVisualForm" type="hidden" value="1">
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
    <input name="doVisualForm" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php case"add": ?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->FORM_INFO1 . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->FORM_SUB1;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->FORM_NAME;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="title" placeholder="<?php echo Lang::$word->FORM_NAME;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->FORM_EMAIL;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="mailto" placeholder="<?php echo Lang::$word->FORM_EMAIL;?>">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->FORM_BTN;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="submit_btn" placeholder="<?php echo Lang::$word->FORM_BTN;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->FORM_MSG;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="sendmessage" placeholder="<?php echo Lang::$word->FORM_MSG;?>">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->FORM_CAPTCHA;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="captcha" value="1" checked="checked">
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="captcha" value="0">
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <div class="inline-group">
          <label><?php echo Lang::$word->FORM_ACTIVE;?></label>
          <label class="radio">
            <input type="radio" name="active" value="1" checked="checked">
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="active" value="0">
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->FORM_ADD;?></button>
    <a href="index.php?do=forms" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processVisualForm" type="hidden" value="1">
    <input name="doVisualForm" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php case"fields": ?>
<?php $row = Core::getRowById("forms", Filter::$id);?>
<?php $fieldrows = $forms->getAllFields();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo str_replace("[ICOADD]", "<i class=\"icon add sign\"></i>", Lang::$word->FORM_INFO2);?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><i class="layout block icon"></i><?php echo Lang::$word->FORM_F_FLAYOUT . $row->title;?></h2>
</div>
<div class="wojo form segment">
  <div class="two fields">
    <div class="field">
      <label><?php echo Lang::$word->FORM_F_NEWCREATE;?></label>
      <select name="newfield">
        <option value=""><?php echo Lang::$word->FORM_F_NEWCREATE;?></option>
        <?php echo Forms::renderFieldList();?>
      </select>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->FORM_F_AVAILF;?></label>
      <select name="editfield">
        <option value=""><?php echo Lang::$word->FORM_F_AVAILF;?></option>
        <?php if($fieldrows):?>
        <?php foreach($fieldrows as $arow):?>
        <option value="<?php echo $arow->id;?>"><?php echo $arow->title;?></option>
        <?php endforeach;?>
        <?php endif;?>
      </select>
    </div>
  </div>
</div>
<div class="wojo thin attached divider"></div>
<div id="fieldOptions" style="display:none">
  <div class="wojo form segment">
    <form method="post" id="wojo_form" name="wojo_form">
      <div class="fieldarea"> </div>
    </form>
  </div>
  <div id="msgholder"></div>
  <div class="wojo thin attached divider"></div>
</div>
<div class="wojo form">
  <div class="two fitted fields">
    <div class="field">
      <div class="wojo warning labeled icon top right pointing dropdown button" id="addRow"> <i class="dropdown icon"></i>
        <div class="text"><?php echo Lang::$word->FORM_F_ADDROW;?></div>
        <div class="menu">
          <div class="item" data-type="four"><i class="edit icon"></i><?php echo Lang::$word->FORM_F_FOUR_FIELDS;?></div>
          <div class="item" data-type="three"><i class="edit icon"></i><?php echo Lang::$word->FORM_F_THREE_FIELDS;?></div>
          <div class="item" data-type="two"><i class="edit icon"></i><?php echo Lang::$word->FORM_F_TWO_FIELDS;?></div>
          <div class="item" data-type="one"><i class="edit icon"></i><?php echo Lang::$word->FORM_F_ONE_FIELD;?></div>
        </div>
      </div>
    </div>
    <div class="field">
      <div id="removerows"><i class="icon trash"></i> <?php echo Lang::$word->FORM_F_DROPHERE;?></div>
    </div>
  </div>
  <div class="wojo double fitted divider"></div>
  <div id="renderForm"> <?php echo cleanOut($row->form_data);?></div>
  <div class="wojo double fitted divider"></div>
  <a id="serialize" class="wojo positive button"><?php echo Lang::$word->FORM_F_SAVEALL;?></a>
  <div id="smsgholder" class="small-top-space"></div>
</div>
<script type="text/javascript" src="assets/js/form-builder.js"></script> 
<script type="text/javascript"> 
// <![CDATA[
 $(document).ready(function () {
     $.forms({
         url: 'controller.php',
		 formid: $.url(true).param('id'),
         msg: {
             btnsubmit: "<?php echo Lang::$word->FORM_F_INSERTF;?>",
             selfile: "<?php echo Lang::$word->FORM_F_SELFILE;?>"
         }
     });
 })
// ]]>
</script>
<?php break;?>
<?php case"viewdata": ?>
<?php $datarow = $forms->getFormSubmittedData();?>
<?php $title = getValueById("title", "forms", Filter::$id);?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->FORM_INFO3;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><i class="layout block icon"></i><?php echo Lang::$word->FORM_SUB3 . $title;?></h2>
</div>
<table class="wojo sortable table">
  <thead>
    <tr>
      <th data-sort="int"><?php echo Lang::$word->FORM_SUBMITTED;?></th>
      <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
    </tr>
  </thead>
  <?php if(!$datarow):?>
  <tr>
    <td colspan="2"><?php echo Filter::msgSingleInfo(Lang::$word->FORM_NODATA);?></td>
  </tr>
  <?php else:?>
  <tbody>
    <?php foreach ($datarow as $row):?>
    <tr>
      <td data-sort-value="<?php echo strtotime($row->created);?>"><?php echo Filter::doDate("long_date", $row->created);?></td>
      <td><a class="preview" data-content="<?php echo Lang::$word->FORM_VIEWD;?>" data-id="<?php echo $row->id;?>"><i class="rounded inverted success icon laptop link"></i></a> <a class="delete" data-title="<?php echo Lang::$word->FORM_DELDATA;?>" data-option="deleteFormData" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->created;?>"><i class="rounded danger inverted remove icon link"></i></a></td>
    </tr>
    <?php endforeach;?>
    <?php unset($row);?>
	<?php endif;?>
  </tbody>
</table>
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function () {
    $('a.preview').on('click', function () {
        Messi.load('controller.php', {
            viewFormData: 1,
            id: $(this).data('id'),
			doVisualForm: 1
        }, {
            title: "<?php echo Lang::$word->FORM_VIEWD.' <i class=\"icon-double-angle-right\"></i> '.$title;?>"
        });
    });
});
// ]]>
</script>
<?php break;?>
<?php case"view": ?>
<?php $row = Core::getRowById("forms", Filter::$id);?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->FORM_INFO5;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->FORM_PREVIEWF . $row->title;?></h2>
</div>
<div class="wojo form"> <?php echo cleanOut($row->form_html);?> </div>
<?php break;?>
<?php default: ?>
<?php $formrows = $forms->getForms();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->FORM_INFO4;?></div>
</div>
<div class="wojo basic block segment">
  <?php if($user->userlevel == 9):?>
  <a class="wojo basic button push-right" href="index.php?do=forms&amp;action=add"><i class="icon add"></i> <?php echo Lang::$word->FORM_ADD;?></a>
  <?php endif;?>
  <h2 class="wojo left floated header"><i class="layout block icon"></i><?php echo Lang::$word->FORM_SUB4;?></h2>
</div>
<table class="wojo sortable table">
  <thead>
    <tr>
      <th data-sort="string"><?php echo Lang::$word->FORM_NAME;?></th>
      <th data-sort="int"><?php echo Lang::$word->CREATED;?></th>
      <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
    </tr>
  </thead>
  <?php if(!$formrows):?>
  <tr>
    <td colspan="3"><?php echo Filter::msgSingleInfo(Lang::$word->FORM_NOFORM);?></td>
  </tr>
  <?php else:?>
  <?php foreach ($formrows as $row):?>
  <tr>
    <td><?php echo $row->title;?></td>
    <td data-sort-value="<?php echo strtotime($row->created);?>"><?php echo Filter::doDate("long_date", $row->created);?></td>
    <td><a href="index.php?do=forms&amp;action=fields&amp;id=<?php echo $row->id;?>" data-content="<?php echo Lang::$word->FORM_EDITFIELDS;?>"><i class="rounded inverted info icon edit link"></i></a> <a href="index.php?do=forms&amp;action=view&amp;id=<?php echo $row->id;?>" data-content="<?php echo Lang::$word->FORM_PREVIEWF;?>"><i class="rounded inverted warning icon laptop link"></i></a> <a href="index.php?do=forms&amp;action=viewdata&amp;id=<?php echo $row->id;?>" data-content="<?php echo Lang::$word->FORM_PREVIEWD;?>"><i class="rounded inverted purple icon reorder link"></i></a> <a href="index.php?do=forms&amp;action=edit&amp;id=<?php echo $row->id;?>"><i class="rounded inverted success icon pencil link"></i></a> <a class="delete" data-title="<?php echo Lang::$word->FORM_DELFORM;?>" data-option="deleteVisualForm" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->title;?>"><i class="rounded danger inverted remove icon link"></i></a></td>
  </tr>
  <?php endforeach;?>
  <?php unset($row);?>
  <?php endif;?>
</table>
<?php break;?>
<?php endswitch;?>