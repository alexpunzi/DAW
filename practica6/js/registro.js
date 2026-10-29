const camposRegistro = [
    { id: "nombre", validar: validarNombre },
    { id: "email", validar: validarEmail},
    { id: "password", validar: validarPassword},
    { id: "confirmar_password", validarRepetir},
    { id: "sexo", validarSexo},
    { id: "fecha_nacimiento", validarFecha}
]