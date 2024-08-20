// Función para cargar el archivo JSON
async function loadJSON() {
    const response = await fetch('datos.json');
    const data = await response.json();
    return data;
}

// Función para agregar filas a la tabla
function addRowsToTable(data) {
    const tableBody = document.getElementById('result');
    tableBody.innerHTML = ''; // Limpiar las filas actuales

    data.forEach(item => {
        const row = document.createElement('tr');

        row.addEventListener('click', () => {
            openPanel(item);
        });

        for (let key in item) {
            const cell = document.createElement('td');
            cell.setAttribute('data-label', key);
            cell.textContent = item[key];
            row.appendChild(cell);
        }

        tableBody.appendChild(row);
    });
}

// Función para abrir el panel lateral con los detalles del cliente
function openPanel(item) {
    document.getElementById('panelNroCliente').textContent = `Cliente Nro: ${item.NroCliente}`;
    document.getElementById('panelObraSocial').textContent = item.ObraSocial;
    document.getElementById('panelCuit').textContent = item.CUIT;
    document.getElementById('panelModalidad').textContent = item.Modalidad;
    document.getElementById('panelPlazoCC').textContent = item.PlazoCC;
    document.getElementById('panelEntregaLegajos').textContent = item.EntregaLegajos;
    document.getElementById('panelObservaciones').textContent = item.Observaciones;
    document.getElementById('sidePanel').style.width = '250px';
}

// Función para cerrar el panel lateral
function closePanel() {
    document.getElementById('sidePanel').style.width = '0';
}

// Función para filtrar la tabla según el texto ingresado en el input de búsqueda
function filterTable() {
    const searchInput = document.getElementById('search').value.toLowerCase();
    const rows = document.querySelectorAll('#result tr');

    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        let match = false;
        cells.forEach(cell => {
            if (cell.textContent.toLowerCase().includes(searchInput)) {
                match = true;
            }
        });

        if (match) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Función para limpiar el input cuando se presiona "Esc"
function clearSearchInput(event) {
    if (event.key === 'Escape') {
        document.getElementById('search').value = '';
        filterTable(); // Mostrar todas las filas nuevamente
    }
}

// Agregar eventos para la búsqueda dinámica y limpiar input
document.getElementById('search').addEventListener('input', filterTable);
document.getElementById('search').addEventListener('keyup', clearSearchInput);

// Cargar los datos y agregarlos a la tabla
loadJSON().then(data => {
    addRowsToTable(data);
});
