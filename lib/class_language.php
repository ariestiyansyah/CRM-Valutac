<?php
  /**
   * Language Class
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: class_language.php, v3.00 2014-03-05 10:12:05 gewa Exp $
   */

  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  final class Lang
  {
      const langdir = "lang/";
	  public static $language;
	  public static $word = array();
	  public static $lang;


      /**
       * Lang::__construct()
       * 
       * @return
       */
      public function __construct()
      {
		  self::get();
      }
	  
      /**
       * Lang::get()
       * 
       * @return
       */
	  private static function get()
	  {
		  if (isset($_COOKIE['LANG_FM'])) {
			  $sel_lang = sanitize($_COOKIE['LANG_FM'], 2);
			  $vlang = self::fetchLanguage($sel_lang);
			  if(in_array($sel_lang, $vlang)) {
				  Core::$language = $sel_lang;
			  } else {
				  Core::$language = Registry::get("Core")->lang;
			  }
			  if (file_exists(BASEPATH . self::langdir . Core::$language . "/lang.xml")) {
				  self::$word = self::set(BASEPATH . self::langdir . Core::$language . "/lang.xml", Core::$language);
			  } else {
				  self::$word = self::set(BASEPATH . self::langdir . Registry::get("Core")->lang . "/lang.xml", Registry::get("Core")->lang);
			  }
		  } else {
			  Core::$language = Registry::get("Core")->lang;
			  self::$word = self::set(BASEPATH . self::langdir . Registry::get("Core")->lang. "/lang.xml", Registry::get("Core")->lang);
			  
		  }
		  self::$lang = "_" . Core::$language;
		  return self::$word;
	  }

      /**
       * Lang::set()
       * 
       * @return
       */
	  public static function set($lang, $abbr)
	  {
		  $xmlel = simplexml_load_file($lang);
		  $countplugs = glob("" . BASEPATH . self::langdir . "$abbr/plugins/" . "*.lang.plugin.xml");
		  $totalplugs = count($countplugs);

		  $data = new stdClass();
		  foreach ($xmlel as $pkey) {
			  $key = (string )$pkey['data'];
			  $data->$key = (string )str_replace(array('\'', '"'), array("&apos;", "&quot;"), $pkey);
		  }
		  if ($totalplugs) {
			  foreach ($countplugs as $val) {
				  $pxml = simplexml_load_file($val);
				  foreach ($pxml as $pkey) {
					  $key = (string )$pkey['data'];
					  $data->$key = (string )str_replace(array('\'', '"'), array("&apos;", "&quot;"), $pkey);
				  }
			  }
		  }
		  
		  return $data;
	  }
	  
      /**
       * Lang::langSwitch()
       * 
       * @return
       */
	  public static function langSwitch()
	  {

		  $plugins = Core::fetchPluginInfo();
		  
		  $html = '';
		  $html .= '<select id="langchange" name="langswitch">';
		  
		  $html .= "<optgroup label=\"" . Lang::$word->LM_MAIN . "\">\n";
		  $html .= "<option data-type=\"lang\" value=\"main\">-- " . Lang::$word->LM_MAIN1 . "</option>\n";
		  
		  if($plugins) {
			  $html .= "<optgroup label=\"" . Lang::$word->PLUGINS . "\">\n";
			  if($plugins) {
				  foreach ($plugins as $pval) {
					  $html .= "<option data-type=\"plugins/" . $pval->dir . ".lang.plugin\" value=\"" . $pval->{'title' . self::$lang} . "\">-- " . $pval->{'title' . self::$lang} . "</option>\n";
				  }
			  }
			  $html .= "</optgroup>\n";
		  }
		  
		  $html .= '</select>';
		  
		  return $html;
	  }
	    
      /**
       * Lang::fetchLanguage()
       * 
       * @return
       */
	  public static function fetchLanguage()
	  {
		  $lang_array = '';
		  $directory = BASEPATH . Lang::langdir;
		  if (!is_dir($directory)) {
			  return false;
		  } else {
			  $lang_array = glob($directory . "*", GLOB_ONLYDIR);
			  $lang_array = str_replace($directory, "", $lang_array);
	
		  }
	
		  return $lang_array;
	  }
  }
?>