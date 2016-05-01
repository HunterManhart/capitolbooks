<?php

session_start();

$cart = $_SESSION['cart'];

echo "https://www.amazon.com/gp/cart/aws-merge.html?cart-id=".$cart['CartId']."%26associate-id=capibook0a-20%26hmac=".$cart["HMAC"]."%26AWSAccessKeyId=AKIAII2UDQTW2DWWOULQ";

?>