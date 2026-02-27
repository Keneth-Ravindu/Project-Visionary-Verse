document.addEventListener("DOMContentLoaded", () => {
  const riskBtn = document.getElementById("recalcRisk");
  const priorityBtn = document.getElementById("recalcPriority");

  const riskTable = document.getElementById("riskTable");
  const priorityTable = document.getElementById("priorityTable");

  function riskChip(level){
    const cls = level === "High" ? "danger" : level === "Medium" ? "warn" : "good";
    return `<span class="chip ${cls}"><span class="dot"></span>${level}</span>`;
  }

  function computeRisk(overdue, days){
    // Simple demo rule:
    // High if overdue>=3 OR days<=2
    // Medium if overdue>=1 OR days<=7
    // else Low
    if(overdue >= 3 || days <= 2) return "High";
    if(overdue >= 1 || days <= 7) return "Medium";
    return "Low";
  }

  riskBtn?.addEventListener("click", () => {
    riskTable.querySelectorAll("tbody tr").forEach(tr => {
      const overdue = parseInt(tr.dataset.overdue || "0", 10);
      const days = parseInt(tr.dataset.days || "999", 10);
      const level = computeRisk(overdue, days);
      tr.children[3].innerHTML = riskChip(level);
    });
    showToast("DSS updated", "Project delay risks recalculated (UI only).");
  });

  function scoreChip(score){
    return `<span class="chip info"><span class="dot"></span>${score}</span>`;
  }

  function computeScore(urgency, value, active){
    // Demo formula
    return Math.min(100, Math.round(urgency*5 + value*5 + active*3));
  }

  priorityBtn?.addEventListener("click", () => {
    priorityTable.querySelectorAll("tbody tr").forEach(tr => {
      const u = parseInt(tr.dataset.urgency || "0", 10);
      const v = parseInt(tr.dataset.value || "0", 10);
      const a = parseInt(tr.dataset.active || "0", 10);
      const score = computeScore(u,v,a);
      tr.children[4].innerHTML = scoreChip(score);
    });
    showToast("DSS updated", "Client priority scores updated (UI only).");
  });
});