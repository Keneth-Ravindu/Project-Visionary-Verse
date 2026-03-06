document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
    const fillDemoBtn = document.querySelector(".btn-ghost, .btn-secondary, button[type='button']");
    const emailInput = document.querySelector("#email, input[type='email']");
    const passwordInput = document.querySelector("#password, input[type='password']");
    const roleSelect = document.querySelector("#role, select");

    if (fillDemoBtn) {
        fillDemoBtn.addEventListener("click", () => {
            if (emailInput) emailInput.value = "demo@visionaryverse.com";
            if (passwordInput) passwordInput.value = "password";
        });
    }

    if (form) {
        form.addEventListener("submit", (e) => {
            e.preventDefault();

            const role = roleSelect ? roleSelect.value.toLowerCase() : "";

            if (role === "admin") {
                window.location.href = "/pvv/public/dashboard/admin";
            } else if (role === "client") {
                window.location.href = "/pvv/public/dashboard/client";
            } else if (role === "staff") {
                window.location.href = "/pvv/public/dashboard/staff";
            } else {
                alert("Please select a valid role.");
            }
        });
    }
});