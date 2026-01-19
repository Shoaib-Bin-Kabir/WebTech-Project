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
            $sql = "SELECT id, product_name, product_category, product_price, product_quantity, product_photo FROM products";
            return $connection->query($sql);
        }

        $category = $connection->real_escape_string($category);
        $sql = "SELECT id, product_name, product_category, product_price, product_quantity, product_photo FROM products WHERE product_category = '" . $category . "'";
        return $connection->query($sql);
    }

    // Search only by product name
    function searchProducts($connection, $q = '') {
        $q = ($q === null) ? '' : trim($q);

        if ($q === '') {
            $sql = "SELECT id, product_name, product_category, product_price, product_quantity, product_photo FROM products";
            return $connection->query($sql);
        }

        $q = $connection->real_escape_string($q);
        $sql = "SELECT id, product_name, product_category, product_price, product_quantity, product_photo FROM products WHERE product_name LIKE '" . $q . "%'";
        return $connection->query($sql);
    }

    function getProductById($connection, $productId) {
        $productId = (int) $productId;
        $sql = "SELECT id, product_name, product_category, product_price, product_quantity, product_photo FROM products WHERE id = " . $productId;
        return $connection->query($sql);
    }

    function getCartItemCount($connection, $customerId) {
        $customerId = (int) $customerId;
        $sql = "SELECT COALESCE(SUM(quantity), 0) AS cnt FROM customer_cart_items WHERE customer_id = " . $customerId;
        $res = $connection->query($sql);
        if ($res && ($row = $res->fetch_assoc())) {
            return (int) ($row['cnt'] ?? 0);
        }
        return 0;
    }

    function addToCart($connection, $customerId, $productId, $qty = 1) {
        $customerId = (int) $customerId;
        $productId = (int) $productId;
        $qty = (int) $qty;
        if ($customerId <= 0 || $productId <= 0) {
            return false;
        }
        if ($qty <= 0) {
            $qty = 1;
        }

        $checkSql = "SELECT quantity FROM customer_cart_items WHERE customer_id = " . $customerId . " AND product_id = " . $productId;
        $check = $connection->query($checkSql);
        if ($check && $check->num_rows > 0) {
            $row = $check->fetch_assoc();
            $newQty = ((int) ($row['quantity'] ?? 0)) + $qty;
            $updSql = "UPDATE customer_cart_items SET quantity = " . $newQty . " WHERE customer_id = " . $customerId . " AND product_id = " . $productId;
            return $connection->query($updSql);
        }

        $insSql = "INSERT INTO customer_cart_items (customer_id, product_id, quantity) VALUES (" . $customerId . ", " . $productId . ", " . $qty . ")";
        return $connection->query($insSql);
    }

    function getCartItems($connection, $customerId) {
        $customerId = (int) $customerId;
        $sql = "SELECT c.product_id, c.quantity AS cart_quantity, p.id, p.product_name, p.product_category, p.product_price, p.product_quantity, p.product_photo\n" .
            "FROM customer_cart_items c\n" .
            "INNER JOIN products p ON p.id = c.product_id\n" .
            "WHERE c.customer_id = " . $customerId;
        return $connection->query($sql);
    }

    function updateCartItemQuantity($connection, $customerId, $productId, $qty) {
        $customerId = (int) $customerId;
        $productId = (int) $productId;
        $qty = (int) $qty;
        if ($qty <= 0) {
            $delSql = "DELETE FROM customer_cart_items WHERE customer_id = " . $customerId . " AND product_id = " . $productId;
            return $connection->query($delSql);
        }

        $updSql = "UPDATE customer_cart_items SET quantity = " . $qty . " WHERE customer_id = " . $customerId . " AND product_id = " . $productId;
        return $connection->query($updSql);
    }

    function removeCartItem($connection, $customerId, $productId) {
        $customerId = (int) $customerId;
        $productId = (int) $productId;
        $sql = "DELETE FROM customer_cart_items WHERE customer_id = " . $customerId . " AND product_id = " . $productId;
        return $connection->query($sql);
    }

    function clearCart($connection, $customerId) {
        $customerId = (int) $customerId;
        $sql = "DELETE FROM customer_cart_items WHERE customer_id = " . $customerId;
        return $connection->query($sql);
    }

    function createOrderGroup() {
        return uniqid('ORD', true);
    }

    function addOrderItemRow($connection, $orderGroup, $customerId, $productId, $productName, $price, $qty, $shippingAddress) {
        $orderGroup = $connection->real_escape_string((string) $orderGroup);
        $customerId = (int) $customerId;
        $productId = (int) $productId;
        $productName = $connection->real_escape_string((string) $productName);
        $price = (float) $price;
        $qty = (int) $qty;
        $shippingAddress = $connection->real_escape_string((string) $shippingAddress);

        $sql = "INSERT INTO orders (order_group, customer_id, product_id, product_name, price, quantity, shipping_address) VALUES (" .
            "'" . $orderGroup . "', " . $customerId . ", " . $productId . ", '" . $productName . "', " . $price . ", " . $qty . ", '" . $shippingAddress . "')";

        return $connection->query($sql);
    }

    function getOrderGroupsByCustomer($connection, $customerId) {
        $customerId = (int) $customerId;
        $sql = "SELECT order_group, MAX(created_at) AS created_at, MAX(shipping_address) AS shipping_address, SUM(price * quantity) AS total_amount " .
            "FROM orders WHERE customer_id = " . $customerId . " GROUP BY order_group ORDER BY MAX(id) DESC";
        return $connection->query($sql);
    }

    function getOrderItemsByGroup($connection, $orderGroup) {
        $orderGroup = $connection->real_escape_string((string) $orderGroup);
        $sql = "SELECT product_id, product_name, price, quantity FROM orders WHERE order_group = '" . $orderGroup . "' ORDER BY id ASC";
        return $connection->query($sql);
    }
}

?>
