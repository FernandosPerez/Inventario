<?php
session_start();
$sesion     = explode("|", $_SESSION["usuario"]);
include("../include/conn.php");

$titulo     = $_POST['nfoto'];
$idarticulo = $_POST['idarticulo'];

// ── Extensión desde tipo MIME ───────────────────────────
$mimeMap = [
    'image/jpeg' => 'jpg',
    'image/jpg'  => 'jpg',
    'image/png'  => 'png',
    'image/gif'  => 'gif',
    'image/webp' => 'webp',
];
$extension = isset($mimeMap[$_FILES['archivo']['type']]) 
             ? $mimeMap[$_FILES['archivo']['type']] 
             : 'jpg';

$foto    = $titulo . "." . $extension;
$destino = "../img/categorias/inventario/" . $foto;

// ── Borrar foto anterior con diferente extensión ────────
foreach (['jpg','jpeg','png','gif','webp'] as $ext) {
    $vieja = "../img/categorias/inventario/" . $titulo . "." . $ext;
    if (file_exists($vieja) && $vieja != $destino) {
        unlink($vieja);
    }
}

// ── Subir directo con nombre correcto ───────────────────
if (move_uploaded_file($_FILES['archivo']['tmp_name'], $destino)) {

    $dbconn->prepare("UPDATE inventario SET foto=? WHERE id=?")
           ->execute([$foto, $idarticulo]);

    $dbconn->prepare("INSERT INTO inventario_movimientos 
        (articulo, usuario, tipo, campusIngreso, campusEgreso, cantidad, comentario, hora)
        VALUES (?,?,'4','0','0','0','ACTUALIZACIÓN DE FOTO',NOW())")
           ->execute([$idarticulo, $sesion[0]]);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subiendo imagen...</title>
</head>
<body>
<script>
    if (window.opener) {
        // Recarga la página padre con el parámetro de éxito
        const url = window.opener.location.href.split('?')[0];
        window.opener.location.href = url + '?foto_ok=<?= $titulo ?>';
    }
    window.close();

    setTimeout(function() {
        window.location.href = '../inventario.php?foto_ok=<?= $titulo ?>';
    }, 500);
</script>
</body>
</html>