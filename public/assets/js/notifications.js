document.addEventListener("DOMContentLoaded", () => {
  const list = document.getElementById("notifList");
  const unreadCountEl = document.getElementById("unreadCount");
  const markAllBtn = document.getElementById("markAllBtn");
  const simulateBtn = document.getElementById("simulateBtn");

  function refreshUnread(){
    const rows = document.querySelectorAll(".n-row");
    let unread = 0;
    rows.forEach(r => { if (r.dataset.read === "false") unread++; });
    unreadCountEl.textContent = `Unread: ${unread}`;
  }

  function markRowRead(row){
    row.dataset.read = "true";
    row.children[4].innerHTML = `<span class="pill pill-good">Read</span>`;
  }

  // Click a notification row to mark as read
  document.querySelectorAll(".n-row").forEach(row => {
    row.addEventListener("click", (e) => {
      // allow link clicks
      if (e.target.tagName.toLowerCase() === "a") return;
      if (row.dataset.read === "false") {
        markRowRead(row);
        refreshUnread();
      }
    });
  });

  markAllBtn?.addEventListener("click", () => {
    document.querySelectorAll(".n-row").forEach(r => markRowRead(r));
    refreshUnread();
    alert("Marked all as read (UI only). Later: update DB.");
  });

  simulateBtn?.addEventListener("click", () => {
    const row = document.createElement("div");
    row.className = "t-row t-5 n-row";
    row.dataset.read = "false";
    row.innerHTML = `
      <div>New task assigned to you (Create Post Designs)</div>
      <div><span class="pill pill-info">Task</span></div>
      <div><a class="link" href="./tasks.html">Open</a></div>
      <div>Now</div>
      <div><span class="pill pill-info">Unread</span></div>
    `;
    row.addEventListener("click", (e) => {
      if (e.target.tagName.toLowerCase() === "a") return;
      if (row.dataset.read === "false") {
        markRowRead(row);
        refreshUnread();
      }
    });
    list.appendChild(row);
    refreshUnread();
  });

  refreshUnread();
});