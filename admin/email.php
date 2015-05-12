<?php
  /**
   * Email Manager
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: email.php, v3.00 2014-06-05 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php if($user->userlevel == 5): print Filter::msgInfo(Lang::$word->ADMINONLY, false); return; endif;?>
<?php $userdata = $user->getAllUsers();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->MAIL_INFO . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><i class="mail icon"></i><?php echo Lang::$word->MAIL_SUB;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->MAIL_FROM;?></label>
        <label class="input">
          <input type="text" disabled="disabled" value="<?php echo $core->site_email;?>" name="from">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->MAIL_REC_SUJECT;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" placeholder="<?php echo Lang::$word->MAIL_REC_SUJECT;?>" name="subject">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->MAIL_REC;?></label>
        <?php if(isset(Filter::$get['emailid'])):?>
        <label class="input">
          <input name="recipient" type="text" value="<?php echo sanitize(Filter::$get['emailid']);?>" placeholder="<?php echo Lang::$word->MAIL_REC;?>" >
        </label>
        <?php else:?>
        <select name="recipient" id="multiusers">
          <option value="all"><?php echo Lang::$word->MAIL_REC_ALL;?></option>
          <option value="clients"><?php echo Lang::$word->MAIL_REC_C;?></option>
          <option value="staff"><?php echo Lang::$word->MAIL_REC_S;?></option>
          <option value="multiple"><?php echo Lang::$word->MAIL_REC_M;?></option>
        </select>
        <?php endif;?>
      </div>
      <div class="field multiuserList" style="display:none">
        <?php if($userdata):?>
        <label><?php echo Lang::$word->MAIL_REC;?></label>
        <select name="multilist" multiple="multiple">
          <?php foreach ($userdata as $udata):?>
          <option value="<?php echo $udata->id;?>"><?php echo $udata->name.' - '.$udata->username;?></option>
          <?php endforeach;?>
        </select>
        <?php unset($udata);?>
        <?php endif;?>
      </div>
    </div>
    <?php if(isset(Filter::$get['emailid'])):?>
    <div class="field">
      <label><?php echo Lang::$word->MAIL_REC_ATTACH;?></label>
      <label class="input">
        <input type="file" name="attach" class="filefield">
      </label>
    </div>
    <?php endif;?>
    <div class="field">
      <textarea class="bodypost" name="body">&lt;div style=&quot;font-family:Arial, Helvetica, sans-serif; font-size:13px;margin:20px&quot; align=&quot;center&quot;&gt;
  &lt;table style=&quot;background: none repeat scroll 0% 0% rgb(244, 244, 244); border: 2px solid #bbbbb;&quot; border=&quot;0&quot; cellpadding=&quot;10&quot; cellspacing=&quot;5&quot; width=&quot;650&quot;&gt;
    &lt;tbody&gt;
      &lt;tr&gt;
        &lt;th style=&quot;background-color: rgb(204, 204, 204); font-size:16px;padding:5px;border-bottom-width:2px; border-bottom-color:#fff; border-bottom-style:solid&quot;&gt;[LOGO]&lt;/th&gt;
      &lt;/tr&gt;
      &lt;tr&gt;
        &lt;td style=&quot;text-align: left;&quot; valign=&quot;top&quot;&gt;Hello [NAME],

      &lt;tr&gt;
        &lt;td style=&quot;text-align: left;&quot; valign=&quot;top&quot;&gt;Place your content here...&lt;/td&gt;
      &lt;/tr&gt;
      &lt;tr&gt;
        &lt;td style=&quot;text-align: left;&quot; valign=&quot;top&quot;&gt;&lt;em&gt;Thank You,&lt;br/&gt;
          &lt;a href=&quot;[URL]&quot;&gt;[COMPANY]&lt;/a&gt;&lt;/em&gt;&lt;/td&gt;
      &lt;/tr&gt;
      &lt;tr&gt;
        &lt;td style=&quot;text-align: left; background-color:#fff;border-top-width:2px; border-top-color:#ccc; border-top-style:solid;font-size:12px&quot; valign=&quot;top&quot;&gt; This email is sent to you directly from&amp;nbsp;&lt;a href=&quot;[URL]&quot;&gt;[COMPANY]&lt;/a&gt; The information above is gathered from the user input.&lt;br/&gt;
          &copy;[YEAR] &lt;a href=&quot;[URL]&quot;&gt;[COMPANY]&lt;/a&gt;. All rights reserved.&lt;/td&gt;
      &lt;/tr&gt;
    &lt;/tbody&gt;
  &lt;/table&gt;
&lt;/div&gt;</textarea>
    </div>
    <div class="wojo negative label"><?php echo Lang::$word->MAIL_NOTE;?></div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->MAIL_SEND;?></button>
    <input name="processEmail" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<script type="text/javascript">
  $(document).ready(function() {
    $('#multiusers').change(function () {
		var option = $("#multiusers option:selected").val();
		(option == 'multiple') ? $('.multiuserList').show() : $('.multiuserList').hide()
    });
  });
</script> 