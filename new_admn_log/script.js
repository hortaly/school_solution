document.addEventListener("DOMContentLoaded", () => {
  // ==========================================
  // 1. HIGH-VISIBILITY SHOOTING STARS BACKGROUND
  // ==========================================
  const canvas = document.getElementById("star-canvas");
  if (canvas) {
    const ctx = canvas.getContext("2d");
    let width = (canvas.width = window.innerWidth);
    let height = (canvas.height = window.innerHeight);

    window.addEventListener("resize", () => {
      width = canvas.width = window.innerWidth;
      height = canvas.height = window.innerHeight;
    });

    const stars = [];
    const numStars = Math.floor((width * height) / 5000);
    for (let i = 0; i < numStars; i++) {
      stars.push({
        x: Math.random() * width,
        y: Math.random() * height,
        radius: Math.random() * 1.6,
        alpha: Math.random(),
        delta: (Math.random() * 0.025 + 0.008) * (Math.random() < 0.5 ? 1 : -1)
      });
    }

    const shootingStars = [];
    function createShootingStar() {
      return {
        x: Math.random() * width * 1.4,
        y: Math.random() * (height / 2),
        length: Math.random() * 140 + 70,
        speed: Math.random() * 16 + 12,
        angle: Math.PI / 4,
        opacity: 1,
        decay: Math.random() * 0.035 + 0.012
      };
    }

    setInterval(() => {
      if (shootingStars.length < 8 && Math.random() < 0.95) {
        shootingStars.push(createShootingStar());
      }
    }, 350);

    function animateBackground() {
      ctx.clearRect(0, 0, width, height);

      // Draw twinkling background stars
      stars.forEach(star => {
        star.alpha += star.delta;
        if (star.alpha <= 0.15 || star.alpha >= 1) star.delta *= -1;
        ctx.fillStyle = `rgba(255, 255, 255, ${star.alpha})`;
        ctx.beginPath();
        ctx.arc(star.x, star.y, star.radius, 0, Math.PI * 2);
        ctx.fill();
      });

      // Draw high-visibility shooting stars
      ctx.lineWidth = 3;
      for (let i = shootingStars.length - 1; i >= 0; i--) {
        let ss = shootingStars[i];
        let tailX = ss.x - Math.cos(ss.angle) * ss.length;
        let tailY = ss.y - Math.sin(ss.angle) * ss.length;

        let gradient = ctx.createLinearGradient(ss.x, ss.y, tailX, tailY);
        gradient.addColorStop(0, `rgba(255, 255, 255, ${ss.opacity})`);
        gradient.addColorStop(0.3, `rgba(56, 189, 248, ${ss.opacity * 0.9})`);
        gradient.addColorStop(1, `rgba(2, 132, 199, 0)`);

        ctx.strokeStyle = gradient;
        ctx.beginPath();
        ctx.moveTo(ss.x, ss.y);
        ctx.lineTo(tailX, tailY);
        ctx.stroke();

        ss.x += Math.cos(ss.angle) * ss.speed;
        ss.y += Math.sin(ss.angle) * ss.speed;
        ss.opacity -= ss.decay;

        if (ss.opacity <= 0 || ss.x > width || ss.y > height) {
          shootingStars.splice(i, 1);
        }
      }

      requestAnimationFrame(animateBackground);
    }
    animateBackground();
  }

  // ==========================================
  // 2. GSAP ENTRANCE ANIMATIONS
  // ==========================================
  const card = document.querySelector(".auth-card");
  const formGroups = document.querySelectorAll(".form-group");
  const otpInputs = document.querySelectorAll(".otp-input");

  if (card) {
    const tl = gsap.timeline();
    tl.to(card, {
      opacity: 1,
      y: 0,
      duration: 0.8,
      ease: "power3.out"
    });

    if (formGroups.length > 0) {
      tl.to(formGroups, {
        opacity: 1,
        y: 0,
        duration: 0.5,
        stagger: 0.08,
        ease: "power2.out"
      }, "-=0.4");
    }

    if (otpInputs.length > 0) {
      tl.to(otpInputs, {
        opacity: 1,
        scale: 1,
        duration: 0.4,
        stagger: 0.05,
        ease: "back.out(1.7)"
      }, "-=0.2");
    }
  }

  // ==========================================
  // 3. SHOW / HIDE PASSWORD TOGGLE
  // ==========================================
  document.querySelectorAll(".toggle-password").forEach(button => {
    button.addEventListener("click", () => {
      const targetId = button.getAttribute("data-target");
      const input = document.getElementById(targetId);
      if (input) {
        if (input.type === "password") {
          input.type = "text";
          button.textContent = "Hide";
        } else {
          input.type = "password";
          button.textContent = "Show";
        }
      }
    });
  });

  // ==========================================
  // 4. SIGN UP WIZARD STEP NAVIGATION
  // ==========================================
  const nextBtn = document.getElementById("next-step-btn");
  const prevBtn = document.getElementById("prev-step-btn");
  const step1 = document.getElementById("step-1");
  const step2 = document.getElementById("step-2");
  const wizardSubtitle = document.getElementById("wizard-subtitle");

  if (nextBtn && prevBtn && step1 && step2) {
    nextBtn.addEventListener("click", (e) => {
      e.preventDefault();
      const fullname = document.getElementById("fullname").value.trim();
      const phone = document.getElementById("phone_number").value.trim();

      if (!fullname || !phone) {
        alert("Please fill in all fields in Step 1 before proceeding.");
        return;
      }

      gsap.to(step1, {
        opacity: 0,
        x: -20,
        duration: 0.25,
        onComplete: () => {
          step1.style.display = "none";
          step2.style.display = "block";
          if (wizardSubtitle) wizardSubtitle.textContent = "Step 2 of 2: Security";
          gsap.fromTo(step2, { opacity: 0, x: 20 }, { opacity: 1, x: 0, duration: 0.25 });
        }
      });
    });

    prevBtn.addEventListener("click", (e) => {
      e.preventDefault();
      gsap.to(step2, {
        opacity: 0,
        x: 20,
        duration: 0.25,
        onComplete: () => {
          step2.style.display = "none";
          step1.style.display = "block";
          if (wizardSubtitle) wizardSubtitle.textContent = "Step 1 of 2: Basic Information";
          gsap.fromTo(step1, { opacity: 0, x: -20 }, { opacity: 1, x: 0, duration: 0.25 });
        }
      });
    });
  }

  // ==========================================
  // 5. OTP BOX AUTO-FOCUS NAVIGATION
  // ==========================================
  const boxes = document.querySelectorAll(".otp-input");
  if (boxes.length > 0) {
    boxes.forEach((box, index) => {
      box.addEventListener("input", (e) => {
        const val = e.target.value;
        if (val && /^\d$/.test(val)) {
          box.value = val;
          if (index < boxes.length - 1) {
            boxes[index + 1].focus();
          }
        } else {
          box.value = "";
        }
      });

      box.addEventListener("keydown", (e) => {
        if (e.key === "Backspace") {
          if (box.value === "" && index > 0) {
            boxes[index - 1].focus();
            boxes[index - 1].value = "";
          } else {
            box.value = "";
          }
        }
      });

      box.addEventListener("paste", (e) => {
        e.preventDefault();
        const pasteData = e.clipboardData.getData("text").trim();
        if (/^\d{6}$/.test(pasteData)) {
          boxes.forEach((b, idx) => {
            b.value = pasteData[idx];
          });
          boxes[boxes.length - 1].focus();
        }
      });
    });
  }
});