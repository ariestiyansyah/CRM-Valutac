<?php
  /**
   * Header Refresh
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2010
   * @version $Id: headerRefresh.php, v1.00 2011-04-20 10:12:05 gewa Exp $
   */
  // Date in the past
  header("Expires: Thu, 17 May 2001 10:17:17 GMT");
  // always modified
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  // HTTP/1.1
  header("Cache-Control: no-cache, must-revalidate");
  header("Pragma: no-cache");
  ob_start();
?>