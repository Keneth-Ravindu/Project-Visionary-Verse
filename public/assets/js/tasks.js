document.addEventListener("DOMContentLoaded", () => {
    const search = document.getElementById("taskSearch");
    const priorityFilter = document.getElementById("priorityFilter");
    const statusFilter = document.getElementById("statusFilter");
    const table = document.getElementById("tasksTable");

    const form = document.getElementById("taskForm");
    const modalTitle = document.getElementById("taskModalTitle");
    const hiddenId = document.getElementById("mTaskId");

    const mName = document.getElementById("mTaskName");
    const mProject = document.getElementById("mTaskProject");
    const mAssignee = document.getElementById("mTaskAssignee");
    const mPriority = document.getElementById("mTaskPriority");
    const mStatus = document.getElementById("mTaskStatus");
    const mDeadline = document.getElementById("mTaskDeadline");
    const mDescription = document.getElementById("mTaskDescription");

    function applyFilters() {
        const q = (search?.value || "").toLowerCase().trim();
        const pr = (priorityFilter?.value || "all").toLowerCase();
        const st = (statusFilter?.value || "all").toLowerCase();

        table?.querySelectorAll("tbody tr").forEach(tr => {
            const name = (tr.dataset.name || "").toLowerCase();
            const priority = (tr.dataset.priority || "").toLowerCase();
            const status = (tr.dataset.status || "").toLowerCase();

            const okText = !q || name.includes(q);
            const okP = pr === "all" || priority === pr;
            const okS = st === "all" || status === st;

            tr.style.display = (okText && okP && okS) ? "" : "none";
        });
    }

    search?.addEventListener("input", applyFilters);
    priorityFilter?.addEventListener("change", applyFilters);
    statusFilter?.addEventListener("change", applyFilters);

    function resetFormToCreateMode() {
        if (!form) return;

        form.action = "/pvv/public/task/store";
        if (modalTitle) modalTitle.textContent = "Task Details";
        if (hiddenId) hiddenId.value = "";

        if (mName) mName.value = "";
        if (mProject) mProject.value = "";
        if (mAssignee) mAssignee.value = "";
        if (mPriority) mPriority.value = "High";
        if (mStatus) mStatus.value = "To Do";
        if (mDeadline) mDeadline.value = "";
        if (mDescription) mDescription.value = "";
    }

    document.querySelectorAll('[data-open="taskModal"]').forEach(btn => {
        btn.addEventListener("click", () => {
            resetFormToCreateMode();
        });
    });

    document.querySelectorAll(".btnEdit").forEach(btn => {
        btn.addEventListener("click", async () => {
            const id = btn.dataset.id;
            if (!id) return;

            try {
                const response = await fetch(`/pvv/public/task/edit/${id}`);
                const task = await response.json();

                if (form) form.action = `/pvv/public/task/update/${id}`;
                if (modalTitle) modalTitle.textContent = "Edit Task";
                if (hiddenId) hiddenId.value = task.task_id || "";

                if (mName) mName.value = task.name || "";
                if (mProject) mProject.value = task.project_id || "";
                if (mAssignee) mAssignee.value = task.assignee_id || "";
                if (mPriority) mPriority.value = task.priority || "High";
                if (mStatus) mStatus.value = task.status || "To Do";
                if (mDeadline) mDeadline.value = task.deadline || "";
                if (mDescription) mDescription.value = task.description || "";

                const modal = document.getElementById("taskModal");
                if (modal) modal.classList.add("open");
            } catch (error) {
                alert("Failed to load task details.");
                console.error(error);
            }
        });
    });

    applyFilters();
});