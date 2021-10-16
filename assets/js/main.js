const sidenav = document.querySelector('aside');

function openSideNav() {
	sidenav.classList.remove('closed');
}

function closeSideNav() {
	sidenav.classList.add('closed');
}

function editDetail(inputId, displayId) {
	const input = document.querySelector(`${inputId}`);
	const display = document.querySelector(displayId);

	input.style.display = 'initial';
	display.style.display = 'none';
}

$(() => {
	/**
	 * @param {HTMLElement} elem
	 */
	const submitAsyncForm = async (elem) => {
		const url = elem.getAttribute('action');
		const formData = new FormData(elem);
		try {
			const res = await fetch(url, { method: 'POST', body: formData });
			const data = await res.json();
			if (data.ok) {
				window.location.reload();
			}
		} catch (err) {
			console.error(err);
		}
	};

	$('.alert-dismissible .close').each((index, item) => {
		item.addEventListener('click', function () {
			$(this.parentElement).remove();
		});
	});

	document.body.addEventListener('click', (e) => {
		if (e.target.getAttribute('type') == 'submit' && e.target.closest('.async-form')) {
			e.preventDefault();
			submitAsyncForm(e.target.closest('.async-form'));
		}
	});

	document.querySelectorAll('.modal-open').forEach((anchor) => {
		anchor.addEventListener('click', () => {
			const target = document.querySelector(anchor.getAttribute('data-target'));
			target.style.display = 'initial';
		});
	});

	document.querySelectorAll('.close').forEach((close) => {
		close.addEventListener('click', () => {
			const target = document.querySelector(close.getAttribute('data-dismiss'));
			target.style.display = 'none';
		});
	});
	document.addEventListener('click', (e) => {
		if (e.target.classList.contains('modal')) {
			e.target.style.display = 'none';
		}
	});
});
