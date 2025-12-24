document.addEventListener("DOMContentLoaded", () => {
  /* =========================
     ZONE SEARCH (ton code)
     ========================= */
  const input = document.getElementById("city-search");
  const button = document.getElementById("search-button");
  const cards = document.querySelectorAll(".zone-search .zone-card");

  const normalize = (s) =>
    (s || "")
      .toLowerCase()
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "");

  function filterZones() {
    if (!input) return;
    const query = normalize(input.value.trim());

    cards.forEach((card) => {
      const citiesAttr = normalize(card.getAttribute("data-cities"));
      const cityItems = card.querySelectorAll(".second-content li");

      // reset
      card.classList.remove("is-open");
      cityItems.forEach((li) => (li.style.display = "list-item"));

      // input vide => reset
      if (!query) {
        card.style.display = "flex";
        return;
      }

      // pas trouvé => cacher
      if (!citiesAttr.includes(query)) {
        card.style.display = "none";
        return;
      }

      // trouvé => afficher + ouvrir
      card.style.display = "flex";
      card.classList.add("is-open");

      // filtrer les villes dans la carte
      cityItems.forEach((li) => {
        const cityName = normalize(li.textContent);
        if (!cityName.includes(query)) li.style.display = "none";
      });
    });
  }

  if (input) input.addEventListener("input", filterZones);
  if (button) button.addEventListener("click", filterZones);

  /* =========================
     MENU BURGER (ajout)
     ========================= */
  const nav = document.querySelector("nav");
  const burger = document.querySelector(".burger");

  if (nav && burger) {
    const setExpanded = (open) => burger.setAttribute("aria-expanded", open ? "true" : "false");

    burger.addEventListener("click", (e) => {
      e.preventDefault();
      const open = nav.classList.toggle("nav-open");
      setExpanded(open);
    });

    // Ferme le menu quand on clique un lien (mobile)
    nav.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", () => {
        if (window.innerWidth <= 768) {
          nav.classList.remove("nav-open");
          setExpanded(false);
        }
      });
    });

    // Si on repasse en desktop, on ferme (évite menu ouvert en grand écran)
    window.addEventListener("resize", () => {
      if (window.innerWidth > 768) {
        nav.classList.remove("nav-open");
        setExpanded(false);
      }
    });
  }
});
