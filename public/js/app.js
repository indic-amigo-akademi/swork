const App = {
	data() {
		return {
			darkMode: false,
			showProfileDropdown: false,
			showRegisterModal: false,
			showRegisterForm: false,
			search: '',
		};
	},
	mounted() {},

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
	},
};

const app = Vue.createApp(App).mount('#root');
