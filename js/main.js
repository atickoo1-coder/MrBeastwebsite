// Cart State
let cart = JSON.parse(localStorage.getItem('mrbeastCart')) || [];

function saveCart() {
  localStorage.setItem('mrbeastCart', JSON.stringify(cart));
  updateCartUI();
}

function addToCart(product) {
  const existing = cart.find(item => item.id === product.id && item.size === product.size && item.color === product.color);
  if (existing) {
    existing.qty += product.qty || 1;
  } else {
    cart.push({ ...product, qty: product.qty || 1 });
  }
  saveCart();
  openCart();
}

function removeFromCart(index) {
  cart.splice(index, 1);
  saveCart();
}

function updateQty(index, delta) {
  const newQty = cart[index].qty + delta;
  if (newQty <= 0) {
    removeFromCart(index);
    return;
  }
  cart[index].qty = newQty;
  saveCart();
}

function getCartTotal() {
  return cart.reduce((sum, item) => {
    const price = parseFloat(item.price.replace('$', '')) || 0;
    return sum + price * item.qty;
  }, 0);
}

function getCartCount() {
  return cart.reduce((sum, item) => sum + item.qty, 0);
}

function updateCartUI() {
  const countEls = document.querySelectorAll('.cart-count');
  const count = getCartCount();
  countEls.forEach(el => { el.textContent = count; });

  const body = document.querySelector('.cart-drawer-body');
  const footer = document.querySelector('.cart-drawer-footer');
  if (!body) return;

  if (cart.length === 0) {
    body.innerHTML = `
      <div class="cart-empty">
        <div class="cart-empty-icon">&#128722;</div>
        <p>Your cart is empty!</p>
        <p style="font-size:13px;color:#888;">Shop some of our faves</p>
        <div style="display:flex;gap:8px;justify-content:center;margin-top:12px;">
          <a href="shop-all.html" class="btn-primary" style="font-size:12px;padding:10px 20px;">Shop Youth</a>
          <a href="shop-all.html" class="btn-primary" style="font-size:12px;padding:10px 20px;">Shop Adults</a>
        </div>
      </div>
    `;
    if (footer) footer.style.display = 'none';
    return;
  }

  if (footer) footer.style.display = 'block';

  let html = '';
  cart.forEach((item, index) => {
    const price = parseFloat(item.price.replace('$', '')) || 0;
    html += `
      <div class="cart-item">
        <div class="cart-item-image">
          <img src="${item.image || 'https://picsum.photos/seed/placeholder/200/200'}" alt="${item.title}">
        </div>
        <div class="cart-item-info">
          <div class="cart-item-title">${item.title}</div>
          <div style="font-size:12px;color:#888;">${item.size || ''}${item.color ? ' / ' + item.color : ''}</div>
          <div class="cart-item-price">$${(price * item.qty).toFixed(2)}</div>
          <div class="cart-item-quantity">
            <button class="qty-btn" onclick="updateQty(${index}, -1)">-</button>
            <span class="qty-value">${item.qty}</span>
            <button class="qty-btn" onclick="updateQty(${index}, 1)">+</button>
          </div>
          <div class="cart-item-remove" onclick="removeFromCart(${index})">Remove</div>
        </div>
      </div>
    `;
  });
  body.innerHTML = html;

  const totalEl = document.querySelector('.cart-total-value');
  if (totalEl) {
    totalEl.textContent = `$${getCartTotal().toFixed(2)}`;
  }

  const shippingMsg = document.querySelector('.cart-free-shipping');
  if (shippingMsg) {
    const total = getCartTotal();
    if (total >= 75) {
      shippingMsg.innerHTML = '<span style="color:#00c853;">✓ You\'ve unlocked FREE shipping!</span>';
    } else {
      const remaining = 75 - total;
      shippingMsg.innerHTML = `ADD $${remaining.toFixed(0)} MORE TO GET FREE SHIPPING`;
    }
  }
}

function openCart() {
  document.querySelector('.cart-overlay').classList.add('active');
  document.querySelector('.cart-drawer').classList.add('active');
  document.body.style.overflow = 'hidden';
}

function closeCart() {
  document.querySelector('.cart-overlay').classList.remove('active');
  document.querySelector('.cart-drawer').classList.remove('active');
  document.body.style.overflow = '';
}

// Mega Menu
function initMegaMenus() {
  const navLinks = document.querySelectorAll('.nav-link.has-mega');
  const megaMenus = document.querySelectorAll('.mega-menu');

  navLinks.forEach(link => {
    link.addEventListener('mouseenter', () => {
      const target = link.dataset.mega;
      megaMenus.forEach(m => m.classList.remove('active'));
      if (target) {
        document.getElementById(target).classList.add('active');
      }
    });
    link.addEventListener('mouseleave', () => {});
  });

  megaMenus.forEach(menu => {
    menu.addEventListener('mouseenter', () => {
      menu.classList.add('active');
    });
    menu.addEventListener('mouseleave', () => {
      menu.classList.remove('active');
    });
  });

  document.querySelector('.site-header').addEventListener('mouseleave', () => {
    megaMenus.forEach(m => m.classList.remove('active'));
  });
}

