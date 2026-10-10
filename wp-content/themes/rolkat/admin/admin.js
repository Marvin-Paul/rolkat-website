let appData = null;
let currentTab = "overview";
let knownDocuments = []; // cache of public documents from /api/content

function getAuthToken() {
  return sessionStorage.getItem("rolkat_admin_token") || localStorage.getItem("rolkat_admin_token");
}

function setAuthToken(token, remember = false) {
  if (remember) {
    localStorage.setItem("rolkat_admin_token", token);
  } else {
    sessionStorage.setItem("rolkat_admin_token", token);
  }
}

function clearAuthToken() {
  sessionStorage.removeItem("rolkat_admin_token");
  localStorage.removeItem("rolkat_admin_token");
}

function showToast(message, type = "info") {
  const container = document.getElementById("toast-container");
  if (!container) return;
  const toast = document.createElement("div");
  toast.className = `toast toast-${type}`;
  toast.innerHTML = `<span>${message}</span>`;
  container.appendChild(toast);
  setTimeout(() => {
    toast.style.opacity = "0";
    toast.style.transform = "translateX(100%)";
    setTimeout(() => toast.remove(), 200);
  }, 3500);
}

// Authentication
async function handleLogin(e) {
  e.preventDefault();
  const username = document.getElementById("login-username").value.trim();
  const password = document.getElementById("login-password").value;
  const remember = document.getElementById("login-remember").checked;

  try {
    const res = await fetch("/api/admin/login", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ username, password }),
    });
    const data = await res.json();
    if (!res.ok) {
      alert(data.error || "Login failed");
      return;
    }
    setAuthToken(data.token, remember);
    document.getElementById("login-screen").style.display = "none";
    document.getElementById("admin-app").style.display = "flex";
    loadAdminData();
    showToast("Welcome back to ROLKAT Admin!", "success");
  } catch (err) {
    alert("Connection error: " + err.message);
  }
}

function handleLogout() {
  clearAuthToken();
  document.getElementById("admin-app").style.display = "none";
  document.getElementById("login-screen").style.display = "flex";
  showToast("Logged out successfully.", "info");
}

// Data Fetching
async function loadAdminData() {
  const token = getAuthToken();
  if (!token) {
    document.getElementById("login-screen").style.display = "flex";
    document.getElementById("admin-app").style.display = "none";
    return;
  }

  try {
    const res = await fetch("/api/admin/data", {
      headers: { Authorization: `Bearer ${token}` },
    });
    if (res.status === 401 || res.status === 403) {
      handleLogout();
      return;
    }
    appData = await res.json();
    document.getElementById("login-screen").style.display = "none";
    document.getElementById("admin-app").style.display = "flex";
    renderAll();
  } catch (err) {
    showToast("Failed to load admin data: " + err.message, "error");
  }
}

function renderAll() {
  if (!appData) return;
  knownDocuments = appData.documents || [];
  renderOverview();
  renderSettingsForm();
  renderImagesTab();
  renderDocumentsTab();
  renderProperties();
  renderServices();
  renderTeam();
  renderSubmissions();
  if (window.lucide) {
    window.lucide.createIcons();
  }
}

// Overview
function renderOverview() {
  const propCount = (appData.properties || []).length;
  const servCount = (appData.services || []).length;
  const teamCount = (appData.team || []).length;
  const subs = appData.submissions || [];
  const newSubsCount = subs.filter((s) => s.status === "New").length;
  const docCount = (knownDocuments || []).length;

  document.getElementById("stat-properties").textContent = propCount;
  document.getElementById("stat-services").textContent = servCount;
  document.getElementById("stat-team").textContent = teamCount;
  document.getElementById("stat-enquiries").textContent = subs.length;
  document.getElementById("badge-enquiries").textContent = newSubsCount > 0 ? newSubsCount : "";
  const badgeDocs = document.getElementById("badge-documents");
  if (badgeDocs) badgeDocs.textContent = docCount > 0 ? docCount : "";

  // Recent enquiries preview
  const recentTable = document.getElementById("recent-enquiries-table");
  if (recentTable) {
    if (subs.length === 0) {
      recentTable.innerHTML = `<tr><td colspan="5" style="text-align:center;color:#666;">No enquiries received yet.</td></tr>`;
    } else {
      const recent = [...subs].reverse().slice(0, 5);
      recentTable.innerHTML = recent
        .map(
          (s) => `
        <tr>
          <td><strong>${escapeHtml(s.name)}</strong></td>
          <td>${escapeHtml(s.email)}</td>
          <td>${escapeHtml(s.service || "General")}</td>
          <td><span class="status-pill status-${s.status === "New" ? "new" : "available"}">${escapeHtml(s.status)}</span></td>
          <td>${new Date(s.date).toLocaleDateString()}</td>
        </tr>
      `
        )
        .join("");
    }
  }
}

