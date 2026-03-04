(function () {
  const form = document.getElementById("catalogFilters");
  if (!form) return;

  const selCat = document.getElementById("category");
  const selPrice = document.getElementById("priceRange");
  const txtSearch = document.getElementById("searchText");

  const items = Array.from(document.querySelectorAll(".vehicle-item"));

  function inPriceRange(price, range) {
    if (!range) return true;
    if (range === "200+") return price >= 200;

    const [a, b] = range.split("-").map(n => Number(n));
    return price >= a && price <= b;
  }

  function applyFilters() {
    const cat = (selCat?.value || "").trim();
    const pr = (selPrice?.value || "").trim();
    const q = (txtSearch?.value || "").trim().toLowerCase();

    items.forEach(el => {
      const vCat = (el.dataset.category || "").trim();
      const vPrice = Number(el.dataset.price || 0);
      const vName = (el.dataset.name || "");

      const okCat = !cat || vCat === cat;
      const okPrice = inPriceRange(vPrice, pr);
      const okSearch = !q || vName.includes(q);

      el.style.display = (okCat && okPrice && okSearch) ? "" : "none";
    });
  }

  form.addEventListener("input", applyFilters);
  form.addEventListener("change", applyFilters);
})();