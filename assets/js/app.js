(function () {
    var themeToggle = document.querySelector(".theme-toggle");

    function paintThemeToggle() {
        if (!themeToggle) {
            return;
        }
        var dark = document.documentElement.dataset.theme === "dark";
        themeToggle.textContent = dark ? "Light" : "Dark";
        themeToggle.setAttribute("aria-pressed", dark ? "true" : "false");
    }

    if (themeToggle) {
        paintThemeToggle();
        themeToggle.addEventListener("click", function () {
            var next = document.documentElement.dataset.theme === "dark" ? "light" : "dark";
            document.documentElement.dataset.theme = next;
            try {
                window.localStorage.setItem("faultline.theme", next);
            } catch (error) {}
            paintThemeToggle();
        });
    }

    var header = document.querySelector(".site-header");
    var toggle = document.querySelector(".nav-toggle");

    if (header && toggle) {
        toggle.addEventListener("click", function () {
            var open = header.classList.toggle("nav-open");
            toggle.setAttribute("aria-expanded", open ? "true" : "false");
        });

        header.addEventListener("click", function (event) {
            if (event.target.closest(".nav-link") && window.innerWidth <= 900) {
                header.classList.remove("nav-open");
                toggle.setAttribute("aria-expanded", "false");
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
