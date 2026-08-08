/* ==========================================
   SANO TECH SHOP ERP
   MAIN JAVASCRIPT
========================================== */

document.addEventListener("DOMContentLoaded", function () {

    /* ==========================
       PRELOADER
    ========================== */

    const loader = document.getElementById("loader");

    if (loader) {
        window.addEventListener("load", () => {

            loader.style.opacity = "0";

            setTimeout(() => {
                loader.style.display = "none";
            }, 500);

        });
    }

    /* ==========================
       AOS
    ========================== */

    if (typeof AOS !== "undefined") {
        AOS.init({
            duration: 900,
            once: true,
            offset: 100
        });
    }

    /* ==========================
       STICKY NAVBAR
    ========================== */

    const navbar = document.querySelector(".navbar");

    function updateNavbar() {

        if (!navbar) return;

        if (window.scrollY > 60) {

            navbar.classList.add("navbar-scrolled");

        } else {

            navbar.classList.remove("navbar-scrolled");

        }

    }

    updateNavbar();

    window.addEventListener("scroll", updateNavbar);

    /* ==========================
       SMOOTH SCROLL
    ========================== */

    document.querySelectorAll('a[href^="#"]').forEach(link => {

        link.addEventListener("click", function (e) {

            const target = document.querySelector(this.getAttribute("href"));

            if (!target) return;

            e.preventDefault();

            window.scrollTo({

                top: target.offsetTop - 80,

                behavior: "smooth"

            });

        });

    });

    /* ==========================
       ACTIVE MENU
    ========================== */

    const sections = document.querySelectorAll("section[id]");

    const menuLinks = document.querySelectorAll(".navbar .nav-link");

    function activeMenu() {

        let current = "";

        sections.forEach(section => {

            const top = section.offsetTop - 120;

            const height = section.offsetHeight;

            if (window.scrollY >= top && window.scrollY < top + height) {

                current = section.getAttribute("id");

            }

        });

        menuLinks.forEach(link => {

            link.classList.remove("active");

            if (link.getAttribute("href") === "#" + current) {

                link.classList.add("active");

            }

        });

    }

    activeMenu();

    window.addEventListener("scroll", activeMenu);

    /* ==========================
       HERO PARALLAX
    ========================== */

    const hero = document.querySelector(".hero");

    if (hero) {

        window.addEventListener("scroll", () => {

            hero.style.backgroundPositionY =
                window.scrollY * 0.35 + "px";

        });

    }

    /* ==========================
       CARD HOVER
    ========================== */

    document.querySelectorAll(".module-card,.service-card,.mini-card,.stat").forEach(card => {

        card.addEventListener("mouseenter", () => {

            card.style.transform = "translateY(-10px)";

        });

        card.addEventListener("mouseleave", () => {

            card.style.transform = "";

        });

    });

    /* ==========================
       COUNTER
    ========================== */

    const counters = document.querySelectorAll(".stat h2");

    const observer = new IntersectionObserver(entries => {

        entries.forEach(entry => {

            if (!entry.isIntersecting) return;

            const el = entry.target;

            const text = el.innerText;

            const number = parseInt(text);

            if (isNaN(number)) return;

            let start = 0;

            const speed = number / 80;

            function update() {

                start += speed;

                if (start < number) {

                    el.innerText = Math.floor(start) + "+";

                    requestAnimationFrame(update);

                } else {

                    el.innerText = number + "+";

                }

            }

            update();

            observer.unobserve(el);

        });

    });

    counters.forEach(counter => observer.observe(counter));

    /* ==========================
       BACK TO TOP
    ========================== */

    let topBtn = document.createElement("button");

    topBtn.className = "back-top";

    topBtn.innerHTML = '<i class="fa-solid fa-arrow-up"></i>';

    document.body.appendChild(topBtn);

    window.addEventListener("scroll", () => {

        if (window.scrollY > 400) {

            topBtn.classList.add("show");

        } else {

            topBtn.classList.remove("show");

        }

    });

    topBtn.addEventListener("click", () => {

        window.scrollTo({

            top: 0,

            behavior: "smooth"

        });

    });

});