<?php
function displayTerm($term) {
    $halves = explode("|", $term);
    $season = $halves[0];
    $result = "";
    if ($season == "S") {
        $result .= "Spring ";
    } elseif ($season == "F") {
        $result .= "Fall ";
    } elseif ($season == "M") {
        $result .= "Summer ";
    } elseif ($season == "W") {
        $result .= "Winter ";
    }

    $result .= "20" . $halves[1];

    return $result;
}



require_once "../db/connect.php";
$school = $_COOKIE['college'];

//$classes = array();
$types = array();
$query_classes = "SELECT id, term, department, course, teacher FROM classes WHERE school = ?";

$stmt = $db_connection->prepare($query_classes);
$stmt->bind_param("i", $school);
$stmt->execute();
$stmt->bind_result($id, $term, $depart, $course, $teacher);

while($stmt->fetch()){    
    //$classes[$id] = array($class[0], $class[1], $section);    
    if(strpos($depart,'|') !== false){
        $types[displayTerm($term)][trim(explode("|", $depart)[1])][trim($course)][trim($teacher)] = $id;    
    }else{
        $types[displayTerm($term)][trim($depart)][trim($course)][trim($teacher)] = $id;    
    }
}


//$json_classes = json_encode($classes);
$json_types = json_encode($types);

echo <<<EOD
<script type="text/javascript">
    var types = $json_types;
</script> 
EOD;





?>