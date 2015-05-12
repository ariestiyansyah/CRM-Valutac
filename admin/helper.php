<?php

  /**
   * Crumbs Navigation
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: helper.php, v3.00 2014-05-08 10:12:05 gewa Exp $
   */

  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php

  switch (Filter::$do) {
      case "users";
          switch (Filter::$action) {
              case "edit":
			  case "add":
                  print '<a data-help="staff" class="helper wojo top right attached help label"><i class="icon help"></i></a>';
                  break;
              default:
                  break;
          }

          break;

      case "clients":
          switch (Filter::$action) {
              case "edit":
			  case "add":
                  print '<a data-help="clients" class="helper wojo top right attached help label"><i class="icon help"></i></a>';
              default:
                  break;
          }


          break;

      case "config":

      default:
          print '<a data-help="config" class="helper wojo top right attached help label"><i class="icon help"></i></a>';
          break;

      case "backup":
      default:
          break;

      case "gateways":

          switch (Filter::$action) {
              case "edit":
                  print '<a data-help="gateway" class="helper wojo top right attached help label"><i class="icon help"></i></a>';
                  break;
              default:
                  break;
          }


          break;

      case "fields":

          switch (Filter::$action) {
              case "edit":
                  break;
              case "add":
                  break;
              default:
                  break;
          }


          break;

      case "forms":

          switch (Filter::$action) {
              case "edit":
                  break;
              case "add":
                  break;
              case "view":
                  break;
              case "viewdata":
                  break;
              case "fields":
                  break;
              default:
                  break;
          }

          break;

      case "estimator":

          switch (Filter::$action) {
              case "edit":
              case "add":
                  print '<a data-help="estimator" class="helper wojo top right attached help label"><i class="icon help"></i></a>';
                  break;
              case "fields":
                  break;
              case "view":
                  break;
              case "viewdata":
                  break;
              case "transaction":
                  break;
              default:
                  break;
          }

          break;

      case "news":

          switch (Filter::$action) {
              case "edit":
			  case "add":
                  print '<a data-help="news" class="helper wojo top right attached help label"><i class="icon help"></i></a>';
                  break;
              default:
                  break;
          }

          break;

      case "email":

      default:
          break;

      case "timebilling":

          switch (Filter::$action) {
              case "edit":
                  break;
              case "add":
                  break;
              case "view":
                  break;
              default:
                  break;
          }

          break;

      case "invoices":

          switch (Filter::$action) {
              case "edit":
			  case "add":
                  print '<a data-help="invoices" class="helper wojo top right attached help label"><i class="icon help"></i></a>';
              case "editentry":
                  break;
              case "editrecord":
                  break;
              default:
                  break;
          }

          break;

      case "invstatus":

      default:
          break;

      case "quotes":

          switch (Filter::$action) {
              case "edit":
                  break;
              case "add":
                  break;
              default:
                  break;
          }

          break;

      case "transactions":

      default:
          break;

      case "calendar":

      default:
          break;

      case "files":

          switch (Filter::$action) {
              case "edit":
                  break;
                  break;
              case "add":
                  break;
              default:
                  break;
          }

          break;

      case "task_template":

          switch (Filter::$action) {
              case "edit":
                  break;
              case "add":
                  break;
              default:
                  break;
          }

          break;

      case "tasks":

          switch (Filter::$action) {
              case "edit":
                  break;
              case "add":
                  break;
              default:
                  break;
          }

          break;

      case "types":

          switch (Filter::$action) {
              case "edit":
                  break;
              case "add":
                  break;
              default:
                  break;
          }

          break;

      case "projects":

          switch (Filter::$action) {
              case "edit":
                  break;
              case "add":
                  break;
              case "details":
                  break;
              default:
                  break;
          }

          break;

      case "overview":

      default:
          break;

      case "submissions":

          switch (Filter::$action) {
              case "edit":
                  break;
              case "add":
                  print '<a data-help="submissions" class="helper wojo top right attached help label"><i class="icon help"></i></a>';
                  break;
              default:
                  break;
          }

          break;

      case "support":

          switch (Filter::$action) {
              case "edit":
                  break;
              case "add":
                  break;
              default:
                  break;
          }

          break;

      case "messages":

          switch (Filter::$action) {
              case "view":
                  break;
              case "add":
                  break;
              default:
                  break;
          }

          break;

      case "language":

          switch (Filter::$action) {
              default:
                  print '<a data-help="language" class="helper wojo top right attached help label"><i class="icon help"></i></a>';
                  break;
          }

          break;
		  
      default:
          if (file_exists(BASEPATH . 'plugins/' . Filter::$do . '/helper.php')):
              include_once (BASEPATH . 'plugins/' . Filter::$do . '/helper.php');
          endif;

              break;
          }

?>