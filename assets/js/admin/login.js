// Toggle contraseña
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const icon = event.currentTarget.querySelector('.material-symbols-outlined');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.textContent = 'visibility_off';
    } else {
        passwordInput.type = 'password';
        icon.textContent = 'visibility';
    }
}

// Efecto parallax en la imagen de fondo
window.addEventListener('scroll', () => {
    const heroImage = document.getElementById('heroImage');
    if (heroImage) {
        const scrollY = window.scrollY;
        heroImage.style.transform = `translateY(${scrollY * 0.1}px) scale(1.1)`;
    }
});

// Partículas (opcional)
const canvas = document.getElementById('particles');
if (canvas) {
    const ctx = canvas.getContext('2d');
    let width, height;
    let particles = [];

    function initParticles() {
        width = window.innerWidth;
        height = window.innerHeight;
        canvas.width = width;
        canvas.height = height;
        particles = [];
        for (let i = 0; i < 50; i++) {
            particles.push({
                x: Math.random() * width,
                y: Math.random() * height,
                size: Math.random() * 2 + 1,
                speedY: Math.random() * 1 + 0.5,
                opacity: Math.random() * 0.5 + 0.2
            });
        }
    }

    function updateParticles() {
        ctx.clearRect(0, 0, width, height);
        particles.forEach(p => {
            p.y -= p.speedY;
            if (p.y < 0) {
                p.y = height;
            }
            ctx.globalAlpha = p.opacity;
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
            ctx.fillStyle = '#137fec';
            ctx.fill();
        });
        requestAnimationFrame(updateParticles);
    }

    window.addEventListener('resize', () => {
        initParticles();
    });

    initParticles();
    updateParticles();
}