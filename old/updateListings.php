<?php
require_once "../db/connect.php";

session_start(); 


function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    if(empty($data)){
        $data = "NULL";         
    }
    return $data;
}

if(isset($_SESSION['user_id'])){    
    $rep = test_input($_SESSION['user_id']);

    $listings = $_POST['listings'];                

    $updates = 0;

    if(is_numeric($rep)){                    
        foreach($listings as $set){                       
            if(filter_var($set[0], FILTER_VALIDATE_BOOLEAN)){
                $query = "update listings set pending = 0, rep=? where id = ?";                

                $stmt = $db_connection->prepare($query);
                $stmt->bind_param("ii", $rep , test_input($set[1]));
                $stmt->execute();

                $updates++;
            }else{
                $query = "update listings set pending = 1, rep=? where id = ?";                

                $stmt = $db_connection->prepare($query);
                $stmt->bind_param("ii", $rep , test_input($set[1]));
                $stmt->execute();

                $updates++;
            }
        }
        
    }

    echo $updates;

}
?>