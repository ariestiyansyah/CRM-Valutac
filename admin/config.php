<?php
  /**
   * Configuration
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: config.php, v3.00 2014-05-08 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php if($user->userlevel == 5): print Filter::msgInfo(Lang::$word->ADMINONLY); return; endif;?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->CONF_INFO . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><i class="sliders icon"></i><?php echo Lang::$word->CONF_SUB;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_COMPANY;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" value="<?php echo $core->company;?>" name="company">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_URL;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" value="<?php echo $core->site_url;?>" name="site_url">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_DIR;?></label>
        <label class="input">
          <input type="text" value="<?php echo $core->site_dir;?>" name="site_dir">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_EMAIL;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" value="<?php echo $core->site_email;?>" name="site_email">
        </label>
      </div>
    </div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_ADDRESS;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" value="<?php echo $core->address;?>" name="address">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_CITY;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" value="<?php echo $core->city;?>" name="city">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_STATE;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" value="<?php echo $core->state;?>" name="state">
        </label>
      </div>
    </div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_ZIP;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" value="<?php echo $core->zip;?>" name="zip">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_PHONE;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" value="<?php echo $core->phone;?>" name="phone">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_FAX;?></label>
        <label class="input">
          <input type="text" value="<?php echo $core->fax;?>" name="fax">
        </label>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="two fields">
      <div class="field">
        <label class="label"><?php echo Lang::$word->CONF_OFFLINE;?></label>
        <label class="radio">
          <input type="radio" name="enable_offline" value="1" <?php getChecked($core->enable_offline, 1); ?>>
          <i></i><?php echo Lang::$word->YES;?></label>
        <label class="radio">
          <input type="radio" name="enable_offline" value="0" <?php getChecked($core->enable_offline, 0); ?>>
          <i></i><?php echo Lang::$word->NO;?></label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_OFFLINEINFO;?></label>
        <textarea class="altpost" name="offline_info" rows="3"><?php echo $core->offline_info;?></textarea>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_QTYNUMBER;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" value="<?php echo $core->quote_number;?>" name="quote_number">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_INVNUMBER;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" value="<?php echo $core->invoice_number;?>" name="invoice_number">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_PSIZE;?></label>
        <select name="pagesize">
          <option value="A4" <?php if ($core->pagesize == "A4") echo "selected=\"selected\"";?>>A4</option>
          <option value="LETTER" <?php if ($core->pagesize == "LETTER") echo "selected=\"selected\"";?>>LETTER</option>
        </select>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_LOGO;?></label>
        <label class="input">
          <input type="file" name="logo" class="filefield">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_DELLOGO;?></label>
        <div class="inline-group">
          <label class="checkbox">
            <input name="dellogo" type="checkbox" value="1">
            <i></i><?php echo Lang::$word->YES;?></label>
        </div>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_PLOGO;?></label>
        <label class="input">
          <input type="file" name="plogo" class="filefield">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_PLOGO;?></label>
        <div class="wojo small image"> <a class="lightbox" href="<?php echo UPLOADURL;?>print_logo.png"><img src="<?php echo UPLOADURL;?>print_logo.png" alt=""></a> </div>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="field">
      <label><?php echo Lang::$word->CONF_INVNOTE;?></label>
      <textarea class="altpost" name="invoice_note" placeholder="<?php echo Lang::$word->CONF_INVNOTE;?>"><?php echo $core->invoice_note;?></textarea>
    </div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_SDATE;?></label>
        <select name="short_date">
          <?php echo Core::getShortDate($core->short_date);?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_LDATE;?></label>
        <select name="long_date">
          <?php echo Core::getLongDate($core->long_date);?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_LOCALES;?></label>
        <select name="locale">
          <?php echo $core->getlocaleList();?>
        </select>
      </div>
    </div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_WEEK;?></label>
        <select name="weekstart">
          <?php echo $core->weekList();?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_LANG;?></label>
        <select name="lang">
          <?php foreach(Lang::fetchLanguage() as $langlist):?>
          <option value="<?php echo $langlist;?>"<?php if($core->lang == $langlist) echo ' selected="selected"';?>><?php echo strtoupper($langlist);?></option>
          <?php endforeach;?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_TZ;?></label>
        <select name="dtz">
          <?php echo $core->getTimezones();?>
        </select>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_REGYES;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="enable_reg" value="1" <?php getChecked($core->enable_reg, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="enable_reg" value="0" <?php getChecked($core->enable_reg, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_TAX;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="enable_tax" value="1" <?php getChecked($core->enable_tax, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="enable_tax" value="0" <?php getChecked($core->enable_tax, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_UNVSFN;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="unvsfn" value="1" <?php getChecked($core->unvsfn, 1); ?>>
            <i></i><?php echo Lang::$word->USERNAME;?></label>
          <label class="radio">
            <input type="radio" name="unvsfn" value="0" <?php getChecked($core->unvsfn, 0); ?>>
            <i></i><?php echo Lang::$word->FULLNAME;?></label>
        </div>
      </div>
    </div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_TAXNAME;?></label>
        <label class="input">
          <input type="text" name="tax_name" value="<?php echo $core->tax_name;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_TAXRATE;?></label>
        <label class="input">
          <input type="text" name="tax_rate" value="<?php echo $core->tax_rate * 100;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_TAXNO;?></label>
        <label class="input">
          <input type="text" name="tax_number" value="<?php echo $core->tax_number;?>">
        </label>
      </div>
    </div>
    <div class="four fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_CURRENCY;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="currency" value="<?php echo $core->currency;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_CURSYMBOL;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="cur_symbol" value="<?php echo $core->cur_symbol;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_TSEP;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="tsep" value="<?php echo $core->tsep;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_DSEP;?></label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="dsep" value="<?php echo $core->dsep;?>">
        </label>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_UPLOADS;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="enable_uploads" value="1" <?php getChecked($core->enable_uploads, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="enable_uploads" value="0" <?php getChecked($core->enable_uploads, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_AFT;?></label>
        <label class="input">
          <input type="text" name="file_types" value="<?php echo $core->file_types;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_MFS;?></label>
        <label class="input">
          <input type="text" name="file_max" value="<?php echo $core->file_max/1048576;?>">
        </label>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_PPMASSLIVE;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="pp_mode" value="1" <?php getChecked($core->pp_mode, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="pp_mode" value="0" <?php getChecked($core->pp_mode, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_PPMASSPAY;?></label>
        <label class="input">
          <input type="text" name="pp_email" value="<?php echo $core->pp_email;?>">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_PPMASSPASS;?></label>
        <label class="input">
          <input type="text" name="pp_pass" value="<?php echo $core->pp_pass;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_PPMASSAPI;?></label>
        <label class="input">
          <input type="text" name="pp_api" value="<?php echo $core->pp_api;?>">
        </label>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_IPP;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" name="perpage" value="<?php echo $core->perpage;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_INVREMINDER;?></label>
        <label class="input">
          <input type="text" name="invdays" value="<?php echo $core->invdays;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_THEME;?></label>
        <select name="theme">
          <?php getTemplates(BASEPATH."/themes/", $core->theme)?>
        </select>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_MAILER;?></label>
        <select name="mailer" id="mailerchange" class="selectbox">
          <option value="PHP" <?php if ($core->mailer == "PHP") echo "selected=\"selected\"";?>>PHP Mailer</option>
          <option value="SMAIL" <?php if ($core->mailer == "SMAIL") echo "selected=\"selected\"";?>>Sendmail</option>
          <option value="SMTP" <?php if ($core->mailer == "SMTP") echo "selected=\"selected\"";?>>SMTP Mailer</option>
        </select>
      </div>
      <div class="field showsmail">
        <label><?php echo Lang::$word->CONF_SMAILPATH;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input name="sendmail" value="<?php echo $core->sendmail;?>" type="text">
        </label>
      </div>
    </div>
    <div class="showsmtp">
      <div class="wojo thin attached divider"></div>
      <div class="two fields">
        <div class="field">
          <label><?php echo Lang::$word->CONF_SMTP_HOST;?></label>
          <label class="input"><i class="icon-append icon asterisk"></i>
            <input name="smtp_host" value="<?php echo $core->smtp_host;?>" placeholder="<?php echo Lang::$word->CONF_SMTP_HOST;?>" type="text">
          </label>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->CONF_SMTP_USER;?></label>
          <label class="input"><i class="icon-append icon asterisk"></i>
            <input name="smtp_user" value="<?php echo $core->smtp_user;?>" placeholder="<?php echo Lang::$word->CONF_SMTP_USER;?>" type="text">
          </label>
        </div>
      </div>
      <div class="three fields">
        <div class="field">
          <label><?php echo Lang::$word->CONF_SMTP_PASS;?></label>
          <label class="input"><i class="icon-append icon asterisk"></i>
            <input name="smtp_pass" value="<?php echo $core->smtp_pass;?>" placeholder="<?php echo Lang::$word->CONF_SMTP_PASS;?>" type="text">
          </label>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->CONF_SMTP_PORT;?></label>
          <label class="input"><i class="icon-append icon asterisk"></i>
            <input name="smtp_port" value="<?php echo $core->smtp_port;?>" placeholder="<?php echo Lang::$word->CONF_SMTP_PORT;?>" type="text">
          </label>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->CONF_SMTP_SSL;?></label>
          <div class="inline-group">
            <label class="radio">
              <input name="is_ssl" type="radio" value="1" <?php getChecked($core->is_ssl, 1); ?>>
              <i></i><?php echo Lang::$word->YES;?></label>
            <label class="radio">
              <input name="is_ssl" type="radio" value="0" <?php getChecked($core->is_ssl, 0); ?>>
              <i></i> <?php echo Lang::$word->NO;?> </label>
          </div>
        </div>
      </div>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->CONF_UPDATE;?></button>
    <input name="processConfig" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function () {
	var res2 = '<?php echo $core->mailer;?>';
		(res2 == "SMTP" ) ? $('.showsmtp').show() : $('.showsmtp').hide();
    $('#mailerchange').change(function () {
		var res = $("#mailerchange option:selected").val();
		(res == "SMTP" ) ? $('.showsmtp').show() : $('.showsmtp').hide();
	});
	
    (res2 == "SMAIL") ? $('.showsmail').show() : $('.showsmail').hide();
    $('#mailerchange').change(function () {
        var res = $("#mailerchange option:selected").val();
        (res == "SMAIL") ? $('.showsmail').show() : $('.showsmail').hide();
    });
});
// ]]>
</script>