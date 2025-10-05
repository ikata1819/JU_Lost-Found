<?php
session_start();
include 'db_config.php';

if ($_POST) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        header("Location: ../home.php");  // Updated to .php
        exit();
    } else {
        header("Location: ../login.html?error=Invalid email or password.");
        exit();
    }
} else {
    header("Location: ../login.html");
    exit();
}
?>