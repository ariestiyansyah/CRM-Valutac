<?php
  /**
   * Mailer Templates
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: mtemplates.php, v3.00 2014-06-05 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php if($user->userlevel == 5): print Filter::msgInfo(Lang::$word->ADMINONLY, false); return; endif;?>
<?php $files = $content->getMailerTemplates();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->MTPL_INFO;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->MTPL_SUB;?><span id="tplsub">none</span></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <select name="filename" id="mtemplatelist">
          <option value="">--- <?php echo Lang::$word->MTPL_SELTPL;?> ---</option>
          <?php if($files):?>
          <?php foreach($files as $row):?>
          <?php $name = basename($row);?>
          <option value="<?php echo $name;?>"><?php echo substr(str_replace("_", " ",$name), 0, -8);?></option>
          <?php endforeach;?>
          <?php endif;?>
        </select>
      </div>
      <div class="field">
        <div class="inline-group">
          <label class="checkbox">
            <input name="backup" type="checkbox" value="1" checked="CHECKED">
            <i></i><?php echo Lang::$word->MTPL_TPLBACK;?></label>
        </div>
      </div>
    </div>
    <div class="wojo divider"></div>
    <div class="field">
      <textarea class="fullpage" name="body" placeholder="<?php echo Lang::$word->MTPL_SELNOTE;?>">&lt;div style=&quot;color:rgba(255,255,255,0.5); text-align:center;font-family:Helvetica, Arial, sans&quot;&gt;<?php echo Lang::$word->MTPL_SELNOTE;?>&lt;/div&gt;</textarea>
    </div>
    <p class="wojo error"><?php echo Lang::$word->MTPL_NOTE;?></p>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->MTPL_UPDATE;?></button>
    <input name="processMtemplate" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<script type="text/javascript">
  $(document).ready(function() {
      $('#mtemplatelist').change(function() {
          var option = $(this).val();
          $.ajax({
			  cache: false,
              type: "get",
              url: "controller.php",
			  dataType: "json",
              data: {
                  'getMailerTemplate': 1,
                  filename: option
              },
              success: function(json) {
                  if (json.status == "error") {
                      $("#tplsub").html("none");
                      $('.fullpage').redactor('set', json.message);
                  } else {
                      $("#tplsub").html(json.title);
                      $('.fullpage').redactor('set', json.message);

                  }
              }
          });

      });
  });
</script> 