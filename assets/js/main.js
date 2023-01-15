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

function initTabArea(tabArea) {
	const tabBtns = tabArea.querySelectorAll('.tab-list .tab');

	tabBtns.forEach(tab => {
		tab.addEventListener('click', (e) => {
			const clickedTab = e.target;
			tabBtns.forEach(tab => {
				tab.classList.remove('active');
				tabArea.querySelector(`${tab.getAttribute('data-target')}`).style.display = 'none';
			});

			clickedTab.classList.add('active');
			tabArea.querySelector(`${clickedTab.getAttribute('data-target')}`).style.display = 'initial';
		});
	});
}

function initTabs() {
	tabAreas = getTabbedContent();

	tabAreas.forEach(initTabArea);
}
function getTabbedContent() {
	const tabbedContent = document.querySelectorAll('.tabs');
	return tabbedContent;
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

	initTabs();
});

function toggleFloatingTab(tab) {
	// if tab display is none, set to initial else set to none
	const display = tab.style.display != 'initial' ? 'initial' : 'none';
	tab.style.display = display;
}

document.querySelectorAll('.floating-tab').forEach((tab) => {
	const { hook } = tab.dataset;
	const btn = document.querySelector(hook) ?? null;

	btn?.addEventListener('click', () => { toggleFloatingTab(tab) });
	// tab.querySelector('.close')?.addEventListener('click', () => { toggleFloatingTab(tab) });
});