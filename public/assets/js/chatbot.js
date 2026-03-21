document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("chatForm");
    const input = document.getElementById("chatInput");
    const messages = document.getElementById("chatMessages");
    const sendBtn = document.getElementById("chatSendBtn");

    if (!form || !input || !messages) return;

    function appendMessage(sender, text, type = "info", items = []) {
        const wrapper = document.createElement("div");
        wrapper.className = `callout ${type}`;
        wrapper.style.marginBottom = "12px";

        let html = `<strong>${escapeHtml(sender)}</strong><p>${escapeHtml(text)}</p>`;

        if (Array.isArray(items) && items.length > 0) {
            html += `<ul style="margin:8px 0 0 18px;">`;
            items.forEach(item => {
                html += `<li>${escapeHtml(item)}</li>`;
            });
            html += `</ul>`;
        }

        wrapper.innerHTML = html;
        messages.appendChild(wrapper);
        messages.scrollTop = messages.scrollHeight;
    }

    function appendTyping() {
        const wrapper = document.createElement("div");
        wrapper.className = "callout info";
        wrapper.id = "typingIndicator";
        wrapper.style.marginBottom = "12px";
        wrapper.innerHTML = `<strong>Assistant</strong><p>Typing...</p>`;
        messages.appendChild(wrapper);
        messages.scrollTop = messages.scrollHeight;
    }

    function removeTyping() {
        const typing = document.getElementById("typingIndicator");
        if (typing) typing.remove();
    }

    function escapeHtml(text) {
        const div = document.createElement("div");
        div.textContent = text;
        return div.innerHTML;
    }

    function attachQuickPromptEvents() {
        document.querySelectorAll(".quickPrompt").forEach(btn => {
            btn.addEventListener("click", () => {
                const text = btn.dataset.text || "";
                if (!text) return;
                input.value = text;
                form.requestSubmit();
            });
        });
    }

    async function sendMessage(message) {
        appendMessage("You", message, "warn");
        appendTyping();

        input.value = "";
        input.disabled = true;
        if (sendBtn) sendBtn.disabled = true;

        try {
            const formData = new FormData();
            formData.append("message", message);

            const response = await fetch("/pvv/public/chatbot/ask", {
                method: "POST",
                body: formData
            });

            const data = await response.json();
            removeTyping();

            if (data.status !== "success") {
                appendMessage("Assistant", data.message || "No response received.", "danger");
                return;
            }

            const bot = data.data || {};
            appendMessage(
                "Assistant",
                bot.reply || "No response received.",
                "info",
                bot.items || []
            );
        } catch (error) {
            removeTyping();
            appendMessage("Assistant", "Something went wrong while processing your request.", "danger");
        } finally {
            input.disabled = false;
            if (sendBtn) sendBtn.disabled = false;
            input.focus();
        }
    }

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const message = input.value.trim();
        if (!message) return;

        await sendMessage(message);
    });

    attachQuickPromptEvents();
});