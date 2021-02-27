// function allowDrop(ev) {
// 	ev.preventDefault();
// }

// function drag(ev, id) {
// 	ev.dataTransfer.setData('text', ev.target.id);
// }

// function drop(ev) {
// 	ev.preventDefault();
// 	var data = ev.dataTransfer.getData('text');
// 	ev.target.appendChild(document.getElementById(data));
// }

// document.addEventListener('DOMContentLoaded', (event) => {
// 	var dragSrcEl = null;

// 	function handleDragStart(e) {
// 		this.style.opacity = '0.4';

// 		dragSrcEl = this;

// 		e.dataTransfer.effectAllowed = 'move';
// 		e.dataTransfer.setData('text/html', this.innerHTML);
// 	}

// 	function handleDragOver(e) {
// 		if (e.preventDefault) {
// 			e.preventDefault();
// 		}

// 		e.dataTransfer.dropEffect = 'move';

// 		return false;
// 	}

// 	function handleDragEnter(e) {
// 		this.classList.add('over');
// 	}

// 	function handleDragLeave(e) {
// 		this.classList.remove('over');
// 	}

// 	function handleDrop(e) {
// 		if (e.stopPropagation) {
// 			e.stopPropagation(); // stops the browser from redirecting.
// 		}

// 		if (dragSrcEl != this) {
// 			dragSrcEl.innerHTML = this.innerHTML;
// 			this.innerHTML = e.dataTransfer.getData('text/html');
// 		}

// 		return false;
// 	}

// 	function handleDragEnd(e) {
// 		this.style.opacity = '1';

// 		items.forEach(function (item) {
// 			item.classList.remove('over');
// 		});
// 	}

// 	let items = document.querySelectorAll('.container .box');
// 	items.forEach(function (item) {
// 		item.addEventListener('dragstart', handleDragStart, false);
// 		item.addEventListener('dragenter', handleDragEnter, false);
// 		item.addEventListener('dragover', handleDragOver, false);
// 		item.addEventListener('dragleave', handleDragLeave, false);
// 		item.addEventListener('drop', handleDrop, false);
// 		item.addEventListener('dragend', handleDragEnd, false);
// 	});
// });

window.addEventListener('load', function () {
	states.loadXML();
});

function initDragnDrop() {
	const boardList = document.querySelector('.board-list');
	let sortableBoardList = new Sortable(boardList, {
		filter: '.notDraggable',
		onChange() {
			boards = parseHTMLToJson(
				document.querySelector('.board-list').innerHTML.toString()
			);
			states.boards = boards;
		},
	});
	const boardBodyList = document.querySelectorAll('.board .board-body'),
		sortableBoardBodyList = [];
	for (let board of boardBodyList) {
		sortableBoardBodyList.push(
			new Sortable(board, {
				group: 'nested',
				animation: 150,
				fallbackOnBody: true,
				swapThreshold: 0.65,
				filter: '.notDraggable',
				onChange() {
					boards = parseHTMLToJson(
						document.querySelector('.board-list').innerHTML.toString()
					);
					states.boards = boards;
				},
			})
		);
	}
}


