<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mealId = $_POST['mealid'] ?? null;
    $quantity = $_POST['quantity'] ?? 1;
    $orderType = $_POST['orderType'] ?? 'ToGo'; // Default to ToGo if not specified

    if (!$mealId) {
        echo "Missing meal ID.";
        exit;
    }

    try {
        // First, get the meal price
        $mealQuery = "SELECT Price, Name FROM Meal WHERE Meal_ID = ?";
        $mealStmt = $pdo->prepare($mealQuery);
        $mealStmt->execute([$mealId]);
        $meal = $mealStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$meal) {
            echo "Error: Meal not found or price is not set.";
            exit;
        }
        
        $mealPrice = $meal['Price'];
        $mealName = $meal['Name'];
        
        // Calculate total price
        $totalPrice = $mealPrice * $quantity;
        
        // Start a transaction
        $pdo->beginTransaction();
        
        // Insert into Orders table with the calculated total price
        $orderSql = "INSERT INTO Orders (OrderType, Server, OrderStatus, TotalPrice, Time) 
                    VALUES (?, ?, 'Pending', ?, NOW())";
        $orderStmt = $pdo->prepare($orderSql);
        
        // Generate a random server ID between 1001-1020 
        $serverId = rand(1001, 1020);
        
        $orderStmt->execute([$orderType, $serverId, $totalPrice]);
        $orderId = $pdo->lastInsertId();
        
        // Insert into OrderDetail table linking the order with the meal
        $detailSql = "INSERT INTO OrderDetail (Order_ID, Meal_ID, Quantity) VALUES (?, ?, ?)";
        $detailStmt = $pdo->prepare($detailSql);
        $detailStmt->execute([$orderId, $mealId, $quantity]);
        
        // Commit the transaction
        $pdo->commit();
        
        echo "<h3>Order Placed Successfully!</h3>";
        echo "Your Order ID: " . htmlspecialchars($orderId) . "<br>";
        echo "Meal: " . htmlspecialchars($mealName) . "<br>";
        echo "Quantity: " . htmlspecialchars($quantity) . "<br>";
        echo "Order Type: " . htmlspecialchars($orderType) . "<br>";
        echo "Your order is currently <b>Pending</b>.<br>";
        echo "Total Price: $" . number_format($totalPrice, 2) . "<br>";
        
        // Add special message for dine-in orders
        if ($orderType == 'DineIn') {
            echo "<b>Note:</b> A table will be assigned when you arrive at the restaurant.<br>";
        }
        
        echo "You can track your order using the Order ID above.";
        
    } catch (PDOException $e) {
        // Roll back the transaction if something failed
        $pdo->rollBack();
        echo "Error placing order: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
?>