// Settings Form
function renderSettingsForm() {
  const s = appData.settings || {};
  document.getElementById("setting-siteName").value = s.siteName || "";
  document.getElementById("setting-tagline").value = s.tagline || "";
  document.getElementById("setting-phone").value = s.phone || "";
  document.getElementById("setting-email").value = s.email || "";
  document.getElementById("setting-officeAddress").value = s.officeAddress || "";
  document.getElementById("setting-workingHours").value = s.workingHours || "";
  document.getElementById("setting-whatsappNumber").value = s.whatsappNumber || "";
  document.getElementById("setting-mapEmbedUrl").value = s.mapEmbedUrl || "";
  document.getElementById("setting-facebook").value = s.facebook || "";
  document.getElementById("setting-instagram").value = s.instagram || "";
  document.getElementById("setting-linkedin").value = s.linkedin || "";
  document.getElementById("setting-heroEyebrow").value = s.heroEyebrow || "";
  document.getElementById("setting-heroTitle").value = s.heroTitle || "";
  document.getElementById("setting-heroText").value = s.heroText || "";

  const calc = s.calculatorDefaults || {};
  document.getElementById("setting-calcAmount").value = calc.amount || 5000000;
  document.getElementById("setting-calcRate").value = calc.rate || 18;
  document.getElementById("setting-calcTerm").value = calc.term || 3;

  document.getElementById("setting-adminUsername").value = s.adminUsername || "admin";
  document.getElementById("setting-adminPassword").value = s.adminPassword || "";
}

async function handleSaveSettings(e) {
  e.preventDefault();
  const token = getAuthToken();
  const settings = {
    siteName: document.getElementById("setting-siteName").value.trim(),
    tagline: document.getElementById("setting-tagline").value.trim(),
    phone: document.getElementById("setting-phone").value.trim(),
    email: document.getElementById("setting-email").value.trim(),
    officeAddress: document.getElementById("setting-officeAddress").value.trim(),
    workingHours: document.getElementById("setting-workingHours").value.trim(),
    whatsappNumber: document.getElementById("setting-whatsappNumber").value.trim(),
    mapEmbedUrl: document.getElementById("setting-mapEmbedUrl").value.trim(),
    facebook: document.getElementById("setting-facebook").value.trim(),
    instagram: document.getElementById("setting-instagram").value.trim(),
    linkedin: document.getElementById("setting-linkedin").value.trim(),
    heroEyebrow: document.getElementById("setting-heroEyebrow").value.trim(),
    heroTitle: document.getElementById("setting-heroTitle").value.trim(),
    heroText: document.getElementById("setting-heroText").value.trim(),
    calculatorDefaults: {
      amount: Number(document.getElementById("setting-calcAmount").value) || 5000000,
      rate: Number(document.getElementById("setting-calcRate").value) || 18,
      term: Number(document.getElementById("setting-calcTerm").value) || 3,
    },
    adminUsername: document.getElementById("setting-adminUsername").value.trim(),
    adminPassword: document.getElementById("setting-adminPassword").value.trim() || undefined,
  };

  try {
    const res = await fetch("/api/admin/settings", {
      method: "PUT",
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      },
      body: JSON.stringify(settings),
    });
    if (!res.ok) throw new Error("Could not save settings");
    showToast("Settings updated and saved!", "success");
    loadAdminData();
  } catch (err) {
    showToast("Error saving settings: " + err.message, "error");
  }
}

// Properties
function renderProperties() {
  const container = document.getElementById("properties-list");
  if (!container) return;
  const list = appData.properties || [];
  if (list.length === 0) {
    container.innerHTML = `<tr><td colspan="5" style="text-align:center;color:#666;">No properties found. Add your first listing!</td></tr>`;
    return;
  }
  container.innerHTML = list
    .map((p) => {
      let statusClass = "status-available";
      if (p.status === "Under offer") statusClass = "status-under-offer";
      if (p.status === "Sold / Rented" || p.status === "Sold" || p.status === "Rented") statusClass = "status-sold";

      const thumb = p.image
        ? `<div class="admin-thumb" style="background-image:url('${escapeHtml(p.image)}');"></div>`
        : `<div class="admin-thumb admin-thumb--empty" data-lucide="image"></div>`;

      return `
      <tr>
        <td>
          <div class="title-with-thumb">
            ${thumb}
            <div>
              <strong>${escapeHtml(p.title)}</strong>
              <div style="font-size:0.8rem;color:#666;">${escapeHtml(p.description || "")}</div>
            </div>
          </div>
        </td>
        <td>${escapeHtml(p.location || "Kampala")}</td>
        <td><strong>${escapeHtml(p.price || "Contact for price")}</strong></td>
        <td><span class="status-pill ${statusClass}">${escapeHtml(p.status || "Available")}</span></td>
        <td>
          <button class="btn btn-secondary btn-sm" onclick="openPropertyModal('${p.id}')" style="display:inline-flex;align-items:center;gap:4px;"><i data-lucide="pencil" style="width:12px;height:12px;"></i>Edit</button>
          <button class="btn btn-danger btn-sm" onclick="deleteProperty('${p.id}')" style="display:inline-flex;align-items:center;gap:4px;"><i data-lucide="trash-2" style="width:12px;height:12px;"></i>Delete</button>
        </td>
      </tr>
    `;
    })
    .join("");
}

