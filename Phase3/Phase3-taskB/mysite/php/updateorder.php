<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderID = $_POST['orderid'] ?? null;
    $status = $_POST['status'] ?? null;

    if (!$orderID || !$status) {
        exit("<div style='text-align: center; padding-top: 50px;'>Missing order ID or status.</div>");
    }

    $validStatuses = ['Pending', 'Cancelled', 'Completed'];
    if (!in_array($status, $validStatuses)) {
        exit("<div style='text-align: center; padding-top: 50px;'>⚠️ Invalid status value.</div>");
    }

    // Get the order details before updating
    $getOrderStmt = $pdo->prepare("SELECT * FROM Orders WHERE Order_ID = ?");
    $getOrderStmt->execute([$orderID]);
    $order = $getOrderStmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        exit("<div style='text-align: center; padding-top: 50px;'>⚠️ No order found with ID #{$orderID}.</div>");
    }

    $oldStatus = $order['OrderStatus'];

    // Update the order
    $stmt = $pdo->prepare("UPDATE Orders SET OrderStatus = ? WHERE Order_ID = ?");
    $stmt->execute([$status, $orderID]);

    // Output only the simple clean result without full HTML page structure
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Order Updated</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background-color: #343a40;
                color: white;
                text-align: center;
                padding: 20px;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
            }
            .btn-primary {
                background-color: #0d6efd;
                border-color: #0d6efd;
                padding: 0.375rem 0.75rem;
                border-radius: 0.25rem;
                color: white;
                text-decoration: none;
                display: inline-block;
                margin-top: 15px;
            }
            .footer {
                margin-top: 30px;
                color: rgba(255, 255, 255, 0.5);
            }
        </style>
    </head>
    <body>
        <div class="container">
            <?php if ($stmt->rowCount() > 0): ?>
            <h1>Order Updated Successfully!</h1>
            <p>Order #<?php echo htmlspecialchars($orderID); ?> status updated from <?php echo htmlspecialchars($oldStatus); ?> to <?php echo htmlspecialchars($status); ?>.</p>
            
            <h2>Order Details:</h2>
            <p>Order Type: <?php echo htmlspecialchars($order['OrderType']); ?></p>
            <p>Total Price: $<?php echo htmlspecialchars(number_format($order['TotalPrice'], 2)); ?></p>
            <p>Order Time: <?php echo htmlspecialchars($order['Time']); ?></p>
            <?php else: ?>
            <h1>No Changes Made</h1>
            <p>Order #<?php echo htmlspecialchars($orderID); ?> was already set to status <?php echo htmlspecialchars($status); ?>.</p>
            <?php endif; ?>
            
            <a href="../htmlFiles/staff.html" class="btn btn-primary">Update Another Order</a>
            
            <div class="footer">
                <p>We hope you enjoy our food!</p>
            </div>
        </div>
    </body>
    </html>
    <?php
} else {
    // Handle non-POST requests with simple error message
    echo "<div style='text-align: center; padding-top: 50px; background-color: #343a40; color: white;'>";
    echo "<h1>Error</h1>";
    echo "<p>Invalid request method.</p>";
    echo "<a href='../htmlFiles/staff.html' style='color: #0d6efd;'>Go Back</a>";
    echo "</div>";
}
?>