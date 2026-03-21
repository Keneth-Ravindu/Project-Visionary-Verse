document.addEventListener("DOMContentLoaded", () => {
    const reportProject = document.getElementById("reportProject");
    const downloadPdfBtn = document.getElementById("downloadPdfBtn");

    function updatePdfLink() {
        if (!reportProject || !downloadPdfBtn) return;
        const projectId = reportProject.value;
        downloadPdfBtn.href = `/pvv/public/report/export/${projectId}`;
    }

    reportProject?.addEventListener("change", updatePdfLink);

    updatePdfLink();
});