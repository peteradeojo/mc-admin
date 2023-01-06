/**
 * @var {HTMLFormElement} form
 */
const form = document.querySelector('#newPatientForm');

form.querySelector('#category').addEventListener('change', async function (e) {
  const option = e.target.options[e.target.options.selectedIndex];

  form.querySelector('#extra-info').innerHTML = '';

  const conditionalForm = option.getAttribute('data-conditional-form');
  if (conditionalForm) {
    try {
      const res = await fetch(conditionalForm);
      const extraInfo = await res.text();
      form.querySelector('#extra-info').innerHTML = extraInfo;
    } catch (err) {
      console.error(err);
    }
  }
});