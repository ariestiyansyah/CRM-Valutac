<?php
  /**
   * Menu
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: footer.php, v3.00 2014-171-10 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<div class="content-center top-space">
  <div class="wojo avatar image">
    <?php if($user->avatar):?>
    <img src="<?php echo UPLOADURL;?>avatars/<?php echo $user->avatar;?>" alt="<?php echo $user->username;?>">
    <?php else:?>
    <img src="<?php echo UPLOADURL;?>avatars/blank.png" alt="<?php echo $user->username;?>">
    <?php endif;?>
  </div>
  <p class="hide-phone"><?php echo Lang::$word->MENU_WELCOME;?>, <?php echo $user->username;?>!</p>
  <div class="wojo half inverted divider"></div>
</div>
<nav id="menu" class="nav">
  <ul>
    <li> <a href="account.php"<?php if(!Filter::$do) echo ' class="active"';?>> <i class="icon dashboard"></i><span><?php echo Lang::$word->FMENU_DASH;?></span> </a> </li>
    <li> <a href="account.php?do=projects"<?php if(Filter::$do == 'projects') echo ' class="active"';?>> <i class="icon send"></i><span><?php echo Lang::$word->FMENU_PROJECTS;?></span> </a> </li>
    <li> <a href="account.php?do=billing"<?php if(Filter::$do == 'billing') echo ' class="active"';?>> <i class="icon payment"></i><span><?php echo Lang::$word->FMENU_BILL;?></span> </a> </li>
    <li> <a href="account.php?do=profile"<?php if(Filter::$do == 'profile') echo ' class="active"';?>> <i class="icon user"></i><span><?php echo Lang::$word->FMENU_PROFILE;?></span> </a> </li>
    <li> <a href="account.php?do=contact"<?php if(Filter::$do == 'contact') echo ' class="active"';?>> <i class="icon mail"></i><span><?php echo Lang::$word->FMENU_MSGS;?></span> </a> </li>
    <li> <a href="account.php?do=support"<?php if(Filter::$do == 'support') echo ' class="active"';?>> <i class="icon support"></i><span><?php echo Lang::$word->FMENU_SUPPORT;?></span> </a> </li>
  </ul>
</nav>
