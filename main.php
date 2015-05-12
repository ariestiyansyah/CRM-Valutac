<?php
  /**
   * Main
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: main.php, v3.00 2014-06-05 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php $subrow = $user->getSubmissions();?>
<?php $invactive = $user->getClientInvoices("<> 'Paid'");?>
<?php $completed = $user->countUserProjects("p_status = 100");?>
<?php $pending = $user->countUserProjects("p_status <> 100");?>
<div class="top-space">
  <div class="columns small-gutters">
    <div class="screen-25 mobile-50 phone-100">
      <div class="wojo negative inverted basic segment">
        <div class="wojo huge font content-center">
          <div class="wojo top right basic attached label"><i class="icon payment"></i></div>
          <?php echo count($invactive);?></div>
        <p class="wojo small font content-center"><?php echo Lang::$word->FDASH_SUB1;?></p>
      </div>
    </div>
    <div class="screen-25 mobile-50 phone-100">
      <div class="wojo purple inverted basic segment">
        <div class="wojo huge font content-center">
          <div class="wojo top right attached label"><i class="icon edit"></i></div>
          <?php echo count($subrow);?></div>
        <p class="wojo small font content-center"><?php echo Lang::$word->FDASH_SUB;?></p>
      </div>
    </div>
    <div class="screen-25 mobile-50 phone-100">
      <div class="wojo positive inverted basic segment">
        <div class="wojo huge font content-center">
          <div class="wojo top right attached label"><i class="icon send"></i></div>
          <?php echo $completed;?></div>
        <p class="wojo small font content-center"><?php echo Lang::$word->DASH_COMPLETED;?></p>
      </div>
    </div>
    <div class="screen-25 mobile-50 phone-100">
      <div class="wojo warning inverted basic segment">
        <div class="wojo huge font content-center">
          <div class="wojo top right attached label"><i class="icon send"></i></div>
          <?php echo $pending;?></div>
        <p class="wojo small font content-center"><?php echo Lang::$word->DASH_PENDING;?></p>
      </div>
    </div>
  </div>
</div>
<div class="wojo icon black message notice"> <i class="pin icon"></i>
  <div class="content">
    <p><?php echo Lang::$word->FDASH_INFO;?></p>
  </div>
</div>
<?php if($user->userlevel == 9 or $user->userlevel == 5):?>
<p class="wojo warning message"><?php echo Lang::$word->ADMINONLY_1;?></p>
<?php else:?>
<div class="wojo basic segment">
  <h3 class="wojo header"><?php echo Lang::$word->FDASH_SUB;?></h3>
  <?php if($subrow):?>
  <table class="wojo basic table">
    <thead>
      <tr>
        <th><?php echo Lang::$word->FDASH_ITEM;?></th>
        <th><?php echo Lang::$word->FDASH_PROJECT;?></th>
        <th><?php echo Lang::$word->FDASH_SUBMITTED;?></th>
        <th><?php echo Lang::$word->FDASH_ACTION;?></th>
        <th><?php echo Lang::$word->VIEW;?></th>
      </tr>
    </thead>
    <?php foreach ($subrow as $row):?>
    <tr>
      <td><?php echo $row->title;?></td>
      <td><?php echo $row->ptitle;?></td>
      <td><?php echo Filter::doDate("short_date", $row->created);?></td>
      <td><?php echo ($row->status == 1) ? '<span class="wojo negative label">' . Lang::$word->FDASH_ACTION1 . '</span>': Lang::$word->FDASH_ACTION2;?></td>
      <td><a href="account.php?do=projects&amp;action=viewsubmission&amp;id=<?php echo $row->id;?>" data-content="<?php echo Lang::$word->VIEW.': '.$row->title;?>"><i class="rounded inverted black icon laptop link"></i></a></td>
    </tr>
    <?php endforeach;?>
    <?php unset($row);?>
  </table>
</div>
<?php endif;?>
<?php if($invactive):?>
<div class="wojo tertiary segment">
  <h3 class="wojo header"><?php echo Lang::$word->FDASH_SUB1;?></h3>
  <table class="wojo basic table">
    <thead>
      <tr>
        <th>#</th>
        <th><?php echo Lang::$word->FDASH_SUB1;?></th>
        <th><?php echo Lang::$word->INVC_DUEDATE;?></th>
        <th><?php echo Lang::$word->TOTAL;?> / <?php echo Lang::$word->PAID;?></th>
        <th><?php echo Lang::$word->FDASH_METHOD;?></th>
        <th><?php echo Lang::$word->ACTION;?></th>
      </tr>
    </thead>
    <?php foreach ($invactive as $row):?>
    <tr>
      <td><small><?php echo $core->invoice_number . $row->id;?></small></td>
      <td><a href="account.php?do=billing&amp;action=viewinvoice&amp;id=<?php echo $row->id;?>"><?php echo $row->title;?></a></td>
      <td><?php echo Filter::doDate("short_date", $row->duedate);?></td>
      <td><?php echo $row->amount_total;?> / <?php echo $row->amount_paid;?></td>
      <td><?php echo $row->method;?></td>
      <td><a href="account.php?do=billing&amp;action=invoice&amp;id=<?php echo $row->id;?>" class="wojo warning label"><?php echo Lang::$word->FDASH_PAY;?></a></td>
    </tr>
    <?php endforeach;?>
    <?php unset($row);?>
  </table>
</div>
<?php endif;?>
<?php endif;?>