(function () {
  const root = document.documentElement;

  // Theme init
  const saved = localStorage.getItem("gocar_theme");
  if (saved === "dark") root.classList.add("dark");

  // Toggle theme
  const btnTheme = document.querySelector("[data-theme-toggle]");
  const icon = document.querySelector("[data-theme-icon]");

  function syncIcon() {
    if (!icon) return;
    icon.textContent = root.classList.contains("dark") ? "light_mode" : "dark_mode";
  }
  syncIcon();

  if (btnTheme) {
    btnTheme.addEventListener("click", () => {
      root.classList.toggle("dark");
      localStorage.setItem("gocar_theme", root.classList.contains("dark") ? "dark" : "light");
      syncIcon();
    });
  }

  // Mobile menu
  const btnMobile = document.querySelector("[data-mobile-toggle]");
  const panel = document.querySelector("[data-mobile-panel]");
  if (btnMobile && panel) {
    btnMobile.addEventListener("click", () => {
      panel.classList.toggle("hidden");
    });
  }
})();