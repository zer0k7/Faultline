(function () {
    var key = "faultline.lab.progress.v1";
    var state = {};

    try {
        state = JSON.parse(window.localStorage.getItem(key) || "{}") || {};
    } catch (error) {
        state = {};
    }

    function save() {
        window.localStorage.setItem(key, JSON.stringify(state));
    }

    function update() {
        var total = 0;
        var solved = 0;

        document.querySelectorAll("[data-category]").forEach(function (section) {
            var cards = section.querySelectorAll(".lab-module[data-module]");
            var catTotal = cards.length;
            var catSolved = 0;

            cards.forEach(function (card) {
                var moduleKey = card.getAttribute("data-module");
                if (state[moduleKey]) {
                    catSolved += 1;
                    card.classList.add("is-solved");
                    var status = card.querySelector("[data-status]");
                    if (status && !status.textContent) {
                        status.textContent = "Solved.";
                        status.className = "flag-status ok";
                    }
                } else {
                    card.classList.remove("is-solved");
                }
            });

            total += catTotal;
            solved += catSolved;

            var count = document.getElementById("cat-count-" + section.getAttribute("data-category"));
            var bar = document.getElementById("cat-bar-" + section.getAttribute("data-category"));
            if (count) {
                count.textContent = catSolved + " / " + catTotal;
            }
            if (bar) {
                bar.style.width = catTotal ? (catSolved / catTotal) * 100 + "%" : "0%";
            }
        });

        var overallCount = document.getElementById("overall-count");
        var overallBar = document.getElementById("overall-bar");
        if (overallCount) {
            overallCount.textContent = solved + " / " + total;
        }
        if (overallBar) {
            overallBar.style.width = total ? (solved / total) * 100 + "%" : "0%";
        }
    }

    document.querySelectorAll(".flag-form").forEach(function (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault();
            var moduleKey = form.getAttribute("data-module");
            var input = form.querySelector('input[name="flag"]');
            var button = form.querySelector('button[type="submit"]');
            var status = form.parentElement.querySelector("[data-status]");
            if (!input || !input.value.trim()) {
                return;
            }
            if (button) {
                button.disabled = true;
            }
            if (status) {
                status.textContent = "Checking.";
                status.className = "flag-status";
            }

            fetch("check.php", {
                method: "POST",
                body: new FormData(form),
                headers: { "X-Requested-With": "fetch" },
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    if (data && data.ok) {
                        state[moduleKey] = true;
                        save();
                        if (status) {
                            status.textContent = "Accepted.";
                            status.className = "flag-status ok";
                        }
                        update();
                    } else {
                        if (status) {
                            status.textContent = "Not accepted.";
                            status.className = "flag-status bad";
                        }
                    }
                })
                .catch(function () {
                    if (status) {
                        status.textContent = "Check failed. Try again.";
                        status.className = "flag-status bad";
                    }
                })
                .finally(function () {
                    if (button) {
                        button.disabled = false;
                    }
                });
        });
    });

    var reset = document.getElementById("reset-progress");
    if (reset) {
        reset.addEventListener("click", function () {
            if (!window.confirm("Clear all solved modules in this browser?")) {
                return;
            }
            state = {};
            save();
            document.querySelectorAll("[data-status]").forEach(function (node) {
                node.textContent = "";
                node.className = "flag-status";
            });
            update();
        });
    }

    update();
})();
