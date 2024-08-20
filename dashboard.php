<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit;
}

// Configurar el tiempo de expiración de la sesión
$timeout_duration = 3600; // 1 hora

// Verificar la última actividad de la sesión
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time();

include 'config.php';

// Procesar la eliminación de registros
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM clientes WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Registro eliminado exitosamente";
    } else {
        echo "Error eliminando el registro: " . $conn->error;
    }
}

// Procesar la adición de nuevos registros
if (isset($_POST['add'])) {
    $nro_cliente = $_POST['nro_cliente'];
    $obra_social = $_POST['obra_social'];
    $cuit = $_POST['cuit'];
    $modalidad = $_POST['modalidad'];
    $plazo_cc = $_POST['plazo_cc'];
    $entrega_legajos = $_POST['entrega_legajos'];
    $observaciones = $_POST['observaciones'];

    $sql = "INSERT INTO clientes (nro_cliente, obra_social, cuit, modalidad, plazo_cc, entrega_legajos, observaciones) VALUES ('$nro_cliente', '$obra_social', '$cuit', '$modalidad', '$plazo_cc', '$entrega_legajos', '$observaciones')";
    if ($conn->query($sql) === TRUE) {
        echo "Nuevo registro añadido exitosamente";
    } else {
        echo "Error añadiendo el registro: " . $conn->error;
    }
}

// Procesar la actualización de registros
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nro_cliente = $_POST['nro_cliente'];
    $obra_social = $_POST['obra_social'];
    $cuit = $_POST['cuit'];
    $modalidad = $_POST['modalidad'];
    $plazo_cc = $_POST['plazo_cc'];
    $entrega_legajos = $_POST['entrega_legajos'];
    $observaciones = $_POST['observaciones'];

    $sql = "UPDATE clientes SET nro_cliente='$nro_cliente', obra_social='$obra_social', cuit='$cuit', modalidad='$modalidad', plazo_cc='$plazo_cc', entrega_legajos='$entrega_legajos', observaciones='$observaciones' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Registro actualizado exitosamente";
    } else {
        echo "Error actualizando el registro: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            // Mostrar formulario de agregar
            $('#add-btn').on('click', function(){
                $('#add-modal').show();
            });

            $('.close').on('click', function(){
                $('.modal').hide();
            });

            $(window).on('click', function(event) {
                if ($(event.target).is('.modal')) {
                    $('.modal').hide();
                }
            });

            // Confirmar eliminación
            $('.delete-btn').on('click', function(){
                var result = confirm('¿Estás seguro de que deseas eliminar este registro? Una vez eliminado, no se puede recuperar.');
                return result;
            });

            // Mostrar formulario de actualización
            $('.update-btn').on('click', function(){
                var id = $(this).data('id');
                $('#update-modal-' + id).show();
            });

            $('.logout-icon').on('click', function(){
                window.location.href = 'logout.php';
            });
        });
    </script>
</head>
<body>
    <header>
        <h1>Dashboard</h1>
        <span class="logout-icon">&#x1F511;</span>
    </header>
    <div class="container">
        <button id="add-btn" class="btn btn-primary">Agregar Nuevo Cliente</button>
        <div id="add-modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <form method="post">
                    <input type="text" name="nro_cliente" placeholder="Nro Cliente" required>
                    <input type="text" name="obra_social" placeholder="Obra Social" required>
                    <input type="text" name="cuit" placeholder="CUIT" required>
                    <input type="text" name="modalidad" placeholder="Modalidad" required>
                    <input type="text" name="plazo_cc" placeholder="Plazo CC" required>
                    <input type="text" name="entrega_legajos" placeholder="Entrega Legajos" required>
                    <input type="text" name="observaciones" placeholder="Observaciones">
                    <button type="submit" name="add" class="btn btn-primary">Agregar</button>
                </form>
            </div>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nro Cliente</th>
                        <th>Obra Social</th>
                        <th>CUIT</th>
                        <th>Modalidad</th>
                        <th>Plazo CC</th>
                        <th>Entrega Legajos</th>
                        <th>Observaciones</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM clientes";
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
                                    <td>
                                        <button class='btn btn-secondary update-btn' data-id='{$row['id']}'>Modificar</button>
                                        <div id='update-modal-{$row['id']}' class='modal'>
                                            <div class='modal-content'>
                                                <span class='close'>&times;</span>
                                                <form method='post'>
                                                    <input type='hidden' name='id' value='{$row['id']}'>
                                                    <input type='text' name='nro_cliente' value='{$row['nro_cliente']}' required>
                                                    <input type='text' name='obra_social' value='{$row['obra_social']}' required>
                                                    <input type='text' name='cuit' value='{$row['cuit']}' required>
                                                    <input type='text' name='modalidad' value='{$row['modalidad']}' required>
                                                    <input type='text' name='plazo_cc' value='{$row['plazo_cc']}' required>
                                                    <input type='text' name='entrega_legajos' value='{$row['entrega_legajos']}' required>
                                                    <input type='text' name='observaciones' value='{$row['observaciones']}'>
                                                    <button type='submit' name='update' class='btn btn-primary'>Guardar</button>
                                                </form>
                                            </div>
                                        </div>
                                        <form method='post' style='display:inline;'>
                                            <input type='hidden' name='id' value='{$row['id']}'>
                                            <button type='submit' name='delete' class='btn btn-danger delete-btn'>Eliminar</button>
                                        </form>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No se encontraron resultados</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
