<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscador de Clientes</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#search').on('keyup', function(){
                var query = $(this).val();
                $.ajax({
                    url: "search.php",
                    type: "POST",
                    data: {'query': query},
                    success: function(data){
                        $('#result').html(data);
                    }
                });
            });

            $('#login-form').on('submit', function(e){
                e.preventDefault();
                var username = $('#username').val();
                var password = $('#password').val();
                $.ajax({
                    url: "login.php",
                    type: "POST",
                    data: {
                        'username': username,
                        'password': password
                    },
                    success: function(data){
                        if(data === 'success'){
                            window.location.href = 'dashboard.php';
                        } else {
                            alert('Usuario o contraseè´–a incorrectos.');
                        }
                    }
                });
            });

            $('.login-icon').on('click', function(){
                $('#login-modal').show();
            });

            $('.close').on('click', function(){
                $('#login-modal').hide();
            });

            $('#result').on('click', 'tr', function() {
                var nroCliente = $(this).find('td').eq(0).text();
                var obraSocial = $(this).find('td').eq(1).text();
                var cuit = $(this).find('td').eq(2).text();
                var modalidad = $(this).find('td').eq(3).text();
                var plazoCC = $(this).find('td').eq(4).text();
                var entregaLegajos = $(this).find('td').eq(5).text();
                var observaciones = $(this).find('td').eq(6).text();
                openPanel(nroCliente, obraSocial, cuit, modalidad, plazoCC, entregaLegajos, observaciones);
            });
        });

        function openPanel(nroCliente, obraSocial, cuit, modalidad, plazoCC, entregaLegajos, observaciones) {
            document.getElementById('panelNroCliente').innerText = nroCliente;
            document.getElementById('panelObraSocial').innerText = obraSocial;
            document.getElementById('panelCuit').innerText = cuit;
            document.getElementById('panelModalidad').innerText = modalidad;
            document.getElementById('panelPlazoCC').innerText = plazoCC;
            document.getElementById('panelEntregaLegajos').innerText = entregaLegajos;
            document.getElementById('panelObservaciones').innerText = observaciones;
            document.getElementById('sidePanel').style.width = '250px';
        }

        function closePanel() {
            document.getElementById('sidePanel').style.width = '0';
        }
    </script>
</head>
<body>
    <header>
        <h1>Buscador de Clientes</h1>
        <span class="login-icon">&#x1F464;</span>
    </header>
    <div class="container">
        <input type="text" id="search" placeholder="Buscar clientes...">
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
                    </tr>
                </thead>
                <tbody id="result">
                    <?php
                    $sql = "SELECT * FROM clientes";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td data-label='Nro Cliente'>{$row['nro_cliente']}</td>
                                    <td data-label='Obra Social'>{$row['obra_social']}</td>
                                    <td data-label='CUIT'>{$row['cuit']}</td>
                                    <td data-label='Modalidad'>{$row['modalidad']}</td>
                                    <td data-label='Plazo CC'>{$row['plazo_cc']}</td>
                                    <td data-label='Entrega Legajos'>{$row['entrega_legajos']}</td>
                                    <td data-label='Observaciones'>{$row['observaciones']}</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No se encontraron resultados</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- The Modal -->
    <div id="login-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form id="login-form">
                <input type="text" id="username" name="username" placeholder="Usuario" required>
                <input type="password" id="password" name="password" placeholder="Contraseè´–a" required>
                <button type="submit">Ingresar</button>
            </form>
        </div>
    </div>

    <!-- Side Panel -->
    <div id="sidePanel" class="side-panel">
        <a href="javascript:void(0)" class="close-btn" onclick="closePanel()">&times;</a>
        <div class="side-panel-content">
            <h2 id="panelNroCliente"></h2>
            <p><strong>Obra Social:</strong> <span id="panelObraSocial"></span></p>
            <p><strong>CUIT:</strong> <span id="panelCuit"></span></p>
            <p><strong>Modalidad:</strong> <span id="panelModalidad"></span></p>
            <p><strong>Plazo CC:</strong> <span id="panelPlazoCC"></span></p>
            <p><strong>Entrega Legajos:</strong> <span id="panelEntregaLegajos"></span></p>
            <p><strong>Observaciones:</strong> <span id="panelObservaciones"></span></p>
        </div>
    </div>
</body>
</html>
