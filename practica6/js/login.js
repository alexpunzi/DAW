const camposLogin = [
    {
        id: "login_usuario",
        validar: (valor) => estaVacio(valor)
            ? "Escribe tu nombre de usuario (no puede estar vacío ni ser solo espacios)." : ""
    },
    {
        id: "login_password",
        validar: (valor) => estaVacio(valor)
            ? "Escribe tu contraseña (no puede estar vacía ni ser solo espacios)." : ""
    }
];

document.addEventListener("DOMContentLoaded", () => {
    activarValidacion("form_login", camposLogin);
})