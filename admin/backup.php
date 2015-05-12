<?php
  /**
   * Database Backup
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: backup.php, v3.00 2014-06-05 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  require_once(BASEPATH . "lib/class_dbtools.php");
  Registry::set('dbTools',new dbTools());
  $tools = Registry::get("dbTools");
?>
<?php if($user->userlevel !=9):?>
<?php Filter::msgAlert(Lang::$word->ADMINONLY);?>
<?php else:?>
<?php
  if (isset($_GET['backupok']) && $_GET['backupok'] == "1")
      Filter::msgOk(Lang::$word->DB_CREATED,1,1);
	    
  if (isset($_GET['create']) && $_GET['create'] == "1")
      $tools->doBackup('',false);
	  
  $dir = BASEPATH . 'admin/backups/';
?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->DB_INFO;?></div>
</div>
<div class="wojo basic block segment">
  <?php if($user->userlevel == 9):?>
  <a class="wojo basic button push-right" href="index.php?do=backup&amp;create=1"><i class="icon add"></i> <?php echo Lang::$word->DB_DOBACKUP;?></a>
  <?php endif;?>
  <h2 class="wojo left floated header"><i class="database icon"></i><?php echo Lang::$word->DB_SUB;?></h2>
</div>
<?php if (is_dir($dir)):?>
<?php $getDir = dir($dir);?>
<div class="wojo divided list">
  <?php while (false !== ($file = $getDir->read())):?>
  <?php if ($file != "." && $file != ".." && $file != "index.php"):?>
  <?php $latest =  ($file == $core->sbackup) ? " active" : "";?>
  <div class="item<?php echo $latest;?>"><i class="big icon hdd"></i>
	<div class="header"><?php echo getSize(filesize(BASEPATH . 'admin/backups/' . $file));?></div>
	<div class="push-right"> <a class="dbdelete" data-content="<?php echo Lang::$word->DELETE;?>" data-option="deleteBackup" data-file="<?php echo $file;;?>"><i class="rounded danger inverted trash icon"></i></a> <a href="<?php echo ADMINURL . '/backups/' . $file;?>" data-content="<?php echo Lang::$word->DOWNLOAD;?>"><i class="rounded success inverted download alt icon"></i></a> <a class="restore" data-content="<?php echo Lang::$word->DB_DORESTORE;?>" data-file="<?php echo $file;?>"><i class="rounded warning inverted refresh icon"></i></a> </div>
	<div class="content"><?php echo str_replace(".sql", "", $file);?></div>
  </div>
  <?php endif;?>
  <?php endwhile;?>
  <?php $getDir->close();?>
</div>
<?php endif;?>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function () {
    $('a.restore').on('click', function () {
        var parent = $(this).closest('div.item');
        var id = $(this).data('file')
        var title = id;
        var text = "<div class=\"messi-warning\"><i class=\"massive icon warn warning sign\"></i></p><p><?php echo Lang::$word->DB_RESCONFIRM;?></div>";
        new Messi(text, {
            title: "<?php echo Lang::$word->DB_DORESTORE;?>",
            modal: true,
            closeButton: true,
            buttons: [{
                id: 0,
                label: "<?php echo Lang::$word->DB_RESTORE;?>",
                val: 'Y',
				class: 'negative'
            }],
            callback: function (val) {
                if (val === "Y") {
					$.ajax({
						type: 'post',
						dataType: 'json',
						url: "controller.php",
						data: 'restoreBackup=' + id,
						success: function (json) {
							parent.effect('highlight', 1500);
							$.sticky(decodeURIComponent(json.message), {
								type: json.type,
								title: json.title
							});
						}
					});
                }
            }
        })
    });
	
    $('body').on('click', 'a.dbdelete', function () {
        var file = $(this).data('file');
        var name = $(this).data('file');
        var title = $(this).data('file');
        var option = $(this).data('option');
        var parent = $(this).parent().parent();

        new Messi("<div class=\"messi-warning\"><i class=\"massive icon warn warning sign\"></i></p><p><?php echo Lang::$word->DELCONFIRM;?></p></div>", {
            title: title,
            titleClass: '',
            modal: true,
            closeButton: true,
            buttons: [{
                id: 0,
                label: '<?php echo Lang::$word->DELETE;?>',
                class: 'negative',
                val: 'Y'
            }],
            callback: function (val) {
                $.ajax({
                    type: 'post',
                    url: "controller.php",
                    dataType: 'json',
                    data: {
                        file: file,
                        delete: option,
                        title: encodeURIComponent(name)
                    },
                    beforeSend: function () {
                        parent.animate({
                            'backgroundColor': '#FFBFBF'
                        }, 400);
                    },
                    success: function (json) {
                        parent.fadeOut(400, function () {
                            parent.remove();
                        });
                        $.sticky(decodeURIComponent(json.message), {
                            type: json.type,
                            title: json.title
                        });
                    }

                });
            }
        });
    });

});
// ]]>
</script>
<?php endif;?>