document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("clientSearch");
    const statusFilter = document.getElementById("clientStatus");
    const table = document.getElementById("clientsTable");
    const rows = table ? Array.from(table.querySelectorAll("tbody tr")) : [];

    const clientForm = document.getElementById("clientForm");
    const modalTitle = document.getElementById("clientModalTitle");
    const hiddenId = document.getElementById("mClientId");

    const nameInput = document.getElementById("mClientName");
    const companyInput = document.getElementById("mClientCompany");
    const emailInput = document.getElementById("mClientEmail");
    const phoneInput = document.getElementById("mClientPhone");
    const statusInput = document.getElementById("mClientStatus");

    function filterRows() {
        const q = (searchInput?.value || "").toLowerCase().trim();
        const status = (statusFilter?.value || "all").toLowerCase();

        rows.forEach((row) => {
            const name = (row.dataset.name || "").toLowerCase();
            const rowStatus = (row.dataset.status || "").toLowerCase();

            const matchesSearch = name.includes(q);
            const matchesStatus = status === "all" || rowStatus === status;

            row.style.display = matchesSearch && matchesStatus ? "" : "none";
        });
    }

    if (searchInput) searchInput.addEventListener("input", filterRows);
    if (statusFilter) statusFilter.addEventListener("change", filterRows);

    function resetFormToCreateMode() {
        if (!clientForm) return;

        clientForm.action = "/pvv/public/client/store";
        if (modalTitle) modalTitle.textContent = "Client Details";
        if (hiddenId) hiddenId.value = "";

        if (nameInput) nameInput.value = "";
        if (companyInput) companyInput.value = "";
        if (emailInput) emailInput.value = "";
        if (phoneInput) phoneInput.value = "";
        if (statusInput) statusInput.value = "active";
    }

    document.querySelectorAll('[data-open="clientModal"]').forEach((btn) => {
        btn.addEventListener("click", () => {
            resetFormToCreateMode();
        });
    });

    document.querySelectorAll(".btnEdit").forEach((btn) => {
        btn.addEventListener("click", async () => {
            const id = btn.dataset.id;
            if (!id) return;

            try {
                const response = await fetch(`/pvv/public/client/edit/${id}`);
                const client = await response.json();

                if (clientForm) clientForm.action = `/pvv/public/client/update/${id}`;
                if (modalTitle) modalTitle.textContent = "Edit Client";
                if (hiddenId) hiddenId.value = client.client_id || "";

                if (nameInput) nameInput.value = client.name || "";
                if (companyInput) companyInput.value = client.company || "";
                if (emailInput) emailInput.value = client.email || "";
                if (phoneInput) phoneInput.value = client.phone || "";
                if (statusInput) statusInput.value = client.status || "active";

                const modal = document.getElementById("clientModal");
                if (modal) modal.classList.add("open");
            } catch (error) {
                alert("Failed to load client details.");
                console.error(error);
            }
        });
    });
});