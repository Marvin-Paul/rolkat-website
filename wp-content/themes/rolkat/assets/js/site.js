(() => {
  // Mobile Nav Toggle
  const toggle = document.querySelector(".nav-toggle");
  const navigation = document.querySelector("#primary-navigation");

  if (toggle && navigation) {
    toggle.addEventListener("click", () => {
      const isExpanded = toggle.getAttribute("aria-expanded") === "true";
      toggle.setAttribute("aria-expanded", String(!isExpanded));
      navigation.classList.toggle("is-open", !isExpanded);
    });

    navigation.addEventListener("click", (event) => {
      if (event.target instanceof HTMLAnchorElement && window.matchMedia("(max-width: 860px)").matches) {
        toggle.setAttribute("aria-expanded", "false");
        navigation.classList.remove("is-open");
      }
    });
  }

  // Loan Calculator
  const calculator = document.querySelector("[data-loan-calculator]");
  const result = document.querySelector("[data-loan-result]");

  function initCalculator(defaults) {
    if (calculator instanceof HTMLFormElement && result) {
      const amountInput = calculator.elements.namedItem("amount");
      const rateInput = calculator.elements.namedItem("rate");
      const termInput = calculator.elements.namedItem("term");

      if (defaults) {
        if (defaults.amount && amountInput) amountInput.value = defaults.amount;
        if (defaults.rate && rateInput) rateInput.value = defaults.rate;
        if (defaults.term && termInput) termInput.value = defaults.term;
      }

      const currency = new Intl.NumberFormat("en-UG", {
        style: "currency",
        currency: "UGX",
        maximumFractionDigits: 0,
      });

      const updateEstimate = () => {
        const amount = Number(amountInput.value);
        const annualRate = Number(rateInput.value);
        const years = Number(termInput.value);
        const months = years * 12;

        if (
          amountInput.value === "" ||
          rateInput.value === "" ||
          termInput.value === "" ||
          !Number.isFinite(amount) ||
          !Number.isFinite(annualRate) ||
          !Number.isFinite(years) ||
          amount < Number(amountInput.min) ||
          amount > Number(amountInput.max) ||
          annualRate < Number(rateInput.min) ||
          annualRate > Number(rateInput.max) ||
          !Number.isInteger(years) ||
          years < Number(termInput.min) ||
          years > Number(termInput.max)
        ) {
          result.textContent = "Enter valid loan details";
          return;
        }

        const monthlyRate = annualRate / 100 / 12;
        const repayment =
          monthlyRate === 0
            ? amount / months
            : (amount * monthlyRate) / (1 - Math.pow(1 + monthlyRate, -months));

        result.textContent = currency.format(repayment);
      };

      calculator.addEventListener("input", updateEstimate);
      updateEstimate();
    }
  }

  // Escape HTML helper
  function escape(str) {
    if (!str) return "";
    return String(str)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  }

  // Load and Hydrate Content from Server
  async function loadSiteContent() {
    try {
      const res = await fetch("/api/content");
      if (!res.ok) return;
      const data = await res.json();
      const s = data.settings || {};

      // Site Identity
      if (s.siteName) {
        document.querySelectorAll("#brand-name, #footer-brand-name").forEach((el) => (el.textContent = s.siteName));
      }
      if (s.tagline) {
        document.querySelectorAll("#brand-caption, #footer-brand-caption").forEach((el) => (el.textContent = s.tagline));
      }

      // Hero Section
      if (s.heroEyebrow) document.getElementById("hero-eyebrow").textContent = s.heroEyebrow;
      if (s.heroTitle) document.getElementById("hero-title").textContent = s.heroTitle;
      if (s.heroText) document.getElementById("hero-text").textContent = s.heroText;
      if (s.tagline) document.getElementById("hero-note").textContent = s.tagline;

      // Contact Info
      if (s.phone) {
        const phoneEl = document.getElementById("site-phone");
        phoneEl.innerHTML = `<a href="tel:${escape(s.phone.replace(/\s+/g, ""))}">${escape(s.phone)}</a>`;
      }
      if (s.email) {
        const emailEl = document.getElementById("site-email");
        emailEl.innerHTML = `<a href="mailto:${escape(s.email)}">${escape(s.email)}</a>`;
      }
      if (s.officeAddress) {
        document.getElementById("site-address").textContent = s.officeAddress;
      }
      if (s.workingHours) {
        document.getElementById("site-hours").textContent = s.workingHours;
        document.getElementById("site-hours-container").style.display = "block";
      }
      if (s.whatsappNumber) {
        const waClean = s.whatsappNumber.replace(/[^0-9]/g, "");
        const waLink = document.getElementById("site-whatsapp-link");
        waLink.href = `https://wa.me/${waClean}`;
        document.getElementById("site-whatsapp-container").style.display = "block";
      }

      // Services Grid
      const services = data.services || [];
      const servGrid = document.getElementById("services-grid");
      if (servGrid && services.length > 0) {
        servGrid.innerHTML = services
          .map(
            (srv) => `
          <article class="service-card">
            <span class="service-card-number">${escape(srv.number || "01")}</span>
            <h3>${escape(srv.title)}</h3>
            <p>${escape(srv.description)}</p>
            <a class="text-link" href="#contact">Learn more</a>
          </article>
        `
          )
          .join("");
      }

      // Properties Grid
      const properties = data.properties || [];
      const propGrid = document.getElementById("properties-grid");
      if (propGrid && properties.length > 0) {
        propGrid.innerHTML = properties
          .map(
            (prop) => `
          <article class="property-card">
            <div class="property-card-placeholder" aria-hidden="true"></div>
            <div class="property-card-body">
              <span class="property-card-status">${escape(prop.status || "Available")}</span>
              <h3>${escape(prop.title)}</h3>
              <div class="property-meta">
                <span style="display:inline-flex;align-items:center;gap:4px;"><i data-lucide="map-pin" style="width:14px;height:14px;"></i>${escape(prop.location || "Kampala")}</span> &middot; 
                <strong>${escape(prop.price || "Contact for price")}</strong>
              </div>
              <p style="margin-top:8px;">${escape(prop.description || "")}</p>
              <a class="text-link" href="#contact">Enquire about property</a>
            </div>
          </article>
        `
          )
          .join("");
      }

      // Team Grid
      const team = data.team || [];
      const teamGrid = document.getElementById("team-grid");
      if (teamGrid && team.length > 0) {
        teamGrid.innerHTML = team
          .map(
            (m) => `
          <article class="team-card">
            <div class="team-card-placeholder" aria-hidden="true"></div>
            <div class="property-card-body">
              <span class="property-card-status" style="color:var(--ink-soft);">${escape(m.role || "Consultant")}</span>
              <h3>${escape(m.name)}</h3>
              <p style="margin-top:8px;font-size:14px;color:var(--muted);">${escape(m.bio || "")}</p>
            </div>
          </article>
        `
          )
          .join("");
      }

      // Calculator defaults
      initCalculator(s.calculatorDefaults);

      // Render Lucide icons
      if (window.lucide) {
        window.lucide.createIcons();
      }
    } catch (err) {
      console.warn("Could not load dynamic site content:", err);
      initCalculator(null);
    }
  }

  // Handle Contact Form Submission
  const contactForm = document.getElementById("public-contact-form");
  if (contactForm) {
    contactForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const alertBox = document.getElementById("contact-alert");
      const submitBtn = document.getElementById("contact-submit-btn");

      const payload = {
        name: contactForm.elements.namedItem("name")?.value,
        email: contactForm.elements.namedItem("email")?.value,
        phone: contactForm.elements.namedItem("phone")?.value,
        service: contactForm.elements.namedItem("service")?.value,
        message: contactForm.elements.namedItem("message")?.value,
        honeypot: contactForm.elements.namedItem("website")?.value,
      };

      submitBtn.disabled = true;
      submitBtn.textContent = "Sending message...";

      try {
        const res = await fetch("/api/contact", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(payload),
        });
        const data = await res.json();

        if (res.ok) {
          alertBox.style.display = "block";
          alertBox.style.background = "#e7f6ec";
          alertBox.style.color = "#0f6831";
          alertBox.style.border = "1px solid #c2e9cf";
          alertBox.textContent = data.message || "Thank you! Your message has been sent successfully.";
          contactForm.reset();
        } else {
          throw new Error(data.error || "Failed to send message. Please try again.");
        }
      } catch (err) {
        alertBox.style.display = "block";
        alertBox.style.background = "#fdeeed";
        alertBox.style.color = "#a32121";
        alertBox.style.border = "1px solid #fad2d2";
        alertBox.textContent = err.message;
      } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = "Send message";
      }
    });
  }

  // Run on load
  loadSiteContent();
})();
