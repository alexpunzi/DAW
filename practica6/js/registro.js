const camposRegistro = [
    { id: "nombre", validar: validarNombre },
    { id: "apellidos", validar: crearValidadorApellidos(false) }, // opcional en el registro
    { id: "email", validar: validarEmail },
    { id: "password", validar: validarPassword },
    { id: "confirmar_password", validar: validarRepetir },
    { id: "sexo", validar: validarSexo },
    { id: "fecha_nacimiento", validar: validarFecha }
];

document.addEventListener("DOMContentLoaded", () => {
    activarValidacion("form_registro", camposRegistro);
});
