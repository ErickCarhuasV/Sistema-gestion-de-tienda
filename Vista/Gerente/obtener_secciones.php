<?php
// obtener_secciones.php
include 'bd.php'; // Conexión a la base de datos

if (isset($_POST['ID_AREA'])) {
    $area_id = $_POST['ID_AREA'];

    $query = "SELECT ID_SECCION, DESCRIPCION_SECCION FROM SECCION WHERE ID_AREA = :ID_AREA";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':area_id', $area_id, PDO::PARAM_INT);
    $stmt->execute();
    
    $secciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($secciones);
}
?>
