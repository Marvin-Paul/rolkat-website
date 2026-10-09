(() => {
  // Lightweight service illustrations. Motion starts only when the card is visible.
  const lottieContainers = document.querySelectorAll("[data-lottie-src]");
  const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  if (window.lottie && lottieContainers.length) {
    const animationByContainer = new Map();

    lottieContainers.forEach((container) => {
      const animation = window.lottie.loadAnimation({
        container,
        renderer: "svg",
        loop: !prefersReducedMotion,
        autoplay: false,
        path: container.dataset.lottieSrc,
        rendererSettings: {
          progressiveLoad: true,
          preserveAspectRatio: "xMidYMid meet",
        },
      });

      animationByContainer.set(container, animation);

      if (prefersReducedMotion) {
        animation.addEventListener("DOMLoaded", () => animation.goToAndStop(45, true));
      }
    });

    if (!prefersReducedMotion && "IntersectionObserver" in window) {
      const animationObserver = new IntersectionObserver(
        (entries) => {
          entries.forEach((entry) => {
            const animation = animationByContainer.get(entry.target);
            if (!animation) return;
            if (entry.isIntersecting) animation.play();
            else animation.pause();
          });
        },
        { threshold: 0.3 }
      );

      lottieContainers.forEach((container) => animationObserver.observe(container));
    } else if (!prefersReducedMotion) {
      animationByContainer.forEach((animation) => animation.play());
    }
  }

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
      if (event.target instanceof HTMLAnchorElement && window.matchMedia("(max-width: 1100px)").matches) {
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
        result.classList.remove("is-updating");
        void result.offsetWidth;
        result.classList.add("is-updating");
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

  // Load and Hydrate Content from Server (preview mode only)
  async function loadSiteContent() {
    if (window.rolkatTheme && window.rolkatTheme.isWordPress) {
      initCalculator(null);
      return;
    }

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

      // Services Marquee & Grid Hydration
      const services = data.services || [];
      const group1 = document.getElementById("services-group-1");
      const group2 = document.getElementById("services-group-2");
      const servGrid = document.getElementById("services-grid");

      const cardMarkup = (srv) => `
          <article class="service-card">
            <span class="service-card-number">${escape(srv.number || "01")}</span>
            <h3>${escape(srv.title)}</h3>
            <p>${escape(srv.description)}</p>
            <a class="text-link" href="#contact">Learn more</a>
          </article>
      `;

      if (group1 && services.length > 0) {
        const html = services.map(cardMarkup).join("\n");
        group1.innerHTML = html;
        if (group2) {
          group2.innerHTML = html;
        }
      } else if (servGrid && services.length > 0) {
        servGrid.innerHTML = services.map(cardMarkup).join("\n");
      }

      // Properties Grid with optional filters
      const properties = data.properties || [];
      const propGrid = document.getElementById("properties-grid");
      const statusFilter = document.getElementById("preview-property-status");
      const typeFilter = document.getElementById("preview-property-type");

      function renderProperties() {
        if (!propGrid) return;
        const statusValue = statusFilter ? statusFilter.value : "";
        const typeValue = typeFilter ? typeFilter.value : "";
        const filtered = properties.filter((prop) => {
          const statusOk = !statusValue || prop.status === statusValue;
          const typeOk = !typeValue || prop.type === typeValue;
          return statusOk && typeOk;
        });

        if (filtered.length === 0) {
          propGrid.innerHTML = `<p class="empty-state">No listings match these filters. Contact our team to discuss your requirements.</p>`;
          return;
        }

        propGrid.innerHTML = filtered
          .map(
            (prop) => `
          <article class="property-card">
            <div class="property-card-placeholder" aria-hidden="true"></div>
            <div class="property-card-body">
              <div class="property-card-tags">
                <span class="property-card-status">${escape(prop.status || "Available")}</span>
                ${prop.type ? `<span class="property-card-type">${escape(prop.type)}</span>` : ""}
              </div>
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

        if (window.lucide) {
          window.lucide.createIcons();
        }
      }

      renderProperties();
      if (statusFilter) statusFilter.addEventListener("change", renderProperties);
      if (typeFilter) typeFilter.addEventListener("change", renderProperties);

      // Floating WhatsApp from settings
      if (s.whatsappNumber) {
        const float = document.getElementById("whatsapp-float");
        if (float) {
          float.href = `https://wa.me/${s.whatsappNumber.replace(/[^0-9]/g, "")}`;
          float.style.display = "grid";
        }
      }

      // Map embed
      if (s.mapEmbedUrl) {
        const mapFrame = document.getElementById("office-map");
        const mapWrap = document.getElementById("office-map-wrap");
        if (mapFrame && mapWrap) {
          mapFrame.src = s.mapEmbedUrl;
          mapWrap.style.display = "block";
        }
      }

      // Social links
      const socialWrap = document.getElementById("footer-social");
      if (socialWrap) {
        const links = [];
        if (s.facebook) links.push(`<li><a href="${escape(s.facebook)}" target="_blank" rel="noopener noreferrer">Facebook</a></li>`);
        if (s.instagram) links.push(`<li><a href="${escape(s.instagram)}" target="_blank" rel="noopener noreferrer">Instagram</a></li>`);
        if (s.linkedin) links.push(`<li><a href="${escape(s.linkedin)}" target="_blank" rel="noopener noreferrer">LinkedIn</a></li>`);
        socialWrap.innerHTML = links.join("");
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
        subject: contactForm.elements.namedItem("subject")?.value,
        service: contactForm.elements.namedItem("subject")?.value || contactForm.elements.namedItem("service")?.value,
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

  // Services Marquee Controls
  const marqueeTrack = document.getElementById("services-track");
  const marqueeToggleBtn = document.getElementById("marquee-toggle-btn");

  if (marqueeTrack && marqueeToggleBtn) {
    marqueeToggleBtn.addEventListener("click", () => {
      const isPaused = marqueeTrack.classList.toggle("is-paused");
      marqueeToggleBtn.setAttribute(
        "aria-label",
        isPaused ? "Play moving cards" : "Pause moving cards"
      );
      marqueeToggleBtn.setAttribute(
        "title",
        isPaused ? "Play animation" : "Pause animation"
      );
      marqueeToggleBtn.innerHTML = isPaused
        ? '<i data-lucide="play" style="width:14px;height:14px;"></i>'
        : '<i data-lucide="pause" style="width:14px;height:14px;"></i>';
      if (window.lucide && typeof window.lucide.createIcons === "function") {
        window.lucide.createIcons();
      }
    });
  }

  // Sticky Header & Back-to-Top Scroll Triggers
  const header = document.querySelector(".site-header");
  const backToTopBtn = document.getElementById("back-to-top");

  function handleScroll() {
    const scrollY = window.scrollY || window.pageYOffset;
    if (header) {
      if (scrollY > 30) {
        header.classList.add("is-scrolled");
      } else {
        header.classList.remove("is-scrolled");
      }
    }
    if (backToTopBtn) {
      if (scrollY > 350) {
        backToTopBtn.classList.add("is-visible");
      } else {
        backToTopBtn.classList.remove("is-visible");
      }
    }
  }

  window.addEventListener("scroll", handleScroll, { passive: true });
  handleScroll();

  if (backToTopBtn) {
    backToTopBtn.addEventListener("click", () => {
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  }

  // IntersectionObserver for Scroll Reveals
  function initScrollReveals() {
    const reveals = document.querySelectorAll(".reveal:not(.is-revealed)");
    if (!reveals.length) return;

    if ("IntersectionObserver" in window) {
      const observer = new IntersectionObserver(
        (entries) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              entry.target.classList.add("is-revealed");
              observer.unobserve(entry.target);
            }
          });
        },
        { rootMargin: "0px 0px -40px 0px", threshold: 0.1 }
      );

      reveals.forEach((el) => observer.observe(el));
    } else {
      reveals.forEach((el) => el.classList.add("is-revealed"));
    }
  }

  initScrollReveals();

  // Run on load
  loadSiteContent().then(() => {
    initScrollReveals();
  });
})();