function openPropertyModal(id = null) {
  const modal = document.getElementById("property-modal");
  const form = document.getElementById("property-form");
  form.reset();
  document.getElementById("prop-modal-id").value = id || "";
  document.getElementById("prop-image").value = "";

  let existingImage = "";
  if (id) {
    const item = (appData.properties || []).find((p) => p.id === id);
    if (item) {
      document.getElementById("prop-modal-title").textContent = "Edit Property";
      document.getElementById("prop-title").value = item.title;
      document.getElementById("prop-location").value = item.location;
      document.getElementById("prop-price").value = item.price;
      document.getElementById("prop-status").value = item.status;
      document.getElementById("prop-type").value = item.type || "Sale";
      document.getElementById("prop-description").value = item.description || "";
      existingImage = item.image || "";
    }
  } else {
    document.getElementById("prop-modal-title").textContent = "Add Property Listing";
  }

  setupImageUploader(
    modal.querySelector(".image-uploader"),
    existingImage,
    (base64) => {
      document.getElementById("prop-image").value = base64 || "";
    }
  );

  modal.classList.add("active");
  if (window.lucide) window.lucide.createIcons();
}

async function handlePropertySubmit(e) {
  e.preventDefault();
  const token = getAuthToken();
  const id = document.getElementById("prop-modal-id").value;
  const payload = {
    title: document.getElementById("prop-title").value.trim(),
    location: document.getElementById("prop-location").value.trim(),
    price: document.getElementById("prop-price").value.trim(),
    status: document.getElementById("prop-status").value,
    type: document.getElementById("prop-type").value,
    description: document.getElementById("prop-description").value.trim(),
    image: document.getElementById("prop-image").value || "",
  };

  const method = id ? "PUT" : "POST";
  const url = id ? `/api/admin/properties/${id}` : "/api/admin/properties";

  try {
    const res = await fetch(url, {
      method,
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      },
      body: JSON.stringify(payload),
    });
    if (!res.ok) throw new Error("Failed to save property");
    closeModal("property-modal");
    showToast(id ? "Property listing updated!" : "New property listing added!", "success");
    loadAdminData();
  } catch (err) {
    showToast(err.message, "error");
  }
}

async function deleteProperty(id) {
  if (!confirm("Are you sure you want to delete this property listing?")) return;
  const token = getAuthToken();
  try {
    const res = await fetch(`/api/admin/properties/${id}`, {
      method: "DELETE",
      headers: { Authorization: `Bearer ${token}` },
    });
    if (!res.ok) throw new Error("Failed to delete property");
    showToast("Property deleted.", "info");
    loadAdminData();
  } catch (err) {
    showToast(err.message, "error");
  }
}

// Services
function renderServices() {
  const container = document.getElementById("services-list");
  if (!container) return;
  const list = appData.services || [];
  if (list.length === 0) {
    container.innerHTML = `<tr><td colspan="4" style="text-align:center;color:#666;">No services found. Add your first service!</td></tr>`;
    return;
  }
  container.innerHTML = list
    .map(
      (s) => `
    <tr>
      <td><span class="status-pill status-new">${escapeHtml(s.number || "01")}</span></td>
      <td><strong>${escapeHtml(s.title)}</strong></td>
      <td><span style="font-size:0.88rem;color:#444;">${escapeHtml(s.description)}</span></td>
      <td>
        <button class="btn btn-secondary btn-sm" onclick="openServiceModal('${s.id}')" style="display:inline-flex;align-items:center;gap:4px;"><i data-lucide="pencil" style="width:12px;height:12px;"></i>Edit</button>
        <button class="btn btn-danger btn-sm" onclick="deleteService('${s.id}')" style="display:inline-flex;align-items:center;gap:4px;"><i data-lucide="trash-2" style="width:12px;height:12px;"></i>Delete</button>
      </td>
    </tr>
  `
    )
    .join("");
}

function openServiceModal(id = null) {
  const modal = document.getElementById("service-modal");
  const form = document.getElementById("service-form");
  form.reset();
  document.getElementById("serv-modal-id").value = id || "";

  if (id) {
    const item = (appData.services || []).find((s) => s.id === id);
    if (item) {
      document.getElementById("serv-modal-title").textContent = "Edit Service";
      document.getElementById("serv-number").value = item.number;
      document.getElementById("serv-title").value = item.title;
      document.getElementById("serv-description").value = item.description;
    }
  } else {
    document.getElementById("serv-modal-title").textContent = "Add Service";
  }
  modal.classList.add("active");
}

async function handleServiceSubmit(e) {
  e.preventDefault();
  const token = getAuthToken();
  const id = document.getElementById("serv-modal-id").value;
  const payload = {
    number: document.getElementById("serv-number").value.trim(),
    title: document.getElementById("serv-title").value.trim(),
    description: document.getElementById("serv-description").value.trim(),
  };

  const method = id ? "PUT" : "POST";
  const url = id ? `/api/admin/services/${id}` : "/api/admin/services";

  try {
    const res = await fetch(url, {
      method,
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      },
      body: JSON.stringify(payload),
    });
    if (!res.ok) throw new Error("Failed to save service");
    closeModal("service-modal");
    showToast(id ? "Service updated!" : "Service added!", "success");
    loadAdminData();
  } catch (err) {
    showToast(err.message, "error");
  }
}

