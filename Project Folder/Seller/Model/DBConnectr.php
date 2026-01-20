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
            $sql = "SELECT * FROM seller WHERE Email = ?";
      $stmt = $connection->prepare($sql);
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $result = $stmt->get_result();
    
      return $result;
   }
   function updateSellerName($connection, $email, $name) {
    $sql = "UPDATE seller SET Name = ? WHERE Email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $name, $email);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function updateSellerNID($connection, $email, $nid) {
    $sql = "UPDATE seller SET NID = ? WHERE Email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $nid, $email);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function updateSellerEmail($connection, $oldEmail, $newEmail) {
    $sql = "UPDATE seller SET Email = ? WHERE Email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $newEmail, $oldEmail);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function updateSellerPhone($connection, $email, $phone) {
    $sql = "UPDATE seller SET Phone_Number = ? WHERE Email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $phone, $email);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function updateSellerPhoto($connection, $email, $photoPath) {
    $sql = "UPDATE seller SET Photo = ? WHERE Email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $photoPath, $email);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}


function getAllProducts($connection) {
    $sql = "SELECT * FROM products ORDER BY created_at DESC";
    $stmt = $connection->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

// Update product quantity (without seller check)
function updateProductQuantityAll($connection, $productId, $newQuantity) {
    $sql = "UPDATE products SET product_quantity = ? WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ii", $newQuantity, $productId);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

// Update product price (without seller check)
function updateProductPriceAll($connection, $productId, $newPrice) {
    $sql = "UPDATE products SET product_price = ? WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("di", $newPrice, $productId);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

// Update product photo (without seller check)
function updateProductPhotoAll($connection, $productId, $newPhotoPath) {
    $sql = "UPDATE products SET product_photo = ? WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("si", $newPhotoPath, $productId);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

// Get product by ID only (without seller check)
function getProductByIdOnly($connection, $productId) {
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

// Delete product (without seller check)
function deleteProductAll($connection, $productId) {
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $productId);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
}
?>