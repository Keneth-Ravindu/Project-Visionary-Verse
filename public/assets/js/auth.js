document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("loginForm");
  const fillDemo = document.getElementById("fillDemo");

  fillDemo?.addEventListener("click", () => {
    document.getElementById("email").value = "demo@visionaryverse.com";
    document.getElementById("password").value = "password";
  });

  form?.addEventListener("submit", (e) => {
    e.preventDefault();
    const role = document.getElementById("role").value;

    // Frontend demo redirect by role
    if (role === "admin") window.location.href = "./dashboard-admin.html";
    if (role === "staff") window.location.href = "./dashboard-staff.html";
    if (role === "client") window.location.href = "./dashboard-client.html";
  });
});