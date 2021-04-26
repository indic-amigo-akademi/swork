const date = new Date();

document.getElementById('full-year').innerText = date.getFullYear();

window.oncontextmenu = function (event) {
	const notes = document.querySelectorAll('.note'),
		boards = document.querySelectorAll('.board');
	let isNote = true,
		isBoard = true;
	// notes.forEach(function (note) {
	// 	isNote = event.target !== note && isNote;
	// });
	// boards.forEach(function (board) {
	// 	isBoard = event.target !== board && isBoard;
	// });

	// if (isBoard) {
	// 	states.showBoardContextMenu = false;
	// }
	// if (isNote) {
	// 	states.note_id = -1;
	// 	states.board_id = -1;
	// 	states.showNoteContextMenu = false;
	// }
};

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

	states.showNoteContextMenu = false;
	states.showBoardContextMenu = false;
};
