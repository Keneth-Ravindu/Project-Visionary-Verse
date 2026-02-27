document.addEventListener("DOMContentLoaded", () => {
  const search = document.getElementById("projectSearch");
  const serviceFilter = document.getElementById("serviceFilter");
  const statusFilter = document.getElementById("statusFilter");
  const table = document.getElementById("projectsTable");
  const form = document.getElementById("projectForm");

  const mName = document.getElementById("mProjectName");
  const mClient = document.getElementById("mProjectClient");
  const mService = document.getElementById("mProjectService");
  const mStatus = document.getElementById("mProjectStatus");
  const mDue = document.getElementById("mProjectDue");

  let editingRow = null;

  function statusChip(status){
    const cls = status === "Completed" ? "good"
      : status === "Review" ? "warn"
      : status === "In Progress" ? "info" : "";
    return `<span class="chip ${cls}"><span class="dot"></span>${status}</span>`;
  }

  function serviceChip(service){
    const cls = service === "SEO" ? "info"
      : service === "Ads" ? "warn"
      : service === "Social Media" ? "good" : "";
    return `<span class="chip ${cls}"><span class="dot"></span>${service}</span>`;
  }

  function applyFilters(){
    const q = (search.value || "").toLowerCase().trim();
    const svc = serviceFilter.value;
    const st = statusFilter.value;

    table.querySelectorAll("tbody tr").forEach(tr => {
      const name = (tr.dataset.name || "").toLowerCase();
      const okText = !q || name.includes(q);
      const okSvc = (svc === "all") || (tr.dataset.service === svc);
      const okSt = (st === "all") || (tr.dataset.status === st);
      tr.style.display = (okText && okSvc && okSt) ? "" : "none";
    });
  }

  search?.addEventListener("input", applyFilters);
  serviceFilter?.addEventListener("change", applyFilters);
  statusFilter?.addEventListener("change", applyFilters);

  table.addEventListener("click", (e) => {
    const tr = e.target.closest("tr");
    if(!tr) return;

    if(e.target.classList.contains("btnEdit")){
      editingRow = tr;
      mName.value = tr.children[0].textContent.trim();
      mClient.value = tr.children[1].textContent.trim();
      mService.value = tr.dataset.service;
      mStatus.value = tr.dataset.status;
      mDue.value = tr.children[4].textContent.trim();
      document.getElementById("projectModal").classList.add("open");
    }

    if(e.target.classList.contains("btnStatus")){
      const order = ["To Do","In Progress","Review","Completed"];
      const idx = order.indexOf(tr.dataset.status);
      const next = order[(idx + 1) % order.length];
      tr.dataset.status = next;
      tr.children[3].innerHTML = statusChip(next);
      showToast("Project status updated", `Project moved to ${next} (UI only).`);
      applyFilters();
    }
  });

  form?.addEventListener("submit", (e) => {
    e.preventDefault();

    if(editingRow){
      editingRow.dataset.name = mName.value.trim();
      editingRow.dataset.service = mService.value;
      editingRow.dataset.status = mStatus.value;

      editingRow.children[0].textContent = mName.value.trim();
      editingRow.children[1].textContent = mClient.value.trim();
      editingRow.children[2].innerHTML = serviceChip(mService.value);
      editingRow.children[3].innerHTML = statusChip(mStatus.value);
      editingRow.children[4].textContent = mDue.value;

      showToast("Saved", "Project updated (UI only).");
    } else {
      showToast("Saved", "Project saved (UI only).");
    }

    document.getElementById("projectModal").classList.remove("open");
    editingRow = null;
    form.reset();
    applyFilters();
  });

  applyFilters();
});