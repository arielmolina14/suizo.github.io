<?php
include 'config.php';

if (isset($_POST['query'])) {
    $query = $_POST['query'];
    $sql = "SELECT * FROM clientes WHERE nro_cliente LIKE '%$query%' OR obra_social LIKE '%$query%' OR cuit LIKE '%$query%' OR modalidad LIKE '%$query%' OR plazo_cc LIKE '%$query%' OR entrega_legajos LIKE '%$query%' OR observaciones LIKE '%$query%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['nro_cliente']}</td>
                    <td>{$row['obra_social']}</td>
                    <td>{$row['cuit']}</td>
                    <td>{$row['modalidad']}</td>
                    <td>{$row['plazo_cc']}</td>
                    <td>{$row['entrega_legajos']}</td>
                    <td>{$row['observaciones']}</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No se encontraron resultados</td></tr>";
    }
}
?>
