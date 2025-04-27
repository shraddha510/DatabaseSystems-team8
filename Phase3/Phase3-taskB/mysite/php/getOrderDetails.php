<?php
require_once 'db.php';

// Set the content type to JSON
header('Content-Type: application/json');

if (isset($_GET['orderid'])) {
    $orderID = $_GET['orderid'];
    
    try {
        // Prepare and execute the SQL query
        $stmt = $pdo->prepare("SELECT * FROM Orders WHERE Order_ID = ?");
        $stmt->execute([$orderID]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($order) {
            // Return the order data as JSON
            echo json_encode($order);
        } else {
            echo json_encode(['error' => "Order not found with ID #{$orderID}"]);
        }
    } catch (PDOException $e) {
        // Handle database errors
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    // Return an error if no order ID is provided
    http_response_code(400);
    echo json_encode(['error' => 'Order ID is required']);
}
?>