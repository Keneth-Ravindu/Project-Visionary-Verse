document.addEventListener("DOMContentLoaded", () => {
  const openCreateBtn = document.getElementById("openCreateBtn");
  const closeModalBtn = document.getElementById("closeModalBtn");
  const cancelBtn = document.getElementById("cancelBtn");
  const modal = document.getElementById("taskModal");
  const form = document.getElementById("taskForm");

  const searchInput = document.getElementById("searchInput");
  const statusFilter = document.getElementById("statusFilter");
  const note = document.getElementById("statusNote");

  function openModal() { modal.hidden = false; }
  function closeModal() { modal.hidden = true; form.reset(); }

  function pillClassFor(status){
    if(status === "todo") return "pill pill-info";
    if(status === "progress") return "pill pill-good";
    if(status === "review") return "pill pill-warn";
    return "pill pill-good";
  }
  function labelFor(status){
    if(status === "todo") return "To Do";
    if(status === "progress") return "In Progress";
    if(status === "review") return "Review";
    return "Done";
  }

  function applyFilters() {
    const q = (searchInput.value || "").toLowerCase().trim();
    const status = statusFilter.value;

    document.querySelectorAll(".task-row").forEach(row => {
      const name = (row.dataset.name || "").toLowerCase();
      const rowStatus = row.dataset.status;

      const matchText = !q || name.includes(q);
      const matchStatus = status === "all" || rowStatus === status;

      row.style.display = (matchText && matchStatus) ? "" : "none";
    });
  }

  // Status change UI
  document.querySelectorAll(".statusSelect").forEach(sel => {
    sel.addEventListener("change", (e) => {
      const row = e.target.closest(".task-row");
      const newStatus = e.target.value;
      row.dataset.status = newStatus;

      const pill = row.querySelector(".statusPill");
      pill.className = `${pillClassFor(newStatus)} statusPill`;
      pill.textContent = labelFor(newStatus);

      note.textContent =
        `Status updated to "${labelFor(newStatus)}" (UI only). Later: send AJAX to PHP + create notification.`;

      applyFilters();
    });
  });

  openCreateBtn?.addEventListener("click", openModal);
  closeModalBtn?.addEventListener("click", closeModal);
  cancelBtn?.addEventListener("click", closeModal);

  modal?.addEventListener("click", (e) => {
    if (e.target === modal) closeModal();
  });

  searchInput?.addEventListener("input", applyFilters);
  statusFilter?.addEventListener("change", applyFilters);

  form?.addEventListener("submit", (e) => {
    e.preventDefault();
    alert("Task created (UI only). Later connect to TaskController.");
    closeModal();
  });

  applyFilters();
});