<?php

class DBConnectr {
    
    // Function 1: Open connection to database
    function openConnection() {
        $db_host = "localhost";
        $db_user = "root";
        $db_password = "";
        $db_name = "sample test";
        
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
        
        $sql = "SELECT * FROM users WHERE Name = '" . $email . "'";
        $result = $connection->query($sql);
        
        return $result;
    }
    
    // Function 4: Insert new user (Sign Up)
    function insertUser($connection, $name, $password, $file) {
        $name = $connection->real_escape_string($name);
        $password = $connection->real_escape_string($password);
        $file = $connection->real_escape_string($file);
        
        $sql = "INSERT INTO users (Name, pass, file) VALUES ('" . $name . "', '" . $password . "', '" . $file . "')";
        
        $result = $connection->query($sql);
        
        if (!$result) {
            die("Insert failed: " . $connection->error);
        }
        
        return $result;
    }
    
    // Function 5: Get user by email/name (for login)
    function getUserByEmail($connection, $email, $password) {
        $email = $connection->real_escape_string($email);
        $password = $connection->real_escape_string($password);
        
        $sql = "SELECT * FROM users WHERE Name = '" . $email . "' AND pass = '" . $password . "'";
        $result = $connection->query($sql);
        
        return $result;
    }
}

?>