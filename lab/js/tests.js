(() => {
	document.addEventListener('DOMContentLoaded', async () => {
		try {
			const res = await fetch('/lab/api/get_pending_tests.php');
			const data = await res.json();
			if (data.error) {
				return alert('Server error. Please try again later or contact the admin');
			} else {
				data.forEach((test) => {
					const li = document.createElement('li');
					li.classList.add('list-item');

					const a = document.createElement('a');
					a.href = `/lab/test.php?id=${test.lab_tests_id}`;
					a.classList.add('modal-open');
					a.setAttribute('data-target', '#tests-modal');
					a.innerHTML = test.name;
					
          li.appendChild(a);
					document.getElementById('tests-list').appendChild(li);
				});
			}
		} catch (err) {
			console.error(err);
		}
	});
})();
