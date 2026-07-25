(function () {
  'use strict';

  var header = document.getElementById('header');
  var hamburger = document.getElementById('hamburger');
  var nav = document.getElementById('nav');
  var backToTop = document.getElementById('backToTop');

  if (header) {
    var ticking = false;
    window.addEventListener('scroll', function () {
      if (!ticking) {
        requestAnimationFrame(function () {
          header.classList.toggle('scrolled', window.scrollY > 60);
          ticking = false;
        });
        ticking = true;
      }
    });
  }

  var backdrop = document.getElementById('navBackdrop');

  function closeNav() {
    hamburger.classList.remove('active');
    nav.classList.remove('active');
    if (backdrop) backdrop.classList.remove('active');
    document.body.classList.remove('nav-open');
  }

  if (hamburger && nav) {
    hamburger.addEventListener('click', function () {
      hamburger.classList.toggle('active');
      nav.classList.toggle('active');
      if (backdrop) backdrop.classList.toggle('active');
      document.body.classList.toggle('nav-open');
    });

    if (backdrop) {
      backdrop.addEventListener('click', closeNav);
    }

    Array.prototype.forEach.call(nav.querySelectorAll('.nav-link'), function (link) {
      link.addEventListener('click', closeNav);
    });
  }

  if (backToTop) {
    window.addEventListener('scroll', function () {
      backToTop.classList.toggle('show', window.scrollY > 500);
    });

    backToTop.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
      }
    });
  }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

  Array.prototype.forEach.call(document.querySelectorAll('.animate-in, .animate-in-left, .animate-in-right, .animate-scale'), function (el) {
    observer.observe(el);
  });

  (function () {
    var lks = document.querySelectorAll('.nav-link[data-section]');
    if (lks.length === 0) return;
    function upd() {
      var sy = window.pageYOffset || document.documentElement.scrollTop;
      sy += 100;
      var cur = null;
      for (var i = 0; i < lks.length; i++) {
        var el = document.getElementById(lks[i].getAttribute('data-section'));
        if (el && el.offsetTop <= sy) cur = lks[i];
      }
      for (var j = 0; j < lks.length; j++) { lks[j].classList.remove('active'); }
      if (cur) cur.classList.add('active');
    }
    window.addEventListener('scroll', upd, { passive: true });
    upd();
  })();

  Array.prototype.forEach.call(document.querySelectorAll('.modal-overlay'), function (overlay) {
    var closeBtn = overlay.querySelector('.modal-close');
    if (closeBtn) {
      closeBtn.addEventListener('click', function () { overlay.classList.remove('active'); });
    }
    overlay.addEventListener('click', function (e) {
      if (e.target === overlay) overlay.classList.remove('active');
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && overlay.classList.contains('active')) {
        overlay.classList.remove('active');
      }
    });
  });

  Array.prototype.forEach.call(document.querySelectorAll('.legal-card'), function (card) {
    card.addEventListener('click', function () {
      var modalId = card.getAttribute('data-modal');
      if (modalId) {
        var modal = document.getElementById(modalId);
        if (modal) modal.classList.add('active');
      }
    });
  });

  Array.prototype.forEach.call(document.querySelectorAll('.service-card .btn-sm'), function (btn) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      var modalId = btn.getAttribute('data-modal');
      if (modalId) {
        var modal = document.getElementById(modalId);
        if (modal) modal.classList.add('active');
      }
    });
  });

  var form = document.getElementById('contactForm');
  if (form) {
    var csrfInput = document.getElementById('csrf_token');
    var formSuccess = document.getElementById('formSuccess');
    var fields = {
      name: { input: document.getElementById('form_name'), error: document.getElementById('error_name'), validate: function (v) { return v.trim().length >= 2; } },
      email: { input: document.getElementById('form_email'), error: document.getElementById('error_email'), validate: function (v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); } },
      phone: { input: document.getElementById('form_phone'), error: document.getElementById('error_phone'), validate: function (v) { return v.trim() === '' || /^[\d\s+\-()]{7,20}$/.test(v); } },
      message: { input: document.getElementById('form_message'), error: document.getElementById('error_message'), validate: function (v) { return v.trim().length >= 10; } }
    };

    Object.keys(fields).forEach(function (key) {
      var f = fields[key];
      if (f.input) {
        f.input.addEventListener('blur', function () {
          var valid = f.validate(f.input.value);
          if (f.error) f.error.style.display = valid ? 'none' : 'block';
          f.input.style.borderColor = valid ? '#e5e7eb' : '#dc3545';
        });
      }
    });

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var valid = true;

      Object.keys(fields).forEach(function (key) {
        var f = fields[key];
        if (f.input) {
          var v = f.validate(f.input.value);
          if (!v) valid = false;
          if (f.error) f.error.style.display = v ? 'none' : 'block';
          f.input.style.borderColor = v ? '#e5e7eb' : '#dc3545';
        }
      });

      if (!valid) return;

      var formData = new FormData(form);
      if (csrfInput) formData.set('csrf_token', csrfInput.value);

      var submitBtn = form.querySelector('button[type="submit"]');
      if (submitBtn) { submitBtn.disabled = true; submitBtn.textContent = 'Sending...'; }

      fetch(window.location.href + '?action=contact', { method: 'POST', body: formData })
        .then(function (res) { return res.json(); })
        .then(function (data) {
          if (data.success) {
            if (formSuccess) {
              formSuccess.style.display = 'block';
              formSuccess.textContent = 'Thank you! Your message has been sent successfully. We will get back to you shortly.';
            }
            form.reset();
            Object.keys(fields).forEach(function (key) {
              var f = fields[key];
              if (f.input) f.input.style.borderColor = '#e5e7eb';
            });
          } else {
            alert(data.message || 'Something went wrong. Please try again.');
          }
        })
        .catch(function () { alert('Network error. Please try again.'); })
        .finally(function () {
          if (submitBtn) { submitBtn.disabled = false; submitBtn.textContent = 'Send Message'; }
        });
    });
  }
})();
