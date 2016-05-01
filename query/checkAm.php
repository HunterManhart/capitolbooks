<?php

// Your AWS Access Key ID, as taken from the AWS Your Account page
$aws_access_key_id = "AKIAII2UDQTW2DWWOULQ";

// Your AWS Secret Key corresponding to the above ID, as taken from the AWS Your Account page
$aws_secret_key = "ifCaKl0s9vnIDmtSHYjWq5f4RCE7/2O9gjjJp5Zb";

// The region you are interested in
$endpoint = "webservices.amazon.com";

$uri = "/onca/xml";

$params = array(
    "Service" => "AWSECommerceService",
    "Operation" => "ItemLookup",
    "SearchIndex" => "All",
    "IdType" => "ISBN",
    "ItemId" => (string)$isbn,
    "AWSAccessKeyId" => "AKIAII2UDQTW2DWWOULQ",
    "AssociateTag" => "capibook0a-20",        
    "ResponseGroup" => "Images,ItemAttributes,Offers",
    "Condition" => "Used",
    "Sort" => "salesrank"
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

//header('Location: '.$request_url);

//echo $request_url;

$xml = simplexml_load_file($request_url);

//print_r($xml);

//print_r($xml->Items->Item->count());
//echo $xml->Items->Item->ItemAttributes->Title;
//echo $xml->Items->Item->ItemAttributes->Author;



?>