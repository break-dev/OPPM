<?php

session_start();

include('cnx/cnx.php');
include('global/variables.php');
include('global/auxiliares.php');

if (!isset($_SESSION["Id"])) {
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
	<link rel="icon" href="<?php echo $favicon; ?>" type="image/png" />

	<!-- Bootstrap -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

	<!-- Íconos -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

	<!-- Select2 -->
	<!-- Select2 -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

	<!-- JSColor -->
	<script src="libs/jscolor/jscolor.js"></script>

	<title><?php echo $nom_app; ?> | Gestión de Lotes</title>

	<script type="text/javascript">

	</script>

	<style type="text/css">
		/*.circle {
			width: 50px;
			height: 50px;
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
		}*/

		.circle {
	    width: 50px;
	    height: 50px;
	    border-radius: 50%;
	    background-color: gold;
	    position: absolute;
	    text-align: center;
	    line-height: 50px;
	    font-size: 12px;
	    font-weight: bold;
	    color: black;
	    border: 2px solid black;
	    cursor: pointer;
	    transition: transform 0.2s ease-in-out;
	    display: flex;
	    align-items: center;
	    justify-content: center;
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

		/* Ocultar el SVG inicialmente */
			.progress-ring {
		    position: absolute;
		    top: 0;
		    left: 0;
		    transform: rotate(-90deg); /* Para que el progreso inicie desde arriba */
		    pointer-events: none;
			}

			.progress-ring-circle {
		    fill: none;
		    stroke: #F24B6A; /* Color del progreso */
		    stroke-width: 4;
		    stroke-dasharray: 175; /* Longitud total del borde */
		    stroke-dashoffset: 175; /* Inicialmente oculto */
		    transition: stroke-dashoffset 1.5s linear;
		    opacity: 0; /* Oculto hasta que el usuario presione */
			}

			/* Activar la animación cuando el usuario mantiene presionado */
			.circle.progress-active .progress-ring-circle {
		    stroke-dashoffset: 0; /* Muestra el progreso completo en 2s */
		    opacity: 1; /* Hace visible el borde */
			}

		/* Estilos para Popover*/
			.popover-header {
        font-size: 12px; /* Cambiar el tamaño de la fuente del título */
        padding: 2px;
      }

      .popover-body {
        font-size: 12px; /* Cambiar el tamaño de la fuente del título */
        padding: 10px;
        width: 250px;
      }

      .close-popover {
        cursor: pointer;
      }

	</style>

	<style type="text/css">
		.modal-circle {
			display: inline-block;
			width: 80px; /* Ajusta según sea necesario */
			height: 80px;
			border-radius: 50%;
			text-align: center;
			line-height: 80px;
			font-weight: bold;
			color: white;
			cursor: pointer;
			transition: all 0.3s ease-in-out;
		}

		.modal-circle:hover {
			filter: brightness(1.2); /* Aumenta el brillo para resaltar */
			box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5); /* Agrega sombra */
			transform: scale(1.05); /* Aumenta ligeramente el tamaño */
		}

		.modal-selected-circle {
			filter: brightness(1.2);
			box-shadow: 0px 0px 18px #0597F2; /* Azul con efecto brillante */
			transform: scale(1.1);
		}
	</style>
</head>

<body class="bg-light" onload="f_SetDimension(); f_Init();" style="zoom: 100%;">
	<div class="container-fluid">
		<div class="row">
			<!-- Llamando a Navbar -->
			<?php echo $navbar_maintop; ?>

			<!-- Menús principales -->
			<div id="div_menu1" class="col-md-1 col-sm-1 col-xs-1" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; height: 114vh; background-color: #DEDEDE; zoom: 80%;">

			</div>

			<div class="col-md-11 col-sm-11 col-xs-11" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding-top: 10px; padding-left: 35px;">
				<div class="d-flex row">
					<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-bottom: 5px; zoom: 80%;">
						<div class="row" style="padding-top: 0px; padding-left : 20px; padding-right: 20px;">
							<div class="d-flex">
								<div class="p-2 flex-grow-1">
									<h6>Filtros</h6>
								</div>

								<div class="d-flex align-items-center">
									<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #D1A43F; width: 110px; height: 27px; margin-right: 3px; text-align: center; color: #ffffff; font-weight: bold; font-size: 14px;">
										Descarga
									</div>
									<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #5054A6; width: 110px; height: 27px; margin-right: 3px; text-align: center; color: #ffffff; font-weight: bold; font-size: 14px;">
										Alimentación
									</div>
									<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #A17A55; width: 110px; height: 27px; margin-right: 3px; text-align: center; color: #ffffff; font-weight: bold; font-size: 14px;">
										Chancado
									</div>
									<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #E67B4C; width: 110px; height: 27px; margin-right: 3px; text-align: center; color: #ffffff; font-weight: bold; font-size: 14px;">
										Acarreo
									</div>
									<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #04B2D9; width: 110px; height: 27px; margin-right: 3px; text-align: center; color: #ffffff; font-weight: bold; font-size: 14px;">
										Muestreo
									</div>
									<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #6E984C; width: 110px; height: 27px; text-align: center; color: #ffffff; font-weight: bold; font-size: 14px;">
										Despacho
									</div>
								</div>
							</div>
						</div>

						<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
							<hr style="border-color: #D9D9D9;" />
						</div>

						<div class="row" style="padding-left: 30px; margin-top: -5px; margin-bottom: 10px; font-size: 13px;">
							<div class="col-md-2 col-sm-2 col-xs-12" style="padding: 2px;">
								<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
									<div class="row" style="padding-left: 10px; padding-right: 10px;">
										<h6 style="font-size: 14px;">Por Unidades</h6>
									</div>

									<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
										<hr style="border-color: #D9D9D9;" />
									</div>

									<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
										<select id="filtro_unidades" class="form-select" style="text-align: left; font-size: 14px;">

										</select>
									</div>
								</div>
							</div>

							<div class="col-md-10 col-sm-10 col-xs-12" style="padding: 2px;">
								<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px; height: 95px;">
									<div class="d-flex" style="margin-top: -7px; padding-left: 10px; padding-right: 10px;">
										<!-- <input id="filtro_lote" type="text" class="form-control" style="font-size: 14px; margin-left: 5px;"> -->
										<h6 style="font-size: 14px; margin-top: 8px; margin-right: 15px;">Por Lotes: </h6>

										<div class="flex-fill" style="margin-top: 3px;">
											<select id="filtro_lote" class="form-control" multiple data-placeholder="Elija una o más opciones..." style="font-size: 14px; border: solid; border-width: 1px; border-color: #BFBFBF; border-radius: 7px; max-height: 40px;">
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
							<div class="col-md-12 col-sm-12 col-xs-12">
								<button class="btn btn-secondary" type="button" onclick="f_LoadUnidadesPendientes();" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px; background-color: #cfaa41; margin-bottom: 10px;">
									<i class="bi bi-search"></i> <b>Ejecutar Búsqueda</b>
								</button>
							</div>
						</div>
					</div>

					<div class="row" style="padding: 0px;">
						<div id="div_Unidades" class="col-md-2 col-sm-2 col-xs-12" style="padding: 0px; padding-bottom: 5px; zoom: 80%;">
							<div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
								<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
									<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
										<div class="d-flex">
											<h6>Lista de Unidades / Lotes</h6>

											<div id="wt_plantas" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
												<img src="<?php echo $img_waiting ?>" style="width: 20px;">
												<label style="font-style: italic;"> Cargando datos...</label>
											</div>
										</div>
									</div>
								</div>

								<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
									<hr style="border-color: #D9D9D9;" />
								</div>

								<div id="div_ListaUnidades" class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; width: 100%; height: 970px; overflow-y: scroll;">
									
								</div>
							</div>
						</div>

						<div id="div_detalle" class="col-md-10 col-sm-10 col-xs-12" style="padding: 0px; padding-left: 5px;">
							<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 5px;">
								<div id="map" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; position: relative; width: 100%; height: 115vh; background: url('images/mapa_lozas.png') no-repeat center; background-size: cover; border: 1px solid #ccc;">
										<!-- Círculos dinámicos aparecerán aquí -->
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
				<h6 id="sb1_titulo" class="offcanvas-title" id="offcanvasExampleLabel"></h6>
				<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
			</div>

			<div id="div_submenu1" class="offcanvas-body" style="color: #212529;">

			</div>
		</div>
	</div>

	<!-- Ventanas modales -->
	<div class="modal fade" id="modal_GestionLotes" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_GestionLotesLabel" aria-hidden="true" style="zoom: 80%;">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-6" >Gestionando Información de: </h1>
					<h1 class="modal-title fs-6" id="modal_GestionLotesLabel" style="margin-left: 5px; color: #337ab7; font-weight: bold;"></h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<table style="width: 100%;">
						<tr>
							<td style="width: 60%; padding-right: 10px; vertical-align: top;">
								<table style="width: 100%;">
									<tr>
										<td id="td_idproceso_1" style="text-align: center; cursor: pointer;" onclick="f_ProcesoSelected(this, 1);">
											<img class="modal-circle" src="<?php echo $img_circle_1 ?>" style="width: 80px; margin-right: 5px;">
										</td>

										<td id="td_idproceso_2" style="text-align: center; cursor: pointer;" onclick="f_ProcesoSelected(this, 2);">
											<img class="modal-circle" src="<?php echo $img_circle_2 ?>" style="width: 80px; margin-right: 5px;">
										</td>

										<td id="td_idproceso_3" style="text-align: center; cursor: pointer;" onclick="f_ProcesoSelected(this, 3);">
											<img class="modal-circle" src="<?php echo $img_circle_3 ?>" style="width: 80px; margin-right: 5px;">
										</td>

										<td id="td_idproceso_4" style="text-align: center; cursor: pointer;" onclick="f_ProcesoSelected(this, 4);">
											<img class="modal-circle" src="<?php echo $img_circle_4 ?>" style="width: 80px; margin-right: 5px;">
										</td>

										<td id="td_idproceso_5" style="text-align: center; cursor: pointer;" onclick="f_ProcesoSelected(this, 5);">
											<img class="modal-circle" src="<?php echo $img_circle_5 ?>" style="width: 80px; margin-right: 5px;">
										</td>

										<td id="td_idproceso_6" style="text-align: center; cursor: pointer; padding-right: 10px;" onclick="f_ProcesoSelected(this, 6);">
											<img class="modal-circle" src="<?php echo $img_circle_6 ?>" style="width: 80px;">
										</td>
									</tr>

									<tr>
										<td style="text-align: center; font-weight: bold; color: #ffffff; font-size: 14px; cursor: pointer;">
											<div style="margin-top: -53px; z-index: 10; position: relative; margin-right: 5px;">
												DESC
											</div>
										</td>

										<td style="text-align: center; font-weight: bold; color: #ffffff; font-size: 14px; cursor: pointer;">
											<div style="margin-top: -53px; z-index: 10; position: relative; margin-right: 5px;">
												ALI
											</div>
										</td>

										<td style="text-align: center; font-weight: bold; color: #ffffff; font-size: 14px; cursor: pointer;">
											<div style="margin-top: -53px; z-index: 10; position: relative; margin-right: 5px;">
												CHN
											</div>
										</td>

										<td style="text-align: center; font-weight: bold; color: #ffffff; font-size: 14px; cursor: pointer;">
											<div style="margin-top: -53px; z-index: 10; position: relative; margin-right: 5px;">
												ACRR
											</div>
										</td>

										<td style="text-align: center; font-weight: bold; color: #ffffff; font-size: 14px; cursor: pointer;">
											<div style="margin-top: -53px; z-index: 10; position: relative; margin-right: 5px;">
												MUES
											</div>
										</td>

										<td style="text-align: center; font-weight: bold; color: #ffffff; font-size: 14px; cursor: pointer; padding-right: 10px;">
											<div style="margin-top: -53px; z-index: 10; position: relative;">
												DESP
											</div>
										</td>
									</tr>
								</table>

								<hr style="border-color: #D9D9D9; margin-right: 10px;">

								<table class="table table-bordered table-striped table-hover">
                  <thead>
                    <tr style="font-size: 12px;">
                      <th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
                        N°
                      </th>

                      <th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
                        Equipo / Personal
                      </th>
                    </tr>
                  </thead>

                  <tbody id="tbl_Recursos">

                  </tbody>
                </table>
							</td>

							<th style="text-align: center; border-left: solid; border-left-width: 1px; border-left-color: #D9D9D9;">
							</td>

							<td style="vertical-align: top;">
								<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 5px;">
									<div class="row" style="padding-left: 10px; padding-right: 10px;">
										<h6 style="font-size: 14px;">Historial</h6>
									</div>

									<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
										<hr style="border-color: #D9D9D9;" />
									</div>

									<div id="div_historial" class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px; align-items: flex-start; height: 400px; overflow-y: scroll;">
										
									</div>
								</div>
							</td>
						</tr>
					</table>
				</div>

				<input id="hd_idproceso" type="hidden">
				<input id="hd_codlote" type="hidden">
				<input id="hd_idtipovehiculo" type="hidden">
				<input id="hd_idtipocarga" type="hidden">

				<div class="modal-footer" style="padding: 0px;">
					<div class="p-2 flex-grow-1">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
					</div>

					<div class="p-2">
						<div class="d-flex">
							<div id="div_button_play" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 7px; cursor: pointer; margin-right: 10px; display: none;">
								<table style="width: 100%; border-spacing: 0px;" onclick="f_ExecuteTask('I');">
									<tr>
										<td style="text-align: center;">
											<img src="<?php echo $img_button_play ?>" style="width: 40px;">
										</td>
									</tr>

									<tr style="font-size: 14px;">
										<td style="text-align: center; font-weight: bold;">
											Iniciar
										</td>
									</tr>
								</table>
							</div>

							<div id="div_button_finalizar" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 7px; cursor: pointer; margin-right: 10px; display: none;">
								<table style="width: 100%; border-spacing: 0px;" onclick="f_ExecuteTask('F');">
									<tr>
										<td style="text-align: center;">
											<img src="<?php echo $img_button_finish ?>" style="width: 40px;">
										</td>
									</tr>

									<tr style="font-size: 14px;">
										<td style="text-align: center; font-weight: bold;">
											Finalizar
										</td>
									</tr>
								</table>
							</div>

							<div id="div_button_pause" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 7px; cursor: pointer; margin-right: 10px; display: none;">
								<table style="width: 100%; border-spacing: 0px;" onclick="f_ExecuteTask('P');">
									<tr>
										<td style="text-align: center;">
											<img src="<?php echo $img_button_pause ?>" style="width: 40px;">
										</td>
									</tr>

									<tr style="font-size: 14px;">
										<td style="text-align: center; font-weight: bold;">
											Pausar
										</td>
									</tr>
								</table>
							</div>

							<div id="div_button_stop" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 7px; cursor: pointer; display: none;">
								<table style="width: 100%; border-spacing: 0px;" onclick="f_ExecuteTask('C');">
									<tr>
										<td style="text-align: center;">
											<img src="<?php echo $img_button_cancel ?>" style="width: 40px;">
										</td>
									</tr>

									<tr style="font-size: 14px;">
										<td style="text-align: center; font-weight: bold;">
											Cancelar
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal_RegistroMotivo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_RegistroMotivoLabel" aria-hidden="true" style="zoom: 80%;">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div id="titulo_RegistroMotivo" class="modal-header" style="color: #ffffff;">
	        <h1 class="modal-title fs-5" id="modal_RegistroMotivoLabel">Registrar Moitivo</h1>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body">
					<div class="d-flex justify-content-center" style="padding: 5px;">
						<div class="col-md-8 col-sm-8 col-xs-8">
							<textarea id="proceso_estado_motivo" type="text" class="form-control col-md-12 col-xs-12" rows="2"></textarea>
						</div>
					</div>
	      </div>

	      <input id="hd_RegistroMotivo_CodLote" type="hidden">
	      <input id="hd_RegistroMotivo_Proceso" type="hidden">
	      <input id="hd_RegistroMotivo_Estado" type="hidden">

	      <div class="modal-footer">
	      	<div id="wt_registromotivo" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
						<img src="<?php echo $img_waiting ?>" style="width: 20px;">
						<label style="font-style: italic;"> Grabando datos...</label>
					</div>

	        <button type="button" class="btn btn-secondary wt_registromotivo_button" data-bs-dismiss="modal">Cerrar</button>
	        <button type="button" class="btn btn-primary wt_registromotivo_button" onclick="f_GrabarMotivo();">Grabar</button>
	      </div>
	    </div>
	  </div>
	</div>

	<!-- Referenciando a JQuery -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

	<!-- Select2 -->
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

	<!-- ECharts -->
	<script src="https://cdn.jsdelivr.net/npm/echarts@5.3.3/dist/echarts.min.js"></script>

	<!-- JSColor -->
	<script>
		// Here we can adjust defaults for all color pickers on page:
		jscolor.presets.default = {
			position: 'bottom',
			palette: [
				'#000000', '#7d7d7d', '#870014', '#ec1c23', '#ff7e26',
				'#fef100', '#22b14b', '#00a1e7', '#3f47cc', '#a349a4',
				'#ffffff', '#c3c3c3', '#b87957', '#feaec9', '#ffc80d',
				'#eee3af', '#b5e61d', '#99d9ea', '#7092be', '#c8bfe7',
			],
			//paletteCols: 12,
			hideOnPaletteClick: true,
		};
	</script>

	<!-- Referenciando auxiliares -->
	<?php include('global/auxiliares_js.php'); ?>

	<!-- Funciones de Inicio -->
	<script type="text/javascript">
		function f_Init() {
			// Genera menús
				f_GetMenuPrincipal();

			// Titulo de Pantalla
				$("#nv_titulo").html('| Gestión de Lotes');

			// Cargando listas generales
				f_LoadFiltroUnidades();

			// Carga el detalle de información
				f_LoadUnidadesPendientes();
		}
	</script>

	<!-- Seteando objetos Select2 -->
	<script type="text/javascript">
		$('#filtro_lote').select2({
			theme: "bootstrap-5",
			width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : '100%',
			placeholder: $( this ).data( 'placeholder' ),
			allowClear: true,
			minimumResultsForSearch: -1
		});
	</script>

	<!-- Funciones del Mapa -->
	<script type="text/javascript">
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

		// Carga las posiciones de todos los Lotes
			function f_ReloadPositions(){
				$("#map").html('');

				$.post("apis/backend.php", { accion: "get_OperacionesGestionLotes_GetPosition" }, function (data) {
					console.log("Cargando lotes:", data);

					data.forEach(lote => {
						let pos = getAbsolutePosition(lote.eje_x, lote.eje_y);

						let circle = $(`
						                <div class='circle' data-lote-id='${lote.cod_lote}' data-prov-ruc='${lote.PROVEEDORMINERO_RUC}' data-prov-rs='${lote.PROVEEDORMINERO_RAZONSOCIAL}' data-peso-lote='${lote.PESO_LOTE}'>
						                    <svg class="progress-ring" width="50" height="50">
						                        <circle class="progress-ring-circle" cx="25" cy="25" r="23.5"></circle>
						                    </svg>
						                    <label style="padding-top: 3px;">
						                        ${lote.cod_lote}
						                    </label>
						                </div>
						             `);

						circle.css({ left: pos.x + "px", top: pos.y + "px" });

						$("#map").append(circle);
					});
				});
			}

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

							savePosition(0, selectedLote.data("lote-id"), pos.x, pos.y);

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

		function savePosition(_init, cod_lote, eje_x, eje_y) {
			$.post("apis/backend.php", { accion: "grabar_OperacionesGestionLotes_SavePosition", init: _init, cod_lote: cod_lote, eje_x: eje_x, eje_y: eje_y },
				function (data) {
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
							const circle = $(`<div class='circle' data-lote-id='${loteId}'>
																	${loteId}
																</div>`);

							circle.css({ left: "10%", top: "10%" });

							$("#map").append(circle);

							makeDraggable(circle);
							savePosition(0, loteId, 10, 10); // Guardar posición inicial en BD
						}
					});
			});

		// Crea el efecto de selección para visualizar el estado actual del Lote
			let holdTimer;
			let isLongPress = false; // Bandera para detectar presión prolongada

			$(document).on("mousedown touchstart", ".circle", function (event) {
		    event.preventDefault(); // Evita conflictos en móviles
		    let circle = $(this).find(".progress-ring-circle");

		    isLongPress = false; // Resetear la bandera antes de iniciar

		    $(this).addClass("progress-active"); // Activa la animación

		    holdTimer = setTimeout(() => {
	        isLongPress = true; // Marca que fue una presión prolongada

	        console.log("Evento prolongado activado en:", $(this));

	        $(this).removeClass("progress-active"); // Remueve la animación tras 1.5s

	        // Llamando a la función que abre los Popover
	        	var cod_lote = $(this).data("lote-id");
	        	var proveedor_ruc = $(this).data("prov-ruc");
	        	var proveedor_razonsocial = $(this).data("prov-rs");
	        	var peso_inicio = $(this).data("peso-lote").split('|')[0];
	        	var peso_fin = $(this).data("peso-lote").split('|')[1];

	        	f_GetInfoLoteCancha(cod_lote, proveedor_ruc, proveedor_razonsocial, peso_inicio, peso_fin);

		    }, 1500); // 1.5 segundos de espera
			});

			$(document).on("mouseup mouseleave touchend touchcancel", ".circle", function () {
		    clearTimeout(holdTimer);

		    $(this).removeClass("progress-active"); // Cancela el progreso si suelta antes
			});

		// Evitar que se dispare el click si hubo un "long press"
			$(document).on("click", ".circle", function (event) {
		    // Acción normal del click si no fue una presión prolongada
			    if (selectedLote) {
		        selectedLote.removeClass("selected");
			    }

		    selectedLote = $(this);
		    selectedLote.addClass("selected");

		    if (isLongPress) {
	        selectedLote.removeClass("selected");
	        selectedLote = null;
		    }
			});

		// Bloquear el menú emergente en los círculos
			$(document).on("contextmenu selectstart", ".circle", function (event) {
		    event.preventDefault();
			});

		// Recargar Mapa
			f_ReloadPositions(); // Carga inicial de Lotes
			ajustarMapa(); // Ajuste inicial

			$(window).resize(ajustarMapa); // Ajuste dinámico cuando cambia la orientación
	</script>

	<!-- Funciones Principales -->
	<script type="text/javascript">
		function f_LoadFiltroUnidades(){
			// Obteniendo filtros


			// Cargando clientes
				$("#filtro_unidades").html('');

				$.post( "apis/backend.php", { accion: "get_ListaUnidadesxFechas" }, 
					function( data ) {
						if(data.estado == 1){
							$("#filtro_unidades").html(data.html);
						}

					}, "json");
		}

		function f_LoadUnidadesPendientes() {
			// Limpiar el div antes de insertar nuevas tablas
			$("#div_ListaUnidades").html('');

			$.post("apis/backend.php", { accion: "get_OperacionesGestionLotes_UnidadesPendientes" }, function(data) {
				if (data.estado == 1) {
					let unidadesProcesadas = {}; // Objeto para almacenar unidades y sus lotes

					// Recorrer los resultados del backend
						$.each(data.res, function(key, val) {
							let unidad = val.placa; // Identificador de la unidad (placa)
							let codigoLote = val.COD_LOTE + '|' + val.ID_TIPOVEHICULO + '|' + val.ID_TIPOCARGA + '|' + val.TIPO_VEHICULO + '|' + val.TIPO_CARGA + '|' + val.PESO_INICIO + '|' + val.PESO_FIN; // Código de Lote, Tipo de Vehículo y Carga
							
							// Si la unidad no ha sido procesada, inicializar su estructura
								if (!unidadesProcesadas[unidad]) {
										unidadesProcesadas[unidad] = {
												detalles: val, // Guardamos los detalles de la unidad
												lotes: [] // Creamos un array vacío para sus lotes
										};
								}

							// Agregamos el lote a la unidad correspondiente
								unidadesProcesadas[unidad].lotes.push(codigoLote);
						});

					// Generar el HTML dinámicamente
						let _html = '';
						var colores_procesos = '';

						$.each(unidadesProcesadas, function(unidad, dataUnidad) {
							_html += '<table class="table table-bordered table-hover" style="margin-bottom: -10px;">';

							// Arma cabecera
								_html += '	<thead>';
								_html += '		<tr style="font-size: 12px;">';
								_html += '			<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; border-top-right-radius: 15px;">';
								_html += '				' + unidad + ((dataUnidad.detalles.PLACA2.trim().length == 0) ? '' : ' / ' + dataUnidad.detalles.PLACA2.trim());
								_html += '			</th>';
								_html += '		</tr>';
								_html += '	</thead>';

							// Arma body
								_html += '	<tbody>';

								// Agregar filas para cada lote de la unidad
									$.each(dataUnidad.lotes, function(index, lote) {
										_html += '		<tr style="font-size: 13px; cursor: pointer;">';
										_html += '			<td rowspan="2" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; width: 40px;">';
										_html += '				' + parseInt(index + 1);
										_html += '			</td>';
										_html += '			<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';
										_html += '				' + lote.split('|')[0] + ((lote.split('|')[6] == 0) ? '' : '<label style="margin-left: 5px; color: #337ab7;">(' + f_RedondearDecimales((lote.split('|')[5] - lote.split('|')[6]), 2) + ' Tn)');
										_html += '			</td>';
										_html += '			<td rowspan="2" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;" onclick="f_GetInfoLote(' + "'" + lote.split('|')[0] + "', " + lote.split('|')[1] + ', ' + lote.split('|')[2] + ", '" + lote.split('|')[3] + "', '" + lote.split('|')[4] + "'" + ')">';
										_html += '				<img src="<?php echo $img_select; ?>" style="width: 25px;">';
										_html += '			</td>';
										_html += '		</tr>';

										// Obteniendo y definiendo los colores de procesos de cada Lote
											f_GetColoresProcesos(lote.split('|')[0]);

										_html += '		<tr style="font-size: 13px; cursor: pointer;">';
										_html += '			<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; width: 40px;">';
										_html += '				<div class="d-flex">';
										_html += '					<div class="d-flex">';
										_html += '						<div id="div_procesocolor_1_' + lote.split('|')[0] + '" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #E6E6E6; width: 23px; height: 10px; margin-right: 3px;">';
										_html += '						</div>';
										_html += '						<div id="div_procesocolor_2_' + lote.split('|')[0] + '" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #E6E6E6; width: 23px; height: 10px; margin-right: 3px;">';
										_html += '						</div>';
										_html += '						<div id="div_procesocolor_3_' + lote.split('|')[0] + '" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #E6E6E6; width: 23px; height: 10px; margin-right: 3px;">';
										_html += '						</div>';
										_html += '						<div id="div_procesocolor_4_' + lote.split('|')[0] + '" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #E6E6E6; width: 23px; height: 10px; margin-right: 3px;">';
										_html += '						</div>';
										_html += '						<div id="div_procesocolor_5_' + lote.split('|')[0] + '" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #E6E6E6; width: 23px; height: 10px; margin-right: 3px;">';
										_html += '						</div>';
										_html += '						<div id="div_procesocolor_6_' + lote.split('|')[0] + '" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #E6E6E6; width: 23px; height: 10px;">';
										_html += '						</div>';
										_html += '					</div>';
										_html += '				</div>';
										_html += '			</td>';
										_html += '		</tr>';
									});

								_html += '   </tbody>';


							_html += '</table><br>';
						});

					// Insertar el contenido generado en el div
						$("#div_ListaUnidades").html(_html);
				}
				else {
					$("#div_ListaUnidades").html('<p>No se encontraron unidades pendientes.</p>');
				}
			}, "json");
		}

		function f_GetInfoLote(_cod_lote, _id_tipovehiculo, _id_tipocarga, _tipo_vehiculo, _tipo_carga){
			// Seteando Título
				var sub_titulo = '<label style="margin-left: 5px; color: #212529; font-weight: 500;">';
				sub_titulo += '| Tipo Vehículo: <span style="font-weight: bold; color: #337ab7; margin-right: 5px;">' + ((_tipo_vehiculo.length == 0) ? '-- No definido --' : _tipo_vehiculo) + '</span>';
				sub_titulo += '| Tipo Carga: <span style="font-weight: bold; color: ' + ((_tipo_carga.length == 0) ? '#FF5F5D' : '#337ab7') + ';">' + ((_tipo_carga.length == 0) ? '-- No definido --' : _tipo_carga) + '</span>';
				sub_titulo += '</label>';

				sub_titulo += '<input id="tit_lote" type="hidden" value="' + _cod_lote + '">'; // Agregando variable Hidden para el Lote

				$("#modal_GestionLotesLabel").html(_cod_lote + sub_titulo);

			// Limpiando objetos
				$("#div_historial").html('');

			// Seteando variables Hidden
				$("#hd_codlote").val(_cod_lote);
				$("#hd_idtipovehiculo").val(_id_tipovehiculo);
				$("#hd_idtipocarga").val(_id_tipocarga);

			// Ocultando botones de acción
				$("#div_button_play").hide();
				$("#div_button_pause").hide();
				$("#div_button_finalizar").hide();
				$("#div_button_stop").hide();

			// Abriendo modal
				f_OpenModal('modal_GestionLotes');

			// Remueve la clase de selección en todos los círculos
				document.querySelectorAll('.modal-circle').forEach(img => {
					img.classList.remove('modal-selected-circle');
				});

			// Verificando el último Proceso del Lote seleccionado
				var id_proceso = 0;
				var html = '';

				$("#tbl_Recursos").html('');

				$.post( "apis/backend.php", { accion: "get_OperacionesGestionLotes_EstadoLoteProceso", cod_lote: _cod_lote }, 
					function( data ) {
						if(data.estado == 1){
							// Selecciona el Proceso
								var _objeto = $("#td_idproceso_" + data.id_proceso)[0];

								f_ProcesoSelected(_objeto, data.id_proceso, data.estado_proceso);
						}
						else{
							html += '<tr style="cursor: pointer; font-size: 14px;">';
							html += '	 <td colspan="3" style="background-color: #FF5F5D; text-align: center; color: #ffffff;">';
							html += '	 	 Aun no ha iniciado la gestión de este lote.<br><label style="font-weight: bold;">-> Seleccione un Proceso para iniciar la gestión. <-</label>';
							html += '	 </td>';
							html += '</tr>';

							$("#tbl_Recursos").html(html);
						}

					}, "json");
		}

		function f_ProcesoSelected(_td_Element, _id_proceso, _estado_proceso) {
			// Remueve la clase de selección en todos los círculos
				document.querySelectorAll('.modal-circle').forEach(img => {
					img.classList.remove('modal-selected-circle');
				});

			// Encuentra la imagen dentro del <td> y le agrega la clase de selección
				let img = _td_Element.querySelector('.modal-circle');

				if (img) {
					img.classList.add('modal-selected-circle');
				}

			// Setea variables Hidden
				$("#hd_idproceso").val(_id_proceso);

			// Ocultando botones de acción
				$("#div_button_play").hide();
				$("#div_button_pause").hide();
				$("#div_button_finalizar").hide();
				$("#div_button_stop").hide();

				if (_estado_proceso == undefined){
					$("#div_button_play").hide();
				}

			// Obteniendo la información del Lote para la selección de recursos
				var cod_lote = $("#hd_codlote").val();
				var id_tipovehiculo = (($("#hd_idtipovehiculo").val().length == 0) ? '0' : $("#hd_idtipovehiculo").val());
				var id_tipocarga = (($("#hd_idtipocarga").val().length == 0) ? '0' : $("#hd_idtipocarga").val());

				$("#tbl_Recursos").html('');
				
				$.post( "apis/backend.php", { accion: "get_OperacionesGestionLotes_InfoRecursos", cod_lote: cod_lote, id_proceso: _id_proceso, id_tipovehiculo: id_tipovehiculo, id_tipocarga: id_tipocarga }, 
					function( data ) {
						$("#tbl_Recursos").html(data.html);

						// Seteando botones de acción
							$("#div_button_play").hide();
							$("#div_button_pause").hide();
							$("#div_button_finalizar").hide();
							$("#div_button_stop").hide();

							if(data.estado == ''){
								$("#div_button_play").show();
							}

							if(data.estado == 'I'){
								$("#div_button_pause").show();
								$("#div_button_finalizar").show();
								$("#div_button_stop").show();
							}

							if(data.estado == 'F'){
								$("#div_button_play").show();
							}

							if(data.estado == 'P'){
								$("#div_button_play").show();
								$("#div_button_stop").show();
							}

							if(data.estado == 'C'){
								$("#div_button_play").show();
							}

					}, "json");

			// Carga el historial de acciones por Proceso
				f_GetHistorialLote(cod_lote);
		}

		function f_GetHistorialLote(_cod_lote){
			$("#div_historial").html('');

			$.post( "apis/backend.php", { accion: "get_OperacionesGestionLotes_HistorialLote", cod_lote: _cod_lote }, 
				function( data ) {
					if(data.estado == 1){
						$("#div_historial").html(data.html);
					}

				}, "json");
		}

		function f_ShowHistorial(_id_proceso){
			if ($(".tr_ProcesoHistorial_" + _id_proceso).is(":visible")) {
		    $(".tr_ProcesoHistorial_" + _id_proceso).fadeOut(400);

		    setTimeout(() => {
			    $("#img_ProcesoHistorial_" + _id_proceso).attr('src', '<?php echo $img_button_down ?>');
				}, 400);
			}
			else {
		    $(".tr_ProcesoHistorial_" + _id_proceso).fadeIn(400);

		    setTimeout(() => {
			    $("#img_ProcesoHistorial_" + _id_proceso).attr('src', '<?php echo $img_button_up ?>');
				}, 400);
			}
		}

		function f_GetColoresProcesos(_cod_lote){
			var colores_procesos = '';

			$.post( "apis/backend.php", { accion: "get_OperacionesGestionLotes_ColoresProcesos", cod_lote: _cod_lote }, 
				function( data ) {
					if(data.estado == 1){
						colores_procesos = data.colores_procesos;

						// Seteando colores
							colores_procesos = colores_procesos.split('|');

							colores_procesos.forEach(function(color_proceso, indice){
								var cod_lote = color_proceso.split(';')[2];
								var id_proceso = color_proceso.split(';')[0];
								var color = color_proceso.split(';')[1];

								if (cod_lote != 'x'){
									$("#div_procesocolor_" + id_proceso + "_" + cod_lote).css("background-color", color);
								}
							});
					}

				}, "json");
		}

		function f_GetInfoLoteCancha(_cod_lote, _proveedor_ruc, _proveedor_razonsocial, _peso_inicio, _peso_fin){
      var div = document.querySelector(`div[data-lote-id="${_cod_lote}"]`);

      // Seteando Peso Neto
      	var peso_neto = 0;

      	if (_peso_fin > 0){
      		peso_neto = parseFloat((_peso_inicio - _peso_fin) / 1000).toFixed(2);
      	}

      var html_content = `<div style="padding: 10px;">
														Proveedor Minero: <strong>${_proveedor_ruc}</strong>
													</div>

													<div style="padding: 10px;">
														<strong>${_proveedor_razonsocial}</strong>
													</div>

													<div style="padding: 10px; margin-top: -10px;">
														Peso Neto (Tn): <strong>${peso_neto}</strong>
													</div>`;

      var popover = new bootstrap.Popover(div, {
          html: true,
          title: `
          				<div class="d-flex" style="padding: 0px;">
	          				<div class="d-flex flex-fill" style="font-size: 14px;">
	          					<div class="p-2 flex-fill" style="width: 50px;">
	          						Información de Lote
          						</div>

          						<div class="p-2 close-popover" data-close style="cursor: pointer;">
	          						x
          						</div>
	        					</div>
	        				</div>
          			 `,
          content: html_content,
          placement: 'bottom'
      });

      popover.show(); // Mostrar el popover
		}

		// Cerrar Popover
			$(document).on("click", ".close-popover", function() {
		    let popover = $(this).closest(".popover"); // Encuentra el popover padre
		    let popoverId = popover.attr("id"); // Obtiene el ID del popover

		    if (popoverId) {
		    	let relatedElement = $(`[aria-describedby="${popoverId}"]`);

	        relatedElement.removeAttr("aria-describedby"); // Remueve el atributo
	        relatedElement.popover("dispose"); // Elimina el popover completamente
		    }

		    popover.remove(); // Elimina el popover del DOM
			});
	</script>

	<!-- Funciones Secundarias -->
	<script type="text/javascript">
		function f_LoadingPlantas(_is_show) {
			if (_is_show == 1) {
				$("#wt_plantas").show();
			} else {
				$("#wt_plantas").hide();
			}
		}

		function f_LoadingLotes(_is_show) {
			if (_is_show == 1) {
				$("#wt_loadinglotes").show();
			} else {
				$("#wt_loadinglotes").hide();
			}
		}

		function f_LoadingLotes_Distribucion(_is_show) {
			if (_is_show == 1) {
				$("#wt_loadinglotesdistribuciones").show();
			} else {
				$("#wt_loadinglotesdistribuciones").hide();
			}
		}

		function f_LoadingProgramacion(_is_show) {
			if (_is_show == 1) {
				$("#wt_programacion").show();
			} else {
				$("#wt_programacion").hide();
			}
		}

		function f_LoadingDistribucion(_is_show) {
			if (_is_show == 1) {
				$("#wt_distribucion").show();
			} else {
				$("#wt_distribucion").hide();
			}
		}

		function f_HideListaProgramaciones(_x) {
			if (_x == 1) {
				$("#div_plantas").hide();
				$("#div_detalle").width('100%');

				f_CerrarDiv('C', 'div_ShowListaProgramaciones');
				f_CerrarDiv('A', 'div_HideListaProgramaciones');
			} else {
				$("#div_plantas").show();
				$("#div_detalle").width('');

				f_CerrarDiv('A', 'div_ShowListaProgramaciones');
				f_CerrarDiv('C', 'div_HideListaProgramaciones');
			}
		}

		function f_SelectChkLotes() {
			var is_checked = false;

			// Obteniendo valor del checkbox
			if ($("#th_Chk").prop('checked')) {
				is_checked = true;
			}

			// Recorre solo las filas visibles
			var d = 1;

			$("#tbl_FiltroLotes tr").filter(function() {
				$("#chk_lote_" + d).prop('checked', is_checked);

				d++;
			});

			// Cuenta los seleccionados
			f_CountSelected();
		}

		function f_CountSelected() {
			var d = 1;
			var _count = 0;
			var _total_tmh = 0;
			var _total_tms = 0;

			$("#tbl_FiltroLotes tr").filter(function() {
				if ($("#chk_lote_" + d).prop('checked')) {
					_total_tmh += parseFloat($(this).find("td:eq(6)").text());
					_total_tms += ((isNaN($(this).find("td:eq(7)").text().trim())) ? 0 : parseFloat($(this).find("td:eq(7)").text()));

					_count++;
				}

				d++;
			});

			// Setea el conteo de seleccionados
			$("#lbl_countlotes").html(_count);

			// Setea el total de Netos
			$("#lbl_totaltmh").html(f_RedondearDecimales(_total_tmh, 3));
			$("#lbl_totaltms").html(f_RedondearDecimales(_total_tms, 3));
		}

		function f_SelectChkLotes_Distribucion() {
			var is_checked = false;

			// Obteniendo valor del checkbox
			if ($("#th_Chk_Distribucion").prop('checked')) {
				is_checked = true;
			}

			// Recorre solo las filas visibles
			var d = 1;

			$("#tbl_FiltroLotes_Distribucion tr").filter(function() {
				$("#chk_lotedistribucion_" + d).prop('checked', is_checked);

				d++;
			});

			// Cuenta los seleccionados
			f_CountSelected_Distribucion();
		}

		function f_CountSelected_Distribucion() {
			var d = 1;
			var _count = 0;
			var _total_distribuido = 0;

			$("#tbl_FiltroLotes_Distribucion tr").filter(function() {
				if ($("#chk_lotedistribucion_" + d).prop('checked')) {
					_total_distribuido += (($("#tmh_distribuido_" + d).val().length == 0) ? 0 : parseFloat($("#tmh_distribuido_" + d).val()));

					_count++;
				}

				d++;
			});

			// Setea el conteo de seleccionados
			$("#lbl_countlotes_Distribucion").html(_count);

			// Setea el total de Netos
			$("#lbl_totaltmh_Distribuido").html(f_RedondearDecimales(_total_distribuido, 3));
		}

		function f_LoadingGrabarProgramacion(_is_show) {
			if (_is_show == 1) {
				$("#wt_grabarprogramacion").show();

				$(".wt_grabarprogramacion_button").prop('disabled', true);
			} else {
				$("#wt_grabarprogramacion").hide();

				$(".wt_grabarprogramacion_button").prop('disabled', false);
			}
		}

		function f_LoadingGrabarProgramacion_Distribucion(_is_show) {
			if (_is_show == 1) {
				$("#wt_grabardistribucion").show();

				$(".wt_grabardistribucion_button").prop('disabled', true);
			} else {
				$("#wt_grabardistribucion").hide();

				$(".wt_grabardistribucion_button").prop('disabled', false);
			}
		}

		function f_SetCapacidad() {
			var tipo_vehiculo = $("#tipo_unidad").val().split('|')[0];
			var tiene_carreta = (($("#tipo_unidad").val().length == 0) ? 0 : $("#tipo_unidad").val().split('|')[1]);
			var capacidad_unidad = '';

			// Oculta Placa 2
			$("#div_placa2").hide();

			// Determinando si el tipo de unidad tiene carreta
			if (tiene_carreta == 1) {
				$("#div_placa2").show();
			}

			// Obteniendo la Capacidad
			if (tiene_carreta == 0) {
				if ($("#distribucion_unidad").val() != null) {
					if ($("#distribucion_unidad").val().length > 0) {
						var capacidad_unidad = $("#distribucion_unidad").val().split('|')[1];

						if (capacidad_unidad.trim().length == 0 || capacidad_unidad == 0) {
							capacidad_unidad = 'Sin Asignar...';
						} else {
							capacidad_unidad = f_RedondearDecimales((capacidad_unidad / 1000), 3);
						}
					}
				}
			} else {
				if ($("#distribucion_unidad2").val() != null) {
					if ($("#distribucion_unidad2").val().length > 0) {
						var capacidad_unidad = $("#distribucion_unidad2").val().split('|')[1];

						if (capacidad_unidad.trim().length == 0 || capacidad_unidad == 0) {
							capacidad_unidad = 'Sin Asignar...';
						} else {
							capacidad_unidad = f_RedondearDecimales((capacidad_unidad / 1000), 3);
						}
					}
				}
			}

			$("#unidad_capacidad").val(capacidad_unidad);
		}

		function f_LoadingGrabar_CierrePrograma(_is_show) {
			if (_is_show == 1) {
				$("#wt_configuracionvehicular").show();

				$(".wt_configuracionvehicular_button").prop('disabled', true);
			} else {
				$("#wt_configuracionvehicular").hide();

				$(".wt_configuracionvehicular_button").prop('disabled', false);
			}
		}

		function f_LoadingGrabar_ConfiguracionVehicular(_is_show) {
			if (_is_show == 1) {
				$("#wt_configuracionvehicular").show();

				$(".wt_configuracionvehicular_button").prop('disabled', true);
			} else {
				$("#wt_configuracionvehicular").hide();

				$(".wt_configuracionvehicular_button").prop('disabled', false);
			}
		}

		function f_LoadingGrabar_RCI(_is_show) {
			if (_is_show == 1) {
				$("#wt_rci").show();

				$(".wt_rci_button").prop('disabled', true);
			} else {
				$("#wt_rci").hide();

				$(".wt_rci_button").prop('disabled', false);
			}
		}

		function f_LoadingConfirmarGuia(_is_show) {
			if (_is_show == 1) {
				$("#wt_confirmarguia").show();

				$(".wt_confirmarguia_button").prop('disabled', true);
			} else {
				$("#wt_confirmarguia").hide();

				$(".wt_confirmarguia_button").prop('disabled', false);
			}
		}

		function f_LoadingEditCodigoPlanta(_is_show) {
			if (_is_show == 1) {
				$("#wt_EditCodigoPlanta").show();

				$(".wt_EditCodigoPlanta_Button").prop('disabled', true);
			} else {
				$("#wt_EditCodigoPlanta").hide();

				$(".wt_EditCodigoPlanta_Button").prop('disabled', false);
			}
		}
	</script>

	<!-- Funciones de Grabación -->
	<script type="text/javascript">
		function f_ExecuteTask(_estado){
			var cod_lote = $("#hd_codlote").val();
			var id_proceso = $("#hd_idproceso").val();

			// Seteando mensaje de confirmación
				var msg_estado = '';

				if (_estado == 'I'){
					msg_estado = 'Iniciar';
				}

				if (_estado == 'F'){
					msg_estado = 'Finalizar';
				}

				if (_estado == 'P'){
					msg_estado = 'Pausar';
				}

				if (_estado == 'C'){
					msg_estado = 'Cancelar';
				}

				if (!confirm("¿Está seguro de " + msg_estado +" el proceso?")){
					return;
				}

			// Guarda el estado de Checks seleccionados
				let recursos_selected = [];

		    document.querySelectorAll("#tbl_Recursos input[type='checkbox']").forEach((checkbox) => {
	        if (checkbox.checked) {
            let id = checkbox.id; // Obtener el id del checkbox
            let numero = id.match(/\d+$/); // Extraer solo la parte numérica

            if (numero) {
              recursos_selected.push(parseInt(numero[0])); // Convertir a número y agregar al array
            }
	        }
		    });

		  // Solo para el caso de "Pausar" y "Cancelar"
				if (_estado == 'P' || _estado == 'C'){
					// Seteando objetos hidden
						$("#hd_RegistroMotivo_CodLote").val(cod_lote);
						$("#hd_RegistroMotivo_Proceso").val(id_proceso);
						$("#hd_RegistroMotivo_Estado").val(_estado);

					// Seteando título
						if (_estado == 'P'){
							$("#modal_RegistroMotivoLabel").html('Registro el Motivo de la Pausa');
							$("#titulo_RegistroMotivo").css('background-color', '#ffca18');
						}
						else{
							$("#modal_RegistroMotivoLabel").html('Registro el Motivo de la Cancelación');
							$("#titulo_RegistroMotivo").css('background-color', '#fe0000');
						}

					f_OpenModal('modal_RegistroMotivo');
				}
				else{
					$.post( "apis/backend.php", { accion: "grabar_OperacionesGestionLotes_ProcesosEstados", cod_lote: cod_lote, id_proceso: id_proceso, recursos_selected: recursos_selected, estado: _estado }, 
		        function( data ) {
		          if(data.estado == 1){
		          	// Seteando botones de acción
									$("#div_button_play").hide();
									$("#div_button_pause").hide();
									$("#div_button_finalizar").hide();
									$("#div_button_stop").hide();

									if(_estado == 'I'){
										$("#div_button_pause").show();
										$("#div_button_finalizar").show();
										$("#div_button_stop").show();
									}

									if(_estado == 'F'){
										$("#div_button_play").show();
									}

									if(_estado == 'P'){
										$("#div_button_play").show();
										$("#div_button_stop").show();
									}

									if(_estado == 'C'){
										$("#div_button_play").show();
									}

								// Ubicando lote en mapa
									var cod_lote = $("#hd_codlote").val();

									savePosition(1, cod_lote, 0, 0);

								// Cargando historial
		            	f_GetHistorialLote(cod_lote);

		            // Actualizando color de estado
		            	f_GetColoresProcesos(cod_lote);

		            // Recargando Circulos
		            	f_ReloadPositions();
		          }

		        }, "json");
				}
		}

		function f_GrabarMotivo(){
			// Recupera variables hidden
				var cod_lote = $("#hd_RegistroMotivo_CodLote").val();
				var id_proceso = $("#hd_RegistroMotivo_Proceso").val();
				var estado = $("#hd_RegistroMotivo_Estado").val();

			// Obteniendo Motivo
				var motivo = $("#proceso_estado_motivo").val().trim();

			// Validando datos
				if (motivo == null){
          alert("Debe registrar el Motivo.");

          $("#proceso_estado_motivo").val('');

          return;
        }
        if (motivo.length == 0){
          alert("Debe registrar el Motivo.");

          $("#proceso_estado_motivo").val('');

          return;
        }

			// Grabando datos
				$.post( "apis/backend.php", { accion: "grabar_OperacionesGestionLotes_ProcesosEstados_Motivo", cod_lote: cod_lote, id_proceso: id_proceso, estado: estado, motivo: motivo }, 
	        function( data ) {
	          if(data.estado == 1){
	          	// Cerrar Modal
	          		f_cerrarModal('modal_RegistroMotivo');

	          	// Seteando botones de acción
								$("#div_button_play").hide();
								$("#div_button_pause").hide();
								$("#div_button_finalizar").hide();
								$("#div_button_stop").hide();

								if(estado == 'I'){
									$("#div_button_pause").show();
									$("#div_button_finalizar").show();
									$("#div_button_stop").show();
								}

								if(estado == 'F'){
									$("#div_button_play").show();
								}

								if(estado == 'P'){
									$("#div_button_play").show();
									$("#div_button_stop").show();
								}

								if(estado == 'C'){
									$("#div_button_play").show();
								}

							// Cargando historial
	            	f_GetHistorialLote(cod_lote);

	            // Actualizando color de estado
	            	f_GetColoresProcesos(cod_lote);

	            // Recargando Circulos
	            	f_ReloadPositions();
	          }

	        }, "json");
		}
	</script>

	<!-- Funciones de Menús -->
	<script type="text/javascript">
		function f_SetDimension() {
			if (screen.width < 500) {
				$("#offcanvasExample").css('width', '60%');

			}
		}
	</script>

	<!-- Funcion Default -->
	<script type="text/javascript">

	</script>
</body>

</html>