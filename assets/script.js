function togglePasswordVisibility() {
  const passwordInput = document.getElementById("password");
  const checkbox = document.getElementById("showPasswordCheckbox");

  if (checkbox.checked) {
    passwordInput.type = "text";
  } else {
    passwordInput.type = "password";
  }
}

document.addEventListener("DOMContentLoaded", function () {
  // Konversi semua elemen dengan class "local-time"
  document.querySelectorAll(".local-time").forEach(function (el) {
    const timestamp = parseInt(el.getAttribute("data-timestamp"));
    if (!isNaN(timestamp)) {
      const date = new Date(timestamp * 1000); // JS butuh milidetik
      el.textContent = date.toLocaleString(); // Otomatis sesuai timezone user
    }
  });
});
