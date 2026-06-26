<?php

  session_start();

  include('cnx/cnx.php');
  include('global/variables.php');
  include('global/auxiliares.php');

  // if(!isset($_SESSION["Id"])){
  //   header('Location: index.php');
  // }

?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo $favicon; ?>" type="image/png"/>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

    <!-- Íconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

    <!-- Select2 -->
    <link href="libs/select2/dist/css/select2.min.css" rel="stylesheet">

    <title><?php echo $nom_app; ?> | Administración de Conductores</title>

    <script type="text/javascript">
      let loaded_img_TC_1 = '';
      let loaded_img_TC_2 = '';
      let img_selected_TC_1 = '0';
      let img_selected_TC_2 = '0';

      let loaded_img_TP_1 = '';
      let loaded_img_TP_2 = '';
      let img_selected_TP_1 = '0';
      let img_selected_TP_2 = '0';
    </script>

    <style type="text/css">
      .circle {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: gold;
        position: absolute;
        text-align: center;
        line-height: 30px;
        font-size: 12px;
        font-weight: bold;
        color: black;
        border: 2px solid black;
        cursor: pointer;
        transition: transform 0.2s ease-in-out;
      }

      /* Efecto de latido */
      .circle.selected {
        border-color: blue !important;
        animation: latido 0.8s infinite alternate;
      }

      @keyframes latido {
        0% { transform: scale(1); }
        100% { transform: scale(1.2); }
      }

      #map {
          width: 100%;
          height: 100%;
          position: absolute;
          background: url('plano.jpg') no-repeat center;
          background-size: cover; /* Asegura que la imagen se vea completa sin cortes */
          background-position: center center;
          background-repeat: no-repeat;
          background-attachment: fixed;
          border: 1px solid #ccc;
      }
    </style>
  </head>

  <body class="bg-light" onload="f_SetDimension(); f_Init();" style="zoom: 100%;">
    <div class="container-fluid">
      <div class="row">
        <!-- Panel Izquierdo -->
        <div class="col-3">
          <h5>Lista de Lotes</h5>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>Lote</th>
              </tr>
            </thead>
            <tbody id="lotes-list">
              <!-- Ejemplo dinámico -->
              <tr data-lote-id="240001"><td>1</td><td>240001</td></tr>
              <tr data-lote-id="240002"><td>2</td><td>240002</td></tr>
              <tr data-lote-id="250010"><td>2</td><td>250010</td></tr>
            </tbody>
          </table>
        </div>

        <!-- Panel Central -->
        <div class="col-9">
          <h5>Gestión de Lotes</h5>
          <div id="map" style="position: relative; width: 100%; height: 115vh; background: url('images/mapa_lozas.png') no-repeat center; background-size: cover; border: 1px solid #ccc;">
              <!-- Círculos dinámicos aparecerán aquí -->
          </div>
        </div>
      </div>
    </div>


    <!-- Ventanas modales -->


    <!-- Referenciando a JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- jQuery UI (necesario para draggable) -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <!-- jQuery UI CSS (opcional, mejora la apariencia) -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

    <!-- Select2 -->
    <script src="libs/select2/dist/js/select2.full.min.js"></script>

    <!-- ECharts -->
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.3.3/dist/echarts.min.js"></script>

    <!-- Referenciando auxiliares -->
    <?php include('global/auxiliares_js.php'); ?>

    <!-- Funciones de Inicio -->
    <script type="text/javascript">
      function f_Init(){
        // Genera menús
          f_GetMenuPrincipal();

        // Titulo de Pantalla
          $("#nv_titulo").html('| Administración de Conductores');

        // Cargando listas generales

        // Carga el detalle de información
          f_LoadResultados();
      }
    </script>

    <!-- Funciones Principales -->
    <script type="text/javascript">
      $(document).ready(function () {
          let selectedLote = null;

          // Ajustar el tamaño del mapa dinámicamente
          function ajustarMapa() {
              let panelWidth = $(".col-3").outerWidth();
              let windowWidth = window.innerWidth - panelWidth;
              let windowHeight = window.innerHeight;
              // $("#map").css({ width: windowWidth + "px", height: windowHeight + "px" });

              // Reajustar la posición de los lotes para mantener proporción
              $(".circle").each(function () {
                  let loteId = $(this).data("lote-id");

                  // Obtener la última posición guardada en la BD
                  $.post("apis/backend.php", { accion: "get_OperacionesGestionLotes_GetPosition" }, function (data) {
                      let lote = data.find(l => l.cod_lote == loteId);
                      if (lote) {
                          let pos = getAbsolutePosition(lote.eje_x, lote.eje_y);
                          $(`[data-lote-id='${loteId}']`).css({ left: pos.x + "px", top: pos.y + "px" });
                      }
                  });
              });
          }

          ajustarMapa(); // Ajuste inicial
          $(window).resize(ajustarMapa); // Ajuste dinámico cuando cambia la orientación

          // Cargar posiciones iniciales desde la BD
          $.post("apis/backend.php", { accion: "get_OperacionesGestionLotes_GetPosition" }, function (data) {
              console.log("Cargando lotes:", data);
              data.forEach(lote => {
                  let pos = getAbsolutePosition(lote.eje_x, lote.eje_y);
                  let circle = $(`<div class='circle' data-lote-id='${lote.cod_lote}'>${lote.cod_lote}</div>`);
                  circle.css({ left: pos.x + "px", top: pos.y + "px" });
                  $("#map").append(circle);
              });
          });

          // Evento para seleccionar un lote
          $(document).on("click", ".circle", function () {
              if (selectedLote) {
                  selectedLote.removeClass("selected");
              }
              selectedLote = $(this);
              selectedLote.addClass("selected");
          });

          // Evento para mover el lote seleccionado
          $("#map").click(function (event) {
              if (selectedLote) {
                  let parentOffset = $(this).offset();
                  let circleWidth = selectedLote.outerWidth();
                  let circleHeight = selectedLote.outerHeight();

                  let newX = event.pageX - parentOffset.left - (circleWidth / 2);
                  let newY = event.pageY - parentOffset.top - (circleHeight / 2);

                  // Evita que se salga del mapa
                  newX = Math.max(0, Math.min(newX, $("#map").width() - circleWidth));
                  newY = Math.max(0, Math.min(newY, $("#map").height() - circleHeight));

                  selectedLote.animate({ left: newX + "px", top: newY + "px" }, 300, function () {
                      let pos = getRelativePosition(newX, newY);
                      savePosition(selectedLote.data("lote-id"), pos.x, pos.y);
                      selectedLote.removeClass("selected");
                      selectedLote = null;
                  });
              }
          });

          function getRelativePosition(x, y) {
              return { x: (x / $("#map").width()) * 100, y: (y / $("#map").height()) * 100 };
          }

          function getAbsolutePosition(xPercent, yPercent) {
              return { x: (xPercent / 100) * $("#map").width(), y: (yPercent / 100) * $("#map").height() };
          }

          // Guardar posición en la BD en %
          function savePosition(cod_lote, eje_x, eje_y) {
              $.post("apis/backend.php", {
                  accion: "grabar_OperacionesGestionLotes_SavePosition",
                  cod_lote: cod_lote,
                  eje_x: eje_x,
                  eje_y: eje_y
              }, function (data) {
                  if (data.estado == 1) {
                      console.log(`Posición guardada para ${cod_lote}: X=${eje_x}%, Y=${eje_y}%`);
                  }
              });
          }

          // Asignar un punto inicial solo si el lote no existe en BD
          $("#lotes-list tr").click(function () {
              const loteId = $(this).data("lote-id");

              // Verificar si ya existe en BD antes de crearlo
              $.post("apis/backend.php", { accion: "get_OperacionesGestionLotes_GetPosition" }, function (data) {
                  let lote = data.find(l => l.lote_id == loteId);

                  if (!lote) { // Si no existe, se crea en posición inicial
                      const circle = $(`<div class='circle' data-lote-id='${loteId}'>${loteId}</div>`);
                      circle.css({ left: "10%", top: "10%" });
                      $("#map").append(circle);
                      makeDraggable(circle);
                      savePosition(loteId, 10, 10); // Guardar posición inicial en BD
                  }
              });
          });
      });
    </script>

    <!-- Funciones Secundarias -->
    <script type="text/javascript">

    </script>

    <!-- Funciones de Grabación -->
    <script type="text/javascript">

    </script>

    <!-- Funcion Default -->
    <script type="text/javascript">
      
    </script>
  </body>
</html>