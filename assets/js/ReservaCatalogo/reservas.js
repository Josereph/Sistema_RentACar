// assets/js/ReservaCatalogo/reservas.js
(function () {
  const gallery = document.getElementById("vehicleGallery");
  if (!gallery) return;

  const mainImg = document.getElementById("mainGalleryImg");
  const thumbs = document.getElementById("galleryThumbs");
  if (!mainImg || !thumbs) return;

  thumbs.addEventListener("click", (e) => {
    const btn = e.target.closest(".thumbnail");
    if (!btn) return;

    const index = Number(btn.dataset.index);
    const imgEl = btn.querySelector("img");
    if (!imgEl) return;

    mainImg.src = imgEl.src;

    thumbs.querySelectorAll(".thumbnail").forEach(t => t.classList.remove("is-active"));
    btn.classList.add("is-active");
  });
})();