// Hero Slider
function initHeroSlider() {
  const slides = document.querySelectorAll('.hero-slide');
  const dots = document.querySelectorAll('.hero-dot');
  if (!slides.length) return;

  let current = 0;
  const total = slides.length;
  let interval;

  function goTo(index) {
    slides.forEach(s => s.classList.remove('active'));
    dots.forEach(d => d.classList.remove('active'));
    slides[index].classList.add('active');
    dots[index].classList.add('active');
    current = index;
  }

  function next() {
    goTo((current + 1) % total);
  }

  dots.forEach((dot, i) => {
    dot.addEventListener('click', () => {
      clearInterval(interval);
      goTo(i);
      interval = setInterval(next, 5000);
    });
  });

  interval = setInterval(next, 5000);

  const hero = document.querySelector('.hero');
  if (hero) {
    hero.addEventListener('mouseenter', () => clearInterval(interval));
    hero.addEventListener('mouseleave', () => {
      interval = setInterval(next, 5000);
    });
  }
}

// Mobile Nav
function initMobileNav() {
  const toggle = document.querySelector('.mobile-toggle');
  const close = document.querySelector('.mobile-nav-close');
  const nav = document.querySelector('.mobile-nav');
  const overlay = document.querySelector('.mobile-nav-overlay');

  if (!toggle || !nav) return;

  function openMobile() {
    nav.classList.add('active');
    if (overlay) overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeMobile() {
    nav.classList.remove('active');
    if (overlay) overlay.classList.remove('active');
    document.body.style.overflow = '';
  }

  toggle.addEventListener('click', openMobile);
  if (close) close.addEventListener('click', closeMobile);
  if (overlay) overlay.addEventListener('click', closeMobile);
}

// Newsletter
function initNewsletter() {
  const forms = document.querySelectorAll('.newsletter-form');
  forms.forEach(form => {
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      const input = form.querySelector('input');
      if (input.value.trim()) {
        alert('Thanks for signing up! You\'ll be the first to know about new drops.');
        input.value = '';
      }
    });
  });
}

// Product image hover swap
function initImageHover() {
  document.querySelectorAll('.product-card-image').forEach(card => {
    const hover = card.querySelector('.img-hover');
    if (hover) {
      card.addEventListener('mouseenter', () => {
        hover.style.opacity = '1';
      });
      card.addEventListener('mouseleave', () => {
        hover.style.opacity = '0';
      });
    }
  });
}

// Product card click
function initProductClicks() {
  document.querySelectorAll('.product-card').forEach(card => {
    card.addEventListener('click', (e) => {
      if (e.target.closest('.qty-btn') || e.target.closest('.cart-item-remove')) return;
      const id = card.dataset.productId;
      if (id) {
        window.location.href = `product.html?id=${id}`;
      }
    });
  });
}

// Render product card HTML
function renderProductCard(product) {
  const badgesHtml = (product.badges || []).map(b => {
    const cls = `badge-${b}`;
    return `<span class="product-badge ${cls}">${b}</span>`;
  }).join('');

  const fromText = product.priceFrom ? '<span class="from-text">From </span>' : '';

  return `
    <div class="product-card" data-product-id="${product.id}">
      <div class="product-card-image">
        <img class="img-main" src="${product.images[0]}" alt="${product.title}" loading="lazy">
        <img class="img-hover" src="${product.images[1] || product.images[0]}" alt="${product.title}" loading="lazy">
        <div class="product-badges">${badgesHtml}</div>
      </div>
      <div class="product-card-info">
        <h3>${product.title}</h3>
        <div class="product-price">${fromText}${product.price}</div>
      </div>
    </div>
  `;
}

// Init on DOM ready
document.addEventListener('DOMContentLoaded', () => {
  initMegaMenus();
  initHeroSlider();
  initMobileNav();
  initNewsletter();
  initImageHover();
  initProductClicks();
  updateCartUI();
  initSearchModal();

  // Cart drawer toggle
  const cartToggle = document.querySelector('.cart-toggle');
  if (cartToggle) cartToggle.addEventListener('click', openCart);

  const cartClose = document.querySelector('.cart-close');
  if (cartClose) cartClose.addEventListener('click', closeCart);

  const cartOverlay = document.querySelector('.cart-overlay');
  if (cartOverlay) cartOverlay.addEventListener('click', closeCart);
});

/* ==========================================================================
   SEARCH MODAL IMPLEMENTATION
   ========================================================================== */
