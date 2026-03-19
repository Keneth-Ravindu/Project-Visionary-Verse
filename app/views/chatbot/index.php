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

$quickPrompts = [];

if ($userRole === 'admin') {
    $quickPrompts = [
        'Overall system summary',
        'Show project status',
        'Project progress',
        'Project risk',
        'High priority client'
    ];
} elseif ($userRole === 'staff') {
    $quickPrompts = [
        'Show my tasks',
        'Show my overdue tasks',
        'What is due today?',
        'Project progress',
        'Project status'
    ];
} else {
    $quickPrompts = [
        'Project status',
        'Project progress',
        'Pending approvals',
        'Approvals summary'
    ];
}
?>

<div class="wrap page">
    <div class="card" style="max-width:900px; margin:0 auto;">
        <div class="card-head">
            <div>
                <h1 class="h1" style="margin:0;">Chatbot Assistant</h1>
                <p class="sub">Ask about tasks, progress, approvals, risks, and team workload.</p>
            </div>
        </div>

        <div id="chatMessages" class="chat-box" style="height:420px;">
            <div class="callout info" style="margin-bottom:12px;">
                <strong>Assistant</strong>
                <p>Hello! I am your Visionary Verse assistant. Use the quick prompts below or type your own question.</p>
            </div>
        </div>

        <div style="margin:16px 0 12px;">
            <strong>Quick Questions:</strong>
            <div id="quickPromptsWrap" style="margin-top:8px; display:flex; gap:8px; flex-wrap:wrap;">
                <?php foreach ($quickPrompts as $prompt): ?>
                    <button class="btn btn-outline quickPrompt" data-text="<?= htmlspecialchars($prompt) ?>">
                        <?= htmlspecialchars($prompt) ?>
                    </button>
                <?php endforeach; ?>
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
            <button type="submit" id="chatSendBtn" class="btn">Send</button>
        </form>
    </div>

    <div class="footer">© Visionary Verse — Chatbot</div>
</div>

<?php require_once "../app/views/layouts/footer.php"; ?>