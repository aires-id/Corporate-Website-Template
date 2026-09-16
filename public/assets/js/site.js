/* SPDX-License-Identifier: NCSA */
(() => {
  'use strict';

  // Keep policy navigation below the actual header, including after zoom/resizing.
  const siteHeader = document.querySelector('.site-header');
  if (siteHeader && document.querySelector('.policy-layout')) {
    const updateHeaderHeight = () => {
      document.documentElement.style.setProperty('--site-header-height', `${Math.ceil(siteHeader.getBoundingClientRect().height)}px`);
    };
    updateHeaderHeight();
    if ('ResizeObserver' in window) {
      new ResizeObserver(updateHeaderHeight).observe(siteHeader);
    } else {
      window.addEventListener('resize', updateHeaderHeight, { passive: true });
    }
  }

  const menuButton = document.querySelector('[data-menu-toggle]');
  const drawer = document.getElementById('mobile-drawer');
  const overlay = document.querySelector('[data-drawer-overlay]');
  const closeButton = document.querySelector('[data-menu-close]');

  const drawerControls = () => drawer
    ? [...drawer.querySelectorAll('a[href], button:not([disabled]), [tabindex]')]
    : [];
  const drawerFocusables = () => drawerControls().filter((element) => element.getAttribute('tabindex') !== '-1');
  const setDrawerInert = (isInert) => {
    if (!drawer) return;
    if ('inert' in drawer) {
      drawer.inert = isInert;
      return;
    }
    drawerControls().forEach((element) => {
      if (isInert) {
        element.dataset.drawerTabindex = element.getAttribute('tabindex') || '';
        element.setAttribute('tabindex', '-1');
      } else if ('drawerTabindex' in element.dataset) {
        const original = element.dataset.drawerTabindex;
        if (original) element.setAttribute('tabindex', original);
        else element.removeAttribute('tabindex');
        delete element.dataset.drawerTabindex;
      }
    });
  };

  const closeDrawer = () => {
    if (!drawer || !overlay || !menuButton) return;
    drawer.classList.remove('is-open');
    drawer.setAttribute('aria-hidden', 'true');
    setDrawerInert(true);
    overlay.hidden = true;
    menuButton.setAttribute('aria-expanded', 'false');
    document.body.classList.remove('drawer-open');
    menuButton.focus();
  };

  const openDrawer = () => {
    if (!drawer || !overlay || !menuButton) return;
    overlay.hidden = false;
    drawer.classList.add('is-open');
    drawer.setAttribute('aria-hidden', 'false');
    setDrawerInert(false);
    menuButton.setAttribute('aria-expanded', 'true');
    document.body.classList.add('drawer-open');
    closeButton?.focus();
  };

  menuButton?.addEventListener('click', () => {
    drawer?.classList.contains('is-open') ? closeDrawer() : openDrawer();
  });
  closeButton?.addEventListener('click', closeDrawer);
  overlay?.addEventListener('click', closeDrawer);
  drawer?.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeDrawer));
  setDrawerInert(true);
  const desktopDropdowns = [...document.querySelectorAll('.nav-dropdown')];
  desktopDropdowns.forEach((dropdown) => {
    dropdown.addEventListener('toggle', () => {
      if (!dropdown.open) return;
      desktopDropdowns.forEach((other) => {
        if (other !== dropdown) other.removeAttribute('open');
      });
    });
  });
  document.addEventListener('click', (event) => {
    if (!event.target.closest('.nav-dropdown')) {
      desktopDropdowns.forEach((dropdown) => dropdown.removeAttribute('open'));
    }
  });
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      desktopDropdowns.forEach((dropdown) => dropdown.removeAttribute('open'));
      if (drawer?.classList.contains('is-open')) closeDrawer();
      return;
    }
    if (event.key !== 'Tab' || !drawer?.classList.contains('is-open')) return;
    const focusables = drawerFocusables();
    if (!focusables.length) {
      event.preventDefault();
      return;
    }
    const first = focusables[0];
    const last = focusables[focusables.length - 1];
    if (!drawer.contains(document.activeElement)) {
      event.preventDefault();
      first.focus();
    } else if (event.shiftKey && document.activeElement === first) {
      event.preventDefault();
      last.focus();
    } else if (!event.shiftKey && document.activeElement === last) {
      event.preventDefault();
      first.focus();
    }
  });

  const formErrors = document.getElementById('form-errors');
  if (formErrors) window.requestAnimationFrame(() => formErrors.focus());

  const consentKey = 'organization_portal_cookie_consent';
  const banner = document.getElementById('cookie-banner');
  const getConsent = () => {
    try {
      return window.localStorage.getItem(consentKey) || document.cookie.match(/(?:^|; )organization_portal_cookie_consent=([^;]+)/)?.[1] || '';
    } catch (_) {
      return document.cookie.match(/(?:^|; )organization_portal_cookie_consent=([^;]+)/)?.[1] || '';
    }
  };
  const saveConsent = (choice) => {
    try { window.localStorage.setItem(consentKey, choice); } catch (_) { /* Cookie remains available. */ }
    document.cookie = `organization_portal_cookie_consent=${encodeURIComponent(choice)}; path=/; max-age=31536000; samesite=lax`;
  };

  const loadAds = () => {
    const client = document.body.dataset.adsClient?.trim();
    if (!client || document.querySelector('script[data-google-ads]')) return;
    const script = document.createElement('script');
    script.async = true;
    script.dataset.googleAds = 'true';
    script.crossOrigin = 'anonymous';
    script.src = `https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=${encodeURIComponent(client)}`;
    script.addEventListener('load', () => {
      document.querySelectorAll('[data-ad-slot]').forEach((slot) => {
        if (slot.dataset.adLoaded === 'true') return;
        slot.dataset.adLoaded = 'true';
        slot.textContent = '';
        const ad = document.createElement('ins');
        ad.className = 'adsbygoogle';
        ad.style.display = 'block';
        ad.dataset.adClient = client;
        ad.dataset.adFormat = 'auto';
        ad.dataset.fullWidthResponsive = 'true';
        slot.appendChild(ad);
        try { (window.adsbygoogle = window.adsbygoogle || []).push({}); } catch (_) { /* Ad blocking must not affect content. */ }
      });
    });
    document.head.appendChild(script);
  };

  const consent = getConsent();
  if (!consent && banner) banner.hidden = false;
  if (consent === 'accepted') loadAds();
  banner?.querySelectorAll('[data-cookie-choice]').forEach((button) => {
    button.addEventListener('click', () => {
      const choice = button.dataset.cookieChoice === 'accepted' ? 'accepted' : 'rejected';
      saveConsent(choice);
      banner.hidden = true;
      if (choice === 'accepted') loadAds();
    });
  });

  const editor = document.querySelector('[data-rich-editor]');
  const editorInput = document.querySelector('[data-editor-input]');
  const form = editor?.closest('form');
  const safeUrl = (value) => {
    try {
      const url = new URL(value, window.location.origin);
      return url.protocol === 'https:' ? url.href : '';
    } catch (_) {
      return '';
    }
  };
  const insertHtml = (html) => document.execCommand('insertHTML', false, html);
  const escapeHtml = (value) => value.replace(/[&<>"']/g, (character) => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
  }[character]));

  document.querySelectorAll('[data-editor-command]').forEach((button) => {
    button.addEventListener('click', () => {
      if (!editor) return;
      editor.focus();
      const command = button.dataset.editorCommand;
      if (command === 'link' || command === 'image') {
        const value = window.prompt(command === 'link' ? 'Masukkan URL tautan (https://)' : 'Masukkan URL gambar (https://)');
        const url = value ? safeUrl(value) : '';
        if (!url) return;
        if (command === 'link') document.execCommand('createLink', false, url);
        if (command === 'image') {
          const description = window.prompt('Deskripsi gambar untuk pembaca layar (boleh dikosongkan bila dekoratif):');
          insertHtml(`<img src="${escapeHtml(url)}" alt="${escapeHtml((description || '').trim())}">`);
        }
      } else if (command === 'ad') {
        insertHtml('<p data-ad-slot="article-inline">Iklan akan tampil di sini bila cookie iklan disetujui.</p>');
      } else if (command === 'h2' || command === 'h3' || command === 'p') {
        document.execCommand('formatBlock', false, command.toUpperCase());
      } else {
        document.execCommand(command, false);
      }
      editorInput && (editorInput.value = editor.innerHTML);
    });
  });
  editor?.addEventListener('input', () => { if (editorInput) editorInput.value = editor.innerHTML; });
  form?.addEventListener('submit', () => { if (editor && editorInput) editorInput.value = editor.innerHTML; });
})();