async function deleteService(id) {
  if (!confirm("Are you sure you want to delete this service?")) return;
  const token = getAuthToken();
  try {
    const res = await fetch(`/api/admin/services/${id}`, {
      method: "DELETE",
      headers: { Authorization: `Bearer ${token}` },
    });
    if (!res.ok) throw new Error("Failed to delete service");
    showToast("Service deleted.", "info");
    loadAdminData();
  } catch (err) {
    showToast(err.message, "error");
  }
}

// Team Members
function renderTeam() {
  const container = document.getElementById("team-list");
  if (!container) return;
  const list = appData.team || [];
  if (list.length === 0) {
    container.innerHTML = `<tr><td colspan="6" style="text-align:center;color:#666;">No team members found. Add your first profile with image card &amp; PDF.</td></tr>`;
    return;
  }
  container.innerHTML = list
    .map((m) => {
      const thumb = m.image
        ? `<div class="admin-thumb" style="background-image:url('${escapeHtml(m.image)}');"></div>`
        : `<div class="admin-thumb admin-thumb--empty" data-lucide="user"></div>`;
      const hasImg = m.image && String(m.image).length > 0;
      const hasPdf = (m.pdf && String(m.pdf).length > 0) || (m.pdfUrl && String(m.pdfUrl).length > 0);
      const pdfHref = (m.pdf && String(m.pdf).length > 0) ? m.pdf : (m.pdfUrl || "");

      return `
    <tr>
      <td>
        <div class="title-with-thumb">
          ${thumb}
          <div><strong>${escapeHtml(m.name)}</strong></div>
        </div>
      </td>
      <td><span class="status-pill status-new">${escapeHtml(m.role || "")}</span></td>
      <td><span style="font-size:0.85rem;color:#555;">${escapeHtml(m.bio || "")}</span></td>
      <td>
        ${hasImg
          ? `<span class="status-pill status-available" style="display:inline-flex;align-items:center;gap:4px;"><i data-lucide="image" style="width:12px;height:12px;"></i>Image</span>`
          : `<span class="status-pill" style="background:#f1f5f9;color:#94a3b8;">No image</span>`}
        <div style="margin-top:4px;">
          ${hasPdf
            ? `<a href="${escapeHtml(pdfHref)}" target="_blank" rel="noopener noreferrer" class="status-pill status-new" style="display:inline-flex;align-items:center;gap:4px;text-decoration:none;"><i data-lucide="file-text" style="width:12px;height:12px;"></i>PDF</a>`
            : `<span class="status-pill" style="background:#f1f5f9;color:#94a3b8;">No PDF</span>`}
        </div>
      </td>
      <td>
        <button class="btn btn-secondary btn-sm" onclick="openTeamModal('${m.id}')" style="display:inline-flex;align-items:center;gap:4px;"><i data-lucide="pencil" style="width:12px;height:12px;"></i>Edit</button>
        <button class="btn btn-danger btn-sm" onclick="deleteTeam('${m.id}')" style="display:inline-flex;align-items:center;gap:4px;"><i data-lucide="trash-2" style="width:12px;height:12px;"></i>Delete</button>
      </td>
    </tr>
  `;
    })
    .join("");
}

