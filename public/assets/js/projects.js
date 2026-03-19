document.addEventListener("DOMContentLoaded", () => {
    const search = document.getElementById("projectSearch");
    const serviceFilter = document.getElementById("serviceFilter");
    const statusFilter = document.getElementById("statusFilter");
    const table = document.getElementById("projectsTable");

    const form = document.getElementById("projectForm");
    const modalTitle = document.getElementById("projectModalTitle");
    const hiddenId = document.getElementById("mProjectId");

    const mName = document.getElementById("mProjectName");
    const mClient = document.getElementById("mProjectClient");
    const mService = document.getElementById("mProjectService");
    const mStatus = document.getElementById("mProjectStatus");
    const mDue = document.getElementById("mProjectDue");
    const mDescription = document.getElementById("mProjectDescription");

    function applyFilters() {
        const q = (search?.value || "").toLowerCase().trim();
        const svc = (serviceFilter?.value || "all").toLowerCase();
        const st = (statusFilter?.value || "all").toLowerCase();

        table?.querySelectorAll("tbody tr").forEach(tr => {
            const name = (tr.dataset.name || "").toLowerCase();
            const service = (tr.dataset.service || "").toLowerCase();
            const status = (tr.dataset.status || "").toLowerCase();

            const okText = !q || name.includes(q);
            const okSvc = svc === "all" || service === svc.toLowerCase();
            const okSt = st === "all" || status === st.toLowerCase();

            tr.style.display = (okText && okSvc && okSt) ? "" : "none";
        });
    }

    search?.addEventListener("input", applyFilters);
    serviceFilter?.addEventListener("change", applyFilters);
    statusFilter?.addEventListener("change", applyFilters);

    function resetFormToCreateMode() {
        if (!form) return;

        form.action = "/pvv/public/project/store";
        if (modalTitle) modalTitle.textContent = "Project Details";
        if (hiddenId) hiddenId.value = "";

        if (mName) mName.value = "";
        if (mClient) mClient.value = "";
        if (mService) mService.value = "SEO";
        if (mStatus) mStatus.value = "To Do";
        if (mDue) mDue.value = "";
        if (mDescription) mDescription.value = "";
    }

    document.querySelectorAll('[data-open="projectModal"]').forEach(btn => {
        btn.addEventListener("click", () => {
            resetFormToCreateMode();
        });
    });

    document.querySelectorAll(".btnEdit").forEach(btn => {
        btn.addEventListener("click", async () => {
            const id = btn.dataset.id;
            if (!id) return;

            try {
                const response = await fetch(`/pvv/public/project/edit/${id}`);
                const project = await response.json();

                if (form) form.action = `/pvv/public/project/update/${id}`;
                if (modalTitle) modalTitle.textContent = "Edit Project";
                if (hiddenId) hiddenId.value = project.project_id || "";

                if (mName) mName.value = project.name || "";
                if (mClient) mClient.value = project.client_id || "";
                if (mService) mService.value = project.service || "SEO";
                if (mStatus) mStatus.value = project.status || "To Do";
                if (mDue) mDue.value = project.due_date || "";
                if (mDescription) mDescription.value = project.description || "";

                const modal = document.getElementById("projectModal");
                if (modal) modal.classList.add("open");
            } catch (error) {
                alert("Failed to load project details.");
                console.error(error);
            }
        });
    });

    applyFilters();
});