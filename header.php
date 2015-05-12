<?php
  /**
   * Header
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: header.php, v3.00 2014-08-10 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<!DOCTYPE html>
<head>
<meta charset="utf-8">
<title><?php echo $core->company;?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="<?php echo Core::protocol();?>://fonts.googleapis.com/css?family=Roboto:400,300,500,700,100" rel="stylesheet" type="text/css">
<link href="<?php echo THEMEURL . '/cache/' . Minify::cache(array('css/base.css','css/button.css','css/image.css','css/icon.css','css/form.css','css/input.css','css/table.css','css/label.css','css/segment.css','css/message.css','css/divider.css','css/dropdown.css','css/list.css','css/header.css','css/menu.css','css/datepicker.css','css/breadcrumb.css','css/progress.css','css/editor.css','css/popup.css','css/utility.css','css/style.css'),'css');?>" rel="stylesheet" type="text/css" />
<script type="text/javascript">
var SITEURL = "<?php echo SITEURL;?>";
</script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.js"></script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/modernizr.mq.js"></script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/global.js"></script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/editor.js"></script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.ui.touch-punch.js"></script>
<script type="text/javascript" src="<?php echo THEMEURL;?>/js/master.js"></script>
</head>
<body>