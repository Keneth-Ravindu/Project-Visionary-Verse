document.addEventListener("DOMContentLoaded", () => {
  const fillDemo = document.getElementById("fillDemo");
  const form = document.getElementById("loginForm");

  fillDemo?.addEventListener("click", () => {
    document.getElementById("email").value = "demo@visionaryverse.com";
    document.getElementById("password").value = "password";
  });

  form?.addEventListener("submit", (e) => {
    e.preventDefault();
    const role = document.getElementById("role").value;
    if(role === "admin") location.href = "./dashboard-admin.html";
    if(role === "staff") location.href = "./dashboard-staff.html";
    if(role === "client") location.href = "./dashboard-client.html";
  });
});