// Shared UI: drawer, dropdown, modals, toast
document.addEventListener("DOMContentLoaded", () => {
  // Drawer (mobile menu)
  const drawer = document.getElementById("drawer");
  const openDrawer = document.getElementById("openDrawer");
  const closeDrawer = document.getElementById("closeDrawer");

  function setDrawer(isOpen){
    if(!drawer) return;
    drawer.classList.toggle("open", isOpen);
  }
  openDrawer?.addEventListener("click", () => setDrawer(true));
  closeDrawer?.addEventListener("click", () => setDrawer(false));
  drawer?.addEventListener("click", (e) => {
    if(e.target === drawer) setDrawer(false);
  });

  // Notifications dropdown
  const notifBtn = document.getElementById("notifBtn");
  const notifMenu = document.getElementById("notifMenu");

  function setMenu(open){
    if (!notifMenu) return;
    notifMenu.classList.toggle("open", open);
  }

  notifBtn?.addEventListener("click", (e) => {
    e.preventDefault();
    e.stopPropagation();
    setMenu(!notifMenu.classList.contains("open"));
  });

  document.addEventListener("click", (e) => {
    if (!notifMenu || !notifBtn) return;

    if (!notifMenu.contains(e.target) && !notifBtn.contains(e.target)) {
      setMenu(false);
    }
  });

  notifMenu?.addEventListener("click", (e) => {
    e.stopPropagation();
  });

  // Modals (any element with data-open="modalId" opens it)
  document.querySelectorAll("[data-open]").forEach(btn => {
    btn.addEventListener("click", () => {
      const id = btn.getAttribute("data-open");
      const modal = document.getElementById(id);
      modal?.classList.add("open");
    });
  });

  // Close modal buttons (data-close)
  document.querySelectorAll("[data-close]").forEach(btn => {
    btn.addEventListener("click", () => {
      const id = btn.getAttribute("data-close");
      const modal = document.getElementById(id);
      modal?.classList.remove("open");
    });
  });

  // Click backdrop to close
  document.querySelectorAll(".modal").forEach(modal => {
    modal.addEventListener("click", (e) => {
      if(e.target === modal) modal.classList.remove("open");
    });
  });

  // ESC closes modals + dropdown + drawer
  document.addEventListener("keydown", (e) => {
    if(e.key !== "Escape") return;
    document.querySelectorAll(".modal.open").forEach(m => m.classList.remove("open"));
    setMenu(false);
    setDrawer(false);
  });

  // Toast helper
  window.showToast = (title, msg) => {
    const toast = document.getElementById("toast");
    if(!toast) return;
    toast.querySelector("strong").textContent = title;
    toast.querySelector("p").textContent = msg;
    toast.classList.add("show");
    clearTimeout(window.__toastT);
    window.__toastT = setTimeout(() => toast.classList.remove("show"), 2600);
  };
});

// Notifications auto-refresh every 10s (JS polling)
function refreshNotifications() {
    fetch('/pvv/public/notificationApi/latest')
        .then(res => res.json())
        .then(data => {

            const badge = document.querySelector('.badge');
            const menu = document.getElementById('notifMenu');

            if (!menu) return;

            menu.innerHTML = '';

            if (data.notifications.length === 0) {
                menu.innerHTML = `
                    <div class="item">
                        <b>No new notifications</b>
                        <small>You're all caught up.</small>
                    </div>
                `;
            } else {
                data.notifications.forEach(n => {

                    const item = document.createElement('div');
                    item.className = 'item';

                    item.innerHTML = `
                        <b>${n.type}</b>
                        <small>${n.message}</small>
                    `;

                    menu.appendChild(item);
                });

                const viewAll = document.createElement('div');
                viewAll.className = 'item';
                viewAll.innerHTML = `<a href="/pvv/public/notification/index">View all notifications</a>`;
                menu.appendChild(viewAll);
            }

            if (badge) badge.innerText = data.count;
        });
}

setInterval(refreshNotifications, 10000);