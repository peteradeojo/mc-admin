(() => {
	const prescriptionDisplay = document.querySelector('#prescription-modal');
	const getPrescriptionData = async (id) => {
		try {
			const res = await fetch(`/phm/api/getprescription.php?id=${id}`);
			const data = await res.json();
			// console.log(data);
			return data;
		} catch (err) {
			console.error(err);
		}
	};
	document.querySelectorAll('.show-prescription').forEach((link) => {
		link.addEventListener('click', async () => {
			let { prescriptions: prescription, id } = await getPrescriptionData(link.getAttribute('data-id'));

			prescription = JSON.parse(prescription);

			let block = document.createElement('form');
			block.method = 'POST';
			block.classList.add('async-form');
			block.action = '/phm/api/savedispensation.php';
			let id_input = document.createElement('input');
			id_input.type = 'hidden';
			id_input.value = id;
			id_input.name = 'id';
			block.appendChild(id_input);

			Object.keys(prescription).forEach((item, index) => {
				let formGroup = document.createElement('div');
				formGroup.classList.add('form-group', 'mb-1');
				let input = `
				<label>
          <input type='checkbox' name='prescription[]' value='${index}' ${
					prescription[item].status == 1 ? 'checked' : ''
				}>
          <p>
            ${item}<br/>
            Mode: ${prescription[item].mode}<br/>
            Quantity: ${prescription[item].quantity}<br/>
            Duration: ${prescription[item].duration}<br/>
          </p>
        </label>`;
				formGroup.innerHTML = input;
				block.appendChild(formGroup);
			});

			block.innerHTML += `
      <div class='form-group mb-1'>
        <label>Notes</label>
        <textarea name='notes' class='form-control'></textarea>
      </div>
      <div class='form-group'>
        <button class='btn' type='submit'>Submit</button>
      </div>`;

			prescriptionDisplay.querySelector('.modal-body').replaceChildren(block);
		});
	});
})();
