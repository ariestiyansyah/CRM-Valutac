<?php
  /**
   * Filemanager Class
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: class_fm.php, v4.00 2014-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  class Filemanager
  {
      private static $base_dir;
      public $cur_dir;
	  public $rel_dir;
      private $dir_list = array();
      private $file_list = array();
      private $dir_count = 0;
      private $file_count = 0;
      private $cdirs = 0;
      private $cfiles = 0;
      const maxFile = 52428800;
      const DS = '/';

      public static $fileTypes = array(
          "gif",
          "jpg",
          "jpeg",
          "bmp",
          "png",
          "psd",
          "txt",
          "nfo",
          "doc",
          "docx",
          "xls",
          "xlsx",
          "htm",
          "html",
          "zip",
          "rar",
          "tar",
          "css",
          "pdf",
          "swf",
          "avi",
          "mp4",
          "ogv",
          "webm",
          "mp3");


      /**
       * FileManager::__construct()
       * 
       * @return
       */
      public function __construct()
      {
          self::$base_dir = str_replace("\\", "/", UPLOADS);

          if (isset($_REQUEST['cdir'])) {
              $this->rel_dir = str_replace("../", "", $_REQUEST['cdir']);
              $this->rel_dir = str_replace(".", "", $_REQUEST['cdir']);
              $this->rel_dir = str_replace("\\", "/", $_REQUEST['cdir']);
          } else {
              $this->rel_dir = null;
          }
      }


      /**
       * FileManager::renderAll()
       * 
       * @return
       */
      public function renderAll()
      {
		  
          if (is_dir(UPLOADS . $this->rel_dir) and opendir(UPLOADS . $this->rel_dir) == true) {
              $handler = opendir(UPLOADS . $this->rel_dir);
              $this->cur_dir = self::$base_dir . $this->rel_dir;
              $fileurl = UPLOADURL . $this->rel_dir;
          } else {
              $handler = opendir(UPLOADS);
              $this->cur_dir = UPLOADS;
              $fileurl = UPLOADURL;
              $this->rel_dir = null;
          }
          $parent_dir = str_replace('.', '', dirname($this->rel_dir));
          $dirs = $files = array();

          while (false !== ($file = readdir($handler))) {

              if ($file != "." && $file != ".." && $file != "Thumbs" && $file != ".htaccess" && $file != "index.php") {
                  if (filetype($this->cur_dir . self::DS . $file) == 'dir') {
					  
					  $link = self::fixPath($this->rel_dir . self::DS . $file);
					  $link = ltrim($link, "/");

                      $dirs[] = array(
                          'name' => $file,
                          'path' => self::fixPath(ltrim($this->rel_dir . self::DS . $file, "/")),
                          'link' => $link,
                          'size' => 0,
                          'type' => 'dir',
                          'time' => filemtime($this->cur_dir . self::DS . $file),
                          'ftime' => date('d-m-Y', filemtime($this->cur_dir . self::DS . $file)),
                          'ext' => 'folder.png');

                  } else {
					  $link = self::fixPath($this->rel_dir . self::DS . $file);
					  $link = ltrim($link, "/");
					  $path = self::fixPath($this->rel_dir . self::DS . $file);
					  $path = ltrim($path, "/");

                      $ext = explode(".", $this->cur_dir . self::DS . $file, strlen($this->cur_dir . self::DS . $file));
                      $extn = $ext[count($ext) - 1];
                      $showimg = '<a href="' . $link . '" class="lightbox" title="' . $file . '">' . $file . '</a>';

                      $files[] = array(
                          'name' => self::getFileType($file) == 'image' ? $showimg : $file,
                          'fname' => $file,
                          'path' => $path,
						  'dir' => self::fixPath(ltrim($this->rel_dir . self::DS . "/")),
                          'size' => getSize(filesize($this->cur_dir . self::DS . $file)),
						  'mime' => self::_getMIMEtype($this->cur_dir . self::DS . $file),
                          'fsize' => filesize($this->cur_dir . self::DS . $file),
                          'type' => self::getFileType($file),
                          'time' => filemtime($this->cur_dir . self::DS . $file),
                          'ftime' => date('d-m-Y', filemtime($this->cur_dir . self::DS . $file)),
                          'ext' => self::getFileIcon($extn),
                          'link' => UPLOADURL . $link
						  );
                  }
              }
          }


          closedir($handler);

          if ($this->rel_dir != '') {

              $link = str_replace(UPLOADS, '', $parent_dir);
              $link = ltrim($link, '/\\');

              array_unshift($dirs, array(
                  'name' => Lang::$word->_FM_BACK,
                  'path' => '..',
                  'link' => 'index.php?do=filemanager&amp;cdir=' . $link,
                  'size' => 0,
                  'type' => 'back',
                  'time' => 0,
                  'buffer' => false,
                  ));
          }
		  
          $breadcrumb = array();
          $next = '';
          $back = Lang::$word->_FM_HOME . '/' . str_replace(UPLOADS, '', trim($this->rel_dir, '/'));
          $back = explode('/', $back);
          foreach ($back as $key => $backlink) {
              if ($backlink != Lang::$word->_FM_HOME) {
                  $next .= self::DS . $backlink;
                  $next = ltrim($next, '/');
              }
              if (empty($this->rel_dir)) {
                  $crumbs[Lang::$word->_FM_HOME] = 'index.php?do=filemanager';
              } else {
                  $crumbs[$backlink] = 'index.php?do=filemanager&amp;cdir=' . $next;
              }
          }
		  
          $this->dir_count = count($dirs) - 1;
          $this->file_count = count($files);
		  
          return array(
              'dirs' => $dirs,
              'files' => $files,
              'crumbs' => $crumbs,
              'dir_count' => $this->dir_count,
              'file_count' => $this->file_count,
              'directory' => $this->rel_dir
			  );

	  }

      /**
       * Filemanager::makeFile()
       * 
       * @param mixed $filename
       * @return
       */
	  public static function makeFile($filename)
	  {
		  $path = self::fixPath(UPLOADS . $_REQUEST['fdirectory'] . '/');
	
		  if (isset($filename)) {

			  if (empty($_POST['filename'])) {
				  $json['status'] = "error";
				  $json['title'] = Lang::$word->_ERROR;
				  $json['msg'] = Lang::$word->_FM_NAME_R;
				  print json_encode($json);
				  exit;
			  }
			  $extension = pathinfo($filename, PATHINFO_EXTENSION);
			  if (!in_array(strtolower($extension), self::$fileTypes)) {
				  $json['status'] = "error";
				  $json['title'] = Lang::$word->_ERROR;
				  $json['msg'] = str_replace("[EXT]", $extension, Lang::$word->_FM_FILE_ERR5);
				  print json_encode($json);
				  exit;
			  }
	
			  if (file_exists($path . $filename)) {
				  $json['status'] = "error";
				  $json['title'] = Lang::$word->_ERROR;
				  $json['msg'] = Lang::$word->_FM_FILE_ERR1;
				  print json_encode($json);
				  exit;
			  }

			  if (!is_writeable($path)) {
				  $json['status'] = "error";
				  $json['title'] = Lang::$word->_ERROR;
				  $json['msg'] = Lang::$word->_FM_FILE_ERR2;
				  print json_encode($json);
				  exit;
			  }
	
			  if (!is_dir($path)) {
				  $json['status'] = "error";
				  $json['title'] = Lang::$word->_ERROR;
				  $json['msg'] = Lang::$word->_FM_FILE_ERR4;
				  print json_encode($json);
				  exit;
			  }
	
			  $opt = pathinfo($filename);
			  $caption = str_replace("_", " ", $opt['filename']);
	          $html = '';
			  if (touch($path . $filename)) {
	
				  $ext = explode(".", $filename);
				  $extn = $ext[count($ext) - 1];
				  $newfile = $path . $filename;
				  $filepath = self::fixPath($_REQUEST['fdirectory'] . self::DS . $filename);
				  
				  $files = count(glob("$path/*")) - count(glob("$path/*", GLOB_ONLYDIR));
			  
				  $html .= '			  
					  <tr class="warning">
						<td><img src="assets/images/mime/small/' . self::getFileIcon($extn) . '" alt="" /></td>
						<td><span class="edit" data-id="' . time() . '" data-dir="' . $_REQUEST['fdirectory'] . '" data-name="' . $filename . '"> ' . $filename . '</span></td>
						<td>0 B</td>
						<td>' . self::_getMIMEtype($newfile) . '</td>
						<td>' . date('d-m-Y', filemtime($newfile)) . '</td>
						<td>';
				  $html .= '<a class="remove" data-name="' . $filename . '" data-dir="' . $_REQUEST['fdirectory'] . '" data-path="' . $filepath . '"><i class="circular danger remove icon link"></i></a></td>
					  </tr>';
	
				  $json['status'] = "success";
				  $json['fcount'] = $files;
				  $json['msg'] = $html;
				  print json_encode($json);
				  exit;
			  }
		  }
	
		  $json['status'] = "error";
		  exit;
	  }

      /**
       * Filemanager::makeDirectory()
       * 
       * @param mixed $dirname
       * @return
       */
	  public static function makeDirectory($dirname)
	  {
		  $path = self::fixPath(UPLOADS . $_REQUEST['fdirectory'] . '/');
	
		  if (isset($dirname)) {

			  if (empty($dirname)) {
				  $json['status'] = "error";
				  $json['title'] = Lang::$word->_ERROR;
				  $json['msg'] = Lang::$word->_FM_DIRNAME_R;
				  print json_encode($json);
				  exit;
			  }
			  
			  if (is_dir($path . $dirname)) {
				  $json['status'] = "error";
				  $json['title'] = Lang::$word->_ERROR;
				  $json['msg'] = Lang::$word->_FM_FILE_ERR6;
				  print json_encode($json);
				  exit;
			  }

			  if (!is_writeable($path)) {
				  $json['status'] = "error";
				  $json['title'] = Lang::$word->_ERROR;
				  $json['msg'] = Lang::$word->_FM_FILE_ERR2;
				  print json_encode($json);
				  exit;
			  }
	
			  if (!is_dir($path)) {
				  $json['status'] = "error";
				  $json['title'] = Lang::$word->_ERROR;
				  $json['msg'] = Lang::$word->_FM_FILE_ERR4;
				  print json_encode($json);
				  exit;
			  }

	          $html = '';
			  if (mkdir($path . $dirname)) {
				  $folder = substr($path, strlen(UPLOADS)) . '/' . $dirname;
				  $html .= '<div class="item"><a class="left floated wojo small button" data-id="' . time() . '" href="index.php?do=filemanager&amp;cdir=' . urlencode(self::fixPath($folder)) . '"> <i class="icon folder"></i> ' . self::fixPath($dirname) . ' </a> <a class="remdir right floated" data-path="' . self::fixPath($folder) . '" data-name="' . $dirname . '"><i class="rounded danger inverted trash icon link"></i></a></div>';
	
				  $json['status'] = "success";
				  $json['dcount'] = (count(glob("$path/*", GLOB_ONLYDIR)));;
				  $json['msg'] = $html;
				  print json_encode($json);
				  exit;
			  }
		  }
	
		  $json['status'] = "error";
		  exit;
	  }

      /**
       * FileManager::deleteFile()
       * 
       * @return
       */
	  public static function deleteFile()
	  {
		  $path = self::fixPath(UPLOADS . $_POST['path']);

		  if (file_exists($path)) {
			  unlink($path);
	
			  $count = self::fixPath(UPLOADS . dirname($_POST['path']) . self::DS);
			  $files = count(glob("$count/*")) - count(glob("$count/*", GLOB_ONLYDIR));
	
			  $json['status'] = "success";
			  $json['title'] = Lang::$word->_SUCCESS;
			  $json['fcount'] = $files;
			  $json['msg'] = str_replace("[NAME]", $_POST['name'], Lang::$word->_FM_DELETE_OK);
		  } else {
			  $json['status'] = "error";
			  $json['title'] = Lang::$word->_ERROR;
			  $json['msg'] = str_replace("[NAME]", $_POST['name'], Lang::$word->_FM_DELETE_ERR);
		  }

		  print json_encode($json);
	  }
	  
      /**
       * FileManager::deleteDir()
       * 
       * @return
       */
	  public static function deleteDir()
	  {
		  $path = self::fixPath(UPLOADS . $_POST['path']);
	
		  if (is_dir($path)) {
			  if (self::purge($path)) {
				  $json['status'] = "success";
				  $json['title'] = Lang::$word->_SUCCESS;
				  $json['dcount'] = (count(glob("$path/*", GLOB_ONLYDIR)));
				  $json['msg'] = str_replace("[NAME]", $_POST['name'], Lang::$word->_FM_DELETED_OK);
			  } else {
				  $json['status'] = "error";
				  $json['title'] = Lang::$word->_ERROR;
				  $json['msg'] = str_replace("[NAME]", $_POST['name'], Lang::$word->_FM_DELETED_ERR);
			  }
	
		  } else {
			  $json['status'] = "error";
			  $json['title'] = Lang::$word->_ERROR;
			  $json['msg'] = str_replace("[NAME]", $_POST['name'], Lang::$word->_FM_DELETED_ERR);
		  }
	
		  print json_encode($json);
	  }

      /**
       * FileManager::getDirectories()
       * 
       * @return
       */
      public static function getDirectories()
      {
          $dir = self::fixPath(UPLOADS);
		  $iter = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS), 
		  RecursiveIteratorIterator::SELF_FIRST, RecursiveIteratorIterator::CATCH_GET_CHILD);

		  $paths = array();
		  foreach ($iter as $path => $dir) {
			  if ($dir->isDir()) {
				  $folder = substr($path, strlen(UPLOADS)) . '/';
				  $paths[] = '<div class="item" data-value="' . self::fixPath($folder . '/') . '"><i class="icon folder"></i>' . self::fixPath($folder . '/') . '</div>';
			  }
		  }
		  
		  $json['type'] = 'success';
		  $json['message'] = $paths;
		  
		   print json_encode($json);

      }

      /**
       * FileManager::getFiles()
       * 
       * @return
       */
      public static function getFiles()
      {
		  $wdir = $_REQUEST['directory'];
		  $dir = UPLOADS . $wdir;
		  
		  $flags = FilesystemIterator::SKIP_DOTS | FilesystemIterator::FOLLOW_SYMLINKS;
		  $iterator = new RecursiveDirectoryIterator($dir, $flags);
		  $result = array();
		  foreach ($iterator as $file) {
			  
			  if($file->isFile() and !preg_match("/\.(htaccess|php)*$/i", $file, $matches)) {
				$type = self::getFileType($file->getFilename());
				$info = pathinfo($file->getFilename());
				$name = $info['filename'];
				$ext  = $info['extension'];
				if($type != "image") {
				$result[] = '<li><a><img class="item" data-title="' . $name . '" data-type="' . $type . '" data-name="' . $file->getFilename() . '" data-path="' . $wdir . '" src="' . ADMINURL .  '/assets/images/mime/' . self::getFileIcon($ext) . '" alt="' . $name . '"></a></li>';		
				} else {
				$result[] = '<li><a><img class="item" data-title="' . $name . '" data-type="' . $type . '" data-name="' . $file->getFilename() . '" data-path="' . $wdir . '" src="' . UPLOADURL . $wdir . $file->getFilename() . '" alt="' . $name . '"></a></li>';					
				}

			  }
		  }
	
		  $json['type'] = 'success';
		  $json['message'] = $result;
		  
		   print json_encode($json);

      }
	  
      /**
       * Filemanager::doUpload()
       * 
       * @param mixed $filename
       * @return
       */
	  public static function doUpload($filename)
	  {
		  $path = self::fixPath(UPLOADS . $_REQUEST['fdirectory'] . '/');
	
		  if (isset($_FILES[$filename]) && $_FILES[$filename]['error'] == 0) {
	
			  $extension = pathinfo($_FILES[$filename]['name'], PATHINFO_EXTENSION);
			  if (!in_array(strtolower($extension), self::$fileTypes)) {
				  $json['status'] = "error";
				  $json['msg'] = str_replace("[EXT]", $extension, Lang::$word->_FM_FILE_ERR5);
				  print json_encode($json);
				  exit;
			  }
	
			  if (file_exists($path . $_FILES[$filename]['name'])) {
				  $json['status'] = "error";
				  $json['msg'] = Lang::$word->_FM_FILE_ERR1;
				  print json_encode($json);
				  exit;
			  }

			  if (!is_writeable($path)) {
				  $json['status'] = "error";
				  $json['msg'] = Lang::$word->_FM_FILE_ERR2;
				  print json_encode($json);
				  exit;
			  }
	
			  if (!is_dir($path)) {
				  $json['status'] = "error";
				  $json['msg'] = Lang::$word->_FM_FILE_ERR4;
				  print json_encode($json);
				  exit;
			  }

			  if (self::maxFile != null && self::maxFile < $_FILES[$filename]['size']) {
				  $json['status'] = "error";
				  $json['msg'] = str_replace("[LIMIT]", getSize(self::maxFile), Lang::$word->_FM_FILE_ERR3);
				  print json_encode($json);
				  exit;
			  }
	
			  $opt = pathinfo($_FILES[$filename]['name']);
			  $caption = str_replace("_", " ", $opt['filename']);
	          $html = '';
			  if (move_uploaded_file($_FILES[$filename]['tmp_name'], $path . $_FILES[$filename]['name'])) {
	
				  $ext = explode(".", $_FILES[$filename]['name']);
				  $extn = $ext[count($ext) - 1];
				  $newfile = $path . $_FILES[$filename]['name'];
				  $filepath = self::fixPath($_REQUEST['fdirectory'] . self::DS . $_FILES[$filename]['name']);
				  
				  $files = count(glob("$path/*")) - count(glob("$path/*", GLOB_ONLYDIR));
			  
				  $html .= '			  
					  <tr>
						<td><img src="assets/images/mime/small/' . self::getFileIcon($extn) . '" alt="" /></td>
						<td><span class="edit" data-id="' . time() . '" data-dir="' . $_REQUEST['fdirectory'] . '" data-name="' . $_FILES[$filename]['name'] . '"> ' . $_FILES[$filename]['name'] . '</span></td>
						<td>' . getSize($_FILES[$filename]['size']) . '</td>
						<td>' . self::_getMIMEtype($newfile) . '</td>
						<td>' . date('d-m-Y', filemtime($newfile)) . '</td>
						<td>';
						if (self::getFileType($_FILES[$filename]['name']) == "image") {
							$html .= '<a href="' . UPLOADURL . $filepath . '" title="' . $_FILES[$filename]['name'] . '" class="lightbox"><i class="circular green unhide icon link"></i></a>';
						}
				  $html .= '<a class="remove" data-name="' . $_FILES[$filename]['name'] . '" data-dir="' . $_REQUEST['fdirectory'] . '" data-path="' . $filepath . '"><i class="circular red remove icon link"></i></a></td>
					  </tr>';
	
				  $json['status'] = "success";
				  $json['fcount'] = $files;
				  $json['msg'] = $html;
				  print json_encode($json);
				  exit;
			  }
		  }
	
		  $json['status'] = "error";
		  exit;
	  }

      /**
       * FileManager::getPickerFiles()
       * 
       * @param mixed $dir
       * @param bool $filter
       * @return
       */
	  public static function getPickerFiles($dir, $filter = false)
	  {
		  $filedata = self::scanFiles($dir, $filter);
	
		  $html = '';
		  if ($filedata) {
			  natsort($filedata);
			  $html .= '<ul class="filegrid alt" style="max-width:470px">';
			  foreach ($filedata as $row):
				  $row = self::fixpath($row);
				  $upl = self::fixpath(UPLOADS);
				  $data = str_replace($upl, "", $row);
				  $ext = explode(".", $data);
				  $extn = $ext[count($ext) - 1];
				  if(self::getFileType($data) == "image") {
					  $img = '<img src="' . UPLOADURL . $data . '">';
				  } else {
					  $img = '<img src="assets/images/mime/' . self::getFileIcon($extn) . '">';
				  }
	
				  $html .= '<li class="filelist"><a data-path="' . $data . '">' . $img . '</a></li>';
	
			  endforeach;
			  $html .= '</ul>';
		  }
	
		  return $html;
	
	  }
	  
      /**
       * Filemanager::getFileType()
       * 
       * @param mixed $filename
       * @return
       */
      public static function getFileType($filename)
      {
          if (preg_match("/^.*\.(jpg|jpeg|png|gif|bmp)$/i", $filename) != 0) {
              return 'image';

          } elseif (preg_match("/^.*\.(txt|css|php|sql|js)$/i", $filename) != 0) {
              return 'text';

          } elseif (preg_match("/^.*\.(zip)$/i", $filename) != 0) {
              return 'zip';
          }
          return 'generic';
      }
	  
      /**
       * Filemanager::getFileIcon()
       * 
       * @param mixed $extn
       * @return
       */
      private static function getFileIcon($extn)
      {
          switch ($extn) {
              case "css":
                  return "css.png";
                  break;

              case "csv":
                  return "csv.png";
                  break;

              case "fla":
              case "swf":
                  return "fla.png";
                  break;

              case "mp3":
                  return "mp3.png";
                  break;

              case "wav":
                  return "waw.png";
                  break;

              case "mp4":
                  return "mp4.png";
                  break;

              case "ogg":
                  return "ogg.png";
                  break;
				    
              case "jpg":
              case "JPG":
                  return "jpg.png";
                  break;

              case "gif":
                  return "gif.png";
                  break;
				  
              case "png":
                  return "png.png";
                  break;
				  
              case "bmp":
              case "dib":
                  return "bmp.png";
                  break;

              case "txt":
              case "log":
              case "sql":
                  return "txt.png";
                  break;

              case "js":
                  echo "js.png";
                  break;

              case "pdf":
                  return "pdf.png";
                  break;

              case "psd":
                  return "psd.png";
                  break;
				  
              case "zip":
              case "tgz":
              case "gz":
                  return "zip.png";
                  break;

              case "rar":
                  return "rar.png";
                  break;
				  
              case "doc":
			  case "docx":
              case "rtf":
                  return "doc.png";
                  break;

              case "xls":
              case "xlsx":
                  return "xls.png";
                  break;
				  
              case "asp":
              case "jsp":
                  echo "asp.png";
                  break;

              case "php":
                  return "ini.png";
                  break;

              case "htm":
              case "html":
                  return "html.png";
                  break;

              case "ppt":
			  case "pptx":
                  return "ppt.png";
                  break;

              case "exe":
              case "bat":
              case "com":
                  return "exe.png";
                  break;

              case "wmv":
              case "mpg":
              case "mpeg":
              case "wma":
              case "asf":
                  return "wmv.png";
                  break;

              case "midi":
              case "mid":
                  return "midi.png";
                  break;

              case "mov":
                  return "mov.png";
                  break;

              case "psd":
                  return "psd.png";
                  break;

              case "ram":
              case "rm":
                  return "rm.png";
                  break;

              case "xml":
                  return "xml.png";
                  break;

              default:
                  return "default.png";
                  break;
          }


      }

      /**
       * FileManager::_getMIMEtype()
       * 
       * @param mixed $filename
       * @return
       */
	  private static function _getMIMEtype($filename)
	  {
		  preg_match("|\.([a-z0-9]{2,4})$|i", $filename, $fileSuffix);
		  
          $fs = (isset($fileSuffix[1])) ? $fileSuffix[1] : null;
		  
		  switch(strtolower($fs))
		  {
			  case "js" :
				  return "application/x-javascript";
  
			  case "json" :
				  return "application/json";
  
			  case "jpg" :
			  case "jpeg" :
			  case "jpe" :
				  return "image/jpg";
  
			  case "png" :
			  case "gif" :
			  case "bmp" :
			  case "tiff" :
				  return "image/".strtolower($fs);
  
			  case "css" :
				  return "text/css";
  
			  case "xml" :
				  return "application/xml";
  
			  case "doc" :
			  case "docx" :
				  return "application/msword";
  
			  case "xls" :
			  case "xlt" :
			  case "xlm" :
			  case "xld" :
			  case "xla" :
			  case "xlc" :
			  case "xlw" :
			  case "xll" :
				  return "application/vnd.ms-excel";
  
			  case "ppt" :
			  case "pps" :
				  return "application/vnd.ms-powerpoint";
  
			  case "rtf" :
				  return "application/rtf";
  
			  case "pdf" :
				  return "application/pdf";
  
			  case "html" :
			  case "htm" :
			  case "php" :
				  return "text/html";
  
			  case "txt" :
				  return "text/plain";
  
			  case "mpeg" :
			  case "mpg" :
			  case "mpe" :
				  return "video/mpeg";

			  case "mp4" :
				  return "video/mp4";
				  
			  case "mp3" :
				  return "audio/mpeg3";
  
			  case "wav" :
				  return "audio/wav";
  
			  case "aiff" :
			  case "aif" :
				  return "audio/aiff";
  
			  case "avi" :
				  return "video/msvideo";
  
			  case "wmv" :
				  return "video/x-ms-wmv";
  
			  case "mov" :
				  return "video/quicktime";
  
			  case "zip" :
				  return "application/zip";
  
			  case "tar" :
				  return "application/x-tar";
  
			  case "swf" :
				  return "application/x-shockwave-flash";
  
			  default :
			  if(function_exists("mime_content_type"))
			  {
				  $fileSuffix = mime_content_type($filename);
			  }
  
			  return "unknown/" . trim($fs, ".");
		  }
	  }

      /**
       * FileManager::scanFiles()
       * 
       * @param mixed $dir
       * @param bool $filter
       * @return
       */
      public static function scanFiles($dir, $filter = false)
      {
          clearstatcache();
          $directory = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
          $iterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::LEAVES_ONLY);

          $extensions = $filter;

          foreach ($iterator as $fileinfo) {
              if ($filter) {
                  if (in_array($fileinfo->getExtension(), $extensions)) {
                      $files[] = $fileinfo->getPathname();
                  }
              } else {
                  $files[] = $fileinfo->getPathname();
              }
          }
          return (empty($files)) ? 0 : $files;
      }
	  
      /**
       * FileManager::purge()
       * 
       * @param mixed $dir
       * @param bool $delroot
       * @return
       */
      private static function purge($dir, $delroot = true)
      {
          if (!$dh = @opendir($dir))
              return;

          while (false !== ($obj = readdir($dh))) {
              if ($obj == '.' || $obj == '..' || $obj == 'index.php' || $obj == 'index.html')
                  continue;
              
              if (!@unlink($dir . '/' . $obj))
                  self::purge($dir . '/' . $obj, true);
          }
          
          closedir($dh);
          
          if ($delroot)
              @rmdir($dir);
          return true;
      }
	  
      /**
       * FileManager::fixPath()
       * 
       * @param mixed $path
       * @return
       */
      public static function fixPath($path)
      {
          $path = str_replace('\\', '/', $path);
          $path = preg_replace("#/+#", "/", $path);
		  
          return $path;
      }

  }
?>