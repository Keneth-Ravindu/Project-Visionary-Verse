document.addEventListener("DOMContentLoaded", () => {
  const search = document.getElementById("clientSearch");
  const filter = document.getElementById("clientStatus");
  const table = document.getElementById("clientsTable");
  const form = document.getElementById("clientForm");

  const mName = document.getElementById("mClientName");
  const mEmail = document.getElementById("mClientEmail");
  const mCompany = document.getElementById("mClientCompany");
  const mStatus = document.getElementById("mClientStatus");

  let editingRow = null;

  function applyFilters(){
    const q = (search.value || "").toLowerCase().trim();
    const st = filter.value;

    table.querySelectorAll("tbody tr").forEach(tr => {
      const name = (tr.dataset.name || "").toLowerCase();
      const status = tr.dataset.status;
      const okText = !q || name.includes(q);
      const okStatus = (st === "all") || (status === st);
      tr.style.display = (okText && okStatus) ? "" : "none";
    });
  }

  search?.addEventListener("input", applyFilters);
  filter?.addEventListener("change", applyFilters);

  table.addEventListener("click", (e) => {
    const tr = e.target.closest("tr");
    if(!tr) return;

    if(e.target.classList.contains("btnEdit")){
      editingRow = tr;
      mName.value = tr.children[0].textContent.trim();
      mEmail.value = tr.children[1].textContent.trim();
      mCompany.value = tr.children[2].textContent.trim();
      mStatus.value = tr.dataset.status;
      document.getElementById("clientModal").classList.add("open");
    }

    if(e.target.classList.contains("btnToggle")){
      const next = tr.dataset.status === "active" ? "inactive" : "active";
      tr.dataset.status = next;

      tr.children[3].innerHTML = next === "active"
        ? `<span class="chip good"><span class="dot"></span>Active</span>`
        : `<span class="chip warn"><span class="dot"></span>Inactive</span>`;

      e.target.textContent = next === "active" ? "Deactivate" : "Activate";
      showToast("Status updated", `Client is now ${next.toUpperCase()} (UI only).`);
      applyFilters();
    }
  });

  form?.addEventListener("submit", (e) => {
    e.preventDefault();
    showToast("Saved", "Client saved (UI only). Connect to PHP later.");
    document.getElementById("clientModal").classList.remove("open");
    editingRow = null;
    form.reset();
  });

  applyFilters();
});