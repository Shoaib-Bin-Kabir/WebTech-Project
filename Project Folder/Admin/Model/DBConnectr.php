<?php

class DBConnectr {
    
   
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
    
    
    function closeConnection($connection) {
        $connection->close();
    }
    
   
   function checkEmailExists($connection, $email) {
       $sql = "SELECT * FROM Login WHERE email = ?";
       $stmt = $connection->prepare($sql);
       $stmt->bind_param("s", $email);
       $stmt->execute();
       $result = $stmt->get_result();
       return $result;
    }

    function caesarCipher($password, $shift) {
        $result = "";
        
        for ($i = 0; $i < strlen($password); $i++) {
            $char = $password[$i];
            $ascii = ord($char);
            
           
            if ($ascii >= 97 && $ascii <= 122) {
                $result .= chr((($ascii - 97 + $shift) % 26) + 97);
            }
           
            elseif ($ascii >= 65 && $ascii <= 90) {
                $result .= chr((($ascii - 65 + $shift) % 26) + 65);
            }
           
            elseif ($ascii >= 48 && $ascii <= 57) {
                $result .= chr((($ascii - 48 + $shift) % 10) + 48);
            }
            else {
                $result .= $char;
            }
        }
        return $result;
    }
    
  
    function addSeller($connection, $email, $password) {
        //$password = $this->caesarCipher($password, 5);
      $userType = "Seller";
    
      $sql = "INSERT INTO Login (email, password, user_type) VALUES (?, ?, ?)";
      $stmt = $connection->prepare($sql);
      $stmt->bind_param("sss", $email, $password, $userType);
      $result = $stmt->execute();
    
      if ($result){
          return $connection->insert_id;
      } else {
          return false;
      }
    }


    function insertSeller($connection, $id , $email , $password){
        $sql = "INSERT INTO Seller (ID,Email, Password) VALUES (?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("iss", $id, $email, $password);
        $result = $stmt->execute();
        return $result;
    }
    
    
}

?>