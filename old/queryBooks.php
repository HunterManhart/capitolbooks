<?php
//require_once "../db/connect.php";
//$classes = [1, 2, 3];
$classes_books = array();

$query_books = "Select isbn_13 from classes_books where class_id = ?";
$classes = explode(",", $classes);

foreach($classes as $class){             
    $stmt = $db_connection->prepare($query_books);
    $stmt->bind_param("i", $class);
    $stmt->execute();
    $stmt->bind_result($book);
    $classes_books[$class] = array();
    while($stmt->fetch()){
        $classes_books[$class][] = $book;
    }        
}

//$json_classes_books = json_encode($classes_books);

//echo <<<EOD
//<script type="text/javascript">  
//    //Populate array in JavaScript with the PHP array    
//    var books = $json_classes_books;
//</script> 
//EOD;


?>