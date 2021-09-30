const sidenav = document.querySelector('aside');

function openSideNav() {
	sidenav.classList.remove('closed');
}

function closeSideNav() {
	sidenav.classList.add('closed');
}

$(() => {
	$('.alert-dismissible .close').each((index, item) => {
		item.addEventListener('click', function () {
			$(this.parentElement).remove();
		});
	});
});
