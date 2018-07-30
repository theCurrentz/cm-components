<?php
//functioon for requesting data from amazon ecom and rendering via wordpress shortcode
global $AWS_KEY_1, $AWS_KEY_2;

function amazonapi_function( $itemId ) {
  $asin = $itemId[item];
  // Your AWS Access Key ID, as taken from the AWS Your Account page
  $aws_access_key_id = $aws_key_1;

  // Your AWS Secret Key corresponding to the above ID, as taken from the AWS Your Account page
  $aws_secret_key = $aws_key_2;

  // The region you are interested in
  $endpoint = "webservices.amazon.com";

  $uri = "/onca/xml";

  $params = array(
      "Service" => "AWSECommerceService",
      "Operation" => "ItemLookup",
      "AWSAccessKeyId" => "AKIAILHXNTV7F4SUTTSQ",
      "AssociateTag" => "chroma-20",
      "ItemId" => "$asin",
      "IdType" => "ASIN",
      "ResponseGroup" => "Images,ItemAttributes,Offers"
  );

  // Set current timestamp if not set
  if (!isset($params["Timestamp"])) {
      $params["Timestamp"] = gmdate('Y-m-d\TH:i:s\Z');
  }

  // Sort the parameters by key
  ksort($params);

  $pairs = array();

  foreach ($params as $key => $value) {
      array_push($pairs, rawurlencode($key)."=".rawurlencode($value));
  }

  // Generate the canonical query
  $canonical_query_string = join("&", $pairs);

  // Generate the string to be signed
  $string_to_sign = "GET\n".$endpoint."\n".$uri."\n".$canonical_query_string;

  // Generate the signature required by the Product Advertising API
  $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $aws_secret_key, true));

  // Generate the signed URL
  $request_url = 'http://'.$endpoint.$uri.'?'.$canonical_query_string.'&Signature='.rawurlencode($signature);

  $url=$request_url;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_URL, $url);    // get the url contents

  $data = curl_exec($ch); // execute curl request
  curl_close($ch);

  $xml = simplexml_load_string($data);

  $price = $xml->Items->Item->OfferSummary->LowestNewPrice->FormattedPrice;

  $url = $xml->Items->Item->DetailPageURL;
  $result = "<a class='amazonlink' href = " . $url . " target='_blank'>(" . $price . "; amazon.com)" . "</a>";
  if (empty($price)) {
  }else{
    return $result;
  }
}
add_shortcode( 'amazonapi', 'amazonapi_function' );
