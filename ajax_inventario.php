<?php
// 1. Iniciamos el buffer de salida inmediatamente para atrapar cualquier HTML o espacio en blanco accidental
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("include/perfil.php"); // Si este archivo genera HTML, quedará atrapado en el buffer
include("include/conn.php");

$sesion = explode("|", $_SESSION["usuario"]);
$campus = (int)$sesion[2];

// Parámetros de DataTables
$draw = isset($_POST['draw']) ? (int)$_POST['draw'] : 1;
$start = isset($_POST['start']) ? (int)$_POST['start'] : 0;
$length = isset($_POST['length']) ? (int)$_POST['length'] : 10;
$searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';

// Filtros personalizados
$filtro_almacen = isset($_POST["almacen"]) ? (int)$_POST["almacen"] : 0;
$filtro_plantel = isset($_POST["plantel"]) ? (int)$_POST["plantel"] : 0;
$filtro_existencias = isset($_POST["existencias"]) ? (int)$_POST["existencias"] : 0;
$filtro_foto = isset($_POST["foto"]) ? (int)$_POST["foto"] : 0;

if ($campus != 8 && $filtro_plantel == 0) {
    $filtro_plantel = $campus;
}

$esAdmin = ($campus == 8 && $filtro_plantel == 0);
$params = [];
$whereSql = "WHERE i.status = 1";
$havingSql = "";

// 1. Aplicar filtros WHERE
if ($filtro_almacen != 0) {
    $whereSql .= " AND i.area = ?";
    $params[] = $filtro_almacen;
}
if ($filtro_foto == 1) {
    $whereSql .= " AND (i.foto IS NOT NULL AND i.foto != '')";
}
if ($filtro_foto == 2) {
    $whereSql .= " AND (i.foto IS NULL OR i.foto = '')";
}
if ($searchValue != '') {
    $whereSql .= " AND (i.codigo LIKE ? OR i.nombre LIKE ?)";
    $params[] = "%$searchValue%";
    $params[] = "%$searchValue%";
}

// 2. Construir la consulta principal
if (!$esAdmin) {
    // Consulta UN plantel
    $sqlBase = "SELECT i.id, i.area, i.codigo, i.nombre, i.medida, i.foto,
                COALESCE(s.stock, 0) AS stock,
                COALESCE(t.total, 0) AS total
                FROM inventario i
                LEFT JOIN inventario_stock s ON s.articulo_id = i.id AND s.plantel_id = " . (int)$filtro_plantel . "
                LEFT JOIN (
                    SELECT articulo_id, SUM(stock) AS total FROM inventario_stock GROUP BY articulo_id
                ) t ON t.articulo_id = i.id
                $whereSql";
                
    if ($filtro_existencias == 1) $sqlBase .= " AND COALESCE(s.stock, 0) > 0";
    if ($filtro_existencias == 2) $sqlBase .= " AND COALESCE(s.stock, 0) = 0";

} else {
    // Consulta TODOS los planteles (Admin)
    $sqlBase = "SELECT i.id, i.area, i.codigo, i.nombre, i.medida, i.foto,
                COALESCE(SUM(CASE WHEN s.plantel_id = 1  THEN s.stock END), 0) AS `SJR`,
                COALESCE(SUM(CASE WHEN s.plantel_id = 13 THEN s.stock END), 0) AS `SJR5`,
                COALESCE(SUM(CASE WHEN s.plantel_id = 2  THEN s.stock END), 0) AS `ACULCO`,
                COALESCE(SUM(CASE WHEN s.plantel_id = 3  THEN s.stock END), 0) AS `TECAMAC`,
                COALESCE(SUM(CASE WHEN s.plantel_id = 4  THEN s.stock END), 0) AS `ATLACOMULCO`,
                COALESCE(SUM(CASE WHEN s.plantel_id = 5  THEN s.stock END), 0) AS `TEPEJI`,
                COALESCE(SUM(CASE WHEN s.plantel_id = 6  THEN s.stock END), 0) AS `NOPALA`,
                COALESCE(SUM(CASE WHEN s.plantel_id = 7  THEN s.stock END), 0) AS `EN LINEA`,
                COALESCE(SUM(CASE WHEN s.plantel_id = 8  THEN s.stock END), 0) AS `CORPORATIVO`,
                COALESCE(SUM(s.stock), 0) AS total
                FROM inventario i
                LEFT JOIN inventario_stock s ON s.articulo_id = i.id
                $whereSql 
                GROUP BY i.id, i.area, i.codigo, i.nombre, i.medida, i.foto";

    if ($filtro_existencias == 1) $havingSql = " HAVING COALESCE(SUM(s.stock), 0) > 0";
    if ($filtro_existencias == 2) $havingSql = " HAVING COALESCE(SUM(s.stock), 0) = 0";
    
    $sqlBase .= $havingSql;
}

// 3. Contar registros totales (para DataTables)
$countSql = "SELECT COUNT(*) FROM ($sqlBase) AS conteo";
$stmtCount = $dbconn->prepare($countSql);
$stmtCount->execute($params);
$totalRecords = $stmtCount->fetchColumn();

// 4. Aplicar Ordenamiento y Paginación
$orderSql = " ORDER BY i.area, SUBSTRING_INDEX(i.codigo, '-', 1), CAST(SUBSTRING_INDEX(i.codigo, '-', -1) AS UNSIGNED)";
$limitSql = "";
if ($length != -1) {
    $limitSql = " LIMIT " . (int)$start . ", " . (int)$length;
}

// 5. Ejecutar consulta final
$finalSql = $sqlBase . $orderSql . $limitSql;
$stmtData = $dbconn->prepare($finalSql);
$stmtData->execute($params);
$data = $stmtData->fetchAll(PDO::FETCH_ASSOC);

// 6. Limpiamos por completo el buffer de salida (adiós al HTML intruso del Topbar y modales)
if (ob_get_length()) {
    ob_clean();
}

// 7. Declaramos la cabecera JSON correcta
header('Content-Type: application/json; charset=utf-8');

// 8. Retornamos únicamente el JSON limpio
echo json_encode([
    "draw" => (int)$draw,
    "recordsTotal" => (int)$totalRecords,
    "recordsFiltered" => (int)$totalRecords,
    "data" => $data
], JSON_UNESCAPED_UNICODE);
exit;
?>