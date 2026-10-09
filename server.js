const { createServer } = require("node:http");
const { readFile, writeFile } = require("node:fs/promises");
const path = require("node:path");

const root = path.join(__dirname, "wp-content", "themes", "rolkat");
const dataFilePath = path.join(__dirname, "data", "site-data.json");

// Helper: load JSON data from disk
async function loadData() {
  try {
    const raw = await readFile(dataFilePath, "utf-8");
    return JSON.parse(raw);
  } catch (err) {
    console.error("Failed to read site-data.json:", err);
    return {
      settings: {},
      services: [],
      properties: [],
      team: [],
      submissions: [],
    };
  }
}

// Helper: save JSON data to disk
async function saveData(data) {
  await writeFile(dataFilePath, JSON.stringify(data, null, 2), "utf-8");
}

// Helper: parse JSON request body
function parseJsonBody(req) {
  return new Promise((resolve, reject) => {
    let body = "";
    req.on("data", (chunk) => {
      body += chunk;
      if (body.length > 2e6) {
        // 2MB limit
        reject(new Error("Payload too large"));
      }
    });
    req.on("end", () => {
      if (!body) {
        resolve({});
        return;
      }
      try {
        resolve(JSON.parse(body));
      } catch (err) {
        reject(new Error("Invalid JSON body"));
      }
    });
    req.on("error", reject);
  });
}

// Helper: send JSON response
function sendJson(res, statusCode, payload) {
  res.writeHead(statusCode, {
    "Content-Type": "application/json; charset=utf-8",
    "Cache-Control": "no-store",
    "Access-Control-Allow-Origin": "*",
    "Access-Control-Allow-Methods": "GET, POST, PUT, DELETE, OPTIONS",
    "Access-Control-Allow-Headers": "Content-Type, Authorization",
  });
  res.end(JSON.stringify(payload));
}

// Simple in-memory session tokens
const activeSessions = new Set(["admin-default-session-token"]);

function isAuthorized(req) {
  const authHeader = req.headers["authorization"] || "";
  if (!authHeader.startsWith("Bearer ")) return false;
  const token = authHeader.slice(7).trim();
  return activeSessions.has(token);
}

// Routes mapping for static files
const staticRoutes = new Map([
  ["/", ["preview.html", "text/html; charset=utf-8"]],
  ["/preview.html", ["preview.html", "text/html; charset=utf-8"]],
  ["/style.css", ["style.css", "text/css; charset=utf-8"]],
  ["/assets/js/site.js", ["assets/js/site.js", "text/javascript; charset=utf-8"]],
  ["/assets/js/lucide.min.js", ["assets/js/lucide.min.js", "text/javascript; charset=utf-8"]],
  ["/admin", ["admin/index.html", "text/html; charset=utf-8"]],
  ["/admin/", ["admin/index.html", "text/html; charset=utf-8"]],
  ["/admin/index.html", ["admin/index.html", "text/html; charset=utf-8"]],
  ["/admin/admin.css", ["admin/admin.css", "text/css; charset=utf-8"]],
  ["/admin/admin.js", ["admin/admin.js", "text/javascript; charset=utf-8"]],
  ["/admin/lucide.min.js", ["admin/lucide.min.js", "text/javascript; charset=utf-8"]],
]);

