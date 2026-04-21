const form = document.getElementById("registroForm");
const nombre = document.getElementById("nombre");
const email = document.getElementById("email");
const password = document.getElementById("password");
const confirmPassword = document.getElementById("confirmPassword");
const errorMsg = document.getElementById("errorMsg");

const togglePass1 = document.getElementById("togglePass1");
const togglePass2 = document.getElementById("togglePass2");

// Mostrar/ocultar contraseñas
togglePass1.addEventListener("click", () => {
    password.type = password.type === "password" ? "text" : "password";
});

togglePass2.addEventListener("click", () => {
    confirmPassword.type = confirmPassword.type === "password" ? "text" : "password";
});

// Validación email
function validarEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

// Validación contraseña segura
function validarPassword(pass) {
    return pass.length >= 6;
}

form.addEventListener("submit", function(e) {
    e.preventDefault();

    let errores = [];

    if(nombre.value.trim() === "") {
        errores.push("El nombre es obligatorio");
    }

    if(email.value.trim() === "") {
        errores.push("El correo es obligatorio");
    } else if(!validarEmail(email.value)) {
        errores.push("Correo no válido");
    }

    if(password.value.trim() === "") {
        errores.push("La contraseña es obligatoria");
    } else if(!validarPassword(password.value)) {
        errores.push("La contraseña debe tener al menos 6 caracteres");
    }

    if(confirmPassword.value !== password.value) {
        errores.push("Las contraseñas no coinciden");
    }

    if(errores.length > 0) {
        errorMsg.style.color = "#f87171";
        errorMsg.innerHTML = errores.join("<br>");
        return;
    }

    // Simulación de registro exitoso
    errorMsg.style.color = "#22c55e";
    errorMsg.textContent = "Registro exitoso (listo para backend)";
});