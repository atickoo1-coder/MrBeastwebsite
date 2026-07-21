let filteredProducts = [...products];
let visibleCount = 12;

function getFilteredProducts() {
  const searchQuery = (document.getElementById('shop-search-input')?.value || '').trim().toLowerCase();

  const selectedTypes = [];
  document.querySelectorAll('.filter-type:checked').forEach(cb => selectedTypes.push(cb.value));

  const selectedSizes = [];
  document.querySelectorAll('.filter-size:checked').forEach(cb => selectedSizes.push(cb.value));

  const selectedColors = [];
  document.querySelectorAll('.color-swatch.active').forEach(el => selectedColors.push(el.dataset.color));

  const minPrice = parseFloat(document.getElementById('price-min')?.value) || 0;
  const maxPrice = parseFloat(document.getElementById('price-max')?.value) || 999;

  return products.filter(p => {
    // Search query check
    if (searchQuery) {
      const matchTitle = p.title.toLowerCase().includes(searchQuery);
      const matchCat = p.category.toLowerCase().includes(searchQuery);
      const matchType = p.type.toLowerCase().includes(searchQuery);
      const matchDesc = p.description && p.description.toLowerCase().includes(searchQuery);
      const matchBadges = p.badges && p.badges.some(b => b.toLowerCase().includes(searchQuery));
      if (!matchTitle && !matchCat && !matchType && !matchDesc && !matchBadges) {
        return false;
      }
    }

    const pPrice = parseFloat(p.price.replace('$', '')) || 0;
    if (pPrice < minPrice || pPrice > maxPrice) return false;
    if (selectedTypes.length && !selectedTypes.includes(p.type)) return false;
    if (selectedSizes.length && !p.sizes.some(s => selectedSizes.some(sel => s.toLowerCase().includes(sel.toLowerCase())))) return false;
    if (selectedColors.length && !p.colors.some(c => selectedColors.includes(c))) return false;
    return true;
  });
}

function renderShopProducts(reset = true) {
  if (reset) visibleCount = 12;
  filteredProducts = getFilteredProducts();

  const grid = document.querySelector('.products-grid');
  const countEl = document.querySelector('.product-count');
  const searchInput = document.getElementById('shop-search-input');
  const query = searchInput ? searchInput.value.trim() : '';

  if (countEl) {
    countEl.textContent = `${filteredProducts.length} products${query ? ` for "${query}"` : ''}`;
  }

  if (!filteredProducts.length) {
    grid.innerHTML = `<div style="grid-column:1/-1;text-align:center;padding:60px 0;color:#888;"><h3 style="font-size:24px;margin-bottom:8px;">No products found</h3><p>Try adjusting your search query or filters.</p></div>`;
    const loadMoreBtn = document.querySelector('.load-more');
    if (loadMoreBtn) loadMoreBtn.style.display = 'none';
    return;
  }

  const toShow = filteredProducts.slice(0, visibleCount);
  grid.innerHTML = toShow.map(renderProductCard).join('');

  const loadMoreBtn = document.querySelector('.load-more');
  if (loadMoreBtn) {
    loadMoreBtn.style.display = visibleCount >= filteredProducts.length ? 'none' : 'block';
  }

  initImageHover();
  initProductClicks();
}

function loadMore() {
  visibleCount += 12;
  renderShopProducts(false);
}

function resetFilters() {
  document.querySelectorAll('.filter-sidebar input[type="checkbox"]').forEach(cb => cb.checked = false);
  document.querySelectorAll('.color-swatch.active').forEach(el => el.classList.remove('active'));
  const minInput = document.getElementById('price-min');
  const maxInput = document.getElementById('price-max');
  const searchInput = document.getElementById('shop-search-input');
  const searchClear = document.querySelector('.sidebar-search-clear');
  if (minInput) minInput.value = '';
  if (maxInput) maxInput.value = '';
  if (searchInput) searchInput.value = '';
  if (searchClear) searchClear.style.display = 'none';
  renderShopProducts();
}

document.addEventListener('DOMContentLoaded', () => {
  // Parse search query from URL parameter ?search=...
  const urlParams = new URLSearchParams(window.location.search);
  const searchParam = urlParams.get('search');
  const searchInput = document.getElementById('shop-search-input');
  const searchClear = document.querySelector('.sidebar-search-clear');

  if (searchParam && searchInput) {
    searchInput.value = searchParam;
    if (searchClear) searchClear.style.display = 'block';
  }

  renderShopProducts();

  if (searchInput) {
    searchInput.addEventListener('input', () => {
      if (searchClear) searchClear.style.display = searchInput.value.trim() ? 'block' : 'none';
      renderShopProducts();
    });
  }

  if (searchClear) {
    searchClear.addEventListener('click', () => {
      if (searchInput) {
        searchInput.value = '';
        searchClear.style.display = 'none';
        renderShopProducts();
      }
    });
  }

  document.querySelectorAll('.filter-sidebar input[type="checkbox"]').forEach(cb => {
    cb.addEventListener('change', () => renderShopProducts());
  });

  document.querySelectorAll('.color-swatch').forEach(el => {
    el.addEventListener('click', () => {
      el.classList.toggle('active');
      renderShopProducts();
    });
  });

  document.querySelectorAll('.filter-price-inputs input').forEach(input => {
    input.addEventListener('change', () => renderShopProducts());
  });
});

