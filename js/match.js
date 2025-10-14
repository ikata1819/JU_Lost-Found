
// ✅ Toast Utility
function showToast(message, type = "success") {
    const toast = document.getElementById("toast");
    toast.className = `toast ${type} show`;
    toast.textContent = message;

    // Hide after 3 seconds
    setTimeout(() => {
        toast.className = "toast";
    }, 3000);
}

// ✅ Mark as Matched Button Handler
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".mark-matched-btn").forEach(button => {
        button.addEventListener("click", async (e) => {
            const form = e.target.closest(".match-form");
            const lostId = form.dataset.lost;
            const foundId = form.dataset.found;

            // Toast confirmation
            showToast("Are you sure you want to mark this as matched?", "warning");

            // Wait briefly, then confirm (simulate modal)
            setTimeout(() => {
                if (confirm("Confirm match?")) {
                    fetch("./php/mark_matched.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: `lost_id=${lostId}&found_id=${foundId}`
                    })
                    .then(res => res.text())
                    .then(response => {
                        showToast("Items successfully marked as matched ✅", "success");
                        form.closest(".match-card").remove(); // hide matched card
                    })
                    .catch(() => showToast("Error updating records ❌", "error"));
                }
            }, 500);
        });
    });
});

