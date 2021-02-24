const { onMounted, onUpdated, onUnmounted } = Vue;

const App = {
	data() {
		return {
			darkMode: false || localStorage.getItem('darkMode') === 'true',
			showProfileDropdown: false,
			showRegisterModal: false,
			showRegisterForm: false,
			search: '',
			username: '',
			password: '',
			cpassword: '',
			alert: {
				success: '',
				error: '',
			},
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
					alert(jsonData.error);
				} else {
					this.alert.success = jsonData;
					alert(jsonData.msg);
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
				alert(jsonData.error);
				location.href = `http://localhost:8241/${this.alert.errors.status}`;
			} else {
				this.alert.success = jsonData;
				alert(jsonData.msg);
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
				alert(jsonData.error);
				location.href = `http://localhost:8241/${this.alert.errors.status}`;
			} else {
				this.alert.success = jsonData;
				alert(jsonData.msg);
			}
			location.reload();
		},
	},
};

const app = Vue.createApp(App).mount('#root');
