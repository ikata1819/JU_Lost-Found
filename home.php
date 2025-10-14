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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="./css/lostfound.css" rel="stylesheet">
        <link href="./css/profile.css" rel="stylesheet">
    
    <script src="js/script.js" defer></script>
    <script src="js/lostfound.js" defer></script>

    
</head>

<body>
<?php include __DIR__ . '/partials/header.php'; ?>

<!-- ✅ Popup message (hidden by default) -->
<?php if (isset($_GET['msg'])): ?>
    <div class="popup-msg" id="popup-msg">
        <?= htmlspecialchars($_GET['msg']); ?>
    </div>
<?php endif; ?>

<main class="container text-center">
    <h1 id="welcome" class="mb-4 mt-8 fw-bold" style="color: #082E33;">Welcome! (Loading...)</h1>

    <div class="row g-4" >
        <div class="col-md-5" id="report-lost">
            <div class="card shadow-sm p-4 text-white" style="background-color: #082E33;">
                <h2 class="h4 mb-3 fw-bold">Report Lost Item</h2>
                <form action="php/report_lost.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <input type="text" name="item_name" class="form-control" placeholder="Item Name" required>
                    </div>
                    <div class="mb-3">
                        <textarea name="description" class="form-control" placeholder="Description..." rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="last_seen_location" class="form-control" placeholder="Last Seen Location" required>
                    </div>
                    <div class="mb-3">
                        <input type="date" name="lost_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <input type="file" name="image" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-custom-primary w-100 fw-semibold">Report Lost</button>
                </form>
            </div>
        </div>

        <div class="col-md-5" id="report-found">
            <div class="card shadow-sm p-4 text-white" style="background-color: #4F7C82;">
                <h2 class="h4 mb-3 fw-bold">Report Found Item</h2>
                <form action="php/report_found.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <input type="text" name="item_name" class="form-control" placeholder="Item Name" required>
                    </div>
                    <div class="mb-3">
                        <textarea name="description" class="form-control" placeholder="Description..." rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="found_location" class="form-control" placeholder="Found Location" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="collection_point" class="form-control" placeholder="Collection Point" required>
                    </div>
                    <div class="mb-3">
                        <input type="date" name="found_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <input type="file" name="image" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-custom-success w-100 fw-semibold">Report Found</button>
                </form>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-4" id="search-items">
        <div class="col-md-12">
            <div class="card shadow-sm p-4" style="background-color: #93B1B5; color: #082E33;">
                <h2 class="h4 mb-3 fw-bold">Search Items</h2>
                <form class="row g-2 align-items-center" action="php/search_items.php" method="GET">
                    <div class="col-sm-4">
                        <input type="text" name="item_name" class="form-control" placeholder="Item name">
                    </div>
                    <div class="col-sm-3">
                        <input type="text" name="person_name" class="form-control" placeholder="Person name">
                    </div>
                    <div class="col-sm-3">
                        <input type="text" name="location" class="form-control" placeholder="Location">
                    </div>
                    <div class="col-sm-2 d-flex gap-2">
                        <select name="type" class="form-select">
                            <option value="">Any</option>
                            <option value="lost">Lost</option>
                            <option value="found">Found</option>
                        </select>
                        <button type="submit" class="btn btn-custom-outline fw-semibold">🔍</button>
                    </div>
                </form>
                <p class="mt-3 mb-0" style="color: #082E33; opacity: 0.7;">Search results will open on a separate page.</p>
            </div>
        </div>
    </div>
</main>


<footer class="text-center mt-5 py-3 text-white" style="background-color: #082E33;">
    <p class="mb-0">&copy; 2025 Jahangirnagar University. All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>



</body>
</html>
