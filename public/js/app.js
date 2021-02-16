const App = {
	data() {
		return {
			darkMode: false,
			search: '',
		};
	},
	mounted() {},
	methods: {
		toggleDark() {
			this.darkMode = !this.darkMode;
		},
	},
};

Vue.createApp(App).mount('#root');
