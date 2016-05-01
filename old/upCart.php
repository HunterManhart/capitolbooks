<?php

session_start();

$cart = $_SESSION['cart'];

$items = $_POST['items'];

// Your AWS Access Key ID, as taken from the AWS Your Account page
$aws_access_key_id = "AKIAII2UDQTW2DWWOULQ";

// Your AWS Secret Key corresponding to the above ID, as taken from the AWS Your Account page
$aws_secret_key = "ifCaKl0s9vnIDmtSHYjWq5f4RCE7/2O9gjjJp5Zb";

// The region you are interested in
$endpoint = "webservices.amazon.com";

$uri = "/onca/xml";

if($cart['CartId'] != ""){
$params = array(
    "Service" => "AWSECommerceService",
    "Operation" => "CartAdd",
    "AWSAccessKeyId" => "AKIAII2UDQTW2DWWOULQ",
    "AssociateTag" => "capibook0a-20",
    "CartId" => $cart["CartId"],
    "HMAC" => $cart["HMAC"],    
    "ResponseGroup" => "Cart"
);
}else{
$params = array(
    "Service" => "AWSECommerceService",
    "Operation" => "CartCreate",
    "AWSAccessKeyId" => "AKIAII2UDQTW2DWWOULQ",
    "AssociateTag" => "capibook0a-20",    
    "ResponseGroup" => "Cart"
);
}

// Add items
for($i=1; $i<=count($items); $i++){
    $params["Item.".$i.".OfferListingId"] = $items[$i-1];
    $params["Item.".$i.".Quantity"] = "1";
}

// Set current timestamp if not set
if (!isset($params["Timestamp"])) {
    $params["Timestamp"] = gmdate('Y-m-d\TH:i:s\Z');
}

//print_r($params);

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

//echo $request_url;
//header('Location: '.$request_url);

$xml = simplexml_load_file($request_url);


$cid = $xml->Cart->CartId;
$hmac = $xml->Cart->HMAC;
$sub = $xml->Cart->SubTotal->FormattedPrice;

//echo $cid;
//echo " ".$hmac;
echo $sub;

$cart = array(
    "CartId" => (string)$cid,
    "HMAC" => (string)$hmac
);

$_SESSION['cart'] = $cart;

//print_r($_SESSION);

?>