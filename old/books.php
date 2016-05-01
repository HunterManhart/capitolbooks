<?php
$classes = $_COOKIE['classes'];
$school = $_COOKIE['college'];
require_once "../db/connect.php";
require_once "query/queryBooks.php";
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Your Results</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="../img/logo.ico">

    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
    <link href="../css/site.css" rel="stylesheet" />

    <script src="../js/jquery-1.11.3.min.js" type="text/javascript"></script>
    <script src="../js/jquery.cookie.js" type="text/javascript"></script>
    <script src="../js/site.js" type="text/javascript"></script>
</head>
<body class="books-class">
    <header>
        <ul>
            <li class="title"><a href="../index.php"><img class="logo" src="../img/logo.svg" alt="Capitol Books Logo"/>Capitol Books</a></li>
            <li class="options">
                <ul>
                    <li><a href="javascript: goPastColleges('find.php')">Classes</a></li>
                    <li><a href="javascript: goPastColleges('sell.php')">Books</a></li>
                </ul>
            </li>
        </ul>
        <div class="error header-error"></div>
    </header>

    <div class="main">

    <div class="classes-buttons">
        <button class="classes-prev">Previous</button>
        <button class="classes-next">Next</button>
        <button id="to-cart">Go to cart</button>
    </div>    

    <?php
    $query_classinfo = "Select department, course from classes where id = ?";
    $query_booksinfo = "Select title, author, publisher from books where isbn_13 = ?";
    $query_listings = "Select id, seller, price from listings where isbn_13 = ? and pending = 0 and school = ?";

    echo "<div class='classes-slider'>";
    foreach($classes_books as $id => $books){        
        $stmt = $db_connection->prepare($query_classinfo);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($department, $course);
        $stmt->fetch();
        //echo class html
        echo "<div class='classes-book'>";
        if(strpos($department,'|') !== false){
            echo "<h1>".explode("|", $department)[1]." ".$course."</h1>";
        }else{
            echo "<h1>".$department." ".$course."</h1>";
        }
        echo '<button class="offer-add-all">Add Class to Cart</button>';

        $stmt->close();
        
        foreach($books as $isbn){ // more efficient if no repeated books (rare)                                
            //echo book html        
            require "query/checkAm.php";

            $title = $xml->Items->Item->ItemAttributes->Title;
            $author = $xml->Items->Item->ItemAttributes->Author;
            $publisher = $xml->Items->Item->ItemAttributes->Publisher;
            $edition = $xml->Items->Item->ItemAttributes->Edition;
            $productId = $xml->Items->Item->Offers->Offer->OfferListing->OfferListingId;
            $asin = $xml->Items->Item->ASIN;
        
            echo '<div>';
            if(isset($title) || isset($author)){                
                echo '<div class="book-head">
                    <div class="book-title">'.
                      $title
                    .'</div>
                    <div class="book-author">'.
                        $author
                    .'</div>
                </div>                
                <div class="book-footer">
                    <div class="book-edition">
                        Edition: '.$edition.'
                    </div>
                    <div class="book-publisher">
                        Publisher: '.$publisher.'
                    </div>
                    <div class="book-isbn">ISBN13: '.$isbn.'</div>                    
                </div>';

                //$stmtl = $db_connection->prepare($query_listings);
                //$stmtl->bind_param("ii", $isbn, $school);            
                //$stmtl->execute();
                //$stmtl->bind_result($listing, $seller, $price);                                              

                ////echo "<div class='books-slider'>";
                //while($stmtl->fetch()){
                //    //$query_user = "select first_name, last_name from sellers where id = ?";
                //    //$stmts = $db_connection->prepare($query_user);
                //    //$stmts->bind_param("i", $seller);
                //    //$stmts->execute();
                //    //$stmts->bind_result($first, $last);
                //    //$stmts->fetch();         
                //    //echo '<div>'.$first.' '.$last.': '.$price.'</div>';
                //    echo '<div>'.$seller.': '.$price.'</div>';
                //}
                //$priceLowNew = $xml->Items->Item->OfferSummary->LowestNewPrice->FormattedPrice;
                $priceN = $xml->Items->Item->Offers->Offer->OfferListing->Price->Amount;
                $priceN /= 100;        
                $priceN *= 1.07; 
                $priceN = number_format(round($priceN, 2),2);        
                $price = "$".$priceN;

                echo "<div class='offer'>
                        <div class='offer-price'>Used: ".$price."</div>
                        <button class='offer-add' data-value='".$productId."'>Add Book To Cart</button>                        
                      </div>";
                
                //echo "</div>";
            }else{
                //print_r($xml);

                $stmtb = $db_connection->prepare($query_booksinfo);
                $stmtb->bind_param("i", $isbn);
                $stmtb->execute();
                $stmtb->bind_result($title, $author, $publisher);
                $stmtb->fetch();

                echo '<div class="book-head">                    
                    <div class="book-title">'.
                      $title
                    .'</div>
                    <div class="book-author">'.
                        $author
                    .'</div>
                </div>                
                <div class="book-footer">                    
                    <div class="book-publisher">
                        Publisher: '.$publisher.'
                    </div>
                    <div class="book-isbn">ISBN13: '.$isbn.'</div> 
                    The book information above might not be correct and thus we cannot provide any offers for it.             
                </div>';
            }            
                      
            echo '</div>';  
        }        
        echo "</div>";
    }
    echo "</div>";
    ?>            

    </div>    
</body>
</html>





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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

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
    $query_listings = "INSERT INTO listings (id, seller, school, semester, isbn_13, price, guaranteed, pending, image_front, image_back, date_changed, date_entered) values (null, ?, ?, 2, ?, 0, 0, 1, null, null, now(), now())";
    $query_times = "INSERT INTO dropoff_times (id, seller, tYear, tMonth, tDay, tHour) values (null, ?, ?, ?, ?, ?)";
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
    foreach ($prices as $isbn) {
        $isbn = test_input($isbn);

        //$overall += (float)$price;
        //$garent += (float)$gar;
        $stmtl->bind_param("iii", $id, $school, $isbn);
        $stmtl->execute();
    }
    $stmtl->close();


    $stmtt = $db_connection->prepare($query_times);
    foreach($times as $dataT){
        $stmtt->bind_param("iiiii", $id, $dataT[0], $dataT[1], $dataT[2], $dataT[3]);
        $stmtt->execute();
        $stmtt->bind_result($id);
    }
    $stmtt->close();



    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    $headers .= 'From: "Orders" <orders@capitolbooks.org>' . "\r\n";

    $subject = "Listing Confirmation & Next Step";

    $message = '<html><div style="font-family: Verdana; font-size: 12px; font-color: black;"><b>Please read this email as it contains important information regarding your listing.</b><br><br>

Thanks for listing with Capitol Books! You listing number is #'.$id.', which you may use to track the progress of your order at <a href="capitolbooks.org/tracking.php?id='.$id.'">www.capitolbook.org/tracking</a>.<br><br>

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
