<?php
  /**
   * Crumbs Navigation
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: crumbs.php, v3.00 2014-05-08 10:12:05 gewa Exp $
   */

  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php
  switch (Filter::$do) {
      case "users";

          switch (Filter::$action) {
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=users" class="section">' . Lang::$word->MENU_STAFF . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->STAFF_TITLE . '</div>';
                  break;
              case "add":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=users" class="section">' . Lang::$word->MENU_STAFF . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->STAFF_TITLE1 . '</div>';
                  break;
              case "view":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=users" class="section">' . Lang::$word->MENU_STAFF . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->STAFF_TITLE3 . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->MENU_STAFF . '</div>';
                  break;
          }

          break;


      case "clients":

          switch (Filter::$action) {
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=clients" class="section">' . Lang::$word->MENU_CLIENTS . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->CLIENT_TITLE . '</div>';
                  break;
              case "add":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=clients" class="section">' . Lang::$word->MENU_CLIENTS . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->CLIENT_TITLE1 . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->MENU_CLIENTS . '</div>';
                  break;
          }


          break;

      case "config":

      default:
          echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->CONF_TITLE . '</div>';
          break;

      case "backup":

      default:
          echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->DB_TITLE . '</div>';
          break;

      case "gateways":

          switch (Filter::$action) {
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=gateways" class="section">' . Lang::$word->GATE_TITLE1 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->GATE_TITLE . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->GATE_TITLE1 . '</div>';
                  break;
          }


          break;

      case "fields":

          switch (Filter::$action) {
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=fields" class="section">' . Lang::$word->CUSF_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->CUSF_TITLE1 . '</div>';
                  break;
              case "add":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=fields" class="section">' . Lang::$word->CUSF_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->CUSF_TITLE2 . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->CUSF_TITLE . '</div>';
                  break;
          }


          break;

      case "forms":

          switch (Filter::$action) {
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=forms" class="section">' . Lang::$word->FORM_TITLE4 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->FORM_TITLE . '</div>'; 
                  break;
              case "add":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=forms" class="section">' . Lang::$word->FORM_TITLE4 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->FORM_TITLE1 . '</div>'; 
                  break;
              case "view":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=forms" class="section">' . Lang::$word->FORM_TITLE4 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->FORM_TITLE5 . '</div>'; 
                  break;
              case "viewdata":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=forms" class="section">' . Lang::$word->FORM_TITLE4 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->FORM_TITLE3 . '</div>'; 
                  break;
              case "fields":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=forms" class="section">' . Lang::$word->FORM_TITLE4 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->FORM_TITLE2 . '</div>'; 
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->FORM_TITLE4 . '</div>';
                  break;
          }

          break;

      case "estimator":

          switch (Filter::$action) {
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=estimator" class="section">' . Lang::$word->ESTM_TITLE2 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->FORM_TITLE . '</div>'; 
                  break;
              case "add":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=estimator" class="section">' . Lang::$word->ESTM_TITLE2 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->FORM_TITLE1 . '</div>'; 
                  break;
              case "fields":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=estimator" class="section">' . Lang::$word->ESTM_TITLE2 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->ESTM_TITLE3 . '</div>'; 
                  break;
              case "view":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=estimator" class="section">' . Lang::$word->ESTM_TITLE2 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->FORM_TITLE5 . '</div>'; 
                  break;
              case "viewdata":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=estimator" class="section">' . Lang::$word->ESTM_TITLE2 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->ESTM_TITLE6 . '</div>'; 
                  break;
              case "transaction":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=estimator" class="section">' . Lang::$word->ESTM_TITLE2 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->ESTM_TITLE7 . '</div>'; 
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->ESTM_TITLE2 . '</div>';
                  break;
          }

          break;

      case "news":

          switch (Filter::$action) {
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=news" class="section">' . Lang::$word->NEWS_TITLE2 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->NEWS_TITLE . '</div>';
                  break;
              case "add":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=news" class="section">' . Lang::$word->NEWS_TITLE2 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->NEWS_TITLE1 . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->NEWS_TITLE2 . '</div>';
                  break;
          }

          break;

      case "email":

      default:
          echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->MAIL_TITLE . '</div>';
          break;

      case "mtemplates":

      default:
          echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->MTPL_TITLE . '</div>';
          break;
		  
      case "timebilling":

          switch (Filter::$action) {
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=timebilling&amp;action=view&amp;id=' . get('pid') . '" class="section">' . Lang::$word->BILL_TITLE3 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->BILL_TITLE1 . '</div>';
                  break;
              case "add":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=timebilling&amp;action=view&amp;id=' . Filter::$id . '" class="section">' . Lang::$word->BILL_TITLE3 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->BILL_TITLE2 . '</div>';
                  break;
              case "view":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=timebilling" class="section">' . Lang::$word->BILL_TITLE3 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->BILL_TITLE . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->BILL_TITLE3 . '</div>';
                  break;
          }

          break;

      case "invoices":

          switch (Filter::$action) {
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=invoices&amp;id=' . get('pid') . '" class="section">' . Lang::$word->INVC_TITLE3 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->INVC_TITLE . '</div>';
                  break;
              case "editentry":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=invoices&amp;id=' . get('pid') . '" class="section">' . Lang::$word->INVC_TITLE3 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->INVC_ENTRYTITLE2 . '</div>';
                  break;
              case "editrecord":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=invoices&amp;id=' . get('pid') . '" class="section">' . Lang::$word->INVC_TITLE3 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->INVC_RECTITLE2 . '</div>';
                  break;
              case "add":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=invoices&amp;id=' . Filter::$id . '" class="section">' . Lang::$word->INVC_TITLE3 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->INVC_TITLE2 . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->INVC_TITLE3 . '</div>';
                  break;
          }

          break;

      case "invstatus":

      default:
          echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->INVC_TITLE4 . '</div>';
          break;

      case "quotes":

          switch (Filter::$action) {
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=quotes" class="section">' . Lang::$word->QUTS_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->QUTS_TITLE2 . '</div>';
                  break;
              case "add":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=quotes" class="section">' . Lang::$word->QUTS_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->QUTS_TITLE3 . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->QUTS_TITLE . '</div>';
                  break;
          }

          break;

      case "transactions":

      default:
          echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->TRANS_TITLE . '</div>';
          break;

      case "calendar":

      default:
          echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->CAL_TITLE . '</div>';
          break;

      case "files":

          switch (Filter::$action) {
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=files" class="section">' . Lang::$word->FILE_TITLE2 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->FILE_TITLE . '</div>';
                  break;
                  break;
              case "add":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=files" class="section">' . Lang::$word->FILE_TITLE2 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->FILE_TITLE1 . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->FILE_TITLE2 . '</div>';
                  break;
          }

          break;

      case "task_template":

          switch (Filter::$action) {
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=task_template" class="section">' . Lang::$word->TTASK_TITLE2 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->TTASK_TITLE . '</div>';
                  break;
              case "add":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=task_template" class="section">' . Lang::$word->TTASK_TITLE2 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->TTASK_TITLE1 . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->TTASK_TITLE2 . '</div>';
                  break;
          }

          break;

      case "tasks":

          switch (Filter::$action) {
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=tasks" class="section">' . Lang::$word->TASK_TITLE2 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->TASK_TITLE . '</div>';
                  break;
              case "add":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=tasks" class="section">' . Lang::$word->TASK_TITLE2 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->TASK_TITLE1 . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->TASK_TITLE2 . '</div>';
                  break;
          }

          break;

      case "types":

          switch (Filter::$action) {
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=types" class="section">' . Lang::$word->TYPE_TITLE2 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->TYPE_TITLE . '</div>';
                  break;
              case "add":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=types" class="section">' . Lang::$word->TYPE_TITLE2 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->TYPE_TITLE1 . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->TYPE_TITLE2 . '</div>';
                  break;
          }

          break;

      case "projects":

          switch (Filter::$action) {
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=projects" class="section">' . Lang::$word->PROJ_TITLE3 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->PROJ_TITLE . '</div>';
                  break;
              case "add":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=projects" class="section">' . Lang::$word->PROJ_TITLE3 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->PROJ_TITLE1 . '</div>';
                  break;
              case "details":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=projects" class="section">' . Lang::$word->PROJ_TITLE3 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->PROJ_TITLE2 . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->PROJ_TITLE3 . '</div>';
                  break;
          }

          break;

      case "overview":

      default:
         echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=projects" class="section">' . Lang::$word->PROJ_TITLE3 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->OVER_TITLE . '</div>';
          break;

      case "submissions":

          switch (Filter::$action) {
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=submissions&amp;id=' . get('pid') . '" class="section">' . Lang::$word->SUBS_TITLE2 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->SUBS_TITLE1 . '</div>';
                  break;
              case "add":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=submissions&amp;id=' . Filter::$id . '" class="section">' . Lang::$word->SUBS_TITLE2 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->SUBS_TITLE1 . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=projects" class="section">' . Lang::$word->PROJ_TITLE3 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->SUBS_TITLE2 . '</div>';
                  break;
          }

          break;

      case "support":

          switch (Filter::$action) {
              case "edit":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=support&amp;id=' . Filter::$id . '" class="section">' . Lang::$word->SUP_TITLE1 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->SUP_TITLE . '</div>';
                  break;
              case "add":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=support&amp;id=' . Filter::$id . '" class="section">' . Lang::$word->SUP_TITLE1 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->SUP_TITLE2 . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->SUP_TITLE1 . '</div>';
                  break;
          }

          break;

      case "messages":

          switch (Filter::$action) {
              case "view":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=messages" class="section">' . Lang::$word->MSG_TITLE2 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->MSG_TITLE . '</div>';
                  break;
              case "add":
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <a href="index.php?do=messages" class="section">' . Lang::$word->MSG_TITLE2 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->MSG_TITLE1 . '</div>';
                  break;
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->MSG_TITLE2 . '</div>';
                  break;
          }

          break;

      case "language":

          switch (Filter::$action) {
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->LM_TITLE1 . '</div>';
                  break;
          }

          break;

      case "system":

          switch (Filter::$action) {
              default:
                  echo '<i class="icon home"></i> <a href="index.php" class="section">' . Lang::$word->DASH_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->SYS_TITLE . '</div>';
                  break;
          }

          break;
		  
      default:
          if (file_exists(BASEPATH . 'plugins/' . Filter::$do . '/crumbs_admin.php')):
              include_once (BASEPATH . 'plugins/' . Filter::$do . '/crumbs_admin.php');
          else:
              echo Lang::$word->DASH_TITLE;
          endif;

      break;
  }
?>