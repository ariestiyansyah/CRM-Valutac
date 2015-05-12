<?php
  /**
   * Profile
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: profile.php, v3.00 2014-06-05 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
 
  $row = $user->getUserData();
?>
<div class="wojo form tertiary segment">
  <h3 class="wojo header"><?php echo Lang::$word->PRO_SUB . $user->username;?></h3>
  <div class="wojo message">
    <p><i class="pin icon"></i> <?php echo Lang::$word->PRO_INFO . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
  </div>
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->USERNAME;?></label>
        <label class="input"> <i class="icon-prepend icon user"></i>
          <input type="text" disabled="disabled" name="username" readonly value="<?php echo $row->username;?>" placeholder="<?php echo Lang::$word->USERNAME;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PASSWORD;?></label>
        <label class="input"> <i class="icon-prepend icon lock"></i>
          <input type="text" name="password" placeholder="<?php echo Lang::$word->PASSWORD;?>">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->EMAIL;?></label>
        <label class="input"><i class="icon-prepend icon mail"></i> <i class="icon-append icon asterisk"></i>
          <input type="text" value="<?php echo $row->email;?>" name="email">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PHONE;?></label>
        <label class="input"><i class="icon-prepend icon phone"></i>
          <input type="text" value="<?php echo $row->phone;?>" name="phone">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->FNAME;?></label>
        <label class="input"><i class="icon-prepend icon user"></i><i class="icon-append icon asterisk"></i>
          <input type="text" value="<?php echo $row->fname;?>" name="fname">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->LNAME;?></label>
        <label class="input"><i class="icon-prepend icon user"></i><i class="icon-append icon asterisk"></i>
          <input type="text" value="<?php echo $row->lname;?>" name="lname">
        </label>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->COUNTRY;?></label>
        <select name="country">
          <option value="0"><?php echo Lang::$word->COUNTRY;?></option>
          <?php foreach($content->getCountryList() as $country):?>
          <?php $sel = ($country->id == $row->country) ? " selected=\"selected\"" : "" ;?>
          <option value="<?php echo $country->id;?>"<?php echo $sel;?>><?php echo $country->name;?></option>
          <?php endforeach;?>
          <?php unset($country);?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CITY;?></label>
        <label class="input"><i class="icon-prepend icon flag"></i>
          <input type="text" value="<?php echo $row->city;?>" name="city">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->ZIP;?></label>
        <label class="input"><i class="icon-prepend icon pin"></i>
          <input type="text" value="<?php echo $row->zip;?>" name="zip">
        </label>
      </div>
    </div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->COMPANY;?></label>
        <label class="input"><i class="icon-prepend icon building"></i>
          <input type="text" value="<?php echo $row->company;?>" name="company">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->STATE;?></label>
        <label class="input"><i class="icon-prepend icon umbrella"></i>
          <input type="text" value="<?php echo $row->state;?>" name="state">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->ADDRESS;?></label>
        <label class="input"><i class="icon-prepend icon map marker"></i>
          <input type="text" value="<?php echo $row->address;?>" name="address">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->AVATAR;?></label>
        <label class="input">
          <input type="file" name="avatar" class="filefield">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->AVATAR;?></label>
        <div class="wojo avatar image">
          <?php if($row->avatar):?>
          <img src="<?php echo UPLOADURL;?>avatars/<?php echo $row->avatar;?>" alt="<?php echo $user->username;?>">
          <?php else:?>
          <img src="<?php echo UPLOADURL;?>avatars/blank.png" alt="<?php echo $row->username;?>">
          <?php endif;?>
        </div>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->UVAT;?></label>
        <label class="input"><i class="icon-prepend icon payment"></i>
          <input type="text" value="<?php echo $row->vat;?>" name="vat">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_CURRENCY;?></label>
        <label class="input"><i class="icon-append icon information" data-content="<?php echo Lang::$word->CLIENT_CURRENCY_T;?>"></i> <i class="icon-prepend icon money"></i>
          <input type="text" value="<?php echo $row->currency;?>" name="currency">
        </label>
      </div>
    </div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->CREATED;?></label>
        <label class="input"><i class="icon-prepend icon calendar"></i>
          <input type="text" value="<?php echo $row->created;?>" disabled="disabled" name="created">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->STAFF_LASTLOGIN;?></label>
        <label class="input"><i class="icon-append icon calendar"></i>
          <input type="text" value="<?php echo $row->lastlogin;?>" disabled="disabled" name="lastlogin">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->STAFF_LASTIP;?></label>
        <label class="input"><i class="icon-prepend icon laptop"></i>
          <input type="text" value="<?php echo $row->lastip;?>" disabled="disabled" name="lastip">
        </label>
      </div>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" data-url="/ajax/controller.php" name="dosubmit" class="wojo button"><?php echo Lang::$word->PRO_SUBMIT;?></button>
    <input name="processUser" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>