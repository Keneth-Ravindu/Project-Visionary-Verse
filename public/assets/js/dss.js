document.addEventListener("DOMContentLoaded", () => {
  const projectPick = document.getElementById("projectPick");
  const clientPick = document.getElementById("clientPick");
  const recalcBtn = document.getElementById("recalcBtn");

  const projects = {
    ads: { total: 9, done: 4, overdue: 3, days: 3 },
    seo: { total: 12, done: 8, overdue: 1, days: 5 },
    social: { total: 8, done: 6, overdue: 0, days: 8 },
  };

  const clients = {
    nova: { budget: "High", urgency: "High", tier: "VIP", complexity: "Medium", score: 86, points: { b:36,u:27,t:18,c:5 } },
    glow: { budget: "Medium", urgency: "Medium", tier: "Regular", complexity: "Medium", score: 63, points: { b:24,u:18,t:12,c:9 } },
    sky:  { budget: "High", urgency: "Medium", tier: "Regular", complexity: "High", score: 72, points: { b:34,u:18,t:12,c:8 } },
  };

  function riskLevel({ total, done, overdue, days }){
    const doneRate = total ? (done/total) : 0;
    if (overdue >= 2 && days <= 3) return { level:"High Risk", type:"danger", reason:`${overdue} overdue tasks and deadline is near.` };
    if (overdue >= 1 || (days <= 5 && doneRate < 0.7)) return { level:"Medium Risk", type:"warn", reason:`Some overdue/slow progress detected.` };
    return { level:"Low Risk", type:"info", reason:`Progress looks healthy.` };
  }

  function refresh(){
    // project risk
    const p = projects[projectPick.value];
    document.getElementById("mTotal").textContent = p.total;
    document.getElementById("mDone").textContent = p.done;
    document.getElementById("mOverdue").textContent = p.overdue;
    document.getElementById("mDays").textContent = p.days;

    const r = riskLevel(p);
    const riskBox = document.getElementById("riskBox");
    riskBox.className = `alert alert-${r.type}`;
    document.getElementById("riskTitle").textContent = r.level;
    document.getElementById("riskReason").textContent = r.reason;

    // client priority
    const c = clients[clientPick.value];
    document.getElementById("priorityScore").textContent = c.score;

    document.getElementById("fBudget").textContent = c.budget;
    document.getElementById("fUrgency").textContent = c.urgency;
    document.getElementById("fTier").textContent = c.tier;
    document.getElementById("fComplex").textContent = c.complexity;

    document.getElementById("pBudget").textContent = c.points.b;
    document.getElementById("pUrgency").textContent = c.points.u;
    document.getElementById("pTier").textContent = c.points.t;
    document.getElementById("pComplex").textContent = c.points.c;
  }

  projectPick?.addEventListener("change", refresh);
  clientPick?.addEventListener("change", refresh);
  recalcBtn?.addEventListener("click", () => alert("Recalculate UI only. Later: call /api/dss endpoints."));

  refresh();
});