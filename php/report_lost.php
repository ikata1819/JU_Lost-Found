<?php
session_start();
require 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$item_name = $_POST['item_name'];
$description = $_POST['description'];
$location = $_POST['last_seen_location'];
$lost_date = $_POST['lost_date'];

$image_url = NULL;
if (!empty($_FILES['image']['name'])) {
    $target_dir = "../uploads/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
    $target_file = $target_dir . time() . "_" . basename($_FILES["image"]["name"]);
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $image_url = $target_file;
    }
}

try {
    $sql = "INSERT INTO lost_items (user_id, item_name, description, last_seen_location, lost_date, image_url) 
            VALUES (:user_id, :item_name, :description, :location, :lost_date, :image_url)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':user_id' => $user_id,
        ':item_name' => $item_name,
        ':description' => $description,
        ':location' => $location,
        ':lost_date' => $lost_date,
        ':image_url' => $image_url
    ]);

    header("Location: ../home.php?msg=Lost+Item+Reported");
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
