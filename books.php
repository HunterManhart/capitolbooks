<?php
require_once "db/connect.php";
$school = $_COOKIE['college'];
$classes = $_COOKIE['classes'];
require_once "query/queryBooks.php";
setcookie("classes", "", time()-3600);

$query_dorms = "SELECT id, place from dropoff WHERE school = ?";
$stmtd = $db_connection->prepare($query_dorms);
$stmtd->bind_param("i", $school);
$stmtd->execute();
$stmtd->bind_result($dormid, $dormname);
$dorms = "";
while($stmtd->fetch()){
    $dorms .= "<option value = '$dormid'>$dormname</option>";
}

function getPrice($isbn){
    $response = file_get_contents('http://prices.valorebooks.com/lookup-multiple-categories?SiteID=nuTnh3&ProductCode='.$isbn.'&ShowType=BuybackPriceOnly&MinimumCondition=3&Level=Normal');
    $xml=simplexml_load_string($response);
    $value = .8 * (float)$xml->{'buy-offer'}->{'item-price'};

    return number_format($value, 2);
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Find Your Books</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="img/logo.ico">

    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
    <link href="css/site.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/font-awesome.min.css">

    <script type='text/javascript'>
        var year = <?php echo getdate()['year'] ?>;
        var month = <?php echo getdate()['mon'] ?>;
        var day = <?php echo getdate()['mday'] ?>;
    </script>
    <script src="js/jquery-1.11.3.min.js" type="text/javascript"></script>
    <script src="js/jquery.cookie.js"></script>
    <script src="js/site.js" type="text/javascript"></script>
    <script type='text/javascript' src="js/whenisgood.js"></script>
</head>
<body class="insert">
<header>
    <h1><a href="index.php"><img class="logo" src="img/logo.svg" alt="Capitol Books Logo"/>Capitol Books</a></li></h1>
    <nav class="options">
        <ul>
            <li><a href="javascript: goPastColleges('find.php')">Classes</a></li>
            <li><a href="javascript: goPastColleges('books.php')">Books</a></li>
        </ul>
    </nav>
    <div class="error header-error"></div>
</header>

<div class="main">

    <div class="book-list">
        <?php
        $query_classinfo = "Select department, course from classes where id = ?";
        $query_booksinfo = "Select title, author, publisher from books where isbn_13 = ?";
        $query_listings = "Select id, seller, price from listings where isbn_13 = ? and pending = 0 and school = ?";

        foreach($classes_books as $id => $books){

            foreach($books as $isbn){ // more efficient if no repeated books (rare)
                //echo book html
                require "query/checkAm.php";

                $title = $xml->Items->Item->ItemAttributes->Title;
                $author = $xml->Items->Item->ItemAttributes->Author;
                $image = $xml->Items->Item->SmallImage->URL;

                echo '<div class="book-select">';
                if(isset($title) || isset($author)){
                    echo '<img src="'.$image.'">
                            <p class=\'book-title\'>'.$title.'</p>
                            <p class=\'book-isbn\'>'.$isbn.'</p>
                            <p class=\'book-price\'>$'.getPrice($isbn).'</p>
                            <button class="button small book-remove fa-times"></button>';

                }else{

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
        }
        ?>
        <button class="button special next fa-check">Finished</button>
    </div>
    <div class="searchBook">
        <input class="isbn" type="text" autocomplete="off" placeholder="Book search">
        <ul id="resultsTop" class="dropdown-list hidden"></ul>
        <form class="info" id="seller">
            <input id="firstName" name="first" type="text" placeholder="First Name" required/>
            <input id="lastName" name="last" type="text" placeholder="Last Name" required/>
            <input id="email" name="email" type="text" placeholder="Email" required/>
            <input id="phoneNumber" name="number" type="text" placeholder="Phone Number" required/>
            <?php echo '<select id="dorm">'.$dorms.'</select>'  ?>
            <h1>Choose <span id="timeHeader">3 times</span> below that you are available</h1>
            <div id="timegrid"></div>
            <div class="buttons">
                <button type="button" class="button special fa-pencil-square-o" id="edit">Edit</button>
                <button type="submit" name="submit" class="button form-submit fa-thumbs-o-up">Submit</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>