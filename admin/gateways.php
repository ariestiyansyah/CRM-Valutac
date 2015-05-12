<?php
  /**
   * Gateways
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: gateways.php, v3.00 2014-06-05 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php if($user->userlevel == 5): print Filter::msgInfo(Lang::$word->ADMINONLY, false); return; endif;?>
<?php switch(Filter::$action): case "edit": ?>
<?php $row = Core::getRowById("gateways", Filter::$id);?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->GATE_INFO . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment"> <a class="push-right viewtip"><i class="rounded inverted info icon help link"></i></a>
  <h2 class="wojo left floated header"><?php echo Lang::$word->GATE_SUB . $row->displayname;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->GATE_NAME;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" value="<?php echo $row->displayname;?>" name="displayname">
        </label>
      </div>
      <div class="field">
        <label><?php echo $row->extra_txt;?></label>
        <label class="input">
          <input type="text" value="<?php echo $row->extra;?>" name="extra">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo $row->extra_txt2;?></label>
        <label class="input">
          <input type="text" value="<?php echo $row->extra2;?>" name="extra2">
        </label>
      </div>
      <div class="field">
        <label><?php echo $row->extra_txt3;?></label>
        <label class="input">
          <input type="text" value="<?php echo $row->extra3;?>" name="extra3">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label class="label"><?php echo Lang::$word->GATE_LIVE;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="live" value="1" <?php getChecked($row->live, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="live" value="0" <?php getChecked($row->live, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->GATE_ACTIVE;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="active" value="1" <?php getChecked($row->active, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="active" value="0" <?php getChecked($row->active, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="field">
      <label class="label"><?php echo Lang::$word->GATE_IPN;?></label>
      <label class="input">
        <input type="text" disabled="disabled" value="<?php echo SITEURL.'/gateways/'.$row->dir.'/ipn.php';?>" readonly>
      </label>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->GATE_UPDATE;?></button>
    <a href="index.php?do=gateways" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processGateway" type="hidden" value="1">
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
  </form>
</div>
<div id="msgholder"></div>
<div id="showhelp" style="display:none"><?php echo cleanOut($row->info);?></div>
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function () {
	$('a.viewtip').on('click', function () {
		var text = $("#showhelp").html();
		new Messi(text, {
			title: "<?php echo $row->displayname;?>"
		});
	});
});
// ]]>
</script>
<?php break;?>
<?php default: ?>
<?php $gaterow = $content->getGateways();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->GATE_INFO1;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><i class="exchange icon"></i><?php echo Lang::$word->GATE_SUB1;?></h2>
</div>
<table class="wojo basic table">
  <thead>
    <tr>
      <th><?php echo Lang::$word->GATE_NAME;?></th>
      <th><?php echo Lang::$word->ACTIONS;?></th>
    </tr>
  </thead>
  <tbody>
    <?php if(!$gaterow):?>
    <tr>
      <td colspan="2"><?php echo Filter::msgSingleInfo(Lang::$word->GATE_NOGATE);?></td>
    </tr>
    <?php else:?>
    <?php foreach ($gaterow as $row):?>
    <tr>
      <td><?php echo $row->displayname;?></td>
      <td><a href="index.php?do=gateways&amp;action=edit&amp;id=<?php echo $row->id;?>"><i class="rounded inverted success icon pencil link"></i></a></td>
    </tr>
    <?php endforeach;?>
    <?php unset($row);?>
    <?php endif;?>
  </tbody>
</table>
<?php break;?>
<?php endswitch;?>