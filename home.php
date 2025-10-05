<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - JU Lost & Found</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="logo">
            <h2 style="color: var(--primary); margin: 0; font-weight: 700;">🛡 JU Lost & Found</h2>
            <small style="color: var(--secondary);">Jahangirnagar University</small>
        </div>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="php/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1 id="welcome">Welcome! (Loading...)</h1>
        <div class="dashboard-section">
            <h2>Report Lost Item</h2>
            <p>Describe your lost item here. (Form placeholder)</p>
            <form style="max-width: 400px;">
                <textarea placeholder="Description..."></textarea>
                <input type="file" name="image">
                <button type="submit" class="btn">Report Lost</button>
            </form>
        </div>
        <div class="dashboard-section">
            <h2>Report Found Item</h2>
            <p>Share details of found item.</p>
            <form style="max-width: 400px;">
                <textarea placeholder="Description..."></textarea>
                <input type="file" name="image">
                <button type="submit" class="btn">Report Found</button>
            </form>
        </div>
        <div class="dashboard-section">
            <h2>Search Items</h2>
            <form class="search-form" style="max-width: 400px;">
                <div class="search-wrapper">
                    <input type="text" name="search" placeholder="Search by keyword, location...">
                    <button type="submit" class="search-btn">🔍 Search</button>
                </div>
            </form>
            <p>Search results will appear here.</p>
        </div>
        <div class="dashboard-section">
            <h2>User Manual</h2>
            <ul>
                <li>Step 1: Register/Login</li>
                <li>Step 2: Report lost/found items</li>
                <li>Step 3: Use search to match</li>
                <li>Step 4: Claim with proof</li>
            </ul>
        </div>
    </main>
    <footer>
        <p>&copy; 2025 Jahangirnagar University. All rights reserved.</p>
    </footer>
</body>
</html>