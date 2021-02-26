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
	data() {
		return {
			darkMode: true || localStorage.getItem('darkMode') === 'true',
			showProfileDropdown: false,
			showRegisterModal: false,
			showNoteModal: false,
			showRegisterForm: false,
			drag: null,
			search: '',
			username: '',
			password: '',
			cpassword: '',
			content: '',
			tags: '',
			tagsArray: new Set(['PHP', 'Symfony']),
			board: -1,
			alert: {
				success: '',
				error: '',
			},
			boards: [
				{
					name: 'hello',
					notes: [],
					created: '2021-02-26T11:11:03.262Z',
					edited: '2021-02-26T11:11:03.262Z',
				},
				{
					name: 'hi',
					notes: [
						{
							created: '2021-02-26T16:52:29.891Z',
							edited: '2021-02-26T16:52:29.891Z',
							tags: [
								{ index: 0, value: 'PHP' },
								{ index: 1, value: 'Symfony' },
							],
							content: 'My first Project on Symfony',
						},
					],

					created: '2021-02-26T11:11:05.876Z',
					edited: '2021-02-26T11:11:05.876Z',
				},
			],
		};
	},
	setup() {
		onMounted(() => {
			// console.log('mounted!');
		});
		onUpdated(() => {
			const dark = app.darkMode ? 'true' : 'false';
			localStorage.setItem('darkMode', dark);
			// console.log('updated!');
		});
		onUnmounted(() => {
			// console.log('unmounted!');
		});
	},
	methods: {
		resetPopup() {
			this.showProfileDropdown = false;
			this.showRegisterModal = false;
			this.showRegisterForm = false;
			this.showNoteModal = false;
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
			}
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
				(edited = new Date().toISOString());
			const newNote = {
				created,
				edited,
				tags,
				content,
			};
			this.boards[parseInt(this.board)].notes.push(newNote);
		},
		dragStartBoard(event, targetIndex) {
			this.drag = { targetIndex };
			event.target.style.opacity = '0.4';
			event.dataTransfer.effectAllowed = 'move';
		},
		dragEndBoard(event) {
			event.target.style.opacity = '1';
			const { targetIndex, sourceIndex } = this.drag,
				boards = this.boards;
			let temp = boards[targetIndex];
			boards[targetIndex] = boards[sourceIndex];
			boards[sourceIndex] = temp;
			this.boards = boards;
			this.drag = null;
		},
		dragOverBoard(event, sourceIndex) {
			if (event.stopPropagation) {
				event.stopPropagation(); // stops the browser from redirecting.
			}
			// e.originalEvent.dataTransfer.setData("text/plain", `${row}_${col}`);
			this.drag = { ...this.drag, sourceIndex };
		},
		marked(content) {
			return marked(content);
		},
	},
};

const app = Vue.createApp(App);
app.config.isCustomElement = (tag) => tag.startsWith('ion-');

const states = app.mount('#root');
