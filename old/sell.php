<?php
require_once "db/connect.php";
$school = $_COOKIE['college'];
$classes = $_COOKIE['classes'];
require_once "query/queryBooks.php";
//$isbn = $_GET['isbn'];
//$query_image = "select image from schools where id = ?";
//$stmt = $db_connection->prepare($query_image);
//$stmt->bind_param("i", $school);
//$stmt->execute();
//$stmt->bind_result($school_image);
//$stmt->fetch();
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Sell Your Books</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="../img/logo.ico">

    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
    <link href="../css/site.css" rel="stylesheet" />
    <?php // make into if image exists in img folder
//    if($school_image != ""){ // escape this "bobby tables"
//        echo '<style type="text/css">
//        .insert{
//            background: url("img/'.$school_image.'") no-repeat center fixed;
//            -webkit-background-size: cover;
//            -moz-background-size: cover;
//            -o-background-size: cover;
//            background-size: cover;
//        }
//        </style>';
//    }
    ?>
    <script src="../js/jquery-1.11.3.min.js" type="text/javascript"></script>
    <script src="../js/jquery.cookie.js"></script>
    <script src="../js/site.js" type="text/javascript"></script>
    <?php
//    if($isbn != ""){
//        echo "<script> $(document).ready(function(){
//        var element = $('.isbn:not(.hidden)').first();
//
//        getBook('".$isbn."', element); });</script>";
//    }
//

    ?>
</head>
<body class="insert">
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

        <div class="book-list">
            <?php
            $query_classinfo = "Select department, course from classes where id = ?";
            $query_booksinfo = "Select title, author, publisher from books where isbn_13 = ?";
            $query_listings = "Select id, seller, price from listings where isbn_13 = ? and pending = 0 and school = ?";

            echo "<div class='classes-slider'>";
            foreach($classes_books as $id => $books){
//                $stmt = $db_connection->prepare($query_classinfo);
//                $stmt->bind_param("i", $id);
//                $stmt->execute();
//                $stmt->bind_result($department, $course);
//                $stmt->fetch();
//                //echo class html
//                echo "<div class='classes-book'>";
//                if(strpos($department,'|') !== false){
//                    echo "<h1>".explode("|", $department)[1]." ".$course."</h1>";
//                }else{
//                    echo "<h1>".$department." ".$course."</h1>";
//                }
//                echo '<button class="offer-add-all">Add Class to Cart</button>';
//
//                Don’t feel like waiting for your books to sell? At any point, you may choose to sell your books through our third-party merchant account and receive payment immediately. <b>Your quote to sell through the third-party service is: $'.number_format($garent,2).'.</b><br><br>
//
//                To immediately sell your books, click <a href="capitolbooks.org/now.php?id='.$id.'">here</a>.<br><br>

//                $stmt->close();

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
//                        $priceN = $xml->Items->Item->Offers->Offer->OfferListing->Price->Amount;
//                        $priceN /= 100;
//                        $priceN *= 1.07;
//                        $priceN = number_format(round($priceN, 2),2);
//                        $price = "$".$priceN;
//
//                        echo "<div class='offer'>
//                        <div class='offer-price'>Used: ".$price."</div>
//                        <button class='offer-add' data-value='".$productId."'>Add Book To Cart</button>
//                      </div>";

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
                //echo "</div>";
            }
            echo "</div>";
            ?>
        </div>
        <div class="searchBook">
            <input class="isbn" type="text" autocomplete="off" placeholder="Book search">
            <ul id="resultsTop" class="dropdown-list hidden"></ul>
        </div>

<!--        <div class="insert-container">        -->
<!--            <div class="container">-->
<!--                <input class="isbn" type="text" autocomplete="off" placeholder="ISBN">-->
<!--                <div class="error book-error hidden"></div>-->
<!--                <div class="book-container hidden">-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="container">-->
<!--                <input class="isbn" type="text" autocomplete="off">-->
<!--                <div class="error book-error hidden"></div>-->
<!--                <div class="book-container hidden">-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="container">-->
<!--                <input class="isbn" type="text" autocomplete="off">-->
<!--                <div class="error book-error hidden"></div>-->
<!--                <div class="book-container hidden">-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="container">-->
<!--                <input class="isbn" type="text" autocomplete="off">-->
<!--                <div class="error book-error hidden"></div>-->
<!--                <div class="book-container hidden">-->
<!--                </div>-->
<!--            </div>-->
<!--            <!--<button class='button-big add-isbn'>Create Book<button>-->
<!--            <button class="button-big next">Finished</button>-->
<!---->
<!--            <form class="info" id="seller">-->
<!--                <input id="firstName" name="first" type="text" placeholder="First Name" required/>-->
<!--                <input id="lastName" name="last" type="text" placeholder="Last Name" required/>-->
<!--                <input id="email" name="email" type="text" placeholder="Email" required/>-->
<!--                <input id="phoneNumber" name="number" type="text" placeholder="Phone Number" required/>                                    -->
<!--                <div class="buttons">-->
<!--                    <button type="button" class="button-big" id="edit">Edit</button>-->
<!--                    <button type="submit" name="submit" class="button-big form-submit">Submit</button>-->
<!--                </div>-->
<!--            </form>            -->
<!--        </div>-->
    </div>
</body>
</html>