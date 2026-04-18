(function () {
  function init() {
    const container = document.querySelector(".container");
    const allMenus = document.querySelectorAll(".menu");

    if (!allMenus.length) return;

    document.body.addEventListener("click", () => {
      allMenus.forEach(menu => {
        if (menu.classList.contains("open")) {
          menu.classList.remove("open");
        }
      });
    });

    window.addEventListener("resize", () => {
      allMenus.forEach(menu => menu.classList.remove("open"));
    });

    allMenus.forEach(menu => {
      const trigger = menu.querySelector(".menu__trigger");
      const dropdown = menu.querySelector(".menu__dropdown");
      if (!trigger || !dropdown) return;

      trigger.addEventListener("click", e => {
        e.stopPropagation();

        if (menu.classList.contains("open")) {
          menu.classList.remove("open");
        } else {
          allMenus.forEach(m => m.classList.remove("open"));
          menu.classList.add("open");
        }

        if (container && dropdown.getBoundingClientRect().right > container.getBoundingClientRect().right) {
          dropdown.style.left = "auto";
          dropdown.style.right = 0;
        }
      });

      dropdown.addEventListener("click", e => e.stopPropagation());
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
