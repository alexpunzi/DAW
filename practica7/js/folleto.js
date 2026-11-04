
 // Tarifas (las mismas de la tabla de tarifas de la página)
const COSTE_ENVIO = 10;

const BLOQUE_PAGINAS = [
    { hasta: 4, precio: 2 },       // Páginas 1 a 4: 2,00 € cada una
    { hasta: 10, precio: 1.8 },   // Páginas 5 a 10: 1,80 € cada una
    { hasta: Infinity, precio: 1.6 } // Páginas 11 en adelante: 1,60 € cada una
];

const PRECIO_FOTO_COLOR = 0.5;
const PRECIO_FOTO_ALTA_RESOLUCION = 0.2;
const LIMITE_RESOLUCION = 300;

// Datos de la tabla
const MAX_PAGINAS = 15;
const FOTOS_POR_PAGINA = 3;

const COLUMNAS = [
    { etiqueta: "B/N 150-300 dpi", color: false, dpi: 300 },
    { etiqueta: "B/N 450-900 dpi", color: false, dpi: 450 },
    { etiqueta: "Color 150-300 dpi", color: true, dpi: 300 },
    { etiqueta: "Color 450-900 dpi", color: true, dpi: 450 }
];

// Coste de las páginas aplicando el sistema de bloques
function costePaginas(paginas) {
    let coste = 0;
    let inicioBloque = 0;

    for (const bloque of BLOQUE_PAGINAS) {
        // Calculamos cuántas páginas entran en este bloque
        const enEsteBloque =
            Math.min(paginas, bloque.hasta) - inicioBloque;

        // Si ya no quedan páginas por cobrar, terminamos
        if (enEsteBloque <= 0) {
            break;
        }

        // Acumulamos el coste de las páginas de este bloque
        coste += enEsteBloque * bloque.precio;

        // Actualizamos el inicio para el siguiente bloque
        inicioBloque = bloque.hasta;
    }

    return coste;
}

// Calcula el coste total del folleto
function costeFolleto(paginas, fotos, color, dpi) {
    let coste = COSTE_ENVIO + costePaginas(paginas);

    if (color) {
        coste += fotos * PRECIO_FOTO_COLOR;
    }

    if (dpi > LIMITE_RESOLUCION) {
        coste += fotos * PRECIO_FOTO_ALTA_RESOLUCION;
    }

    return coste;
}

// Formatea un importe en euros
function formatoEuros(valor) {
    return new Intl.NumberFormat("es-ES", {
        style: "currency",
        currency: "EUR"
    }).format(valor);
}

// Crea un <th> o <td> con su texto usando textContent
function crearCelda(etiqueta, texto, scope) {
    const celda = document.createElement(etiqueta);

    celda.textContent = texto;

    if (scope) {
        celda.setAttribute("scope", scope);
    }

    return celda;
}

// Crea la tabla de costes
function crearTablaCostes() {
    const tabla = document.createElement("table");
    tabla.id = "tabla_costes";

    const titulo = document.createElement("caption");
    titulo.textContent =
        "Posibles costes de un folleto de publicidad impreso";

    tabla.appendChild(titulo);

    // Cabecera
    const cabecera = document.createElement("thead");
    const filaCabecera = document.createElement("tr");

    filaCabecera.appendChild(
        crearCelda("th", "Páginas", "col")
    );

    filaCabecera.appendChild(
        crearCelda("th", "Fotos", "col")
    );

    for (const columna of COLUMNAS) {
        filaCabecera.appendChild(
            crearCelda("th", columna.etiqueta, "col")
        );
    }

    cabecera.appendChild(filaCabecera);
    tabla.appendChild(cabecera);

    // Cuerpo: una fila por número de páginas
    const cuerpo = document.createElement("tbody");

    for (let paginas = 1; paginas <= MAX_PAGINAS; paginas++) {
        const fotos = paginas * FOTOS_POR_PAGINA;
        const fila = document.createElement("tr");

        fila.appendChild(
            crearCelda("th", paginas, "row")
        );

        fila.appendChild(
            crearCelda("td", fotos)
        );

        for (const columna of COLUMNAS) {
            const coste = costeFolleto(
                paginas,
                fotos,
                columna.color,
                columna.dpi
            );

            fila.appendChild(
                crearCelda("td", formatoEuros(coste))
            );
        }

        cuerpo.appendChild(fila);
    }

    tabla.appendChild(cuerpo);

    return tabla;
}

// Muestra u oculta la tabla de costes
function alternarTablaCostes() {
    let tabla = $("tabla_costes");

    if (!tabla) {
        tabla = crearTablaCostes();
        $("costes").appendChild(tabla);

        tabla.hidden = true;
    }

    tabla.hidden = !tabla.hidden;

    const boton = $("boton_costes");

    boton.textContent = tabla.hidden
        ? "Mostrar tabla de costes"
        : "Ocultar tabla de costes";

    boton.setAttribute(
        "aria-expanded",
        String(!tabla.hidden)
    );
}

// Configura el botón cuando se carga el documento
document.addEventListener("DOMContentLoaded", () => {
    const boton = $("boton_costes");

    if (boton) {
        boton.addEventListener("click", alternarTablaCostes);
    }
});
