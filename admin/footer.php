<?php
  /**
   * Footer
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2011
   * @version $Id: footer.php, v2.00 2013-05-08 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<!-- Footer -->
<div class="wojo-grid">
  <footer id="footer" class="clearfix">
    <div class="copyright">Copyright &copy;<?php echo date('Y').' '.$core->company;?> &bull;</div>
  </footer>
</div>
<!-- Footer /-->
<script src="../assets/fullscreen.js"></script>
<script type="text/javascript"> 
// <![CDATA[  
$(document).ready(function () {
    $.Master({
		weekstart: <?php echo ($core->weekstart - 1);?>,
        lang: {
            button_text: "<?php echo Lang::$word->BROWSE;?>",
            empty_text: "<?php echo Lang::$word->NOFILE;?>",
            monthsFull: [<?php echo Core::monthList(false);?>],
            monthsShort: [<?php echo Core::monthList(false, false);?>],
			weeksFull : [<?php echo Core::weekList(false);?>],
			weeksShort : [<?php echo Core::weekList(false, false);?>],
			today : "<?php echo Lang::$word->DASH_TODAY;?>",
			clear : "<?php echo Lang::$word->CLEAR;?>",
			delBtn : "<?php echo Lang::$word->DELETE_REC;?>",
			selProject : "<?php echo Lang::$word->INVC_PROJCSELETC;?>",
            delMsg1: "<?php echo Lang::$word->DELCONFIRM;?>",
            delMsg2: "<?php echo Lang::$word->DELCONFIRM1;?>",
            working: "<?php echo Lang::$word->WORKING;?>"
        }
    });
});
// ]]>
</script>
</body></html>
