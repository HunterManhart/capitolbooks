<?php

require_once "../db/connect.php";

$query_schools = "select * from schools order by title";
$query = "";

$schools = array();

$result = $db_connection->query($query_schools);
if(!$result) die("Connection failed: " . $db_connection->connect_error);

$num_rows = $result->num_rows;
for($i=0; $i<$num_rows; $i++){
    $row = $result->fetch_row();
    $schools[] = array($row[0] => $row[1]);
}

$json_schools = json_encode($schools);  

echo <<<EOD
<script type="text/javascript">  
    //Populate array in JavaScript with the PHP array 
    var schools = $json_schools;
</script> 
EOD;
?>