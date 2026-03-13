<?php
$title = 'Chatbot';
$pageScript = 'chatbot.js';
require_once "../app/views/layouts/header.php";
?>

<?php
$pageTitle = 'Chatbot';
$activeTab = 'chatbot';
$userRole = $_SESSION['user_role'] ?? 'admin';
$roleLabel = ucfirst($userRole);
require_once "../app/views/partials/topnav.php";
?>

<div class="wrap page">
  <div class="card" style="max-width:900px; margin:0 auto;">
    <div class="card-head">
      <div>
        <h1 class="h1" style="margin:0;">AI Project Assistant</h1>
        <p class="sub">Ask about tasks, overdue work, project risk, priority clients, and project status.</p>
      </div>
    </div>

    <div id="chatMessages" style="height:420px; overflow-y:auto; border:1px solid rgba(0,0,0,0.08); border-radius:16px; padding:16px; background:#fff;">
      <div class="callout info" style="margin-bottom:12px;">
        <strong>Assistant</strong>
        <p>Hello! Ask me things like "show my tasks", "overdue tasks", "project risk", or "project status".</p>
      </div>
    </div>

    <div style="margin-bottom:15px;">
    <strong>Quick Questions:</strong>
    <div style="margin-top:8px; display:flex; gap:8px; flex-wrap:wrap;">
        
        <button class="btn btn-outline quickPrompt" data-text="my tasks">
        My Tasks
        </button>

        <button class="btn btn-outline quickPrompt" data-text="my overdue tasks">
        My Overdue Tasks
        </button>

        <button class="btn btn-outline quickPrompt" data-text="pending approvals">
        Pending Approvals
        </button>

        <button class="btn btn-outline quickPrompt" data-text="project risk">
        Project Risk
        </button>

        <button class="btn btn-outline quickPrompt" data-text="project status">
        Project Status
        </button>

    </div>
    </div>


    <form id="chatForm" style="margin-top:16px; display:flex; gap:10px; align-items:center;">
      <input
        type="text"
        id="chatInput"
        class="input"
        placeholder="Type your question..."
        style="flex:1;"
        required
      />
      <button type="submit" class="btn">Send</button>
    </form>
  </div>

  <div class="footer">© Visionary Verse — Chatbot</div>
</div>

<?php require_once "../app/views/layouts/footer.php"; ?>