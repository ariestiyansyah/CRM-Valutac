<?php
  /**
   * System Announcement
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: news.php, v3.00 2014-05-08 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php if($user->userlevel == 5): print Filter::msgInfo(Lang::$word->ADMINONLY, false); return; endif;?>
<?php switch(Filter::$action): case "edit": ?>
<?php $row = Core::getRowById("news", Filter::$id);?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->NEWS_INFO . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->NEWS_SUB . $row->title;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->NEWS_NAME;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" value="<?php echo $row->title;?>" name="title">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->NEWS_START;?></label>
        <label class="input"><i class="icon-append icon calendar"></i>
          <input type="text" data-datepicker="true" data-value="<?php echo $row->created;?>" value="<?php echo $row->created;?>" name="created">
        </label>
      </div>
    </div>
    <div class="field">
      <textarea class="bodypost" name="body"><?php echo $row->body;?></textarea>
    </div>
    <div class="field">
      <label class="label"><?php echo Lang::$word->NEWS_ACTIVE;?></label>
      <div class="inline-group">
        <label class="radio">
          <input type="radio" name="active" value="1" <?php getChecked($row->active, 1); ?>>
          <i></i><?php echo Lang::$word->YES;?></label>
        <label class="radio">
          <input type="radio" name="active" value="0" <?php getChecked($row->active, 0); ?>>
          <i></i><?php echo Lang::$word->NO;?></label>
      </div>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->NEWS_UPDATE;?></button>
    <a href="index.php?do=news" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processNews" type="hidden" value="1">
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php case"add": ?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->NEWS_INFO1 . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->NEWS_SUB1;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->NEWS_NAME;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" placeholder="<?php echo Lang::$word->NEWS_NAME;?>" name="title">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->NEWS_START;?></label>
        <label class="input"><i class="icon-append icon calendar"></i>
          <input type="text" data-datepicker="true" data-value="<?php echo date('m/d/Y');?>" name="created">
        </label>
      </div>
    </div>
    <div class="field">
      <textarea class="bodypost" name="body"></textarea>
    </div>
    <div class="field">
      <label class="label"><?php echo Lang::$word->NEWS_ACTIVE;?></label>
      <div class="inline-group">
        <label class="radio">
          <input type="radio" name="active" value="1" checked="checked">
          <i></i><?php echo Lang::$word->YES;?></label>
        <label class="radio">
          <input type="radio" name="active" value="0">
          <i></i><?php echo Lang::$word->NO;?></label>
      </div>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->NEWS_ADD;?></button>
    <a href="index.php?do=news" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processNews" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php default: ?>
<?php $newsrow = $content->getNews();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->NEWS_INFO2;?></div>
</div>
<div class="wojo basic block segment">
  <?php if($user->userlevel == 9):?>
  <a class="wojo basic button push-right" href="index.php?do=news&amp;action=add"><i class="icon add"></i> <?php echo Lang::$word->NEWS_ADD;?></a>
  <?php endif;?>
  <h2 class="wojo left floated header"><i class="attachment icon"></i><?php echo Lang::$word->NEWS_SUB2;?></h2>
</div>
<table class="wojo sortable table">
  <thead>
    <tr>
      <th data-sort="string"><?php echo Lang::$word->NEWS_NAME;?></th>
      <th data-sort="int"><?php echo Lang::$word->CREATED;?></th>
      <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
    </tr>
  </thead>
  <?php if(!$newsrow):?>
  <tr>
    <td colspan="3"><?php echo Filter::msgSingleInfo(Lang::$word->NEWS_NONEWS);?></td>
  </tr>
  <?php else:?>
  <?php foreach ($newsrow as $row):?>
  <tr>
    <td><?php echo $row->title;?></td>
    <td data-sort-value="<?php echo strtotime($row->created);?>"><?php echo Filter::dodate("short_date", $row->created);?></td>
    <td><a href="index.php?do=news&amp;action=edit&amp;id=<?php echo $row->id;?>"><i class="rounded inverted success icon pencil link"></i></a> <a class="delete" data-title="<?php echo Lang::$word->NEWS_DELETE;?>" data-option="deleteNews" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->title;?>"><i class="rounded danger inverted remove icon link"></i></a></td>
  </tr>
  <?php endforeach;?>
  <?php unset($row);?>
  <?php endif;?>
</table>
<?php break;?>
<?php endswitch;?>