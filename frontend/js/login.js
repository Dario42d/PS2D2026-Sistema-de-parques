const form = document.getElementById("loginForm");
const email = document.getElementById("email");
const password = document.getElementById("password");
const errorMsg = document.getElementById("errorMsg");
const togglePassword = document.getElementById("togglePassword");

// Mostrar / ocultar contraseña
togglePassword.addEventListener("click", () => {
    if (password.type === "password") {
        password.type = "text";
    } else {
        password.type = "password";
    }
});

// Validación email
function validarEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

form.addEventListener("submit", function(e) {
    e.preventDefault();

    let errores = [];

    if(email.value.trim() === "") {
        errores.push("El correo es obligatorio");
    } else if(!validarEmail(email.value)) {
        errores.push("El correo no es válido");
    }

    if(password.value.trim() === "") {
        errores.push("La contraseña es obligatoria");
    } else if(password.value.length < 4) {
        errores.push("La contraseña debe tener al menos 4 caracteres");
    }

    if(errores.length > 0) {
        errorMsg.innerHTML = errores.join("<br>");
        return;
    }

    errorMsg.style.color = "#22c55e";
    errorMsg.textContent = "Login válido (listo para backend)";
});