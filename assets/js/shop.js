//javascript in shop.html
function toggleSize() {
    const header = document.querySelector('.filter-header');
    const options = document.getElementById('gender-options');
    options.style.display = options.style.display === 'block' ? 'none' : 'block';
    header.classList.toggle('collapsed');
  }

  function toggleSize1() {
    const header = document.querySelector('.filter-header-color');
    const options = document.getElementById('color-options');
    options.style.display = options.style.display === 'block' ? 'none' : 'block';
    header.classList.toggle('collapsed');
  }

  function toggleSize2() {
    const header = document.querySelector('.filter-header-collection');
    const options = document.getElementById('collection-options');
    options.style.display = options.style.display === 'block' ? 'none' : 'block';
    header.classList.toggle('collapsed');
  }