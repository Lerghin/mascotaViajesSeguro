<?php
// Recibir el id_seguro desde la URL o asignar null si no se pasa
if (isset($_GET['id_seguro'])) {
    $id_seguro = $_GET['id_seguro'];
} else {
    $id_seguro = null;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Dinámico de Registro de Seguros, Coberturas y Pagos</title>
    <style>
        /* Estilos globales */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7f6;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
            font-size: 24px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input, select {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-top: 5px;
            outline: none;
        }

        input:focus, select:focus {
            border-color: #3f51b5;
            box-shadow: 0 0 5px rgba(63, 81, 181, 0.5);
        }

        .form-group input[type="submit"] {
            background-color: #3f51b5;
            color: white;
            cursor: pointer;
            border: none;
            margin-top: 20px;
            padding: 12px;
            font-size: 16px;
            border-radius: 5px;
        }

        .form-group input[type="submit"]:hover {
            background-color: #303f9f;
        }

        .toggle-button {
            background-color: #4caf50;
            color: white;
            padding: 12px;
            cursor: pointer;
            border: none;
            margin-top: 10px;
            text-align: center;
            display: block;
            width: 100%;
            border-radius: 5px;
            font-size: 16px;
        }

        .toggle-button:hover {
            background-color: #45a049;
        }

        .form-container .hidden {
            display: none;
        }

        /* Media Queries para Responsividad */
        @media (max-width: 768px) {
            .form-container {
                padding: 15px;
            }

            h2 {
                font-size: 22px;
            }

            .form-group input, .form-group select {
                font-size: 14px;
                padding: 10px;
            }

            .form-group input[type="submit"], .toggle-button {
                font-size: 14px;
                padding: 10px;
            }
        }
    </style>
    <script>
    // Pasamos el valor de PHP a JavaScript
    var idSeguro = <?php echo json_encode($id_seguro); ?>;

    // Función para alternar la visibilidad entre los formularios
    function toggleForm(formId) {
        var form = document.getElementById(formId);
        var allForms = document.querySelectorAll('.form-container form');

        // Ocultar todos los formularios
        allForms.forEach(function (form) {
            form.classList.add('hidden');
        });

        // Mostrar el formulario seleccionado
        form.classList.remove('hidden');
    }

    // Cargar datos de seguro para edición
    function loadSeguroData() {
        if (idSeguro) {
            fetch(`getSegurosData.php?id_seguro=${idSeguro}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('id_seguro').value = data.seguro.id_seguro;
                        document.getElementById('fecha_inicio').value = data.seguro.fecha_inicio;
                        document.getElementById('fecha_fin').value = data.seguro.fecha_fin;
                        toggleForm('formSeguro');
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al cargar los datos del seguro.');
                });
        } else {
            alert('No se ha encontrado el seguro.');
        }
    }

    // Cargar datos de cobertura para edición
    function loadCoberturaData() {
    // Obtener el id_seguro desde el campo de entrada o desde la URL
    var idSeguro = <?php echo json_encode($id_seguro); ?>;

    if (idSeguro) {
        fetch(`getCoberturaData.php?id_seguro=${idSeguro}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Obtener el campo select y limpiar sus opciones
                    var selectCobertura = document.getElementById('id_cobertura');
                    var seguros= document.getElementById('id_seguro').value;
                    console.log(seguros);
                    // Asegurarse de que el formulario de coberturas sea visible
                    toggleForm('formCoberturas');
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cargar las coberturas.');
            });
    } else {
        alert('No se ha proporcionado el id_seguro.');
    }
}


    // Guardar datos editados del seguro
    function editSeguroData(event) {
        event.preventDefault();

        var idSeguro = document.getElementById('id_seguro').value;

        var fechaInicio = document.getElementById('fecha_inicio').value;
        var fechaFin = document.getElementById('fecha_fin').value;

        if (!idSeguro || !fechaInicio || !fechaFin) {
            alert('Todos los campos son obligatorios.');
            return;
        }

        var formData = new FormData();
        formData.append('id_seguro', idSeguro);
        formData.append('fecha_inicio', fechaInicio);
        formData.append('fecha_fin', fechaFin);

        fetch('editarSeguro.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                toggleForm('formSeguro');
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un error al editar el seguro.');
        });
    }

    // Guardar datos editados de cobertura
    function editCoberturaData(event) {
    event.preventDefault();

    var idCobertura = document.getElementById('id_cobertura').value;
  
    var idSeguro = document.getElementById('id_seguro').value;  // Asegúrate de obtener también el id_seguro
    console.log(idSeguro)
    if (!idCobertura  || !idSeguro) {
        alert('Debe seleccionar una cobertura, proporcionar un nombre y seleccionar un seguro.');
        return;
    }

    var formData = new FormData();
    formData.append('id_cobertura', idCobertura);
  
    formData.append('id_seguro', idSeguro);  // Enviar también el id_seguro

    fetch('editarCobertura.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            toggleForm('formCoberturas');  // Alterna el formulario (esto depende de tu lógica)
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Hubo un error al editar la cobertura.');
    });
}


    // Llamar a la función de cargar los datos de seguro si ya tenemos el id_seguro
    if (idSeguro) {
        loadSeguroData();
    }
    </script>
</head>
<body>

    <div class="form-container">
        <h2>Formulario de Seguro</h2>

        <button class="toggle-button" onclick="loadSeguroData()">Editar Seguro</button>
        <button class="toggle-button" onclick="loadCoberturaData(2)">Editar Cobertura</button>

        <!-- Formulario de registro de seguro -->
        <form id="formSeguro" class="hidden" onsubmit="editSeguroData(event)">
            <div class="form-group">
                <input type="text" id="id_seguro" name="id_seguro" readonly>
            </div>
            <div class="form-group">
                <label for="fecha_inicio">Fecha de Inicio</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" required>
            </div>
            <div class="form-group">
                <label for="fecha_fin">Fecha de Fin</label>
                <input type="date" id="fecha_fin" name="fecha_fin" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Guardar Cambios">
            </div>
        </form>

        <!-- Formulario de registro de coberturas -->
        <form id="formCoberturas" class="hidden" onsubmit="editCoberturaData(event)">
    <div class="form-group">
        <input type="text" class="hidden" id="id_seguro" name="id_seguro" readonly>  <!-- Este campo debe contener el id_seguro -->
    </div>
    <div class="form-group">
        <label for="id_cobertura">Cobertura</label>
        <select id="id_cobertura" name="id_cobertura" required>
            <option value="">Seleccione una cobertura</option>
            <option value="1">Básico 200$</option>
            <option value="2">Medio 500$</option>
            <option value="3">Estándar 800$</option>
            <option value="4">Premium 1000$</option>
        </select>
    </div>
  
    <div class="form-group">
        <input type="submit" value="Guardar Cambios">
    </div>
</form>



    </div>
</body>
</html>
