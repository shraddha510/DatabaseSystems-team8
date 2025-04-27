<?php
require_once 'db.php';

if (isset($_GET['orderid'])) {
    $orderId = $_GET['orderid'];

    try {
        // Query to get order details
        $orderSql = "SELECT o.Order_ID, o.OrderType, o.Server, o.OrderStatus, o.TotalPrice, o.Time 
                     FROM Orders o 
                     WHERE o.Order_ID = :orderId";
        $orderStmt = $pdo->prepare($orderSql);
        $orderStmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
        $orderStmt->execute();
        $order = $orderStmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            echo "<div class='alert alert-warning'><strong>Warning!</strong> No order found with ID #{$orderId}.</div>";
        } else {
            // Get order items
            $itemsSql = "SELECT m.Name, m.Price, od.Quantity, (m.Price * od.Quantity) as ItemTotal
                         FROM OrderDetail od
                         JOIN Meal m ON od.Meal_ID = m.Meal_ID
                         WHERE od.Order_ID = :orderId";
            $itemsStmt = $pdo->prepare($itemsSql);
            $itemsStmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
            $itemsStmt->execute();
            $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

            // Display order details
            echo "<h3>Order #" . htmlspecialchars($orderId) . " Details</h3>";
            echo "<div class='order-status'>";
            echo "<p><strong>Status:</strong> <span class='status-" . strtolower(htmlspecialchars($order['OrderStatus'])) . "'>" . htmlspecialchars($order['OrderStatus']) . "</span></p>";
            echo "<p><strong>Order Type:</strong> " . htmlspecialchars($order['OrderType']) . "</p>";
            echo "<p><strong>Order Time:</strong> " . htmlspecialchars($order['Time']) . "</p>";
            echo "</div>";

            // Display order items
            echo "<h4>Order Items</h4>";
            echo "<table class='table'>";
            echo "<thead><tr><th>Item</th><th>Price</th><th>Quantity</th><th>Total</th></tr></thead>";
            echo "<tbody>";
            
            foreach ($items as $item) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($item['Name']) . "</td>";
                echo "<td>$" . htmlspecialchars(number_format($item['Price'], 2)) . "</td>";
                echo "<td>" . htmlspecialchars($item['Quantity']) . "</td>";
                echo "<td>$" . htmlspecialchars(number_format($item['ItemTotal'], 2)) . "</td>";
                echo "</tr>";
            }
            
            echo "</tbody>";
            echo "</table>";
            
            echo "<div class='order-total'>";
            echo "<p><strong>Total Price:</strong> $" . htmlspecialchars(number_format($order['TotalPrice'], 2)) . "</p>";
            echo "</div>";

            // Show appropriate message based on status
            echo "<div class='order-message'>";
            switch ($order['OrderStatus']) {
                case 'Pending':
                    echo "<p>Your order is being prepared. Please wait...</p>";
                    break;
                case 'Completed':
                    echo "<p>Your order has been completed! Thank you for your business.</p>";
                    break;
                case 'Cancelled':
                    echo "<p>This order has been cancelled.</p>";
                    break;
                default:
                    echo "<p>Order status is being updated.</p>";
            }
            echo "</div>";
        }
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'><strong>Error!</strong> " . $e->getMessage() . "</div>";
    }
} else {
    echo "<div class='alert alert-warning'><strong>Warning!</strong> Please provide an order ID.</div>";
}
?>