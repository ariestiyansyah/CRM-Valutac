<?php
  /**
   * Project Submissions
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: submissions.php, v3.00 2014-05-08 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Filter::$action): case "edit": ?>
<?php (Filter::$id and !$row = $content->getProjectSubmissions(false)) ? Filter::error("You have selected an Invalid Id", "Content::getProjectSubmissions()") : "";?>
<?php if(!$user->checkProjectAccess($row->project_id)): print Filter::msgSingleInfo(Lang::$word->NOACCESS); return; endif;?>
<?php $plist = $content->getProjectList();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->SUBS_INFO . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->SUBS_SUB . $row->title;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->SUBS_NAME;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" value="<?php echo $row->title;?>" name="title">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PROJ_NAME;?></label>
        <?php if($user->userlevel == 9):?>
        <select name="project_id">
          <option value="">--- <?php echo Lang::$word->TASK_SELPROJ;?> ---</option>
          <?php if ($plist):?>
          <?php foreach ($plist as $prow):?>
          <option value="<?php echo $prow->id;?>"<?php if($prow->id == $row->project_id) echo ' selected="selected"';?>><?php echo $prow->title;?></option>
          <?php endforeach;?>
          <?php unset($prow);?>
          <?php endif;?>
        </select>
        <?php else:?>
        <?php echo getValueById("title", "projects", $row->project_id);?>
        <input name="project_id" type="hidden" value="<?php echo $row->project_id;?>" />
        <?php endif;?>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->SUBS_DATE;?></label>
        <label class="input"><i class="icon-prepend icon calendar"></i>
          <input name="sdate" type="text" disabled="disabled" value="<?php echo Filter::dodate("short_date", $row->created);?>" readonly>
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->SUBS_TYPE;?></label>
        <select name="s_type">
          <?php echo $content->projectSubmissionList($row->s_type);?>
        </select>
      </div>
    </div>
    <?php echo $content->rendertCustomFields('sb', $row->custom_fields);?>
    <div class="field">
      <textarea class="bodypost" name="description"><?php echo $row->description;?></textarea>
    </div>
    <div class="wojo double fitted divider"></div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->SUBS_STATUS;?></label>
        <?php echo $content->projectSubmissionStatus($row->status);?> </div>
      <div class="field">
        <label><?php echo Lang::$word->SUBS_CREVIEW;?></label>
        <?php echo cleanOut($row->review);?> </div>
      <div class="field">
        <label><?php echo Lang::$word->SUBS_DATEREVIEW;?></label>
        <?php echo Filter::dodate("long_date", $row->review_date);?> </div>
    </div>
    <div class="wojo double fitted divider"></div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->SUBS_SENDREVIEW;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="revsend" value="1" >
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="revsend" value="0" checked="checked">
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PROJ_MANAGER;?></label>
        <?php if($user->userlevel == 9):?>
        <select name="staff_id">
          <?php foreach ($user->getUserList("9' or userlevel = '5") as $srow):?>
          <option value="<?php echo $srow->id;?>"<?php if($srow->id == $row->staff_id) echo ' selected="selected"';?>><?php echo $srow->name;?></option>
          <?php endforeach;?>
          <?php unset($srow);?>
        </select>
        <?php else:?>
        <?php echo $user->name;?>
        <input name="staff_id" type="hidden" value="<?php echo $user->uid;?>" />
        <?php endif;?>
      </div>
    </div>
    <div class="wojo double fitted divider"></div>
    <?php $filerow = $content->getFilesByProject($row->project_id);?>
    <h3 class="wojo block header"><i class="icon reorder"></i> <?php echo Lang::$word->PROJ_FILES;?></h3>
    <table class="wojo sortable table">
      <thead>
        <tr>
          <th data-sort="string"><?php echo Lang::$word->FILE_NAME;?></th>
          <th data-sort="string"><?php echo Lang::$word->FILESIZE;?></th>
          <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
        </tr>
      </thead>
      <?php if(!$filerow):?>
      <tr>
        <td colspan="3"><?php echo Filter::msgSingleInfo(Lang::$word->FILE_NOFILES);?></td>
      </tr>
      <?php else:?>
      <?php foreach ($filerow as $frow):?>
      <?php $file_info = is_file(UPLOADS . 'data/' . $frow->filename) ? getimagesize(UPLOADS . 'data/' . $frow->filename) : null;?>
      <?php $is_image = (!empty($file_info)) ? true : false;?>
      <tr>
        <td><?php echo $frow->title;?></td>
        <td><?php echo getSize($frow->filesize);?></td>
        <td><?php if($is_image):?>
          <a href="<?php echo UPLOADURL . 'data/' . $frow->filename;?>" class="lightbox" data-content="<?php echo Lang::$word->VIEW.': '.$frow->title;?>"><i class="rounded inverted warning icon photo link"></i></a>
          <?php else:?>
          <a href="<?php echo UPLOADURL . 'data/' . $frow->filename;?>" data-content="<?php echo Lang::$word->DOWNLOAD;?>"><i class="rounded inverted info icon download cloud link"></i></a>
          <?php endif;?>
          <a href="index.php?do=files&amp;action=edit&amp;id=<?php echo $frow->id;?>"><i class="rounded inverted success icon pencil link"></i></a></td>
      </tr>
      <?php endforeach;?>
      <?php unset($frow);?>
      <?php endif;?>
    </table>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->SUBS_UPDATE;?></button>
    <a href="index.php?do=submissions&amp;id=<?php echo $row->project_id;?>" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processSubmission" type="hidden" value="1">
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php case"add": ?>
<?php $plist = $content->getProjectList();?>
<?php $row = Core::getRowById("projects", Filter::$id);?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->SUBS_INFO1 . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->SUBS_SUB1 . $row->title;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->SUBS_NAME;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" placeholder="<?php echo Lang::$word->SUBS_NAME;?>" name="title">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PROJ_NAME;?></label>
        <?php if($user->userlevel == 9):?>
        <select name="project_id">
          <option value="">--- <?php echo Lang::$word->TASK_SELPROJ;?> ---</option>
          <?php if ($plist):?>
          <?php foreach ($plist as $prow):?>
          <option value="<?php echo $prow->id;?>"<?php if($prow->id == $row->id) echo ' selected="selected"';?>><?php echo $prow->title;?></option>
          <?php endforeach;?>
          <?php unset($prow);?>
          <?php endif;?>
        </select>
        <?php else:?>
        <?php echo $row->title;?>
        <input name="project_id" type="hidden" value="<?php echo Filter::$id;?>" />
        <?php endif;?>
      </div>
    </div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->SUBS_SENDREVIEW;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="revsend" value="1" checked="checked">
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="revsend" value="0" >
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->SUBS_TYPE;?></label>
        <select name="s_type">
          <?php echo $content->projectSubmissionList();?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PROJ_MANAGER;?></label>
        <?php if($user->userlevel == 9):?>
        <select name="staff_id">
          <?php foreach ($user->getUserList("9' or userlevel = '5") as $srow):?>
          <option value="<?php echo $srow->id;?>"><?php echo $srow->name;?></option>
          <?php endforeach;?>
          <?php unset($srow);?>
        </select>
        <?php else:?>
        <?php echo $user->name;?>
        <input name="staff_id" type="hidden" value="<?php echo $user->uid;?>" />
        <?php endif;?>
      </div>
    </div>
    <?php echo $content->rendertCustomFields('sb', false);?>
    <div class="field">
      <textarea class="bodypost" name="description"></textarea>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->FILE_ATTACH;?></label>
        <label class="input">
          <input type="file" name="filename" class="filefield">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->FILE_NAME;?></label>
        <label class="input">
          <input type="text" name="filetitle" placeholder="<?php echo Lang::$word->FILE_NAME;?>">
        </label>
      </div>
    </div>
    <?php $filerow = $content->getFilesByProject(Filter::$id);?>
    <h3 class="wojo block header"><i class="icon reorder"></i> <?php echo Lang::$word->PROJ_FILES;?></h3>
    <table class="wojo sortable table">
      <thead>
        <tr>
          <th data-sort="string"><?php echo Lang::$word->FILE_NAME;?></th>
          <th data-sort="string"><?php echo Lang::$word->FILESIZE;?></th>
          <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
        </tr>
      </thead>
      <?php if(!$filerow):?>
      <tr>
        <td colspan="3"><?php echo Filter::msgSingleInfo(Lang::$word->FILE_NOFILES);?></td>
      </tr>
      <?php else:?>
      <?php foreach ($filerow as $frow):?>
      <?php $file_info = is_file(UPLOADS . 'data/' . $frow->filename) ? getimagesize(UPLOADS . 'data/' . $frow->filename) : null;?>
      <?php $is_image = (!empty($file_info)) ? true : false;?>
      <tr>
        <td><?php echo $frow->title;?></td>
        <td><?php echo getSize($frow->filesize);?></td>
        <td><?php if($is_image):?>
          <a href="<?php echo UPLOADURL . 'data/' . $frow->filename;?>" class="lightbox" data-content="<?php echo Lang::$word->VIEW.': '.$frow->title;?>"><i class="rounded inverted warning icon photo link"></i></a>
          <?php else:?>
          <a href="<?php echo UPLOADURL . 'data/' . $frow->filename;?>" data-content="<?php echo Lang::$word->DOWNLOAD;?>"><i class="rounded inverted info icon download cloud link"></i></a>
          <?php endif;?>
          <a href="index.php?do=files&amp;action=edit&amp;id=<?php echo $frow->id;?>"><i class="rounded inverted success icon pencil link"></i></a></td>
      </tr>
      <?php endforeach;?>
      <?php unset($frow);?>
      <?php endif;?>
    </table>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->SUBS_ADD;?></button>
    <a href="index.php?do=submissions&amp;id=<?php echo Filter::$id;?>" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processSubmission" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php default: ?>
<?php if(!$user->checkProjectAccess(Filter::$id)): print Filter::msgSingleInfo(Lang::$word->NOACCESS); return; endif;?>
<?php $subrow = $content->getProjectSubmissions();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->SUBS_INFO2;?></div>
</div>
<div class="wojo basic block segment"> <a class="wojo basic button push-right" href="index.php?do=submissions&amp;action=add&amp;id=<?php echo Filter::$id;?>"><i class="icon add"></i> <?php echo Lang::$word->SUBS_ADD;?></a>
  <h2 class="wojo left floated header"><i class="plus icon"></i><?php echo Lang::$word->SUBS_SUB2 . $content->getTitleById("projects");?></h2>
</div>
<table class="wojo sortable table">
  <thead>
    <tr>
      <th data-sort="string"><?php echo Lang::$word->SUBS_NAME;?></th>
      <th data-sort="string"><?php echo Lang::$word->SUBS_TYPE;?></th>
      <th data-sort="string"><?php echo Lang::$word->SUBS_STATUS;?></th>
      <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <td colspan="4"><a href="index.php?do=projects" class="wojo black button"><i class="icon left arrow"></i> <?php echo Lang::$word->PROJ_BACK;?></a></td>
    </tr>
  </tfoot>
  <?php if(!$subrow):?>
  <tr>
    <td colspan="4"><?php echo Filter::msgSingleInfo(Lang::$word->SUBS_NOSUBS);?></td>
  </tr>
  <?php else:?>
  <?php foreach ($subrow as $row):?>
  <tr>
    <td><?php echo $row->title;?></td>
    <td><?php echo $row->s_type;?></td>
    <td><?php echo $content->projectSubmissionStatus($row->status);?></td>
    <td><a href="index.php?do=submissions&amp;action=edit&amp;pid=<?php echo Filter::$id;?>&amp;id=<?php echo $row->id;?>"><i class="rounded inverted success icon pencil link"></i></a> <a class="delete" data-title="<?php echo Lang::$word->SUBS_DELETE;?>" data-option="deleteSubmission" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->title;?>"><i class="rounded danger inverted remove icon link"></i></a></td>
  </tr>
  <?php endforeach;?>
  <?php unset($row);?>
  <?php endif;?>
</table>
<?php break;?>
<?php endswitch;?>