// Comprobación de caracteres
function esMayuscula(c) {
    return c >= "A" && c <= "Z";
}

function esMinuscula(c) {
    return c >= "a" && c <= "z";
}

function esLetra(c) {
    return esMayuscula(c) || esMinuscula(c);
}

function esDigito(c) {
    return c >= "0" && c <= "9";
}

// Validación del nombre de usuario
function validarNombre(valor) {
    if (valor.length < 3 || valor.length > 15) {
        return "El nombre debe tener entre 3 y 15 caracteres.";
    }
    if (esDigito(valor[0])) {
        return "El nombre no puede empezar por un número.";
    }
    for (const c of valor) {
        if (!esLetra(c) && !esDigito(c)) {
            return "Solo se permiten letras del alfabeto inglés y números (sin espacios, ñ ni tildes).";
        }
    }
    return "";
}

// Validación de la contraseña
function validarPassword(valor) {
    if (valor.length < 6 || valor.length > 15) {
        return "La contraseña debe tener entre 6 y 15 caracteres.";
    }
    let hayMayuscula = false;
    let hayMinuscula = false;
    let hayNumero = false;
    for (const c of valor) {
        if (esMayuscula(c)) {
            hayMayuscula = true;
        } else if (esMinuscula(c)) {
            hayMinuscula = true;
        } else if (esDigito(c)) {
            hayNumero = true;
        } else if (c !== "-" && c !== "_") {
            return "Solo se permiten letras inglesas, números, guión (-) y guión bajo (_).";
        }
    }
    if (!hayMayuscula || !hayMinuscula || !hayNumero) {
        return "Debe contener al menos una mayúscula, una minúscula y un número.";
    }
    return "";
}

// Comprobación de la contraseña repetida
function validarRepetir(valor) {
    if (valor === "") {
        return "Repite la contraseña.";
    }
    if (valor !== $("password").value) {
        return "Las contraseñas no coinciden.";
    }
    return "";
}

// Validación del sexo
function validarSexo(valor) {
    return valor === "" ? "Selecciona una opción" : "";
}

// Validación del correo electrónico
const ESPECIALES_LOCAL = "!#$%&'*+-/=?^_`{|}~";

function validarParteLocal(local) {
    if (local.length < 1 || local.length > 64) {
        return "La parte anterior a la @ debe tener entre 1 y 64 caracteres.";
    }
    if (local[0] === "." || local[local.length - 1] === ".") {
        return "Antes de la @ no puede haber un punto al principio ni al final.";
    }
    if (local.includes("..")) {
        return "Antes de la @ no puede haber dos puntos seguidos.";
    }
    for (const c of local) {
        if (!esLetra(c) && !esDigito(c) && c !== "." && !ESPECIALES_LOCAL.includes(c)) {
            return `El carácter "${c}" no está permitido antes de la @.`;
        }
    }
    return "";
}

function validarDominio(dominio) {
    if (dominio.length < 1 || dominio.length > 255) {
        return "El dominio (después de la @) debe tener entre 1 y 255 caracteres.";
    }
    for (const sub of dominio.split(".")) {
        if (sub.length < 1 || sub.length > 63) {
            return "Cada parte del dominio (entre puntos) debe tener entre 1 y 63 caracteres.";
        }
        if (sub[0] === "-" || sub[sub.length - 1] === "-") {
            return "Ninguna parte del dominio puede empezar ni acabar en guión.";
        }
        for (const c of sub) {
            if (!esLetra(c) && !esDigito(c) && c !== "-") {
                return `El carácter "${c}" no está permitido en el dominio.`;
            }
        }
    }
    return "";
}

function validarEmail(valor) {
    if (valor === "") {
        return "El email es obligatorio.";
    }
    if (valor.length > 254) {
        return "El email no puede superar los 254 caracteres.";
    }
    const posArroba = valor.indexOf("@");

    if (posArroba === -1 || posArroba != valor.lastIndexOf("@")) {
        return "Debe tener una única @ (ejemplo: usuario@dominio.com).";
    }

    const local = valor.slice(0, posArroba);
    const dominio = valor.slice(posArroba + 1);
    return validarParteLocal(local) || validarDominio(dominio);
}

// Validación de la fecha de nacimiento
function validarFecha(valor) {
    if (estaVacio(valor)) {
        return "Selecciona tu fecha de nacimiento (día, mes y año completos).";
    }
    let anio, mes, dia;
    if (valor.includes("/")) {
        const p = valor.split("/");
        if (p.length !== 3) {
            return "Escribe la fecha con el formato dd/mm/aaaa.";
        }
        [dia, mes, anio] = p.map(Number);
    } else {
        const p = valor.split("-");
        if (p.length !== 3) {
            return "La fecha no es válida. Vuelve a seleccionarla.";
        }
        [anio, mes, dia] = p.map(Number);
    }
    if (Number.isNaN(anio) || Number.isNaN(mes) || Number.isNaN(dia)) {
        return "La fecha no es válida. Vuelve a seleccionarla.";
    }
    const fecha = new Date(anio, mes - 1, dia);
    if (fecha.getFullYear() !== anio || fecha.getMonth() !== mes - 1 || fecha.getDate() !== dia) {
        return "La fecha no es válida. Vuelve a seleccionarla.";
    }
    const cumple18 = new Date(anio + 18, mes - 1, dia);
    const hoy = new Date();
    hoy.setHours(0, 0, 0, 0);
    if (cumple18 > hoy) {
        return "Debes tener al menos 18 años cumplidos para registrarte.";
    }
    return "";
}

// Validación del nombre y apellidos reales
const LETRAS_ACENTUADAS = "áéíóúüñçàèìòùâêîôûäëïöÁÉÍÓÚÜÑÇÀÈÌÒÙÂÊÎÔÛÄËÏÖ";

function esLetraPersona(c) {
    return esLetra(c) || LETRAS_ACENTUADAS.includes(c);
}

function comprobarNombrePersona(valor, maximo) {
    const texto = valor.trim();
    if (texto.length > maximo) {
        return `Máximo ${maximo} caracteres.`;
    }
    let hayLetra = false;
    for (const c of texto) {
        if (esLetraPersona(c)) {
            hayLetra = true;
        } else if (esDigito(c)) {
            return "No se admiten números.";
        } else if (c !== " " && c !== "-" && c !== "'") {
            return `El carácter "${c}" no está permitido. Solo letras, espacios, guion (-) y apóstrofo (').`;
        }
    }
    if (!hayLetra) {
        return "Debe contener letras.";
    }
    const primero = texto[0];
    const ultimo = texto[texto.length - 1];
    if (primero === "-" || primero === "'" || ultimo === "-" || ultimo === "'") {
        return "No puede empezar ni terminar con guion o apóstrofo.";
    }
    return "";
}

// Validación del nombre
function validarNombrePersona(valor) {
    if (estaVacio(valor)) {
        return "Escribe tu nombre.";
    }
    return comprobarNombrePersona(valor, 40);
}

// Validación de los apellidos
function crearValidadorApellidos(obligatorio) {
    return (valor) => {
        if (estaVacio(valor)) {
            return obligatorio ? "Escribe al menos un apellido." : "";
        }
        return comprobarNombrePersona(valor, 60);
    };
}