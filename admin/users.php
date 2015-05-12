<?php
  /**
   * Users
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: users.php, v3.00 2014-05-08 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Filter::$action): case "edit": ?>
<?php $row = Core::getRowById(Users::uTable, Filter::$id);?>
<?php if($user->userlevel == 5 and $user->uid != Filter::$id): print Filter::msgInfo(Lang::$word->ADMINONLY, false); return; endif;?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->STAFF_INFO . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->STAFF_SUB . $row->fname . ' ' . $row->lname;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->USERNAME;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" value="<?php echo $row->username;?>" disabled="disabled" name="username">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PASSWORD;?></label>
        <input type="text" name="password">
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->EMAIL;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" value="<?php echo $row->email;?>" name="email">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PHONE;?></label>
        <label class="input">
          <input type="text" value="<?php echo $row->phone;?>" name="phone">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->FNAME;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" value="<?php echo $row->fname;?>" name="fname">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->LNAME;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
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
        <label class="input">
          <input type="text" value="<?php echo $row->city;?>" name="city">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->ZIP;?></label>
        <label class="input">
          <input type="text" value="<?php echo $row->zip;?>" name="zip">
        </label>
      </div>
    </div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->ADDRESS;?></label>
        <label class="input">
          <input type="text" value="<?php echo $row->address;?>" name="address">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->STATE;?></label>
        <label class="input">
          <input type="text" value="<?php echo $row->state;?>" name="state">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PPEMAIL;?></label>
        <label class="input">
          <input type="text" value="<?php echo $row->pp_email;?>" name="pp_email">
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
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->CREATED;?></label>
        <label class="input">
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
        <label class="input"><i class="icon-append icon calendar"></i>
          <input type="text" value="<?php echo $row->lastip;?>" disabled="disabled" name="lastip">
        </label>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <?php echo $content->rendertCustomFields('s', $row->custom_fields);?>
    <?php if($user->userlevel == 9):?>
    <div class="field">
      <label><?php echo Lang::$word->STAFF_ACCLEVEL;?></label>
      <div class="inline-group">
        <label class="radio">
          <input type="radio" name="userlevel" value="9" <?php getChecked($row->userlevel, 9); ?>>
          <i></i><?php echo Lang::$word->ADMIN;?></label>
        <label class="radio">
          <input type="radio" name="userlevel" value="5" <?php getChecked($row->userlevel, 5); ?>>
          <i></i><?php echo Lang::$word->STAFF;?></label>
      </div>
    </div>
    <?php endif;?>
    <div class="wojo divider"></div>
    <div class="field">
      <label><?php echo Lang::$word->NOTES;?></label>
      <label class="textarea">
        <textarea name="notes" rows="3"><?php echo $row->notes;?></textarea>
      </label>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->STAFF_UPDATE;?></button>
    <a href="index.php?do=users" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processUser" type="hidden" value="1">
    <?php if($user->userlevel == 5):?>
    <input name="userlevel" type="hidden" value="5" />
    <?php endif;?>
    <input name="username" type="hidden" value="<?php echo $row->username;?>">
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php case"add": ?>
<?php if($user->userlevel == 5): print Filter::msgInfo(Lang::$word->ADMINONLY, false); return; endif;?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->STAFF_INFO1 . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->STAFF_SUB1;?></h2>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->USERNAME;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" placeholder="<?php echo Lang::$word->USERNAME;?>" name="username">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PASSWORD;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" placeholder="<?php echo Lang::$word->PASSWORD;?>" name="password">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->EMAIL;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" placeholder="<?php echo Lang::$word->EMAIL;?>" name="email">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PHONE;?></label>
        <label class="input">
          <input type="text" placeholder="<?php echo Lang::$word->PHONE;?>" name="phone">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->FNAME;?></label>
        <label class="input"><i class="icon-append icon asterisk"></i>
          <input type="text" placeholder="<?php echo Lang::$word->FNAME;?>" name="fname">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->LNAME;?></label>
        <label class="input">
          <input type="text" placeholder="<?php echo Lang::$word->LNAME;?>" name="lname">
        </label>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->COUNTRY;?></label>
        <select name="country">
          <option disabled="" selected="" value="0"><?php echo Lang::$word->COUNTRY;?></option>
          <?php foreach($content->getCountryList() as $country):?>
          <option value="<?php echo $country->id;?>"><?php echo $country->name;?></option>
          <?php endforeach;?>
          <?php unset($country);?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CITY;?></label>
        <label class="input">
          <input type="text" placeholder="<?php echo Lang::$word->CITY;?>" name="city">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->ZIP;?></label>
        <label class="input">
          <input type="text" placeholder="<?php echo Lang::$word->ZIP;?>" name="zip">
        </label>
      </div>
    </div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->ADDRESS;?></label>
        <label class="input">
          <input type="text" placeholder="<?php echo Lang::$word->ADDRESS;?>" name="address">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->STATE;?></label>
        <label class="input">
          <input type="text" placeholder="<?php echo Lang::$word->STATE;?>" name="state">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PPEMAIL;?></label>
        <label class="input">
          <input type="text" placeholder="<?php echo Lang::$word->PPEMAIL;?>" name="pp_email">
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
      <div class="field"></div>
    </div>
    <div class="wojo fitted divider"></div>
    <?php echo $content->rendertCustomFields('s', false);?>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->STAFF_ACCLEVEL;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="userlevel" value="9">
            <i></i><?php echo Lang::$word->ADMIN;?></label>
          <label class="radio">
            <input type="radio" name="userlevel" checked="checked" value="5">
            <i></i><?php echo Lang::$word->STAFF;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->STAFF_NOTIFY;?></label>
        <div class="inline-group">
          <label class="checkbox">
            <input name="notify" type="checkbox" value="1">
            <i></i><?php echo Lang::$word->STAFF_NOTIFY;?></label>
        </div>
      </div>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->NOTES;?></label>
      <label class="textarea">
        <textarea name="notes" placeholder="<?php echo Lang::$word->NOTES;?>"></textarea>
      </label>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->STAFF_ADD;?></button>
    <a href="index.php?do=users" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processUser" type="hidden" value="1">
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php case"view": ?>
<?php if($user->userlevel == 5 and $user->uid != Filter::$id): print Filter::msgInfo(Lang::$word->ADMINONLY, false); return; endif;?>
<?php $payrow = $user->getStafPaymentHistory();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->STAFF_INFO3;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo header"><i class="user icon"></i><?php echo Lang::$word->STAFF_SUB3 . $user->name;?></h2>
</div>
<table class="wojo sortable table">
  <thead>
    <tr>
      <th data-sort="string"><?php echo Lang::$word->STAFF_PPTXN;?></th>
      <th data-sort="string"><?php echo Lang::$word->INVC_PAID;?></th>
      <th data-sort="int"><?php echo Lang::$word->CREATED;?></th>
      <th data-sort="string"><?php echo Lang::$word->NOTES;?></th>
    </tr>
  </thead>
  <tbody>
    <?php if(!$payrow):?>
    <tr>
      <td colspan="4"><?php echo Filter::msgSingleInfo(Lang::$word->CLIENT_NOTXN);?></td>
    </tr>
    <?php else:?>
    <?php foreach ($payrow as $row):?>
    <tr>
      <td><?php echo $row->txn_id;?></td>
      <td><?php echo $row->amount . ' ' . $row->currency;?></td>
      <td data-sort-value="<?php echo strtotime($row->created);?>"><?php echo Filter::dodate("long_date", $row->created);?></td>
      <td><?php echo cleanOut($row->note);?></td>
    </tr>
    <?php endforeach;?>
    <?php unset($row);?>
    <?php endif;?>
  </tbody>
</table>
<?php break;?>
<?php default: ?>
<?php $userrow = $user->getUsers();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->STAFF_INFO2;?></div>
</div>
<div class="wojo basic block segment">
  <?php if($user->userlevel == 9):?>
  <a class="wojo basic button push-right" href="index.php?do=users&amp;action=add"><i class="icon add"></i> <?php echo Lang::$word->STAFF_ADD;?></a>
  <?php endif;?>
  <h2 class="wojo left floated header"><i class="user icon"></i><?php echo Lang::$word->STAFF_SUB2;?></h2>
</div>
<table class="wojo sortable table">
  <thead>
    <tr>
      <th data-sort="string"><?php echo Lang::$word->NAME;?></th>
      <th data-sort="string"><?php echo Lang::$word->EMAIL;?></th>
      <th class="disabled"><?php echo Lang::$word->PAYMENTS;?></th>
      <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
    </tr>
  </thead>
  <?php foreach ($userrow as $row):?>
  <tr>
    <td><img src="<?php echo UPLOADURL;?>/avatars/<?php echo ($row->avatar) ? $row->avatar : "blank.png";?>" alt="" class="wojo avatar image"/><?php echo Core::renderName($row);?></td>
    <td><a href="index.php?do=email&amp;emailid=<?php echo urlencode($row->email);?>"><?php echo $row->email;?></a></td>
    <td><a href="index.php?do=users&amp;action=view&amp;id=<?php echo $row->id;?>" data-content="<?php echo Lang::$word->STAFF_PAYHISTORY;?>"><i class="rounded inverted info icon history link"></i></a>
      <?php if($user->userlevel == 9):?>
      <a data-id="<?php echo $row->id;?>" class="addpay" data-content="<?php echo Lang::$word->STAFF_ADDPAY;?>"><i class="rounded inverted warning icon payment link"></i></a>
      <?php endif;?></td>
    <td><a href="index.php?do=users&amp;action=edit&amp;id=<?php echo $row->id;?>"><i class="rounded inverted success icon pencil link"></i></a>
      <?php if($user->userlevel == 9):?>
      <a class="delete" data-title="<?php echo Lang::$word->STAFF_DELUSER;?>" data-option="deleteUser" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->username;?>"><i class="rounded danger inverted remove icon link"></i></a>
      <?php endif;?></td>
  </tr>
  <?php endforeach;?>
  <?php unset($row);?>
</table>
<?php if($user->userlevel == 9):?>
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function () {
    $('a.addpay').on('click', function () {
        var uid = $(this).data('id')
        var parent = $(this).parent().parent();
		  $.ajax({
			  type: 'post',
			  url: "controller.php",
			  data: {
				  getUserInfo: 1,
				  id: uid,
			  },
			  cache: false,
			  success: function (msg) {
				  $(".messi-content #pemail").val(msg);
			  }
		  });
        var text = "<table class=\"wojo small basic table form\">";
        text += "<tr><th><?php echo Lang::$word->EMAIL;?>:</th>";
        text += "<td><input name=\"receiverEmail\" id=\"pemail\" type=\"text\" class=\"inputbox\" value=\"\" size=\"35\" /></td></tr>";
        text += "<tr><th><?php echo Lang::$word->TRANS_PAYAMOUNT;?>:</th>";
        text += "<td><input name=\"amount\" id=\"pamount\" type=\"text\" value=\"\" size=\"15\" /></td></tr>";
        text += "<tr><th><?php echo Lang::$word->STAFF_PAYCURRENCY;?>:</th>";
        text += "<td><input name=\"currency\" id=\"pcurrency\" type=\"text\" value=\"<?php echo $core->currency;?>\" size=\"15\" /></td></tr>";
        text += "<tr><th><?php echo Lang::$word->NOTES;?>:</th>";
        text += "<td><textarea name=\"note\" id=\"pnote\" cols=\"35\" rows=\"4\"></textarea></td></tr>";
        text += "<input name=\"uid\" id=\"uid\" type=\"hidden\" value=\"" + uid + "\" /></table>";
		
        new Messi(text, {
            title: "<?php echo Lang::$word->STAFF_PAYSTAFF;?>",
            modal: true,
            closeButton: true,
            buttons: [{
                id: 0,
				class: 'basic',
				icon: '<i class="icon check"></i>',
                label: "<?php echo Lang::$word->STAFF_ADDPAY;?>",
                val: 'Y'
            }]
        });
    });
	
	$('body').on('click', '.messi a.mod-button', function () {
		var temail = $('#pemail').val();
		var tval = $('#pamount').val();
		var tnote = $('#pnote').val();
		var tcur = $('#pcurrency').val();
		var uid = $('#uid').val();
		$.ajax({
			type: 'post',
			url: "controller.php",
			dataType: 'json',
			data: {
				'MassPay': 1,
				'id': uid,
				'email': temail,
				'amount': tval,
				'cur': tcur,
				'note': tnote
			},
			success: function (json) {
				$.sticky(decodeURIComponent(json.message), {
					type: json.type,
					title: json.title
				});
			}
		});
	});
});
// ]]>
</script>
<?php endif;?>
<?php break;?>
<?php endswitch;?>