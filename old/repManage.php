<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Capitol Books</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="../img/logo.ico">

    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
    <link href="../css/site.css" rel="stylesheet" />
    <link href="login.css" rel="stylesheet" />

    <script src="../js/jquery-1.11.3.min.js" type="text/javascript"></script>
    <script src="../js/jquery.cookie.js"></script>
    <script src="../js/site.js" type="text/javascript"></script>
    <script src="login.js" type="text/javascript"></script>
</head>
<body class="rep">
    <header>
        <ul>
            <li class="title"><img class="logo" src="../img/logo.svg" alt="Capitol Books Logo"/>Capitol Books</li>
            <li class="dropdown">
                <input id="searchTop" type="search" class="search" autocomplete="off" data-toggle="dropdown" placeholder="Find a book">
                <ul id="resultsTop" class="dropdown-list hidden"></ul>
            </li>
            <li class="options">
                <ul>
                    <li><a href="javascript: goPastColleges('find.php')">BUY</a></li>
                    <li><a href="javascript: goPastColleges('sell.php')">SELL</a></li>
                    <li>
                        <a href="cart.php">YOUR CART</a>

                    </li>
                </ul>
                <div id="cart-notif">Eh</div>
            </li>
        </ul>
        <div class="error header-error"></div>
        <div class="header-message"></div>
    </header>

    <div class="main">
        <div class="block login-block ">
            <h1>Login</h1>
            <input type="text" value="" placeholder="Username" id="username" />
            <input type="password" value="" placeholder="Password" id="password" />
            <button class="login">Submit</button>
            <div class="error" id="errormessage"></div>
        </div>
        <div class="block listings-block hidden">
            <h1>Lookup Listings</h1>
            <input type="text" value="" placeholder="First Name" id="firstName" />
            <input type="text" value="" placeholder="Last Name" id="lastName" />
            <input type="text" value="" placeholder="Seller Number" id="sellerID" />
            <input type="text" value="" placeholder="Email" id="email" />
            <button class="seller">Submit</button>
            <div class="error" id="errorseller"></div>
        </div>
        <div class="block user-block hidden">
            <h1>User Info</h1>
            <div class="user">
                <div class="user user-id"></div>
                <div class="user user-name"></div>
                <div class="user user-contact"></div>
                <div class="user user-email"></div>
            </div>
            <div class="listings">
                <h1>Listings</h1>
                
            </div>
            <button type="button" class="listings-submit">Update</button>
        </div>
    </div>
</body>
</html>