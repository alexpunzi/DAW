// Atajo para no tener que escribir document.getElementById(id) constantemente
function $(id) {
    return document.getElementById(id);
}

// Comprueba si un texto está completamente vacío
function estaVacio(texto) {
    return texto.trim() === "";
}

// Devuelve el aviso de error de un campo; si todavía no existe, lo crea (vacío) tras el campo.
// El aviso se crea una sola vez y se reutiliza (se rellena o se vacía), así nunca se duplica.
function obtenerAviso(campo) {
    let aviso = $(`error_${campo.id}`);
    if (!aviso) {
        aviso = document.createElement("span");
        aviso.className = "mensaje-error";
        aviso.id = `error_${campo.id}`; // id="email" -> "error_email"
        aviso.setAttribute("role", "alert"); // Los lectores de pantalla lo anuncian al rellenarse
        campo.parentElement.appendChild(aviso);
    }
    return aviso;
}

// Encargada de mostrar el aviso visual y de accesibilidad cuando un campo es incorrecto
function mostrarError(campo, mensaje) {
    const aviso = obtenerAviso(campo);
    aviso.textContent = `Error: ${mensaje}`;

    campo.classList.add("campo-error"); // Marcar el campo en rojo
    campo.setAttribute("aria-invalid", "true"); // Indica a los lectores de pantalla que el dato es inválido
    // Si el campo ya tenía texto de ayuda (data-ayuda="id"), se leen los dos
    const ayuda = campo.dataset.ayuda; // Lee el atributo personalizado data-ayuda del HTML si existe
    campo.setAttribute("aria-describedby", ayuda ? `${ayuda} ${aviso.id}` : aviso.id);
}

// Deja el campo como estaba antes de mostrar el error
function limpiarError(campo) {
    obtenerAviso(campo).textContent = ""; // Se vacía, no se elimina (ver obtenerAviso)
    campo.classList.remove("campo-error");
    campo.removeAttribute("aria-invalid");

    // Vuelve a apuntar solo al texto de ayuda (si lo tiene)
    const ayuda = campo.dataset.ayuda;
    if (ayuda) {
        campo.setAttribute("aria-describedby", ayuda);
    } else {
        campo.removeAttribute("aria-describedby");
    }
}

// Evalúa un único campo pasándole una función de validación personalizada (validar).
// Esta función debe devolver un mensaje de error si falla o vacío si es correcto.
// Dependiendo del resultado llama a mostrarError o limpiarError
function validarCampo(campo, validar) {
    const mensaje = validar(campo.value);
    if (mensaje) {
        mostrarError(campo, mensaje);
        return false;
    }
    limpiarError(campo);
    return true;
}

// Recibe un bloque con varios campos y sus respectivas reglas de validación (un array de objetos).
// Recorre todos ellos para validarlos de golpe.
// Si encuentra errores, guarda cuál fue el primer campo que falló y, al terminar usa .focus() para
// llevar automáticamente al cursor del usuario a ese lugar
function validarCampos(lista) {
    let primerError = null;
    for (const { id, validar } of lista) {
        const campo = $(id);
        if (!validarCampo(campo,validar) && primerError === null) {
            primerError = campo;
        }
    }
    if (primerError !== null) {
        primerError.focus(); // Lleva al usuario al primer fallo
    }
    return primerError === null;
}

// Motor que pone todo en marcha.
// Toma el ID del formulario y lista de campos a validar,
// y crea dos comportamientos automáticos
function activarValidacion(idFormulario, lista) {
    const formulario = $(idFormulario);
    if (!formulario) {
        return;
    }

    // Si el usuario pulsa "Enviar" con un campo recién editado, ese campo pierde el foco y su
    // error aparece o desaparece ANTES de soltar el clic; al cambiar la altura de la página el
    // botón se mueve y el clic se pierde. Evitamos que el botón le quite el foco al campo con
    // mousedown: el clic sigue funcionando y el formulario se valida entero en el submit.
    for (const boton of formulario.querySelectorAll('button[type="submit"]')) {
        boton.addEventListener("mousedown", (evento) => evento.preventDefault());
    }

    // Al intentar enviar el formulario (submit), ejecuta validarCampos.
    // Si hay algún error, cancela el envío al servidor (evento.preventDefault)
    formulario.addEventListener("submit", (evento) => {
        
        if (!validarCampos(lista)) {
            evento.preventDefault(); // Cancela el envío
        }
    });

    // Al terminar de escribir un campo se validará en tiempo real
    for (const { id, validar } of lista) {
        $(id).addEventListener("change", () => validarCampo($(id), validar));
    }
}
