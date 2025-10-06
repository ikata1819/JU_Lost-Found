<?php
session_start();
require 'db_config.php'; // Uses $pdo

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$item_name = $_POST['item_name'];
$description = $_POST['description'];
$found_location = $_POST['found_location'];
$collection_point = $_POST['collection_point'];
$found_date = $_POST['found_date'];

$image_url = NULL;
if (!empty($_FILES['image']['name'])) {
    $target_dir = "../uploads/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
    $target_file = $target_dir . time() . "_" . basename($_FILES["image"]["name"]);

    // ✅ Validate file type for safety
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    if (in_array($_FILES["image"]["type"], $allowed_types)) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_url = $target_file;
        }
    }
}

try {
    $sql = "INSERT INTO found_items (user_id, item_name, description, found_location, collection_point, found_date, image_url)
            VALUES (:user_id, :item_name, :description, :found_location, :collection_point, :found_date, :image_url)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':user_id' => $user_id,
        ':item_name' => $item_name,
        ':description' => $description,
        ':found_location' => $found_location,
        ':collection_point' => $collection_point,
        ':found_date' => $found_date,
        ':image_url' => $image_url
    ]);

    header("Location: ../home.php?msg=Found+Item+Reported");
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
