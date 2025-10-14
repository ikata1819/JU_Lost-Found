<?php
session_start();
include 'php/db_config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare('SELECT id, name, email, type, dept, user_id, usertype, created_at FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// fetch lost and found items by this user
$stmtLost = $pdo->prepare('SELECT lost_id AS id, item_name, description, last_seen_location AS location, lost_date AS date, image_url, created_at, "lost" AS category FROM lost_items WHERE user_id = ? ORDER BY created_at DESC');
$stmtLost->execute([$user_id]);
$lostItems = $stmtLost->fetchAll();

$stmtFound = $pdo->prepare('SELECT found_id AS id, item_name, description, found_location AS location, found_date AS date, image_url, created_at, "found" AS category FROM found_items WHERE user_id = ? ORDER BY created_at DESC');
$stmtFound->execute([$user_id]);
$foundItems = $stmtFound->fetchAll();

$allItems = array_merge($lostItems, $foundItems);
// Sort descending by created_at if present, otherwise by date field
usort($allItems, function($a, $b) {
    $aTime = !empty($a['created_at']) ? strtotime($a['created_at']) : (!empty($a['date']) ? strtotime($a['date']) : 0);
    $bTime = !empty($b['created_at']) ? strtotime($b['created_at']) : (!empty($b['date']) ? strtotime($b['date']) : 0);
    return $bTime <=> $aTime;
});

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile - <?= htmlspecialchars($user['name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/lostfound.css" rel="stylesheet">
    <link href="css/profile.css" rel="stylesheet">
    <style> .item-card { border-left: 4px solid var(--accent); } </style>
</head>
<body>
<?php include __DIR__ . '/partials/header.php'; ?>

<main class="container mt-4">
    <div class="d-flex align-items-center gap-3 mb-3">
        <div class="profile-avatar" style="width:72px;height:72px;font-size:1.6rem;"><?= strtoupper(substr($user['name'],0,1)); ?></div>
        <div>
            <h2 class="mb-0"><?= htmlspecialchars($user['name']); ?></h2>
            <div class="text-muted"><?= htmlspecialchars($user['email']); ?></div>
            <div class="text-muted small">Joined: <?= htmlspecialchars($user['created_at']); ?></div>
        </div>
    </div>

    <h3 class="mt-4">Your Reports</h3>
    <?php if (count($allItems) === 0): ?>
        <div class="alert alert-info">You haven't reported any items yet.</div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($allItems as $it): ?>
                <div class="col-md-6">
                    <div class="card item-card p-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="mb-1"><?= htmlspecialchars($it['item_name']); ?> <small class="text-muted">(<?= htmlspecialchars(strtoupper($it['category'])); ?>)</small></h5>
                                <div class="text-muted small mb-1"><?= nl2br(htmlspecialchars($it['description'])); ?></div>
                                <div class="text-muted small mt-1">Location: <?= htmlspecialchars($it['location']); ?> • Date: <?= htmlspecialchars($it['date']); ?></div>
                            </div>
                            <?php if (!empty($it['image_url'])): ?>
                                <?php
                                    $img = $it['image_url'];
                                    // normalize common relative path patterns like ../uploads/... to uploads/...
                                    $img = preg_replace('#^(?:\.\./)+#', '', $img);
                                    $imgPath = $img;
                                ?>
                                <div style="width:92px;height:72px;flex-shrink:0;">
                                    <img src="<?= htmlspecialchars($imgPath); ?>" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:8px;" />
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<footer class="text-center mt-5 py-3 text-white" style="background-color: #082E33;">
    <p class="mb-0">&copy; 2025 Jahangirnagar University. All rights reserved.</p>
</footer>

</body>
</html>
