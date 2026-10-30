function esMayuscula(c) {
    return c >= "A" && c <= "Z";
}

function esMinuscula(c) {
    return c >= "a" && c <= "z";
}

function esLetra (c) {
    return esMayuscula(c) || esMinuscula(c);
}

function esDigito(c) {
    return c >= "0" && c <= "9";
}

// Todos los validadores devuelven "" si es correcto, o el mensaje de error.

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
        return "Debe contener al menos una mayúscula, una minúscula y un número."
    }
    return "";
}

function validarRepetir(valor) {
    if (valor === "") {
        return "Repite la contraseña.";
    }
    if (valor !== $("password").value) {
        return "Las contraseñas no coinciden.";
    }
    return "";
}

function validarSexo(valor) {
    return valor === "" ? "Selecciona una opción" : "";
}

const ESPECIALES_LOCAL = "!#$%&'*+-/=?^_`{|}~";

// Verifica que el texto anterior a la @ de un correo electrónico cumpla con las
// normas estándar de formato y longitud.
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
            return "Ninguna parte del dominio puede empezar ni acabar en guión."
        }
        for (const c of sub) {
            if (!esLetra(c) && !esDigito(c) && c!=="-") {
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
        return "El email no puede superar los 254 caracteres."
    }
    const posArroba = valor.indexOf("@");

    // Comprueba si falta la arroba o si hay más de una
    if (posArroba === -1 || posArroba != valor.lastIndexOf("@")) { // -1 comprueba si falta la @
        return "Debe tener una única @ (ejemplo: usuario@dominio.com).";
    }

    const local = valor.slice(0, posArroba);
    const dominio = valor.slice(posArroba + 1);
    return validarParteLocal(local) || validarDominio(dominio); // "" || "" = ""
}

// Con <input type="date"> el valor llega SIEMPRE como "aaaa-mm-dd",
// o como "" si el usuario no ha elegido fecha o la ha dejado a medias.
function validarFecha(valor) {
    if (estaVacio(valor)) {
        return "Selecciona tu fecha de nacimiento (día, mes y año completos).";
    }
    let anio, mes, dia;
    if (valor.includes("/")) {
        // Respaldo: navegadores antiguos que tratan type="date" como texto (dd/mm/aaaa)
        const p = valor.split("/");
        if (p.length !== 3) {
            return "Escribe la fecha con el formato dd/mm/aaaa.";
        }
        [dia, mes, anio] = p.map(Number);
    } else {
        const p = valor.split("-"); // ["aaaa", "mm", "dd"]
        if (p.length !== 3) {
            return "La fecha no es válida. Vuelve a seleccionarla.";
        }
        [anio, mes, dia] = p.map(Number);
    }
    if (Number.isNaN(anio) || Number.isNaN(mes) || Number.isNaN(dia)) {
        return "La fecha no es válida. Vuelve a seleccionarla.";
    }
    // Red de seguridad: Date "corrige" fechas imposibles (31/02 -> 03/03); si cambia algo, no existía
    const fecha = new Date(anio, mes - 1, dia);
    if (fecha.getFullYear() !== anio || fecha.getMonth() !== mes - 1 || fecha.getDate() !== dia) {
        return "La fecha no es válida. Vuelve a seleccionarla.";
    }
    // Tiene 18 años si hoy es igual o posterior al día en que cumple 18
    const cumple18 = new Date(anio + 18, mes - 1, dia);
    const hoy = new Date();
    hoy.setHours(0, 0, 0, 0);
    if (cumple18 > hoy) {
        return "Debes tener al menos 18 años cumplidos para registrarte.";
    }
    return "";
}

// ---- Nombre y apellidos de una PERSONA (distintos del "nombre de usuario" del registro) ----
// Aquí sí se permiten tildes, ñ, espacios, guion y apóstrofo (José, María José, De la Cruz, O'Brien).
const LETRAS_ACENTUADAS = "áéíóúüñçàèìòùâêîôûäëïöÁÉÍÓÚÜÑÇÀÈÌÒÙÂÊÎÔÛÄËÏÖ";

function esLetraPersona(c) {
    return esLetra(c) || LETRAS_ACENTUADAS.includes(c);
}

// Comprueba un texto de nombre o apellidos. Devuelve "" si es correcto o el mensaje de error.
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

// Nombre de pila: obligatorio, hasta 40 caracteres
function validarNombrePersona(valor) {
    if (estaVacio(valor)) {
        return "Escribe tu nombre.";
    }
    return comprobarNombrePersona(valor, 40);
}

// Apellido/s: hasta 60 caracteres. Si "obligatorio" es false, se puede dejar vacío.
function crearValidadorApellidos(obligatorio) {
    return (valor) => {
        if (estaVacio(valor)) {
            return obligatorio ? "Escribe al menos un apellido." : "";
        }
        return comprobarNombrePersona(valor, 60);
    };
}
