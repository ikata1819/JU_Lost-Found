<?php
session_start();
include 'db_config.php';

if ($_POST) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $type = $_POST['type'];
    $dept = $_POST['dept'];
    $user_id = $_POST['user_id'];
    $usertype = $_POST['usertype'];

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, type, dept, user_id, usertype) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $password, $type, $dept, $user_id, $usertype]);
        header("Location: ../login.html?msg=Registration successful!");
        exit();
    } catch (PDOException $e) {
        header("Location: ../register.html?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: ../register.html");
    exit();
}
?>