function openTeamModal(id = null) {
  const modal = document.getElementById("team-modal");
  const form = document.getElementById("team-form");
  form.reset();
  document.getElementById("team-modal-id").value = id || "";
  document.getElementById("team-image").value = "";
  document.getElementById("team-pdf").value = "";

  let existingImage = "";
  let existingPdf = "";
  let existingPdfIsUrl = false;
  if (id) {
    const item = (appData.team || []).find((m) => m.id === id);
    if (item) {
      document.getElementById("team-modal-title").textContent = "Edit Team Member";
      document.getElementById("team-name").value = item.name;
      document.getElementById("team-role").value = item.role;
      document.getElementById("team-bio").value = item.bio;
      existingImage = item.image || "";
      // Determine if stored as base64 pdf or a URL like /documents/...
      if (item.pdf && String(item.pdf).length > 0) {
        existingPdf = item.pdf;
        existingPdfIsUrl = !String(item.pdf).startsWith("data:");
      } else if (item.pdfUrl && String(item.pdfUrl).length > 0) {
        existingPdf = item.pdfUrl;
        existingPdfIsUrl = true;
      }
    }
  } else {
    document.getElementById("team-modal-title").textContent = "Add Team Member";
  }

  // --- Image card uploader (existing pattern) ---
  setupImageUploader(
    modal.querySelector(".image-uploader"),
    existingImage,
    (base64) => {
      document.getElementById("team-image").value = base64 || "";
    }
  );

  // --- Company document dropdown ---
  const docSelect = document.getElementById("team-pdf-doc");
  if (docSelect) {
    const currentOptions = `<option value="">— No linked document —</option>` +
      (knownDocuments || []).map((d) => `<option value="${escapeHtml(d.url)}">${escapeHtml(d.label)}</option>`).join("");
    docSelect.innerHTML = currentOptions;
    // If existing pdf is a URL matching a known doc, select it
    if (existingPdfIsUrl) {
      const match = (knownDocuments || []).find((d) => d.url === existingPdf);
      if (match) docSelect.value = match.url;
    }
    docSelect.onchange = () => {
      // If user picks a doc, clear the custom upload
      if (docSelect.value) {
        document.getElementById("team-pdf").value = docSelect.value;
        updatePdfPreview(docSelect.value, false, getPdfLabelFromUrl(docSelect.value));
      } else if (!document.getElementById("team-pdf").value.startsWith("data:")) {
        document.getElementById("team-pdf").value = "";
        updatePdfPreview("", false, "");
      }
    };
  }

  // --- Custom PDF upload setup ---
  const pdfInput = document.getElementById("team-pdf-input");
  const pdfBrowseBtn = document.getElementById("team-pdf-browse");
  const pdfClearBtn = document.getElementById("team-pdf-clear");
  const pdfPreviewWrap = document.querySelector("#team-pdf-uploader [data-pdf-preview]");
  const pdfPreviewName = document.querySelector("#team-pdf-uploader [data-pdf-name]");

  function getPdfLabelFromUrl(url) {
    const found = (knownDocuments || []).find((d) => d.url === url);
    return found ? found.label : "";
  }

  function updatePdfPreview(val, isDataUrl, displayName) {
    if (!pdfPreviewWrap) return;
    const icon = pdfPreviewWrap.querySelector("svg, i");
    const name = pdfPreviewWrap.querySelector("[data-pdf-name]") || pdfPreviewName;
    if (val) {
      if (icon) {
        icon.setAttribute("data-lucide", "file-check");
        icon.style.color = "#16A34A";
        icon.style.opacity = "1";
      }
      if (name) {
        name.textContent = displayName || (isDataUrl ? "Custom PDF (embedded)" : "Linked document");
        name.style.fontWeight = "600";
        name.style.color = "#0F172A";
      }
    } else {
      if (icon) {
        icon.setAttribute("data-lucide", "file-off");
        icon.style.color = "";
        icon.style.opacity = "0.5";
      }
      if (name) {
        name.textContent = "No PDF selected";
        name.style.fontWeight = "";
        name.style.color = "";
      }
    }
    if (window.lucide && typeof window.lucide.createIcons === "function") {
      window.lucide.createIcons();
    }
  }

  // Prime initial preview
  if (existingPdf) {
    const label = existingPdfIsUrl ? getPdfLabelFromUrl(existingPdf) : "Custom PDF (embedded)";
    updatePdfPreview(existingPdf, !existingPdfIsUrl, label);
    document.getElementById("team-pdf").value = existingPdf;
  } else {
    updatePdfPreview("", false, "");
  }

  if (pdfBrowseBtn && pdfInput) {
    pdfBrowseBtn.addEventListener("click", (e) => {
      e.preventDefault();
      pdfInput.click();
    });
  }
  if (pdfInput) {
    pdfInput.addEventListener("change", async () => {
      const f = pdfInput.files && pdfInput.files[0];
      if (!f) return;
      try {
        if (f.type && f.type !== "application/pdf") {
          throw new Error("Please select a PDF file.");
        }
        const dataUrl = await fileToDataURL(f);
        document.getElementById("team-pdf").value = dataUrl || "";
        // Clear the company doc selection (custom upload takes precedence)
        if (docSelect) docSelect.value = "";
        updatePdfPreview(dataUrl, true, f.name);
      } catch (err) { showToast(err.message, "error"); }
      pdfInput.value = "";
    });
  }
  if (pdfClearBtn) {
    pdfClearBtn.addEventListener("click", (e) => {
      e.preventDefault();
      document.getElementById("team-pdf").value = "";
      if (docSelect) docSelect.value = "";
      updatePdfPreview("", false, "");
    });
  }

  modal.classList.add("active");
  if (window.lucide) window.lucide.createIcons();
}

async function handleTeamSubmit(e) {
  e.preventDefault();
  const token = getAuthToken();
  const id = document.getElementById("team-modal-id").value;
  const pdfVal = document.getElementById("team-pdf").value || "";
  const payload = {
    name: document.getElementById("team-name").value.trim(),
    role: document.getElementById("team-role").value.trim(),
    bio: document.getElementById("team-bio").value.trim(),
    image: document.getElementById("team-image").value || "",
    pdf: pdfVal,
    // Backwards-compat: also set pdfUrl if it's a linked (non-data) URL
    pdfUrl: (pdfVal && !String(pdfVal).startsWith("data:")) ? pdfVal : "",
  };

  const method = id ? "PUT" : "POST";
  const url = id ? `/api/admin/team/${id}` : "/api/admin/team";

  try {
    const res = await fetch(url, {
      method,
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      },
      body: JSON.stringify(payload),
    });
    if (!res.ok) throw new Error("Failed to save team member");
    closeModal("team-modal");
    showToast(id ? "Team profile (with image card & PDF) updated!" : "Team profile (with image card & PDF) created!", "success");
    loadAdminData();
  } catch (err) {
    showToast(err.message, "error");
  }
}

