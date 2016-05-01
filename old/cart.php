<?php

session_start();

$cart = $_SESSION['cart'];

// Your AWS Access Key ID, as taken from the AWS Your Account page
$aws_access_key_id = "AKIAII2UDQTW2DWWOULQ";

// Your AWS Secret Key corresponding to the above ID, as taken from the AWS Your Account page
$aws_secret_key = "ifCaKl0s9vnIDmtSHYjWq5f4RCE7/2O9gjjJp5Zb";

// The region you are interested in
$endpoint = "webservices.amazon.com";

$uri = "/onca/xml";

$params = array(
    "Service" => "AWSECommerceService",
    "Operation" => "CartGet",
    "AWSAccessKeyId" => "AKIAII2UDQTW2DWWOULQ",
    "AssociateTag" => "capibook0a-20",
    "CartId" => $cart["CartId"],
    "HMAC" => $cart["HMAC"],
    "ResponseGroup" => "Cart"
);

//print_r($cart);

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

//echo $request_url;
//header('Location: '.$request_url);

$xml = simplexml_load_file($request_url);

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Capitol Books</title>   
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="../img/logo.ico">

    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
    <link href="../css/site.css" rel="stylesheet" />

    <script src="../js/jquery-1.11.3.min.js" type="text/javascript"></script>
    <script src="../js/jquery.cookie.js"></script>
    <script src="../js/site.js" type="text/javascript"></script>
</head>
<body class="cart-class">
    <header>
        <ul>            
            <li class="title"><img class="logo" src="../img/logo.svg" alt="Capitol Books Logo"/>Capitol Books</li>
            <li class="dropdown">
                <input id="searchTop" type="search" class="search" autocomplete="off" data-toggle="dropdown" placeholder="Find a book">
                <ul id="resultsTop" class="dropdown-list hidden"></ul>
            </li>
            <li class="options"><ul>
            <li><a href="javascript: goPastColleges('find.php')">BUY</a></li>
            <li><a href="javascript: goPastColleges('sell.php')">SELL</a></li>
            </ul></li>
        </ul>
        <div class="error header-error"></div>
        <div class="cart-error-checkout error">We need at least your email to confirm your order</div>    
        <div class="header-message"></div>
    </header>

    <div class="main">
        <div class="cart">
            <?php            
            
            $items = $xml->Cart->CartItems;
            if(isset($items->CartItem)){
                $num = $items->CartItem->count();                                    
            }else{
                echo "<div class='cart-error'>You Have No Items in your cart</div>";
            }           

            $totalN = $xml->Cart->SubTotal->Amount;
            $totalN /= 100;        
            $totalN *= 1.075; 
            $totalN += (3.99 * $num);
            $totalN = number_format(round($totalN, 2), 2);        
            $total = "$".$totalN;
            
            for($i=0; $i<$num; $i++){
                $cur = $items->CartItem[$i];
                $title = $cur->Title;
                $quan = $cur->Quantity;
                $priceN = $cur->ItemTotal->Amount;    
                $priceN /= 100;
                $priceN *= 1.075; 
                $priceN = number_format(round($priceN, 2), 2);        
                $price = "$".$priceN;
                $itemId = $cur->CartItemId;
                $asin = $cur->ASIN;

                echo "<div class='cart-item' data-value='".$itemId."' data-asin='".$asin."'>";                            

                echo '<input class="cart-quan" value="'.$quan.'">';
                echo '<div class="cart-price-ind">'.$price.'</div>';
                echo '<div class="cart-title">'.$title.'</div>';
                echo "</div>";
            }

            if(isset($items->CartItem)){                                                       
                echo '<div class="cart-total">Total: '.$total.'</div>';
                echo '<button class="cart-update">Update</button>';   
                echo '<button class="cart-buy">Checkout</button>';         
            }

            ?>            
        </div>                
    </div>
</body>
</html>