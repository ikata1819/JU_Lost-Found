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