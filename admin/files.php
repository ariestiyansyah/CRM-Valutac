<?php
  /**
   * Project Files
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: files.php, v3.00 2014-06-05 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Filter::$action): case "edit": ?>
<?php $row = $content->getFilesById();?>
<?php if($user->userlevel == 5 and !$row): print Filter::msgInfo(Lang::$word->ADMINONLY, false); return; endif;?>
<?php $typerow = $content->getProjectList(true);?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->FILE_INFO . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->FILE_SUB . $row->title;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->FILE_NAME;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" value="<?php echo $row->title;?>" name="title">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->FILE_SELPROJ;?></label>
        <select name="project_id">
          <option value="">--- <?php echo Lang::$word->FILE_SELPROJ;?> ---</option>
          <?php if($typerow):?>
          <?php foreach ($typerow as $prow):?>
          <option value="<?php echo $prow->id;?>"<?php if($prow->id == $row->project_id) echo ' selected="selected"';?>><?php echo $prow->title;?></option>
          <?php endforeach;?>
          <?php unset($prow);?>
          <?php endif;?>
        </select>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->FILE_ATTACH;?></label>
        <label class="input">
          <input type="file" name="filename" class="filefield">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->FILE_VER;?></label>
        <label class="input">
          <input type="text" value="<?php echo $row->version;?>" name="version">
        </label>
      </div>
    </div>
    <div class="field">
      <textarea class="bodypost" name="filedesc"><?php echo $row->filedesc;?></textarea>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->FILE_UPDATE;?></button>
    <a href="index.php?do=files" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processProjectFile" type="hidden" value="1">
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php case"add":?>
<?php $typerow = $content->getProjectList(true);?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->FILE_INFO1 . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->FILE_SUB1;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->FILE_NAME;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" placeholder="<?php echo Lang::$word->FILE_NAME;?>" name="title">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->FILE_SELPROJ;?></label>
        <select name="project_id">
          <option value="">--- <?php echo Lang::$word->FILE_SELPROJ;?> ---</option>
          <?php if($typerow):?>
          <?php foreach ($typerow as $prow):?>
          <option value="<?php echo $prow->id;?>"><?php echo $prow->title;?></option>
          <?php endforeach;?>
          <?php unset($prow);?>
          <?php endif;?>
        </select>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->FILE_ATTACH;?></label>
        <label class="input">
          <input type="file" name="filename" class="filefield">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->FILE_VER;?></label>
        <label class="input">
          <input type="text" placeholder="<?php echo Lang::$word->FILE_VER;?>" name="version">
        </label>
      </div>
    </div>
    <div class="field">
      <textarea class="bodypost" name="filedesc"></textarea>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->FILE_ADD;?></button>
    <a href="index.php?do=files" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processProjectFile" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php default:?>
<?php $filerow = $content->getProjectFiles();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->FILE_INFO2;?></div>
</div>
<div class="wojo basic block segment"> <a class="wojo basic button push-right" href="index.php?do=files&amp;action=add"><i class="icon add"></i> <?php echo Lang::$word->FILE_ADD;?></a>
  <h2 class="wojo left floated header"><i class="file icon"></i><?php echo Lang::$word->FILE_SUB2;?></h2>
</div>
<table class="wojo sortable table">
  <thead>
    <tr>
      <th data-sort="string"><?php echo Lang::$word->FILE_NAME;?></th>
      <th data-sort="string"><?php echo Lang::$word->PROJ_NAME;?></th>
      <th data-sort="int"><?php echo Lang::$word->FILESIZE;?></th>
      <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
    </tr>
  </thead>
  <?php if(!$filerow):?>
  <tr>
    <td colspan="4"><?php echo Filter::msgSingleInfo(Lang::$word->FILE_NOFILES);?></td>
  </tr>
  <?php else:?>
  <?php foreach ($filerow as $row):?>
  <?php $file_info = is_file(UPLOADS . 'data/' . $row->filename) ? getimagesize(UPLOADS . 'data/' . $row->filename) : null;?>
  <?php $is_image = (!empty($file_info)) ? true : false;?>
  <tr>
    <td><?php echo $row->title;?></td>
    <td><a href="index.php?do=projects&amp;action=edit&amp;id=<?php echo $row->pid;?>"><?php echo $row->ptitle;?></a></td>
    <td><?php echo getSize($row->filesize);?></td>
    <td><?php if($is_image):?>
      <a href="<?php echo UPLOADURL . 'data/' . $row->filename;?>" class="lightbox" data-content="<?php echo Lang::$word->VIEW.': '.$row->title;?>"><i class="rounded inverted warning icon photo link"></i></a>
      <?php else:?>
      <a href="<?php echo UPLOADURL . 'data/' . $row->filename;?>" data-content="<?php echo Lang::$word->DOWNLOAD;?>"><i class="rounded inverted info icon download cloud link"></i></a>
      <?php endif;?>
      <a href="index.php?do=files&amp;action=edit&amp;id=<?php echo $row->id;?>"><i class="rounded inverted success icon pencil link"></i></a> <a class="delete" data-title="<?php echo Lang::$word->FILE_DELFILE;?>" data-option="deleteProjectFile" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->title;?>"><i class="rounded danger inverted remove icon link"></i></a></td>
  </tr>
  <?php endforeach;?>
  <?php unset($row);?>
  <?php endif;?>
</table>
<?php break;?>
<?php endswitch;?>