<?php
  /**
   * Print Quote
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2011
   * @version $Id: print_quote.php, v1.00 2011-06-05 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  
  require_once("init.php");
  if (!$user->is_Admin())
    redirect_to("login.php");
	
  $row = $content->getQuoteById();
  $quotedata = $content->getQuoteEntries();
?>
<?php if($row):?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Invoice For &rsaquo;<?php echo $row->title;?></title>
<link rel="stylesheet" href="../assets/style/print_style.css">
</head>
<body>
<header>
  <h1>Quote</h1>
  <address>
  <p><?php echo Registry::get("Core")->company;?></p>
  <p><?php echo $core->address;?><br>
  <p><?php echo $core->city.', '.$core->state.' '.$core->zip;?></p>
  <p><?php echo ($core->phone) ? 'Phone: '.$core->phone : '';?></p>
  <p>Business Number: <?php echo $core->tax_number;?></p>
  <?php if($row->vat):?>
  <p>VAT: <?php echo $row->vat;?></p>
  <?php endif;?>
  </address>
  <span>
  <?php if(file_exists(UPLOADS.'print_logo.png')):?>
  <img src="<?php echo UPLOADURL;?>print_logo.png" alt="<?php echo Registry::get("Core")->company;?>" />
  <?php elseif (Registry::get("Core")->logo):?>
  <img src="<?php echo UPLOADURL.Registry::get("Core")->logo;?>" alt="<?php echo Registry::get("Core")->company;?>" />
  <?php else:?>
  <?php echo Registry::get("Core")->company;?>
  <?php endif;?>
  </span>
</header>
<article>
  <address>
  <p><?php echo $row->fullname;?><br />
    <?php echo $row->company;?><br />
    <?php echo $row->address;?><br />
    <?php echo $row->city.', '.$row->state.' '.$row->zip;?> <br />
    <?php echo ($row->phone) ? 'Phone: '.$row->phone : '';?></p>
  </address>

  <table class="meta">
    <tr>
      <th><span>Quote #</span></th>
      <td><span><?php echo $core->quote_number . $row->id;?></span></td>
    </tr>
    <tr>
      <th><span>Created</span></th>
      <td><span><?php echo Filter::dodate("short_date", $row->created);?></span></td>
    </tr>
    <tr>
      <th><span>Expires</span></th>
      <td><span><?php echo Filter::dodate("short_date", $row->expire);?></span></td>
    </tr>
    <tr>
      <th><span>Quote Total</span></th>
      <td><span><?php echo $core->formatMoney($row->amount_total);?></span></td>
    </tr>
  </table>
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
        <td><span><?php echo $irow->amount;?></span></td>
      </tr>
      <?php endforeach;?>
      <?php endif;?>
      <?php if($row->tax):?>
      <tr>
        <td><span><?php echo $core->tax_name;?></span></td>
        <td><span><?php echo $row->tax;?></span></td>
      </tr>
      <?php endif;?>
    </tbody>
  </table>
</article>
<aside>
  <div>
    <p><small class="extra"><?php echo cleanOut($row->notes);?></small></p>
  </div>
</aside>
<footer> <?php echo $core->company;?> | <?php echo $core->site_url;?></footer>
</body>
</html>
<?php else:?>
<?php die('You have selected invalid quote');?>
<?php endif;?>