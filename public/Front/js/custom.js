const inputs = document.querySelectorAll("input");
const email = inputs[0];
const something = document.querySelector(".error");
const submit = document.querySelector("button[type=submit]");

const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

submit.addEventListener("click", (e) => {
  if (!emailRegex.test(email.value))
    error_msg(e, "[ " + email.value + " ] is not a valid Email !");
  if (email.value == "") error_msg(e, "Email Can't be empty !");
});
const error_msg = (e, value) => {
  email.style.borderColor = "red";
  something.innerHTML = value;
  e.preventDefault();
};
