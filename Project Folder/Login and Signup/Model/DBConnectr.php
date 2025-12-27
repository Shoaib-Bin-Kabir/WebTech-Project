<?php

class DBConnectr {
    
    // Function 1: Open connection to database
    function openConnection() {
        $db_host = "localhost";
        $db_user = "root";
        $db_password = "";
        $db_name = "project table";
        
        $connection = new mysqli($db_host, $db_user, $db_password, $db_name);
        
        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }
        
        return $connection;
    }
    
    // Function 2: Close connection
    function closeConnection($connection) {
        $connection->close();
    }
    
    // Function 3: Check if email already exists
    function checkEmailExists($connection, $email) {
        $email = $connection->real_escape_string($email);
        
        $sql = "SELECT * FROM login WHERE email = '" . $email . "'";
        $result = $connection->query($sql);
        
        return $result;
    }
    
    // Function 4: Insert new user (Sign Up)
    function insertUser($connection, $email, $password) {
        $email = $connection->real_escape_string($email);
        $password = $connection->real_escape_string($password);
        $userType = "Customer";
        
        $sql = "INSERT INTO login (email, password, user_type) VALUES ('" . $email . "', '" . $password . "', '" . $userType . "')";
        
        $result = $connection->query($sql);
        
        if (!$result) {
            die("Insert failed: " . $connection->error);
        }
        
        return $result;
    }
    
    // Function 5: Get user by email and password (for login)
    function getUserByEmail($connection, $email, $password) {
        $email = $connection->real_escape_string($email);
        $password = $connection->real_escape_string($password);
        
        $sql = "SELECT * FROM login WHERE email = '" . $email . "' AND password = '" . $password . "'";
        $result = $connection->query($sql);
        
        return $result;
    }
}

?>