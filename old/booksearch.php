<?php
require_once "../db/connect.php";

$data = $_POST['data'];
$search = $data."%";
$results = '';

$query = "Select title, isbn_13 from books where title like ? limit 10";
$stmt = $db_connection->prepare($query);
$stmt->bind_param("s", $search);
$stmt->execute();
$stmt->bind_result($book, $isbn);
$num = 0;
while($stmt->fetch()){
    $results .= "<li data-value='".$isbn."'>".$book."</li>";
    $num++;
}

//if($num < 2){
//$googleKey = "AIzaSyB8SaGo1IurC5leU3BQSuU3_cWmsB6jALU";       
//$test = "AIzaSyCjSvero2qeLQo6gzp-9wYuCApbUqMtB-Q";     
//$response = file_get_contents('https://www.googleapis.com/books/v1/volumes?q=intitle:' . $data);
//$obj = json_decode($response);
////print_r($obj);
//$books = $obj->items;
////print_r($books);

//$b = 0;
//$i = 0;
//while($b < 10 && $i < 30){
//    $info = $books[$i]->volumeInfo;
//    $title = $info->title;
//    $isbns = $info->industryIdentifiers[1]->identifier;  
//    //echo $isbns." ";     
//    if(is_numeric($isbns)){
//        $b++;
//        //echo $isbns." ";
//        $results .= "<li data-value='".$isbns."'>".$title."</li>";
//    }    
//    $i++;
//}
//}


echo $results;
?>