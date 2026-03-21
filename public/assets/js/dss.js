document.addEventListener("DOMContentLoaded", () => {
    const recalcRisk = document.getElementById("recalcRisk");
    const recalcPriority = document.getElementById("recalcPriority");

    recalcRisk?.addEventListener("click", () => {
        if (typeof showToast === "function") {
            showToast("DSS Updated", "Project delay risks recalculated.");
        }
    });

    recalcPriority?.addEventListener("click", () => {
        if (typeof showToast === "function") {
            showToast("Scores Updated", "Client priority scores recalculated.");
        }
    });
});