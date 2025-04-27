<?php
require_once 'db.php';

header('Content-Type: application/json');

try {
    $sql = "SELECT Meal_ID, Name, Price FROM Meal ORDER BY Meal_ID";
    $stmt = $pdo->query($sql);
    $meals = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($meals);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>