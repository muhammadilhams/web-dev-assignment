var counter = 1;
var element = {};

function addElement() {
  const input = document.getElementById("label");
  inputValue = input.value;
  if (inputValue === "") {
    alert("unfilled");
    input.focus();
    input.value = "";
    return;
  }
  addItem(inputValue);
  input.focus();
  input.value = "";
}
function addItem(a) {
  a = a.toUpperCase();
  if (element.hasOwnProperty(a)) {
    element[a] = element[a] + 1;
    var x = (document.getElementById(a).innerText = element[a]);
  } else {
    element[a] = counter;
    document.getElementById("list").innerHTML += `<li>
             <button class ="inputan btn btn-outline-secondary" >${a}</button>
 
            <button class="delete btn btn-danger" onclick="deleteElement()">remove</button>
          </li>`;
  }
  console.log(element);
}
function deleteElement() {
  const deleted = document.getElementsByClassName("delete");
  for (var i = 0; i < deleted.length; i++) {
    deleted[i].onclick = function () {
      var konfirmasi = confirm("are you sure to remove this?");
      if (konfirmasi == true) {
        this.parentElement.style.display = "none";
        addItem();
      }
    };
  }
}
