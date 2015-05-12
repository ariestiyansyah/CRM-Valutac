<?php
  /**
   * Print Pdf
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2011
   * @version $Id: print_pdf.php, v1.00 2011-06-05 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if (!Registry::get("Users")->is_Admin())
    redirect_to("login.php");

  $row = Registry::get("Content")->getQuoteById();
  $quotedata = Registry::get("Content")->getQuoteEntries();
?>
<?php if($row):?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Invoice For &rsaquo;<?php echo $row->title;?></title>
<style type="text/css">
body { background-color: #fff; color: #333; font-family: DejaVu Serif, Helvetica, Times-Roman; font-size: 1em; margin: 0; padding: 0 }
table { font-size: 75%; width: 100%; border-collapse: separate; border-spacing: 2px }
th, td { position: relative; text-align: left; border-radius: .25em; border-style: solid; border-width: 1px; padding: .5em }
th { background: #EEE; border-color: #BBB }
td { border-color: #DDD }
h1 { font: bold 100% sans-serif; letter-spacing: .5em; text-align: center; text-transform: uppercase }
table.inventory { clear: both; width: 100% }
table.inventory th, table.payments th { font-weight: 700; text-align: center }
table.inventory td:nth-child(1) { width: 52% }
table.payments { padding-top: 20px }
table.balance th, table.balance td { width: 50% }
.green { background-color: #D5EEBE; color: #689340 }
.blue { background-color: #D0EBFB; color: #4995B1 }
.red { background-color: #FAD0D0; color: #AF4C4C }
.yellow { background-color: #FFC; color: #BBB840 }
#aside { padding-top: 30px; font-size: 65% }
small { font-size: 65%; line-height: 1.5em }
#aside h1 { border: none; border-bottom-style: solid; border-color: #999; border-width: 0 0 1px; margin: 0 0 1em }
table.inventory td.right { text-align: right; width: 12% }
table.payments td.right, table.balance td { text-align: right }
#footer { position: fixed; bottom: 0px; left: 0px; right: 0px; height: 100px; text-align: center; border-top: 2px solid #eee; font-size: 65%; padding-top: 5px }
</style>
</head>
<body>
<table border="0">
  <tr>
    <td style="width: 60%;" valign="top"><?php if(file_exists(UPLOADS.'print_logo.png')):?>
      <img src="<?php echo UPLOADS;?>print_logo.png" alt="<?php echo Registry::get("Core")->company;?>" />
      <?php elseif (Registry::get("Core")->logo):?>
      <img src="<?php echo UPLOADS . Registry::get("Core")->logo;?>" alt="<?php echo Registry::get("Core")->company;?>" />
      <?php else:?>
      <?php echo Registry::get("Core")->company;?>
      <?php endif;?></td>
    <td valign="top" style="width:40%;text-align: right"><h4 style="margin:0px;padding:0px;font-size: 12px;">Quote: #<?php echo Registry::get("Core")->quote_number . $row->id;?></h4>
      <h4 style="margin:0px;padding:0px;font-size: 12px;"><?php echo Filter::dodate("short_date", $row->created);?></h4></td>
  </tr>
</table>
<div style="background-color:#ddd;height:1px">&nbsp;</div>
<table style="padding-top:25px">
  <tr>
    <td valign="top" style="width:60%">Payment To</td>
    <td colspan="2" valign="top" style="width:40%">Bill To</td>
  </tr>
  <tr>
    <td valign="top" style="width:60%">
      <p><?php echo Registry::get("Core")->company;?><br />
        <?php echo Registry::get("Core")->address;?><br />
        <?php echo Registry::get("Core")->city.', '.Registry::get("Core")->state.' '.Registry::get("Core")->zip;?><br />
        <?php echo (Registry::get("Core")->phone) ? 'Phone: '.Registry::get("Core")->phone : '';?><br />
        Business Number: <?php echo Registry::get("Core")->tax_number;?></p></td>
    <td colspan="2" valign="top" style="width:40%">
      <p><?php echo $row->fullname;?><br />
        <?php echo $row->company;?><br />
        <?php echo $row->address;?><br />
        <?php echo $row->city.', '.$row->state.' '.$row->zip;?><br />
        <?php echo ($row->phone) ? 'Phone: '.$row->phone : '';?></p></td>
  </tr>
  <tr>
    <td valign="top" style="width:60%"><?php if($row->vat):?>
      <br />
      VAT: <?php echo $row->vat;?>
      <?php endif;?></td>
    <td valign="top" style="width:20%">Quote Total:<br />
      Expires:</td>
    <td valign="top" style="width:20%"><?php echo Registry::get("Core")->cur_symbol . ' ' . $row->amount_total;?><br />
      <?php echo Filter::dodate("short_date", $row->expire);?></td>
  </tr>
</table>
<div style="height:20px"></div>
<table class="inventory">
  <thead>
    <tr>
      <th><span>Quote Items</span></th>
      <th><span>Total</span></th>
    </tr>
  </thead>
  <tbody>
    <?php if($quotedata):?>
    <?php foreach ($quotedata as $irow):?>
    <tr>
      <td><span><?php echo $irow->title;?> <small>(<?php echo $irow->description;?>)</small></span></td>
      <td class="right"><span><?php echo $irow->amount;?></span></td>
    </tr>
    <?php endforeach;?>
    <?php if($row->tax):?>
    <tr>
      <td align="right"><?php echo Registry::get("Core")->tax_name;?>:</td>
      <td align="right"><?php echo $row->tax;?></td>
    </tr>
    <?php endif;?>
    <?php endif;?>
  </tbody>
</table>

<div id="aside">
  <div>
    <p><small class="extra"><?php echo cleanOut($row->notes);?></small></p>
  </div>
  <div style="height:30px"></div>
  <div id="footer"><?php echo Registry::get("Core")->company;?> | <?php echo Registry::get("Core")->site_url;?></div>
</div>
</body>
</html>
<?php else:?>
<?php die('You have selected invalid invoice');?>
<?php endif;?>