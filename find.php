<?php
require_once "db/connect.php";
$school = $_COOKIE['college'];
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
    <?php // make into if image exists in img folder
//    if($school_image != ""){ // escape this "bobby tables"
//        echo '<style type="text/css">
//        .find-class{
//            background: url("img/'.$school_image.'") no-repeat center fixed;
//            -webkit-background-size: cover;
//            -moz-background-size: cover;
//            -o-background-size: cover;
//            background-size: cover;
//        }
//        </style>';
//    }
    ?>    

    <?php require_once "query/queryClasses.php"?>
    <script src="js/jquery-1.11.3.min.js" type="text/javascript"></script>
    <script src="js/jquery.cookie.js"></script>
    <script src="js/site.js" type="text/javascript"></script>
</head>
<body class="find-class">
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

        <div class="find">
            <h2>Select Your Courses</h2>


            <div class="row-titles">
                <ul class="titles">
<!--                    <li class="">Term</li>-->
                    <li class="">Department</li>
                    <li class="">Course</li>
                    <?php 
                    if($school == 4){
                        echo '<li class="">Section</li>';
                    }else{
                        echo '<li class="">Teacher</li>';
                    }
                    ?>
                    
                </ul>
            </div>


            <div class="row-book">

                <div class="row-selections">

                    <ul class="opt">

<!--                        <li class="opt-term">-->
<!--                            <input class="" placeholder="Select Term" title="Term Input Box" type="text" value="" tabindex="0" autocomplete="OFF" aria-autocomplete="list">-->
<!--                            <ul class='opt-list'></ul>-->
<!--                        </li>-->

                        <li class="opt-department">
                            <input class="" placeholder="Select Department" title="Department Input Box" type="text" value="" tabindex="0" autocomplete="OFF" aria-autocomplete="list">
                            <ul class='opt-list'></ul>
                        </li>

                        <li class="opt-course">
                            <input class="" placeholder="Select Course" title="Course Input Box" type="text" value="" tabindex="0">
                            <ul class='opt-list'></ul>
                        </li>

                        <li class="opt-section">
                            <input class="opt-value" placeholder="Select Section" title="Section Input Box" type="text" value="" tabindex="0">
                            <ul class='opt-list'></ul>
                        </li>
                    </ul>

                </div>

                <div class="row-selections">

                    <ul class="opt">

<!--                        <li class="opt-term">-->
<!--                            <input class="" placeholder="Select Term" title="Term Input Box" type="text" value="" tabindex="0" autocomplete="OFF" aria-autocomplete="list">-->
<!--                            <ul class='opt-list'></ul>-->
<!--                        </li>-->

                        <li class="opt-department">
                            <input class="" placeholder="Select Department" title="Department Input Box" type="text" value="" tabindex="0" autocomplete="OFF" aria-autocomplete="list">
                            <ul class='opt-list'></ul>
                        </li>

                        <li class="opt-course">
                            <input class="" placeholder="Select Course" title="Course Input Box" type="text" value="" tabindex="0">
                            <ul class='opt-list'></ul>
                        </li>

                        <li class="opt-section">
                            <input class="opt-value" placeholder="Select Section" title="Section Input Box" type="text" value="" tabindex="0">
                            <ul class='opt-list'></ul>
                        </li>
                    </ul>

                </div>

                <div class="row-selections">

                    <ul class="opt">

<!--                        <li class="opt-term">-->
<!--                            <input class="" placeholder="Select Term" title="Term Input Box" type="text" value="" tabindex="0" autocomplete="OFF" aria-autocomplete="list">-->
<!--                            <ul class='opt-list'></ul>-->
<!--                        </li>-->

                        <li class="opt-department">
                            <input class="" placeholder="Select Department" title="Department Input Box" type="text" value="" tabindex="0" autocomplete="OFF" aria-autocomplete="list">
                            <ul class='opt-list'></ul>
                        </li>

                        <li class="opt-course">
                            <input class="" placeholder="Select Course" title="Course Input Box" type="text" value="" tabindex="0">
                            <ul class='opt-list'></ul>
                        </li>

                        <li class="opt-section">
                            <input class="opt-value" placeholder="Select Section" title="Section Input Box" type="text" value="" tabindex="0">
                            <ul class='opt-list'></ul>
                        </li>
                    </ul>

                </div>

                <div class="row-selections">

                    <ul class="opt">

<!--                        <li class="opt-term">-->
<!--                            <input class="" placeholder="Select Term" title="Term Input Box" type="text" value="" tabindex="0" autocomplete="OFF" aria-autocomplete="list">-->
<!--                            <ul class='opt-list'></ul>-->
<!--                        </li>-->

                        <li class="opt-department">
                            <input class="" placeholder="Select Department" title="Department Input Box" type="text" value="" tabindex="0" autocomplete="OFF" aria-autocomplete="list">
                            <ul class='opt-list'></ul>
                        </li>

                        <li class="opt-course">
                            <input class="" placeholder="Select Course" title="Course Input Box" type="text" value="" tabindex="0">
                            <ul class='opt-list'></ul>
                        </li>

                        <li class="opt-section">
                            <input class="opt-value" placeholder="Select Section" title="Section Input Box" type="text" value="" tabindex="0">
                            <ul class='opt-list'></ul>
                        </li>
                    </ul>

                </div>
                <div class="row-errors">
                    <div class="duplicateCourseErrorMessage" style="display:none;">
                        <a href="#" class="errorMsg" title="Error Selected course has already been added to the list. Please remove the duplicate course to continue. Press to tab to proceed">
                            Selected course has already been added to the list. Please remove the duplicate course to continue.
                        </a>
                    </div>
                    <div class="noTextBookCourseErrorMessage" style="display:none;">
                        <a href="#" class="errorMsg" title="Error This course does not require any textbooks. Press to tab to proceed">
                            This course does not require any textbooks.
                        </a>
                    </div>
                    <div class="incorrectOptionErrorMessage" style="display:none;">
                        <a href="#" class="errorMsg" title="Error Please enter a valid entry Press to tab to proceed">
                            Please enter a valid entry
                        </a>
                    </div>
                    <div class="completeOrRemoveErrorMessage" style="display:none;">
                        <a href="#" class="errorMsg" title="Error Please enter all fields to select a course or remove the row to continue.  Press to tab to proceed">
                            Please enter all fields to select a course or remove the row to continue.
                        </a>
                    </div>
                    <div class="cannotRemoveRow" style="display:none;">
                        <a href="#" class="errorMsg" title="Error No course details entered.  Please enter details for one course to continue. Press to tab to proceed">
                            No course details entered.  Please enter details for one course to continue.
                        </a>
                    </div>
                </div>
                <a href="#" class="removeBookRow removeBookRowTextIndent" title="Remove Row" tabindex="0"></a>
                <a href="#findMaterialButton" class="skipFCM" style="display: block;height: 0; width: 0; line-height: 0; font-size: 0" tabindex="0">Skip to Find the Material</a>
                
            </div>

            <center><a href="javascript: addCourse();" title="Add More Courses" class="button special addMoreRows" tabindex="0">Add More Courses </a></center>
        </div>
        <button class="button" id="get">Get Books</button>
    </div>    
</body>
</html>