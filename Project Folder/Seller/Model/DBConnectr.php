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
        $sellerEmail = $connection->real_escape_string($sellerEmail);
        $productName = $connection->real_escape_string($productName);
        
        $sql = "SELECT * FROM products WHERE seller_email = '" . $sellerEmail . "' AND product_name = '" . $productName . "'";
        $result = $connection->query($sql);
        
        return $result;
    }

    function insertProduct($connection, $sellerEmail, $productName, $category, $price, $quantity, $photoPath) {
        $sellerEmail = $connection->real_escape_string($sellerEmail);
        $productName = $connection->real_escape_string($productName);
        $category = $connection->real_escape_string($category);
        $price = $connection->real_escape_string($price);
        $quantity = $connection->real_escape_string($quantity);
        $photoPath = $connection->real_escape_string($photoPath);
        
        $sql = "INSERT INTO products (seller_email, product_name, product_category, product_price, product_quantity, product_photo) 
                VALUES ('" . $sellerEmail . "', '" . $productName . "', '" . $category . "', '" . $price . "', '" . $quantity . "', '" . $photoPath . "')";
        
        if ($connection->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }
}
?>