document.addEventListener("DOMContentLoaded", function() {
    let cpInput = document.getElementById("cpFiltro");
    let tabla = document.getElementById("tablaResultados");

    // Evento al escribir en el input de código postal
    cpInput.addEventListener("input", function() {
        let codigoPostal = this.value.trim();
        if (codigoPostal.length >= 3) { // Buscar cuando tenga al menos 3 caracteres
            cargarMunicipios(codigoPostal);
        } else {
            tabla.innerHTML = ""; // Limpiar tabla si no hay suficientes caracteres
        }
    });

    // Función para cargar municipios en base al código postal
    function cargarMunicipios(codigoPostal) {
        fetch(`Ajax/municipiosA.php?action=municipios&cp=${codigoPostal}`)
            .then(response => response.json())
            .then(data => {
                tabla.innerHTML = "";
                data.forEach(row => {
                    let tr = document.createElement("tr");
                    tr.innerHTML = `
                        <td>${row.claveEdo}</td>
                        <td>${row.estado}</td>
                        <td>${row.ciudadMun}</td>
                        <td>${row.colBarrio}</td>
                        <td>${row.codigoPos}</td>
                    `;
                    tabla.appendChild(tr);
                });
            });
    }
});
