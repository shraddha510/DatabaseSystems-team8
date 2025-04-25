<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderID = $_POST['orderid'] ?? null;
    $status = $_POST['status'] ?? null;

    if (!$orderID || !$status) {
        exit("Missing order ID or status.");
    }

    $validStatuses = ['Pending', 'Cancelled', 'Completed'];
    if (!in_array($status, $validStatuses)) {
        exit("⚠️ Invalid status value.");
    }

    $stmt = $pdo->prepare("UPDATE Orders SET OrderStatus = ? WHERE Order_ID = ?");
    $stmt->execute([$status, $orderID]);

    if ($stmt->rowCount() > 0) {
        echo "✅ Order #$orderID updated to status $status.";
    } else {
        echo "⚠️ No order found with ID #$orderID.";
    }
} else {
    echo "Invalid request.";
}
?>
