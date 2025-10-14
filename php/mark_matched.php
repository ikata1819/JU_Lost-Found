<?php
require 'db_config.php'; // adjust path if needed
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lost_id = isset($_POST['lost_id']) ? intval($_POST['lost_id']) : 0;
    $found_id = isset($_POST['found_id']) ? intval($_POST['found_id']) : 0;

    if ($lost_id > 0 && $found_id > 0) {
        try {
            // Begin transaction (optional but safe)
            $pdo->beginTransaction();

            // Mark both lost and found reports as inactive
            $stmt1 = $pdo->prepare("UPDATE lost_items SET is_active = 0 WHERE lost_id = ?");
            $stmt1->execute([$lost_id]);

            $stmt2 = $pdo->prepare("UPDATE found_items SET is_active = 0 WHERE found_id = ?");
            $stmt2->execute([$found_id]);

            // Optionally insert into a "matched_items" table
            /*
            $stmt3 = $pdo->prepare("
                INSERT INTO matched_items (lost_id, found_id, matched_date)
                VALUES (?, ?, NOW())
            ");
            $stmt3->execute([$lost_id, $found_id]);
            */

            $pdo->commit();

            echo json_encode(['success' => true]);
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid IDs provided']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}
?>
