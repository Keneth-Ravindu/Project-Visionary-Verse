document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("chatForm");
  const input = document.getElementById("chatInput");
  const messages = document.getElementById("chatMessages");

  if (!form || !input || !messages) return;

  function appendMessage(sender, text, type = "info") {
    const wrapper = document.createElement("div");
    wrapper.className = `callout ${type}`;
    wrapper.style.marginBottom = "12px";

    wrapper.innerHTML = `
      <strong>${sender}</strong>
      <p>${text}</p>
    `;

    messages.appendChild(wrapper);
    messages.scrollTop = messages.scrollHeight;
  }

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const message = input.value.trim();
    if (!message) return;

    appendMessage("You", message, "warn");
    input.value = "";

    try {
      const formData = new FormData();
      formData.append("message", message);

      const response = await fetch("/pvv/public/chatbot/ask", {
        method: "POST",
        body: formData
      });

      const data = await response.json();
      appendMessage("Assistant", data.reply || "No response received.", "info");
    } catch (error) {
      appendMessage("Assistant", "Something went wrong while processing your request.", "danger");
    }
  });
});

document.querySelectorAll(".quickPrompt").forEach(btn => {
  btn.addEventListener("click", () => {
    const text = btn.dataset.text;
    document.getElementById("chatInput").value = text;
    document.getElementById("chatForm").dispatchEvent(new Event("submit"));
  });
});