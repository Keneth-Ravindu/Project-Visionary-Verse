document.addEventListener("DOMContentLoaded", () => {
  const search = document.getElementById("approvalSearch");
  const filter = document.getElementById("approvalFilter");
  const table = document.getElementById("approvalsTable");

  const uploadForm = document.getElementById("deliverableForm");
  const mName = document.getElementById("mDelivName");
  const mProject = document.getElementById("mDelivProject");
  const mBy = document.getElementById("mDelivBy");

  const viewModal = document.getElementById("viewDeliverableModal");
  const viewTitle = document.getElementById("viewTitle");
  const viewSub = document.getElementById("viewSub");

  function chipForStatus(status){
    const cls = status === "Approved" ? "good"
      : status === "Pending" ? "warn"
      : "danger";
    return `<span class="chip ${cls}"><span class="dot"></span>${status}</span>`;
  }

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

    if(e.target.classList.contains("btnView")){
      viewTitle.textContent = tr.children[0].textContent.trim();
      viewSub.textContent = `Project: ${tr.children[1].textContent.trim()} • Status: ${tr.dataset.status}`;
      viewModal.classList.add("open");
    }

    if(e.target.classList.contains("btnApprove")){
      tr.dataset.status = "Approved";
      tr.children[3].innerHTML = chipForStatus("Approved");
      showToast("Approved", "Deliverable approved (UI only).");
      applyFilters();
    }

    if(e.target.classList.contains("btnChanges")){
      tr.dataset.status = "Changes Requested";
      tr.children[3].innerHTML = chipForStatus("Changes Requested");
      showToast("Changes requested", "Client requested changes (UI only).");
      applyFilters();
    }
  });

  uploadForm?.addEventListener("submit", (e) => {
    e.preventDefault();
    showToast("Uploaded", "Deliverable uploaded (UI only).");
    document.getElementById("deliverableModal").classList.remove("open");
    uploadForm.reset();
    applyFilters();
  });

  applyFilters();
});