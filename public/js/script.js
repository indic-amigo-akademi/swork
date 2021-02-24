const date = new Date();

document.getElementById("full-year").innerText = date.getFullYear();

const hello = document.getElementById("hello");

hello.addEventListener("click", async function() {
  const res = await fetch("/");
  let log = await res.text();
  console.log(log);
});

/* megha */

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

function allowDrop(ev) {
  ev.preventDefault();
}

function dragStart(ev) {
  ev.dataTransfer.setData("text/plain", ev.target.id);
}

function dropIt(ev) {
  ev.preventDefault();
  let sourceId = ev.dataTransfer.getData("text/plain");
  let sourceIdEl = document.getElementById(sourceId);
  let sourceIdParentEl = sourceIdEl.parentElement;
  let targetEl = document.getElementById(ev.target.id);
  let targetParentEl = targetEl.parentElement;

  if (targetParentEl.id !== sourceIdParentEl.id) {
    if (targetEl.className === sourceIdEl.className) {
      targetParentEl.appendChild(sourceIdEl);
    } else {
      targetEl.appendChild(sourceIdEl);
    }
  } else {
    let holder = targetEl;
    let holderText = holder.textContent;
    targetEl.textContent = sourceIdEl.textContent;
    sourceIdEl.textContent = holderText;
    holderText = "";
  }
}
