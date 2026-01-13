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

    function checkProductNameExists($connection, $sellerEmail, $productName) {
       $sql = "SELECT * FROM products WHERE seller_email = ? AND product_name = ?";
       $stmt = $connection->prepare($sql);
       $stmt->bind_param("ss", $sellerEmail, $productName);
       $stmt->execute();
       $result = $stmt->get_result();
       return $result;
     }

    function insertProduct($connection, $sellerEmail, $productName, $category, $price, $quantity, $photoPath) {
        $sql = "INSERT INTO products (seller_email, product_name, product_category, product_price, product_quantity, product_photo) 
            VALUES (?, ?, ?, ?, ?, ?)";
      $stmt = $connection->prepare($sql);
      $stmt->bind_param("ssssds", $sellerEmail, $productName, $category, $price, $quantity, $photoPath);
      $result = $stmt->execute();
      return $result;
    }

    function getSellerByEmail($connection, $email) {
      $email = $connection->real_escape_string($email);
      $sql = "SELECT * FROM Seller WHERE Email = ?";
      $stmt = $connection->prepare($sql);
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $result = $stmt->get_result();
    
      return $result;
   }
   function updateSellerName($connection, $email, $name) {
    $sql = "UPDATE Seller SET Name = ? WHERE Email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $name, $email);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function updateSellerNID($connection, $email, $nid) {
    $sql = "UPDATE Seller SET NID = ? WHERE Email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $nid, $email);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function updateSellerEmail($connection, $oldEmail, $newEmail) {
    $sql = "UPDATE Seller SET Email = ? WHERE Email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $newEmail, $oldEmail);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function updateSellerPhone($connection, $email, $phone) {
    $sql = "UPDATE Seller SET Phone_Number = ? WHERE Email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $phone, $email);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function updateSellerPhoto($connection, $email, $photoPath) {
    $sql = "UPDATE Seller SET Photo = ? WHERE Email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $photoPath, $email);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
}
?>