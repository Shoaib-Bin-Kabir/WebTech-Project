<?php

class DBConnectr {
    
    // Open connection to database
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
    
    // Close connection
    function closeConnection($connection) {
        $connection->close();
    }
    
    // Get customer info by email
    function getCustomerByEmail($connection, $email) {
        $email = $connection->real_escape_string($email);
        
        $sql = "SELECT c.*, l.ID as LoginID FROM Customer c 
                INNER JOIN Login l ON c.ID = l.ID 
                WHERE l.email = '" . $email . "'";
        $result = $connection->query($sql);
        
        return $result;
    }
    
    // Update customer info
    function updateCustomer($connection, $id, $name, $phone, $nid) {
        $id = $connection->real_escape_string($id);
        $name = $connection->real_escape_string($name);
        $phone = $connection->real_escape_string($phone);
        $nid = $connection->real_escape_string($nid);
        
        $sql = "UPDATE Customer SET Name = '" . $name . "', Phone_Number = '" . $phone . "', NID = '" . $nid . "' WHERE ID = '" . $id . "'";
        $result = $connection->query($sql);
        
        return $result;
    }
    
    // Update customer photo
    function updateCustomerPhoto($connection, $id, $photoPath) {
        $id = $connection->real_escape_string($id);
        $photoPath = $connection->real_escape_string($photoPath);
        
        $sql = "UPDATE Customer SET Photo = '" . $photoPath . "' WHERE ID = '" . $id . "'";
        $result = $connection->query($sql);
        
        return $result;
    }
    
    // Create customer record
    function createCustomerRecord($connection, $loginID, $email, $password) {
        $loginID = $connection->real_escape_string($loginID);
        $email = $connection->real_escape_string($email);
        $password = $connection->real_escape_string($password);
        
        $sql = "INSERT INTO Customer (ID, Email, Password) VALUES ('" . $loginID . "', '" . $email . "', '" . $password . "')";
        $result = $connection->query($sql);
        
        return $result;
    }

    // Get products for customer dashboard (optionally by category)
    function getAllProducts($connection, $category = '') {
        if ($category === '' || $category === null) {
            $sql = "SELECT product_name, product_category, product_price, product_quantity, product_photo FROM products";
            return $connection->query($sql);
        }

        $category = $connection->real_escape_string($category);
        $sql = "SELECT product_name, product_category, product_price, product_quantity, product_photo FROM products WHERE product_category = '" . $category . "'";
        return $connection->query($sql);
    }

    // Search only by product name
    function searchProducts($connection, $q = '') {
        $q = ($q === null) ? '' : trim($q);

        if ($q === '') {
            $sql = "SELECT product_name, product_category, product_price, product_quantity, product_photo FROM products";
            return $connection->query($sql);
        }

        $q = $connection->real_escape_string($q);
        $sql = "SELECT product_name, product_category, product_price, product_quantity, product_photo FROM products WHERE product_name LIKE '" . $q . "%'";
        return $connection->query($sql);
    }
}

?>
