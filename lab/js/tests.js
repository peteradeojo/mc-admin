(() => {
	document.addEventListener('DOMContentLoaded', async () => {
		try {
			const testsList = document.getElementById('tests-list');
			if (!testsList) {
				return;
			}
			const res = await fetch('/lab/api/get_pending_tests.php');
			const data = await res.json();
			if (data.error) {
				return alert('Server error. Please try again later or contact the admin');
			} else {
				if (data.length < 1) {
					const li = document.createElement('li');
					li.classList.add('list-item');
					li.textContent = 'No pending tests';
					document.getElementById('tests-list')?.appendChild(li);
					return;
				}

				data.forEach((test) => {
					const li = document.createElement('li');
					li.classList.add('list-item');

					const a = document.createElement('a');
					a.href = `/lab/test.php?id=${test.lab_tests_id}`;
					a.classList.add('modal-open');
					a.setAttribute('data-target', '#tests-modal');
					a.innerHTML = test.name;

					li.appendChild(a);
					document.getElementById('tests-list')?.appendChild(li);
				});
			}
		} catch (err) {
			console.error(err);
		}
	});

	document.getElementById('submitTestResultsForm')?.addEventListener('submit', async (e) => {
		e.preventDefault();
		const formData = new FormData(e.target);
		const res = await fetch('/lab/api/submit_test_results.php', {
			method: 'POST',
			body: formData,
		});
		const data = await res.json();
		if (data.error) {
			return alert('Server error. Please try again later or contact the admin');
		} else {
			window.location.href = '/lab/index.php';
		}
	});

	$('#tests-table').DataTable({
		ajax: {
			url: '/lab/api/get_tests.php',
			dataSrc: '',
		},
		columns: [
			{ data: 'name', orderable: true },
			{ data: 'date', orderable: true },
			{
				data: function (row, type, set, meta) {
					return `<a href='/lab/test_result.php?id=${row.lab_tests_id}'>View</a>`
				},
			}
		]
	});
})();
