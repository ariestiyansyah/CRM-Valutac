<?php
  /**
   * Client Overview
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: overview.php, v3.00 2014-05-08 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php $urow = Core::getRowById("users", Filter::$id);?>
<?php $projectrow = $content->getProjectsForClient($urow->id);?>
<?php $invoicerow = $content->getInvoiceForClient($urow->id);?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->OVER_INFO;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><i class="users icon"></i><?php echo Lang::$word->OVER_SUB . $urow->fname.' '.$urow->lname;?></h2>
</div>
<table class="wojo basic table">
  <tr>
    <td><?php echo Lang::$word->INVC_CNAME;?>:</td>
    <td><a href="index.php?do=clients&amp;action=edit&amp;id=<?php echo $urow->id;?>"><?php echo Core::renderName($urow);?></a></td>
  </tr>
  <tr>
    <td><?php echo Lang::$word->INVC_CEMAIL;?>:</td>
    <td><a href="index.php?do=email&amp;emailid=<?php echo urlencode($urow->email);?>"><?php echo $urow->email;?></a></td>
  </tr>
  <tr>
    <td><?php echo Lang::$word->OVER_NOTE;?>:</td>
    <td><?php echo cleanOut(wordwrap($urow->notes,100,'<br />'));?></td>
  </tr>
</table>
<div class="wojo tripple fitted divider"></div>
<table class="wojo basic table">
  <thead>
    <tr>
      <th class="header"><?php echo Lang::$word->PROJ_NAME;?></th>
      <th class="header"><?php echo Lang::$word->PROJ_MANAGER;?></th>
      <th class="header"><?php echo Lang::$word->CREATED;?></th>
      <th class="header"><?php echo Lang::$word->STATUS;?></th>
      <th class="header"><?php echo Lang::$word->ACTIONS;?></th>
    </tr>
  </thead>
  <?php if($projectrow == 0):?>
  <tr>
    <td colspan="5"><?php echo Filter::msgSingleInfo(Lang::$word->PROJ_NOPROJECT);?></td>
  </tr>
  <?php else:?>
  <?php foreach ($projectrow as $row):?>
  <tr>
    <td><?php echo $row->title;?></td>
    <td><?php echo $row->staffname;?></td>
    <td><?php echo Filter::doDate("short_date", $row->start_date);?></td>
    <td><?php echo $content->progressBarStatus($row->p_status);?></td>
    <td><a href="index.php?do=projects&amp;action=edit&amp;id=<?php echo $row->pid;?>"><i class="rounded inverted success icon pencil link"></i></a>
      <?php if($user->userlevel == 9):?>
      <a class="delete" data-title="<?php echo Lang::$word->PROJ_DELETE;?>" data-option="deleteProject" data-id="<?php echo $row->pid;?>" data-name="<?php echo $row->title;?>"><i class="rounded danger inverted remove icon link"></i></a>
      <?php endif;?></td>
  </tr>
  <?php endforeach;?>
  <?php unset($row);?>
  <?php endif;?>
</table>
<div class="wojo tripple fitted divider"></div>
<table class="wojo basic table">
  <thead>
    <tr>
      <th class="header"><?php echo Lang::$word->INVC_NAME;?></th>
      <th class="header"><?php echo Lang::$word->TOTAL;?></th>
      <th class="header"><?php echo Lang::$word->PAID;?></th>
      <th class="header"><?php echo Lang::$word->OVER_BSTATUS;?></th>
      <th class="header"><?php echo Lang::$word->ACTIONS;?></th>
    </tr>
  </thead>
  <?php if(!$invoicerow):?>
  <tr>
    <td colspan="5"><?php echo Filter::msgSingleInfo(Lang::$word->INVC_NOINVOICE2);?></td>
  </tr>
  <?php else:?>
  <?php foreach ($invoicerow as $row):?>
  <tr>
    <td><?php echo $row->title;?></td>
    <td><?php echo $core->formatMoney($row->amount_total);?></td>
    <td><?php echo $core->formatMoney($row->amount_paid);?></td>
    <td><?php echo $content->progressBarBilling($row->amount_paid,$row->amount_total);?></td>
    <td><a href="index.php?do=invoices&amp;id=<?php echo $row->project_id;?>" data-content="<?php echo Lang::$word->VIEW.': '.$row->title;?>"><i class="rounded inverted warning icon bookmark link"></i></a></td>
  </tr>
  <?php endforeach;?>
  <?php unset($row);?>
  <?php endif;?>
</table>