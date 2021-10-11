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
	$('.alert-dismissible .close').each((index, item) => {
		item.addEventListener('click', function () {
			$(this.parentElement).remove();
		});
	});

	document.querySelectorAll('.modal-open').forEach((anchor) => {
		// console.log(anchor);
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
