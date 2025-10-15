<?php
// Resolved header partial (fragment only). This file is included inside pages that already
// provide HTML <head> and CSS links. It renders the top navigation + profile dropdown.
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$homeHref = isset($_SESSION['user_id']) ? 'home.php' : 'index.html';
?>

<header class="shadow-sm mb-4 sticky-top" style="background-color: white; border-bottom: 3px solid #4F7C82;">
    <div class="container d-flex justify-content-between  py-3">
        <div class="logo">
            <h2 class="fw-bold mb-0" style="color: #082E33;">🛡️ JU Lost & Found </h2>
            <small style="color: #4F7C82;">Jahangirnagar University</small>
        </div>
        <nav>
            <ul class="nav align-items-center" style="width:100%">
                <li class="nav-item"><a class="nav-link fw-semibold" href="<?= htmlspecialchars($homeHref); ?>" style="color: #4F7C82;">Home</a></li>
                <li class="nav-item"><a class="nav-link fw-semibold" href="<?= htmlspecialchars($homeHref . '#report-lost'); ?>" style="color: #165ebdff;">Report Lost</a></li>
                <li class="nav-item"><a class="nav-link fw-semibold" href="<?= htmlspecialchars($homeHref . '#report-found'); ?>" style="color: #165ebdff;">Report Found</a></li>
                <li class="nav-item"><a class="nav-link fw-semibold" href="<?= htmlspecialchars($homeHref . '#search-items'); ?>" style="color: #165ebdff;">Search</a></li>
                <li class="nav-item"><a class="nav-link fw-semibold" href="match.php" style="color: #55ba93ff;">Match Report</a></li>

                <li class="nav-item ms-auto" aria-hidden="true"></li>

                <!-- Profile dropdown -->
                <li class="nav-item ms-3" style="position:relative;">
                    <div class="profile-wrapper" role="button" aria-haspopup="true" aria-expanded="false">
                        <div class="profile-avatar" id="profileAvatar" aria-label="Open profile">A</div>
                        <div class="profile-dropdown" id="profileDropdown">
                            <div class="profile-card">
                                <div class="profile-avatar" style="width:48px;height:48px;font-size:1.05rem;">A</div>
                                <div class="profile-info">
                                    <div class="name" id="profileName">User Name</div>
                                    <div class="email" id="profileEmail">email@example.com</div>
                                </div>
                            </div>
                            <div class="profile-divider"></div>
                            <div class="profile-actions">
                                <a href="profile.php" id="profileHistoryLink">Previous History</a>
                                <a href="php/logout.php" style="color:#dc3545;">Log out</a>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fetch profile info and populate dropdown
    fetch('php/get_profile.php')
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;
            const user = data.user;
            const nameEl = document.getElementById('profileName');
            const emailEl = document.getElementById('profileEmail');
            const avatarEls = document.querySelectorAll('.profile-avatar');
            if (nameEl) nameEl.textContent = user.name;
            if (emailEl) emailEl.textContent = user.email;
            const initials = user.name.split(' ').map(s=>s[0]).slice(0,2).join('').toUpperCase();
            avatarEls.forEach(a=> a.textContent = initials);
            const history = document.getElementById('profileHistoryLink');
            if (history) history.href = 'profile.php?id=' + user.id;
        })
        .catch(err => console.error('Profile load error', err));
});
</script>

<?php
// Include lostfound.js only when the current page is home.php because the anchor sections exist there
$currentPage = basename($_SERVER['PHP_SELF']);
if ($currentPage === 'home.php'): ?>
<script src="/js/lostfound.js"></script>
<?php endif; ?>
