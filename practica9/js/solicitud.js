// Validación del formulario "Solicitar folleto".
// Reutiliza las funciones comunes de utilidades.js ($, estaVacio, activarValidacion...)
// y validarEmail / esDigito de validadores.js.

// Devuelve un validador que exige texto (no vacío ni solo espacios) y un máximo de caracteres
function obligatorio(descripcion, maximo = 200) {
    return (valor) => {
        if (estaVacio(valor)) {
            return `${descripcion} es obligatorio.`;
        }
        if (valor.length > maximo) {
            return `${descripcion} no puede superar los ${maximo} caracteres.`;
        }
        return "";
    };
}

// Devuelve un validador para un <select>: hay que elegir una opción
function elegirOpcion(mensaje) {
    return (valor) => (valor === "" ? mensaje : "");
}

function validarCorreo(valor) {
    return validarEmail(valor.trim());
}

// Opcional: si se rellena, solo dígitos, espacios y un + inicial; entre 9 y 15 cifras
function validarTelefono(valor) {
    if (estaVacio(valor)) {
        return "";
    }
    const texto = valor.trim();
    let cifras = 0;
    for (let i = 0; i < texto.length; i++) {
        const c = texto[i];
        if (esDigito(c)) {
            cifras++;
        } else if (c === "+" && i === 0) {
            continue;
        } else if (c !== " ") {
            return "El teléfono solo puede contener números, espacios y un + al principio.";
        }
    }
    if (cifras < 9 || cifras > 15) {
        return "El teléfono debe tener entre 9 y 15 cifras.";
    }
    return "";
}

function validarCodigoPostal(valor) {
    if (estaVacio(valor)) {
        return "El código postal es obligatorio.";
    }
    if (valor.length !== 5) {
        return "El código postal debe tener exactamente 5 dígitos.";
    }
    for (const c of valor) {
        if (!esDigito(c)) {
            return "El código postal solo puede contener números.";
        }
    }
    return "";
}

function validarCopias(valor) {
    if (estaVacio(valor)) {
        return "Indica el número de copias.";
    }
    for (const c of valor.trim()) {
        if (!esDigito(c)) {
            return "El número de copias debe ser un número entero positivo.";
        }
    }
    const copias = Number(valor);
    if (copias < 1 || copias > 99) {
        return "El número de copias debe estar entre 1 y 99.";
    }
    return "";
}

// Opcional: si se rellena, no puede ser anterior a hoy (el valor llega como aaaa-mm-dd)
function validarFechaRecepcion(valor) {
    if (estaVacio(valor)) {
        return "";
    }
    const partes = valor.split("-");
    if (partes.length !== 3) {
        return "La fecha no es válida. Vuelve a seleccionarla.";
    }
    const [anio, mes, dia] = partes.map(Number);
    const fecha = new Date(anio, mes - 1, dia);
    if (fecha.getFullYear() !== anio || fecha.getMonth() !== mes - 1 || fecha.getDate() !== dia) {
        return "La fecha no es válida. Vuelve a seleccionarla.";
    }
    const hoy = new Date();
    hoy.setHours(0, 0, 0, 0);
    return fecha < hoy ? "La fecha de recepción no puede ser anterior a hoy." : "";
}

const camposSolicitud = [
    { id: "nombre",          validar: validarNombrePersona },
    { id: "apellidos",       validar: crearValidadorApellidos(true) },
    { id: "correo",          validar: validarCorreo },
    { id: "telefono",        validar: validarTelefono },
    { id: "calle",           validar: obligatorio("La calle") },
    { id: "numero",          validar: obligatorio("El número", 20) },
    { id: "puerta",          validar: obligatorio("La puerta", 20) },
    { id: "cp",              validar: validarCodigoPostal },
    { id: "localidad",       validar: obligatorio("La localidad") },
    { id: "provincia",       validar: obligatorio("La provincia") },
    { id: "pais",            validar: elegirOpcion("Selecciona un país.") },
    { id: "anuncio",         validar: elegirOpcion("Selecciona el anuncio que quieres imprimir.") },
    { id: "num_copias",      validar: validarCopias },
    { id: "fecha_recepcion", validar: validarFechaRecepcion }
];

document.addEventListener("DOMContentLoaded", () => {
    activarValidacion("form_solicitud", camposSolicitud);
});
