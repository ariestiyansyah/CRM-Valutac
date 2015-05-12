<?php
  /**
   * Authorize.Net Class
   *
   * @package Digital Downloads Pro
   * @author wojoscripts.com
   * @copyright 2010
   * @version $Id: anet_class.php, v2.00 2011-08-16 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  class AuthNet
  {
      public $field_string;
      public $fields = array();
      
      public $response_string;
      public $response = array();
      
      
      /**
       * AuthNet::__construct()
       * 
       * @return
       */
      function __construct()
      {
          $this->add_field('x_version', '3.1');
          $this->add_field('x_delim_data', 'TRUE');
          $this->add_field('x_delim_char', '|');
          $this->add_field('x_url', 'FALSE');
          $this->add_field('x_type', 'AUTH_CAPTURE');
          $this->add_field('x_method', 'CC');
          $this->add_field('x_relay_response', 'FALSE');
      }
      
      /**
       * AuthNet::add_field()
       * 
       * @return
       */
      public function add_field($field, $value)
      {
          $this->fields["$field"] = $value;
      }
      
      /**
       * AuthNet::process()
       * 
       * @return
       */
      public function process($aurl)
      {
          foreach ($this->fields as $key => $value)
              $this->field_string .= "$key=" . urlencode($value) . "&";
          
          // execute post via CURL
          $ch = curl_init($aurl);
          curl_setopt($ch, CURLOPT_PORT, 443);
          curl_setopt($ch, CURLOPT_HEADER, 0);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
          curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
          curl_setopt($ch, CURLOPT_TIMEOUT, 10);
          curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim($this->field_string, "& "));
          $this->response_string = urldecode(curl_exec($ch));
          
          if (curl_errno($ch)) {
              $this->response['Response Reason Text'] = curl_error($ch);
              return 3;
          } else
              curl_close($ch);
          
          $temp_values = explode('|', $this->response_string);
          
          $temp_keys = array("Response Code", "Response Subcode", "Response Reason Code", "Response Reason Text", "Approval Code", "AVS Result Code", "Transaction ID", "Invoice Number", "Description", "Amount", "Method", "Transaction Type", "Customer ID", "Cardholder First Name", "Cardholder Last Name", "Company", "Billing Address", "City", "State", "Zip", "Country", "Phone", "Fax", "Email", "Ship to First Name", "Ship to Last Name", "Ship to Company", "Ship to Address", "Ship to City", "Ship to State", "Ship to Zip", "Ship to Country", "Tax Amount", "Duty Amount", "Freight Amount", "Tax Exempt Flag", "PO Number", "MD5 Hash", "Card Code (CVV2/CVC2/CID) Response Code", "Cardholder Authentication Verification Value (CAVV) Response Code");
          
          for ($i = 0; $i <= 27; $i++) {
              array_push($temp_keys, 'Reserved Field ' . $i);
          }
		  
          $i = 0;
          while (sizeof($temp_keys) < sizeof($temp_values)) {
              array_push($temp_keys, 'Merchant Defined Field ' . $i);
              $i++;
          }
          
          $this->response = array_combine($temp_keys, $temp_values);
          return $this->response['Response Code'];
      }
      
      /**
       * AuthNet::responseText()
       * 
       * @return
       */
      public function responseText()
      {
          return $this->response['Response Reason Text'];
      }
      
      /**
       * AuthNet::getFields()
       * 
       * @return
       */
      public function getFields()
      {
          echo "<h3>authorizenet_class->dump_fields() Output:</h3>";
          echo "<table width=\"95%\" border=\"1\" cellpadding=\"2\" cellspacing=\"0\">
            <tr>
               <td bgcolor=\"black\"><b><font color=\"white\">Field Name</font></b></td>
               <td bgcolor=\"black\"><b><font color=\"white\">Value</font></b></td>
            </tr>";
          
          foreach ($this->fields as $key => $value) {
              echo "<tr><td>$key</td><td>" . urldecode($value) . "&nbsp;</td></tr>";
          }
          
          echo "</table><br>";
      }
      
      /**
       * AuthNet::getResponse()
       * 
       * @return
       */
      public function getResponse()
      {
          echo "<h3>authorizenet_class->dump_response() Output:</h3>";
          echo "<table width=\"95%\" border=\"1\" cellpadding=\"2\" cellspacing=\"0\">
            <tr>
               <td bgcolor=\"black\"><b><font color=\"white\">Index&nbsp;</font></b></td>
               <td bgcolor=\"black\"><b><font color=\"white\">Field Name</font></b></td>
               <td bgcolor=\"black\"><b><font color=\"white\">Value</font></b></td>
            </tr>";
          
          $i = 0;
          foreach ($this->response as $key => $value) {
              echo "<tr>
                  <td valign=\"top\" align=\"center\">$i</td>
                  <td valign=\"top\">$key</td>
                  <td valign=\"top\">$value&nbsp;</td>
               </tr>";
              $i++;
          }
          echo "</table><br>";
      }
  }
?>