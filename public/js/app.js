const App = {
	data() {
		return {
			darkMode: false,
			showProfileDropdown: false,
			search: '',
		};
	},
	mounted() {
		window.onclick =function (event){
			if(!event.target.matches('.dropdownButton')){
				this.showProfileDropdown=false;
			}
		}
	},
	
	methods: {
		toggleDark() {
			this.darkMode = !this.darkMode;
		},
		toggleShowProfileDropdown(){
			this.showProfileDropdown = !this.showProfileDropdown;
		},
	},

};

Vue.createApp(App).mount('#root');
