<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: Login.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Carnes Ideal</title>
    <link rel="icon" type="image/png" href="images/circlecarnesideal.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <style>
      /* Mensaje de guardado exitoso */
      #msg-guardado {
        display: none;
        position: fixed;
        top: 80px;
        left: 50%;
        transform: translateX(-50%);
        background: #27ae60;
        color: #fff;
        padding: 14px 32px;
        border-radius: 8px;
        font-size: 1.1rem;
        font-family: 'Poppins', sans-serif;
        z-index: 9999;
        box-shadow: 0 4px 16px rgba(39,174,96,0.25);
        animation: fadeInMsg 0.3s ease;
      }
      @keyframes fadeInMsg {
        from { opacity:0; transform: translateX(-50%) translateY(-10px); }
        to   { opacity:1; transform: translateX(-50%) translateY(0); }
      }
    </style>
</head>
<body>

  <!-- ✅ Mensaje de guardado exitoso -->
  <div id="msg-guardado">✅ Registro guardado correctamente.</div>

  <div class="top-bar">
    <div class="header-bar">
      <div class="header-left">
        <img src="images/ae3dc9c4-38d3-4482-9305-1d0b122a5aa3.png" alt="Logo" class="logo-header">
      </div>
      <div class="header-center">
        <h1 class="titulo-principal">Registro de Recepción de Productos</h1>
      </div>
      <div class="user-menu">
        <span class="user-icon" id="userIcon">
          <i class="fa fa-user"></i>
        </span>
        <div class="user-dropdown" id="userDropdown" style="display:none;">
          <span id="userName" style="font-weight:bold;"><?php echo htmlspecialchars($_SESSION['nombre_completo']); ?></span>
          <button id="logoutBtn">Cerrar sesión</button>
        </div>
      </div>
    </div>
  </div>

  <div class="contenedor">
    <!-- ✅ id cambiado, sin action — lo maneja fetch en index.js -->
    <form id="formulario-recepcion" method="POST" style="display:contents;">

      <!-- Campo oculto para enviar productos como JSON -->
      <input type="hidden" id="productos_json" name="productos_json" value="[]">

      <!-- Caja izquierda -->
      <div class="box izquierda">
          <label for="fecha">Fecha de recepción:</label>
          <input type="date" name="fecha" id="fecha" required>
          <label for="proveedor">Proveedor:</label>
          <select name="proveedor" id="proveedor" required style="width:100%;">
            <option value="">-- Selecciona proveedor --</option>
            <option value="CARNICOS LOPEZ HARO">CARNICOS LOPEZ HARO</option>
            <option value="CARBENZERS">CARBENZERS</option>
            <option value="CARNICERIA LARO">CARNICERIA LARO</option>
            <option value="GUSI">GUSI</option>
            <option value="MAG">MAG</option>
            <option value="HSM">HSM</option>
            <option value="CAFISON">CAFISON</option>
            <option value="PRADERAS HUASTECAS">PRADERAS HUASTECAS</option>
            <option value="ARCOSA">ARCOSA</option>
            <option value="SUPERKARNE">SUPERKARNE</option>
            <option value="ELISEO CECINA">ELISEO CECINA</option>
            <option value="GASTROSOPHIA">GASTROSOPHIA</option>
            <option value="LUIS RIAÑO">LUIS RIAÑO</option>
            <option value="QUEVEDO BEEF">QUEVEDO BEEF</option>
            <option value="DIPORSA">DIPORSA</option>
            <option value="CARROLL">CARROLL</option>
            <option value="GRANJERO FELIZ">GRANJERO FELIZ</option>
            <option value="IMPOCARNES">IMPOCARNES</option>
            <option value="KOWI">KOWI</option>
            <option value="OBRADOR MIGUELITO">OBRADOR MIGUELITO</option>
            <option value="ALPRO">ALPRO</option>
            <option value="NAREECI">NAREECI</option>
            <option value="FRITURAS GABY">FRITURAS GABY</option>
            <option value="CRUJIENTE TRADICIÓN MEXICANA">CRUJIENTE TRADICIÓN MEXICANA</option>
            <option value="EMPACADORA BEAR">EMPACADORA BEAR</option>
            <option value="AMERICA LUIS">AMERICA LUIS</option>
            <option value="EL PASTORCITO">EL PASTORCITO</option>
            <option value="ALIFRESCOS">ALIFRESCOS</option>
            <option value="EXCELENCIA">EXCELENCIA</option>
            <option value="OTRO">OTRO</option>
          </select>

          <hr style="margin:18px 0;border:none;border-top:2px dashed #e74c3c;">
          <h3 style="color:#e74c3c;text-align:center;margin-bottom:12px;">Registro de productos recibidos</h3>

          <div class="input-group">
            <label for="producto">Producto*</label>
            <input type="text" id="producto" placeholder="Nombre del producto">
          </div>
          <div class="input-group">
            <label for="cantidad">Cantidad*</label>
            <input type="number" id="cantidad" min="1" placeholder="Cantidad recibida">
          </div>
          <div class="input-group">
            <label>Unidad *</label><br>
            <div id="unidadCheckboxes" style="display:flex;gap:18px;justify-content:center;width:100%;">
              <label><input type="checkbox" value="Caja" class="unidadCheck"> Caja</label>
              <label><input type="checkbox" value="Kg" class="unidadCheck"> Kg</label>
              <label><input type="checkbox" value="Pieza" class="unidadCheck"> Pieza</label>
            </div>
          </div>
          <div class="input-group">
            <label for="precio_unidad">Precio unidad *</label>
            <input type="number" id="precio_unidad" min="0" step="0.01" placeholder="$ por unidad">
          </div>

          <button type="button" id="agregarProducto" style="background:#e74c3c;color:#fff;margin-bottom:10px;">Agregar</button>

          <div id="panelProductos" style="display:none;margin-bottom:20px;">
            <table style="width:100%;border-collapse:collapse;box-shadow:0 2px 8px #e74c3c22;">
              <thead style="background:#e74c3c;color:#fff;">
                <tr>
                  <th>Producto</th><th>Cantidad</th><th>Unidad</th><th>Precio por unidad</th>
                </tr>
              </thead>
              <tbody id="tablaProductos"></tbody>
            </table>
            <div style="display:flex;justify-content:space-between;gap:10px;margin-top:10px;">
              <button type="button" id="eliminarSeleccionado" style="background:#ffd6d6;color:#e74c3c;border:1.5px solid #e74c3c;">Eliminar seleccionado</button>
              <button type="button" id="borrarProductos" style="background:#ffd6d6;color:#e74c3c;border:1.5px solid #e74c3c;">Borrar todo</button>
            </div>
          </div>
      </div>

      <!-- Caja central -->
      <div class="box central">
        <div class="sensorial-box" style="background:rgba(255,255,255,0.98);border-radius:1px;box-shadow:0 4px 10px #e74c3c22;padding:18px;margin-bottom:20px;">
          <h3 style="color:#e74c3c;text-align:center;margin-bottom:14px;">CARACTERÍSTICAS SENSORIALES</h3>
          <table style="width:100%;border-collapse:collapse;">
            <thead>
              <tr style="background:#ffeaea;color:#e74c3c;">
                <th>Parámetro</th><th>Sí</th><th>No</th><th>Observaciones</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Olor</td>
                <td><input type="radio" name="sensorial_olor" value="Si"></td>
                <td><input type="radio" name="sensorial_olor" value="No"></td>
                <td><input type="text" name="obs_olor" placeholder="Observaciones" style="width:98%;"></td>
              </tr>
              <tr>
                <td>Color</td>
                <td><input type="radio" name="sensorial_color" value="Si"></td>
                <td><input type="radio" name="sensorial_color" value="No"></td>
                <td><input type="text" name="obs_color" placeholder="Observaciones" style="width:98%;"></td>
              </tr>
              <tr>
                <td>Textura</td>
                <td><input type="radio" name="sensorial_textura" value="Si"></td>
                <td><input type="radio" name="sensorial_textura" value="No"></td>
                <td><input type="text" name="obs_textura" placeholder="Observaciones" style="width:98%;"></td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="temp-box" style="background:rgba(255,255,255,0.98);border-radius:16px;box-shadow:0 4px 18px #e74c3c22;padding:18px;">
          <h3 style="color:#e74c3c;text-align:center;margin-bottom:14px;">Temperatura del producto</h3>
          <div style="display:flex;flex-direction:column;align-items:center;gap:14px;">
            <label for="temp_producto" style="font-weight:600;color:#e74c3c;">Selecciona la temperatura (°C):</label>
            <input type="range" id="temp_producto" name="temp_producto" min="-20" max="20" value="0" step="0.1" style="width:90%;">
            <span id="temp_valor" style="font-size:1.2rem;color:#df3b2c;font-weight:600;">0°C</span>
          </div>
        </div>

        <div class="empaque-box" style="background:rgba(255,255,255,0.98);border-radius:1px;box-shadow:0 4px 18px #e74c3c22;padding:18px;margin-bottom:10px;">
          <h3 style="color:#e74c3c;text-align:center;margin-bottom:14px;">Empaques</h3>
          <div style="display:flex;align-items:center;gap:22px;justify-content:center;">
            <label>Empaque limpio:</label>
            <label><input type="radio" name="empaque_limpio" value="Si"> Sí</label>
            <label><input type="radio" name="empaque_limpio" value="No"> No</label>
          </div>
        </div>
      </div>

      <!-- Caja derecha -->
      <div class="box derecha">
        <div class="evidencia-box" style="background:rgba(255,255,255,0.98);border-radius:16px;box-shadow:0 4px 18px #ff190022;padding:18px;margin-bottom:20px;">
          <h3 style="color:#e74c3c;text-align:center;margin-bottom:14px;">Subir evidencia</h3>
          <input type="file" id="evidencia_pdf" name="evidencia_pdf" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv,.zip,.rar" style="width:100%;margin-bottom:10px;">
          <button type="button" id="borrar_pdf" style="background:#ffd6d6;color:#e74c3c;border-color:#e74c3c;margin-bottom:10px;">Borrar PDF</button>
          <input type="file" id="evidencia_foto" name="evidencia_foto[]" accept="image/*" multiple style="width:100%;margin-bottom:10px;">
          <button type="button" id="borrar_foto" style="background:#ffd6d6;color:#e74c3c;border-color:#e74c3c;">Borrar fotos</button>
        </div>

        <div class="verifico-box" style="background:rgba(255,255,255,0.98);border-radius:16px;box-shadow:0 4px 18px #e74c3c22;padding:18px;margin-bottom:12px;">
          <h3 style="color:#e74c3c;text-align:center;margin-bottom:14px;">Número de documento:</h3>
          <input type="text" name="num_remision" id="num_remision" required placeholder="Ingrese el número de remisión" style="margin-bottom:12px;width:410px;">
          <h3 style="color:#e74c3c;text-align:center;margin-bottom:14px;">Verifico</h3>
          <select name="verifico" id="verifico" required style="width:100%;">
            <option value="">-- Seleccione --</option>
            <option value="Librado">Librado</option>
            <option value="Omar">Omar</option>
            <option value="Ricardo">Ricardo</option>
            <option value="Otro">Otro</option>
          </select>
        </div>

        <div style="display:flex;gap:10px;justify-content:flex-end;">
          <button type="submit">Guardar</button>
          <button type="reset">Limpiar</button>
        </div>
      </div>

    </form>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', function() {

    // Menú usuario
    const userIcon = document.getElementById('userIcon');
    const userDropdown = document.getElementById('userDropdown');
    userIcon.addEventListener('click', function() {
      userDropdown.style.display = userDropdown.style.display === 'none' ? 'block' : 'none';
    });

    // Cerrar sesión
    document.getElementById('logoutBtn').addEventListener('click', function() {
      fetch('logout.php').then(() => window.location.href = 'Login.html');
    });

    // Productos
    let productos = [];
    let seleccionado = null;
    const agregarBtn     = document.getElementById('agregarProducto');
    const panelProductos = document.getElementById('panelProductos');
    const tablaProductos = document.getElementById('tablaProductos');
    const eliminarBtn    = document.getElementById('eliminarSeleccionado');
    const unidadChecks   = document.querySelectorAll('.unidadCheck');

    unidadChecks.forEach(check => {
      check.addEventListener('change', function() {
        if(this.checked) unidadChecks.forEach(c => { if(c !== this) c.checked = false; });
      });
    });

    agregarBtn.addEventListener('click', function(e) {
      e.preventDefault();
      const producto = document.getElementById('producto').value.trim();
      const cantidad = document.getElementById('cantidad').value.trim();
      let unidad = '';
      unidadChecks.forEach(c => { if(c.checked) unidad = c.value; });
      const precio = document.getElementById('precio_unidad').value.trim();
      if(producto && cantidad && unidad && precio) {
        productos.push({producto, cantidad, unidad, precio});
        mostrarProductos();
        document.getElementById('producto').value = '';
        document.getElementById('cantidad').value = '';
        unidadChecks.forEach(c => c.checked = false);
        document.getElementById('precio_unidad').value = '';
      } else {
        alert('Completa todos los campos del producto: nombre, cantidad, unidad y precio.');
      }
    });

    document.getElementById('borrarProductos').addEventListener('click', function() {
      productos = []; seleccionado = null; mostrarProductos();
    });

    eliminarBtn.addEventListener('click', function() {
      if(seleccionado !== null) {
        productos.splice(seleccionado, 1); seleccionado = null; mostrarProductos();
      }
    });

    function mostrarProductos() {
      tablaProductos.innerHTML = '';
      if(productos.length > 0) {
        panelProductos.style.display = 'block';
        productos.forEach((p, i) => {
          const row = document.createElement('tr');
          row.innerHTML = `<td>${p.producto}</td><td>${p.cantidad}</td><td>${p.unidad}</td><td>$${parseFloat(p.precio).toFixed(2)}</td>`;
          row.style.cursor = 'pointer';
          row.onclick = function() {
            seleccionado = i;
            Array.from(tablaProductos.children).forEach(r => r.style.background = '');
            row.style.background = '#ffeaea';
            eliminarBtn.disabled = false;
          };
          tablaProductos.appendChild(row);
        });
        eliminarBtn.disabled = seleccionado === null;
      } else {
        panelProductos.style.display = 'none';
        seleccionado = null;
        eliminarBtn.disabled = true;
      }
      document.getElementById('productos_json').value = JSON.stringify(productos);
    }

    // Temperatura
    const tempSlider = document.getElementById('temp_producto');
    const tempValor  = document.getElementById('temp_valor');
    tempSlider.addEventListener('input', function() {
      tempValor.textContent = tempSlider.value + '°C';
    });

    // Borrar archivos
    document.getElementById('borrar_pdf').addEventListener('click', function() {
      document.getElementById('evidencia_pdf').value = '';
    });
    document.getElementById('borrar_foto').addEventListener('click', function() {
      document.getElementById('evidencia_foto').value = '';
    });

    // ✅ GUARDAR con fetch — no redirige
    document.getElementById('formulario-recepcion').addEventListener('submit', function(e) {
      e.preventDefault();
      if(productos.length === 0) {
        alert('⚠️ Debes agregar al menos un producto antes de guardar.');
        return;
      }
      // Actualizar JSON justo antes de enviar
      document.getElementById('productos_json').value = JSON.stringify(productos);
      const formData = new FormData(this);
      fetch('guardar_excel.php', { method: 'POST', body: formData })
      .then(res => res.text())
      .then(data => {
        if(data.includes('\u2705')) {
          // Mostrar mensaje 3 segundos y ocultar
          const msg = document.getElementById('msg-guardado');
          msg.style.display = 'block';
          setTimeout(() => { msg.style.display = 'none'; }, 3000);
          // Limpiar campos
          document.getElementById('formulario-recepcion').reset();
          productos = []; seleccionado = null; mostrarProductos();
          tempSlider.value = 0; tempValor.textContent = '0°C';
          $('#proveedor').val(null).trigger('change');
          $('#verifico').val(null).trigger('change');
        } else {
          alert('❌ Error: ' + data);
        }
      })
      .catch(() => alert('❌ Error de conexión'));
    });

    // Limpiar también oculta el mensaje
    document.querySelector('button[type="reset"]').addEventListener('click', function() {
      setTimeout(() => {
        document.getElementById('msg-guardado').style.display = 'none';
        productos = []; seleccionado = null; mostrarProductos();
        tempSlider.value = 0; tempValor.textContent = '0°C';
        $('#proveedor').val(null).trigger('change');
        $('#verifico').val(null).trigger('change');
      }, 10);
    });

  });
  </script>
  <script>
  $(document).ready(function() {
    const $proveedor = $('#proveedor');
    $proveedor.select2({ placeholder: "-- Selecciona proveedor --" });

    const $verifico = $('#verifico');
    $verifico.select2({ placeholder: "-- Seleccione --", minimumResultsForSearch: Infinity });

    function setupTache(select) {
      const $container = select.next('.select2-container');
      $container.find('.select2-selection__arrow').append('<span class="select2-selection__icon">&#x25BC;</span>');
      function actualizarIcono() {
        const $icon = $container.find('.select2-selection__icon');
        $icon.html(select.val() ? '&#x2716;' : '&#x25BC;');
      }
      select.on('change', actualizarIcono);
      actualizarIcono();
      $container.on('click', '.select2-selection__icon', function(e) {
        e.stopPropagation();
        if(select.val()) { select.val(null).trigger('change'); }
        else { select.select2('open'); }
      });
      select.on('select2:open', function() {
        $(".select2-search__field").attr("placeholder", "Buscar...");
      });
    }
    setupTache($proveedor);
    setupTache($verifico);
  });
  </script>
</body>
</html>
