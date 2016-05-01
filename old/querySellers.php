<?php
require_once "../db/connect.php";
$classes = [1, 2, 3];
$listings = array();


//$classes = explode(",", $classes);

foreach($classes_books as $id => $books){         
    $stmt = $db_connection->prepare($query_books);
    $stmt->bind_param("i", $class);
    $stmt->execute();
    $stmt->bind_result($book);
    $classes_books[$class] = array();
    while($stmt->fetch()){
        $classes_books[$class][] = $book;        
    }        
}



?>