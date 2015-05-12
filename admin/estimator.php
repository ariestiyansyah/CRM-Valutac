<?php
  /**
   * Estimator Builder
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: estimator.php, v3.00 2014-06-05 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  require_once(BASEPATH . "lib/class_estimator.php");
  Registry::set('Estimator',new Estimator());
  $estimator = Registry::get("Estimator");
?>
<?php if($user->userlevel == 5): print Filter::msgInfo(Lang::$word->ADMINONLY, false); return; endif;?>
<?php switch(Filter::$action): case "edit": ?>
<?php $row = Core::getRowById("estimator", Filter::$id);?>
<?php $gaterow = $content->getGateways();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->FORM_INFO . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->FORM_SUB . $row->title;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="three fields">
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
      <div class="field">
        <label><?php echo Lang::$word->FORM_BTN;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="submit_btn" value="<?php echo $row->submit_btn;?>">
        </label>
      </div>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->FORM_MSG;?></label>
      <label class="input"> <i class="icon-append icon asterisk"></i>
        <input type="text" name="sendmessage" value="<?php echo $row->sendmessage;?>">
      </label>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->ESTM_DESC;?></label>
      <textarea class="bodypost" name="description"><?php echo $row->description;?></textarea>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="four fields">
      <div class="field">
        <label><?php echo Lang::$word->FORM_CAPTCHA;?></label>
        <label class="radio">
          <input type="radio" name="captcha" value="1" <?php getChecked($row->captcha, 1); ?>>
          <i></i><?php echo Lang::$word->YES;?></label>
        <label class="radio">
          <input type="radio" name="captcha" value="0" <?php getChecked($row->captcha, 0); ?>>
          <i></i><?php echo Lang::$word->NO;?></label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->FORM_ACTIVE;?></label>
        <label class="radio">
          <input type="radio" name="active" value="1" <?php getChecked($row->active, 1); ?>>
          <i></i><?php echo Lang::$word->YES;?></label>
        <label class="radio">
          <input type="radio" name="active" value="0" <?php getChecked($row->active, 0); ?>>
          <i></i><?php echo Lang::$word->NO;?></label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->ESTM_DELIVERY;?></label>
        <label class="radio">
          <input type="radio" name="showhours" value="1" <?php getChecked($row->showhours, 1); ?>>
          <i></i><?php echo Lang::$word->YES;?></label>
        <label class="radio">
          <input type="radio" name="showhours" value="0" <?php getChecked($row->showhours, 0); ?>>
          <i></i><?php echo Lang::$word->NO;?></label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->ESTM_DPD;?></label>
        <label class="input">
          <input type="text" name="dpd" value="<?php echo $row->dpd;?>" placeholder="<?php echo Lang::$word->ESTM_DPD;?>">
        </label>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo header"><?php echo Lang::$word->ESTM_TITLE5;?> / <?php echo $row->title;?></div>
    <div class="wojo fitted divider"></div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->ESTM_ORDER;?></label>
        <label class="radio">
          <input type="radio" name="is_service" value="1" <?php getChecked($row->is_service, 1); ?>>
          <i></i><?php echo Lang::$word->YES;?></label>
        <label class="radio">
          <input type="radio" name="is_service" value="0" <?php getChecked($row->is_service, 0); ?>>
          <i></i><?php echo Lang::$word->NO;?></label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->ESTM_GATESEL;?></label>
        <div class="scrollbox">
          <?php $class = 'odd'; ?>
          <?php foreach ($gaterow as $srow):?>
          <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
          <div class="<?php echo $class;?><?php if(in_array($srow->id, Core::_explode($row->gateways))) echo " sel";?>">
            <label class="checkbox">
              <?php if(in_array($srow->id, Core::_explode($row->gateways))): ?>
              <input type="checkbox" name="gateways[]" value="<?php echo $srow->id; ?>" checked="checked" />
              <?php  else: ?>
              <input type="checkbox" name="gateways[]" value="<?php echo $srow->id; ?>" />
              <?php endif; ?>
              <i></i><?php echo $srow->displayname;?> </label>
          </div>
          <?php endforeach;?>
          <?php unset($srow);?>
        </div>
      </div>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->FORM_UPDATE;?></button>
    <a href="index.php?do=estimator" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processEstimator" type="hidden" value="1">
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
    <input name="doVisualEstimator" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php case"add": ?>
<?php $gaterow = $content->getGateways();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->FORM_INFO . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->ESTM_SUB2;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="three fields">
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
      <div class="field">
        <label><?php echo Lang::$word->FORM_BTN;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="submit_btn" placeholder="<?php echo Lang::$word->FORM_BTN;?>">
        </label>
      </div>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->FORM_MSG;?></label>
      <label class="input"> <i class="icon-append icon asterisk"></i>
        <input type="text" name="sendmessage" placeholder="<?php echo Lang::$word->FORM_MSG;?>">
      </label>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->ESTM_DESC;?></label>
      <textarea class="bodypost" name="description"></textarea>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="four fields">
      <div class="field">
        <label><?php echo Lang::$word->FORM_CAPTCHA;?></label>
        <label class="radio">
          <input type="radio" name="captcha" value="1" checked="checked">
          <i></i><?php echo Lang::$word->YES;?></label>
        <label class="radio">
          <input type="radio" name="captcha" value="0" >
          <i></i><?php echo Lang::$word->NO;?></label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->FORM_ACTIVE;?></label>
        <label class="radio">
          <input type="radio" name="active" value="1" checked="checked">
          <i></i><?php echo Lang::$word->YES;?></label>
        <label class="radio">
          <input type="radio" name="active" value="0" >
          <i></i><?php echo Lang::$word->NO;?></label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->ESTM_DELIVERY;?></label>
        <label class="radio">
          <input type="radio" name="showhours" value="1" checked="checked">
          <i></i><?php echo Lang::$word->YES;?></label>
        <label class="radio">
          <input type="radio" name="showhours" value="0">
          <i></i><?php echo Lang::$word->NO;?></label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->ESTM_DPD;?></label>
        <label class="input">
          <input type="text" name="dpd" placeholder="<?php echo Lang::$word->ESTM_DPD;?>">
        </label>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo header"><?php echo Lang::$word->ESTM_TITLE5;?> </div>
    <div class="wojo fitted divider"></div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->ESTM_ORDER;?></label>
        <label class="radio">
          <input type="radio" name="is_service" value="1" >
          <i></i><?php echo Lang::$word->YES;?></label>
        <label class="radio">
          <input name="is_service" type="radio" value="0" checked="checked" >
          <i></i><?php echo Lang::$word->NO;?></label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->ESTM_GATESEL;?></label>
        <div class="scrollbox">
          <?php $class = 'odd'; ?>
          <?php foreach ($gaterow as $srow):?>
          <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
          <div class="<?php echo $class;?>">
            <label class="checkbox">
              <input type="checkbox" name="gateways[]" value="<?php echo $srow->id; ?>" />
              <i></i><?php echo $srow->displayname;?> </label>
          </div>
          <?php endforeach;?>
          <?php unset($srow);?>
        </div>
      </div>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->FORM_ADD;?></button>
    <a href="index.php?do=estimator" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processEstimator" type="hidden" value="1">
    <input name="doVisualEstimator" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php case"fields": ?>
<?php $row = Core::getRowById(Estimator::mTable, Filter::$id);?>
<?php $fieldrows = $estimator->getAllFields();?>
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
        <?php echo Estimator::renderFieldList();?>
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
<script type="text/javascript" src="assets/js/estimator-builder.js"></script> 
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
<?php case"view": ?>
<?php $row = Core::getRowById(Estimator::mTable, Filter::$id);?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->FORM_INFO5;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->FORM_PREVIEWF . $row->title;?></h2>
</div>
<div class="wojo form"> <?php echo cleanOut($row->form_html);?> </div>
<?php break;?>
<?php case"viewdata": ?>
<?php $datarow = $estimator->getFormSubmittedData();?>
<?php $title = getValueById("title", Estimator::mTable, Filter::$id);?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->FORM_INFO3;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->FORM_SUB3 . $title;?></h2>
</div>
<table class="wojo sortable table">
  <thead>
    <tr>
      <th data-sort="int"><?php echo Lang::$word->FORM_SUBMITTED;?></th>
      <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
    </tr>
  </thead>
  <tbody>
    <?php if(!$datarow):?>
    <tr>
      <td colspan="2"><?php echo Filter::msgSingleInfo(Lang::$word->FORM_NODATA);?></td>
    </tr>
    <?php else:?>
    <?php foreach ($datarow as $row):?>
    <tr>
      <td data-sort-value="<?php echo strtotime($row->created);?>"><?php echo Filter::doDate("long_date", $row->created);?></td>
      <td><a data-id="<?php echo $row->id;?>" class="preview" data-content="<?php echo Lang::$word->FORM_VIEWD;?>"><i class="rounded inverted success icon laptop link"></i></a> <a class="delete" data-title="<?php echo Lang::$word->FORM_DELDATA;?>" data-option="deleteEstimatorData" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->created;?>"><i class="rounded danger inverted remove icon link"></i></a>
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
    $('a.preview').on('click', function () {
        Messi.load('controller.php', {
            viewFormData: 1,
            id: $(this).data('id'),
			doVisualEstimator: 1
        }, {
            title: "<?php echo Lang::$word->FORM_VIEWD.' / '.$title;?>"
        });
    });
});
// ]]>
</script>
<?php break;?>
<?php case"transaction": ?>
<?php $title = getValueById("title", Estimator::mTable, Filter::$id);?>
<?php $payrows = $estimator->getPayments();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->ESTM_INFO5;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><i class="layout block icon"></i><?php echo Lang::$word->ESTM_SUB5 . $title;?></h2>
</div>
<table class="wojo sortable table">
  <thead>
    <tr>
      <th data-sort="string">#</th>
      <th data-sort="string"><?php echo Lang::$word->FROM;?> / <?php echo Lang::$word->EMAIL;?></th>
      <th data-sort="int"><?php echo Lang::$word->AMOUNT;?></th>
      <th data-sort="string"><?php echo Lang::$word->FDASH_METHOD;?></th>
      <th data-sort="int"><?php echo Lang::$word->CREATED;?></th>
      <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
    </tr>
  </thead>
  <tbody>
    <?php if(!$payrows):?>
    <tr>
      <td colspan="6"><?php echo Filter::msgSingleInfo(Lang::$word->ESTM_NOTRANS);?></td>
    </tr>
    <?php else:?>
    <?php foreach ($payrows as $row):?>
    <tr>
      <td><?php echo $row->txn_id;?></td>
      <td><?php echo $row->user;?> / <?php echo $row->email;?></td>
      <td><?php echo $core->formatMoney($row->price);?></td>
      <td><?php echo $row->pp;?></td>
      <td data-sort-value="<?php echo strtotime($row->created);?>"><?php echo Filter::doDate("long_date", $row->created);?></td>
      <td><a class="delete" data-title="<?php echo Lang::$word->ESTM_DELTRANS;?>" data-option="deleteEstimatroTransaction" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->txn_id;?>"><i class="rounded danger inverted remove icon link"></i></a></td>
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
<?php default: ?>
<?php $formrows = $estimator->getForms();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->FORM_INFO4;?></div>
</div>
<div class="wojo basic block segment">
  <?php if($user->userlevel == 9):?>
  <a class="wojo basic button push-right" href="index.php?do=estimator&amp;action=add"><i class="icon add"></i> <?php echo Lang::$word->FORM_ADD;?></a>
  <?php endif;?>
  <h2 class="wojo left floated header"><i class="layout block icon"></i><?php echo Lang::$word->ESTM_SUB4;?></h2>
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
    <td><a href="index.php?do=estimator&amp;action=fields&amp;id=<?php echo $row->id;?>" data-content="<?php echo Lang::$word->FORM_EDITFIELDS;?>"><i class="rounded inverted info icon edit link"></i></a>
      <?php if($row->is_service):?>
      <a href="index.php?do=estimator&amp;action=transaction&amp;id=<?php echo $row->id;?>" data-content="<?php echo Lang::$word->ESTM_TRANS;?>"><i class="rounded inverted info icon dollar link"></i></a>
      <?php endif;?>
      <a href="index.php?do=estimator&amp;action=view&amp;id=<?php echo $row->id;?>" data-content="<?php echo Lang::$word->FORM_PREVIEWF;?>"><i class="rounded inverted warning icon laptop link"></i></a> <a href="index.php?do=estimator&amp;action=viewdata&amp;id=<?php echo $row->id;?>" data-content="<?php echo Lang::$word->FORM_PREVIEWD;?>"><i class="rounded inverted purple icon reorder link"></i></a> <a href="index.php?do=estimator&amp;action=edit&amp;id=<?php echo $row->id;?>"><i class="rounded inverted success icon pencil link"></i></a> <a class="delete" data-title="<?php echo Lang::$word->FORM_DELFORM;?>" data-option="deleteEstimator" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->title;?>"><i class="rounded danger inverted remove icon link"></i></a></td>
  </tr>
  <?php endforeach;?>
  <?php unset($row);?>
  <?php endif;?>
</table>
<?php break;?>
<?php endswitch;?>