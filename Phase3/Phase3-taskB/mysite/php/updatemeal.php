<?php
require_once 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mealID = $_POST['mealid'] ?? null;
    $quantity = $_POST['quantity'] ?? null;

    if (!$mealID || !$quantity) {
        echo json_encode(['error' => 'Missing meal ID or quantity.']);
        exit;
    }

    try {
        // First get the current meal information
        $getMealStmt = $pdo->prepare("SELECT * FROM Meal WHERE Meal_ID = ?");
        $getMealStmt->execute([$mealID]);
        $meal = $getMealStmt->fetch(PDO::FETCH_ASSOC);

        if (!$meal) {
            echo json_encode(['error' => "No meal found with ID #{$mealID}."]);
            exit;
        }

        // Update the meal quantity 
        $updateStmt = $pdo->prepare("UPDATE Meal SET Quantity = ? WHERE Meal_ID = ?");
        $updateStmt->execute([$quantity, $mealID]);

        if ($updateStmt->rowCount() > 0) {
            echo json_encode([
                'success' => true,
                'message' => "Meal #{$mealID} quantity updated to {$quantity}.",
                'meal' => $meal
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => "Meal #{$mealID} quantity was already set to {$quantity}.",
                'meal' => $meal
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}
?>