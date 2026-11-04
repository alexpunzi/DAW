<?php
$title = "Solicitud de folleto enviada";
require_once "includes/funciones.php";
require_once "includes/cabecera.php"; 
require_once "includes/inicio.php";

$logueado = true; require_once "includes/acceso.php";


//datos recibidos 
$nombre   = post("nombre");     
$apellidos = post("apellidos");
$correo   = post("correo");     
$telefono  = post("telefono");
$anuncio  = post("anuncio");    
$texto     = post("texto_adicional");
$color    = post("color_portada");          
$dpi      = (int) post("resolucion");        
$fecha    = post("fecha_recepcion");
$tipo     = post("tipo_impresion");          
$mostrarPrecio = (post("imprimir_precio") !== ""); //solo llega si esta marcado 


if ($copias < 1) { $copias = 1; }
//datos fixticios del folleto 
$paginas = 5;  
$fotos = $paginas * 3;
$unidad  = costeFolleto($paginas, $fotos, $tipo === "color", $dpi);
$total   = $unidad * $copias;
?>

<main>
  <h1>Solicitud recibida</h1>
  <dl>
    <dt>Solicitante</dt><dd><?php echo e("$nombre $apellidos"); ?></dd>
    <dt>Contacto</dt><dd><?php echo e("$correo · $telefono"); ?></dd>
    <dt>Dirección</dt>
    <dd><?php echo e(post("calle") . " " . post("numero") . ", " . post("piso") . post("puerta")
         . " · " . post("cp") . " " . post("localidad") . " (" . post("provincia") . ", " . post("pais") . ")"); ?></dd>
    <dt>Anuncio</dt><dd><?php echo e($anuncio); ?></dd>
    <dt>Copias</dt><dd><?php echo $copias; ?></dd>
    <dt>Resolución</dt><dd><?php echo $dpi; ?> dpi</dd>
    <dt>Tipo de impresión</dt><dd><?php echo e($tipo); ?></dd>
    <dt>Fecha de recepción</dt><dd><?php echo e($fecha); ?></dd>
<?php if ($mostrarPrecio) { ?>
    <dt>Coste total</dt><dd><?php echo euros($total); ?> (<?php echo euros($unidad); ?> por copia)</dd>
<?php } ?>
  </dl>
</main>

<?php require_once "includes/pie.php"; ?>