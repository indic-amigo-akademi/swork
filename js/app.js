const App = {
	data() {
		return {
			darkMode: false,
			search: '',
		};
	},
	methods: {
		toggleDark() {
			this.darkMode = !this.darkMode;
		},
	},
};

Vue.createApp(App).mount('#root');
