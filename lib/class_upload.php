<?php
  /**
   * Uploader Class
   *
   * @package Freelance Manager
   * @author wojocms.com
   * @copyright 2011
   * @version $Id: class_upload.php,v 1.00 2011-06-01 21:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  class Uploader
  {
      private $maxSize;
      private $allowedExt;
      public $fileInfo = array();
	  private static $instance; 

      /**
       * Uploader::__construct()
       * 
       * @param mixed $maxSize
       * @param mixed $allowedExt
       * @return
       */
	  private function __construct($maxSize, $allowedExt)
      {
          $this->maxSize = $maxSize;
          $this->allowedExt = $allowedExt;
      }

      /**
       * Uploader::instance()
       * 
       * @return
       */
	  public static function instance($maxSize = null, $allowedExt = null){
		  if (!self::$instance){ 
			  self::$instance = new Uploader($maxSize, $allowedExt); 
		  } 
	  
		  return self::$instance;  
	  }
	  
      /**
       * Uploader::check()
       * 
       * @param mixed $uploadName
       * @return
       */
      public function check($uploadName)
      {
          if (isset($_FILES[$uploadName])) {
              $this->fileInfo['ext'] = substr(strrchr($_FILES[$uploadName]["name"], '.'), 1);
              $this->fileInfo['name'] = basename($_FILES[$uploadName]["name"]);
              $this->fileInfo['size'] = $_FILES[$uploadName]["size"];
              $this->fileInfo['temp'] = $_FILES[$uploadName]["tmp_name"];
              if ($this->fileInfo['size'] < $this->maxSize) {
                  if (strlen($this->allowedExt) > 0) {
                      $exts = explode(',', $this->allowedExt);
                      if (in_array(strtolower($this->fileInfo['ext']), $exts)) {
                          return true;
                      }
					  Filter::$msgs['name'] = Lang::$word->FORM_ERROR8 . $this->allowedExt;
                      return false;

                  }
				  Filter::$msgs['name'] = Lang::$word->FORM_ERROR9;//no extension specified
                  return false;

              } else {
                  if ($this->maxSize < 1000000) {
                      $rsi = round($this->maxSize / 1024, 2) . ' Kb';
                  } else
                      if ($this->maxSize < 1000000000) {
                          $rsi = round($this->maxSize / 1048576, 2) . ' Mb';
                      } else {
                          $rsi = round($this->maxSize / 1073741824, 2) . ' Gb';
                      }
					  Filter::$msgs['name'] = Lang::$word->FORM_ERROR10 . $rsi;
                  return false;
              }
          }
		  Filter::$msgs['name'] = Lang::$word->FORM_ERROR11;//Either form not submitted or file/s not found
          return false;

      }

      /**
       * Uploader::upload()
       * 
       * @param mixed $name
       * @param mixed $dir
       * @param bool $fname
	   * @param mixed $prefix
       * @return
       */
      public function upload($name, $dir, $prefix = 'SOURCE_', $fname = false)
      {
          if (!is_dir($dir)) {
			  Filter::$msgs['name'] = Lang::$word->FORM_ERROR12; //Directory doesn't exist!
          }
          if ($this->check($name)) {
              if (!$fname) {
                  $this->fileInfo['fname'] = $prefix . randName() . '.' . $this->fileInfo['ext'];
              } else {
                  $this->fileInfo['fname'] = $fname;
              }
              while (file_exists($dir . $this->fileInfo['fname'])) {
                  $this->fileInfo['fname'] = $prefix . randName() . '.' . $this->fileInfo['ext'];
              }
              if (@!move_uploaded_file($this->fileInfo['temp'], $dir . $this->fileInfo['fname'])) {
				  Filter::$msgs['name'] = Lang::$word->FORM_ERROR13; //File not moved
              }
          }
      }

  }
?>