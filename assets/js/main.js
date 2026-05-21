/* ============================================================
   PORTAFOLIO — main.js
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

    /* ---- Swiper carousel (Habilidades) ---- */
    if (document.querySelector('.skillSwiper')) {
        new Swiper('.skillSwiper', {
            slidesPerView: 1,
            spaceBetween: 16,
            loop: true,
            pagination: { el: '.swiper-pagination', clickable: true },
            breakpoints: {
                480:  { slidesPerView: 2 },
                768:  { slidesPerView: 3 },
                1024: { slidesPerView: 4 },
            },
        });
    }

    /* ---- Intersection Observer: fade-in-up ---- */
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('visible');
                observer.unobserve(e.target);
            }
        });
    }, { threshold: 0.15 });

    document.querySelectorAll('.fade-in-up').forEach(el => observer.observe(el));

    /* ---- Animate tech progress bars ---- */
    const barObserver = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.querySelectorAll('.tech-fill').forEach(bar => {
                    bar.style.width = bar.dataset.width + '%';
                });
                barObserver.unobserve(e.target);
            }
        });
    }, { threshold: 0.2 });

    const techSection = document.querySelector('#tecnologias');
    if (techSection) barObserver.observe(techSection);

    /* ---- Active nav link on scroll ---- */
    const sections = document.querySelectorAll('section[id]');
    const navLinks  = document.querySelectorAll('#mainNav .nav-link');

    const scrollSpy = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                navLinks.forEach(a => a.classList.remove('active'));
                const active = document.querySelector(`#mainNav a[href="#${e.target.id}"]`);
                if (active) active.classList.add('active');
            }
        });
    }, { rootMargin: '-40% 0px -55% 0px' });

    sections.forEach(s => scrollSpy.observe(s));

    /* ---- Contact form AJAX ---- */
    const form      = document.getElementById('contactForm');
    const alertBox  = document.getElementById('contactAlert');
    const btnSend   = document.getElementById('btnSend');

    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            alertBox.innerHTML = '';

            const data = new FormData(form);
            btnSend.disabled = true;
            btnSend.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Enviando…';

            try {
                const res  = await fetch('api/contacto.php', { method: 'POST', body: data });
                const json = await res.json();

                if (json.success) {
                    alertBox.innerHTML = `<div class="alert-custom alert-success"><i class="bi bi-check-circle me-1"></i>${json.message}</div>`;
                    form.reset();
                } else {
                    alertBox.innerHTML = `<div class="alert-custom alert-error"><i class="bi bi-exclamation-circle me-1"></i>${json.message}</div>`;
                }
            } catch {
                alertBox.innerHTML = `<div class="alert-custom alert-error"><i class="bi bi-exclamation-circle me-1"></i>Error de conexión. Intenta nuevamente.</div>`;
            } finally {
                btnSend.disabled = false;
                btnSend.innerHTML = 'Enviar mensaje <i class="bi bi-send"></i>';
            }
        });
    }

});
