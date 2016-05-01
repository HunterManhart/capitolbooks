<?php
require_once "db/connect.php";
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Capitol Books</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="img/logo.ico">

    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
    <link href="css/site.css" rel="stylesheet" />

    <?php require_once "query/querySchools.php"?>
    <script src="js/jquery-1.11.3.min.js" type="text/javascript"></script>
    <script src="js/jquery.cookie.js"></script>
    <script src="js/site.js" type="text/javascript"></script>
</head>
<body>
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
        <div class="colleges">
            <div class="dropdown">
                <div class="border">
                    <input id="searchColleges" type="search" class="search" autocomplete="off" placeholder="Choose Your College">
                </div>
                <ul id="resultsColleges" class="dropdown-list hidden"></ul>
            </div>            
        </div>
    </div>
</body>
</html>
