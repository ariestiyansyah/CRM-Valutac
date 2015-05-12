<?php
  /**
   * Ipn
   *
   * @package Freelance Manager
   * @author wojoscripts.com
   * @copyright 2013
   * @version $Id: eipn.php, v2.00 2013-07-10 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  require_once("../../init.php");

  function ccValidate($ccn, $type)
  {
      switch ($type) {
          case "A":
              //American Express
              $pattern = "/^([34|37]{2})([0-9]{13})$/";
              return(preg_match($pattern, $ccn)) ? true : false;
              break;
              
          case "DI":
              //Diner's Club
              $pattern = "/^([30|36|38]{2})([0-9]{12})$/";
              return(preg_match($pattern, $ccn)) ? true : false;
              break;
              
          case "D":
              //Discover Card
              $pattern = "/^([6011]{4})([0-9]{12})$/";
              return(preg_match($pattern, $ccn)) ? true : false;
              break;
              
          case "M":
              //Mastercard
              $pattern = "/^([51|52|53|54|55]{2})([0-9]{14})$/";
              return(preg_match($pattern, $ccn)) ? true : false;
              break;
              
          case "V":
              //Visa
              $pattern = "/^([4]{1})([0-9]{12,15})$/";
              return(preg_match($pattern, $ccn)) ? true : false;
              break;
      }
  }
  
  function ccnCheck($ccn)
  {
      $ccn = preg_replace('/\D/', '', $ccn);
      $num_lenght = strlen($ccn);
      $parity = $num_lenght % 2;
      
      $total = 0;
      for ($i = 0; $i < $num_lenght; $i++) {
          $digit = $ccn[$i];
          if ($i % 2 == $parity) {
              $digit *= 2;
              if ($digit > 9) {
                  $digit -= 9;
              }
          }
          $total += $digit;
      }
      return($total % 10 == 0) ? true : false;
  }
  
  $row2 = $db->first("SELECT * FROM gateways WHERE name = 'anet'");
  $aurl = ($row2->live) ? 'https://secure.authorize.net/gateway/transact.dll' : 'https://test.authorize.net/gateway/transact.dll';
?>
<?php
  if (isset($_POST['doAnet'])) {
      if (empty($_POST['fname']))
          Filter::$msgs['fname'] = 'Please enter your first name';

      if (empty($_POST['lname']))
          Filter::$msgs['lname'] = 'Please enter your last name';

      if (empty($_POST['address']))
          Filter::$msgs['address'] = 'Please enter your address';

      if (empty($_POST['city']))
          Filter::$msgs['city'] = 'Please enter your city';

      if (empty($_POST['country']))
          Filter::$msgs['country'] = 'Please select your country';

      if (empty($_POST['state']))
          Filter::$msgs['state'] = 'Please select your state/province';

      if (empty($_POST['email']))
          Filter::$msgs['email'] = 'Please enter your email address';

      if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $_POST['email']))
          Filter::$msgs['email'] = 'Entered email address is invalid!';

      if (!isset($_POST['cctype']))
          Filter::$msgs['cctype'] = 'Please select your Credit Card Type';

      if (empty($_POST['ccn']))
          Filter::$msgs['ccn'] = 'Please enter your Credit Card number';

      if (!empty($_POST['ccn'])) {
          if (!ccValidate($_POST['ccn'], $_POST['cctype']))
              Filter::$msgs['ccn'] = 'Credit Card number does not match the card type';

          if (!ccnCheck($_POST['ccn']))
              Filter::$msgs['ccn'] = 'Invalid credit card number.';
      }

      if (empty($_POST['ccname']))
          Filter::$msgs['ccname'] = 'Please enter name on your Credit Card';

      if (empty($_POST['cvv']))
          Filter::$msgs['cvv'] = 'Please enter your 3/4 digit CVV code';

      if (empty(Filter::$msgs)) {
          require_once (BASEPATH . "gateways/anet/anet_class.php");
          $an = new AuthNet;

          $an->add_field('x_login', $row2->extra);
          $an->add_field('x_tran_key', $row2->extra3);
          $an->add_field('x_version', '3.1');
          $an->add_field('x_type', 'AUTH_CAPTURE');
          $an->add_field('x_test_request', ($row2->live) ? false : true);
          $an->add_field('x_relay_response', 'FALSE');
          $an->add_field('x_delim_data', 'TRUE');
          $an->add_field('x_delim_char', '|');
          $an->add_field('x_encap_char', '');

          $x_exp_date = $_POST['month'] . $_POST['year'];

          $an->add_field('x_method', 'CC');
          $an->add_field('x_card_num', html_entity_decode($_POST['ccn'], ENT_QUOTES, 'UTF-8'));
          $an->add_field('x_exp_date', $x_exp_date);
          $an->add_field('x_card_code', html_entity_decode($_POST['cvv'], ENT_QUOTES, 'UTF-8'));
          $an->add_field('x_first_name', html_entity_decode($_POST['fname'], ENT_QUOTES, 'UTF-8'));
          $an->add_field('x_last_name', html_entity_decode($_POST['lname'], ENT_QUOTES, 'UTF-8'));
          $an->add_field('x_address', html_entity_decode($_POST['address'], ENT_QUOTES, 'UTF-8'));
          $an->add_field('x_city', html_entity_decode($_POST['city'], ENT_QUOTES, 'UTF-8'));
          $an->add_field('x_state', html_entity_decode($_POST['state'], ENT_QUOTES, 'UTF-8'));
          $an->add_field('x_zip', html_entity_decode($_POST['zip'], ENT_QUOTES, 'UTF-8'));
          $an->add_field('x_country', html_entity_decode($_POST['country'], ENT_QUOTES, 'UTF-8'));
          $an->add_field('x_customer_ip', $_SERVER['REMOTE_ADDR']);
          $an->add_field('x_currency_code', 'USD');
          $an->add_field('x_invoice_num', time());
          $an->add_field('x_description', 'Service Name ' . html_entity_decode($_POST['item_name'], ENT_QUOTES, 'UTF-8'));
          $an->add_field('x_email', html_entity_decode($_POST['email'], ENT_QUOTES, 'UTF-8'));
          $an->add_field('x_amount', html_entity_decode($_POST['totalamount'], ENT_QUOTES, 'UTF-8'));
          $an->add_field('x_cust_id', $user->uid);

          $an->add_field('c_id', html_entity_decode($_POST['item_number'], ENT_QUOTES, 'UTF-8'));


          switch ($an->process($aurl)) {
              case 1:
				  $formrow = $db->first("SELECT * FROM estimator WHERE id = " . (int)$an->response['Merchant Defined Field 1']);
                  $checksum = strtoupper(md5($row2->extra2 . $row2->extra . $an->response['Transaction ID'] . $an->response['Amount']));
                  if ($checksum == $an->response['MD5 Hash']) {
                      $mc_gross = $an->response['Amount'];
					  
					  if($usr = $db->first("SELECT * FROM users WHERE id = " . $user->uid)) {
						  $first_last = $usr->fname . ' ' . $usr->fname;
						  $payer_email = $usr->email;
					  } else {
						  $first_last = sanitize($_POST['fname'] . ' ' . $_POST['lname']);
						  $payer_email = isset($_POST['email']) ? $_POST['email'] : false;
					  }
	  
					  $edata = array(
						  'txn_id' => $an->response['Transaction ID'],
						  'form_id' => $formrow->id,
						  'user' => $first_last,
						  'email' => $payer_email,
						  'price' => floatval($mc_gross),
						  'currency' => sanitize($an->response['currency_code']),
						  'created' => "NOW()",
						  'pp' => "AuthorizeNet",
						  'status' => 1
						  );
			
					  $db->insert("payments", $edata);
					  $db->delete("cart", "sesid = '" . $user->sesid . "'");
					  
                      print 1;

						  /* == Notify User == */
						  $form_data = getValue("form_data", "estimator_data", "sesid = '" . $user->sesid . "'");
						  if($payer_email and $form_data) {
						  require_once (BASEPATH . "lib/class_mailer.php");
						  $mailer = Mailer::sendMail();
		
						  ob_start();
						  require_once (BASEPATH . 'mailer/Email_Service_Payment.tpl.php');
						  $html_message = ob_get_contents();
						  ob_end_clean();

						  if (file_exists(UPLOADS . 'print_logo.png')) {
							  $logo = '<img src="' . UPLOADURL . 'print_logo.png" alt="' . Registry::get("Core")->company . '" />';
						  } elseif (Registry::get("Core")->logo) {
							  $logo = '<img src="' . UPLOADURL . Registry::get("Core")->logo . '" alt="' . Registry::get("Core")->company . '" />';
						  } else {
							  $logo = Registry::get("Core")->company;
						  }
		
						  $body = str_replace(array(
							  '[LOGO]',
							  '[NAME]',
							  '[FORMDATA]',
							  '[CCOMPANY]',
							  '[SITEURL]'), array(
							  $logo,
							  $first_last,
							  cleanOut($form_data),
							  Registry::get("Core")->company,
							  SITEURL), $html_message);
						  
						  $subject = Lang::$word->STAFF_PAYCOMPLETE_OK . $formrow->title;

						  $msg = Swift_Message::newInstance()
									->setSubject($subject)
									->setTo(array($payer_email => $first_last))
									->setFrom(array(Registry::get("Core")
									->site_email => Registry::get("Core")->company))
									->setBody($body, 'text/html');
						  $mailer->send($msg);
					  }

                  } else {
                      Filter::msgInfo('<span>Info!</span>Your payment was <strong>APPROVED!</strong> but there was a problem with confirmation.<br /> Please contact the administrator', false);
                  }

                  break;

              case 2:

                  Filter::msgError('<span>Error!</span>Your payment was <strong>DECLINED!</strong><br />' . $an->responseText(), false);
                  break;

              case 3:

                  Filter::msgError('<span>Error!</span>Payment processing returned <strong>ERROR!</strong><br />' . $an->responseText(), false);
                  break;
          }
      } else
          print Filter::msgStatus();

  }
?>