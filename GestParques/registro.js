const form = document.getElementById("registroForm");
const nombre = document.getElementById("nombre");
const email = document.getElementById("email");
const password = document.getElementById("password");
const confirmPassword = document.getElementById("confirmPassword");
const errorMsg = document.getElementById("errorMsg");

form.addEventListener("submit", async (e) => {
    e.preventDefault();

    if (password.value !== confirmPassword.value) {
        errorMsg.textContent = "Las contraseñas no coinciden";
        errorMsg.style.color = "red";
        return;
    }

    try {
        const res = await fetch("registro.php", {   // 👈 CAMBIO IMPORTANTE
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                nombre: nombre.value,
                email: email.value,
                password: password.value
            })
        });

        const data = await res.json();

        if (!res.ok) throw new Error(data.mensaje);

        errorMsg.style.color = "green";
        errorMsg.textContent = data.mensaje;

        form.reset();

        setTimeout(() => {
            window.location.href = "login.html";
        }, 1500);

    } catch (err) {
        errorMsg.style.color = "red";
        errorMsg.textContent = err.message || "Error de conexión";
    }
});