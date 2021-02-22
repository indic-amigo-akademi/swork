const date = new Date();

document.getElementById('full-year').innerText = date.getFullYear();

const hello = document.getElementById('hello');

hello.addEventListener('click', async function () {
	const res = await fetch('/');
	let log = await res.text();
	console.log(log);
});

function toggleSignup() {
	var edit = document.getElementById("SignUp");
	edit.style.display = "block";
	edit = document.getElementById("Login");
	edit.style.display = "none";
}

function toggleLogin() {
	var edit = document.getElementById("SignUp");
	edit.style.display = "none";
	edit = document.getElementById("Login");
	edit.style.display = "block";
}
  