const camposLogin = [
    {
        id: "login_email",
        // Mismas reglas que el email del registro (se ignoran los espacios de los extremos)
        validar: (valor) => validarEmail(valor.trim())
    },
    {
        id: "login_password",
        validar: (valor) => estaVacio(valor)
            ? "Escribe tu contraseña (no puede estar vacía ni ser solo espacios)." : ""
    }
];

document.addEventListener("DOMContentLoaded", () => {
    activarValidacion("form_login", camposLogin);
});