async function deleteTeam(id) {
  if (!confirm("Are you sure you want to delete this team member?")) return;
  const token = getAuthToken();
  try {
    const res = await fetch(`/api/admin/team/${id}`, {
      method: "DELETE",
      headers: { Authorization: `Bearer ${token}` },
    });
    if (!res.ok) throw new Error("Failed to delete team member");
    showToast("Team member deleted.", "info");
    loadAdminData();
  } catch (err) {
    showToast(err.message, "error");
  }
}

// Submissions / Enquiries
function renderSubmissions() {
  const container = document.getElementById("submissions-list");
  if (!container) return;
  const list = appData.submissions || [];
  if (list.length === 0) {
    container.innerHTML = `<tr><td colspan="7" style="text-align:center;color:#666;">No enquiries submitted yet.</td></tr>`;
    return;
  }

  container.innerHTML = [...list]
    .reverse()
    .map(
      (sub) => `
    <tr>
      <td>${new Date(sub.date).toLocaleString()}</td>
      <td><strong>${escapeHtml(sub.name)}</strong></td>
      <td><a href="mailto:${escapeHtml(sub.email)}" style="color:var(--admin-primary);">${escapeHtml(sub.email)}</a></td>
      <td>${escapeHtml(sub.phone || "—")}</td>
      <td>${escapeHtml(sub.subject || sub.service || "—")}</td>
      <td><div style="max-width:240px;font-size:0.85rem;white-space:normal;">${escapeHtml(sub.message)}</div></td>
      <td>
        <span class="status-pill status-${sub.status === "New" ? "new" : "available"}">${escapeHtml(sub.status)}</span>
        <div style="margin-top:6px;display:flex;gap:4px;">
          <button class="btn btn-secondary btn-sm" onclick="toggleSubStatus('${sub.id}')" style="display:inline-flex;align-items:center;gap:4px;">
            <i data-lucide="${sub.status === "New" ? "check" : "rotate-ccw"}" style="width:12px;height:12px;"></i>
            ${sub.status === "New" ? "Mark Handled" : "Mark New"}
          </button>
          <button class="btn btn-danger btn-sm" onclick="deleteSubmission('${sub.id}')" title="Delete" style="display:inline-flex;align-items:center;justify-content:center;padding:5px 8px;">
            <i data-lucide="trash-2" style="width:12px;height:12px;"></i>
          </button>
        </div>
      </td>
    </tr>
  `
    )
    .join("");
}

async function toggleSubStatus(id) {
  const token = getAuthToken();
  const sub = (appData.submissions || []).find((s) => s.id === id);
  if (!sub) return;
  const newStatus = sub.status === "New" ? "Handled" : "New";

  try {
    const res = await fetch(`/api/admin/submissions/${id}`, {
      method: "PUT",
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      },
      body: JSON.stringify({ status: newStatus }),
    });
    if (!res.ok) throw new Error("Failed to update status");
    loadAdminData();
  } catch (err) {
    showToast(err.message, "error");
  }
}

async function deleteSubmission(id) {
  if (!confirm("Are you sure you want to delete this enquiry?")) return;
  const token = getAuthToken();
  try {
    const res = await fetch(`/api/admin/submissions/${id}`, {
      method: "DELETE",
      headers: { Authorization: `Bearer ${token}` },
    });
    if (!res.ok) throw new Error("Failed to delete enquiry");
    showToast("Enquiry removed.", "info");
    loadAdminData();
  } catch (err) {
    showToast(err.message, "error");
  }
}

function exportSubmissionsCSV() {
  const list = appData.submissions || [];
  if (list.length === 0) {
    alert("No enquiries to export.");
    return;
  }
  let csv = "ID,Date,Name,Email,Phone,Subject,Service,Status,Message\n";
  list.forEach((s) => {
    csv += `"${s.id}","${s.date}","${(s.name || "").replace(/"/g, '""')}","${(s.email || "").replace(/"/g, '""')}","${(s.phone || "").replace(/"/g, '""')}","${(s.subject || s.service || "").replace(/"/g, '""')}","${(s.service || "").replace(/"/g, '""')}","${(s.status || "").replace(/"/g, '""')}","${(s.message || "").replace(/"/g, '""')}"\n`;
  });

  const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
  const url = URL.createObjectURL(blob);
  const a = document.createElement("a");
  a.href = url;
  a.download = `rolkat-enquiries-${new Date().toISOString().slice(0, 10)}.csv`;
  a.click();
}

