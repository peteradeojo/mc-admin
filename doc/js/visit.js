(() => {
	document.querySelectorAll("[data-action='addInput'").forEach((button) => {
		button.addEventListener('click', addInput);
	});

	document.querySelectorAll('.conditional-input').forEach((check) => {
		check.addEventListener('click', function () {
			const target = document.querySelector(this.getAttribute('data-area'));
			if (this.checked) {
				target.style.display = 'initial';
			} else {
				target.style.display = 'none';
			}
			// if (th)
		});
	});
})();

function addInput() {
	const target = document.querySelector(this.getAttribute('data-target'));
	const inputname = this.getAttribute('data-inputname');
	const inputtype = this.getAttribute('data-inputtype') || 'text';
	const datalist = this.getAttribute('data-datalist');

	const newSpace = document.createElement('div');
	newSpace.classList.add('d-flex', 'justify-content-space-between');
	newSpace.style.marginTop = '4px';

	newSpace.innerHTML = `
				<input type='${inputtype}' name='${inputname}' list='${datalist}'>
				<span clsss='p-1 font-larger' onclick="deleteInput(this.parentElement)" type='button'>&times;</span>
			`;

	target.appendChild(newSpace);
}

/**
 *
 * @param {HTMLInputElement} elem
 */
function deleteInput(elem) {
	elem.parentElement.removeChild(elem);
}

/**
 *
 * @param {HTMLInputElement} elem
 */
function addPrescription(elem) {
	// console.log(elem);
	const target = document.querySelector(elem.getAttribute('data-target'));
	const inputname = elem.getAttribute('data-inputname');
	const inputtype = elem.getAttribute('data-inputtype');
	const datalist = elem.getAttribute('data-datalist');

	const newSpace = document.createElement('div');
	newSpace.classList.add('d-flex');
	newSpace.style.marginTop = '4px';

	newSpace.innerHTML = `
				<div class='my-1'>
				<input type='${inputtype}' name='${inputname}' list='${datalist}' required>
				<input name='quantity-${inputname}' type='text' list='quantity-list' placeholder='Quantity' required>
				<input name='mode-${inputname}' type='text' list='mode-list' placeholder='Frequency' required>
				<input name='duration-${inputname}' type='text' list='duration-list' placeholder='Duration' required>
				</div>
				<button class='btn' onclick="deleteInput(this.parentElement)" type='button'>&times;</button>
			`;

	target.appendChild(newSpace);
}

/**
 *
 * @param {HTMLInputElement} elem
 */
function addAdmissionInstruction(elem) {
	const target = document.querySelector(elem.getAttribute('data-target'));
	const inputname = elem.getAttribute('data-inputname');
	const inputtype = elem.getAttribute('data-inputtype') || 'text';
	const datalist = elem.getAttribute('data-datalist');

	const newSpace = document.createElement('div');
	newSpace.classList.add('d-flex');
	newSpace.style.marginTop = '4px';

	newSpace.innerHTML = `
				<div class='my-1'>
				<input type='${inputtype}' name='${inputname}' list='${datalist}'>
				<input name='quantity-${inputname}' type='text' list='quantity-list' placeholder='Quantity' required>
				<input name='mode-${inputname}' type='text' list='mode-list' placeholder='Frequency' required>
				<input name='duration-${inputname}' type='text' list='duration-list' placeholder='Duration'>
				</div>
				<button class='btn' onclick="deleteInput(this.parentElement)" type='button'>&times;</button>
			`;

	target.appendChild(newSpace);
}
