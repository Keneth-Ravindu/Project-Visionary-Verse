document.addEventListener("DOMContentLoaded", () => {
    const search = document.getElementById("approvalSearch");
    const filter = document.getElementById("approvalFilter");
    const table = document.getElementById("approvalsTable");

    const viewModal = document.getElementById("viewDeliverableModal");
    const viewTitle = document.getElementById("viewTitle");
    const viewSub = document.getElementById("viewSub");
    const viewInfo = document.getElementById("viewInfo");

    function applyFilters() {
        const q = (search?.value || "").toLowerCase().trim();
        const st = filter?.value || "all";

        table?.querySelectorAll("tbody tr").forEach(tr => {
            const name = (tr.dataset.name || "").toLowerCase();
            const status = tr.dataset.status || "";

            const okText = !q || name.includes(q);
            const okStatus = st === "all" || status === st;

            tr.style.display = (okText && okStatus) ? "" : "none";
        });
    }

    search?.addEventListener("input", applyFilters);
    filter?.addEventListener("change", applyFilters);

    document.querySelectorAll(".btnView").forEach(btn => {
        btn.addEventListener("click", async () => {
            const id = btn.dataset.id;
            if (!id) return;

            try {
                const response = await fetch(`/Project-Visionary-Verse/public/approval/show/${id}`);
                const d = await response.json();

                if (viewTitle) viewTitle.textContent = d.name || "Deliverable";
                if (viewSub) viewSub.textContent = `Project: ${d.project_name || "N/A"} • Status: ${d.status || "N/A"}`;

                const fileName = d.file_name ? d.file_name : "No file name stored";
                const feedback = d.feedback ? d.feedback : "No feedback yet";
                const uploadedBy = d.uploader_name ? d.uploader_name : "N/A";
                const submittedAt = d.submitted_at ? d.submitted_at : "N/A";

                if (viewInfo) {
                    viewInfo.innerHTML = `
                        <strong>Uploaded By:</strong> ${uploadedBy}<br>
                        <strong>Submitted At:</strong> ${submittedAt}<br>
                        <strong>File Name:</strong> ${fileName}<br>
                        <strong>Feedback:</strong> ${feedback}
                    `;
                }

                if (viewModal) viewModal.classList.add("open");
            } catch (error) {
                alert("Failed to load deliverable details.");
                console.error(error);
            }
        });
    });

    applyFilters();
});