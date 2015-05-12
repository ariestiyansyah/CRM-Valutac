<?php
  /**
   * System Messages
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: messages.php, v3.00 2014-05-08 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Filter::$action): case "view": ?>
<?php $single = $content->getMessageById();?>
<?php if($single->user1 == $user->uid or $single->user2 == $user->uid):?>
<?php $userp = $content->updateMessageStatus($single->user1);?>
<?php $msgdata = $content->renderMessages();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->MSG_INFO . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->MSG_SUB . $single->msgsubject;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="wojo selection divided list">
      <?php foreach ($msgdata as $row):?>
      <div class="item">
        <?php if($row->attachment):?>
        <div class="right floated wojo black label"><i class="icon download cloud"></i> <a href="<?php echo UPLOADURL . 'tempfiles/' . $row->attachment;?>"><?php echo Lang::$word->FORM_DOWNLOAD;?></a></div>
        <?php endif;?>
        <img src="<?php echo UPLOADURL;?>/avatars/<?php echo ($row->avatar) ? $row->avatar : "blank.png";?>" alt="" class="wojo image avatar">
        <div class="content">
          <div class="header"> <?php echo ($user->uid == $row->userid) ? '<span class="wojo label positive">' . Lang::$word->FROM . ': ' . Lang::$word->YOU . '</span>' : '<span class="wojo label info">' . Lang::$word->FROM . ': ' .  Core::renderName($row) . '</span>';?> </div>
          <?php echo cleanOut($row->body);?> | <small><?php echo Filter::dodate("long_date", $row->created);?></small> </div>
      </div>
      <?php endforeach;?>
      <?php unset($row);?>
    </div>
    <div class="field">
      <textarea class="bodypost" name="body"></textarea>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->FILE_ATTACH;?></label>
      <label class="input">
        <input type="file" name="attachment" class="filefield">
      </label>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->REPLY;?></button>
    <a href="index.php?do=messages" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
    <input name="uid2" type="hidden" value="<?php echo count($msgdata) + 1;?>" />
    <input name="userp" type="hidden" value="<?php echo $userp;?>" />
    <input name="msgsubject" type="hidden" value="<?php echo sanitize($single->msgsubject);?>" />
    <input name="recipient" type="hidden" value="<?php echo ($user->uid == $single->user1) ?  $single->user2 : $single->user1;?>" />
    <input name="update" type="hidden" value="1" />
    <input name="processMessage" type="hidden" value="1" />
  </form>
</div>
<div id="msgholder"></div>
<?php else:?>
<?php print Filter::msgSingleInfo(Lang::$word->MSG_NOMSG2);?>
<?php endif;?>
<?php break;?>
<?php case"add": ?>
<?php $userlist = $user->getUserList("1' or userlevel = '5");?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->MSG_INFO1 . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->MSG_SUB1;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->MSG_SUBJECT;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="msgsubject" placeholder="<?php echo Lang::$word->MSG_SUBJECT;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->MSG_RECEPIENT;?></label>
        <select name="recipient">
          <option value="">--- <?php echo Lang::$word->MSG_RECEPIENT_T;?> ---</option>
          <?php if($userlist):?>
          <?php foreach ($userlist as $urow):?>
          <?php if($user->userlevel == 9):?>
          <option value="<?php echo $urow->id;?>"><?php echo Core::renderName($urow) . ' - ' . $urow->email;?></option>
          <?php else:?>
          <option value="<?php echo $urow->id;?>"><?php echo Core::renderName($urow)?></option>
          <?php endif;?>
          <?php endforeach;?>
          <?php unset($urow);?>
          <?php endif;?>
        </select>
      </div>
    </div>
    <div class="field">
      <textarea class="bodypost" name="body"></textarea>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->FILE_ATTACH;?></label>
      <label class="input">
        <input type="file" name="attachment" class="filefield">
      </label>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->FMSG_SEND;?></button>
    <a href="index.php?do=messages" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processMessage" type="hidden" value="1" />
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php default: ?>
<?php $messagerow = $content->getMessages();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->MSG_INFO2;?></div>
</div>
<div class="wojo basic block segment">
  <?php if($user->userlevel == 9):?>
  <a class="wojo basic button push-right" href="index.php?do=messages&amp;action=add"><i class="icon add"></i> <?php echo Lang::$word->MSG_ADDNEW;?></a>
  <?php endif;?>
  <h2 class="wojo left floated header"><i class="mail icon"></i><?php echo Lang::$word->MSG_SUB2;?></h2>
</div>
<table class="wojo basic table">
  <thead>
    <tr>
      <th></th>
      <th><?php echo Lang::$word->MSG_SENDER;?></th>
      <th><?php echo Lang::$word->MSG_SUBJECT;?></th>
      <th>#<?php echo Lang::$word->MSG_REPLIES;?></th>
      <th><?php echo Lang::$word->MSG_SENT;?></th>
      <th><?php echo Lang::$word->ACTIONS;?></th>
    </tr>
  </thead>
  <?php if(!$messagerow):?>
  <tr>
    <td colspan="5"><?php echo Filter::msgSingleInfo(Lang::$word->MSG_NOMSG);?></td>
  </tr>
  <?php else:?>
  <?php foreach ($messagerow as $row):?>
  <tr<?php echo ($row->user1read == "no") ? ' class="black"' : null;?>>
    <td><span class="wojo black icon label"><?php echo substr($row->fullname, 0, 1);?></span></td>
    <td><?php echo Core::renderName($row);?></td>
    <td><?php echo ($row->attachment) ? '<i class="attachment icon"></i> ': null;?><?php echo $row->msgsubject;?></td>
    <td><span class="wojo info label"><?php echo $row->replies - 1;?></span></td>
    <td><?php echo Filter::doDate("long_date", $row->created);?></td>
    <td><a href="index.php?do=messages&amp;action=view&amp;id=<?php echo $row->uid1;?>"><i class="rounded inverted success icon pencil link"></i></a> <a class="delete" data-title="<?php echo Lang::$word->MSG_DELETE;?>" data-option="deleteMessage" data-id="<?php echo $row->id;?>" data-extra="<?php echo $row->uid1;?>" data-name="<?php echo Core::renderName($row);?>"><i class="rounded danger inverted remove icon link"></i></a></td>
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
<?php break;?>
<?php endswitch;?>