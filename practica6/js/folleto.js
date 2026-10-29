// Tarifas (las mismas de la tabla de tarifas de la página)
const COSTE_ENVIO = 10;
const BLOQUE_PAGINAS = [ // Cada bloque cubre hasta "hasta" páginas
    { hasta: 4, precio:2 }, // Páginas 1 a 4: 2,00 € cada una
    { hasta: 10, precio: 1.8 }, // Páginas 5 a 10: 1,80 € cada una
    { hasta: Infinity, precio: 1.6 } // Páginas 11 en adelante: 1,60 € cada una
]
const PRECIO_FOTO_COLOR = 0.5;
const PRECIO_FOTO_ALTA_RESOLUCION = 0.2;
const LIMITE_RESOLUCION = 300;

// Datos de la tabla
const MAX_PAGINAS = 15;
const FOTOS_POR_COLUMNA = 3;
const COLUMNAS = [
    { etiqueta: "B/N 150-300 dpi", color: false, dpi:300 },
    { etiqueta: "B/N 450-900 dpi", color: false, dpi:450 },
    { etiqueta: "Color 150-300 dpi", color: true, dpi:300 },
    { etiqueta: "Color 450-900 dpi", color: true, dpi: 450 }
];

// Coste de las páginas aplicando el sistema de bloques
function costePaginas(paginas) {
    let coste = 0;
    let inicioBloque = 0;
    for (const bloque of BLOQUES_PAGINAS) {

        // 1. ¿Cuántas páginas de este tramo entran en el total?
        // Si tienes 6 páginas y el bloque llega hasta 4, Math.min(6,4) te devuelve 4 (no te puedes pasar del límite del bloque)
        // Si tienes 3 páginas y el bloque llega hasta 4, Math.min(3,4) te devuelve 3 (no puedes cobrar páginas que no existen)
        // - inicioBloque le resta las páginas que ya se cobraron en tramos anteriores
        const enEsteBloque = Math.min(paginas, bloque.hasta) - inicioBloque;
        
        // 2. Si ya no entran más páginas, dejamos de recorrer los bloques
        if (enEsteBloque <= 0) {
            break; // Ya no quedan páginas por cobrar
        }

        // 3. Acumulamos el coste de las páginas de este bloque
        coste += enEsteBloque * bloque.precio;

        // 4. Actualizo la marca de inicio para el siguiente bloque
        inicioBloque = bloque.hasta;

        return coste;
    }
}