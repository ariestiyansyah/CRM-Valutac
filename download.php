<?php
  /**
   * Dowmload
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2010
   * @version $Id: download.php, v1.00 2011-11-10 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  require_once("init.php");

  if (!$user->logged_in)
      redirect_to("index.php");
?>
<?php  
  $allowed_ext = array(
		'zip' => 'application/zip', 
		'rar' => 'application/x-rar-compressed', 
		'pdf' => 'application/pdf', 
		'doc' => 'application/msword', 
		'xls' => 'application/vnd.ms-excel', 
		'ppt' => 'application/vnd.ms-powerpoint', 
		'exe' => 'application/octet-stream', 
		'gif' => 'image/gif', 
		'png' => 'image/png', 
		'jpg' => 'image/jpeg', 
		'jpeg' => 'image/jpeg', 
		'mp3' => 'audio/mpeg', 
		'wav' => 'audio/x-wav', 
		'mpeg' => 'video/mpeg', 
		'mpg' => 'video/mpeg', 
		'mpe' => 'video/mpeg', 
		'mov' => 'video/quicktime', 
		'avi' => 'video/x-msvideo'
  );
  
  set_time_limit(0);

  if (ini_get('zlib.output_compression')) {
      ini_set('zlib.output_compression', 'Off');
  }

  if (isset($_GET['fid'])) {
      $fid = intval($_GET['fid']);
      $pid = intval($_GET['pid']);

      $row = $db->first("SELECT f.*" 
	  . "\n FROM project_files as f" 
	  . "\n LEFT JOIN projects as p ON p.id = f.project_id" 
	  . "\n WHERE p.client_id = " . $user->uid . " AND f.id = " . $fid);

      if ($row) {
          $fname = basename($row->filename);
          $file_path = '';
          Filter::fetchFile(UPLOADS . 'data/', $fname, $file_path);

          $fsize = filesize($file_path);
          $fext = strtolower(substr(strrchr($fname, "."), 1));

          if (!file_exists($file_path) || !is_file($file_path)) {
              redirect_to(SITEURL . "/account.php?msg=1");
              die();
          }

          if ($allowed_ext[$fext] == '') {
              $mtype = '';
              if (function_exists('mime_content_type')) {
                  $mtype = mime_content_type($file_path);
              } elseif (function_exists('finfo_file')) {
                  $finfo = finfo_open(FILEINFO_MIME);
                  $mtype = finfo_file($finfo, $file_path);
                  finfo_close($finfo);
              }
              if ($mtype == '') {
                  $mtype = "application/force-download";
              }
          } else {
              $mtype = $allowed_ext[$fext];
          }

          header("Pragma: public");
          header("Expires: 0");
          header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
          header("Cache-Control: public");
          header("Content-Description: File Transfer");
          header("Content-Type: $mtype");
          header("Content-Disposition: attachment; filename=\"$fname\"");
          header("Content-Transfer-Encoding: binary");
          header("Content-Length: " . $fsize);

          print Filter::readFile($file_path, true);
      } else {
          redirect_to(SITEURL . "/index.php");
      }
  }
?>