<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cartJson = $_POST['cart'] ?? null;
    $orderType = $_POST['orderType'] ?? 'ToGo'; // Default ToGo if not specified

    if (!$cartJson) {
        echo "Missing cart data.";
        exit;
    }

    $cart = json_decode($cartJson, true);

    if (!is_array($cart) || count($cart) === 0) {
        echo "Cart is empty.";
        exit;
    }

    try {
        // Calculate total price
        $totalPrice = 0;

        foreach ($cart as $item) {
            $mealId = $item['mealId'];
            $quantity = $item['quantity'];

            // Get meal price from database
            $mealQuery = "SELECT Price FROM Meal WHERE Meal_ID = ?";
            $mealStmt = $pdo->prepare($mealQuery);
            $mealStmt->execute([$mealId]);
            $meal = $mealStmt->fetch(PDO::FETCH_ASSOC);

            if (!$meal) {
                echo "Error: Meal ID $mealId not found.";
                exit;
            }

            $mealPrice = $meal['Price'];
            $totalPrice += ($mealPrice * $quantity);
        }

        // Begin transaction
        $pdo->beginTransaction();

        // Insert into Orders table
        $orderSql = "INSERT INTO Orders (OrderType, Server, OrderStatus, TotalPrice, Time) 
                     VALUES (?, ?, 'Pending', ?, NOW())";
        $orderStmt = $pdo->prepare($orderSql);

        $serverId = rand(1001, 1020); // Random server

        $orderStmt->execute([$orderType, $serverId, $totalPrice]);
        $orderId = $pdo->lastInsertId();

        // Insert into OrderDetail for each meal
        $detailSql = "INSERT INTO OrderDetail (Order_ID, Meal_ID, Quantity) VALUES (?, ?, ?)";
        $detailStmt = $pdo->prepare($detailSql);

        foreach ($cart as $item) {
            $mealId = $item['mealId'];
            $quantity = $item['quantity'];
            $detailStmt->execute([$orderId, $mealId, $quantity]);
        }

        // Commit transaction
        $pdo->commit();

        echo "<h3>Order Placed Successfully!</h3>";
        echo "Your Order ID: " . htmlspecialchars($orderId) . "<br>";
        echo "Order Type: " . htmlspecialchars($orderType) . "<br>";
        echo "Your order is currently <b>Pending</b>.<br>";
        echo "Total Price: $" . number_format($totalPrice, 2) . "<br>";

        if ($orderType == 'DineIn') {
            echo "<b>Note:</b> A table will be assigned when you arrive at the restaurant.<br>";
        }

        echo "You can track your order using the Order ID above.";

    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Error placing order: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
?>
