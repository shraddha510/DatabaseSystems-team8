<?php
require_once 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mealID = $_POST['updateMealID'] ?? null;
    $mealName = $_POST['updateMealName'] ?? null;
    $description = $_POST['updateDescription'] ?? null;
    $price = $_POST['updatePrice'] ?? null;

    // Check if all fields are provided
    if (!$mealID || !$mealName || !$description || !$price) {
        echo json_encode(['error' => 'Missing one or more required fields.']);
        exit;
    }

    try {
        // First, check if the meal exists
        $getMealStmt = $pdo->prepare("SELECT * FROM Meal WHERE Meal_ID = ?");
        $getMealStmt->execute([$mealID]);
        $meal = $getMealStmt->fetch(PDO::FETCH_ASSOC);

        if (!$meal) {
            echo json_encode(['error' => "No meal found with ID #{$mealID}."]);
            exit;
        }

        // Update the meal information
        $updateStmt = $pdo->prepare("
            UPDATE Meal 
            SET Name = ?, Description = ?, Price = ?
            WHERE Meal_ID = ?
        ");
        $updateStmt->execute([$mealName, $description, $price, $mealID]);

        if ($updateStmt->rowCount() > 0) {
            echo json_encode([
                'success' => true,
                'message' => "Meal #{$mealID} updated successfully.",
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => "No changes made to Meal #{$mealID}.",
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}
?>
