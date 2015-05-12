<?php
  /**
   * Projects
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: projects.php, v3.00 2014-06-05 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Filter::$action): case "viewproject": ?>
<?php $row = (Filter::$id) ? $user->getProjectById() : Filter::error("You have selected an Invalid Id", "Content::getProjectById()");?>
<?php if(!$row):?>
<?php echo Filter::msgSingleInfo(Lang::$word->PFRO_ERR);?>
<?php else:?>
<?php $subrow = $user->getSubmissionsByProjectId();?>
<?php $taskrow = $user->getTasksByProjectId();?>
<div class="wojo tertiary segment">
  <h3 class="wojo header"><?php echo Lang::$word->FPRO_SUB . $row->title;?></h3>
  <table class="wojo basic table">
    <tr>
      <td><?php echo Lang::$word->PROJ_TYPE;?>:</td>
      <td><?php echo $row->typetitle;?></td>
      <td class="hide-phone">&nbsp;</td>
      <td class="hide-phone">&nbsp;</td>
    </tr>
    <tr>
      <td><?php echo Lang::$word->PROJ_DESC;?>:</td>
      <td><?php echo cleanOut($row->body);?></td>
      <td class="hide-phone">&nbsp;</td>
      <td class="hide-phone">&nbsp;</td>
    </tr>
    <tr>
      <td><?php echo Lang::$word->PROJ_STATUS;?>:</td>
      <td><?php echo $content->progressBarStatus($row->p_status);?></td>
      <td><?php echo Lang::$word->PROJ_MANAGER;?>:</td>
      <td><?php echo Core::renderName($row);?></td>
    </tr>
    <tr>
      <td><?php echo Lang::$word->PROJ_START;?>:</td>
      <td><?php echo Filter::doDate("short_date", $row->start_date);?></td>
      <td><?php echo Lang::$word->PROJ_END;?>:</td>
      <td><?php echo Filter::doDate("short_date", $row->end_date);?></td>
    </tr>
    <tr>
      <td><?php echo Lang::$word->PROJ_BILLSTSTUS;?>:</td>
      <td><?php echo $content->progressBarBilling($row->b_status, $row->cost);?></td>
      <td><?php echo Lang::$word->PROJ_PRICE;?>:</td>
      <td><?php echo $core->formatClientCurrency($row->cost, $user->currency);?></td>
    </tr>
    <?php if($core->enable_uploads):?>
    <tr>
      <td colspan="4"><a id="do-upload" class="wojo labeled icon button"> <i class="arrow down icon"></i> <?php echo Lang::$word->FPRO_SENDFILE;?> </a></td>
    </tr>
    <?php endif;?>
  </table>
</div>
<?php if($core->enable_uploads):?>
<div id="show-upload" class="wojo tertiary form segment" style="display:none">
  <form id="wojo_form" name="wojo_form" method="post">
    <h3 class="wojo header"><?php echo Lang::$word->FPRO_SUB1 . $row->title;?></h3>
    <div class="wojo basic message">
      <p><?php echo Lang::$word->FPRO_INFO;?></p>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->FILE_NAME;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="title"  placeholder="<?php echo Lang::$word->FILE_NAME;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->FILE_ATTACH;?></label>
        <label class="input">
          <input type="file" name="filename" class="filefield">
        </label>
      </div>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->FILE_DESC;?></label>
      <textarea class="bodypost" name="filedesc"></textarea>
    </div>
    <div class="wojo fitted divider"></div>
    <button type="button" data-url="/ajax/controller.php" name="dosubmit" class="wojo teal button"><?php echo Lang::$word->FILE_ADD;?></button>
    <input name="processProjectFile" type="hidden" value="1">
    <input name="project_id" type="hidden" value="<?php echo Filter::$id;?>" />
  </form>
</div>
<div id="msgholder"></div>
<?php endif;?>
<?php if(!$subrow):?>
<?php echo Filter::msgSingleInfo(Lang::$word->SUBS_NOSUBS);?>
<?php else:?>
<div class="wojo tertiary segment">
  <h3 class="wojo header"><?php echo Lang::$word->FPRO_SUB2;?></h3>
  <table class="wojo basic table">
    <thead>
      <tr>
        <th><?php echo Lang::$word->SUBS_NAME;?></th>
        <th><?php echo Lang::$word->SUBS_TYPE;?></th>
        <th><?php echo Lang::$word->CREATED;?></th>
        <th><?php echo Lang::$word->STATUS;?></th>
        <th><?php echo Lang::$word->VIEW;?></th>
      </tr>
    </thead>
    <?php foreach ($subrow as $srow):?>
    <tr>
      <td><?php echo $srow->title;?></td>
      <td><?php echo $srow->s_type;?></td>
      <td><?php echo Filter::doDate("short_date", $srow->created);?></td>
      <td><?php echo $content->projectSubmissionStatus($srow->status);?></td>
      <td><a href="account.php?do=projects&amp;action=viewsubmission&amp;id=<?php echo $srow->id;?>" data-content="<?php echo $srow->title;?>"><i class="rounded inverted black icon laptop link"></i></a></td>
    </tr>
    <?php endforeach;?>
    <?php unset($srow);?>
    <?php endif;?>
  </table>
</div>
<?php if(!$taskrow):?>
<?php echo Filter::msgSingleInfo(Lang::$word->TASK_NOTASKS);?>
<?php else:?>
<div class="wojo tertiary segment">
  <h3 class="wojo header"><?php echo Lang::$word->FPRO_SUB3;?></h3>
  <table class="wojo basic table">
    <thead>
      <tr>
        <th><?php echo Lang::$word->TASK_NAME;?></th>
        <th><?php echo Lang::$word->TASK_PROGRESS;?></th>
        <th><?php echo Lang::$word->TASK_START;?></th>
        <th><?php echo Lang::$word->INVC_DUEDATE;?></th>
        <th><?php echo Lang::$word->INFO;?></th>
      </tr>
    </thead>
    <?php foreach ($taskrow as $trow):?>
    <tr>
      <td><?php echo $trow->title;?></td>
      <td><?php echo $content->progressBarStatus($trow->progress);?></td>
      <td><?php echo Filter::doDate("short_date", $trow->created);?></td>
      <td><?php echo Filter::doDate("short_date", $trow->duedate);?></td>
      <td><a class="viewdesc" data-id="<?php echo $trow->id;?>" data-desc="<?php echo $trow->title;?>" data-content="<?php echo $trow->title;?>"><i class="rounded inverted black icon information link"></i></a>
    </tr>
    <?php endforeach;?>
    <?php unset($trow);?>
  </table>
</div>
<?php endif;?>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function () {
    $("a#do-upload").click(function () {
        $("#show-upload").slideToggle();
    });
    $('a.viewdesc').on('click', function () {
		var desc = $(this).data('desc');
        Messi.load('ajax/controller.php', {
            viewTaskData: 1,
            tid: $(this).data('id'),
			id : <?php echo Filter::$id;?>
        }, {
            title: "<?php echo Lang::$word->FPRO_TASKINFO;?>" + desc
        });
    });
});
// ]]>
</script>
<?php endif;?>
<?php break;?>
<?php case"viewsubmission":?>
<?php $row = (Filter::$id) ? $user->getSingleSubmissionsById() : Filter::error("You have selected an Invalid Id", "Content::getSingleSubmissionsById()");?>
<?php if(!$row):?>
<?php echo Filter::msgSingleInfo(Lang::$word->FPRO_ERR1);?>
<?php else:?>
<?php $filerow = $user->getFilesByProject($row->project_id);?>
<div class="wojo tertiary segment">
  <h3 class="wojo header"><?php echo Lang::$word->FPRO_SUB4 . $row->title;?></h3>
  <table class="wojo basic table">
    <tr>
      <td><?php echo Lang::$word->SUBS_NAME;?></td>
      <td><?php echo $row->title;?></td>
    </tr>
    <tr>
      <td><?php echo Lang::$word->SUBS_TYPE;?>:</td>
      <td><?php echo $row->s_type;?></td>
    </tr>
    <tr>
      <td><?php echo Lang::$word->PROJ_NAME;?>:</td>
      <td><?php echo $row->ptitle;?></td>
    </tr>
    <tr>
      <td><?php echo Lang::$word->NOTES;?>:</td>
      <td><?php echo cleanOut($row->description);?></td>
    </tr>
    <tr>
      <td><?php echo Lang::$word->FPRO_REVIEW;?>:</td>
      <td><?php echo cleanOut($row->review);?></td>
    </tr>
    <tr>
      <td><?php echo Lang::$word->FPRO_SUBMITTED;?>:</td>
      <td><?php echo Core::renderName($row);?></td>
    </tr>
    <tr>
      <td><?php echo Lang::$word->FPRO_DATESENT;?>:</td>
      <td><?php echo filter::dodate("long_date", $row->start);?></td>
    </tr>
    <tr>
      <td><?php echo Lang::$word->STATUS;?>:</td>
      <td><?php echo $content->projectSubmissionStatus($row->status);?></td>
    </tr>
    <?php if($row->status == 1):?>
    <tr>
      <td colspan="2"><a class="wojo button" id="do-submission"><?php echo Lang::$word->FPRO_APPREJ;?></a></td>
    </tr>
    <?php endif;?>
  </table>
</div>
<?php if($row->status == 1):?>
<div id="msgholder"></div>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function () {
    $('body').on('click', 'a#do-submission', function () {
        var text = '';
        text += '<form class="wojo segment form" id="record_form" method="post">';
        text += '<div class="field">';
		text += '<label><?php echo Lang::$word->FPRO_SUB6;?></label>';
        text += '<div class="inline-group">';
        text += '<label class="radio">';
        text += '<input type="radio" name="status" value="2" checked="checked">';
        text += '<i></i><?php echo Lang::$word->FPRO_APPROVE;?></label>';
        text += '<label class="radio">';
        text += '<input type="radio" name="status" value="3">';
        text += '<i></i><?php echo Lang::$word->FPRO_REJECT;?></label>';
        text += '</div>';
        text += '<div class="field">';
		text += '<label><?php echo Lang::$word->FPRO_COMMENTS;?></label>';
        text += '<textarea name="review" id="review" cols="50" rows="4"></textarea>';
        text += '</div>';
        text += '<input name="processSubmissionRecord" type="hidden" value="<?php echo Filter::$id;?>" />';
        text += '</form>';

        new Messi(text, {
            title: "<?php echo Lang::$word->FPRO_SUB5;?>",
            modal: true,
            closeButton: true,
            buttons: [{
                id: 0,
                label: "<?php echo Lang::$word->FPRO_SEND_REVIEW;?>",
                val: 'Y'
            }],
            callback: function (val) {
				$.ajax({
					type: 'post',
					url: "ajax/controller.php",
					data: $("#record_form").serialize(),
					success: function (res) {
						$("#msgholder").html(res);
					}
				});
            }
        });
    });
});
// ]]>
</script>
<?php endif;?>
<?php if(!$filerow):?>
<?php echo Filter::msgSingleInfo(Lang::$word->FILE_NOFILES);?>
<?php else:?>
<div class="wojo tertiary segment">
  <h3 class="wojo header"><?php echo Lang::$word->FPRO_TITLE2;?></h3>
  <table class="wojo basic table">
    <thead>
      <tr>
        <th><?php echo Lang::$word->FILE_NAME;?></th>
        <th><?php echo Lang::$word->FILESIZE;?></th>
        <th><?php echo Lang::$word->FDASH_SUBMITTED;?></th>
        <th><?php echo Lang::$word->VERSION;?></th>
        <th><?php echo Lang::$word->ACTION;?></th>
      </tr>
    </thead>
    <?php foreach ($filerow as $frow):?>
    <?php $file_info = is_file(UPLOADS . 'data/' . $frow->filename) ? getimagesize(UPLOADS . 'data/' . $frow->filename) : null;?>
    <?php $is_image = (!empty($file_info)) ? true : false;?>
    <tr>
      <td><?php echo $frow->title;?></td>
      <td><?php echo getSize($frow->filesize);?></td>
      <td><?php echo Filter::doDate("short_date", $frow->created);?></td>
      <td><?php echo $frow->version;?></td>
      <td><a class="viewdesc" data-id="<?php echo $frow->id;?>" data-content="<?php echo $frow->title;?>"><i class="rounded inverted purple icon laptop link"></i></a>
        <?php if($is_image):?>
        <a href="<?php echo UPLOADURL . 'data/' . $frow->filename;?>" class="lightbox" data-content="<?php echo Lang::$word->VIEW.': '.$frow->title;?>"><i class="rounded inverted black icon photo link"></i></a>
        <?php else:?>
        <a href="<?php echo UPLOADURL . 'data/' . $frow->filename;?>" data-content="<?php echo Lang::$word->DOWNLOAD;?>"><i class="rounded inverted black icon download cloud link"></i></a>
        <?php endif;?></td>
    </tr>
    <?php endforeach;?>
    <?php unset($frow);?>
  </table>
</div>
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function () {
    $('a.viewdesc').on('click', function () {
		var desc = $(this).data('content');
        Messi.load('ajax/controller.php', {
            viewFileDescData: 1,
            id: $(this).data('id')
        }, {
            title: "<?php echo Lang::$word->FILE_DESC;?> / " + desc
        });
    });
});
// ]]>
</script>
<?php endif;?>
<?php endif;?>
<?php break;?>
<?php default: ?>
<?php $projectrow = $user->getProjects();?>
<?php $subrow = $user->getSubmissions();?>
<div class="wojo tertiary segment">
  <h3 class="wojo header"> <?php echo Lang::$word->FPRO_TITLE4;?></h3>
  <div class="wojo message">
    <p><i class="pin icon"></i> <?php echo Lang::$word->FPRO_INFO2;?></p>
  </div>
  <?php if(!$projectrow):?>
  <?php echo Filter::msgSingleInfo(Lang::$word->PROJ_NOPROJECT);?>
  <?php else:?>
  <table class="wojo basic table">
    <thead>
      <tr>
        <th><?php echo Lang::$word->PROJ_NAME;?></th>
        <th><?php echo Lang::$word->PROJ_TYPE;?></th>
        <th><?php echo Lang::$word->PROJ_END;?></th>
        <th><?php echo Lang::$word->COMPLETED;?></th>
        <th><?php echo Lang::$word->VIEW;?></th>
      </tr>
    </thead>
    <?php foreach ($projectrow as $row):?>
    <tr>
      <td><?php echo $row->title;?></td>
      <td><?php echo $row->typetitle;?></td>
      <td><?php echo Filter::doDate("short_date", $row->end_date);?></td>
      <td><?php echo $content->progressBarStatus($row->p_status);?></td>
      <td><a href="account.php?do=projects&amp;action=viewproject&amp;id=<?php echo $row->id;?>" data-content="<?php echo Lang::$word->VIEW.': '.$row->title;?>"><i class="rounded inverted black icon laptop link"></i></a></td>
    </tr>
    <?php endforeach;?>
    <?php unset($row);?>
  </table>
</div>
<?php endif;?>
<?php if(!$subrow):?>
<?php echo Filter::msgSingleInfo(Lang::$word->SUBS_NOSUBS,false);?>
<?php else:?>
<div class="wojo tertiary segment">
  <h3 class="wojo header"><?php echo Lang::$word->FDASH_SUB;?></h3>
  <div class="wojo basic message"> <i class="pin icon"></i> <?php echo Lang::$word->FPRO_INFO3;?> </div>
  <table class="wojo basic table">
    <thead>
      <tr>
        <th><?php echo Lang::$word->FDASH_ITEM;?></th>
        <th><?php echo Lang::$word->PROJ_NAME;?></th>
        <th><?php echo Lang::$word->FDASH_SUBMITTED;?></th>
        <th><?php echo Lang::$word->FDASH_ACTION;?></th>
        <th><?php echo Lang::$word->VIEW;?></th>
      </tr>
    </thead>
    <?php foreach ($subrow as $row):?>
    <tr>
      <td><?php echo $row->title;?></td>
      <td><?php echo $row->ptitle;?></td>
      <td><?php echo Filter::doDate("short_date", $row->start);?></td>
      <td><?php echo ($row->status == 1) ? '<span class="wojo warning label">' . Lang::$word->FDASH_ACTION1 . '</span>': '<span class="wojo positive label">' . Lang::$word->FDASH_ACTION2 . '<span>';?></td>
      <td><a href="account.php?do=projects&amp;action=viewsubmission&amp;id=<?php echo $row->id;?>" data-content="<?php echo Lang::$word->VIEW.': '.$row->title;?>"><i class="rounded inverted black icon laptop link"></i></a></td>
    </tr>
    <?php endforeach;?>
    <?php unset($row);?>
  </table>
</div>
<?php endif;?>
<?php break;?>
<?php endswitch;?>