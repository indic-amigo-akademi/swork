const { onMounted, onUpdated, onUnmounted } = Vue;

const App = {
	data() {
		return {
			darkMode: false || localStorage.getItem('darkMode') === 'true',
			showProfileDropdown: false,
			showRegisterModal: false,
			showRegisterForm: false,
			search: '',
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
	},
};

const app = Vue.createApp(App).mount('#root');