const server = createServer(async (request, response) => {
  const urlObj = new URL(request.url || "/", "http://127.0.0.1");
  const pathname = urlObj.pathname;
  const method = request.method.toUpperCase();

  // CORS preflight
  if (method === "OPTIONS") {
    response.writeHead(204, {
      "Access-Control-Allow-Origin": "*",
      "Access-Control-Allow-Methods": "GET, POST, PUT, DELETE, OPTIONS",
      "Access-Control-Allow-Headers": "Content-Type, Authorization",
    });
    response.end();
    return;
  }

  // --- API Endpoints ---

  // 1. Public Content: GET /api/content
  if (pathname === "/api/content" && method === "GET") {
    const data = await loadData();
    const publicSettings = { ...data.settings };
    delete publicSettings.adminPassword; // hide credentials
    sendJson(response, 200, {
      settings: publicSettings,
      services: data.services || [],
      properties: data.properties || [],
      team: data.team || [],
    });
    return;
  }

  // 2. Public Contact Enquiry: POST /api/contact
  if (pathname === "/api/contact" && method === "POST") {
    try {
      const body = await parseJsonBody(request);
      if (body.honeypot) {
        // Silently drop bot spam
        sendJson(response, 200, { success: true, message: "Enquiry received." });
        return;
      }
      if (!body.name || !body.email || !body.message) {
        sendJson(response, 400, { error: "Please fill in all required fields (Name, Email, Message)." });
        return;
      }

      const data = await loadData();
      if (!Array.isArray(data.submissions)) data.submissions = [];

      const newSubmission = {
        id: "sub-" + Date.now(),
        date: new Date().toISOString(),
        name: String(body.name).trim(),
        email: String(body.email).trim(),
        phone: String(body.phone || "").trim(),
        service: String(body.service || "").trim(),
        message: String(body.message).trim(),
        status: "New",
      };

      data.submissions.push(newSubmission);
      await saveData(data);

      sendJson(response, 200, {
        success: true,
        message: "Thank you for reaching out! Our team will contact you shortly.",
      });
    } catch (err) {
      sendJson(response, 500, { error: err.message });
    }
    return;
  }

  // 3. Admin Authentication: POST /api/admin/login
  if (pathname === "/api/admin/login" && method === "POST") {
    try {
      const { username, password } = await parseJsonBody(request);
      const data = await loadData();
      const cfg = data.settings || {};

      const expectedUser = cfg.adminUsername || "admin";
      const expectedPass = cfg.adminPassword || "rolkat2026";

      if (username === expectedUser && password === expectedPass) {
        const token = "tok-" + Math.random().toString(36).substring(2) + Date.now().toString(36);
        activeSessions.add(token);
        sendJson(response, 200, { success: true, token });
      } else {
        sendJson(response, 401, { error: "Invalid username or password." });
      }
    } catch (err) {
      sendJson(response, 500, { error: err.message });
    }
    return;
  }

  // Admin Protected Routes
  if (pathname.startsWith("/api/admin/")) {
    if (!isAuthorized(request)) {
      sendJson(response, 401, { error: "Unauthorized access. Please log in." });
      return;
    }

    const data = await loadData();

    // GET /api/admin/data
    if (pathname === "/api/admin/data" && method === "GET") {
      sendJson(response, 200, data);
      return;
    }

    // PUT /api/admin/settings
    if (pathname === "/api/admin/settings" && method === "PUT") {
      try {
        const newSettings = await parseJsonBody(request);
        const currentPass = data.settings.adminPassword || "rolkat2026";
        data.settings = {
          ...data.settings,
          ...newSettings,
          adminPassword: newSettings.adminPassword || currentPass,
        };
        await saveData(data);
        sendJson(response, 200, { success: true });
      } catch (err) {
        sendJson(response, 500, { error: err.message });
      }
      return;
    }

    // CRUD: Properties
    if (pathname === "/api/admin/properties" && method === "POST") {
      try {
        const item = await parseJsonBody(request);
        item.id = "prop-" + Date.now();
        if (!Array.isArray(data.properties)) data.properties = [];
        data.properties.push(item);
        await saveData(data);
        sendJson(response, 201, { success: true, item });
      } catch (err) {
        sendJson(response, 500, { error: err.message });
      }
      return;
    }

    const propMatch = pathname.match(/^\/api\/admin\/properties\/([^/]+)$/);
    if (propMatch) {
      const id = propMatch[1];
      if (method === "PUT") {
        const body = await parseJsonBody(request);
        const idx = (data.properties || []).findIndex((p) => p.id === id);
        if (idx !== -1) {
          data.properties[idx] = { ...data.properties[idx], ...body, id };
          await saveData(data);
          sendJson(response, 200, { success: true });
        } else {
          sendJson(response, 404, { error: "Property not found" });
        }
        return;
      }
      if (method === "DELETE") {
        data.properties = (data.properties || []).filter((p) => p.id !== id);
        await saveData(data);
        sendJson(response, 200, { success: true });
        return;
      }
    }

    // CRUD: Services
    if (pathname === "/api/admin/services" && method === "POST") {
      try {
        const item = await parseJsonBody(request);
        item.id = "srv-" + Date.now();
        if (!Array.isArray(data.services)) data.services = [];
        data.services.push(item);
        await saveData(data);
        sendJson(response, 201, { success: true, item });
      } catch (err) {
        sendJson(response, 500, { error: err.message });
      }
      return;
    }

    const srvMatch = pathname.match(/^\/api\/admin\/services\/([^/]+)$/);
    if (srvMatch) {
      const id = srvMatch[1];
      if (method === "PUT") {
        const body = await parseJsonBody(request);
        const idx = (data.services || []).findIndex((s) => s.id === id);
        if (idx !== -1) {
          data.services[idx] = { ...data.services[idx], ...body, id };
          await saveData(data);
          sendJson(response, 200, { success: true });
        } else {
          sendJson(response, 404, { error: "Service not found" });
        }
        return;
      }
      if (method === "DELETE") {
        data.services = (data.services || []).filter((s) => s.id !== id);
        await saveData(data);
        sendJson(response, 200, { success: true });
        return;
      }
    }

    // CRUD: Team
    if (pathname === "/api/admin/team" && method === "POST") {
      try {
        const item = await parseJsonBody(request);
        item.id = "team-" + Date.now();
        if (!Array.isArray(data.team)) data.team = [];
        data.team.push(item);
        await saveData(data);
        sendJson(response, 201, { success: true, item });
      } catch (err) {
        sendJson(response, 500, { error: err.message });
      }
      return;
    }

    const teamMatch = pathname.match(/^\/api\/admin\/team\/([^/]+)$/);
    if (teamMatch) {
      const id = teamMatch[1];
      if (method === "PUT") {
        const body = await parseJsonBody(request);
        const idx = (data.team || []).findIndex((t) => t.id === id);
        if (idx !== -1) {
          data.team[idx] = { ...data.team[idx], ...body, id };
          await saveData(data);
          sendJson(response, 200, { success: true });
        } else {
          sendJson(response, 404, { error: "Team member not found" });
        }
        return;
      }
      if (method === "DELETE") {
        data.team = (data.team || []).filter((t) => t.id !== id);
        await saveData(data);
        sendJson(response, 200, { success: true });
        return;
      }
    }

    // Submissions: Update / Delete
    const subMatch = pathname.match(/^\/api\/admin\/submissions\/([^/]+)$/);
    if (subMatch) {
      const id = subMatch[1];
      if (method === "PUT") {
        const body = await parseJsonBody(request);
        const idx = (data.submissions || []).findIndex((s) => s.id === id);
        if (idx !== -1) {
          data.submissions[idx] = { ...data.submissions[idx], ...body, id };
          await saveData(data);
          sendJson(response, 200, { success: true });
        } else {
          sendJson(response, 404, { error: "Enquiry not found" });
        }
        return;
      }
      if (method === "DELETE") {
        data.submissions = (data.submissions || []).filter((s) => s.id !== id);
        await saveData(data);
        sendJson(response, 200, { success: true });
        return;
      }
    }
  }

  // --- Static Files ---
  const route = staticRoutes.get(pathname);
  if (!route) {
    response.writeHead(404, { "Content-Type": "text/plain; charset=utf-8" });
    response.end("Not found");
    return;
  }

  try {
    const body = await readFile(path.join(root, route[0]));
    response.writeHead(200, {
      "Content-Type": route[1],
      "Cache-Control": "no-store",
      "X-Content-Type-Options": "nosniff",
    });
    response.end(body);
  } catch (error) {
    console.error(`Failed to serve ${route[0]}:`, error);
    response.writeHead(500, { "Content-Type": "text/plain; charset=utf-8" });
    response.end("File could not be loaded");
  }
});

const port = Number(process.env.PORT) || 5173;
server.listen(port, "127.0.0.1", () => {
  console.log(`ROLKAT website running at http://127.0.0.1:${port}`);
  console.log(`ROLKAT Admin Portal running at http://127.0.0.1:${port}/admin`);
});
