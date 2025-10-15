<?php
require __DIR__ . '/db_config.php'; // ✅ safer path
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lost_id = isset($_POST['lost_id']) ? intval($_POST['lost_id']) : 0;
    $found_id = isset($_POST['found_id']) ? intval($_POST['found_id']) : 0;

    if ($lost_id > 0 && $found_id > 0) {
        try {
            $pdo->beginTransaction();

            $stmt1 = $pdo->prepare("UPDATE lost_items SET is_active = 0 WHERE lost_id = ?");
            $stmt1->execute([$lost_id]);

            $stmt2 = $pdo->prepare("UPDATE found_items SET is_active = 0 WHERE found_id = ?");
            $stmt2->execute([$found_id]);

            // optional: record match
            /*
            $stmt3 = $pdo->prepare("INSERT INTO matched_items (lost_id, found_id, matched_date)
                                    VALUES (?, ?, NOW())");
            $stmt3->execute([$lost_id, $found_id]);
            */

            $pdo->commit();
            echo json_encode(['success' => true, 'message' => 'Records updated successfully']);
        } catch (Exception $e) {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid IDs']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
