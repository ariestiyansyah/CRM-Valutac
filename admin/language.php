<?php
  /**
   * Language Manager
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: language.php, v3.00 2014-06-05 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->LM_INFO;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><i class="language icon"></i><?php echo Lang::$word->LM_SUB;?></h2>
</div>
<div class="wojo form">
  <div class="two fields">
    <div class="field">
      <div class="wojo fluid icon input">
        <input id="filter" type="text" placeholder="<?php echo Lang::$word->SEARCH;?>">
        <i class="search icon"></i> </div>
    </div>
    <div class="field"> <?php echo Lang::langSwitch()?> </div>
  </div>
</div>
<div id="mainsegment" class="wojo form segment">
  <div class="wojo-grid">
    <div id="langphrases" class="two columns small-gutters">
      <?php $xmlel = simplexml_load_file(BASEPATH . Lang::langdir . Core::$language . "/lang.xml"); ?>
      <?php $data = new stdClass();?>
      <?php $i = 1;?>
      <?php  foreach ($xmlel as $pkey) :?>
      <div class="row">
        <div contenteditable="true" data-path="lang" data-edit-type="language" data-id="<?php echo $i++;?>" data-key="<?php echo $pkey['data'];?>" class="wojo phrase"><?php echo $pkey;?></div>
      </div>
      <?php endforeach;?>
    </div>
  </div>
</div>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function () {
    /* == Filter == */
    $("#filter").on("keyup", function () {
        var filter = $(this).val(),
            count = 0;
        $("div[contenteditable=true]").each(function () {
            if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                $(this).parent().fadeOut();
            } else {
                $(this).parent().show();
                count++;
            }
        });
    });

    /* == Group Filter == */
    $('#langchange').change(function () {
        var sel = $(this).val();
		var type = $("#langchange option:selected").data('type');
        $("#mainsegment").addClass('loading');
        $.ajax({
            type: "POST",
            url: "controller.php",
            dataType: 'json',
            data: {
                'loadLanguage': 1,
                'filename': type,
				'type': sel
            },
            beforeSend: function () {},
            success: function (json) {
                if (json.status == "success") {
                    $("#langphrases").html(json.message).fadeIn("slow");
					$("#langname").html(sel);
                } else {
                    $.sticky(decodeURIComponent(json.message), {
                        type: "error",
                        title: json.title
                    });
                }
				$("#mainsegment").removeClass('loading');
            }
        })
    });
});
// ]]>
</script>