// =======================================
// IMAGE UPLOAD HELPERS & RENDERING
// =======================================
function fileToDataURL(file) {
  return new Promise((resolve, reject) => {
    if (!file) { resolve(""); return; }
    if (!file.type || file.type.indexOf("image/") !== 0) {
      reject(new Error("Please select an image file (PNG, JPG, WebP, etc.)."));
      return;
    }
    const reader = new FileReader();
    reader.onload = (e) => resolve(String(e.target.result || ""));
    reader.onerror = () => reject(new Error("Failed to read image file."));
    reader.readAsDataURL(file);
  });
}

function setupImageUploader(uploaderEl, initialValue, onChange) {
  if (!uploaderEl) return;
  const preview = uploaderEl.querySelector("[data-preview]");
  const input = uploaderEl.querySelector("[data-input]");
  const browseBtn = uploaderEl.querySelector("[data-browse]");
  const clearBtn = uploaderEl.querySelector("[data-clear]");

  function applyPreview(src) {
    if (!preview) return;
    if (src && typeof src === "string") {
      preview.style.backgroundImage = `url('${src}')`;
      preview.classList.add("has-image");
      preview.classList.remove("is-empty");
    } else {
      preview.style.backgroundImage = "";
      preview.classList.remove("has-image");
      preview.classList.add("is-empty");
    }
  }

  applyPreview(initialValue || "");

  if (browseBtn && input) {
    browseBtn.addEventListener("click", (e) => {
      e.preventDefault();
      input.click();
    });
  }

  if (preview) {
    preview.classList.add("is-empty");
    if (initialValue) preview.classList.remove("is-empty");
    preview.style.cursor = "pointer";
    preview.addEventListener("click", () => { if (input) input.click(); });
    preview.addEventListener("dragover", (e) => {
      e.preventDefault();
      uploaderEl.classList.add("is-dragging");
    });
    preview.addEventListener("dragleave", () => uploaderEl.classList.remove("is-dragging"));
    preview.addEventListener("drop", async (e) => {
      e.preventDefault();
      uploaderEl.classList.remove("is-dragging");
      if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0]) {
        try {
          const dataUrl = await fileToDataURL(e.dataTransfer.files[0]);
          applyPreview(dataUrl);
          if (onChange) onChange(dataUrl);
        } catch (err) { showToast(err.message, "error"); }
      }
    });
  }

  if (input) {
    input.addEventListener("change", async () => {
      const f = input.files && input.files[0];
      try {
        const dataUrl = await fileToDataURL(f);
        applyPreview(dataUrl);
        if (onChange) onChange(dataUrl);
      } catch (err) { showToast(err.message, "error"); }
      // reset so same file can be re-selected
      input.value = "";
    });
  }

  if (clearBtn) {
    clearBtn.addEventListener("click", (e) => {
      e.preventDefault();
      applyPreview("");
      if (onChange) onChange("");
    });
  }
}

async function saveImages(partialImages) {
  const token = getAuthToken();
  if (!token) return;
  try {
    const res = await fetch("/api/admin/images", {
      method: "PUT",
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      },
      body: JSON.stringify(partialImages),
    });
    if (res.ok) {
      const result = await res.json();
      if (appData) {
        if (!appData.images) appData.images = {};
        Object.assign(appData.images, result.images || partialImages);
      }
    } else {
      throw new Error(`Server responded with ${res.status}`);
    }
  } catch (err) {
    console.error("saveImages error:", err);
    throw err;
  }
}

function renderImagesTab() {
  const tab = document.getElementById("tab-images");
  if (!tab) return;
  if (!appData.images) appData.images = { heroSlide1: "", heroSlide2: "", heroSlide3: "", aboutSplitPanel: "", ctaBanner: "" };

  const uploaders = tab.querySelectorAll(".image-uploader[data-key]");
  uploaders.forEach((up) => {
    const key = up.getAttribute("data-key");
    const current = (appData.images && appData.images[key]) || "";
    setupImageUploader(up, current, async (newVal) => {
      const payload = { [key]: newVal || "" };
      try {
        await saveImages(payload);
        showToast(`${key} image saved.`, "success");
      } catch (err) {
        showToast(`Failed to save ${key}: ${err.message}`, "error");
      }
    });
  });
}

