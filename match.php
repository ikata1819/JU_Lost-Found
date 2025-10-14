<?php
session_start();
require 'php/db_config.php'; // Using your PDO connection

// Fetch all active lost and found items with user emails AND image URLs
$sql = "
SELECT 
    l.lost_id, l.item_name AS lost_item, l.description AS lost_description,
    l.last_seen_location AS lost_location, l.lost_date, l.image_url AS lost_image,
    u1.email AS lost_email,
    f.found_id, f.item_name AS found_item, f.description AS found_description,
    f.found_location, f.found_date, f.image_url AS found_image,
    u2.email AS found_email
FROM lost_items l
JOIN users u1 ON l.user_id = u1.id
JOIN found_items f ON f.is_active = 1
JOIN users u2 ON f.user_id = u2.id
WHERE l.is_active = 1
";

$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matched Reports - JU Lost & Found</title>
    <link href="css/match.css" rel="stylesheet">
    <link href="css/match-btn.css" rel="stylesheet">
    <link href="css/profile.css" rel="stylesheet">


</head>
<body>
<?php include __DIR__ . '/partials/header.php'; ?>

<h1>🔍 Matched Lost & Found Reports</h1>

<div class="match-container">
<?php
if ($rows && count($rows) > 0) {
    $foundMatch = false;

    foreach ($rows as $row) {
        $matchCount = 0;

        // Case-insensitive comparisons
        $nameMatch = (strcasecmp(trim($row['lost_item']), trim($row['found_item'])) == 0);
        $locationMatch = (strcasecmp(trim($row['lost_location']), trim($row['found_location'])) == 0);

        // Only check date if name or location matched
        if ($nameMatch) $matchCount++;
        if ($locationMatch) $matchCount++;
        $dateMatch = false;
        if ($nameMatch || $locationMatch) {
            if ($row['lost_date'] == $row['found_date']) {
                $dateMatch = true;
                $matchCount++;
            }
        }

        // Skip if no name/location match
        if (!$nameMatch && !$locationMatch) continue;

        $foundMatch = true;

        // Choose bar color based on score
        $barColor = ($matchCount == 3) ? 'green' : (($matchCount == 2) ? 'orange' : 'yellow');

        $matchSpan = "<span class='match-highlight'>*MATCH*</span>";

        // Correct image paths
        $lostImagePath = str_replace('../uploads/', 'uploads/', trim($row['lost_image']));
        $foundImagePath = str_replace('../uploads/', 'uploads/', trim($row['found_image']));

        // --- Start HTML Output ---
        echo "
        <div class='match-card'>
            <div class='match-header'>
                <div class='match-count'>✅ Match Score: {$matchCount}/3</div>
                <small>Lost ID: {$row['lost_id']} | Found ID: {$row['found_id']}</small>
            </div>
            <div class='match-bar {$barColor}'></div>";

        // --- Conditional Image Display ---
        if ($matchCount >= 2) {
            echo "
            <h4 style='text-align: center; color: var(--primary); margin-bottom: 5px;'>
                Visual Confirmation Required (Score: {$matchCount})
            </h4>
            <div class='image-display'>";

            // Lost Image
            if (!empty($lostImagePath) && file_exists($lostImagePath)) {
                echo "<img src='{$lostImagePath}' alt='Lost Item Image'>";
            } else {
                echo "<div class='no-image-text'>Lost Image Not Found.</div>";
            }

            echo "<span class='image-separator'>&#8644;</span>";

            // Found Image
            if (!empty($foundImagePath) && file_exists($foundImagePath)) {
                echo "<img src='{$foundImagePath}' alt='Found Item Image'>";
            } else {
                echo "<div class='no-image-text'>Found Image Not Found.</div>";
            }

            echo "</div>";
        }

        // --- Reports and Details ---
        echo "
            <div class='item-grid' style='margin-top: " . ($matchCount >= 2 ? '20px' : '0') . ";'>
                <div class='lost-report'>
                    <h3>Lost Report</h3>
                    <p><strong>Item:</strong> {$row['lost_item']} " . ($nameMatch ? $matchSpan : "") . "</p>
                    <p><strong>Location:</strong> {$row['lost_location']} " . ($locationMatch ? $matchSpan : "") . "</p>
                    <p><strong>Date:</strong> {$row['lost_date']} " . ($dateMatch ? $matchSpan : "") . "</p>
                </div>
                <div class='found-report'>
                    <h3>Found Report</h3>
                    <p><strong>Item:</strong> {$row['found_item']} " . ($nameMatch ? $matchSpan : "") . "</p>
                    <p><strong>Location:</strong> {$row['found_location']} " . ($locationMatch ? $matchSpan : "") . "</p>
                    <p><strong>Date:</strong> {$row['found_date']} " . ($dateMatch ? $matchSpan : "") . "</p>
                </div>
            </div>

            <div class='desc-section'>
                <p><strong>Lost Description:</strong> {$row['lost_description']}</p>
                <p><strong>Found Description:</strong> {$row['found_description']}</p>
            </div>

            <div class='emails'>
                <p><strong>Lost Reported By:</strong> <a href='mailto:{$row['lost_email']}'>{$row['lost_email']}</a></p>
                <p><strong>Found Reported By:</strong> <a href='mailto:{$row['found_email']}'>{$row['found_email']}</a></p>
            </div>

            <!-- ✅ Matched Button -->
            <form method='POST' action='./php/mark_matched.php' class='match-form' 
                data-lost='{$row['lost_id']}' data-found='{$row['found_id']}'>
                <button type='button' class='mark-matched-btn'>Mark as Matched</button>
            </form>
        </div>";
    }

    if (!$foundMatch) {
        echo "<p>No matches found with current criteria (must match by item name OR location).</p>";
    }
} else {
    echo "<p>No reports found in the database.</p>";
}
?>
</div>

<!-- ✅ JavaScript for AJAX -->
<script>
document.querySelectorAll('.mark-matched-btn').forEach(button => {
    button.addEventListener('click', async function () {
        const form = this.closest('.match-form');
        const lost_id = form.dataset.lost;
        const found_id = form.dataset.found;

        if (confirm('Mark this pair as matched? This will remove them from the active list.')) {
            const response = await fetch('./php/mark_matched.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `lost_id=${lost_id}&found_id=${found_id}`
            });

            const result = await response.json();
            if (result.success) {
                alert('Marked as matched successfully!');
                form.closest('.match-card').remove();
            } else {
                alert('Error: ' + (result.error || 'Something went wrong.'));
            }
        }
    });
});
</script>

</body>
</html>
