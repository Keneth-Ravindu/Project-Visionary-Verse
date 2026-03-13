document.addEventListener("DOMContentLoaded", () => {
    const filter = document.getElementById("notifFilter");
    const table = document.getElementById("notifTable");
    const badge = document.getElementById("notifBadge");

    function updateBadge() {
        const unread = table?.querySelectorAll('tbody tr[data-read="no"]').length || 0;
        if (badge) badge.textContent = unread;
    }

    function applyFilter() {
        const t = (filter?.value || "all").toLowerCase();

        table?.querySelectorAll("tbody tr").forEach(tr => {
            const ok = t === "all" || (tr.dataset.type || "").toLowerCase() === t;
            tr.style.display = ok ? "" : "none";
        });
    }

    filter?.addEventListener("change", applyFilter);

    updateBadge();
    applyFilter();
});