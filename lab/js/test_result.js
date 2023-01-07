const button = document.getElementsByClassName('print-button')[0];

button.addEventListener('click', () => {
  window.print();
});