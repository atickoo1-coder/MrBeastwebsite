let filteredProducts = [...products];
let visibleCount = 12;

function getFilteredProducts() {
  const selectedTypes = [];
  document.querySelectorAll('.filter-type:checked').forEach(cb => selectedTypes.push(cb.value));

  const selectedSizes = [];
  document.querySelectorAll('.filter-size:checked').forEach(cb => selectedSizes.push(cb.value));

  const selectedColors = [];
  document.querySelectorAll('.color-swatch.active').forEach(el => selectedColors.push(el.dataset.color));

  const minPrice = parseFloat(document.getElementById('price-min')?.value) || 0;
  const maxPrice = parseFloat(document.getElementById('price-max')?.value) || 999;

  return products.filter(p => {
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

  if (countEl) {
    countEl.textContent = `${filteredProducts.length} products`;
  }

  if (!filteredProducts.length) {
    grid.innerHTML = `<div style="grid-column:1/-1;text-align:center;padding:60px 0;color:#888;"><h3 style="font-size:24px;margin-bottom:8px;">No products found</h3><p>Try adjusting your filters.</p></div>`;
    document.querySelector('.load-more').style.display = 'none';
    return;
  }

  const toShow = filteredProducts.slice(0, visibleCount);
  grid.innerHTML = toShow.map(renderProductCard).join('');

  document.querySelector('.load-more').style.display = visibleCount >= filteredProducts.length ? 'none' : 'block';

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
  document.getElementById('price-min').value = '';
  document.getElementById('price-max').value = '';
  renderShopProducts();
}

document.addEventListener('DOMContentLoaded', () => {
  renderShopProducts();

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
