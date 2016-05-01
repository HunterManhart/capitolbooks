<?php

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    if(empty($data)){
        $data = "NULL";
    }
    return $data;
}


session_start();

$cart = $_SESSION['cart'];

$items = $_POST['items'];
$quans = $_POST['amount'];

$first = test_input($_POST['first']);
$last = test_input($_POST['last']);
$email = test_input($_POST['email']);
$number = test_input($_POST['number']);
$school = test_input($_COOKIE['college']);


require_once "../db/connect.php";

echo $first;

$query_buyer = "INSERT INTO buyers (id, first_name, last_name, email, phone, school) values (null, ?, ?, ?, ?, ?)";

$stmtb = $db_connection->prepare($query_buyer);
$stmtb->bind_param("ssssi", $first, $last, $email, $number, $school);
$stmtb->execute();
$user = $db_connection->insert_id;


$query_cart = "INSERT INTO carts (id, cartid, hmac, user_id, date_added, purchased) values (null, ?, ?, ?, now(), 0)";

$stmtc = $db_connection->prepare($query_cart);
$stmtc->bind_param("ssi", $cart['CartId'], $cart['HMAC'], $user);
$stmtc->execute();
$id = $db_connection->insert_id;


foreach($items as $index => $book){
    $query_items = "INSERT INTO cart_items (itemid, quantity, cart_id) values (?, ?, ?)";

    $stmti = $db_connection->prepare($query_items);
    $stmti->bind_param("sii", $book, $quans[$index], $id);
    $stmti->execute();    
    //echo "1";
}



$_SESSION['cart'] = null;

?>