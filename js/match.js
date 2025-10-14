
function showToast(message, type = "success") {
    const toast = document.getElementById("toast") || (() => {
        const div = document.createElement("div");
        div.id = "toast";
        div.className = "toast";
        document.body.appendChild(div);
        return div;
    })();

    toast.className = `toast ${type} show`;
    toast.textContent = message;

    setTimeout(() => toast.className = "toast", 3000);
}

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".mark-matched-btn").forEach(button => {
        button.addEventListener("click", async (e) => {
            const form = e.target.closest(".match-form");
            const lostId = form.dataset.lost;
            const foundId = form.dataset.found;

            if (!confirm("Are you sure you want to mark this as matched?")) return;

            try {
                const response = await fetch("./php/mark_matched.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `lost_id=${lostId}&found_id=${foundId}`
                });

                const data = await response.json();

                if (data.success) {
                    showToast("Items successfully marked as matched ✅", "success");
                    form.closest(".match-card").remove();
                } else {
                    showToast("❌ Error: " + (data.error || "Unknown error"), "error");
                }
            } catch (err) {
                showToast("Fetch failed ❌ " + err.message, "error");
            }
        });
    });
});
