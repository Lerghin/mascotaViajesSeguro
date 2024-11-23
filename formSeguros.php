<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Dinámico de Registro de Seguros, Coberturas y Pagos</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
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
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-top: 5px;
            outline: none;
        }

        input:focus {
            border-color: #3f51b5;
        }

        .form-group input[type="submit"] {
            background-color: #3f51b5;
            color: white;
            cursor: pointer;
            border: none;
            margin-top: 20px;
        }

        .form-group input[type="submit"]:hover {
            background-color: #303f9f;
        }

        .toggle-button {
            background-color: #4caf50;
            color: white;
            padding: 10px;
            cursor: pointer;
            border: none;
            margin-top: 10px;
            text-align: center;
            display: block;
            width: 100%;
            border-radius: 5px;
        }

        .toggle-button:hover {
            background-color: #45a049;
        }

        .form-container .hidden {
            display: none;
        }
    </style>
   <script>
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



    // Guardar datos del seguro
    function saveSeguroData(event) {
        event.preventDefault();

        var idMascota = localStorage.getItem('id_mascota');
        var fechaInicio = document.getElementById('fecha_inicio').value;
        var fechaFin = document.getElementById('fecha_fin').value;
       

        if (!idMascota || !fechaInicio || !fechaFin) {
            alert('Todos los campos son obligatorios.');
            return;
        }

        var formData = new FormData();
        formData.append('id_mascota', idMascota);
        formData.append('fecha_inicio', fechaInicio);
        formData.append('fecha_fin', fechaFin);

        fetch('procesarSeguro.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.id_seguro) {
                localStorage.setItem('id_seguro', data.id_seguro);
                const idAlmacenado = localStorage.getItem("id_seguro");
        console.log(idAlmacenado)
        const id_seguro = document.getElementById("id_seguro");
         id_seguro.value= idAlmacenado;
                alert(data.message);
                toggleForm('formCoberturas');
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un error al registrar el seguro.');
        });
    }

    function saveCoberturaData(event) {
    event.preventDefault(); // Prevenir el comportamiento por defecto del formulario

    const idSeguro = localStorage.getItem("id_seguro");
    console.log(idSeguro);
    const idCobertura = document.getElementById('id_cobertura').value;

    // Validación de campos
    if (!idSeguro || !idCobertura) {
        alert('Todos los campos son obligatorios.');
        return;
    }

    const formData = new FormData();
    formData.append('id_seguro', idSeguro);
    formData.append('id_cobertura', idCobertura);

    fetch('procesarCobertura.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) // Parsear la respuesta como JSON
    .then(data => {
        if (data.success) {
            alert(data.message); // Mostrar mensaje de éxito
            if (data.redirect) {
                window.location.href = data.redirect; // Redirigir si hay una URL
            }
        } else {
            alert(data.message); // Mostrar mensaje de error
        }
    })
    .catch(error => {
        console.error('Error al registrar la cobertura:', error);
        alert('Hubo un error en el servidor.');
    });
}



  
</script>

</head>
<body>

    <div class="form-container">
        <h2>Formulario de Seguro, Coberturas y Pagos</h2>

        <button class="toggle-button" onclick="toggleForm('formSeguro')">Registrar Seguro</button>
        <button class="toggle-button" onclick="toggleForm('formCoberturas')">Registrar Cobertura</button>
    

        <!-- Formulario de registro de seguro -->
        <form id="formSeguro" class="hidden" onsubmit="saveSeguroData(event)">
            <div class="form-group">
                <input type="text" id="id_mascota" style="display:none" name="id_mascota" value="" readonly>
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
                <input type="submit" value="Siguiente">
            </div>
        </form>

        <!-- Formulario de registro de coberturas -->
        <!-- Formulario de registro de coberturas -->
    <form id="formCoberturas" class="hidden" onsubmit="saveCoberturaData(event)">
         <div class="form-group">
            <label for="id_seguro">ID Seguro</label>
        <input type="text" id="id_seguro" name="id_seguro" readonly>
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
                <input type="submit" value="Registrar">
            </div>
        </form>
      
    </div>

</body>
</html>
