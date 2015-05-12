<?php
  /**
   * Theme Editor
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: tedit.php, v3.00 2014-08-21 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  require_once (BASEPATH . "plugins/tedit/admin_class.php");
  Registry::set('themeEditor', new themeEditor());
?>
<?php if($user->userlevel == 5): print Filter::msgInfo(Lang::$word->ADMINONLY, false); return; endif;?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->TED_INFO;?></div>
</div>
<div class="wojo form segment">
  <div class="two fields">
    <div class="field">
      <div class="wojo black button filetitle"><?php echo Lang::$word->TED_SUB;?></div>
    </div>
    <div class="field">
      <select id="fileselect">
        <option value="" data-type="NA">--- <?php echo Lang::$word->TED_SELFILE;?> ---</option>
        <?php echo themeEditor::getThemeFileList();?>
      </select>
    </div>
  </div>
  <div class="wojo double fitted divider"></div>
  <form id="admin_form" method="post" class="wojo form">
    <textarea id="code" name="code"><?php echo Lang::$word->TED_DEFMSG;?></textarea>
  </form>
  <div class="savebar">
    <div class="two columns">
      <div id="savebtn" class="row"></div>
      <div id="restorebtn" class="row"></div>
    </div>
  </div>
</div>
<div id="msgholder"></div>
<script type="text/javascript" src="../plugins/tedit/assets/cm/lib/codemirror.js"></script> 
<script type="text/javascript" src="../plugins/tedit/assets/cm/mode/htmlmixed/htmlmixed.js"></script> 
<script type="text/javascript" src="../plugins/tedit/assets/cm/mode/xml/xml.js"></script> 
<script type="text/javascript" src="../plugins/tedit/assets/cm/mode/javascript/javascript.js"></script> 
<script type="text/javascript" src="../plugins/tedit/assets/cm/mode/css/css.js"></script> 
<script type="text/javascript" src="../plugins/tedit/assets/cm/mode/clike/clike.js"></script> 
<script type="text/javascript" src="../plugins/tedit/assets/cm/mode/php/php.js"></script> 
<script src="../plugins/tedit/assets/cm/addon/selection/active-line.js"></script> 
<script type="text/javascript"> 
// <![CDATA[  
  var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
      lineNumbers: true,
      lineWrapping: true,
      styleActiveLine: true,
      mode: "html",
      theme: "solarized dark",
  });

  $(document).ready(function () {
      $('#fileselect').change(function () {
		  var $form = $("#admin_form");
		  $form.addClass('loading');
          var name = $('#fileselect option:selected').val();
          var type = $('#fileselect option:selected').data("type");
          $(".extra_info").remove();
          $("<input name=\"data-type\" type=\"hidden\" class=\"extra_info\" value=\"" + type + "\" />").appendTo("#admin_form");
          $("<input name=\"data-name\" type=\"hidden\" class=\"extra_info\" value=\"" + name + "\" />").appendTo("#admin_form");
          $.ajax({
              type: "POST",
              dataType: "json",
              url: "../plugins/tedit/controller.php",
              data: {
                  loadFile: name,
                  fileType: type
              },
              success: function (json) {
                  $form.removeClass('loading');
				  var $saveBtn = $("<button id=\"saveFile\" class=\"wojo positive button\"><?php echo Lang::$word->TED_SAVE;?> <i class=\"icon angle right\"></i> " + json.name + "</button>");
				  var $restBtn = $("<button id=\"restoreFile\" class=\"wojo negative button\"><?php echo Lang::$word->TED_RESTORE;?> <i class=\"icon angle right\"></i> " + json.name + "</button>");
				  
                  (json.saveok == "success") ? $("#savebtn").html($saveBtn) : $("#savebtn").html("");
                  (json.backup == "yes") ? $("#restorebtn").html($restBtn) : $("#restorebtn").html("")
  
                  $(".filetitle").html(json.filename);
                  editor.setOption("mode", json.mode);
                  editor.setValue(json.message);
              }

          });
      })
	  
      $("body").on("click", "#saveFile", function () {
		  var $form = $("#admin_form");
		  $form.addClass('loading');
          var str = editor.getValue();
          $.ajax({
              type: "post",
              url: "../plugins/tedit/controller.php",
              data: {
                  processFile: 1,
                  fileContent: str,
                  dataType: $("input[name=data-type]").val(),
                  dataName: $("input[name=data-name]").val()
              },
              success: function (msg) {
                  $form.removeClass('loading');
                  $("#msgholder").html(msg);
              }
          });
      });
	  
      $("body").on("click", "#restoreFile", function () {
		  var $form = $("#admin_form");
		  $form.addClass('loading');
          $.ajax({
              type: "post",
			  dataType: "json",
              url: "../plugins/tedit/controller.php",
              data: {
                  restoreFile: 1,
                  dataType: $("input[name=data-type]").val(),
                  dataName: $("input[name=data-name]").val()
              },
              success: function (json) {
                 $form.removeClass('loading');
				  editor.setValue(json.message);
				  $("#msgholder").html(json.info);
              }
          });
      });
  });
// ]]>
</script>