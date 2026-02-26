// Basic UI interactions (frontend-only)
document.addEventListener("DOMContentLoaded", () => {
  const notifBtn = document.getElementById("notifBtn");
  const notifDropdown = document.getElementById("notifDropdown");
  const logoutBtn = document.getElementById("logoutBtn");

  if (notifBtn && notifDropdown) {
    notifBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      notifDropdown.hidden = !notifDropdown.hidden;
    });

    document.addEventListener("click", () => {
      notifDropdown.hidden = true;
    });

    notifDropdown.addEventListener("click", (e) => {
      e.stopPropagation();
    });
  }

  if (logoutBtn) {
    logoutBtn.addEventListener("click", () => {
      alert("Frontend UI only: later connect this to /logout in PHP.");
      // Later: window.location.href = "/logout";
    });
  }
});