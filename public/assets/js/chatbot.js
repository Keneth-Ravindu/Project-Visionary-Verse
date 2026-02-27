document.addEventListener("DOMContentLoaded", () => {
  const chatBox = document.getElementById("chatBox");
  const chatInput = document.getElementById("chatInput");
  const sendBtn = document.getElementById("sendBtn");
  const quickHelp = document.getElementById("quickHelp");

  const kb = [
    { keys: ["login","roles","auth"], ans: "You can login as Admin, Team Member, or Client. Backend will control access by role." },
    { keys: ["client","clients"], ans: "Clients module lets you add/edit clients and set status active/inactive." },
    { keys: ["project","projects","service"], ans: "Projects are created per client with service type (SEO, Ads, Social Media, Web Dev) and status tracking." },
    { keys: ["task","tasks","deadline","priority"], ans: "Tasks are created under projects, assigned to team members, with deadlines and priorities." },
    { keys: ["approval","approvals","deliverable"], ans: "Deliverables can be uploaded and clients can approve or request changes." },
    { keys: ["report","reports","dashboard"], ans: "Reports show project progress summary and task completion overview." },
    { keys: ["dss","risk","priority score"], ans: "DSS highlights delay risk indicators and client priority scores for decision support." },
    { keys: ["notification","notifications","realtime","ajax"], ans: "Real-time status notifications can be done using AJAX polling that checks for updates periodically." },
  ];

  function addMsg(text, who="me"){
    const div = document.createElement("div");
    div.className = `msg ${who}`;
    div.textContent = text;
    chatBox.appendChild(div);
    chatBox.scrollTop = chatBox.scrollHeight;
  }

  function botReply(userText){
    const t = userText.toLowerCase();
    const hit = kb.find(item => item.keys.some(k => t.includes(k)));
    return hit ? hit.ans : "I can help with clients, projects, tasks, approvals, reports, DSS, or notifications. Try asking: “How do approvals work?”";
  }

  function send(){
    const text = (chatInput.value || "").trim();
    if(!text) return;
    addMsg(text, "me");
    chatInput.value = "";
    setTimeout(() => addMsg(botReply(text), "bot"), 250);
  }

  sendBtn?.addEventListener("click", send);
  chatInput?.addEventListener("keydown", (e) => {
    if(e.key === "Enter") send();
  });

  quickHelp?.addEventListener("click", () => {
    addMsg("Try: Clients, Projects, Tasks, Approvals, Reports, DSS, Notifications.", "bot");
    showToast("Tip", "Ask about any module to see a response.");
  });
});