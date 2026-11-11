<?php
require_once 'includes/bootstrap.php';

require_once "includes/lib/funciones.php";

$title          = "Mis Mensajes";
requiereUsuario(); // parte privada
$esPartePrivada = true;

require_once "includes/layout/cabecera.php";
require_once "includes/layout/inicio.php";
?>

<section>
    <h2>Bandeja de Mensajes</h2>

    <p>
        Historial de todos los mensajes enviados y recibidos.
    </p>
</section>

<section>
    <h3>ENVIADOS</h3>

    <ul>
        <li>
            <p>
                <strong>Para (Receptor):</strong>
                Luis Pérez
            </p>

            <p>
                <strong>Fecha:</strong>
                29/09/2026
            </p>

            <p>
                <strong>Tipo de mensaje:</strong>
                Comunicar una oferta
            </p>

            <p>
                <strong>Texto:</strong>
                Hola Luis, te ofrezco 550€/mes por el estudio luminoso. Si estás de acuerdo, podemos firmar esta misma semana.
            </p>
        </li>
    </ul>
</section>

<hr>

<section>
    <h3>RECIBIDOS</h3>

    <ul>
        <li>
            <p>
                <strong>De (Emisor):</strong>
                Carlos Martínez
            </p>

            <p>
                <strong>Fecha:</strong>
                28/09/2026
            </p>

            <p>
                <strong>Tipo de mensaje:</strong>
                Solicitar una cita
            </p>

            <p>
                <strong>Texto:</strong>
                Hola Juana, vi tu anuncio del piso céntrico con terraza en Jávea. ¿Sería posible ir a visitarlo este jueves por la tarde?
            </p>
        </li>

        <li>
            <p>
                <strong>De (Emisor):</strong>
                Ana Gómez
            </p>

            <p>
                <strong>Fecha:</strong>
                25/09/2026
            </p>

            <p>
                <strong>Tipo de mensaje:</strong>
                Más información
            </p>

            <p>
                <strong>Texto:</strong>
                Buenos días, ¿el precio del chalet independiente con piscina es negociable?
            </p>
        </li>
    </ul>
</section>

<?php
require_once "includes/layout/pie.php";
?>