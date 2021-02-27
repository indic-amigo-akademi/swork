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
			showProfileDropdown: false,
			showRegisterModal: false,
			showNoteModal: false,
			showRegisterForm: false,
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
			alert: {
				success: '',
				error: '',
			},
			boards: [],
		};
	},
	setup() {
		onMounted(() => {
			// console.log('mounted!');
		});
		onUpdated(() => {
			// console.log('updated!');
		});
		onUnmounted(() => {
			// console.log('unmounted!');
		});
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
			this.board = key;
		},
		async registerUser() {
			if (this.cpassword !== this.password) {
				alert('Password should match');
			} else {
				this.resetPopup();
				let formData = new FormData();
				formData.append('username', this.username);
				formData.append('password', this.password);
				const res = await fetch('http://localhost:8241/register', {
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
			const res = await fetch('http://localhost:8241/login', {
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
				location.href = `http://localhost:8241/${this.alert.errors.status}`;
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
			const res = await fetch('http://localhost:8241/logout', {
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
				location.href = `http://localhost:8241/${this.alert.errors.status}`;
			} else {
				this.alert.success = jsonData;
				await Swal.fire({
					...config,
					title: 'Logout success',
					text: jsonData.msg,
					icon: 'success',
				});
			}
			location.href = 'http://localhost:8241/';
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
				const res = await fetch('http://localhost:8241/plan/new', {
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
					location.href = `http://localhost:8241/${this.alert.errors.status}`;
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
					edited = new Date().toISOString(),
					id = Math.floor((1 + Math.random()) * 0x10000)
						.toString(16)
						.substring(1);
				const newBoard = {
					name: boardName,
					id,
					notes: [],
					created,
					edited,
				};
				this.boards.push(newBoard);
			}
		},
		addNewNote() {
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
				(edited = new Date().toISOString()),
				(id = Math.floor((1 + Math.random()) * 0x10000)
					.toString(16)
					.substring(1));
			const newNote = {
				id,
				created,
				edited,
				tags,
				content,
			};
			this.boards[parseInt(this.board)].notes.push(newNote);
			this.resetInput();
		},
		// dragStartBoard(event, targetIndex) {
		// 	this.drag = { target: { index: targetIndex, element: event.target } };
		// 	event.target.style.opacity = '0.4';
		// 	event.dataTransfer.effectAllowed = 'move';
		// },
		// dragEndBoard(event) {
		// 	event.target.style.opacity = '1';
		// 	const { target, source } = this.drag,
		// 		boards = this.boards;
		// 	console.log(target);
		// 	let temp = boards[target.index[0]];
		// 	boards[target.index[0]] = boards[source.index[0]];
		// 	boards[source.index[0]] = temp;
		// 	this.boards = boards;
		// 	// this.drag = null;
		// },
		// dragOverBoard(event, sourceIndex) {
		// 	if (event.stopPropagation) {
		// 		event.stopPropagation(); // stops the browser from redirecting.
		// 	}
		// 	// e.originalEvent.dataTransfer.setData("text/plain", `${row}_${col}`);
		// 	this.drag = {
		// 		...this.drag,
		// 		source: { index: sourceIndex, element: event.target },
		// 	};
		// },
		// dragStartNote(event, sourceIndex) {
		// 	this.drag = { source: { index: sourceIndex, element: event.target } };
		// 	event.target.style.opacity = '0.4';
		// 	event.dataTransfer.effectAllowed = 'move';
		// },
		// dragEndNote(event) {
		// 	event.target.style.opacity = '1';
		// 	if (this.drag !== null) {
		// 		const { target, source } = this.drag,
		// 			boards = this.boards;
		// 		if (source.index.length == 2) {
		// 			let t1 = boards[source.index[0]].notes.splice(source.index[2], 1);
		// 			if (target.index.length == 2) {
		// 				boards[target.index[0]].notes.splice(target.index[1], 0, t1);
		// 			} else if (target.index.length === 1) {
		// 				boards[target.index[0]].notes.push(t1);
		// 			}
		// 		}
		// 		this.boards = boards;
		// 	}
		// 	// boards[target.index] = boards[source.index];
		// 	// boards[source.index] = temp;
		// 	// this.boards = boards;
		// 	// this.drag = null;
		// },
		// dragOverNote(event, targetIndex) {
		// 	if (event.stopPropagation) {
		// 		event.stopPropagation(); // stops the browser from redirecting.
		// 	}
		// 	// e.originalEvent.dataTransfer.setData("text/plain", `${row}_${col}`);
		// 	this.drag = {
		// 		...this.drag,
		// 		target: { index: targetIndex, element: event.target },
		// 	};
		// },
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
			const res = await fetch('http://localhost:8241/plan/save', {
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
				location.href = `http://localhost:8241/${this.alert.errors.status}`;
			} else {
				this.alert.success = jsonData;
				await Swal.fire({
					...config,
					title: 'Saving success',
					text: jsonData.msg,
					icon: 'success',
				});
			}
			location.reload();
		},
		async loadXML() {
			let slug = location.pathname.split('/');
			slug = slug[slug.length - 1];
			const formData = new FormData();
			formData.append('slug', slug);
			const res = await fetch('http://localhost:8241/plan/load', {
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
				location.href = `http://localhost:8241/${this.alert.errors.status}`;
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
