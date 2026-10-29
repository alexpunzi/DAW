// Atajo para no tener que escribir document.getElementById(id) constantemente
function $(id) {
    return document.getElementById(id);
}

// Comprueba si un texto está completamente vacío
function estaVacio(texto) {
    return texto.trim() === "";
}

// Encargada de mostrar el aviso visual y de accesibilidad cuando un campo es incorrecto
function mostrarError(campo, mensaje) {
    limpiarError(campo); // Evita mensajes duplicados

    const aviso = document.createElement("span");
    aviso.className = "mensaje-error";
    aviso.id = `error_${campo.id}`; // id="email" -> "error_email"
    aviso.setAttribute("role", "alert");
    aviso.textContent = `Error: ${mensaje}`;
    campo.parentElement.appendChild(aviso);

    campo.classList.add("campo-error"); // Marcar el campo en rojo
    campo.setAttribute("aria-invalid", "true"); // Inidca a los lectores de pantalla que el dato es inválido
    // Si el campo ya tenia texto de ayuda (data-ayuda="id"), se leen los dos
    const ayuda = campo.dataset.ayuda; // Lee el atributo personalizado data-ayuda del HTML si existe
    campo.setAttribute("aria-describedby", ayuda ? `${ayuda} ${aviso.id}` : aviso.id); // Informa al lector de pantalla
}

// Deja el campo como estaba antes de mostrar el error
function limpiarError(campo) {
    const aviso = $(`error_${campo.id}`);
    if (aviso) {
        aviso.remove();
    }
    campo.classList.remove("campo-error");
    campo.removeAttribute("aria-invalid");     
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