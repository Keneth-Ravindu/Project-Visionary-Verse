document.addEventListener("DOMContentLoaded", () => {
  const filter = document.getElementById("notifFilter");
  const table = document.getElementById("notifTable");
  const markAll = document.getElementById("markAll");
  const simulate = document.getElementById("simulate");
  const badge = document.getElementById("notifBadge");

  function unreadChip(){
    return `<span class="chip warn"><span class="dot"></span>Unread</span>`;
  }
  function readChip(){
    return `<span class="chip good"><span class="dot"></span>Read</span>`;
  }

  function updateBadge(){
    const unread = table.querySelectorAll('tbody tr[data-read="no"]').length;
    if(badge) badge.textContent = unread;
  }

  function applyFilter(){
    const t = filter.value;
    table.querySelectorAll("tbody tr").forEach(tr => {
      const ok = (t === "all") || (tr.dataset.type === t);
      tr.style.display = ok ? "" : "none";
    });
  }

  filter?.addEventListener("change", applyFilter);

  table.addEventListener("click", (e) => {
    const tr = e.target.closest("tr");
    if(!tr) return;

    if(e.target.classList.contains("btnRead")){
      tr.dataset.read = "yes";
      tr.children[3].innerHTML = readChip();
      showToast("Updated", "Notification marked as read (UI only).");
      updateBadge();
    }
  });

  markAll?.addEventListener("click", () => {
    table.querySelectorAll('tbody tr').forEach(tr => {
      tr.dataset.read = "yes";
      tr.children[3].innerHTML = readChip();
    });
    showToast("All read", "All notifications marked as read (UI only).");
    updateBadge();
  });

  simulate?.addEventListener("click", () => {
    const tr = document.createElement("tr");
    tr.dataset.type = "task";
    tr.dataset.read = "no";
    tr.innerHTML = `
      <td><span class="chip info"><span class="dot"></span>Task</span></td>
      <td>Task status changed (To Do → Review)</td>
      <td>Just now</td>
      <td>${unreadChip()}</td>
      <td class="actions"><button class="btn btn-outline btnRead">Mark Read</button></td>
    `;
    table.querySelector("tbody").prepend(tr);
    showToast("New notification", "Simulated task notification added (UI only).");
    updateBadge();
    applyFilter();
  });

  updateBadge();
  applyFilter();
});