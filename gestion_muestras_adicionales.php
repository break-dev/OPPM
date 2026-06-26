<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');
	include('global/auxiliares.php');

	if(!isset($_SESSION["Id"])){
		header('Location: index.php');
	}

	// Determinando el tipo de ingreso
		$id_rol = $_GET["x"];
		$is_controlinterno = 0;
		$is_administracioncomercial = 0;

		if ($id_rol == 'u4t5Xv2RmPz9Lwq'){
			$is_controlinterno = 1; // Control Interno
		}

		if ($id_rol == 'b8Q7NjKsT1FgHmV'){
			$is_administracioncomercial = 1; // Gestión Comercial
		}
echo '$id_rol: '.$id_rol;
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
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

		<title><?php echo $nom_app; ?> | Gestión de Muestras</title>

		<script type="text/javascript">
			let itemlote_Selected = 0;
			let codlote_Selected = 0;
		</script>

		<style>

		</style>
	</head>

	<body class="bg-light" onload="f_SetDimension(); f_Init();" style="zoom: 80%;">
		<div class="container-fluid">
			<div class="row">
				<!-- Llamando a Navbar -->
				<?php echo $navbar_maintop; ?>

				<div class="row">
					<!-- Menús principales -->
					<div id="div_menu1" class="col-md-1 col-sm-1 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; background-color: #DEDEDE;">
						
					</div>

					<div class="col-md-11 col-sm-11 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding-top: 10px; padding-left: 35px;">
						<div class="d-flex row">
							<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-bottom: 5px;">
								<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
									<h5>Filtros</h5>
								</div>

								<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
									<hr style="border-color: #D9D9D9;"/>
								</div>

								<div class="row" style="padding-left: 30px; margin-top: -5px; margin-bottom: 10px; font-size: 13px;">
									<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Fecha de creación</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<input id="fecha_inicio" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>">

												<input id="fecha_fin" type="date" class="form-control" style="text-align: center; margin-left: 5px; font-size: 14px;" value="<?php echo $g_date; ?>">
											</div>
										</div>
									</div>

									<div class="col-md-9 col-sm-9 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Lotes:</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -8px; padding-left: 10px; padding-right: 10px;">
												<div class="flex-fill">
													<select id="filtro_lote" class="form-control select_datos" multiple data-placeholder="Elija una o más opciones..." style="font-size: 14px; border: solid; border-width: 1px; border-color: #BFBFBF; border-radius: 7px; max-height: 40px;">
														<?php

														$q_lotes = "SELECT ccod_Lote
																						FROM catalogolotes
																					 WHERE (YEAR(dFechaIngreso) >= 2024
																							OR ccod_Lote IN ('AUM-2587', 'AUM-3000', 'AUM-2337', 'AUM-2585', 'AUM-2907', 'AUM-2980'))
																					ORDER BY ccod_Lote DESC";

														if ($res_lotes = mysqli_query($enlace, $q_lotes)) {
															if (mysqli_num_rows($res_lotes) > 0) {
																while ($row_lotes = mysqli_fetch_array($res_lotes)) {
																	?>

																	<option value="<?php echo $row_lotes["ccod_Lote"]; ?>"><?php echo $row_lotes["ccod_Lote"]; ?></option>

																	<?php
																}
															}
														}

														?>
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="row" style="padding-left: 30px; margin-top: 5px; margin-bottom: 10px; font-size: 13px;">
									<div class="col-md-10 col-sm-10 col-xs-12">
										<button class="btn btn-secondary" type="button" onclick="f_LoadLotes();" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px; background-color: #cfaa41; margin-bottom: 10px;">
											<i class="bi bi-search"></i> <b>Ejecutar Búsqueda</b>
										</button>
									</div>

									<div class="col-md-2 col-sm-2 col-xs-12">
										<button class="btn btn-success" type="button" onclick="f_ExportToExcel();" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px; margin-bottom: 12px;">
											<b>Exportar a Excel</b>
										</button>
									</div>
								</div>
							</div>

							<div class="row" style="padding: 0px;">
								<div id="div_lotes" class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px; padding-bottom: 5px;">
									<div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px; margin-right: 5px;">
										<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
											<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
												<div class="d-flex">
													<h6>Lista de Lotes</h6>

													<div id="wt_lotes" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
														<img src="<?php echo $img_waiting ?>" style="width: 20px;">
														<label style="font-style: italic;"> Cargando datos...</label>
													</div>
												</div>
											</div>
										</div>

										<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-y: scroll; width: 100%; height: 650px;">
											<table class="table table-bordered table-hover">
												<thead>
													<tr style="font-size: 12px;">
														<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
															
														</th>

														<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
															Lote
														</th>

														<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
															Planta<br>Ingreso
														</th>

														<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
															Proveedor
														</th>

														<th colspan="6" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; background-color: #D9D2D0; color: #37393c; border-top-right-radius: 15px;">
															Información Muestras
														</th>
													</tr>

													<tr style="font-size: 12px;">
														<th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 60px;">
															DNI/RUC
														</th>

														<th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 220px;">
															Razón Social
														</th>

														<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; background-color: #D9D2D0; color: #37393c;">
															N°
														</th>

														<th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; background-color: #D9D2D0; color: #37393c;">
															Código Muestra
														</th>

														<th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; background-color: #D9D2D0; color: #37393c;">
															Análisis
														</th>

														<th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; background-color: #D9D2D0; color: #37393c;">
															Laboratorio
														</th>
													</tr>
												</thead>

												<tbody id="tbl_lotes">
													
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Menú flotante -->
			<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel" style="background-color: #DEDEDE; width: 20%; z-index: 10000;">
				<div class="offcanvas-header" style="background-color: #ffffff;">
					<h5 id="sb1_titulo" class="offcanvas-title" id="offcanvasExampleLabel"></h5>
					<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
				</div>

				<div id="div_submenu1" class="offcanvas-body" style="color: #212529;">

				</div>
			</div>
		</div>

		<!-- Ventanas modales -->


		<!-- Referenciando a JQuery -->
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

		<!-- Select2 -->
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
					$("#nv_titulo").html('| Gestión de Muestras');

				// Carga el detalle de información
					f_LoadLotes();
			}

		</script>

		<!-- Seteando objetos Select2 -->
		<script type="text/javascript">
			// Listas para edición
				$('.select_datos').select2({
					theme: "bootstrap-5",
					width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
					placeholder: $( this ).data( 'placeholder' ),
					allowClear: true
				});
		</script>

		<!-- Funciones Principales -->
		<script type="text/javascript">
			function f_LoadLotes(){
				var _html = '';

				var fecha_inicio = $("#fecha_inicio").val();
				var fecha_fin = $("#fecha_fin").val();
				var filtro_lotes = $("#filtro_lote").val();

				f_LoadingLotes(1);

				$("#tbl_lotes").html('');

				$.post( "apis/backend.php", { accion: "get_GestionMuestrasAdicionales_ListaLotes", is_controlinterno: <?php echo $is_controlinterno ?>, fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, filtro_lotes: filtro_lotes }, 
					function( data ) {
						if(data.estado == 1){
							$("#tbl_lotes").html(data.html);
						}

						f_LoadingLotes(0);

					}, "json");
			}

			function f_AddNewMuestra(_item, _cod_lote, _objeto, _item_hijo){
				if (!confirm("¿Está seguro de agregar una nueva muestra para el lote: " + _cod_lote + "?")){
					return;
				}

				$.post( "apis/backend.php", { accion: "add_GestionMuestrasAdicionales_NewMuestra", cod_lote: _cod_lote, item: _item_hijo },
					function( data ) {
						if(data.estado == 1){
							f_GetInfoMuestrasLote(_item, _cod_lote, _objeto, _item_hijo, data.new_id);
						}
					});
			}

			function f_EliminarMuestra(_id_registro){
				if (!confirm("¿Está seguro de eliminar la muestra seleccionada?")){
					return;
				}

				$.post( "apis/backend.php", { accion: "eliminar_GestionMuestras_Muestra", id_registro: _id_registro },
					function( data ) {
						if(data.estado == 1){
							f_GetListaGrupos(codlote_Selected, 1);
						}
					});
			}

			// Función para agregar un nuevo hijo (fila)
			function f_GetInfoMuestrasLote(_item, _cod_lote, _objeto, _item_hijo, _id_registro){
				// Identificar el botón "+" presionado y obtener el TR correspondiente
					var btn = $(_objeto);
					var filaPadre = btn.closest('tr'); // Encuentra el TR más cercano al botón presionado

				// Incrementar el rowspan en cada una de las 5 celdas de la fila padre
					for (var i = 1; i <= 5; i++) {
							var tdPadre = $(`#tritem_${i}_${_item}`);
							var rowspanActual = parseInt(tdPadre.attr('rowspan')) || 1;
							tdPadre.attr('rowspan', rowspanActual + 1);
					}

				// Crear la nueva fila hija justo debajo del TR seleccionado
					var nuevaFila = '';

					nuevaFila += '<tr class="clstbl_lotes_' + _item + '" style="cursor: pointer; font-size: 12px; cursor: pointer;">';
					nuevaFila += '	<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
					nuevaFila += '	</td>';

					nuevaFila += '	<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; width: 30px;">';
					nuevaFila += '		<label style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; padding-left: 5px; padding-right: 6px; padding-bottom: 1px; background-color: #0d6efd; color: #ffffff; font-weight: bold; cursor: pointer; height: 22px; width: 22px; font-size: 14px;" onclick="f_AddNewMuestra(' + _item + ", '" + _cod_lote + "', this, " + _item_hijo + ');">+</label>';
					nuevaFila += '	</td>';

          nuevaFila += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; width: 30px;">';
					nuevaFila += '      <label style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; padding-left: 6px; padding-right: 6px; padding-top: 2px; background-color: #FF5F5D; color: #ffffff; font-weight: bold; cursor: pointer; height: 22px; width: 22px; font-size: 11px;" onclick="f_EliminarMuestra(0);">X</label>';
          nuevaFila += '  </td>';
											
					nuevaFila += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
					nuevaFila += '		<input type="text" class="form-control col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 12px;" onblur="f_UpdateDatos(1, ' + _id_registro + ", '" + _cod_lote + "', " + 'this)" value="">';
					nuevaFila += '  </td>';
					nuevaFila += '</tr>';

				// Insertar la fila hija justo después del TR actual
					filaPadre.after(nuevaFila);

				// Asigna los nuevos correlativos (Item)
					// Selecciona todas las filas TR con la clase especificada
				    const filas = document.querySelectorAll(".clstbl_lotes_" + _item);
				    
			    // Recorre las filas seleccionadas
				    var i = 1;

				    filas.forEach((fila, indice) => {
			        // Selecciona el primer TD dentro de la fila actual
			        const primerTD = fila.querySelector("td");
			        
			        // Verifica si el primer TD existe y cambia su HTML
			        if (primerTD) {
			            primerTD.innerHTML = i ++;
			        }
				    });
			}

			// Función para eliminar el último hijo (fila)
			function f_EliminarMuestra() {
				if (childCount > 0) {
					childCount--;

					// Reducir el rowspan de la celda padre
						let rowspan = parseInt(cellParent.getAttribute("rowspan"));

						cellParent.setAttribute("rowspan", rowspan - 1);

					// Eliminar la última fila hija agregada
						let rows = document.querySelectorAll("#miTabla .childRow");

						if (rows.length > 0) {
								rows[rows.length - 1].remove();
						}
				}
			}
		</script>

		<!-- Funciones Secundarias -->
		<script type="text/javascript">
			function f_LoadingLotes(_is_show){
				if (_is_show == 1){
					$("#wt_lotes").show();
				}
				else{
					$("#wt_lotes").hide();
				}
			}
		</script>

		<!-- Funciones de Grabación -->
		<script type="text/javascript">
			function f_UpdateDatos(_item, _id_registro, _cod_lote, _objeto){
				var valor = _objeto.value;

				$.post( "apis/backend.php", { accion: "update_GestionMuestrasAdicionales_InformacionMuestras", is_controlinterno: <?php echo $is_controlinterno ?>, id_registro: _id_registro, cod_lote: _cod_lote, orden_campo: _item, valor: valor },
          function( data ) {
            if(data.estado == 1){
              
            }
          });
			}
		</script>

		<!-- Funciones de Menús -->
		<script type="text/javascript">
			function f_SetDimension(){
				if (screen.width < 500){
					$("#offcanvasExample").css('width', '60%');

					$("#modal_addcliente_content, #modal_addconductor_content, #modal_addzonaorigen_content, #modal_addacompanante_content").css('margin-top', '10px');
				}
			}

		</script>

		<!-- Funcion Default -->
		<script type="text/javascript">
			
		</script>
	</body>
</html>