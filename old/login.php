<?php

require_once "../db/connect.php";

session_start(); 

$data = false;
 
if (isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; // The hashed password.

    $pswrd = md5($password);         
    $query = "SELECT id FROM reps WHERE login = ? AND pass = ? LIMIT 1";

        $stmt = $db_connection->prepare($query);
        $stmt->bind_param("ss", $username, $pswrd);
        $stmt->execute();
        $stmt->bind_result($id);
        $stmt->fetch();                 
                 
        if (is_numeric($id)) {            
                 
            $_SESSION['user_id'] = $id;
            // XSS protection as we might print this value
            $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);
            $_SESSION['username'] = $username;
            $_SESSION['login_string'] = $pswrd;                 
            echo true;            

        } else {
            // No user exists.            
            echo false;
        }    
    
} else {
    // The correct POST variables were not sent to this page. 
    
}




?>