<?php
  /**
   * Clients
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: clients.php, v3.00 2014-05-08 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php if($user->userlevel == 5): print Filter::msgInfo(Lang::$word->ADMINONLY, false); return; endif;?>
<?php switch(Filter::$action): case "edit": ?>
<?php $row = Core::getRowById(Users::uTable, Filter::$id);?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->CLIENT_INFO . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->CLIENT_SUB . $row->fname . ' ' . $row->lname;?></h2>
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
        <label><?php echo Lang::$word->COMPANY;?></label>
        <label class="input">
          <input type="text" value="<?php echo $row->company;?>" name="company">
        </label>
      </div>
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
        <label class="input">
          <input type="text" value="<?php echo $row->vat;?>" name="vat">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CONF_CURRENCY;?></label>
        <label class="input"><i class="icon-append icon money"></i>
          <input type="text" value="<?php echo $row->currency;?>" name="currency">
        </label>
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
    <?php echo $content->rendertCustomFields('c', $row->custom_fields);?>
    <div class="field">
      <label><?php echo Lang::$word->NOTES;?></label>
      <label class="textarea">
        <textarea name="notes" rows="3"><?php echo $row->notes;?></textarea>
      </label>
    </div>
    <div class="wojo double fitted divider"></div>
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->STAFF_UPDATE;?></button>
    <a href="index.php?do=clients" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processUser" type="hidden" value="1">
    <input name="userlevel" type="hidden" value="1" />
    <input name="username" type="hidden" value="<?php echo $row->username;?>">
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php case"add": ?>
<?php if($user->userlevel == 5): print Filter::msgInfo(Lang::$word->ADMINONLY, false); return; endif;?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo Lang::$word->CLIENT_INFO1 . Lang::$word->REQFIELD1 . '<i class="icon asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
</div>
<div class="wojo basic block segment">
  <h2 class="wojo left floated header"><?php echo Lang::$word->CLIENT_SUB1;?></h2>
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
        <label><?php echo Lang::$word->COMPANY;?></label>
        <label class="input">
          <input type="text" placeholder="<?php echo Lang::$word->COMPANY;?>" name="company">
        </label>
      </div>
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
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->AVATAR;?></label>
        <label class="input">
          <input type="file" name="avatar" class="filefield">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->UVAT;?></label>
        <label class="input">
          <input type="text" placeholder="<?php echo Lang::$word->UVAT;?>" name="vat">
        </label>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <?php echo $content->rendertCustomFields('c', false);?>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CONF_CURRENCY;?></label>
        <label class="input"><i class="icon-append icon money"></i>
          <input type="text" placeholder="<?php echo Lang::$word->CONF_CURRENCY;?>" name="currency">
        </label>
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
    <button type="button" name="dosubmit" class="wojo basic button"><?php echo Lang::$word->CLIENT_ADD;?></button>
    <a href="index.php?do=clients" class="wojo black button"><?php echo Lang::$word->CANCEL;?></a>
    <input name="processUser" type="hidden" value="1">
    <input name="userlevel" type="hidden" value="1" />
  </form>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php default: ?>
<?php $clientrow = $user->getClients();?>
<div class="wojo black icon message"><i class="icon pin"></i>
  <div class="content"><?php echo Core::langIcon();?><?php echo str_replace("[+]", "<i class=\"icon add\"></i>", Lang::$word->CLIENT_INFO2);?></div>
</div>
<div class="wojo basic block segment">
  <?php if($user->userlevel == 9):?>
  <a class="wojo basic button push-right" href="index.php?do=clients&amp;action=add"><i class="icon add"></i> <?php echo Lang::$word->CLIENT_ADD;?></a>
  <?php endif;?>
  <h2 class="wojo left floated header"><i class="users icon"></i><?php echo Lang::$word->CLIENT_SUB2;?></h2>
</div>
<div class="wojo small form basic segment">
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="four fields">
      <div class="field">
        <div class="wojo input"> <i class="icon-prepend icon calendar"></i>
          <input name="fromdate" type="text" data-datepicker="true" placeholder="<?php echo Lang::$word->FROM;?>" id="fromdate">
        </div>
      </div>
      <div class="field">
        <div class="wojo action input"> <i class="icon-prepend icon calendar"></i>
          <input name="enddate" type="text" data-datepicker="true" placeholder="<?php echo Lang::$word->TO;?>" id="enddate">
          <a id="doDates" class="wojo icon button"><?php echo Lang::$word->FIND;?></a> </div>
      </div>
      <div class="field">
        <div class="wojo icon input">
          <input type="text" name="usersearchfield" placeholder="<?php echo Lang::$word->SEARCH;?>" id="searchfield">
          <i class="search icon"></i>
          <div id="suggestions"> </div>
        </div>
      </div>
      <div class="field">
        <div class="two fields">
          <div class="field"> <?php echo $pager->items_per_page();?> </div>
          <div class="field"> <?php echo $pager->jump_menu();?> </div>
        </div>
      </div>
    </div>
  </form>
  <div class="wojo divider"></div>
  <div id="abc" class="content-center"> <?php echo alphaBits('index.php?do=clients', "letter");?> </div>
  <div class="wojo fitted divider"></div>
</div>
<table class="wojo sortable table">
  <thead>
    <tr>
      <th data-sort="string"><?php echo Lang::$word->NAME;?></th>
      <th data-sort="string"><?php echo Lang::$word->COMPANY;?></th>
      <?php if($user->userlevel == 9):?>
      <th data-sort="string"><?php echo Lang::$word->EMAIL;?></th>
      <?php endif;?>
      <th data-sort="string"><?php echo Lang::$word->PROJECTS;?></th>
      <?php if($user->userlevel == 9):?>
      <th data-sort="int"><?php echo Lang::$word->CREDIT;?></th>
      <th data-sort="int"> <?php echo Lang::$word->BALANCE;?></th>
      <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
      <?php endif;?>
    </tr>
  </thead>
  <tbody>
    <?php if(!$clientrow):?>
    <tr>
      <td colspan="7"><?php echo Filter::msgSingleInfo(Lang::$word->CLIENT_NOCLIENTS);?></td>
    </tr>
    <?php else:?>
    <?php foreach ($clientrow as $row):?>
    <tr>
      <td><img src="<?php echo UPLOADURL;?>/avatars/<?php echo ($row->avatar) ? $row->avatar : "blank.png";?>" alt="" class="wojo image avatar"/><?php echo Core::renderName($row);?></td>
      <td><?php echo $row->company;?></td>
      <?php if($user->userlevel == 9):?>
      <td><a href="index.php?do=email&amp;emailid=<?php echo urlencode($row->email);?>"><?php echo $row->email;?></a></td>
      <?php endif;?>
      <td><span class="wojo black label"><?php echo $row->projects;?></span></td>
      <?php if($user->userlevel == 9):?>
      <td><a class="do-funds tooltip" data-id="<?php echo $row->id;?>" data-title="<?php echo Lang::$word->CLIENT_ADDCREDIT;?>"><i class="icon add"></i></a> <span id="get-funds_<?php echo $row->id;?>"><?php echo $row->credit;?></span></td>
      <td><?php echo ($row->balance) ? $row->balance : "0.00";?></td>
      <td><a href="index.php?do=clients&amp;action=edit&amp;id=<?php echo $row->id;?>"><i class="rounded inverted success icon pencil link"></i></a>
        <?php if($user->userlevel == 9):?>
        <a class="delete" data-title="<?php echo Lang::$word->CLIENT_DELCLIENT;?>" data-option="deleteUser" data-id="<?php echo $row->id;?>" data-name="<?php echo $row->username;?>"><i class="rounded danger inverted remove icon link"></i></a>
        <?php endif;?></td>
      <?php endif;?>
    </tr>
    <?php endforeach;?>
    <?php unset($row);?>
    <?php endif;?>
  </tbody>
</table>
<div class="wojo fitted divider"></div>
<div class="two columns horizontal-gutters">
  <div class="row"> <span class="wojo black label"><?php echo Lang::$word->TOTAL . ': ' . $pager->items_total;?> / <?php echo Lang::$word->CURPAGE . ': ' . $pager->current_page . ' ' . Lang::$word->OF . ' ' . $pager->num_pages;?></span> </div>
  <div class="row">
    <div class="push-right"><?php echo $pager->display_pages();?></div>
  </div>
</div>
<script type="text/javascript"> 
// <![CDATA[  
$(document).ready(function () {
    $("#searchfield").on('keyup', function () {
        var srch_string = $(this).val();
        var data_string = 'userSearch=' + srch_string;
        if (srch_string.length > 4) {
            $.ajax({
                type: "post",
                url: "controller.php",
                data: data_string,
                success: function (res) {
                    $('#suggestions').html(res).show();
                    $("input").blur(function () {
                        $('#suggestions').fadeOut();
                    });
                }
            });
        }
        return false;
    });
	
    $('a.do-funds').on('click', function () {
        var clid = $(this).data('id');
        var text = "<form  class=\"wojo small form\">";
        text += "<p class=\"wojo small basic message\"><?php echo Lang::$word->CLIENT_ADDCREDIT_I;?></p>";
        text += "<label class=\"input\">";
        text += "<input name=\"amount\" id=\"total-amount\" placeholder=\"<?php echo Lang::$word->CLIENT_ADDCREDIT_I1;?>\" type=\"text\">";
        text += "</label>";
        text += "<input name=\"uid\" id=\"uid\" type=\"hidden\" value=\"" + clid + "\" /></form>";

        new Messi(text, {
            title: "<?php echo Lang::$word->CLIENT_ADDCREDIT_T;?>",
            modal: true,
            closeButton: true,
            buttons: [{
                id: 0,
				class: 'basic',
				icon: '<i class="icon check"></i>',
                label: "<?php echo Lang::$word->SUBMIT;?>",
                val: 'Y'
            }],
            callback: function (val) {
				var clid = $("#uid").val();
				$.ajax({
					type: 'post',
					url: "controller.php",
					data: {
						'addClientFunds': $("#uid").val(),
						'amount': $("#total-amount").val()
					},
					success: function (res) {
						setTimeout(function () {
							$("#get-funds_" + clid).html(res).effect("highlight", {}, 1500);
						}, 500);
					}
				});
            }
        });
    });
	
	/* == From/To range == */
	var from_$input = $('input[name=fromdate]').pickadate({formatSubmit: 'yyyy-mm-dd'}),
		from_picker = from_$input.pickadate('picker')
	
	var to_$input = $('input[name=enddate]').pickadate({formatSubmit: 'yyyy-mm-dd'}),
		to_picker = to_$input.pickadate('picker')
	
	if ( from_picker.get('value') ) {
	  to_picker.set('min', from_picker.get('select'))
	}
	if ( to_picker.get('value') ) {
	  from_picker.set('max', to_picker.get('select'))
	}
	
	from_picker.on('set', function(event) {
	  if ( event.select ) {
		to_picker.set('min', from_picker.get('select'))    
	  }
	  else if ( 'clear' in event ) {
		to_picker.set('min', false)
	  }
	})
	to_picker.on('set', function(event) {
	  if ( event.select ) {
		from_picker.set('max', to_picker.get('select'))
	  }
	  else if ( 'clear' in event ) {
		from_picker.set('max', false)
	  }
	})
});
// ]]>
</script>
<?php break;?>
<?php endswitch;?>
