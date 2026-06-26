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

		<title><?php echo $nom_app; ?> | Workflow de Operador</title>

		<script type="text/javascript">
			let itemlote_Selected = 0;
      let codlote_Selected = 0;
      let paso_Selected = 0;
      let idpaso_Selected = 0;

      let itemproceso_Selected = 0;
      let codproceso_Selected = 0;
		</script>

		<style>
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
			  cursor: grab;
			  z-index: 10; /* Asegura que el overlay esté sobre la imagen del mapa */
			}

			@keyframes resize {
		    0% {
		        width: 32px;
		        height: 32px;
		    }
		    50% {
		        width: 42px;
		        height: 42px;
		    }
		    100% {
		        width: 32px;
		        height: 32px;
		    }
			}

			.animate-size {
		    animation: resize 1s infinite; /* Ajusta el tiempo según prefieras */
		    transition: all 1s ease;
			}

			.blink-shadow {
		    -webkit-box-shadow: 0px 0px 19px 0px rgba(0,72,255,0.83);
				-moz-box-shadow: 0px 0px 19px 0px rgba(0,72,255,0.83);
				box-shadow: 0px 0px 19px 0px rgba(0,72,255,0.83);
		    transition: box-shadow 0.5s ease-in-out;
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
									<h6>WorkFlow de Operador</h6>
								</div>

								<div id="div_plantas" class="col-md-2 col-sm-2 col-xs-12" style="padding: 0px; padding-bottom: 5px;">
									<div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
										<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
											<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
												<div class="d-flex">
													<h6>Alerta de Procesos</h6>

													<div id="wt_loadingingresooperaciones" class="" style="font-size: 10px; text-align: center; display: none; padding-top: 5px;">
														<img src="<?php echo $img_waiting ?>" style="width: 20px;">
														<label style="font-style: italic;"> Cargando datos...</label>
													</div>
												</div>
											</div>
										</div>

										<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="col-md-12 col-sm-12 col-xs-12" style="margin-top: -15px; overflow-y: auto; width: 100%; height: 750px">
											<div id="div_ingresoprocesosseleccion" style="padding: 5px; font-size: 13px;">
			                </div>
			                <hr>
			                <div id="div_ingresoprocesos"  style="padding: 5px; font-size: 13px;">
			                </div>
										</div>
									</div>
								</div>

								<div class="col-md-10 col-sm-10 col-xs-12" style="padding: 0px; padding-left: 5px;">
									<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-left: 0px; margin-right: 0px;  height: 792px">
										<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
											<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
												<div class="col-md-12 col-sm-12 col-xs-12">
													<div class="d-flex">
														<h5 style="font-size: 14px; font-weight: bold; padding-top: 5px;"><label id="lbl_tituloproceso"></label> <label id="lbl_titulolote" ></label>

														<div id="wt_procesos" class="" style="font-size: 10px; text-align: center; display: none; padding-top: 5px;">
															<img src="<?php echo $img_waiting ?>" style="width: 20px;">
															<label style="font-style: italic;"> Cargando...</label>
														</div>
													</div>
												</div>

												<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: auto; width: 100%;">
													<div id="div_loteconfiguracion" class="d-flex flex-column mb-3">
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
				// Titulo de Pantalla
					$("#nv_titulo").html('| Workflow de Operador');
        // Carga Tarjetas
          f_LoadCards_All();
			}
		</script>

		<!-- Funciones Principales -->
		<script type="text/javascript">

			function f_LoadCards_All(){
        f_LoadCards_IngresoOperacionesSeleccionados();
        f_LoadCards_IngresoOperaciones();
      }

      function f_LoadingIngresoOperaciones(_is_show){
        if (_is_show == 1){
          $("#wt_loadingingresooperaciones").show();
        }
        else{
          $("#wt_loadingingresooperaciones").hide();
        }
      }

		</script>

		<!-- Funciones de Menús -->
		<script type="text/javascript">
			function f_SetDimension(){
			}
		</script>
		
	  <script>
     	function f_LoadCards_IngresoOperacionesSeleccionados(){
        f_LoadingIngresoOperaciones(1);
        $.post( "apis/backend.php", { accion: "get_LoteConfiguracion_Cards" , is_select: 1}, 
          function( data ) {
            if(data.estado == 1){
              $("#div_ingresoprocesosseleccion").html(data.html);

              var element = $("#div_select_panel_" + data.id_select);
              setInterval(function () {
	                element.toggleClass("blink-shadow");
	            }, 1000);
            }
            else{
              $("#div_ingresoprocesosseleccion").html('<h6><i class="bi bi-check2-square"></i> Trabajo Seleccionado</h6><label class="text-center" style="color: gray;">Ningún proceso seleccionado</label>');
            }
            f_LoadingIngresoOperaciones(0);
        }, "json");
    	}

    	function f_LoadCards_IngresoOperaciones(){
        f_LoadingIngresoOperaciones(1);
        $.post( "apis/backend.php", { accion: "get_LoteConfiguracion_Cards" , is_select: 0}, 
          function( data ) {
            if(data.estado == 1){
              $("#div_ingresoprocesos").html(data.html);
            }
            else{
              $("#div_ingresoprocesos").html('');
            }
            f_LoadingIngresoOperaciones(0);
            setTimeout('f_LoadCards_IngresoOperaciones()', 60000);
          }, "json");
      }

     function f_LoadItemLoteConfiguracion(_id, _cod_lote, _id_equipo, _descripcion_proceso, _id_proceso ){
	    	$.post( "apis/backend.php", { accion: "update_LoteConfiguracion_Seleccionar", id: _id, id_equipo: _id_equipo }, 
        function( data ) {
          if(data.estado == 1){
          	f_LoadCards_All();
					  mostrarPosicionLotePorProceso1(_cod_lote, _id_proceso);
					  mostrarPosicionLotePorProceso2(_cod_lote, _id_proceso);
          }
          else{
            alert("Ocurrió un error al momento al seleccionar el proceso.");
          }
        }, "json");
      

        var _html = '';

        // Seteando título
        	$("#lbl_tituloproceso").html('PROCESO DE '+_descripcion_proceso);
        	$("#lbl_titulolote").html(' PARA '+_cod_lote);

        // Cargando datos
					_html += '<div class="col-md-12 col-sm-12 colx-xs-12">';
       		_html += ' <div class="row">';

       		_html += '  <div class="row" style="margin-bottom: 10px">';
       		//Inicio
       		_html += '  <div class="col-md-6 d-flex align-items-end justify-content-end" >';

        	_html += ' 	 <button id="btn_inicio" class="btn btn-success" type="button" onclick="f_RegistroFechaHora(0,'+_id+' );" style="color: #ffffff; font-size: 14px; width: 92px; height: 60px;  box-shadow: 0 0 10px #000000; display: none;">';
					_html += '   <b><i class="bi bi-play-circle-fill" style="font-size: 30px !important"></i><br> INICIAR</b>';
					_html += '   </button>';

					_html += '   <div id="div_InfoInicio" style="padding: 0px; display: none;">';
					_html += '    <div class="d-flex flex-column mb-3 align-items-center justify-content-center" style="background-color: #28a745; width: 250px; height: 60px; box-shadow: 0 0 10px #747E7E; padding: 5px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;">';

					_html += '    <div class="row" style="text-align: center; width: 90%; margin-top: -5px;">';
					_html += '     <label style="font-size: 12px; font-weight: bold; background-color: #fff; box-shadow: 0 0 10px #747E7E">';
					_html += '      INICIO';
					_html += '     </label>';
					_html += '    </div>';

					_html += '	<div class="p-2">';
					_html += '	 <label id="lbl_fechahoraregistro_inicio" style="font-size: 12px; font-weight: bold; color: #fff">';
					_html += '	 </label>';
					_html += '	</div>';

					_html += '	<div class="p-2">';
					_html += '	 <label id="lbl_usuarioregistro_inicio" style="font-size: 12px; font-weight: bold; margin-top: -20px; color: #fff;">';
					_html += '	 </label>';
					_html += '	</div>';

					_html += '    </div>';
					_html += '   </div>';

					_html += '   </div>';

					//Fin
       		_html += '  <div class="col-md-6" >';

        	_html += ' 	 <button id="btn_fin" class="btn btn-danger" type="button" onclick="f_RegistroFechaHora(1,'+_id+' );" style="color: #ffffff; font-size: 14px; width: 92px; height: 60px; box-shadow: 0 0 10px #000000; display: none;">';
					_html += '   <b><i class="bi bi-stop-circle-fill" style="font-size: 30px !important"></i><br> FINALIZAR</b>';
					_html += '   </button>';

					_html += '   <div id="div_InfoFin" style="padding: 0px; display: none;">';
					_html += '       <div class="d-flex flex-column mb-3 align-items-center justify-content-center" style="background-color: #FF5F5D; width: 250px; height: 60px; box-shadow: 0 0 10px #747E7E; padding: 5px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;">';

					_html += '    	<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; width: 100%; margin-top: -5px;">';
					_html += '     <label style="font-size: 12px; font-weight: bold; background-color: #fff; box-shadow: 0 0 10px #747E7E">';
					_html += '      FIN';
					_html += '     </label>';
					_html += '    </div>';

					_html += '	<div class="p-2">';
					_html += '	 <label id="lbl_fechahoraregistro_fin" style="font-size: 18px; font-weight: bold;">';
					_html += '	 </label>';
					_html += '	</div>';

					_html += '	<div class="p-2">';
					_html += '	 <label id="lbl_usuarioregistro_fin" style="font-size: 14px; font-weight: bold; margin-top: -20px; color: #000;">';
					_html += '	 </label>';
					_html += '	</div>';

					_html += '    </div>';
					_html += '   </div>';

					_html += '  </div>';

 		  		_html += '  </div>';

        	_html += '   <div  class="step-content">';
       		_html += '     <img src="images/mapa_lozas.png" style="width: 100%; height:640px">';

       		_html += '   <div id="paso_lotes_transp1_'+_id_proceso+'">';
       		_html += '     </div>';

   				_html += '   <div id="paso_lotes_transp2_'+_id_proceso+'">';
       		_html += '     </div>';

       		_html += '   </div>';

 		  		_html += ' </div>';
       		_html += '</div>';

          $("#div_loteconfiguracion").html(_html);

          $.post( "apis/backend.php", { accion: "get_LoteConfiguracionDetalle", id_lote_configuracion: _id}, 
	          function( data ) {
	            if(data.estado == 1){
	            	if(data.res.length > 0){
									$.each( data.res, function( key, val ) {
		            		f_LoadProcesoLoteConfiguracion(val.fechahora_inicio, val.fechahora_fin, val.usuario_inicio, val.usuario_fin);
            	   	});
	            	}else{
									$("#btn_inicio").show();
	            	}	
	            }
	        }, "json");
      }

      function mostrarPosicionLotePorProceso1(_cod_lote, _id_proceso) {
		  	$('#paso_lotes_transp1_'+_id_proceso).html('');
			  $.post(
			    "apis/backend.php",
			    { accion: "get_WorkflowPlanta_ListarPosicion1_LoteConfiguracion",  cod_lote: _cod_lote, id_proceso: _id_proceso },
			    function (data) {
			      if (data.estado === 1) {
			        var posiciones = data.res;

			        // Iterar sobre las posiciones y actualizar las posiciones de los overlays
				        posiciones.forEach((pos) => {

				        	var width = null;
				        	var height = null;

				        	if(pos && pos.nPesoNetoBalanza !== undefined){
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

				        		var imagen_circle_red_lote = '<img src="images/circle_red.png" class="overlay-image-transp animate-size" id="overlay_transp1_'+pos.id_proceso+"_"+pos.cod_lote+'" style="width: '+width+'; height: '+height+'; " >';

	        	    		$('#paso_lotes_transp1_'+_id_proceso).append(imagen_circle_red_lote);

	        	    		var overlay_transp = document.getElementById("overlay_transp1_" + pos.id_proceso+"_"+pos.cod_lote);

					          if (overlay_transp) {
					            // Configurar estilo inicial
									    overlay_transp.style.left = overlay_transp.style.left || (parseFloat(pos.x_posicion)) + "px";
									    overlay_transp.style.top = overlay_transp.style.top || (parseFloat(pos.y_posicion)) + "px";
					          }
				        	}
				        });
			      }
			    },
			    "json"
			  );
			}

  		function mostrarPosicionLotePorProceso2(_cod_lote, _id_proceso) {
		  	$('#paso_lotes_transp2_'+_id_proceso).html('');
			  $.post(
			    "apis/backend.php",
			    { accion: "get_WorkflowPlanta_ListarPosicion2_LoteConfiguracion",  cod_lote: _cod_lote, id_proceso: _id_proceso },
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
    						
    						var imagen_circle_gold_lote = '<img src="images/circle_gold.png" class="overlay-image-transp animate-size" id="overlay_transp2_'+pos.id_proceso+"_"+pos.cod_lote+'" style="width: '+width+'; height: '+height+'; " >';

        	    	$('#paso_lotes_transp2_'+pos.id_proceso).append(imagen_circle_gold_lote);

        	    	var overlay_transp = document.getElementById("overlay_transp2_" + pos.id_proceso+"_"+pos.cod_lote);

			          if (overlay_transp) {
			            // Configurar estilo inicial
								    overlay_transp.style.left = overlay_transp.style.left || (parseFloat(pos.x_posicion)) + "px";
								    overlay_transp.style.top = overlay_transp.style.top || (parseFloat(pos.y_posicion)) + "px";
			          }
			        });
			      }
			    },
			    "json"
			  );
			}
			
      function f_RegistroFechaHora(_is_fin,_id_lote_configuracion){
				// Validando datos
					if (_is_fin == 0){
						if (!confirm("¿Está seguro de Iniciar el Proceso")){
							return
						}
						$("#btn_inicio").hide();
					}
					else{
						if (!confirm("¿Está seguro de Finalizar el Proceso")){
							return
						}
						$("#btn_fin").hide();
					}

        // Grabando Datos
          $.post( "apis/backend.php", { accion: "grabar_LoteConfiguracion_FechaHora", is_fin: _is_fin, id_lote_configuracion: _id_lote_configuracion},
            function( data ) {
              if(data.estado == 1){
              	if(data.is_fin == 1){
              		location.reload();
              	}else{
 									$.post( "apis/backend.php", { accion: "get_LoteConfiguracionDetalle", id_lote_configuracion: _id_lote_configuracion}, function( data ) {
					            if(data.estado == 1){
					            	if(data.res.length > 0){
													$.each( data.res, function( key, val ) {
						            		f_LoadProcesoLoteConfiguracion(val.fechahora_inicio, val.fechahora_fin, val.usuario_inicio, val.usuario_fin);
				            	   	});
					            	}
					            }
					        }, "json");
              	}
              }else if(data.estado == 2){
              	alert("No puedes iniciar otra actividad mientras tenga otra actividad en ejecución.");
              	f_LoadProcesoLoteConfiguracion('','','','');
              }
              else{
                alert("Ocurrió un error al momento de grabar los datos.");
              }

            }, "json");
			}

	    function f_LoadProcesoLoteConfiguracion(_fechahora_inicio, _fechahora_fin, _usuario_inicio, _usuario_fin){
    		$("#btn_inicio").hide();
				$("#btn_fin").hide();

				$("#div_InfoInicio").hide();
				$("#div_InfoFin").hide();

    		if (_fechahora_inicio == undefined || _fechahora_inicio.length == 0){
    			$("#btn_inicio").show();
    		}
    		else{
    			if (_fechahora_fin == undefined || _fechahora_fin.length == 0){
      			$("#div_InfoInicio").show();
      			$("#btn_fin").show();
      			// Seteando objetos de registro
      				$("#lbl_fechahoraregistro_inicio").html(_fechahora_inicio);
      				$("#lbl_usuarioregistro_inicio").html(_usuario_inicio);
      		}
      		else{
      			$("#div_InfoInicio").show();
      			$("#div_InfoFin").show();
      			// Seteando objetos de registro
      				$("#lbl_fechahoraregistro_inicio").html(_fechahora_inicio);
      				$("#lbl_usuarioregistro_inicio").html(_usuario_inicio);
      				$("#lbl_fechahoraregistro_fin").html(_fechahora_fin);
      				$("#lbl_usuarioregistro_fin").html(_usuario_fin);
      		}
    		}
      }
	  </script>

	</body>
</html>