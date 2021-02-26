function parseHTMLToJson(html) {
	let parser = new DOMParser();
	let doc = parser.parseFromString(html, 'text/html');

	let boards = [];

	for (let boardEle of doc.querySelectorAll('.board:not(.new-board)')) {
		let name = boardEle.querySelector('.board-header').innerHTML;
		let created = boardEle.querySelector('.board-created').innerHTML;
		let edited = boardEle.querySelector('.board-edited').innerHTML;
		let newBoard = {
			name,
			created,
			edited,
			notes: [],
		};
		for (let noteEl of boardEle.querySelectorAll('.note:not(.notDraggable)')) {
			let content = noteEl.querySelector('.note-content').innerHTML;
			created = boardEle.querySelector('.board-created').innerHTML;
			edited = boardEle.querySelector('.board-edited').innerHTML;
			let newNote = {
				content,
				created,
				edited,
				tags: [],
			};

			for (let tagEl of noteEl.querySelectorAll('.tag')) {
				value = tagEl.innerHTML;
				index = tagEl.getAttribute('index');
				newNote.tags.push({ value, index });
			}

			newBoard.notes.push(newNote);
		}

		boards.push(newBoard);
	}
	return boards;
}

function parseJsonToXML(json) {
	let boards = json;
	let xml = `<?xml version="1.0" encoding="UTF-8"?>`,
		boardXml = '';
	for (let board of boards) {
		let noteXml = '';
		for (let note of board.notes) {
			let tagXml = '';
			for (let tag of note.tags) {
				tagXml += `<tag><value>${tag.value}</value><index>${tag.index}</index></tag>`;
			}
			noteXml += `
				<note>
					<content>${note.content}</content>
					<created>${note.created}</created>
					<edited>${note.edited}</edited>
					${tagXml}
				</note>
				`;
		}
		boardXml += `
			<board>
				<name>${board.name}</name>
				<created>${board.created}</created>
				<edited>${board.edited}</edited>
				${noteXml}
			</board>
			`;
	}
	xml += `<plan>${boardXml}</plan>`;
	return xml;
}

function parseXMLToJson(xml) {
	if (window.DOMParser) {
		parser = new DOMParser();
		xmlDoc = parser.parseFromString(xml, 'text/xml');
	} // Internet Explorer
	else {
		xmlDoc = new ActiveXObject('Microsoft.XMLDOM');
		xmlDoc.async = false;
		xmlDoc.loadXML(xml);
	}

	let boards = [];

	for (let boardEle of xmlDoc.getElementsByTagName('board')) {
		let name = boardEle.getElementsByTagName('name')[0].innerHTML;
		let created = boardEle.getElementsByTagName('created')[0].innerHTML;
		let edited = boardEle.getElementsByTagName('edited')[0].innerHTML;
		let newBoard = {
			name,
			created,
			edited,
			notes: [],
		};
		for (let noteEl of boardEle.getElementsByTagName('note')) {
			let content = noteEl.getElementsByTagName('content')[0].innerHTML;
			created = boardEle.getElementsByTagName('created')[0].innerHTML;
			edited = boardEle.getElementsByTagName('edited')[0].innerHTML;
			let newNote = {
				content,
				created,
				edited,
				tags: [],
			};

			for (let tagEl of noteEl.getElementsByTagName('tag')) {
				value = tagEl.getElementsByTagName('value')[0].innerHTML;
				index = tagEl.getElementsByTagName('index')[0].innerHTML;
				newNote.tags.push({ value, index });
			}

			newBoard.notes.push(newNote);
		}

		boards.push(newBoard);
	}
	return boards;
}

