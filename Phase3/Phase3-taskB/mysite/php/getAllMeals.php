<?php
require_once 'db.php';

try {
    $sql = "SELECT Meal_ID, Name, Description, Price FROM Meal";
    $stmt = $pdo->query($sql);

    if ($stmt->rowCount() > 0) {
        echo "<h3>All Meals</h3>";
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr>";
        echo "<th>Meal ID</th>";
        echo "<th>Name</th>";
        echo "<th>Description</th>";
        echo "<th>Price ($)</th>";
        echo "</tr>";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['Meal_ID']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Description']) . "</td>";
            echo "<td>" . htmlspecialchars(number_format($row['Price'], 2)) . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No meals found in the database.";
    }
} catch (PDOException $e) {
    echo "Error retrieving meals: " . $e->getMessage();
}
?>
