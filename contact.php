<?php
  /**
   * Contact
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: contact.php, v3.00 2014-06-05 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Filter::$action): case "view": ?>
<?php $single = $content->getMessageById();?>
<?php if($single->user1 == $user->uid or $single->user2 == $user->uid):?>
<?php $userp = $content->updateMessageStatus($single->user1);?>
<?php $msgdata = $content->renderMessages();?>
<div class="wojo tertiary segment">
  <h3 class="wojo header"><?php echo Lang::$word->FMSG_SUB3 . $single->msgsubject;?></h3>
  <div class="wojo message">
    <p><i class="pin icon"></i> <?php echo Lang::$word->MSG_INFO;?></p>
  </div>
  <form id="wojo_form" name="wojo_form" class="wojo form" method="post">
    <div class="wojo selection divided list">
      <?php foreach ($msgdata as $row):?>
      <div class="item">
        <?php if($row->attachment):?>
        <div class="right floated teal wojo label"><i class="icon download cloud"></i> <a href="<?php echo UPLOADURL . 'tempfiles/' . $row->attachment;?>"><?php echo Lang::$word->FORM_DOWNLOAD;?></a></div>
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
    <button type="button" data-url="/ajax/controller.php" name="dosubmit" class="wojo button"><?php echo Lang::$word->REPLY;?></button>
    <a href="account.php?do=contact" class="wojo basic button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
    <input name="uid2" type="hidden" value="<?php echo count($msgdata) + 1;?>" />
    <input name="userp" type="hidden" value="<?php echo $userp;?>" />
    <input name="msgsubject" type="hidden" value="<?php echo sanitize($single->msgsubject);?>" />
    <input name="recipient" type="hidden" value="<?php echo ($user->uid == $single->user1) ?  $single->user2 : $single->user1;?>" />
    <input name="processContact" type="hidden" value="1" />
  </form>
</div>
<div id="msgholder"></div>
<?php else:?>
<?php print Filter::msgSingleInfo(Lang::$word->MSG_NOMSG2);?>
<?php endif;?>
<?php break;?>
<?php default: ?>
<?php $newsrow = $user->getLatestNews();?>
<?php $msgrow = $content->getMessages(false);?>
<div class="wojo message">
  <p><i class="pin icon"></i> <?php echo Lang::$word->FMSG_INFO;?></p>
</div>
<?php if($newsrow):?>
<div class="wojo tertiary segment">
  <h3 class="wojo header"><?php echo Lang::$word->FMSG_SUB;?></h3>
  <table class="wojo basic table">
    <thead>
      <tr>
        <th><?php echo Lang::$word->FMSG_POSTED;?></th>
        <th><?php echo Lang::$word->AUTHOR;?></th>
        <th><?php echo Lang::$word->FMSG_CONTENT;?></th>
      </tr>
    </thead>
    <?php foreach ($newsrow as $row):?>
    <tr>
      <td><?php echo Filter::doDate("short_date", $row->created);?></td>
      <td><?php echo $row->author;?></td>
      <td><?php echo cleanOut($row->body);?></td>
    </tr>
    <?php endforeach;?>
    <?php unset($row);?>
  </table>
</div>
<?php endif;?>
<div id="cform" class="wojo tertiary segment form" style="display:none">
  <h3 class="wojo header"><?php echo Lang::$word->FMSG_SUB2;?></h3>
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->MAIL_REC_SUJECT;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="msgsubject" placeholder="<?php echo Lang::$word->MAIL_REC_SUJECT;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PROJ_MANAGER;?></label>
        <select name="recipient">
          <?php foreach ($user->getUserList("9' or userlevel = '5") as $srow):?>
          <option value="<?php echo $srow->id;?>"><?php echo Core::renderName($srow);?></option>
          <?php endforeach;?>
          <?php unset($srow);?>
        </select>
      </div>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->FILE_ATTACH;?></label>
      <label class="input">
        <input type="file" name="attachment" class="filefield">
      </label>
      <p class="wojo info"><?php echo Lang::$word->CONF_AFT.': '.$core->file_types;?>. <?php echo Lang::$word->CONF_MFS.': '.$core->file_max/1048576;?>mb</p>
    </div>
    <div class="field">
      <textarea class="bodypost" name="message"></textarea>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" data-url="/ajax/controller.php" name="dosubmit" class="wojo button"><?php echo Lang::$word->FMSG_SEND;?></button>
    <input name="processContact" type="hidden" value="1" />
  </form>
</div>
<div id="msgholder"></div>
<div class="mail-header clearfix">
  <h3 class="push-left"><?php echo Lang::$word->FMSG_SUB4;?></h3>
  <a class="push-right" id="view-form" data-content="<?php echo Lang::$word->MSG_ADDNEW;?>"><i class="rounded inverted black icon link add"></i></a> </div>
<div class="columns">
  <div class="screen-30 tablet-40 phone-100">
    <div class="wojo basic segment">
      <div class="scrollbox">
        <?php if($msgrow):?>
        <div id="msg-list" class="wojo selection divided list">
          <?php foreach ($msgrow as $row):?>
          <div class="item<?php echo ($row->user2read == "no") ? ' active' : null;?>" data-id="<?php echo $row->id;?>">
            <div class="right floated info wojo label"><?php echo $row->replies - 1;?></div>
            <div class="content"> <a class="header view-message" data-mid="<?php echo $row->uid1;?>" data-did="<?php echo $row->id;?>" data-id="<?php echo $row->uid1;?>"><?php echo $row->msgsubject;?></a>
              <div class="description"><?php echo ($row->attachment) ? '<i class="attachment icon"></i> ': null;?><?php echo Filter::doDate("long_date", $row->created);?></div>
            </div>
          </div>
          <?php endforeach;?>
        </div>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class="screen-70 tablet-60 phone-100">
    <div id="msg-wrap" class="wojo basic inverted black segment">
      <div id="msg-holder" class="scrollbox">
        <?php if(!$msgrow):?>
        <?php echo Filter::msgSingleInfo(Lang::$word->MSG_NOMSG);?>
        <?php else:?>
        <div class="emptymail"></div>
        <?php endif;?>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
// <![CDATA[
  $(document).ready(function () {
	  $('body').on('click', 'a.view-message', function () {
		  id = $(this).data('id');
		  mid = $(this).data('mid');
		  did = $(this).data('did');
		  loader = $("#msg-wrap").addClass('loading');
		  $.ajax({
			  type: 'get',
			  url: SITEURL + '/ajax/controller.php',
			  dataType: 'json',
			  data: {
				  getMessages: 1,
				  id: id,
				  mid: mid,
				  did: did
			  },
			  success: function (json) {
				  loader.removeClass('loading');
				  $("#msg-holder").html(json.message);
			  }

		  });
      });
	  $('body').on('click', 'a.delmessage', function () {
		  id = $(this).data('id');
		  extra = $(this).data('id');
		  $.ajax({
			  type: 'post',
			  url: SITEURL + '/ajax/controller.php',
			  dataType: 'json',
			  data: {
				  delete: "deleteMessage",
				  id: id,
				  extra: extra
			  },
			  success: function (json) {
				  $("#msg-holder").html("<div class=\"emptymail\"></div>");
				  $("#msg-list .item[data-id=" + id + "]").slideUp();
				  
			  }

		  });
      });
      $('#view-form').on('click', function () {
          $('#cform').slideToggle();
      });
  });
// ]]>
</script>
<?php break;?>
<?php endswitch;?>