<?php
session_start();
header('Content-Type: application/json');
include 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

$user_id = $_SESSION['user_id'];
try {
    $stmt = $pdo->prepare('SELECT id, name, email, type, dept, user_id, usertype, created_at FROM users WHERE id = ?');
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        // you may sanitize or adjust fields here
        echo json_encode(['success' => true, 'user' => $user]);
        exit();
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit();
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit();
}

?>
