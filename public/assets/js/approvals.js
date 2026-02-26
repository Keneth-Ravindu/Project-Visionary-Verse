document.addEventListener("DOMContentLoaded", () => {
  const roleView = document.getElementById("roleView");
  const openUploadBtn = document.getElementById("openUploadBtn");
  const uploadModal = document.getElementById("uploadModal");
  const closeUploadModal = document.getElementById("closeUploadModal");
  const cancelUpload = document.getElementById("cancelUpload");
  const uploadForm = document.getElementById("uploadForm");

  const statusFilter = document.getElementById("statusFilter");
  const approvalNote = document.getElementById("approvalNote");

  const feedbackModal = document.getElementById("feedbackModal");
  const closeFeedbackModal = document.getElementById("closeFeedbackModal");
  const cancelFeedback = document.getElementById("cancelFeedback");
  const feedbackForm = document.getElementById("feedbackForm");

  function openModal(m){ m.hidden = false; }
  function closeModal(m){ m.hidden = true; }

  function applyFilter(){
    const f = statusFilter.value;
    document.querySelectorAll(".d-row").forEach(row => {
      const st = row.dataset.status;
      row.style.display = (f === "all" || st === f) ? "" : "none";
    });
  }

  function setRoleUI(){
    const isClient = roleView.value === "client";
    openUploadBtn.style.display = isClient ? "none" : "";
    document.querySelectorAll(".approveBtn,.changesBtn").forEach(b => b.style.display = isClient ? "" : "none");
    approvalNote.textContent = isClient
      ? "Client View: approve/request changes (UI only)."
      : "Team View: upload deliverables (UI only).";
  }

  roleView?.addEventListener("change", setRoleUI);

  openUploadBtn?.addEventListener("click", () => openModal(uploadModal));
  closeUploadModal?.addEventListener("click", () => closeModal(uploadModal));
  cancelUpload?.addEventListener("click", () => closeModal(uploadModal));
  uploadModal?.addEventListener("click", (e) => { if(e.target === uploadModal) closeModal(uploadModal); });

  uploadForm?.addEventListener("submit", (e) => {
    e.preventDefault();
    alert("Uploaded (UI only). Later: save to DB + notify client.");
    closeModal(uploadModal);
    uploadForm.reset();
  });

  // Approve / Request changes
  document.querySelectorAll(".approveBtn").forEach(btn => {
    btn.addEventListener("click", (e) => {
      const row = e.target.closest(".d-row");
      row.dataset.status = "approved";
      const pill = row.querySelector(".statusPill");
      pill.className = "pill pill-good statusPill";
      pill.textContent = "Approved";
      approvalNote.textContent = "Approved (UI only). Later: store decision + notify team.";
      applyFilter();
    });
  });

  document.querySelectorAll(".changesBtn").forEach(btn => {
    btn.addEventListener("click", () => openModal(feedbackModal));
  });

  closeFeedbackModal?.addEventListener("click", () => closeModal(feedbackModal));
  cancelFeedback?.addEventListener("click", () => closeModal(feedbackModal));
  feedbackModal?.addEventListener("click", (e) => { if(e.target === feedbackModal) closeModal(feedbackModal); });

  feedbackForm?.addEventListener("submit", (e) => {
    e.preventDefault();
    alert("Changes requested (UI only). Later: save feedback + notify team.");
    closeModal(feedbackModal);
    feedbackForm.reset();
  });

  statusFilter?.addEventListener("change", applyFilter);

  setRoleUI();
  applyFilter();
});