function togglePasswordVisibility() {
  const passwordInput = document.getElementById("password");
  const checkbox = document.getElementById("showPasswordCheckbox");

  if (checkbox.checked) {
    passwordInput.type = "text";
  } else {
    passwordInput.type = "password";
  }
}
