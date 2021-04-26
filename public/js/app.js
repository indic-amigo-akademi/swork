const { onMounted, onUpdated, onUnmounted } = Vue;

const config = {
	customClass: {
		container: 'modal',
		popup: 'modal-content',
		header: 'modal_header',
		// title: '...',
		// closeButton: '...',
		// icon: '...',
		// image: '...',
		content: '',
		// htmlContainer: '...',
		// input: '...',
		// inputLabel: '...',
		// validationMessage: '...',
		// actions: '...',
		confirmButton: 'bgBtn blueBtn',
		denyButton: 'bgBtn redBtn',
		cancelButton: 'outlineBtn',
		// loader: '...',
		// footer: '....',
	},
	target: '#root .container',
};

const App = {
	el: '#root',
	components: {
		vdraggable: vuedraggable,
	},
	data() {
		return {
			darkMode: localStorage.getItem('darkMode')
				? localStorage.getItem('darkMode') === 'true'
				: true,
			isEditForm: false,
			showProfileDropdown: false,
			showRegisterModal: false,
			showNoteModal: false,
			showRegisterForm: false,
			showNoteContextMenu: false,
			showBoardContextMenu: false,
			drag: null,
			enabled: true,
			dragging: true,
			search: '',
			username: '',
			password: '',
			cpassword: '',
			content: '',
			tags: '',
			tagsArray: new Set(['PHP', 'Symfony']),
			board_id: -1,
			note_id: -1,
			alert: {
				success: '',
				error: '',
			},
			boards: [],
		};
	},
	setup() {
		onMounted(() => {});
		onUpdated(() => {});
		onUnmounted(() => {});
	},
	updated() {
		this.$nextTick(function () {
			const dark = states.darkMode ? 'true' : 'false';
			localStorage.setItem('darkMode', dark);
			// if (location.pathname.startsWith('/plan')) initDragnDrop();
		});
	},
	methods: {
		resetPopup() {
			this.showProfileDropdown = false;
			this.showRegisterModal = false;
			this.showRegisterForm = false;
			this.showNoteModal = false;
			this.showNoteContextMenu = false;
			// this.board = -1;
			// this.note = -1;
		},
		resetInput() {
			(this.search = ''),
				(this.username = ''),
				(this.password = ''),
				(this.cpassword = ''),
				(this.content = ''),
				(this.tags = '');
		},
		toggleDark() {
			this.darkMode = !this.darkMode;
		},
		togglePopup(popup) {
			this[popup] = !this[popup];
		},
		closePopup(popup) {
			this[popup] = false;
		},
		openPopup(popup) {
			this[popup] = true;
		},
		setCurrentBoard(key) {
			this.board_id = key;
		},
		setCurrentNote(key) {
			this.note_id = key;
			this.resetPopup();
			if (this.isEditForm) {
				const { tags, content } = this.boards[parseInt(this.board_id)].notes[
					parseInt(this.note_id)
				];
				this.tags = [];
				tags.forEach((e) => {
					this.tags.push(e.value);
				});
				this.tags = this.tags.join(', ');
				this.content = content;
			}
		},
		async registerUser() {
			if (this.cpassword !== this.password) {
				alert('Password should match');
			} else {
				this.resetPopup();
				let formData = new FormData();
				formData.append('username', this.username);
				formData.append('password', this.password);
				const res = await fetch(`http://${location.host}/register`, {
					method: 'post',
					body: formData,
				});
				let jsonData = await res.json();
				if (jsonData.error) {
					this.alert.errors = jsonData;
					await Swal.fire({
						...config,
						title: 'Register error',
						text: jsonData.error,
						icon: 'error',
					});
				} else {
					this.alert.success = jsonData;
					await Swal.fire({
						...config,
						title: 'Logout success',
						text: jsonData.msg,
						icon: 'success',
					});
				}
				location.reload();
			}
			this.resetInput();
		},
		async loginUser() {
			this.resetPopup();
			let formData = new FormData();
			formData.append('username', this.username);
			formData.append('password', this.password);
			const res = await fetch(`http://${location.host}/login`, {
				method: 'post',
				body: formData,
			});
			let jsonData = await res.json();
			if (jsonData.error) {
				this.alert.errors = jsonData;
				await Swal.fire({
					...config,
					title: 'Login error',
					text: jsonData.error,
					icon: 'error',
				});
				location.href = `http://${location.host}/${this.alert.errors.status}`;
			} else {
				this.alert.success = jsonData;
				await Swal.fire({
					...config,
					title: 'Login success',
					text: jsonData.msg,
					icon: 'success',
				});
			}
			location.reload();
			this.resetInput();
		},
		async logoutUser() {
			this.resetPopup();
			const res = await fetch(`http://${location.host}/logout`, {
				method: 'post',
			});
			let jsonData = await res.json();
			if (jsonData.error) {
				this.alert.errors = jsonData;
				await Swal.fire({
					...config,
					title: 'Logout error',
					text: jsonData.error,
					icon: 'error',
				});
				location.href = `http://${location.host}/${this.alert.errors.status}`;
			} else {
				this.alert.success = jsonData;
				await Swal.fire({
					...config,
					title: 'Logout success',
					text: jsonData.msg,
					icon: 'success',
				});
			}
			location.href = `http://${location.host}/`;
		},
		async addNewPlan() {
			const { value: planName, isConfirmed } = await Swal.fire({
				...config,
				title: 'Enter your new plan',
				input: 'text',
				inputLabel: 'Your Plan Name',
				inputValue: '',
				showCancelButton: true,
				inputValidator: (value) => {
					if (!value) {
						return 'You need to write something!';
					}
				},
			});

			if (isConfirmed) {
				let formData = new FormData();
				formData.append('name', planName);
				const res = await fetch(`http://${location.host}/plan/new`, {
					method: 'post',
					body: formData,
				});

				let jsonData = await res.json();
				if (jsonData.error) {
					this.alert.errors = jsonData;
					await Swal.fire({
						...config,
						title: 'Add Plan Esrror',
						text: jsonData.error,
						icon: 'error',
					});
					location.href = `http://${location.host}/${this.alert.errors.status}`;
				} else {
					this.alert.success = jsonData;
					await Swal.fire({
						...config,
						title: 'Add Plan Success',
						text: jsonData.msg,
						icon: 'success',
					});
				}
				location.reload();
			}
			this.resetInput();
		},
		async addNewBoard() {
			const { value: boardName, isConfirmed } = await Swal.fire({
				...config,
				title: 'Enter your new board',
				input: 'text',
				inputLabel: 'Your Board Name',
				inputValue: '',
				showCancelButton: true,
				inputValidator: (value) => {
					if (!value) {
						return 'You need to write something!';
					}
				},
			});
			if (isConfirmed) {
				const created = new Date().toISOString(),
					edited = new Date().toISOString();
				const newBoard = {
					name: boardName,
					notes: [],
					created,
					edited,
				};
				this.boards.push(newBoard);
				await this.saveXML();
			}
		},
		async editBoard(id) {
			this.resetPopup();
			const { value: boardName, isConfirmed } = await Swal.fire({
				...config,
				title: 'Enter your board',
				input: 'text',
				inputLabel: 'Your Board Name',
				inputValue: this.boards[parseInt(id)].name,
				showCancelButton: true,
				inputValidator: (value) => {
					if (!value) {
						return 'You need to write something!';
					}
				},
			});
			if (isConfirmed) {
				this.boards[parseInt(id)].name = boardName;
				this.boards[parseInt(id)].edited = new Date().toISOString();
				await this.saveXML();
			}
		},
		async deleteBoard() {
			const { value } = await Swal.fire({
				...config,
				title: 'Do you want to delete this board?',
				showCancelButton: true,
			});

			if (value) {
				this.boards.splice(parseInt(this.board_id), 1);
				await this.saveXML();
			}
		},
		async addNewNote() {
			this.resetPopup();
			const tags = [],
				newTags = Array.from(
					new Set(this.tags.trim(' ').split(/[ ,]+/).filter(Boolean))
				);
			newTags.forEach((e) => {
				this.tagsArray.add(e);
			}),
				(tagsArray = Array.from(this.tagsArray));

			newTags.forEach((e) => {
				tags.push({ index: tagsArray.findIndex((ele) => ele === e), value: e });
			});
			(content = this.content),
				(created = new Date().toISOString()),
				(edited = new Date().toISOString());
			const newNote = {
				created,
				edited,
				tags,
				content,
			};
			this.boards[parseInt(this.board_id)].notes.push(newNote);
			this.resetInput();
			await this.saveXML();
		},
		async deleteNote() {
			const { value } = await Swal.fire({
				...config,
				title: 'Do you want to delete this note?',
				showCancelButton: true,
			});

			if (value) {
				this.boards[parseInt(this.board_id)].notes.splice(
					parseInt(this.note_id),
					1
				);
				await this.saveXML();
			}
		},
		async editNote() {
			this.resetPopup();
			const tags = [],
				newTags = Array.from(
					new Set(this.tags.trim(' ').split(/[ ,]+/).filter(Boolean))
				);
			newTags.forEach((e) => {
				this.tagsArray.add(e);
			}),
				(tagsArray = Array.from(this.tagsArray));

			newTags.forEach((e) => {
				tags.push({ index: tagsArray.findIndex((ele) => ele === e), value: e });
			});
			(content = this.content), (edited = new Date().toISOString());
			const updatedNote = {
				edited,
				tags,
				content,
			};
			let note = this.boards[parseInt(this.board_id)].notes[
				parseInt(this.note)
			];
			this.boards[parseInt(this.board_id)].notes[parseInt(this.note)] = {
				...note,
				...updatedNote,
			};
			this.resetInput();
			await this.saveXML();
		},
		marked(content) {
			return marked(content);
		},
		async saveXML() {
			let data = parseJsonToXML(this.boards),
				slug = location.pathname.split('/');
			slug = slug[slug.length - 1];
			const formData = new FormData();
			formData.append('data', data);
			formData.append('slug', slug);
			const res = await fetch(`http://${location.host}/plan/save`, {
				method: 'post',
				body: formData,
			});

			let jsonData = await res.json();
			if (jsonData.error) {
				this.alert.errors = jsonData;
				await Swal.fire({
					...config,
					title: 'Saving error',
					text: jsonData.error,
					icon: 'error',
				});
				location.href = `http://${location.host}/${this.alert.errors.status}`;
			} else {
				this.alert.success = jsonData;
				// await Swal.fire({
				// 	...config,
				// 	title: 'Saving success',
				// 	text: jsonData.msg,
				// 	icon: 'success',
				// });
			}
			// location.reload();
		},
		async loadXML() {
			let slug = location.pathname.split('/');
			slug = slug[slug.length - 1];
			const formData = new FormData();
			formData.append('slug', slug);
			const res = await fetch(`http://${location.host}/plan/load`, {
				method: 'post',
				body: formData,
			});
			let jsonData = await res.json();
			if (jsonData.error) {
				this.alert.errors = jsonData;
				await Swal.fire({
					...config,
					title: 'Saving error',
					text: this.alert.errors.error,
					icon: 'error',
				});
				location.href = `http://${location.host}/${this.alert.errors.status}`;
			} else {
				boards = parseXMLToJson(jsonData.data);
				this.boards = boards;
			}
		},
	},
};

Vue.config.ignoredElements = [/^ion-/];

const app = new Vue(App);

const states = app;
