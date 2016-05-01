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

    
        $found = false;
        $first = test_input($_POST['first']);
        $last = test_input($_POST['last']);
        $seller = test_input($_POST['number']);
        $emailGiven = test_input($_POST['email']);
        

        if($first != '' && $last != ''){
            $query_user = "select id, email, phone, school from sellers where first_name = ? and last_name = ?";
            $stmt = $db_connection->prepare($query_user);
            $stmt->bind_param("ss", $first, $last);
            $stmt->execute();
            $stmt->bind_result($id, $email, $phone, $school);
            $stmt->fetch();        
            if($id){
                $found = true;
            }        

            $stmt->close();                 
        }
        if($seller != '' && !$found){
            $query_user = "select id, first_name, last_name, email, phone, school from sellers where id = ?";
            $stmt = $db_connection->prepare($query_user);
            $stmt->bind_param("i", $seller);
            $stmt->execute();
            $stmt->bind_result($id, $first, $last, $email, $phone, $school);
            $stmt->fetch();         

            if($id){
                $found = true;                
            }        

            $stmt->close();                 

        }        
        if($emailGiven != '' && !$found){
            $query_user = "select id, first_name, last_name, phone, school from sellers where email = ?";
            $stmt = $db_connection->prepare($query_user);            
            $stmt->bind_param("s", $emailGiven);
            $stmt->execute();
            $stmt->bind_result($id, $first, $last, $phone, $school);
            $stmt->fetch();  

            $email = $emailGiven;

            if($id){
                $found = true;
            }                 

            $stmt->close();   
        }               
        


        if($found){
            $query_listings = "select id, isbn_13, price, guaranteed, pending from listings where seller = ?";
            $stmtl = $db_connection->prepare($query_listings);
            $stmtl->bind_param("i", $id);
            $stmtl->execute();
            $stmtl->bind_result($book, $isbn_13, $price, $guaranteed, $pending);
            $results = array();
            $results[] = [$id, $first, $last, $email, $phone, $school];            
            
            while($stmtl->fetch()){                              
                $results[] = array($book, $isbn_13, $price, $guaranteed, $pending);
            }

            echo json_encode($results);
        }
            



?>