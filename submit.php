<?php
require_once "db/connect.php";
$id = $_GET['id'];
$isbnList = array();

$query = "select isbn_13 from listings where seller = ? and pending = 1";
$stmt = $db_connection->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($get);
while($stmt->fetch()){
    $isbnList[] = $get;
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Campus Rep</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="img/logo.ico">

    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
    <link href="css/site.css" rel="stylesheet" />

    <script src="js/jquery-1.11.3.min.js" type="text/javascript"></script>    
    <script src="js/jquery.cookie.js"></script>
    <script src="js/site.js" type="text/javascript"></script>    
</head>
<body class="submit">
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

        <div class="message">
        <div>
            Thanks for listing with Capitol Books!<br>
            You'll recieve an email in the next 24 hours with important drop-off information,<br> so please be on the lookout.            
            </div>
        </div>

        <div class="order">
            Your Books:
            <div>
                <?php
                foreach($isbnList as $isbn){
                    if(!in_array($isbnList, $done)){
                        $done[] = $isbn;

                        require "query/checkAm.php";

                        $title = $xml->Items->Item->ItemAttributes->Title;
                        $author = $xml->Items->Item->ItemAttributes->Author;

                    if($title !=""){                                        
                    echo '<div class="container">'.
                        '<div class="book-head">
                            <div class="book-title">'.
                            $title  
                            .'</div>
                            <div class="book-author">'.
                                $author
                            .'</div>
                        </div>                        
                        <div class="book-footer">
                            <div class="book-isbn">ISBN13: '.$isbn.'</div>
                        </div></div>';
                        }
                        }
                }
                ?>
            </div>
        </div>

        <div class="questions">Please direct any questions to team@capitolbooks.org.</div>
    </div>
</body>
</html>