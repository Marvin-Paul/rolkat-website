const test = require("node:test");
const assert = require("node:assert/strict");
const { readFileSync } = require("node:fs");
const { pages, renderPage } = require("../page-renderer");
const template = readFileSync(require("node:path").join(__dirname, "../wp-content/themes/rolkat/preview.html"), "utf8");
const sectionIds = (html) => [...html.matchAll(/<section\b[^>]*id="([^"]+)"/g)].map((match) => match[1]);

test("homepage restores overview sections while service details and administration remain independent", () => {
  const html = renderPage(template, "/");
  assert.deepEqual(sectionIds(html), ["home", "pillars", "why-rolkat", "services", "about", "team", "properties", "loan-calculator", "loan-process", "contact", "privacy"]);
  for (const route of Object.keys(pages)) assert.ok(html.includes(`href="${route}"`));
  for (const id of ["services", "properties", "loan-calculator", "loan-process"]) assert.ok(html.includes(`href="#${id}"`));
});

test("each independent page contains only its own sections and shares navigation", () => {
  for (const [route, page] of Object.entries(pages)) {
    const html = renderPage(template, route);
    assert.deepEqual(sectionIds(html), page.sections);
    assert.equal((html.match(/<h1>/g) || []).length, 1);
    assert.ok(html.includes(`data-page="${route.slice(1)}"`));
    assert.ok(html.includes('src="/assets/js/site.js"'));
    assert.ok(html.includes('href="/style.css"'));
    assert.ok(html.includes('href="/#contact"'));
    assert.ok(!html.includes('<article class="service-card">'));
    assert.ok(!html.includes('hidden-section'));
  }
});

test("navigation points to independent pages and retains valid homepage anchors", () => {
  const html = renderPage(template, "/loans");
  for (const target of ["/loans#loan-process", "/loans#loan-calculator", "/real-estate#properties", "/administration", "/#about", "/#pillars"]) {
    assert.ok(html.includes(`href="${target}"`), target);
  }
  assert.ok(!html.includes('href="#service-'));
  assert.ok(!html.includes('href="#team'));
});
