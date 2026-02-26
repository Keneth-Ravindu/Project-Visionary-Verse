document.addEventListener("DOMContentLoaded", () => {
  const chatBox = document.getElementById("chatBox");
  const chatInput = document.getElementById("chatInput");
  const sendBtn = document.getElementById("sendBtn");
  const clearChat = document.getElementById("clearChat");

  const replies = [
    { k: ["approve", "deliverable"], r: "Go to Approvals → open the deliverable → click Approve or Request Changes." },
    { k: ["task", "status"], r: "Open Tasks → change the status dropdown (To Do / In Progress / Review / Done)." },
    { k: ["report", "progress"], r: "Open Reports → select a project → see progress bars and completion KPIs." },
    { k: ["risk", "delay"], r: "Open DSS → Project Delay Risk shows Low/Medium/High based on overdue tasks & days left." },
    { k: ["client", "priority"], r: "Client Priority Score (0–100) helps decide which client to focus on first." },
  ];

  function addMsg(text, who){
    const div = document.createElement("div");
    div.className = `msg ${who}`;
    div.textContent = text;
    chatBox.appendChild(div);
    chatBox.scrollTop = chatBox.scrollHeight;
  }

  function botReply(userText){
    const t = userText.toLowerCase();
    const found = replies.find(x => x.k.every(word => t.includes(word)));
    return found ? found.r : "I can help with tasks, approvals, reports, DSS (risk/priority). Try asking in those words.";
  }

  function send(){
    const text = (chatInput.value || "").trim();
    if(!text) return;
    addMsg(text, "me");
    chatInput.value = "";

    // UI-only response (later: call PHP endpoint)
    setTimeout(() => addMsg(botReply(text), "bot"), 250);
  }

  sendBtn?.addEventListener("click", send);
  chatInput?.addEventListener("keydown", (e) => {
    if (e.key === "Enter") send();
  });

  clearChat?.addEventListener("click", () => {
    chatBox.innerHTML = `<div class="msg bot">Hi! I’m the Visionary Verse assistant. Try: “How do I approve a deliverable?” or “Show my tasks”.</div>`;
  });
});