const pages = {
  "/loans": { title: "Loans", sections: ["service-loans", "services", "loan-calculator", "loan-process"] },
  "/property-management": { title: "Property Management", sections: ["service-property-management", "services"] },
  "/real-estate": { title: "Real Estate", sections: ["service-real-estate", "services", "properties"] },
  "/administration": { title: "Administration", sections: ["team-full-page"] },
};

const destinations = {
  "service-loans": "/loans",
  "service-property-management": "/property-management",
  "service-real-estate": "/real-estate",
  "loan-calculator": "/loans#loan-calculator",
  "loan-process": "/loans#loan-process",
  properties: "/real-estate#properties",
  team: "/administration",
  "team-full-page": "/administration",
  services: "/#pillars",
  home: "/",
};

function renderPage(template, pathname) {
  const page = pages[pathname];
  const sections = new Map();
  // The shared preview template has top-level, non-nested sections.
  for (const match of template.matchAll(/<section\b[^>]*\bid="([^"]+)"[^>]*>[\s\S]*?<\/section>/g)) {
    sections.set(match[1], match[0]);
  }
  const independent = new Set(["service-loans", "service-property-management", "service-real-estate", "team-full-page"]);
  let html = template;
  if (page) {
    const content = page.sections.map((id) => sections.get(id) || "").join("\n");
    html = html.replace(/(<main\b[^>]*>)[\s\S]*?(<\/main>)/, `$1${content}$2`);
    html = html.replace(/<title>[\s\S]*?<\/title>/, `<title>${page.title} | ROLKAT Financial</title>`);
    html = html.replace('<body>', `<body data-page="${pathname.slice(1)}">`);
    html = html.replace(/hidden-section/g, "");
    html = html.replace(/(<section\b[^>]*>[\s\S]*?)<h2>([\s\S]*?)<\/h2>/, "$1<h1>$2</h1>");
  } else {
    html = html.replace(/<section\b[^>]*\bid="([^"]+)"[^>]*>[\s\S]*?<\/section>/g, (section, id) => independent.has(id) ? "" : section);
  }
  html = html.replace(/(href|src)="(style\.css|assets\/[^\"]+)"/g, '$1="/$2"');
  html = html.replace(/href="#([^"]+)"/g, (link, id) => {
    if (id === "main-content") return link;
    if (!page && ["services", "properties", "loan-calculator", "loan-process"].includes(id)) return link;
    const destination = destinations[id];
    if (destination) return `href="${destination}"`;
    return page ? `href="/#${id}"` : link;
  });
  if (page) html = html.replace(/<article class="service-card">[\s\S]*?<\/article>/g, "");
  return html;
}

module.exports = { pages, renderPage };
