(function () {
    var header = document.querySelector(".site-header");
    var toggle = document.querySelector(".nav-toggle");

    function closeMenu() {
        if (!header || !toggle) {
            return;
        }
        header.classList.remove("nav-open");
        toggle.setAttribute("aria-expanded", "false");
        toggle.setAttribute("aria-label", "Open menu");
    }

    if (header && toggle) {
        toggle.setAttribute("aria-label", "Open menu");

        toggle.addEventListener("click", function () {
            var open = header.classList.toggle("nav-open");
            toggle.setAttribute("aria-expanded", open ? "true" : "false");
            toggle.setAttribute("aria-label", open ? "Close menu" : "Open menu");
        });

        header.addEventListener("click", function (event) {
            if (event.target.closest("a") && window.innerWidth <= 980) {
                closeMenu();
            }
        });

        document.addEventListener("keydown", function (event) {
            if (event.key === "Escape") {
                closeMenu();
            }
        });

        window.addEventListener("resize", function () {
            if (window.innerWidth > 980) {
                closeMenu();
            }
        });
    }

    var preview = document.getElementById("hash-preview");

    function paintHash() {
        if (!preview) {
            return;
        }
        if (window.location.hash.length > 1) {
            var raw = window.location.hash.slice(1);
            try {
                preview.innerHTML = decodeURIComponent(raw);
            } catch (error) {
                preview.textContent = raw;
            }
        } else {
            preview.innerHTML = "";
        }
    }

    if (preview) {
        paintHash();
        window.addEventListener("hashchange", paintHash);
    }

    document.querySelectorAll("[data-confirm]").forEach(function (node) {
        node.addEventListener("click", function (event) {
            if (!window.confirm(node.getAttribute("data-confirm"))) {
                event.preventDefault();
            }
        });
    });
})();
