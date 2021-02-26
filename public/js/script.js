const date = new Date();

document.getElementById('full-year').innerText = date.getFullYear();

window.onclick = function (event) {
	const registerModal = document.getElementById('registerModal'),
		profileDropdownBtn = document.getElementById('profileDropdownBtn'),
		profileDropdown = document.getElementById('profileDropdown');
	if (event.target === registerModal) {
		states.closePopup('showRegisterModal');
	}
	if (
		event.target !== profileDropdownBtn &&
		event.target.parentNode !== profileDropdownBtn &&
		event.target !== profileDropdown &&
		event.target.parentNode !== profileDropdown
	) {
		states.closePopup('showProfileDropdown');
	}
};
