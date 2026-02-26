document.addEventListener("DOMContentLoaded", () => {
  const openCreateBtn = document.getElementById("openCreateBtn");
  const closeModalBtn = document.getElementById("closeModalBtn");
  const cancelBtn = document.getElementById("cancelBtn");
  const modal = document.getElementById("clientModal");
  const form = document.getElementById("clientForm");
  const modalTitle = document.getElementById("modalTitle");

  const searchInput = document.getElementById("searchInput");
  const statusFilter = document.getElementById("statusFilter");

  let editMode = false;

  function openModal(title) {
    modalTitle.textContent = title;
    modal.hidden = false;
  }
  function closeModal() {
    modal.hidden = true;
    form.reset();
    editMode = false;
  }

  function applyFilters() {
    const q = (searchInput.value || "").toLowerCase().trim();
    const status = statusFilter.value;

    document.querySelectorAll(".client-row").forEach(row => {
      const name = (row.dataset.name || "").toLowerCase();
      const rowStatus = row.dataset.status;

      const matchText = !q || name.includes(q);
      const matchStatus = status === "all" || rowStatus === status;

      row.style.display = (matchText && matchStatus) ? "" : "none";
    });
  }

  openCreateBtn?.addEventListener("click", () => openModal("Add Client"));
  closeModalBtn?.addEventListener("click", closeModal);
  cancelBtn?.addEventListener("click", closeModal);

  modal?.addEventListener("click", (e) => {
    if (e.target === modal) closeModal();
  });

  searchInput?.addEventListener("input", applyFilters);
  statusFilter?.addEventListener("change", applyFilters);

  document.querySelectorAll(".editBtn").forEach(btn => {
    btn.addEventListener("click", (e) => {
      editMode = true;
      const row = e.target.closest(".client-row");
      openModal("Edit Client");

      // Fill form with row dummy data (UI only)
      document.getElementById("clientName").value = row.children[0].textContent.trim();
      document.getElementById("clientEmail").value = row.children[1].textContent.trim();
      document.getElementById("clientCompany").value = row.children[2].textContent.trim();
      document.getElementById("clientStatus").value = row.dataset.status;
    });
  });

  document.querySelectorAll(".toggleBtn").forEach(btn => {
    btn.addEventListener("click", (e) => {
      const row = e.target.closest(".client-row");
      const current = row.dataset.status;
      const next = current === "active" ? "inactive" : "active";
      row.dataset.status = next;

      // Update pill + button label (UI only)
      const pill = row.querySelector(".pill");
      pill.textContent = next === "active" ? "Active" : "Inactive";
      pill.className = next === "active" ? "pill pill-good" : "pill pill-warn";
      e.target.textContent = next === "active" ? "Deactivate" : "Activate";

      applyFilters();
    });
  });

  form?.addEventListener("submit", (e) => {
    e.preventDefault();
    alert(editMode ? "Saved changes (UI only)." : "Client added (UI only).");
    closeModal();
  });

  applyFilters();
});