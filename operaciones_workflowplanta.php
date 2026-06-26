<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');
	include('global/auxiliares.php');

	if(!isset($_SESSION["Id"])){
    header('Location: index.php');
  }

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

		<title><?php echo $nom_app; ?> | Workflow de Planta</title>

		<script type="text/javascript">
			let itemlote_Selected = 0;
      let codlote_Selected = 0;
      let paso_Selected = 0;
      let idpaso_Selected = 0;

      let itemproceso_Selected = 0;
      let codproceso_Selected = 0;
		</script>

		<style>

			.step-indicator {
			  display: flex;
			  align-items: center;
			  justify-content: space-between;
			}

			.step {
			  text-align: center;
			  cursor: pointer;
			  font-size: 12px;
			}

			.step-circle {
			  width: 30px;
			  height: 30px;
			  line-height: 30px;
			  border-radius: 50%;
			  background-color: #ddd;
			  display: inline-block;
			  font-weight: bold;
			}

			.step.active .step-circle {
			  background-color: #007bff;
			  color: white;
			}

			.step-content {
			  display: none;
			}

			.step-content.active {
			  display: block;
			}

	    .overlay-image {
	      position: absolute;
	      top: 0;
	      left: 0;
	      cursor: grab;
	      touch-action: none; /* Previene problemas con el scroll en móviles */
	      z-index: 20; /* Asegura que el overlay esté sobre la imagen del mapa */
	    }

	    .step-content {
			  position: relative; /* Necesario para posicionar elementos hijos */
			}

			.overlay-image-transp {
			  position: absolute; /* Para mover dentro del contenedor */
				/*top: 0;*/
				/*left: 0;*/
				/*width: 32px;*/
				/*height: 32px;*/
			  cursor: grab;
			  z-index: 10; /* Asegura que el overlay esté sobre la imagen del mapa */
			}
		</style>
	</head>

	<body class="bg-light" onload="f_SetDimension(); f_Init();">
		<div class="container-fluid">
			<div class="row">
				<!-- Llamando a Navbar -->
				<?php echo $navbar_maintop; ?>

				<!-- Menús principales -->
				<div id="div_menu1" class="col-md-1 col-sm-1 col-xs-1" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; background-color: #DEDEDE;">
				</div>

				<div class="col-md-11 col-sm-11 col-xs-11" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding-top: 10px; padding-left: 35px;">
					<div class="d-flex row">
						<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff;">
							<div class="row" style="padding: 20px;">
								<div class="col-md-12 col-sm-12 col-xs-12">
									<h6>WorkFlow</h6>
								</div>

								<div id="div_plantas" class="col-md-2 col-sm-2 col-xs-12" style="padding: 0px; padding-bottom: 5px;">
									<div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
										<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
											<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
												<div class="d-flex">
													<h6>Lista de Lotes</h6>

													<div id="wt_lotes" class="" style="font-size: 10px; text-align: center; display: none; padding-top: 5px;">
														<img src="<?php echo $img_waiting ?>" style="width: 20px;">
														<label style="font-style: italic;"> Cargando datos...</label>
													</div>
												</div>
											</div>
										</div>

										<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-y: auto; width: 100%; height: 750px">
											<table class="table table-bordered table-hover">
							        	<thead>
							        		<tr style="font-size: 10px;">
							        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 40px; border-top-left-radius: 15px;">
							        				N°
							        			</th>

							        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 90px;border-top-right-radius: 15px;">
							        				Lote
							        			</th>
							        		</tr>

							        	</thead>

							        	<tbody id="tbl_lotes">
							        		
							        	</tbody>
							        </table>
										</div>
									</div>
								</div>

								<div id="div_detalle" class="col-md-10 col-sm-10 col-xs-12" style="padding: 0px; padding-left: 5px;">
									<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-left: 0px; margin-right: 0px;  height: 792px">
										<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
											<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
												<div class="col-md-12 col-sm-12 col-xs-12">
													<div class="d-flex">
														<h6>Procesos para: </h6>
														<h6 id="lbl_titulolote" style="margin-left: 5px; color: #337ab7;"></h6>

														<div id="wt_procesos" class="" style="font-size: 10px; text-align: center; display: none; padding-top: 5px;">
															<img src="<?php echo $img_waiting ?>" style="width: 20px;">
															<label style="font-style: italic;"> Cargando...</label>
														</div>
													</div>
												</div>

												<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: auto; width: 100%;">
													<div id="div_procesos" class="d-flex flex-column mb-3">
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>

			<!-- Menú flotante -->
			<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel" style="background-color: #DEDEDE; width: 20%;">
			  <div class="offcanvas-header" style="background-color: #ffffff;">
			    <h5 id="sb1_titulo" class="offcanvas-title" id="offcanvasExampleLabel"></h5>
			    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
			  </div>

			  <div id="div_submenu1" class="offcanvas-body" style="color: #212529;">
			  </div>
			</div>
		</div>

		<!-- Ventanas modales Agregar Equipos-->
		<div class="modal fade" id="modal_addequipos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addequiposLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_addequiposLabel">Nueva Configuración</h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">

						<div class="row" style="padding: 5px;">
								<div class="col-md-12 col-sm-12 colx-xs-12">
									<div id="div_equipos"></div>
						 	 </div>

						</div>

		      </div>

		      <div class="modal-footer">
		      	<div id="wt_grabartolva" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
							<img src="<?php echo $img_waiting ?>" style="width: 20px;">
							<label style="font-style: italic;"> Grabando datos...</label>
						</div>

		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		        <button type="button" class="btn btn-success" onclick="f_AsignarTarea();"> <i class="bi bi-chevron-double-right"></i>Asignar Tarea(s)</button>

		      </div>
		    </div>
		  </div>
		</div>

		<!-- Ventanas modales Ver Equipos-->
		<div class="modal fade" id="modal_verequipos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_verequiposLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_verequiposLabel">Nueva Configuración</h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">

						<div class="row" style="padding: 5px;">
								<div class="col-md-12 col-sm-12 colx-xs-12">
									<div id="div_verequipos"></div>
						 	 </div>

						</div>

		      </div>

		      <div class="modal-footer">
		      	<div id="wt_grabartolva" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
							<img src="<?php echo $img_waiting ?>" style="width: 20px;">
							<label style="font-style: italic;"> Grabando datos...</label>
						</div>
						<input id="input_hd_cod_lote_config" hidden>
		        <button type="button" class="btn btn-primary" onclick="f_AdminConfiguracionPorLote()">Configurar</button>
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		      </div>
		    </div>
		  </div>
		</div>

		<!-- Referenciando a JQuery -->
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
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
					$("#nv_titulo").html('| Workflow de Planta');

				// Cargando listas generales
					f_LoadLotes();
			}
		</script>

		<!-- Funciones Principales -->
		<script type="text/javascript">

			function f_LoadingLotes(_is_show){
				if (_is_show == 1){
					$("#wt_lotes").show();
				}
				else{
					$("#wt_lotes").hide();
				}
			}

			function f_LoadLotes(){
        var _html = '';
        var d = 1;

				// Cargando Lista de Racks
	        $("#tbl_lotes").html('');

	        f_LoadingLotes(1);

	        $.post( "apis/backend.php", { accion: "get_WorkflowPlanta_ListaLotes"}, 
	          function( data ) {
	            if(data.estado == 1){
	            	$("#tbl_lotes").html(data.html);

	            	itemlote_Selected = 1;
								codlote_Selected = data.cod_lote;

								f_LoadItemLote(itemlote_Selected, codlote_Selected);
	            }

	            f_LoadingLotes(0);

          }, "json");
    	}
  	  
      function f_LoadItemLote(_item, _cod_lote){
        var _html = '';
      	paso_Selected = null;
      	idpaso_Selected = null;
      	$('#input_hd_cod_lote_config').val('');
  	    var d = 1;

        // Pinta selección
          f_ColorSelected_Lote(_item);

        // Seteando título
        	$("#lbl_titulolote").html(_cod_lote);

        // Cargando datos
          f_LoadingProcesos(1);

	        $.post( "apis/backend.php", { accion: "get_WorkflowPlanta_ListaProcesos_Operaciones"}, 
	          function( data ) {

						_html += '<div class="col-md-12 col-sm-12 colx-xs-12">';
	       		_html += ' <div class="row">';

	       		_html += '  <div class="step-indicator">';

	            if(data.estado == 1){
  							$.each( data.res, function( key, val ) {

		            	_html += '   <div class="step" id="id_proceso_'+val.Id+'" data-target="paso_'+val.Id+'" data-id="'+val.Id+'"> ';
				       		_html += '      <div class="step-circle">'+(key+1)+'</div>';
				       		_html += '      <p>'+val.descripcion+'</p>';
				       		_html += '   </div>';

								});
	            }

	 		  		_html += '  </div>';

	 		  		  if(data.estado == 1){
  							$.each( data.res, function( key, val ) {

							  	if(val.Id == 10){
							  		imagen_lote = "square_orange.png";
							  	}else if(val.Id == 12){
										imagen_lote = "ruma.png";
							  	}else{
							  		var imagen_lote = "circle_gold.png";
							  	}

		            	_html += '   <div id="paso_'+val.Id+'" class="step-content">';
				       		_html += '     <img src="images/mapa_lozas.png" style="width: 1310px !important; height:640px !important">';
				       		_html += '     <img src="images/'+imagen_lote+'" class="overlay-image" id="overlay_'+val.Id+'" style="width: 40px; height: 40px; z-index:100" ondblclick="f_AdminConfiguracion()">';

				       		_html += '   <div id="paso_lotes_transp_'+val.Id+'">';
				       		_html += '   </div>';

				       		_html += '   </div>';

								});
	            }

	 		  		_html += ' </div>';
	       		_html += '</div>';

	          $("#div_procesos").html(_html);

            // Evento para cambiar de contenido
					  $(".step").on("click", function () {
						  const target = $(this).data("target"); // Obtener el id del contenido asociado
						  const id = $(this).data("id"); // Obtener el id del contenido asociado
						  paso_Selected = target;
						  idpaso_Selected = id;
 
						  // Cambiar clase activa en los steps
							  $(".step").removeClass("active");
							  $(this).addClass("active");

						  // Mostrar solo el contenido correspondiente
							  $(".step-content").removeClass("active");
							  $(`#${target}`).addClass("active");

						  // Inicializar el arrastre del overlay para el nuevo paso
						  	inicializarArrastreLote();

 							// Mostrar la posición del lote por proceso
						  	mostrarPosicionLotePorProceso(1);

						  // Mostrar la ultima posición del lote guardada
				 	 			mostrarUltimaPosicionLote();
						});

          }, "json");

         	f_LoadingProcesos(0);

        itemlote_Selected = _item;
        codlote_Selected = _cod_lote;
      }

      function f_LoadEquipos(_cod_lote = ''){
        var _html = '';	
        var cod_lote = 0;

        if(_cod_lote !== ''){
        	cod_lote = _cod_lote;
        }else{
        	cod_lote = codlote_Selected
        }

				// Cargando Lista de Racks
	        $("#div_equipos").html('');

	        // $.post( "apis/backend.php", { accion: "get_WorkflowPlanta_ListaEquipos", cod_lote: codlote_Selected, id_proceso: idpaso_Selected }, 
	        //   function( data ) {
	        //     if(data.estado == 1){
	        //     	$("#div_equipos").html(data.html);
	        //     }
          // }, "json");

	        $.post( "apis/backend.php", { accion: "get_WorkflowPlanta_ListaEquipos", cod_lote: cod_lote, id_proceso: idpaso_Selected }, 
	          function( data ) {
	            if(data.estado == 1){
	            	$("#div_equipos").html(data.html);
	            }
          }, "json");
    	}

      function f_LoadVerEquipos(_cod_lote){
        var _html = '';
				// Cargando Lista de Racks
	        $("#div_verequipos").html('');

	        $.post( "apis/backend.php", { accion: "get_WorkflowPlanta_VerListaEquipos", cod_lote: _cod_lote, id_proceso: idpaso_Selected }, 
	          function( data ) {
	            if(data.estado == 1){
	            	$("#div_verequipos").html(data.html);
	            }
          }, "json");
    	}


      function f_ColorSelected_Lote(_item){
        var i = 1;
        // Recorre los Tr de la tabla y los limpia
        $("#tbl_lotes tr").each(function () {
          $("#tr_lote_" + i).css('background-color', '');
          i += 1;
        });
        // Seteando item seleccionado
          $("#tr_lote_" + _item).css('background-color', '#FFF587');
          $("#lbl_titulolote").html($("#td_codlote_" + _item).html().trim());
      }

    	function f_LoadingProcesos(_is_show){
				if (_is_show == 1){
					$("#wt_procesos").show();
				}
				else{
					$("#wt_procesos").hide();
				}
			}

   
		</script>

		<!-- Funciones de Menús -->
		<script type="text/javascript">
			function f_SetDimension(){
				if (screen.width < 500){
					$("#offcanvasExample").css('width', '60%');
				}
			}

		</script>
		
	  <script>
    	function inicializarArrastreLote() {
			  const overlay = document.getElementById("overlay_" + idpaso_Selected);
			  const mapContainer = document.querySelector("#" + paso_Selected + " img"); // Imagen del mapa

			  if (overlay && mapContainer) {
			    let isDragging = false;
			    let offsetX = 0,
			      offsetY = 0;

			    const startDrag = (e) => {
			      isDragging = true;
			      const event = e.type === "mousedown" ? e : e.touches[0];
			      offsetX = event.clientX - overlay.getBoundingClientRect().left;
			      offsetY = event.clientY - overlay.getBoundingClientRect().top;
			      overlay.style.cursor = "grabbing";
			    };

			    const drag = (e) => {
			      if (!isDragging) return;

			      const event = e.type === "mousemove" ? e : e.touches[0];
			      const mapRect = mapContainer.getBoundingClientRect(); // Dimensiones del mapa

			      // Calcular nueva posición
				      let newX = event.clientX - mapRect.left - offsetX;
				      let newY = event.clientY - mapRect.top - offsetY;

			      // Limitar movimiento dentro del contenedor del mapa
				      newX = Math.max(0, Math.min(newX, mapRect.width - overlay.offsetWidth));
				      newY = Math.max(0, Math.min(newY, mapRect.height - overlay.offsetHeight));

			      // Aplicar nueva posición al overlay
				      overlay.style.left = `${newX}px`;
				      overlay.style.top = `${newY}px`;
			    };

			    const stopDrag = () => {
			      if (isDragging) {
			        isDragging = false;
			        // Guardar posición al soltar el elemento
				        const posX = parseFloat(overlay.style.left);
				        const posY = parseFloat(overlay.style.top);
			        	overlay.style.cursor = "grab";

			        // Llamar a la función para guardar la posición (solo una vez al soltar)
			        	f_GrabarPosicionLote(codlote_Selected, idpaso_Selected, posX, posY);
			      }
			    };

			    // Verificar si los eventos ya han sido registrados
				    if (!overlay.hasEvents) {
				      overlay.hasEvents = true; // Marcar que los eventos ya están registrados

				      // Registrar eventos para arrastrar
					      overlay.addEventListener("mousedown", startDrag);
					      document.addEventListener("mousemove", drag);
					      document.addEventListener("mouseup", stopDrag);

				      // Registrar eventos táctiles
					      overlay.addEventListener("touchstart", startDrag, { passive: true });
					      document.addEventListener("touchmove", drag, { passive: true });
					      document.addEventListener("touchend", stopDrag);
				    }
			  }
			}

			function f_GrabarPosicionLote(_cod_lote,_id_proceso, _x_posicion, _y_posicion ){
          $.post( "apis/backend.php", { accion: "grabar_WorkflowPosicionLote", cod_lote: _cod_lote, id_proceso: _id_proceso, x_posicion: _x_posicion, y_posicion: _y_posicion }, 
            function( data ) {
              if(data.estado == 1){
						  	// inicializarArrastreLote();
                // mostrarUltimaPosicionLote();
              }
              else{
                alert("Ocurrió un error al momento de grabar la posición del lote.");
              }
            }, "json");
      };

      function f_AsignarTarea(){
      	var cod_lote = 0;
      	var _cod_lote = $('#input_hd_cod_lote_config').val();

      	if(_cod_lote.length > 0){
      		cod_lote = _cod_lote;
      	}else{
					cod_lote = codlote_Selected;
      	}

      	var checkedItems = $('input[type="checkbox"]:checked');
      	checkedItems.each(function() {

      		var id_equipo = $(this).data('id');

    		 	/*$.post( "apis/backend.php", { accion: "update_LoteConfiguracion_Asignar", cod_lote: codlote_Selected, id_proceso: idpaso_Selected, id_equipo: id_equipo}, */
		 		 	$.post( "apis/backend.php", { accion: "update_LoteConfiguracion_Asignar", cod_lote: cod_lote, id_proceso: idpaso_Selected, id_equipo: id_equipo}, 
          function( data ) {	
            if(data.estado == 1){
            	f_cerrarModal('modal_addequipos');
            }
            else{
              alert("Ocurrió un error al momento de ejecutar la tarea.");
            }

          }, "json");
				});
      
      };

      function mostrarUltimaPosicionLote() {
			  var overlay = document.getElementById("overlay_" + idpaso_Selected);

			  $.post(
			    "apis/backend.php",
			    { accion: "get_WorkflowPlanta_ListarUltimaPosicion_Lote", cod_lote: codlote_Selected },
			    function (data) {
			      if (data.estado === 1) {
			        const posiciones = data.res;

			        // Iterar sobre las posiciones y actualizar las posiciones de los overlays
				        posiciones.forEach((pos) => {
				          if (overlay) {
				            // Configurar estilo inicial
								    overlay.style.cursor = "grab";
								    overlay.style.position = "absolute";
								    // overlay.style.left = overlay.style.left || pos.x_posicion + "px";
								    // overlay.style.top = overlay.style.top || pos.y_posicion + "px";
								    overlay.style.left = pos.x_posicion + "px";
								    overlay.style.top = pos.y_posicion + "px";
				          }
				        });
			      }else{
		 					// Configurar estilo inicial
					    	overlay.style.cursor = "grab";
					    // overlay.style.position = "absolute";
					    // overlay.style.left = overlay.style.left || "355px";
					    // overlay.style.top = overlay.style.top || "330px";
						    overlay.style.left = "355px";
						    overlay.style.top = "330px";
			      }
			    },
			    "json"
			  );
			}

		  function mostrarPosicionLotePorProceso(_todos = 0) {
		  	$('#paso_lotes_transp_'+idpaso_Selected).html('');

		  	if(idpaso_Selected == 10){
		  		imagen_lote = "square_orange.png";
		  	}else if(idpaso_Selected == 12){
					imagen_lote = "ruma.png";
		  	}else{
		  		var imagen_lote = "circle_gold.png";
		  	}

			  $.post(
			    "apis/backend.php",
			    { accion: "get_WorkflowPlanta_ListarPosicion_Lote", todos: _todos, cod_lote: codlote_Selected, id_proceso: idpaso_Selected },
			    function (data) {
			      if (data.estado === 1) {
			        const posiciones = data.res;

			        // Iterar sobre las posiciones y actualizar las posiciones de los overlays
				        posiciones.forEach((pos) => {

				        	var width = null;
				        	var height = null;

				        	if(pos.nPesoNetoBalanza > 0 && pos.nPesoNetoBalanza <= 10000 ){
				        		width = "30px";
				        		height = "30px";
				        	}else if(pos.nPesoNetoBalanza > 10000 && pos.nPesoNetoBalanza <= 20000 ){
										width = "35px";
				        		height = "35px";
				        	}else{
										width = "40px";
				        		height = "40px";
				        	}

				        	// var imagen_circle_gold_lote = '<img src="images/circle_gold.png" class="overlay-image-transp" id="overlay_transp_'+pos.id_proceso+"_"+pos.cod_lote+'" style="width: '+width+'; height: '+height+'; opacity: 0.5;  cursor: pointer;" ondblclick="f_AdminConfiguracionVer('+pos.cod_lote+')">';

	    						var imagen_circle_gold_lote = '<img src="images/'+imagen_lote+'" class="overlay-image-transp" id="overlay_transp_'+pos.id_proceso+"_"+pos.cod_lote+'" style="width: '+width+'; height: '+height+'; opacity: 0.7;  cursor: pointer;" ondblclick="f_AdminConfiguracionVer('+pos.cod_lote+')">';

	    						var texto_circle_gold_lote = '<span class="centered-text" id="overlay_transp_text_'+pos.id_proceso+"_"+pos.cod_lote+'" style="position: absolute; font-size: 11px; color: white;border-radius:5px; background: black; opacity: 0.7; padding: 3px; z-index: 10; cursor: pointer;" ondblclick="f_AdminConfiguracionVer('+pos.cod_lote+')">'+pos.cod_lote+'</span>';

	        	    	$('#paso_lotes_transp_'+pos.id_proceso).append(imagen_circle_gold_lote);
	        	    	$('#paso_lotes_transp_'+pos.id_proceso).append(texto_circle_gold_lote);

	        	    	var overlay_transp = document.getElementById("overlay_transp_" + pos.id_proceso+"_"+pos.cod_lote);
	        	    	var overlay_transp_text = document.getElementById("overlay_transp_text_" + pos.id_proceso+"_"+pos.cod_lote);

				          if (overlay_transp && texto_circle_gold_lote) {
				            // Configurar estilo inicial
									    overlay_transp.style.left = overlay_transp.style.left || pos.x_posicion + "px";
									    overlay_transp.style.top = overlay_transp.style.top || pos.y_posicion + "px";

										if(pos.nPesoNetoBalanza > 0 && pos.nPesoNetoBalanza <= 10000 ){
				        		 	overlay_transp_text.style.left = overlay_transp_text.style.left || (parseFloat(pos.x_posicion)-4) + "px";
								    	overlay_transp_text.style.top = overlay_transp_text.style.top || (parseFloat(pos.y_posicion)+4) + "px";
					        	}else if(pos.nPesoNetoBalanza > 10000 && pos.nPesoNetoBalanza <= 20000 ){
										 	overlay_transp_text.style.left = overlay_transp_text.style.left || (parseFloat(pos.x_posicion)-2) + "px";
								    	overlay_transp_text.style.top = overlay_transp_text.style.top || (parseFloat(pos.y_posicion)+7) + "px";
					        	}else{
									 		overlay_transp_text.style.left = overlay_transp_text.style.left || (parseFloat(pos.x_posicion)) + "px";
								    	overlay_transp_text.style.top = overlay_transp_text.style.top || (parseFloat(pos.y_posicion)+10) + "px";
					        	}
								   
				          }
				        });
			      }
			    },
			    "json"
			  );
			}

			function f_AdminConfiguracion(){
        titulo = '<h6>Configuración de Equipos</h6><h6>'+codlote_Selected+ '</h6>';
	        
		    // Colocando el título a la pantalla
	        $("#modal_addequiposLabel").html(titulo);

		    // Cargando datos
	        f_LoadEquipos();
	        f_OpenModal('modal_addequipos');

    	}

			function f_AdminConfiguracionPorLote(){
			 	f_cerrarModal('modal_verequipos');
				var cod_lote = $("#input_hd_cod_lote_config").val();
        titulo = '<h6>Configuración de Equipos</h6><h6>'+cod_lote+ '</h6>';
	        
		    // Colocando el título a la pantalla
	        $("#modal_addequiposLabel").html(titulo);

		    // Cargando datos
	        f_LoadEquipos(cod_lote);
	        f_OpenModal('modal_addequipos');
    	}

			function f_AdminConfiguracionVer(_cod_lote){
        titulo = '<h6>Listado de Equipos </h6><h6>'+_cod_lote+ '</h6>';
	        
		    // Colocando el título a la pantalla
	        $("#modal_verequiposLabel").html(titulo);

		    // Cargando datos
	        f_LoadVerEquipos(_cod_lote);
	        f_OpenModal('modal_verequipos');
					$("#input_hd_cod_lote_config").val(_cod_lote);
    	}

    	function f_GrabarConfiguracion(_id_equipo, _cod_lote){
    		var is_checked = $("#checkbox_equipo_"+_id_equipo).is(':checked');

        // $.post( "apis/backend.php", { accion: "grabar_LoteConfiguracion", id_equipo: _id_equipo, cod_lote: codlote_Selected, id_proceso: idpaso_Selected, is_checked: is_checked },
    	 	$.post( "apis/backend.php", { accion: "grabar_LoteConfiguracion", id_equipo: _id_equipo, cod_lote: _cod_lote, id_proceso: idpaso_Selected, is_checked: is_checked },
          function( data ) {
            if(data.estado == 1){
              // f_LoadResultados();
            }
            else{
              alert("Ocurrió un error al momento de grabar la Configuración de Equipos.");
            }
        }, "json");
      };

	  </script>

	</body>
</html>