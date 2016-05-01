<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    if(empty($data)){
        $data = "NULL";
    }
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    //              SQL

    require_once "../db/connect.php";

    $first = test_input($_POST['first']);
    $last = test_input($_POST['last']);
    $email = test_input($_POST['email']);
    $number = test_input($_POST['number']);
    $dorm = test_input($_POST['dorm']);
    $prices = json_decode($_POST['books'], true); // test this
    $times = $_POST['times'];
    $school = test_input($_COOKIE['college']);
    $ip = $_SERVER['REMOTE_ADDR'];

    $query = "SELECT id from sellers WHERE email = ?";

    $query_sellers = "INSERT INTO sellers (id, first_name, last_name, email, phone, school, subscribed, dorm) values (null, ?, ?, ?, ?, ?, 1, ?)";
    $update_sellers = "Update sellers set first_name = ?, last_name = ?, phone = ?, school = ?, dorm = ? where id = ?";
    //$query_id = "SELECT LAST_INSERT_ID()";
    $query_listings = "INSERT INTO listings (id, seller, school, semester, isbn_13, price, guaranteed, pending, image_front, image_back, date_changed, date_entered) values (null, ?, ?, 2, ?, 0, ?, 1, null, null, now(), now())";
    $query_times = "INSERT INTO dropoff_times (id, seller, tYear, tMonth, tDay, tHour, tHalf, date_entered) values (null, ?, ?, ?, ?, ?, ?, now())";
    $query_ip = "INSERT INTO ips (seller_id, ip) values (?, ?)";

    $id = 0;

    $stmt = $db_connection->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($id);
    while($stmt->fetch()){

    }
    $stmt->close();

    if($id == 0){
      $stmts = $db_connection->prepare($query_sellers);
      $stmts->bind_param("ssssii", $first, $last, $email, $number, $school, $dorm);
      $stmts->execute();

      $id = $db_connection->insert_id;
      $stmts->close();
    }else{
      $stmtu = $db_connection->prepare($update_sellers);
      $stmtu->bind_param("sssiii", $first, $last, $number, $school, $dorm, $id);
      $stmtu->execute();
      $stmtu->close();
    }
    $stmtip = $db_connection->prepare($query_ip);
    $stmtip->bind_param("is", $id, $ip);
    $stmtip->execute();
    $stmtip->close();


    $overall = 0;
    $stmtl = $db_connection->prepare($query_listings);
    foreach ($prices as $isbn => $price) {
      $isbn = test_input($isbn);

      //$overall += (float)$price;
      //$garent += (float)$gar;
        $price = substr($price, 1);
      $stmtl->bind_param("iiid", $id, $school, $isbn, $price);
      $stmtl->execute();
    }
    $stmtl->close();


    $stmtt = $db_connection->prepare($query_times);
    foreach($times as $dataT){
        $stmtt->bind_param("iiiiii", $id, $dataT[0], $dataT[1], $dataT[2], $dataT[3], $dataT[4]);
        $stmtt->execute();
        $stmtt->bind_result($id);
    }
    $stmtt->close();



    //       Google Sheets

    require_once '../vendor/autoload.php';
    require_once "../vendor/google/apiclient/src/Google/Client.php";  // vendor/autoload isn't working for google api

    $scopes = array('https://www.googleapis.com/auth/drive', 'https://spreadsheets.google.com/feeds','https://docs.google.com/feeds');

    $client = new Google_Client();
    $client->setScopes($scopes);
    $client->setAuthConfig('../db/Capitol-c04f20e0ad03.json');

    if($client->isAccessTokenExpired()) {
        $client->refreshTokenWithAssertion();
    }

    $accessToken = $client->getAccessToken();
    $accessToken = $accessToken['access_token'];

    $serviceRequest = new DefaultServiceRequest($accessToken);
    ServiceRequestFactory::setInstance($serviceRequest);
    $spreadsheetService = new Google\Spreadsheet\SpreadsheetService();
    $spreadsheetFeed = $spreadsheetService->getSpreadsheetFeed();
    $spreadsheet = $spreadsheetFeed->getByTitle("RepTimes");

    $worksheetFeed = $spreadsheet->getWorksheetFeed();
    $worksheet = $worksheetFeed->getByTitle('Raw Data');

//print_r($worksheet);
    $cellFeed = $worksheet->getCellFeed();


    $query_dorms = "SELECT id, place from dropoff WHERE school = ?";
    $stmtd = $db_connection->prepare($query_dorms);
    $stmtd->bind_param("i", $school);
    $stmtd->execute();
    $stmtd->bind_result($dormid, $dormname);
    $dorms = [];
    while($stmtd->fetch()){
        $dorms[$dormid] = $dormname;
    }

    $books = "";
    $overall = 0;
    foreach ($prices as $isbn => $price) {
        $overall += floatval(substr($price, 1));
        $books .= "\n$isbn: $price";
    }

    $timeStr = "";
    $timeStrHtml = "";
    foreach($times as $dataT){
        $timeStr .= "\n$dataT[5]";
        $timeStrHtml .= "<br>$dataT[5]";
    }

    $current = file_get_contents("sheetIncr.txt");
    ++$current;

    $cellFeed->editCell($current, 1, "$dorms[$dorm]");
    $cellFeed->editCell($current, 5, "$first $last\n $number\n $email");
    $cellFeed->editCell($current, 6, "$timeStr");
    $cellFeed->editCell($current, 7, "$books");
    $cellFeed->editCell($current, 8, "$overall");

    file_put_contents("sheetIncr.txt", $current);


    //          Email

    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    $headers .= 'From: "Orders" <orders@capitolbooks.org>' . "\r\n";

    $subject = "Listing Confirmation & Next Step";

    $message = '<html><div style="font-family: Verdana; font-size: 12px; font-color: black;"><b>Please read this email as it contains important information regarding your listing.</b><br><br>

Thanks for listing with Capitol Books! You listing number is #'.$id.'.<br><br>

ORDER INFORMATION<br>
-------------------------------------------<br><br>

Scheduled Pickup Times:'.$timeStrHtml.'<br><br>

Sellback Quote: $'.$overall.'<br><br>

If you have any questions in the meantime, don\'t hesitate to reply to this email, or give us a call at (508) 395-8435. We\'re always around and we love to help!</a>).<br><br>

From the team at Capitol Books, thanks for choosing us. We take pride in getting you the best prices for your textbooks.<br><br>

<div style="font-size: 16px;">Capitol Books Team</div><br><br>

_______________________________________________________<br><br>
</div></html>
';

    mail($email, $subject, $message, $headers);

    echo $id;

}
?>
