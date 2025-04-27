<?php
require_once 'db.php';

if (isset($_POST['removeMealID'])) {
    $mealID = $_POST['removeMealID'];

    try {
        // Step 1: Fetch the meal details before deleting
        $fetchSql = "SELECT * FROM Meal WHERE Meal_ID = :mealID";
        $fetchStmt = $pdo->prepare($fetchSql);
        $fetchStmt->bindParam(':mealID', $mealID, PDO::PARAM_INT);
        $fetchStmt->execute();
        $meal = $fetchStmt->fetch(PDO::FETCH_ASSOC);

        if (!$meal) {
            echo "Error: No meal found with ID " . htmlspecialchars($mealID) . ".";
            exit;
        }

        // Step 2: Now delete the meal
        $deleteSql = "DELETE FROM Meal WHERE Meal_ID = :mealID";
        $deleteStmt = $pdo->prepare($deleteSql);
        $deleteStmt->bindParam(':mealID', $mealID, PDO::PARAM_INT);

        if ($deleteStmt->execute()) {
            echo "<h3>Meal Removed Successfully!</h3><br>";
            echo "<strong>Removed Meal Details:</strong><br>";
            echo "Meal ID: " . htmlspecialchars($meal['Meal_ID']) . "<br>";
            echo "Name: " . htmlspecialchars($meal['Name']) . "<br>";
            echo "Description: " . htmlspecialchars($meal['Description']) . "<br>";
            echo "Price: $" . htmlspecialchars($meal['Price']) . "<br>";
        } else {
            echo "Failed to remove meal.";
        }
    } catch (PDOException $e) {
        echo "Error removing meal: " . $e->getMessage();
    }
} else {
    echo "Missing Meal ID.";
}
?>