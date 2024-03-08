// ! -------------------- MDP Form-control  ----------------------

const errorMsg = document.querySelector(".error");
const password = document.querySelector("#form_user_password");
const form = document.querySelector("form");

const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
form.addEventListener("submit", (e) => {
  console.log(password.value);
  if (password.value.length < 6) {
    errorFun(e, "Your password should be at least 6 Chars ! ");
  }
  if (password.value.length === 0) {
    errorFun(e, "Password Can't be empty !");
  }
});

const errorFun = (e, value) => {
  errorMsg.innerHTML = value;
  e.preventDefault();
};

// ! -------------------- Recherche avancée  ----------------------

document.getElementById("search").addEventListener("keyup", function () {
  var input, filter, table, tr, td, i, txtValue, txtValue2;
  input = document.getElementById("search");
  filter = input.value.toUpperCase();
  table = document.getElementsByTagName("table");
  tr = document.querySelectorAll("table tr");

  for (i = 1; i < tr.length; i++) {
    tdId = tr[i].getElementsByTagName("td")[0];
    tdEmail = tr[i].getElementsByTagName("td")[4];
    if (tdId || tdEmail) {
      txtValue = tdId.textContent || tdId.innerText;
      txtValue2 = tdEmail.textContent || tdEmail.innerText;

      if (
        txtValue.toUpperCase().indexOf(filter) > -1 ||
        txtValue2.toUpperCase().indexOf(filter) > -1
      ) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
});

// ! -------------------- Filter  ----------------------
const selectFilter = document.getElementById("select");
selectFilter.addEventListener("change", () => {
  var selectedRole = selectFilter.value;
  var tableRows = document.querySelectorAll("table tr");
  tableRows.forEach((row) => {
    var roles = row.cells[3].textContent;
    if (selectedRole === "" || roles.includes(selectedRole)) {
      row.style.display = "";
    } else {
      row.style.display = "none";
    }
  });
});

// ! ------------- Settings Password ------------------------



