document.addEventListener("DOMContentLoaded", () => {
  const openCreateBtn = document.getElementById("openCreateBtn");
  const closeModalBtn = document.getElementById("closeModalBtn");
  const cancelBtn = document.getElementById("cancelBtn");
  const modal = document.getElementById("projectModal");
  const form = document.getElementById("projectForm");

  const searchInput = document.getElementById("searchInput");
  const serviceFilter = document.getElementById("serviceFilter");

  function openModal() { modal.hidden = false; }
  function closeModal() { modal.hidden = true; form.reset(); }

  function applyFilters() {
    const q = (searchInput.value || "").toLowerCase().trim();
    const service = serviceFilter.value;

    document.querySelectorAll(".project-row").forEach(row => {
      const name = (row.dataset.name || "").toLowerCase();
      const rowService = row.dataset.service;

      const matchText = !q || name.includes(q);
      const matchService = service === "all" || rowService === service;

      row.style.display = (matchText && matchService) ? "" : "none";
    });
  }

  openCreateBtn?.addEventListener("click", openModal);
  closeModalBtn?.addEventListener("click", closeModal);
  cancelBtn?.addEventListener("click", closeModal);

  modal?.addEventListener("click", (e) => {
    if (e.target === modal) closeModal();
  });

  searchInput?.addEventListener("input", applyFilters);
  serviceFilter?.addEventListener("change", applyFilters);

  form?.addEventListener("submit", (e) => {
    e.preventDefault();
    alert("Project created (UI only). Later connect to PHP + MySQL.");
    closeModal();
  });

  applyFilters();
});