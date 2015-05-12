<?php
  /**
   * Core Class
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: core_class.php, v3.00 2014-06-05 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  class Core
  {

      const sTable = "settings";
      public $year = null;
      public $month = null;
      public $day = null;
	 
      public static $language;
	  public $langlist;
	  
      /**
       * Core::__construct()
       * 
       * @return
       */
      public function __construct()
      {
          $this->getSettings();

          ($this->dtz) ? date_default_timezone_set($this->dtz) : date_default_timezone_set('GMT');

          $this->year = (get('year')) ? get('year') : strftime('%Y');
          $this->month = (get('month')) ? get('month') : strftime('%m');
          $this->day = (get('day')) ? get('day') : strftime('%d');

          return mktime(0, 0, 0, $this->month, $this->day, $this->year);
      }


      /**
       * Core::getSettings()
       * 
       * @return
       */
      private function getSettings()
      {
          $sql = "SELECT * FROM " . self::sTable;
          $row = Registry::get("Database")->first($sql);

          $this->company = $row->company;
          $this->site_url = $row->site_url;
		  $this->site_dir = $row->site_dir;
          $this->site_email = $row->site_email;
          $this->address = $row->address;
          $this->city = $row->city;
          $this->state = $row->state;
          $this->zip = $row->zip;
		  $this->country = $row->country;
          $this->phone = $row->phone;
          $this->fax = $row->fax;
          $this->logo = $row->logo;
          $this->short_date = $row->short_date;
          $this->long_date = $row->long_date;
          $this->dtz = $row->dtz;
		  $this->locale = $row->locale;
		  $this->lang  = $row->lang;
		  $this->weekstart  = $row->weekstart;
		  $this->theme  = $row->theme;
		  $this->unvsfn = $row->unvsfn;
		  $this->enable_reg = $row->enable_reg;
          $this->enable_tax = $row->enable_tax;
          $this->tax_name = $row->tax_name;
          $this->tax_rate = $row->tax_rate;
		  $this->tax_number = $row->tax_number;
		  $this->enable_offline = $row->enable_offline;
		  $this->offline_info = $row->offline_info;
		  $this->invoice_note = $row->invoice_note;
		  $this->invoice_number = $row->invoice_number;
		  $this->quote_number = $row->quote_number;
          $this->enable_uploads = $row->enable_uploads;
          $this->file_types = $row->file_types;
          $this->file_max = $row->file_max;
          $this->perpage = $row->perpage;
          $this->sbackup = $row->sbackup;
          $this->currency = $row->currency;
          $this->cur_symbol = $row->cur_symbol;
		  $this->tsep = $row->tsep;
		  $this->dsep = $row->dsep;
		  $this->pp_email = $row->pp_email;
		  $this->pp_pass = $row->pp_pass;
		  $this->pp_api = $row->pp_api;
		  $this->pp_mode = $row->pp_mode;
		  $this->invdays = $row->invdays;
		  $this->pagesize = $row->pagesize;
          $this->mailer = $row->mailer;
          $this->smtp_host = $row->smtp_host;
          $this->smtp_user = $row->smtp_user;
          $this->smtp_pass = $row->smtp_pass;
          $this->smtp_port = $row->smtp_port;
		  $this->sendmail = $row->sendmail;
		  $this->is_ssl = $row->is_ssl;

          $this->crmv = $row->crmv;

      }

      /**
       * Core::processConfig()
       * 
       * @return
       */
      public function processConfig()
      {
          Filter::checkPost('company', Lang::$word->CONF_COMPANY);
          Filter::checkPost('site_email', Lang::$word->CONF_EMAIL);
          Filter::checkPost('currency', Lang::$word->CONF_CURRENCY);

          switch($_POST['mailer']) {
			  case "SMTP" :
				  Filter::checkPost('smtp_host',Lang::$word->CONF_SMTP_HOST);
				  Filter::checkPost('smtp_user',Lang::$word->CONF_SMTP_USER);
				  Filter::checkPost('currency',Lang::$word->CONF_SMTP_PASS);
				  Filter::checkPost('smtp_port',Lang::$word->CONF_SMTP_PORT);
				  break;
			  
			  case "SMAIL" :
				  Filter::checkPost('sendmail',Lang::$word->CONF_SMAILPATH);
			  break;
		  }

          if (!empty($_FILES['logo']['name'])) {
              $file_info = getimagesize($_FILES['logo']['tmp_name']);
              if (empty($file_info))
                  Filter::$msgs['logo'] = Lang::$word->CONF_LOGO_R;
          }

          if (!empty($_FILES['plogo']['name'])) {
              $file_info = getimagesize($_FILES['plogo']['tmp_name']);
              if (empty($file_info))
                  Filter::$msgs['plogo'] = Lang::$word->CONF_LOGO_R;
          }
		  
          if (empty(Filter::$msgs)) {
              $data = array(
					  'company' => sanitize($_POST['company']),
					  'site_url' => sanitize($_POST['site_url']),
					  'site_dir' => sanitize($_POST['site_dir']),
					  'site_email' => sanitize($_POST['site_email']),
					  'address' => sanitize($_POST['address']), 
					  'city' => sanitize($_POST['city']),
					  'state' => sanitize($_POST['state']),
					  'zip' => sanitize($_POST['zip']),
					  'phone' => sanitize($_POST['phone']),
					  'fax' => sanitize($_POST['fax']),
					  'short_date' => sanitize($_POST['short_date']),
					  'long_date' => sanitize($_POST['long_date']),
					  'dtz' => trim($_POST['dtz']),
					  'locale' => sanitize($_POST['locale']),
					  'weekstart' => intval($_POST['weekstart']),
					  'theme' => sanitize($_POST['theme']),
					  'lang' => sanitize($_POST['lang']),
					  'enable_reg' => intval($_POST['enable_reg']),
					  'enable_tax' => intval($_POST['enable_tax']),
					  'tax_name' => sanitize($_POST['tax_name']),
					  'tax_rate' => sanitize($_POST['tax_rate']) / 100,
					  'tax_number' => sanitize($_POST['tax_number']),
					  'enable_offline' => intval($_POST['enable_offline']),
					  'offline_info' => $_POST['offline_info'],
					  'invoice_note' => $_POST['invoice_note'],
					  'invoice_number' => sanitize($_POST['invoice_number']),
					  'quote_number' => sanitize($_POST['quote_number']),
					  'enable_uploads' => intval($_POST['enable_uploads']),
					  'file_types' => trim($_POST['file_types']),
					  'file_max' => intval($_POST['file_max']*1048576),		  
					  'perpage' => intval($_POST['perpage']),
					  'currency' => sanitize($_POST['currency']),
					  'cur_symbol' => sanitize($_POST['cur_symbol']),
					  'tsep' => empty($_POST['tsep']) ? "," : sanitize($_POST['tsep']),
					  'dsep' => empty($_POST['dsep']) ? "." : sanitize($_POST['dsep']),
					  'invdays' => sanitize($_POST['invdays']),  
					  'pp_email' => sanitize($_POST['pp_email']), 
					  'pp_pass' => sanitize($_POST['pp_pass']), 
					  'pp_api' => sanitize($_POST['pp_api']), 
					  'pp_mode' => intval($_POST['pp_mode']), 
					  'pagesize' => sanitize($_POST['pagesize']),
					  'mailer' => sanitize($_POST['mailer']),
					  'sendmail' => sanitize($_POST['sendmail']),
					  'smtp_host' => sanitize($_POST['smtp_host']),
					  'smtp_user' => sanitize($_POST['smtp_user']),
					  'smtp_pass' => sanitize($_POST['smtp_pass']),
					  'smtp_port' => intval($_POST['smtp_port']),
					  'is_ssl' => intval($_POST['is_ssl'])
				  );

			  if (isset($_POST['dellogo']) and $_POST['dellogo'] == 1) {
				  $data['logo'] = "NULL";
			  } elseif (!empty($_FILES['logo']['name'])) {
				  if ($this->logo) {
					  @unlink(UPLOADS . $this->logo);
				  }
					  move_uploaded_file($_FILES['logo']['tmp_name'], UPLOADS . $_FILES['logo']['name']);

				  $data['logo'] = sanitize($_FILES['logo']['name']);
			  } else {
				$data['logo'] = $this->logo;
			  }

			  if (!empty($_FILES['plogo']['name'])) {
				  if (file_exists(UPLOADS . "print_logo.png")) {
					  unlink(UPLOADS . "print_logo.png");
				  }
				  move_uploaded_file($_FILES['plogo']['tmp_name'], UPLOADS . "print_logo.png");
			  }
			  
              Registry::get("Database")->update(self::sTable, $data);
			  
			  if(Registry::get("Database")->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk(Lang::$word->CONF_UPDATED, false);
			  } else {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
			  }
			  print json_encode($json);
			  
		  } else {
			  $json['message'] = Filter::msgStatus();
			  print json_encode($json);
		  }
      }

 	  /**
	   * Core:::langIcon()
	   * 
	   * @return
	   */
	  public static function langIcon()
	  {
		  return "<div class=\"wojo black bottom right attached special label\">" . strtoupper(self::$language) . "</div>"; 
	  }
	  
      /**
       * Core::fetchPluginInfo()
       * 
       * @return
       */
	  public static function fetchPluginInfo()
	  {
		  if (!is_dir(BASEPATH . 'plugins/')) {
			  return false;
		  } else {
			  $options = glob("" . BASEPATH . "plugins/*.xml");
			  $data = array();
			  if ($options) {
				  foreach ($options as $val) {
					  $data[] = simplexml_load_file($val);
				  }
			  }
			  return $data;
		  }
	  }

	  	  
      /**
       * Core::getShortDate()
       * 
       * @return
       */
      public static function getShortDate($selected = false)
	  {
	
		  $format = (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') ? "%#d" : "%e";
	
          $arr = array(
				 '%m-%d-%Y' => strftime('%m-%d-%Y') . ' (MM-DD-YYYY)',
				 $format . '-%m-%Y' => strftime($format . '-%m-%Y') . ' (D-MM-YYYY)',
				 '%m-' . $format . '-%y' => strftime('%m-' . $format . '-%y') . ' (MM-D-YY)',
				 $format . '-%m-%y' => strftime($format . '-%m-%y') . ' (D-MMM-YY)',
				 '%d %b %Y' => strftime('%d %b %Y')
		  );
		  
		  $shortdate = '';
		  foreach ($arr as $key => $val) {
              if ($key == $selected) {
                  $shortdate .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
              } else
                  $shortdate .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
          }
          unset($val);
          return $shortdate;
      }


      /**
       * Core::getLongDate()
       * 
       * @return
       */
      public static function getLongDate($selected = false)
	  {
          $arr = array(
				'%B %d, %Y %I:%M %p' => strftime('%B %d, %Y %I:%M %p'),
				'%d %B %Y %I:%M %p' => strftime('%d %B %Y %I:%M %p'),
				'%B %d, %Y' => strftime('%B %d, %Y'),
				'%d %B, %Y' => strftime('%d %B, %Y'),
				'%A %d %B %Y' => strftime('%A %d %B %Y'),
				'%A %d %B %Y %H:%M' => strftime('%A %d %B %Y %H:%M'),
				'%a %d, %B' => strftime('%a %d, %B')
		  );
		  
		  $longdate = '';
		  foreach ($arr as $key => $val) {
              if ($key == $selected) {
                  $longdate .= "<option selected=\"selected\" value=\"" . $key . "\">" . utf8_encode($val) . "</option>\n";
              } else
                  $longdate .= "<option value=\"" . $key . "\">" . utf8_encode($val) . "</option>\n";
          }
          unset($val);
          return $longdate;
      }

      /**
       * Core::getTimeFormat()
       * 
       * @return
       */ 	  
      public static function getTimeFormat($selected = false)
	  {
          $arr = array(
				'%I:%M %p' => strftime('%I:%M %p'),
				'%I:%M %P' => strftime('%I:%M %P'),
				'%H:%M' => strftime('%H:%M'),
				'%k' => strftime('%k'),
		  );
		  
		  $longdate = '';
		  foreach ($arr as $key => $val) {
              if ($key == $selected) {
                  $longdate .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
              } else
                  $longdate .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
          }
          unset($val);
          return $longdate;
      }

      /**
       * Core::localeList()
       * 
       * @return
       */
	  public static function localeList()
	  {
	
		  $lang = array(
			  "af_utf8,Afrikaans,af_ZA.UTF-8,Afrikaans_South Africa.1252,WINDOWS-1252" => "Afrikaans",
			  "sq_utf8,Albanian,sq_AL.UTF-8,Albanian_Albania.1250,WINDOWS-1250" => "Albanian",
			  "ar_utf8,Arabic,ar_SA.UTF-8,Arabic_Saudi Arabia.1256,WINDOWS-1256" => "Arabic",
			  "eu_utf8,Basque,eu_ES.UTF-8,Basque_Spain.1252,WINDOWS-1252" => "Basque",
			  "be_utf8,Belarusian,be_BY.UTF-8,Belarusian_Belarus.1251,WINDOWS-1251" => "Belarusian",
			  "bs_utf8,Bosnian,bs_BA.UTF-8,Serbian (Latin),WINDOWS-1250" => "Bosnian",
			  "bg_utf8,Bulgarian,bg_BG.UTF-8,Bulgarian_Bulgaria.1251,WINDOWS-1251" => "Bulgarian",
			  "ca_utf8,Catalan,ca_ES.UTF-8,Catalan_Spain.1252,WINDOWS-1252" => "Catalan",
			  "hr_utf8,Croatian,hr_HR.UTF-8,Croatian_Croatia.1250,WINDOWS-1250" => "Croatian",
			  "zh_cn_utf8,Chinese (Simplified),zh_CN.UTF-8,Chinese_China.936" => "Chinese (Simplified)",
			  "zh_tw_utf8,Chinese (Traditional),zh_TW.UTF-8,Chinese_Taiwan.950" => "Chinese (Traditional)",
			  "cs_utf8,Czech,cs_CZ.UTF-8,Czech_Czech Republic.1250,WINDOWS-1250" => "Czech",
			  "da_utf8,Danish,da_DK.UTF-8,Danish_Denmark.1252,WINDOWS-1252" => "Danish",
			  "nl_utf8,Dutch,nl_NL.UTF-8,Dutch_Netherlands.1252,WINDOWS-1252" => "Dutch",
			  "en_utf8,English,en.UTF-8,English_Australia.1252," => "English(Australia)",
			  "en_us_utf8,English (US)" => "English",
			  "et_utf8,Estonian,et_EE.UTF-8,Estonian_Estonia.1257,WINDOWS-1257" => "Estonian",
			  "fa_utf8,Farsi,fa_IR.UTF-8,Farsi_Iran.1256,WINDOWS-1256" => "Farsi",
			  "fil_utf8,Filipino,ph_PH.UTF-8,Filipino_Philippines.1252,WINDOWS-1252" => "Filipino",
			  "fi_utf8,Finnish,fi_FI.UTF-8,Finnish_Finland.1252,WINDOWS-1252" => "Finnish",
			  "fr_utf8,French,fr_FR.UTF-8,French_France.1252,WINDOWS-1252" => "French",
			  "fr_ca_utf8,French (Canada),fr_FR.UTF-8,French_Canada.1252" => "French (Canada)",
			  "ga_utf8,Gaelic,ga.UTF-8,Gaelic; Scottish Gaelic,WINDOWS-1252" => "Gaelic",
			  "gl_utf8,Gallego,gl_ES.UTF-8,Galician_Spain.1252,WINDOWS-1252" => "Gallego",
			  "ka_utf8,Georgian,ka_GE.UTF-8,Georgian_Georgia.65001" => "Georgian",
			  "de_utf8,German,de_DE.UTF-8,German_Germany.1252,WINDOWS-1252" => "German",
			  "el_utf8,Greek,el_GR.UTF-8,Greek_Greece.1253,WINDOWS-1253" => "Greek",
			  "gu_utf8,Gujarati,gu.UTF-8,Gujarati_India.0" => "Gujarati",
			  "he_utf8,Hebrew,he_IL.utf8,Hebrew_Israel.1255,WINDOWS-1255" => "Hebrew",
			  "hi_utf8,Hindi,hi_IN.UTF-8,Hindi.65001" => "Hindi",
			  "hu_utf8,Hungarian,hu.UTF-8,Hungarian_Hungary.1250,WINDOWS-1250" => "Hungarian",
			  "is_utf8,Icelandic,is_IS.UTF-8,Icelandic_Iceland.1252,WINDOWS-1252" => "Indonesian",
			  "id_utf8,Indonesian,id_ID.UTF-8,Indonesian_indonesia.1252,WINDOWS-1252" => "Indonesian",
			  "it_utf8,Italian,it_IT.UTF-8,Italian_Italy.1252,WINDOWS-1252" => "Italian",
			  "ja_utf8,Japanese,ja_JP.UTF-8,Japanese_Japan.932" => "Japanese",
			  "kn_utf8,Kannada,kn_IN.UTF-8,Kannada.65001" => "Kannada",
			  "km_utf8,Khmer,km_KH.UTF-8,Khmer.65001" => "Khmer",
			  "ko_utf8,Korean,ko_KR.UTF-8,Korean_Korea.949" => "Korean",
			  "lo_utf8,Lao,lo_LA.UTF-8,Lao_Laos.UTF-8,WINDOWS-1257" => "Lao",
			  "lt_utf8,Lithuanian,lt_LT.UTF-8,Lithuanian_Lithuania.1257,WINDOWS-1257" => "Lithuanian",
			  "lv_utf8,Latvian,lat.UTF-8,Latvian_Latvia.1257,WINDOWS-1257" => "Latvian",
			  "ml_utf8,Malayalam,ml_IN.UTF-8,Malayalam_India.x-iscii-ma" => "Malayalam",
			  "ms_utf8,Malaysian,ms_MY.UTF-8,Malay_malaysia.1252,WINDOWS-1252" => "Malaysian",
			  "mi_tn_utf8,Maori (Ngai Tahu),mi_NZ.UTF-8,Maori.1252,WINDOWS-1252" => "Maori (Ngai Tahu)",
			  "mi_wwow_utf8,Maori (Waikoto Uni),mi_NZ.UTF-8,Maori.1252,WINDOWS-1252" => "Maori (Waikoto Uni)",
			  "mn_utf8,Mongolian,mn.UTF-8,Cyrillic_Mongolian.1251" => "Mongolian",
			  "no_utf8,Norwegian,no_NO.UTF-8,Norwegian_Norway.1252,WINDOWS-1252" => "Norwegian",
			  "nn_utf8,Nynorsk,nn_NO.UTF-8,Norwegian-Nynorsk_Norway.1252,WINDOWS-1252" => "Nynorsk",
			  "pl_utf8,Polish,pl.UTF-8,Polish_Poland.1250,WINDOWS-1250" => "Polish",
			  "pt_utf8,Portuguese,pt_PT.UTF-8,Portuguese_Portugal.1252,WINDOWS-1252" => "Portuguese",
			  "pt_br_utf8,Portuguese (Brazil),pt_BR.UTF-8,Portuguese_Brazil.1252,WINDOWS-1252" => "Portuguese (Brazil)",
			  "ro_utf8,Romanian,ro_RO.UTF-8,Romanian_Romania.1250,WINDOWS-1250" => "Romanian",
			  "ru_utf8,Russian,ru_RU.UTF-8,Russian_Russia.1251,WINDOWS-1251" => "Russian",
			  "sm_utf8,Samoan,mi_NZ.UTF-8,Maori.1252,WINDOWS-1252" => "Samoan",
			  "sr_utf8,Serbian,sr_CS.UTF-8,Serbian (Cyrillic)_Serbia and Montenegro.1251,WINDOWS-1251" => "Serbian",
			  "sk_utf8,Slovak,sk_SK.UTF-8,Slovak_Slovakia.1250,WINDOWS-1250" => "Slovak",
			  "sl_utf8,Slovenian,sl_SI.UTF-8,Slovenian_Slovenia.1250,WINDOWS-1250" => "Slovenian",
			  "so_utf8,Somali,so_SO.UTF-8" => "Somali",
			  "es_utf8,Spanish (International),es_ES.UTF-8,Spanish_Spain.1252,WINDOWS-1252" => "Spanish",
			  "sv_utf8,Swedish,sv_SE.UTF-8,Swedish_Sweden.1252,WINDOWS-1252" => "Swedish",
			  "tl_utf8,Tagalog,tl.UTF-8" => "Tagalog",
			  "ta_utf8,Tamil,ta_IN.UTF-8,English_Australia.1252" => "Tamil",
			  "th_utf8,Thai,th_TH.UTF-8,Thai_Thailand.874,WINDOWS-874" => "Thai",
			  "to_utf8,Tongan,mi_NZ.UTF-8',Maori.1252,WINDOWS-1252" => "Tongan",
			  "tr_utf8,Turkish,tr_TR.UTF-8,Turkish_Turkey.1254,WINDOWS-1254" => "Turkish",
			  "uk_utf8,Ukrainian,uk_UA.UTF-8,Ukrainian_Ukraine.1251,WINDOWS-1251" => "Ukrainian",
			  "vi_utf8,Vietnamese,vi_VN.UTF-8,Vietnamese_Viet Nam.1258,WINDOWS-1258" => "Vietnamese",
			  );
	
		  return $lang;
	  }

      /**
       * Core::setLocalet()
       * 
       * @return
       */
	  public function setLocale()
	  {
		  return explode(',', $this->locale);
	  }
	  
      /**
       * Core::getlocaleList()
       * 
       * @return
       */
      public function getlocaleList()
      {
          $html = '';
          foreach (self::localeList() as $key => $val) {
              if ($key == $this->locale) {
                  $html .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
              } else
                  $html .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
          }
          unset($val);
          return $html;
      }
	  
      /**
       * Core::yearlyStats()
       * 
       * @return
       */
      public function yearlyStats()
      {
          $sql = "SELECT *, YEAR(created) as year, MONTH(created) as month," 
		  . "\n COUNT(id) as total, SUM(amount) as totalprice" 
		  . "\n FROM invoice_payments" 
		  . "\n WHERE YEAR(created) = '" . $this->year . "'" 
		  . "\n GROUP BY year DESC, month DESC ORDER by created";

          $row = Registry::get("Database")->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Core::yearlyStatsServices()
       * 
       * @return
       */
      public function yearlyStatsServices()
      {
          $sql = "SELECT *, YEAR(created) as year, MONTH(created) as month," 
		  . "\n COUNT(id) as total, SUM(price) as totalprice" 
		  . "\n FROM payments" 
		  . "\n WHERE YEAR(created) = '" . $this->year . "'" 
		  . "\n GROUP BY year DESC, month DESC ORDER by created";

          $row = Registry::get("Database")->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
      /**
       * Core::getYearlySummary()
       * 
       * @return
       */
      public function getYearlySummary()
      {
          $sql = "SELECT YEAR(created) as year, MONTH(created) as month," 
		  . "\n COUNT(id) as total, SUM(amount) as totalprice" 
		  . "\n FROM invoice_payments" 
		  . "\n WHERE YEAR(created) = '" . $this->year . "' GROUP BY year";

          $row = Registry::get("Database")->first($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Core::getYearlySummaryServices()
       * 
       * @return
       */
      public function getYearlySummaryServices()
      {
          $sql = "SELECT YEAR(created) as year, MONTH(created) as month," 
		  . "\n COUNT(id) as total, SUM(price) as totalprice" 
		  . "\n FROM payments" 
		  . "\n WHERE YEAR(created) = '" . $this->year . "' GROUP BY year";

          $row = Registry::get("Database")->first($sql);

          return ($row) ? $row : 0;
      }
	  
      /**
       * Core::yearList()
       * 
       * @param mixed $start_year
       * @param mixed $end_year
       * @return
       */
      function yearList($start_year, $end_year)
      {
          $selected = is_null(get('year')) ? date('Y') : get('year');
          $r = range($start_year, $end_year);

          $select = '';
          foreach ($r as $year) {
              $select .= "<option value=\"$year\"";
              $select .= ($year == $selected) ? ' selected="selected"' : '';
              $select .= ">$year</option>\n";
          }
          return $select;
      }


      /**
       * Core::monthList()
       * 
       * @return
       */ 	  
	  public static function monthList($list = true, $long = true, $selected = false)
	  {
		  $selected = is_null(get('month')) ? strftime('%m') : get('month');
	
		  if ($long) {
			  $arr = array(
		          '01' => Lang::$word->JAN_L, 
		          '02' => Lang::$word->FEB_L, 
		          '03' => Lang::$word->MAR_L, 
		          '04' => Lang::$word->APR_L, 
		          '05' => Lang::$word->MAY_L, 
		          '06' => Lang::$word->JUN_L, 
		          '07' => Lang::$word->JUL_L, 
		          '08' => Lang::$word->AUG_L, 
		          '09' => Lang::$word->SEP_L, 
		          '10' => Lang::$word->OCT_L, 
		          '11' => Lang::$word->NOV_L, 
		          '12' => Lang::$word->DEC_L
				  );
		  } else {
			  $arr = array(
		          '01' => Lang::$word->JAN, 
		          '02' => Lang::$word->FEB, 
		          '03' => Lang::$word->MAR, 
		          '04' => Lang::$word->APR, 
		          '05' => Lang::$word->MAY, 
		          '06' => Lang::$word->JUN, 
		          '07' => Lang::$word->JUL, 
		          '08' => Lang::$word->AUG, 
		          '09' => Lang::$word->SEP, 
		          '10' => Lang::$word->OCT, 
		          '11' => Lang::$word->NOV, 
		          '12' => Lang::$word->DEC
				  );
		  }
		  $html = '';
		  if ($list) {
			  foreach ($arr as $key => $val) {
				  $html .= "<option value=\"$key\"";
				  $html .= ($key == $selected) ? ' selected="selected"' : '';
				  $html .= ">$val</option>\n";
			  }
		  } else {
			  $html .= '"' . implode('","', $arr) . '"';
		  }
		  unset($val);
		  return $html;
	  }

      /**
       * Core::weekList()
       * 
       * @return
       */ 	  
	  public static function weekList($list = true, $long = true, $selected = false)
	  {
		  if ($long) {
			  $arr = array(
		          '1' => Lang::$word->SUN_L, 
		          '2' => Lang::$word->MON_L, 
		          '3' => Lang::$word->TUE_L, 
		          '4' => Lang::$word->WED_L, 
		          '5' => Lang::$word->THU_L, 
		          '6' => Lang::$word->FRI_L, 
		          '7' => Lang::$word->SAT_L
				  );
		  } else {
			  $arr = array(
		          '1' => Lang::$word->SUN, 
		          '2' => Lang::$word->MON, 
		          '3' => Lang::$word->TUE, 
		          '4' => Lang::$word->WED, 
		          '5' => Lang::$word->THU, 
		          '6' => Lang::$word->FRI, 
		          '7' => Lang::$word->SAT
				  );
		  }
	
		  $html = '';
		  if ($list) {
			  foreach ($arr as $key => $val) {
				  $html .= "<option value=\"$key\"";
				  $html .= ($key == $selected) ? ' selected="selected"' : '';
				  $html .= ">$val</option>\n";
			  }
		  } else {
			  $html .= '"' . implode('","', $arr) . '"';
		  }
	
		  unset($val);
		  return $html;
	  }

      /**
       * Core::renderName()
       * 
       * @param array $row
       * @return
       */
      public static function renderName($row)
      {
		  $fullname = isset($row->fullname) ? $row->fullname : $row->fname . ' ' . $row->lname;
          return (Registry::get("Core")->unvsfn) ? $row->username : $fullname;
      }
	  
      /**
       * Core::projectStats()
       * 
       * @return
       */
      public function projectStats()
      {
		  $and = (Registry::get("Users")->userlevel == 5) ? "AND FIND_IN_SET(" . Registry::get("Users")->uid . ", staff_id)" : null;
		  
          $sql = "SELECT * FROM projects" 
		  . "\n WHERE p_status <> 100" 
		  . "\n $and" 
		  //. "\n AND YEAR(start_date) <> '" . $this->year . "'" 
		  . "\n ORDER by p_status DESC";

          $row = Registry::get("Database")->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Core::progressBarBilling()
       * 
       * @param mixed $paid
       * @param mixed $total
       * @return
       */
      public function progressBarBilling($paid, $total)
      {
          $percent = number_format(($paid * 100) / $total);
          return $percent;
      }

      /**
       * Core::getTimezones()
       * 
       * @return
       */
      public function getTimezones()
      {
          $data = '';
          $tzone = DateTimeZone::listIdentifiers();
          foreach ($tzone as $zone) {
              $selected = ($zone == $this->dtz) ? ' selected="selected"' : '';
              $data .= '<option value="' . $zone . '"' . $selected . '>' . $zone . '</option>';
          }
          return $data;
      }

      /**
       * Core::formatMoney()
       * 
       * @param mixed $amount
       * @return
       */
      public function formatMoney($amount)
      {
		  return $this->cur_symbol . number_format($amount, 2, $this->dsep, $this->tsep) . $this->currency;
      }

      /**
       * Core::formatMoney()
       * 
       * @param mixed $amount
       * @return
       */
      public function formatClientCurrency($amount, $cur = '')
      {
		  $client_currency = ($cur !='') ? $cur : $this->currency;
		  return number_format($amount, 2, $this->dsep, $this->tsep) . ' ' . $client_currency;
      }
	  
      /**
       * Core::getRowById()
       * 
       * @param mixed $table
       * @param mixed $id
       * @param bool $and
       * @param bool $is_admin
       * @return
       */
      public static function getRowById($table, $id, $and = false, $is_admin = true)
      {
          $id = sanitize($id, 8, true);
          if ($and) {
              $sql = "SELECT * FROM " . (string )$table . " WHERE id = '" . Registry::get("Database")->escape((int)$id) . "' AND " . Registry::get("Database")->escape($and) . "";
          } else
              $sql = "SELECT * FROM " . (string )$table . " WHERE id = '" . Registry::get("Database")->escape((int)$id) . "'";

          $row = Registry::get("Database")->first($sql);

          if ($row) {
              return $row;
          } else {
              if ($is_admin)
                  Filter::error("You have selected an Invalid Id - #" . $id, "Core::getRowById()");
          }
      }

      /**
       * Core::getRow()
       * 
       * @param mixed $table
       * @param mixed $where
	   * @param bool $is_admin
       * @return
       */
      public static function getRow($table, $where, $what, $is_admin = true)
      {
          $sql = "SELECT * FROM " . (string )$table . " WHERE $where = '" . $what . "'";
          $row = Registry::get("Database")->first($sql);

          if ($row) {
              return $row;
          } else {
              if ($is_admin)
                  Filter::error("You have selected an Invalid Value - #" . $what, "Core::getRow()");
          }
      }
	  
      /**
       * Core::getPeriod()
       * 
       * @param bool $value
       * @return
       */
      public function getPeriod($value)
	  {
		  switch($value) {
			  case "D" :
			  return Lang::$word->INVC_REC_DAYS;
			  break;
			  case "W" :
			  return Lang::$word->INVC_REC_WEEKS;
			  break;
			  case "M" :
			  return Lang::$word->INVC_REC_MONTHS;
			  break;
			  case "Y" :
			  return Lang::$word->INVC_REC_YEARS;
			  break;
		  }

      }
	  
      /**
       * Core::countInvoices()
       * 
       * @return
       */
      public function countInvoices()
      {
		  $sql = "SELECT COUNT(id) as total"
		  . "\n FROM invoices"
		  . "\n WHERE status = 'Unpaid' AND recurring = 0 AND onhold = 0";
		  $row = Registry::get("Database")->first($sql);
		  
		  return ($row) ? $row->total : 0;

      }

      /**
       * Core::countProjects()
       * 
       * @return
       */
      public function countProjects()
      {
		  $access = (Registry::get("Users")->userlevel == 5) ? "AND FIND_IN_SET(" . Registry::get("Users")->uid . ", staff_id)" : "";
		  
		  $sql = "SELECT COUNT(id) as total"
		  . "\n FROM projects"
		  . "\n WHERE p_status <> 100"
		  . "\n $access";
		  $row = Registry::get("Database")->first($sql);
		  
		  return ($row) ? $row->total : 0;

      }

      /**
       * Core::countTasks()
       * 
       * @return
       */
      public function countTasks()
      {
		  $access = (Registry::get("Users")->userlevel == 5) ? "AND staff_id='" . Registry::get("Users")->uid . "'" : "";
		  $sql = "SELECT COUNT(id) as total"
		  . "\n FROM tasks"
		  . "\n WHERE progress <> 100"
		  . "\n $access";
		  $row = Registry::get("Database")->first($sql);
		  
		  return ($row) ? $row->total : 0;

      }

      /**
       * Core::countTickets()
       * 
       * @return
       */
      public function countTickets()
      {
		  $access = (Registry::get("Users")->userlevel == 5) ? "AND staff_id='" . Registry::get("Users")->uid . "'" : "";
		  
		  $sql = "SELECT COUNT(id) as total"
		  . "\n FROM support_tickets"
		  . "\n WHERE status = 'Open'"
		  . "\n $access";
		  $row = Registry::get("Database")->first($sql);
		  
		  return ($row) ? $row->total : 0;

      }

      /**
       * Core::countFrontTickets()
       * 
       * @return
       */
      public function countFrontTickets()
      {
		  $access = (Registry::get("Users")->userlevel == 1) ? "AND client_id='" . Registry::get("Users")->uid . "'" : "";
		  
		  $sql = "SELECT COUNT(id) as total"
		  . "\n FROM support_tickets"
		  . "\n WHERE status = 'Open'"
		  . "\n $access";
		  $row = Registry::get("Database")->first($sql);
		  
		  return ($row) ? $row->total : 0;

      }
      /**
       * Core::countMessages()
       * 
       * @return
       */
      public function countMessages()
      {
		  $sql = "SELECT COUNT(uid1) as total"
		  . "\n FROM messages"
		  . "\n WHERE (user1='" . Registry::get("Users")->uid . "' AND user1read='no')"
		  . "\n OR (user2='" . Registry::get("Users")->uid . "' AND user2read='no')"
		  . "\n AND uid2 = 1";
		  $row = Registry::get("Database")->first($sql);
		  
		  return ($row) ? $row->total : 0;

      }
	  
      /**
       * Core::countUsers()
       * 
       * @return
       */
      public function countUsers($ul)
      {
		  $sql = "SELECT COUNT(id) as total"
		  . "\n FROM users"
		  . "\n WHERE userlevel = $ul";
		  $row = Registry::get("Database")->first($sql);
		  
		  return ($row) ? $row->total : 0;

      }

	  /**
	   * Core::_implode()
	   * 
	   * @param mixed $array
	   * @return
	   */
	  public static function _implode($array)
	  {
          if (is_array($array)) {
			  $result = array();
			  foreach ($array as $row) {
				  if ($row != '') {
					  array_push($result, sanitize($row));
				  }
			  }
			  return implode(',', $result);
          }
		  return false;
	  }

	  /**
	   * Core::_explode()
	   * 
	   * @param mixed $string
	   * @return
	   */
	  public static function _explode($string, $sep = ",")
	  {
		  $data = explode($sep, $string);
          if (count($data) >= 1) {
			  return $data;
          }
		  return false;
	  }

	  /**
	   * Core::sayHello()
	   * 
	   * @return
	   */
	  public static function sayHello()
	  {
		  $welcome = Lang::$word->HI;
		  if (date("H") < 12) {
			  $welcome .= Lang::$word->HI_M;
		  } else if (date('H') > 11 && date("H") < 18) {
			  $welcome .= Lang::$word->HI_A;
		  } else if(date('H') > 17) {
			  $welcome .= Lang::$word->HI_E;
		  }
		  
		  return $welcome;
	  }

	  /**
	   * Core::randomQuotes()
       * 
       * @param mixed $time
	   * @return
	   */
	  public static function randomQuotes($time)
	  {
		  // --> $time('m'); // Quote changes every month
		  // --> $time('h'); // Quote changes every hour
		  // --> $time('i'); // Quote changes every minute
		  
		  $quotes = file_get_contents(BASEPATH . "assets/quotes.txt");
		  $array = explode("%",$quotes);
		  
		  $time = intval(date($time));
		  $count = count($array);
		  $RandomIndexPos = ($time % $count);
		  
		  return $array[$RandomIndexPos];
	  }

	  /**
	   * Core::protocol()
       * 
	   * @return
	   */
	  public static function protocol()
	  {
		  if (isset($_SERVER['HTTPS'])) {
			  $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
		  } else {
			  $protocol = 'http';
		  }
		  
		  return $protocol;
	  }
	  
      /**
       * Core::msgStatus()
       * 
       * @param mixed $user1
       * @param string $user2
       * @param string $user1read
       * @param string $user2read
       * @param string $uid2
       * @return
       */
      public function msgStatus($user1, $user2, $user1read, $user2read, $uid2)
      {

		  if (($user1 == Registry::get("Users")->uid  and $user1read=='no') or ($user2 == Registry::get("Users")->uid and $user2read == 'no') and $uid2 = 1)
		      return 'closed';

      }
  }
?>