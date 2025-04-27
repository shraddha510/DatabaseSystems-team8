<?php
require_once 'db.php';

if (isset($_POST['newMealID'], $_POST['newMealName'], $_POST['newDescription'], $_POST['newPrice'])) {
    $mealID = $_POST['newMealID'];
    $mealName = $_POST['newMealName'];
    $description = $_POST['newDescription'];
    $price = $_POST['newPrice'];

    try {
        $sql = "INSERT INTO Meal (Meal_ID, Name, Description, Price) VALUES (:mealID, :name, :description, :price)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':mealID', $mealID, PDO::PARAM_INT);
        $stmt->bindParam(':name', $mealName);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);

        if ($stmt->execute()) {
            echo "<h3>Meal Added Successfully!</h3><br>";
            echo "<strong>Added Meal Details:</strong><br>";
            echo "Meal ID: " . htmlspecialchars($mealID) . "<br>";
            echo "Name: " . htmlspecialchars($mealName) . "<br>";
            echo "Description: " . htmlspecialchars($description) . "<br>";
            echo "Price: $" . htmlspecialchars($price) . "<br>";
        } else {
            echo "Failed to add meal.";
        }
    } catch (PDOException $e) {
        // Check if the error code is for duplicate entry (1062)
        if ($e->errorInfo[1] == 1062) {
            echo "Error: A meal with ID " . htmlspecialchars($mealID) . " already exists.";
        } else {
            echo "Error adding meal: " . $e->getMessage();
        }
    }
} else {
    echo "Missing required form data.";
}
?>
