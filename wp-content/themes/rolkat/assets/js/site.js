(() => {
  if (window.lucide && typeof window.lucide.createIcons === "function") {
    window.lucide.createIcons();
  }

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
      const setText = (id, value) => {
        const element = document.getElementById(id);
        if (element && value) element.textContent = value;
      };
      const page = document.body.dataset.page;
      const pageSettings = (s.pages || {})[page] || {};
      const detail = document.getElementById(`service-${page}`) || document.getElementById("team-full-page");
      if (detail) {
        const heading = detail.querySelector("h1");
        const introduction = detail.querySelector(".section-heading > p, .section-heading > div:last-child > p");
        if (heading && pageSettings.title) heading.textContent = pageSettings.title;
        if (introduction && pageSettings.introduction) introduction.textContent = pageSettings.introduction;
        if (pageSettings.title) document.title = `${pageSettings.title} | ${s.siteName || "ROLKAT Financial"}`;
      }

      // Site Identity
      if (s.siteName) {
        document.querySelectorAll("#brand-name, #footer-brand-name").forEach((el) => (el.textContent = s.siteName));
      }
      if (s.tagline) {
        document.querySelectorAll("#brand-caption, #footer-brand-caption").forEach((el) => (el.textContent = s.tagline));
      }

      // Hero Section Text
      setText("hero-eyebrow", s.heroEyebrow);
      setText("hero-title", s.heroTitle);
      setText("hero-text", s.heroText);
      setText("hero-note", s.tagline);

      // Hero Background Slider Images (from site-level images object)
      const imgs = data.images || {};
      document.querySelectorAll("img[data-image-key]").forEach((image) => {
        const key = image.dataset.imageKey;
        const source = imgs[key] || (key === "siteLogo" ? "/assets/images/rfs-logo.svg" : "");
        image.hidden = !source;
        if (source) image.src = source;
      });
      const slideKeys = ["heroSlide1", "heroSlide2", "heroSlide3"];
      slideKeys.forEach((key, i) => {
        const slideEl = document.getElementById(`hero-slide-${i}`);
        if (!slideEl) return;
        const fallbackClasses = ["hero-slide--fallback-1", "hero-slide--fallback-2", "hero-slide--fallback-3"];
        if (imgs[key] && typeof imgs[key] === "string" && imgs[key].length > 0) {
          slideEl.style.backgroundImage = `url("${imgs[key]}")`;
          fallbackClasses.forEach((c, idx) => {
            if (idx === i) return;
            slideEl.classList.remove(c);
          });
        } else {
          slideEl.style.backgroundImage = "";
          fallbackClasses.forEach((c, idx) => {
            if (idx === i) slideEl.classList.add(c);
            else slideEl.classList.remove(c);
          });
        }
      });

      // About split panel image
      const splitPanel = document.querySelector(".split-panel");
      if (splitPanel && imgs.aboutSplitPanel) {
        splitPanel.style.backgroundImage = `linear-gradient(135deg, rgba(11, 31, 58, 0.88), rgba(11, 31, 58, 0.7)), url("${imgs.aboutSplitPanel}")`;
        splitPanel.style.backgroundSize = "cover";
        splitPanel.style.backgroundPosition = "center";
      }

      // CTA banner background overlay image
      const ctaBand = document.querySelector(".cta-band");
      if (ctaBand && imgs.ctaBanner) {
        ctaBand.style.backgroundImage = `linear-gradient(120deg, rgba(11, 31, 58, 0.88) 0%, rgba(26, 51, 88, 0.82) 45%, rgba(240, 78, 35, 0.78) 140%), url("${imgs.ctaBanner}")`;
        ctaBand.style.backgroundSize = "cover";
        ctaBand.style.backgroundPosition = "center";
      }

      // Contact Info
      if (s.phone && document.getElementById("site-phone")) {
        const phoneEl = document.getElementById("site-phone");
        phoneEl.innerHTML = `<a href="tel:${escape(s.phone.replace(/\s+/g, ""))}">${escape(s.phone)}</a>`;
      }
      if (s.email && document.getElementById("site-email")) {
        const emailEl = document.getElementById("site-email");
        emailEl.innerHTML = `<a href="mailto:${escape(s.email)}">${escape(s.email)}</a>`;
      }
      if (s.officeAddress && document.getElementById("site-address")) {
        document.getElementById("site-address").textContent = s.officeAddress;
      }
      if (s.workingHours && document.getElementById("site-hours")) {
        document.getElementById("site-hours").textContent = s.workingHours;
        document.getElementById("site-hours-container").style.display = "block";
      }
      if (s.whatsappNumber && document.getElementById("site-whatsapp-link")) {
        const waClean = s.whatsappNumber.replace(/[^0-9]/g, "");
        const waLink = document.getElementById("site-whatsapp-link");
        waLink.href = `https://wa.me/${waClean}`;
        document.getElementById("site-whatsapp-container").style.display = "block";
      }

      // Services Marquee & Grid Hydration
      const services = (data.services || []).filter((service) => !page || service.category === page);
      const group1 = document.getElementById("services-group-1");
      const group2 = document.getElementById("services-group-2");
      const servGrid = document.getElementById("services-grid");

      const cardMarkup = (srv) => `
          <article class="service-card">
            <span class="service-card-number">${escape(srv.number || "01")}</span>
            <h3>${escape(srv.title)}</h3>
            <p>${escape(srv.description)}</p>
            <a class="text-link" href="${!page && ["loans", "property-management", "real-estate"].includes(srv.category) ? `/${srv.category}` : "/#contact"}">${page ? "Enquire about this service" : "Learn more"}</a>
          </article>
      `;

      if (detail && page !== "administration") {
        const features = detail.querySelector(".service-detail-features");
        if (features) features.innerHTML = services.map((service) => `<div class="feature-chip">${escape(service.title)}</div>`).join("");
        const serviceHeading = document.querySelector("#services .section-heading h2");
        if (serviceHeading) serviceHeading.textContent = `${page === "loans" ? "Loan" : page === "real-estate" ? "Real estate" : "Property management"} services`;
        const serviceIntroduction = document.querySelector("#services .services-heading-side > p");
        if (serviceIntroduction) serviceIntroduction.textContent = "Contact our team to discuss the service that suits your needs.";
      }

      if (group1) {
        const html = services.map(cardMarkup).join("\n");
        group1.innerHTML = html;
        if (group2) {
          group2.innerHTML = html;
        }
      } else if (servGrid) {
        servGrid.innerHTML = services.map(cardMarkup).join("\n");
      }

      // Properties RTL Marquee + Grid fallback (optional filters)
      const properties = data.properties || [];
      const propGroup1 = document.getElementById("prop-marquee-group-1");
      const propGroup2 = document.getElementById("prop-marquee-group-2");
      const propGrid = document.getElementById("properties-grid");
      const statusFilter = document.getElementById("preview-property-status");
      const typeFilter = document.getElementById("preview-property-type");

      // Build property card markup (shared by marquee cards and grid fallback)
      const propCardMarkup = (prop) => {
        const hasImg = prop.image && typeof prop.image === "string" && prop.image.length > 0;
        return `
          <article class="property-card property-card--marquee">
            <div class="property-card-placeholder${hasImg ? " has-image" : ""}" ${hasImg ? `style="background-image:url('${escape(prop.image)}');"` : ""} aria-hidden="true"></div>
            <div class="property-card-body">
              ${prop.imageIsIllustration && hasImg ? '<p class="property-image-disclosure">Illustrative image</p>' : ""}
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
              <a class="text-link" href="/#contact">Enquire about property</a>
            </div>
          </article>
        `;
      };

      function renderProperties() {
        const statusValue = statusFilter ? statusFilter.value : "";
        const typeValue = typeFilter ? typeFilter.value : "";
        const filtered = properties.filter((prop) => {
          const statusOk = !statusValue || prop.status === statusValue;
          const typeOk = !typeValue || prop.type === typeValue;
          return statusOk && typeOk;
        });

        // Marquee always shows ALL listings (scrolling carousel)
        const allHtml = properties.length > 0 ? properties.map(propCardMarkup).join("\n") : "";
        if (propGroup1) {
          propGroup1.innerHTML = allHtml;
        }
        if (propGroup2) {
          propGroup2.innerHTML = allHtml;
        }

        // Grid fallback shows filtered results when a filter is active
        if (propGrid) {
          const anyFilter = !!statusValue || !!typeValue;
          if (anyFilter) {
            propGrid.style.display = "grid";
            if (filtered.length === 0) {
              propGrid.innerHTML = `<p class="empty-state" style="grid-column:1/-1;">No listings match these filters. Contact our team to discuss your requirements.</p>`;
            } else {
              propGrid.className = "property-grid-fallback is-grid";
              propGrid.innerHTML = filtered.map(propCardMarkup).join("\n");
            }
          } else {
            propGrid.style.display = "none";
            propGrid.innerHTML = "";
          }
        }

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

      // Team Grid - Image cards with optional PDF profile link
      const team = (data.team || []).slice().sort((a, b) => (a.rank || Infinity) - (b.rank || Infinity));
      const memberById = new Map(team.map((member) => [member.id, member]));
      const chart = document.getElementById("administration-chart");
      if (chart) {
        const rendered = new Set();
        const renderBranch = (member) => {
          if (rendered.has(member.id)) return "";
          rendered.add(member.id);
          const children = team.filter((child) => child.reportsTo === member.id);
          // Managers appear in the source chart before support staff.
          children.sort((a, b) => Number(b.level === "Manager") - Number(a.level === "Manager"));
          const level = member.level === "Executive" ? "executive" : member.level === "Manager" ? "manager" : "support";
          const childMarkup = children.map(renderBranch).join("");
          const portrait = member.image ? `<img class="hierarchy-portrait" src="${escape(member.image)}" alt="" loading="lazy">` : "";
          return `<li><a class="hierarchy-node hierarchy-node--${level}${portrait ? " hierarchy-node--photo" : ""}" href="#staff-${escape(member.id)}">${portrait}<strong>${escape(member.name)}</strong><span>${escape(member.role)}</span></a>${childMarkup ? `<ul>${childMarkup}</ul>` : ""}</li>`;
        };
        const roots = team.filter((member) => !member.reportsTo || !memberById.has(member.reportsTo));
        let branches = roots.map(renderBranch).join("");
        branches += team.filter((member) => !rendered.has(member.id)).map(renderBranch).join("");
        chart.innerHTML = `<ul class="hierarchy-tree">${branches}</ul>`;
      }
      const teamGrid = document.getElementById("team-grid") || document.getElementById("team-full-page-grid");
      if (teamGrid) {
        const visibleTeam = teamGrid.id === "team-grid" ? team.slice(0, 3) : team;
        teamGrid.innerHTML = visibleTeam
          .map(
            (m) => {
              const hasImg = m.image && typeof m.image === "string" && m.image.length > 0;
              const hasPdf = m.pdf && typeof m.pdf === "string" && m.pdf.length > 0;
              // Also support a reference PDF URL field
              const pdfHref = hasPdf ? m.pdf : (m.pdfUrl || "");
              const hasAnyPdf = pdfHref && pdfHref.length > 0;
              const initials = String(m.name || "").trim().split(/\s+/).slice(0, 2).map((part) => part[0]).join("").toUpperCase();
              if (page === "administration") {
                const manager = memberById.get(m.reportsTo);
                return `<tr id="staff-${escape(m.id)}">
                  <td data-label="Rank">${escape(m.rank || "-")}</td>
                  <th scope="row"><div class="staff-person">${hasImg ? `<img class="staff-avatar" src="${escape(m.image)}" alt="" loading="lazy">` : `<span class="staff-avatar" aria-hidden="true">${escape(initials)}</span>`}<span>${escape(m.name)}</span></div>${hasAnyPdf ? `<a class="text-link" href="${escape(pdfHref)}" target="_blank" rel="noopener noreferrer">View profile PDF</a>` : ""}</th>
                  <td data-label="Position">${escape(m.role)}${m.bio ? `<p class="staff-biography">${escape(m.bio)}</p>` : ""}</td>
                  <td data-label="Level">${escape(m.level || "Unassigned")}</td>
                  <td data-label="Reports to (inferred)">${escape(manager ? manager.role === "Chief Executive Officer" ? "CEO" : manager.role : m.reportsTo ? "Unassigned" : "Board / owner")}</td>
                </tr>`;
              }

              return `
          <article class="team-card team-card--image">
            <div class="team-card-media">
              <div class="team-card-placeholder${hasImg ? " has-image" : " team-card-initials"}" ${hasImg ? `style="background-image:url('${escape(m.image)}');"` : ""} aria-hidden="true">${hasImg ? "" : escape(initials)}</div>
            </div>
            <div class="team-card-body">
              <span class="team-card-role">${escape(m.role || "Consultant")}</span>
              <h3>${escape(m.name)}</h3>
              <p style="margin-top:8px;font-size:14px;color:var(--muted);">${escape(m.bio || "")}</p>
              ${hasAnyPdf ? `
                <a class="team-card-pdf-link" href="${escape(pdfHref)}" target="_blank" rel="noopener noreferrer" title="Open full profile document">
                  <i data-lucide="file-text" style="width:14px;height:14px;"></i>
                  <span>View profile PDF</span>
                </a>` : ""}
            </div>
          </article>
        `;
            }
          )
          .join("");
        if (!team.length) teamGrid.innerHTML = page === "administration" ? '<tr><td colspan="5">Please contact our office for administration enquiries.</td></tr>' : '<p class="empty-state">Please contact our office for administration enquiries.</p>';
        setText("administration-member-count", `${team.length} ${team.length === 1 ? "member" : "members"}`);
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

  // Properties RTL Marquee Controls (Right-To-Left carousel)
  const propMarqueeTrack = document.getElementById("prop-marquee-track");
  const propMarqueeToggleBtn = document.getElementById("prop-marquee-toggle-btn");

  if (propMarqueeTrack && propMarqueeToggleBtn) {
    propMarqueeToggleBtn.addEventListener("click", () => {
      const isPaused = propMarqueeTrack.classList.toggle("is-paused");
      propMarqueeToggleBtn.setAttribute(
        "aria-label",
        isPaused ? "Play moving property cards" : "Pause moving property cards"
      );
      propMarqueeToggleBtn.setAttribute(
        "title",
        isPaused ? "Play property animation" : "Pause property animation"
      );
      propMarqueeToggleBtn.innerHTML = isPaused
        ? '<i data-lucide="play" id="prop-marquee-toggle-icon" style="width:14px;height:14px;"></i>'
        : '<i data-lucide="pause" id="prop-marquee-toggle-icon" style="width:14px;height:14px;"></i>';
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

  // ================================
  // HERO CAROUSEL: 3 sliding backgrounds (left -> right swipe)
  // ================================
  function initHeroCarousel() {
    const slides = document.querySelectorAll(".hero-slide");
    const dots = document.querySelectorAll(".hero-slider-dot");
    const prevBtn = document.getElementById("hero-slider-prev");
    const nextBtn = document.getElementById("hero-slider-next");
    if (!slides.length || slides.length < 3) return;

    const prefersReduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    const AUTO_INTERVAL = 6500;
    let current = 0;
    let timer = null;
    let locked = false;

    function setClasses() {
      slides.forEach((el, i) => {
        el.classList.remove("is-current", "is-prev");
        if (i === current) {
          el.classList.add("is-current");
        } else if (
          (current === 0 && i === slides.length - 1) ||
          i === current - 1
        ) {
          el.classList.add("is-prev");
        }
      });
      dots.forEach((dot, i) => {
        dot.classList.toggle("is-active", i === current);
        dot.setAttribute("aria-selected", i === current ? "true" : "false");
      });
    }

    function goTo(index, direction = 1) {
      if (locked) return;
      if (index === current) return;
      const max = slides.length - 1;
      let nextIdx = index;
      if (nextIdx < 0) nextIdx = max;
      if (nextIdx > max) nextIdx = 0;

      locked = true;
      current = nextIdx;
      setClasses();

      const transitionMs = prefersReduced ? 550 : 1250;
      setTimeout(() => {
        locked = false;
      }, transitionMs);
    }

    function next() {
      goTo(current + 1, 1);
    }
    function prev() {
      goTo(current - 1, -1);
    }

    function startAuto() {
      stopAuto();
      if (prefersReduced) return;
      timer = setInterval(next, AUTO_INTERVAL);
    }
    function stopAuto() {
      if (timer) {
        clearInterval(timer);
        timer = null;
      }
    }

    if (prevBtn) {
      prevBtn.addEventListener("click", () => {
        stopAuto();
        prev();
        startAuto();
      });
    }
    if (nextBtn) {
      nextBtn.addEventListener("click", () => {
        stopAuto();
        next();
        startAuto();
      });
    }

    dots.forEach((dot) => {
      dot.addEventListener("click", () => {
        stopAuto();
        const idx = Number(dot.dataset.slideIndex || 0);
        goTo(idx, idx > current ? 1 : -1);
        startAuto();
      });
    });

    // Pause on hover (desktop)
    const hero = document.querySelector(".hero");
    if (hero && !prefersReduced) {
      hero.addEventListener("mouseenter", stopAuto);
      hero.addEventListener("mouseleave", startAuto);
    }

    // Touch swipe support (mobile)
    let touchStartX = 0;
    let touchStartY = 0;
    let touching = false;
    if (hero) {
      hero.addEventListener(
        "touchstart",
        (e) => {
          const t = e.touches[0];
          touchStartX = t.clientX;
          touchStartY = t.clientY;
          touching = true;
          stopAuto();
        },
        { passive: true }
      );
      hero.addEventListener(
        "touchend",
        (e) => {
          if (!touching) return;
          touching = false;
          const t = e.changedTouches[0];
          const dx = t.clientX - touchStartX;
          const dy = t.clientY - touchStartY;
          if (Math.abs(dx) > Math.abs(dy) && Math.abs(dx) > 40) {
            if (dx < 0) next();
            else prev();
          }
          startAuto();
        },
        { passive: true }
      );
    }

    // Keyboard accessibility
    document.addEventListener("keydown", (e) => {
      if (e.key === "ArrowLeft" && document.activeElement?.closest(".hero")) {
        stopAuto();
        prev();
        startAuto();
      } else if (e.key === "ArrowRight" && document.activeElement?.closest(".hero")) {
        stopAuto();
        next();
        startAuto();
      }
    });

    setClasses();
    startAuto();
  }

  // Run on load
  loadSiteContent().then(() => {
    initScrollReveals();
    initHeroCarousel();
  });
})();
