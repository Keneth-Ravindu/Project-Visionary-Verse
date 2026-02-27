document.addEventListener("DOMContentLoaded", () => {
  const search = document.getElementById("taskSearch");
  const priorityFilter = document.getElementById("priorityFilter");
  const statusFilter = document.getElementById("statusFilter");
  const table = document.getElementById("tasksTable");
  const form = document.getElementById("taskForm");

  const mName = document.getElementById("mTaskName");
  const mProject = document.getElementById("mTaskProject");
  const mAssignee = document.getElementById("mTaskAssignee");
  const mPriority = document.getElementById("mTaskPriority");
  const mStatus = document.getElementById("mTaskStatus");
  const mDeadline = document.getElementById("mTaskDeadline");

  let editingRow = null;

  function priorityChip(p){
    const cls = p === "High" ? "danger" : p === "Medium" ? "warn" : "";
    return `<span class="chip ${cls}"><span class="dot"></span>${p}</span>`;
  }
  function statusChip(s){
    const cls = s === "Done" ? "good" : s === "Review" ? "warn" : s === "In Progress" ? "good" : "info";
    return `<span class="chip ${cls}"><span class="dot"></span>${s}</span>`;
  }

  function applyFilters(){
    const q = (search.value || "").toLowerCase().trim();
    const pr = priorityFilter.value;
    const st = statusFilter.value;

    table.querySelectorAll("tbody tr").forEach(tr => {
      const name = (tr.dataset.name || "").toLowerCase();
      const okText = !q || name.includes(q);
      const okP = (pr === "all") || (tr.dataset.priority === pr);
      const okS = (st === "all") || (tr.dataset.status === st);
      tr.style.display = (okText && okP && okS) ? "" : "none";
    });
  }

  search?.addEventListener("input", applyFilters);
  priorityFilter?.addEventListener("change", applyFilters);
  statusFilter?.addEventListener("change", applyFilters);

  table.addEventListener("click", (e) => {
    const tr = e.target.closest("tr");
    if(!tr) return;

    if(e.target.classList.contains("btnEdit")){
      editingRow = tr;
      mName.value = tr.children[0].textContent.trim();
      mProject.value = tr.children[1].textContent.trim();
      mAssignee.value = tr.children[2].textContent.trim();
      mPriority.value = tr.dataset.priority;
      mStatus.value = tr.dataset.status;
      mDeadline.value = tr.children[5].textContent.trim();
      document.getElementById("taskModal").classList.add("open");
    }

    if(e.target.classList.contains("btnMove")){
      const order = ["To Do","In Progress","Review","Done"];
      const idx = order.indexOf(tr.dataset.status);
      const next = order[(idx + 1) % order.length];

      tr.dataset.status = next;
      tr.children[4].innerHTML = statusChip(next);

      showToast("Task moved", `Status changed to ${next} (UI only).`);

      // Simulate "real-time notification" behavior
      if(next === "Review"){
        setTimeout(() => showToast("Notification", "Client/Assignee notified (AJAX polling later)."), 300);
      }

      applyFilters();
    }
  });

  form?.addEventListener("submit", (e) => {
    e.preventDefault();

    if(editingRow){
      editingRow.dataset.name = mName.value.trim();
      editingRow.dataset.priority = mPriority.value;
      editingRow.dataset.status = mStatus.value;

      editingRow.children[0].textContent = mName.value.trim();
      editingRow.children[1].textContent = mProject.value.trim();
      editingRow.children[2].textContent = mAssignee.value.trim();
      editingRow.children[3].innerHTML = priorityChip(mPriority.value);
      editingRow.children[4].innerHTML = statusChip(mStatus.value);
      editingRow.children[5].textContent = mDeadline.value;

      showToast("Saved", "Task updated (UI only).");
    } else {
      showToast("Saved", "Task saved (UI only).");
    }

    document.getElementById("taskModal").classList.remove("open");
    editingRow = null;
    form.reset();
    applyFilters();
  });

  applyFilters();
});