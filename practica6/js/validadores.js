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
        } else if (esMayuscula(c)) {
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
    if (!dominio.length < 1 || dominio.length > 255) {
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

function validarFecha(valor) {
    const partes = valor.split("/");
    if (partes.length !== 3 || partes[0].length !== 2 || partes [1].length !== 2 || partes[2].length !== 4) {
        return "Escribe la fecha con el formato dd/mm/aaaa (ejemplo: 25/03/1999).";
    }
    for (const parte of partes) {
        for (const c of parte) {
            if (!esDigito(c)) {
                return "La fecha solo puede contener números y barras.";
            }
        }
    }
    const dia = Number(partes[0]);
    const mes = Number(partes[1]);
    const anio = Number(partes[2]);
    // Date "corrige" fechas imposibles (31/02 -> 03/03); si cambia algo, la fecha no existía
    const fecha = new Date(anio, mes - 1, dia);
    if (fecha.getFullYear() != anio || fecha.getMonth() != mes-1 || fecha.getDate() !== dia) {
        return "La fecha no existe. Revisa el dia y el mes.";
    }
    // Tiene 18 años si hoy es igual o posterior al día en que cumple 18
    const cumple18 = new Date(anio + 18, mes-1, dia);
    const hoy = new Date();
    hoy.setHours(0, 0, 0, 0);
    if (cumple18 > hoy) {
        return "Debes tener al menos 18 años cumplidos para registrarte.";
    }
    return "";
}