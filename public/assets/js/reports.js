document.addEventListener("DOMContentLoaded", () => {
  const projectSelect = document.getElementById("projectSelect");
  const exportBtn = document.getElementById("exportBtn");

  const kpiProjects = document.getElementById("kpiProjects");
  const kpiTasks = document.getElementById("kpiTasks");
  const kpiDone = document.getElementById("kpiDone");
  const kpiRate = document.getElementById("kpiRate");

  // Dummy data for UI
  const data = {
    all: { projects: 3, tasks: 29, done: 18, todo: 6, progress: 5, review: 0, rate: 62 },
    seo: { projects: 1, tasks: 12, done: 8, todo: 2, progress: 2, review: 0, rate: 67 },
    ads: { projects: 1, tasks: 9, done: 4, todo: 2, progress: 3, review: 0, rate: 44 },
    social: { projects: 1, tasks: 8, done: 6, todo: 2, progress: 0, review: 0, rate: 75 },
  };

  function refresh(){
    const key = projectSelect.value;
    const d = data[key];
    kpiProjects.textContent = d.projects;
    kpiTasks.textContent = d.tasks;
    kpiDone.textContent = d.done;
    kpiRate.textContent = `${d.rate}%`;

    document.getElementById("statusSummary").innerHTML = `
      <div class="alert alert-info"><b>To Do:</b> ${d.todo}</div>
      <div class="alert alert-info"><b>In Progress:</b> ${d.progress}</div>
      <div class="alert alert-warn"><b>Review:</b> ${d.review}</div>
      <div class="alert alert-info"><b>Done:</b> ${d.done}</div>
    `;
  }

  projectSelect?.addEventListener("change", refresh);
  exportBtn?.addEventListener("click", () => alert("Export UI only. Later: generate PDF/CSV from PHP."));

  refresh();
});