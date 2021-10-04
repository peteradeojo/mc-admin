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
});
