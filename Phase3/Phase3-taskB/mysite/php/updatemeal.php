<?php
require_once 'db.php';

if (isset($_POST['updateMealID'], $_POST['updateMealName'], $_POST['updateDescription'], $_POST['updatePrice'])) {
    $mealID = $_POST['updateMealID'];
    $mealName = $_POST['updateMealName'];
    $description = $_POST['updateDescription'];
    $price = $_POST['updatePrice'];

    try {
        // Step 1: Fetch the old values before updating
        $fetchSql = "SELECT * FROM Meal WHERE Meal_ID = :mealID";
        $fetchStmt = $pdo->prepare($fetchSql);
        $fetchStmt->bindParam(':mealID', $mealID, PDO::PARAM_INT);
        $fetchStmt->execute();
        $oldMeal = $fetchStmt->fetch(PDO::FETCH_ASSOC);

        if (!$oldMeal) {
            echo "Error: Meal with ID " . htmlspecialchars($mealID) . " not found.";
            exit;
        }

        // Step 2: Update the meal
        $updateSql = "UPDATE Meal 
                      SET Name = :name, Description = :description, Price = :price 
                      WHERE Meal_ID = :mealID";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->bindParam(':name', $mealName);
        $updateStmt->bindParam(':description', $description);
        $updateStmt->bindParam(':price', $price);
        $updateStmt->bindParam(':mealID', $mealID, PDO::PARAM_INT);

        if ($updateStmt->execute()) {
            echo "<h3>Meal Updated Successfully!</h3><br>";
            echo "<strong>Before Update:</strong><br>";
            echo "Meal ID: " . htmlspecialchars($oldMeal['Meal_ID']) . "<br>";
            echo "Name: " . htmlspecialchars($oldMeal['Name']) . "<br>";
            echo "Description: " . htmlspecialchars($oldMeal['Description']) . "<br>";
            echo "Price: $" . htmlspecialchars($oldMeal['Price']) . "<br><br>";
            echo "<strong>After Update:</strong><br>";
            echo "Meal ID: " . htmlspecialchars($mealID) . "<br>";
            echo "Name: " . htmlspecialchars($mealName) . "<br>";
            echo "Description: " . htmlspecialchars($description) . "<br>";
            echo "Price: $" . htmlspecialchars($price) . "<br>";
        } else {
            echo "Failed to update meal.";
        }

    } catch (PDOException $e) {
        echo "Error updating meal: " . $e->getMessage();
    }
} else {
    echo "Missing required form data.";
}
?>