function createSearchModalHTML() {
  if (document.querySelector('.search-modal-overlay')) return;

  const modalHTML = `
    <div class="search-modal-overlay">
      <div class="search-modal-container">
        <div class="search-modal-header">
          <div class="search-modal-input-wrapper">
            <span class="search-modal-icon">&#128269;</span>
            <input type="text" class="search-modal-input" placeholder="Search products, hoodies, tees, hats..." autocomplete="off">
            <span class="search-modal-clear">&times;</span>
          </div>
          <button class="search-modal-close">&times;</button>
        </div>
        <div class="search-modal-body">
          <div class="search-default-state">
            <div class="search-section-title">Popular Searches</div>
            <div class="search-tags">
              <span class="search-tag">500 Million</span>
              <span class="search-tag">Hoodies</span>
              <span class="search-tag">Tee</span>
              <span class="search-tag">Baseball Jersey</span>
              <span class="search-tag">Beast Vibes</span>
              <span class="search-tag">Shorts</span>
            </div>
          </div>
          <div class="search-results-container"></div>
        </div>
      </div>
    </div>
  `;
  document.body.insertAdjacentHTML('beforeend', modalHTML);
}

function openSearch() {
  createSearchModalHTML();
  const overlay = document.querySelector('.search-modal-overlay');
  const input = document.querySelector('.search-modal-input');
  if (overlay) {
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
    setTimeout(() => input && input.focus(), 100);
  }
}

function closeSearch() {
  const overlay = document.querySelector('.search-modal-overlay');
  if (overlay) {
    overlay.classList.remove('active');
    document.body.style.overflow = '';
  }
}

function initSearchModal() {
  createSearchModalHTML();

  const overlay = document.querySelector('.search-modal-overlay');
  const input = document.querySelector('.search-modal-input');
  const clearBtn = document.querySelector('.search-modal-clear');
  const closeBtn = document.querySelector('.search-modal-close');
  const resultsContainer = document.querySelector('.search-results-container');
  const defaultState = document.querySelector('.search-default-state');

  // Bind all buttons with class search-toggle or data-action="search"
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('.search-toggle, [data-action="search"]');
    if (btn) {
      e.preventDefault();
      openSearch();
    }
  });

  if (closeBtn) closeBtn.addEventListener('click', closeSearch);
  if (overlay) {
    overlay.addEventListener('click', (e) => {
      if (e.target === overlay) closeSearch();
    });
  }

  // ESC key to close
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && overlay && overlay.classList.contains('active')) {
      closeSearch();
    }
  });

  if (input) {
    input.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        const query = input.value.trim();
        if (query) {
          window.location.href = `shop-all.html?search=${encodeURIComponent(query)}`;
        }
      }
    });

    input.addEventListener('input', () => {
      const query = input.value.trim().toLowerCase();
      if (clearBtn) clearBtn.style.display = query ? 'block' : 'none';

      if (!query) {
        if (defaultState) defaultState.style.display = 'block';
        if (resultsContainer) resultsContainer.innerHTML = '';
        return;
      }

      if (defaultState) defaultState.style.display = 'none';

      const matches = (typeof products !== 'undefined' ? products : []).filter(p => {
        return (
          p.title.toLowerCase().includes(query) ||
          p.category.toLowerCase().includes(query) ||
          p.type.toLowerCase().includes(query) ||
          (p.description && p.description.toLowerCase().includes(query)) ||
          (p.badges && p.badges.some(b => b.toLowerCase().includes(query)))
        );
      });

      renderLiveSearchResults(matches, query);
    });
  }

  if (clearBtn) {
    clearBtn.addEventListener('click', () => {
      if (input) {
        input.value = '';
        input.focus();
        clearBtn.style.display = 'none';
        if (defaultState) defaultState.style.display = 'block';
        if (resultsContainer) resultsContainer.innerHTML = '';
      }
    });
  }

  // Tag click handlers
  document.addEventListener('click', (e) => {
    const tag = e.target.closest('.search-tag');
    if (tag) {
      const term = tag.textContent.trim();
      if (input) {
        input.value = term;
        input.dispatchEvent(new Event('input'));
        input.focus();
      }
    }
  });
}

function renderLiveSearchResults(matches, query) {
  const container = document.querySelector('.search-results-container');
  if (!container) return;

  if (matches.length === 0) {
    container.innerHTML = `
      <div class="search-no-results">
        <h4>No products found for "${escapeHTML(query)}"</h4>
        <p>Try searching for "tee", "hoodie", "500 million", or "shorts"</p>
      </div>
    `;
    return;
  }

  const topResults = matches.slice(0, 5);
  let html = `<div class="search-section-title">Products (${matches.length})</div><div class="search-results-list">`;

  topResults.forEach(p => {
    const badge = (p.badges && p.badges.length) ? `<span class="search-result-badge">${p.badges[0]}</span>` : '';
    html += `
      <a href="product.html?id=${p.id}" class="search-result-item">
        <img src="${p.images[0]}" alt="${escapeHTML(p.title)}" class="search-result-thumb">
        <div class="search-result-info">
          <div class="search-result-title">${escapeHTML(p.title)}</div>
          <div class="search-result-meta">
            <span>${escapeHTML(p.type)}</span>
            ${badge}
          </div>
        </div>
        <div class="search-result-price">${p.price}</div>
      </a>
    `;
  });

  html += `</div>`;

  if (matches.length > 0) {
    html += `
      <a href="shop-all.html?search=${encodeURIComponent(query)}" class="search-view-all-btn">
        View All ${matches.length} Results &rarr;
      </a>
    `;
  }

  container.innerHTML = html;
}

function escapeHTML(str) {
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

