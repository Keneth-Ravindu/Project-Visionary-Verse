document.addEventListener("DOMContentLoaded", () => {
  const projectSel = document.getElementById("reportProject");
  const btn = document.getElementById("generateReport");

  const projName = document.getElementById("projName");
  const progressBar = document.getElementById("progressBar");
  const progressText = document.getElementById("progressText");
  const doneCount = document.getElementById("doneCount");
  const remainCount = document.getElementById("remainCount");
  const statusTable = document.getElementById("statusTable");

  // Dummy dataset
  const data = {
    "SEO Optimization": { progress: 78, todo: 2, prog: 3, review: 1, done: 18 },
    "Ads Campaign": { progress: 62, todo: 3, prog: 4, review: 2, done: 10 },
    "Social Media Content": { progress: 70, todo: 2, prog: 2, review: 1, done: 14 },
    "Website Redesign": { progress: 35, todo: 6, prog: 3, review: 0, done: 4 }
  };

  function render(name){
    const d = data[name] || data["SEO Optimization"];
    projName.textContent = name;
    progressBar.style.width = `${d.progress}%`;
    progressText.textContent = `${d.progress}% complete`;

    doneCount.textContent = d.done;
    remainCount.textContent = d.todo + d.prog + d.review;

    statusTable.innerHTML = `
      <tr><td><span class="chip info"><span class="dot"></span>To Do</span></td><td>${d.todo}</td></tr>
      <tr><td><span class="chip good"><span class="dot"></span>In Progress</span></td><td>${d.prog}</td></tr>
      <tr><td><span class="chip warn"><span class="dot"></span>Review</span></td><td>${d.review}</td></tr>
      <tr><td><span class="chip good"><span class="dot"></span>Done</span></td><td>${d.done}</td></tr>
    `;
  }

  btn?.addEventListener("click", () => {
    const name = projectSel.value;
    render(name);
    showToast("Report generated", `${name} report updated (UI only).`);
  });

  render(projectSel.value);
});