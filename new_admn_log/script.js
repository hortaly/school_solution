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

  function shakeForm() {
    if (card) {
      gsap.fromTo(card, 
        { x: -10 }, 
        { x: 10, duration: 0.1, repeat: 5, yoyo: true, ease: "power2.inOut", onComplete: () => gsap.set(card, { x: 0 }) }
      );
    }
  }

  function setButtonLoading(btn, isLoading) {
    const btnText = btn.querySelector(".btn-text");
    const spinner = btn.querySelector(".spinner");
    if (isLoading) {
      btn.disabled = true;
      if (btnText) btnText.style.display = "none";
      if (spinner) spinner.style.display = "block";
    } else {
      btn.disabled = false;
      if (btnText) btnText.style.display = "inline-block";
      if (spinner) spinner.style.display = "none";
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
  // 4. LOGIN FORM LOGIC (index.html)
  // ==========================================
  const loginForm = document.getElementById("login-form");
  if (loginForm) {
    loginForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const email = document.getElementById("email");
      const password = document.getElementById("password");
      let isValid = true;

      clearErrors();

      if (!validateEmail(email.value)) {
        showError(email, "Please enter a valid email address.");
        isValid = false;
      }
      if (!password.value || password.value.length < 8) {
        showError(password, "Password must be at least 8 characters.");
        isValid = false;
      }

      if (!isValid) {
        shakeForm();
        return;
      }

      const submitBtn = loginForm.querySelector("button[type='submit']");
      setButtonLoading(submitBtn, true);

      // TODO: Connect your backend / database API call here
      setTimeout(() => {
        setButtonLoading(submitBtn, false);
        alert("Login successful! (Plug in your backend API here)");
      }, 1500);
    });
  }

  // ==========================================
  // 5. SIGN UP WIZARD LOGIC (signup.html)
  // ==========================================
  const signupForm = document.getElementById("signup-form");
  if (signupForm) {
    const step1 = document.getElementById("step-1");
    const step2 = document.getElementById("step-2");
    const nextBtn = document.getElementById("next-step-btn");
    const prevBtn = document.getElementById("prev-step-btn");
    const wizardSubtitle = document.getElementById("wizard-subtitle");

    nextBtn.addEventListener("click", () => {
      const fullname = document.getElementById("fullname");
      const email = document.getElementById("email");
      let isValid = true;

      clearErrors();

      if (!fullname.value.trim()) {
        showError(fullname, "Full name is required.");
        isValid = false;
      }
      if (!validateEmail(email.value)) {
        showError(email, "Please enter a valid email address.");
        isValid = false;
      }

      if (!isValid) {
        shakeForm();
        return;
      }

      gsap.to(step1, {
        opacity: 0,
        x: -20,
        duration: 0.25,
        onComplete: () => {
          step1.style.display = "none";
          step2.style.display = "block";
          wizardSubtitle.textContent = "Step 2 of 2: Security";
          gsap.fromTo(step2, { opacity: 0, x: 20 }, { opacity: 1, x: 0, duration: 0.25 });
        }
      });
    });

    prevBtn.addEventListener("click", () => {
      gsap.to(step2, {
        opacity: 0,
        x: 20,
        duration: 0.25,
        onComplete: () => {
          step2.style.display = "none";
          step1.style.display = "block";
          wizardSubtitle.textContent = "Step 1 of 2: Basic Information";
          gsap.fromTo(step1, { opacity: 0, x: -20 }, { opacity: 1, x: 0, duration: 0.25 });
        }
      });
    });

    signupForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const password = document.getElementById("password");
      const confirmPassword = document.getElementById("confirm-password");
      let isValid = true;

      clearErrors();

      if (!password.value || password.value.length < 8) {
        showError(password, "Password must be at least 8 characters.");
        isValid = false;
      }
      if (password.value !== confirmPassword.value) {
        showError(confirmPassword, "Passwords do not match.");
        isValid = false;
      }

      if (!isValid) {
        shakeForm();
        return;
      }

      const submitBtn = signupForm.querySelector("button[type='submit']");
      setButtonLoading(submitBtn, true);

      // TODO: Connect your backend / database API call here
      setTimeout(() => {
        setButtonLoading(submitBtn, false);
        alert("Sign up wizard completed successfully! (Plug in your backend API here)");
      }, 1500);
    });
  }

  // ==========================================
  // 6. EMAIL FORM LOGIC (email.html)
  // ==========================================
  const emailForm = document.getElementById("email-form");
  if (emailForm) {
    emailForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const email = document.getElementById("reset-email");
      clearErrors();

      if (!validateEmail(email.value)) {
        showError(email, "Please enter a valid email address.");
        shakeForm();
        return;
      }

      const submitBtn = emailForm.querySelector("button[type='submit']");
      setButtonLoading(submitBtn, true);

      // TODO: Connect your backend API to trigger OTP sending here
      setTimeout(() => {
        setButtonLoading(submitBtn, false);
        window.location.href = "otp.html";
      }, 1500);
    });
  }

  // ==========================================
  // 7. OTP FORM LOGIC (otp.html)
  // ==========================================
  const otpForm = document.getElementById("otp-form");
  if (otpForm) {
    const boxes = document.querySelectorAll(".otp-input");

    boxes.forEach((box, index) => {
      box.addEventListener("input", (e) => {
        const val = e.target.value;
        if (val && /^\d$/.test(val)) {
          box.value = val;
          gsap.fromTo(box, { scale: 1.2 }, { scale: 1, duration: 0.3, ease: "back.out(2)" });
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
            gsap.fromTo(b, { scale: 1.2 }, { scale: 1, duration: 0.3, ease: "back.out(2)" });
          });
          boxes[boxes.length - 1].focus();
        }
      });
    });

    otpForm.addEventListener("submit", (e) => {
      e.preventDefault();
      let code = "";
      boxes.forEach(b => code += b.value);

      if (code.length < 6) {
        shakeForm();
        alert("Please enter the complete 6-digit code.");
        return;
      }

      const submitBtn = otpForm.querySelector("button[type='submit']");
      setButtonLoading(submitBtn, true);

      // TODO: Connect your backend API to verify OTP here
      setTimeout(() => {
        setButtonLoading(submitBtn, false);
        alert("OTP verified successfully! (Plug in your backend API here)");
      }, 1500);
    });
  }

  // Helper Functions
  function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  function showError(inputElement, message) {
    inputElement.classList.add("error");
    const errorDiv = inputElement.parentElement.parentElement.querySelector(".error-message");
    if (errorDiv) {
      errorDiv.textContent = message;
      errorDiv.style.display = "block";
    }
  }

  function clearErrors() {
    document.querySelectorAll(".input-wrapper input").forEach(input => input.classList.remove("error"));
    document.querySelectorAll(".error-message").forEach(msg => msg.style.display = "none");
  }
});