<?php
// php/search_items.php
// Accepts GET parameters: item_name, person_name, location, type (lost|found|empty=both)
session_start();
require 'db_config.php';

function escape($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

$item_name = isset($_GET['item_name']) ? trim($_GET['item_name']) : '';
$person_name = isset($_GET['person_name']) ? trim($_GET['person_name']) : '';
$location = isset($_GET['location']) ? trim($_GET['location']) : '';
$type = isset($_GET['type']) ? trim($_GET['type']) : '';

$results = [];

try {
    // Query lost items when type is 'lost' or empty
    if ($type === 'lost' || $type === '') {
        $sql = "SELECT l.lost_id AS id, l.item_name, l.description, l.last_seen_location AS location, l.lost_date AS date, l.image_url, u.name AS reporter, 'lost' AS type
                FROM lost_items l
                LEFT JOIN users u ON l.user_id = u.id
                WHERE l.is_active = 1";

        $params = [];
        if ($item_name !== '') { $sql .= " AND l.item_name LIKE :item_name"; $params[':item_name'] = "%$item_name%"; }
        if ($person_name !== '') { $sql .= " AND u.name LIKE :person_name"; $params[':person_name'] = "%$person_name%"; }
        if ($location !== '') { $sql .= " AND l.last_seen_location LIKE :location"; $params[':location'] = "%$location%"; }

        $sql .= " ORDER BY l.created_at DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $r) $results[] = $r;
    }

    // Query found items when type is 'found' or empty
    if ($type === 'found' || $type === '') {
        $sql = "SELECT f.found_id AS id, f.item_name, f.description, f.found_location AS location, f.found_date AS date, f.image_url, u.name AS reporter, 'found' AS type
                FROM found_items f
                LEFT JOIN users u ON f.user_id = u.id
                WHERE f.is_active = 1";

        $params = [];
        if ($item_name !== '') { $sql .= " AND f.item_name LIKE :item_name"; $params[':item_name'] = "%$item_name%"; }
        if ($person_name !== '') { $sql .= " AND u.name LIKE :person_name"; $params[':person_name'] = "%$person_name%"; }
        if ($location !== '') { $sql .= " AND f.found_location LIKE :location"; $params[':location'] = "%$location%"; }

        $sql .= " ORDER BY f.created_at DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $r) $results[] = $r;
    }

} catch (PDOException $e) {
    $error = $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Search Results - JU Lost & Found</title>
    <link rel="stylesheet" href="../css/lostfound.css">
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style> .result-card { margin-bottom: 1rem; } .item-img{max-width:120px; max-height:90px; object-fit:cover;} </style>
</head>
<body>
    <div class="container py-4">
    <a href="../home.php" class="btn btn-link">&larr; Back</a>
        <h1 class="h4 mb-3">Search Results</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">Error: <?= escape($error) ?></div>
        <?php endif; ?>

        <div class="mb-3">
            <strong>Filters:</strong>
            <?= escape("item: $item_name") ?>
            <?= escape(" | person: $person_name") ?>
            <?= escape(" | location: $location") ?>
            <?= escape(" | type: $type") ?>
        </div>

        <?php if (count($results) === 0): ?>
            <div class="alert alert-info">No items matched your search.</div>
        <?php else: ?>
            <div class="row">
            <?php foreach ($results as $r): ?>
                <div class="col-md-6">
                    <div class="card result-card p-3">
                        <div class="d-flex gap-3">
                            <?php if (!empty($r['image_url'])): ?>
                                <img src="<?= escape($r['image_url']) ?>" alt="" class="item-img rounded">
                            <?php endif; ?>
                            <div>
                                <h5 class="mb-1"><?= escape($r['item_name']) ?> <small class="text-muted">(<?= escape($r['type']) ?>)</small></h5>
                                <p class="mb-1"><?= nl2br(escape($r['description'])) ?></p>
                                <p class="mb-1"><strong>Location:</strong> <?= escape($r['location']) ?></p>
                                <p class="mb-1"><strong>Date:</strong> <?= escape($r['date']) ?></p>
                                <p class="mb-0"><strong>Reporter:</strong> <?= escape($r['reporter']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
