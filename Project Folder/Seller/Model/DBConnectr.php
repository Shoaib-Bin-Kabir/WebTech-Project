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
    $result1 = $stmt->execute();
    $stmt->close();
    
    $sql2 = "UPDATE Login SET email = ? WHERE email = ?";
    $stmt2 = $connection->prepare($sql2);
    $stmt2->bind_param("ss", $newEmail, $oldEmail);
    $result2 = $stmt2->execute();
    $stmt2->close();
    
    return ($result1 && $result2);
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


function updateProductQuantityAll($connection, $productId, $newQuantity) {
    $sql = "UPDATE products SET product_quantity = ? WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ii", $newQuantity, $productId);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}


function updateProductPriceAll($connection, $productId, $newPrice) {
    $sql = "UPDATE products SET product_price = ? WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("di", $newPrice, $productId);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}


function updateProductPhotoAll($connection, $productId, $newPhotoPath) {
    $sql = "UPDATE products SET product_photo = ? WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("si", $newPhotoPath, $productId);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}


function getProductByIdOnly($connection, $productId) {
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}


function deleteProductAll($connection, $productId) {
    $productId = (int) $productId;

    // Remove from all customer carts first (avoid orphan cart rows).
    $sqlCart = "DELETE FROM customer_cart_items WHERE product_id = ?";
    $stmtCart = $connection->prepare($sqlCart);
    $stmtCart->bind_param("i", $productId);
    $stmtCart->execute();
    $stmtCart->close();

    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $productId);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function insertHistory($connection, $userEmail, $userName, $actionType, $target, $oldValue, $newValue) {
    $sql = "INSERT INTO history (user_email, user_name, action_type, target, old_value, new_value) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ssssss", $userEmail, $userName, $actionType, $target, $oldValue, $newValue);
    $result = $stmt->execute();
    return $result;
}

function getHistoryByEmail($connection, $email) {
    $sql = "SELECT * FROM history WHERE user_email = ? ORDER BY created_at DESC";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function getAllOrders($connection) {
    $sql = "SELECT id, customer_id, product_id, product_name, price, quantity, shipping_address, created_at 
            FROM orders 
            ORDER BY created_at DESC";
    $stmt = $connection->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

}
?>