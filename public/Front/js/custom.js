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
