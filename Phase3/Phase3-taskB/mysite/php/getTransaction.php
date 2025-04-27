<?php

require_once 'db.php';

if (isset($_POST['orderID']) && isset($_POST['transactionNum'])) {
    $orderID = $_POST['orderID'];
    $transactionNum = $_POST['transactionNum'];

    try {
        $sql = "SELECT TransactionNum, Order_ID, Tax, Tips, Discount, AmountPaid, PaymentMethod, PaymentStatus, TransactionDate
                FROM TransactionInfo 
                WHERE Order_ID = :orderID AND TransactionNum = :transactionNum";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':orderID', $orderID, PDO::PARAM_INT);
        $stmt->bindParam(':transactionNum', $transactionNum, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "<h3>Transaction Details</h3>";
            echo "<table border='1' cellpadding='5'>";
            echo "<tr>";
            echo "<th>Transaction Number</th>";
            echo "<th>Order ID</th>";
            echo "<th>Tax ($)</th>";
            echo "<th>Tips ($)</th>";
            echo "<th>Discount ($)</th>";
            echo "<th>Amount Paid ($)</th>";
            echo "<th>Payment Method</th>";
            echo "<th>Payment Status</th>";
            echo "<th>Transaction Date</th>";
            echo "</tr>";

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['TransactionNum']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Order_ID']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Tax']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Tips']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Discount']) . "</td>";
                echo "<td>" . htmlspecialchars($row['AmountPaid']) . "</td>";
                echo "<td>" . htmlspecialchars($row['PaymentMethod']) . "</td>";
                echo "<td>" . htmlspecialchars($row['PaymentStatus']) . "</td>";
                echo "<td>" . htmlspecialchars($row['TransactionDate']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "Error: Transaction not found. Please check Order ID and Transaction Number.";
        }
    } catch (PDOException $e) {
        echo "<p>Error fetching transaction: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>Missing Order ID or Transaction Number. Please fill out the form correctly.</p>";
}
?>