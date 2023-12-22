let passwordInput = document.getElementById("password");
let konfirmasiPasswordInput = document.getElementById("konfirmasi-password");
let showPasswordButtons = document.querySelectorAll("[data-action='show-password']");
let hidePasswordButtons = document.querySelectorAll("[data-action='hide-password']");

function showPassword() {
  passwordInput.type = "text";
  if (konfirmasiPasswordInput) konfirmasiPasswordInput.type = "text";
  showPasswordButtons.forEach(function (btn) {
    btn.style.display = "none";
  });
  hidePasswordButtons.forEach(function (btn) {
    btn.style.display = "block";
  });
}

function hidePassword() {
  passwordInput.type = "password";
  if (konfirmasiPasswordInput) konfirmasiPasswordInput.type = "password";
  hidePasswordButtons.forEach(function (btn) {
    btn.style.display = "none";
  });
  showPasswordButtons.forEach(function (btn) {
    btn.style.display = "block";
  });
}

if (showPasswordButtons.length && hidePasswordButtons.length) {
  showPasswordButtons.forEach(function (button) {
    button.addEventListener("click", showPassword);
  });

  hidePasswordButtons.forEach(function (button) {
    button.addEventListener("click", hidePassword);
  });
}
