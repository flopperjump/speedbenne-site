document.addEventListener("DOMContentLoaded", () => {
  const input = document.getElementById("city-search");
  const button = document.getElementById("search-button");
  const cards = document.querySelectorAll(".zone-search .zone-card");

  const normalize = (s) =>
    (s || "")
      .toLowerCase()
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "");

  function filterZones() {
    const query = normalize(input.value.trim());

    cards.forEach((card) => {
      const citiesAttr = normalize(card.getAttribute("data-cities"));
      const cityItems = card.querySelectorAll(".second-content li");

      // reset
      card.classList.remove("is-open");

      cityItems.forEach((li) => {
        li.style.display = "list-item";
      });

      // si input vide => reset complet
      if (!query) {
        card.style.display = "flex";
        return;
      }

      // si le département ne contient pas la ville => on cache la carte
      if (!citiesAttr.includes(query)) {
        card.style.display = "none";
        return;
      }

      // sinon : on affiche + on ouvre la carte
      card.style.display = "flex";
      card.classList.add("is-open");

      // filtrer les villes DANS la carte
      cityItems.forEach((li) => {
        const cityName = normalize(li.textContent);
        if (!cityName.includes(query)) {
          li.style.display = "none";
        }
      });
    });
  }

  input.addEventListener("input", filterZones);
  button.addEventListener("click", filterZones);
});
