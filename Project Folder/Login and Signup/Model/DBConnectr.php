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
        
        $sql = "SELECT * FROM Login WHERE email = '" . $email . "'";
        $result = $connection->query($sql);
        
        return $result;
    }

    // Caesar Cipher 
    function caesarCipher($password, $shift) {
        $result = "";
        
        for ($i = 0; $i < strlen($password); $i++) {
            $char = $password[$i];
            $ascii = ord($char);
            
            // Lowercase 
            if ($ascii >= 97 && $ascii <= 122) {
                $result .= chr((($ascii - 97 + $shift) % 26) + 97);
            }
            // Uppercase 
            elseif ($ascii >= 65 && $ascii <= 90) {
                $result .= chr((($ascii - 65 + $shift) % 26) + 65);
            }
            // Numbers 
            elseif ($ascii >= 48 && $ascii <= 57) {
                $result .= chr((($ascii - 48 + $shift) % 10) + 48);
            }
            else {
                $result .= $char;
            }
        }
        return $result;
    }
    
    // Function 4: Insert new user (Sign Up)
    function insertUser($connection, $email, $password) {
        $password = $this->caesarCipher($password, 5);
        
        $email = $connection->real_escape_string($email);
        $password = $connection->real_escape_string($password);
        $userType = "Customer";
        
        $sql = "INSERT INTO Login (email, password, user_type) VALUES ('" . $email . "', '" . $password . "', '" . $userType . "')";
        
        $result = $connection->query($sql);
        
        if (!$result) {
            die("Insert failed: " . $connection->error);
        }
        
        return $result;
    }
    
    // Function 5: Get user by email and password (for login)
    function getUserByEmail($connection, $email, $password) {
        $password = $this->caesarCipher($password, 5);
        
        $email = $connection->real_escape_string($email);
        $password = $connection->real_escape_string($password);
        
        $sql = "SELECT * FROM Login WHERE email = '" . $email . "' AND password = '" . $password . "'";
        $result = $connection->query($sql);
        
        return $result;
    }
}

?>