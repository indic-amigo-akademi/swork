const date = new Date();

document.getElementById('full-year').innerText = date.getFullYear();

const hello = document.getElementById('hello');

hello.addEventListener('click', async function () {
	const res = await fetch('/');
	let log = await res.text();
	console.log(log);
});