// =======================================
// DOCUMENTS & PDFs TAB
// =======================================
function renderDocumentsTab() {
  const body = document.getElementById("documents-list-body");
  if (!body) return;
  const list = knownDocuments || [];

  if (list.length === 0) {
    body.innerHTML = `<div class="empty-state-inline"><i data-lucide="folder-x" style="width:22px;height:22px;opacity:0.5;"></i><div>No PDF documents found in the project root. Add .pdf files to the project folder to see them here.</div></div>`;
  } else {
    body.innerHTML = `<div class="document-grid">${list.map((d) => `
      <div class="document-card">
        <div class="document-icon"><i data-lucide="file-text" style="width:26px;height:26px;"></i></div>
        <div class="document-meta">
          <h4>${escapeHtml(d.label || d.file)}</h4>
          <div class="muted-sm" style="font-size:12px;margin-top:2px;">${escapeHtml(d.file)}</div>
        </div>
        <div class="document-actions">
          <a class="btn btn-secondary btn-sm" href="${escapeHtml(d.url)}" target="_blank" rel="noopener noreferrer" style="display:inline-flex;align-items:center;gap:5px;">
            <i data-lucide="external-link" style="width:12px;height:12px;"></i> Open
          </a>
          <a class="btn btn-primary btn-sm" href="${escapeHtml(d.url)}" download style="display:inline-flex;align-items:center;gap:5px;">
            <i data-lucide="download" style="width:12px;height:12px;"></i> Download
          </a>
        </div>
      </div>`).join("")}</div>`;
  }

  // Team doc assignments table
  const assignTbody = document.getElementById("team-doc-assign-table");
  if (assignTbody) {
    const teamList = appData.team || [];
    if (teamList.length === 0) {
      assignTbody.innerHTML = `<tr><td colspan="5" style="text-align:center;color:#888;">No team members yet. Add a team profile to assign image cards and PDFs.</td></tr>`;
    } else {
      assignTbody.innerHTML = teamList.map((m) => {
        const hasImg = m.image && String(m.image).length > 0;
        const hasPdf = (m.pdf && String(m.pdf).length > 0) || (m.pdfUrl && String(m.pdfUrl).length > 0);
        const pdfHref = (m.pdf && String(m.pdf).length > 0) ? m.pdf : (m.pdfUrl || "");
        return `
        <tr>
          <td><strong>${escapeHtml(m.name)}</strong></td>
          <td><span class="status-pill status-new">${escapeHtml(m.role || "")}</span></td>
          <td>${hasImg
            ? `<span class="status-pill status-available" style="display:inline-flex;align-items:center;gap:4px;"><i data-lucide="image" style="width:12px;height:12px;"></i> Image card set</span>`
            : `<span class="status-pill" style="background:#f1f5f9;color:#64748b;"><i data-lucide="image-off" style="width:12px;height:12px;"></i> No image</span>`}</td>
          <td>${hasPdf
            ? `<a href="${escapeHtml(pdfHref)}" target="_blank" rel="noopener noreferrer" class="status-pill status-available" style="display:inline-flex;align-items:center;gap:4px;text-decoration:none;"><i data-lucide="file-text" style="width:12px;height:12px;"></i> View PDF</a>`
            : `<span class="status-pill" style="background:#f1f5f9;color:#64748b;"><i data-lucide="file-x" style="width:12px;height:12px;"></i> No PDF</span>`}</td>
          <td>
            <button class="btn btn-secondary btn-sm" onclick="openTeamModal('${m.id}')" style="display:inline-flex;align-items:center;gap:4px;">
              <i data-lucide="pencil" style="width:12px;height:12px;"></i> Edit profile
            </button>
          </td>
        </tr>`;
      }).join("");
    }
  }

  if (window.lucide) {
    window.lucide.createIcons();
  }
}

// Navigation & Modals
function switchTab(tabId) {
  currentTab = tabId;
  document.querySelectorAll(".nav-item").forEach((btn) => {
    btn.classList.toggle("active", btn.dataset.tab === tabId);
  });
  document.querySelectorAll(".tab-pane").forEach((pane) => {
    pane.classList.toggle("active", pane.id === `tab-${tabId}`);
  });

  const titleMap = {
    overview: "Dashboard Overview",
    settings: "Site & Admin Settings",
    images: "Website Images Manager",
    documents: "Documents & PDFs Manager",
    properties: "Property Listings Manager",
    services: "Services Manager",
    team: "Team Profiles & Image Cards Manager",
    submissions: "Client Enquiries",
  };
  document.getElementById("current-page-title").textContent = titleMap[tabId] || "Dashboard";

  // close mobile nav if open
  document.querySelector(".admin-sidebar").classList.remove("open");

  if (window.lucide) {
    window.lucide.createIcons();
  }
}

function closeModal(id) {
  document.getElementById(id).classList.remove("active");
}

function escapeHtml(str) {
  if (!str) return "";
  return String(str)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

// Initial Setup
document.addEventListener("DOMContentLoaded", () => {
  document.getElementById("login-form").addEventListener("submit", handleLogin);
  document.getElementById("logout-btn").addEventListener("click", handleLogout);
  document.getElementById("settings-form").addEventListener("submit", handleSaveSettings);
  document.getElementById("property-form").addEventListener("submit", handlePropertySubmit);
  document.getElementById("service-form").addEventListener("submit", handleServiceSubmit);
  document.getElementById("team-form").addEventListener("submit", handleTeamSubmit);
  document.getElementById("export-csv-btn").addEventListener("click", exportSubmissionsCSV);

  document.querySelectorAll(".nav-item[data-tab]").forEach((btn) => {
    btn.addEventListener("click", () => switchTab(btn.dataset.tab));
  });

  document.getElementById("mobile-menu-toggle").addEventListener("click", () => {
    document.querySelector(".admin-sidebar").classList.toggle("open");
  });

  loadAdminData();
  if (window.lucide) {
    window.lucide.createIcons();
  }
});
