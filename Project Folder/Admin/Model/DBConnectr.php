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
        $sql = "INSERT INTO seller (ID,Email, Password) VALUES (?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("iss", $id, $email, $password);
        $result = $stmt->execute();
        return $result;
    }
    
    
function getAdminByEmail($connection, $email) {
    $email = $connection->real_escape_string($email);
    $sql = "SELECT * FROM admin WHERE Email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result;
}

function updateAdminName($connection, $email, $name) {
    $sql = "UPDATE admin SET Name = ? WHERE Email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $name, $email);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function updateAdminNID($connection, $email, $nid) {
    $sql = "UPDATE admin SET NID = ? WHERE Email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $nid, $email);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function updateAdminEmail($connection, $oldEmail, $newEmail) {
    $sql = "UPDATE admin SET Email = ? WHERE Email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $newEmail, $oldEmail);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function updateAdminPhone($connection, $email, $phone) {
    $sql = "UPDATE admin SET Phone_Number = ? WHERE Email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $phone, $email);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function updateAdminPhoto($connection, $email, $photoPath) {
    $sql = "UPDATE admin SET Photo = ? WHERE Email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $photoPath, $email);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function checkProductNameExists($connection, $productName) {
    $sql = "SELECT * FROM products WHERE product_name = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $productName);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

// Insert product (admin adds to shop inventory)
function insertProduct($connection, $adminEmail, $productName, $category, $price, $quantity, $photoPath) {
    $sql = "INSERT INTO products (seller_email, product_name, product_category, product_price, product_quantity, product_photo) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sssdis", $adminEmail, $productName, $category, $price, $quantity, $photoPath);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function getAllProducts($connection) {
    $sql = "SELECT * FROM products ORDER BY created_at DESC";
    $result = $connection->query($sql);
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

function updateProductQuantityAll($connection, $productId, $newQuantity) {
    $sql = "UPDATE products SET product_quantity = ? WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ii", $newQuantity, $productId);
    $result = $stmt->execute();
    return $result;
}

function updateProductPriceAll($connection, $productId, $newPrice) {
    $sql = "UPDATE products SET product_price = ? WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("di", $newPrice, $productId);
    $result = $stmt->execute();
    return $result;
}

function updateProductPhotoAll($connection, $productId, $photoPath) {
    $sql = "UPDATE products SET product_photo = ? WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("si", $photoPath, $productId);
    $result = $stmt->execute();
    return $result;
}

function deleteProductAll($connection, $productId) {
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $productId);
    $result = $stmt->execute();
    return $result;
}

function getAllSellers($connection) {
    $sql = "SELECT * FROM seller ORDER BY ID ASC";
    $result = $connection->query($sql);
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

function deleteSellerFromLogin($connection, $email) {
    $sql = "DELETE FROM Login WHERE email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $email);
    $result = $stmt->execute();
    return $result;
}

function deleteSellerFromSeller($connection, $email) {
    $sql = "DELETE FROM seller WHERE Email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $email);
    $result = $stmt->execute();
    return $result;
}

}

?>