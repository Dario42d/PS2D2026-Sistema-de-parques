// login.js

const form = document.getElementById("loginForm");
const email = document.getElementById("email");
const password = document.getElementById("password");
const errorMsg = document.getElementById("errorMsg");
const togglePassword = document.getElementById("togglePassword");

// Mostrar / ocultar contraseña
togglePassword.addEventListener("click", () => {
    password.type = password.type === "password" ? "text" : "password";
});

// Validar email
function validarEmail(correo) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(correo);
}

form.addEventListener("submit", async (e) => {
    e.preventDefault();

    let errores = [];

    if (email.value.trim() === "") {
        errores.push("El correo es obligatorio");
    } else if (!validarEmail(email.value)) {
        errores.push("Correo inválido");
    }

    if (password.value.trim() === "") {
        errores.push("La contraseña es obligatoria");
    }

    if (errores.length > 0) {
        errorMsg.style.color = "red";
        errorMsg.innerHTML = errores.join("<br>");
        return;
    }

    try {
        const res = await fetch("login.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                email: email.value,
                password: password.value
            })
        });

        const data = await res.json();

        if (!res.ok) throw new Error(data.mensaje);

        errorMsg.style.color = "green";
        errorMsg.textContent = "Login válido";

    } catch (error) {
        errorMsg.style.color = "red";
        errorMsg.textContent = error.message;
    }
});