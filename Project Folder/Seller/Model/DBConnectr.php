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
}
?>