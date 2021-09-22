const sidenav = document.querySelector('aside');

function openSideNav() {
	// console.log(sidenav);
	// sidenav.style.display = 'initial';
	// sidenav.style.zIndex = 999;
	sidenav.classList.remove('closed');
}

function closeSideNav() {
	sidenav.classList.add('closed');
}
