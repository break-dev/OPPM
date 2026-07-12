<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');

	require_once 'dompdf/autoload.inc.php';

	use Dompdf\Dompdf;
	use Dompdf\Options;

// ini_set('display_errors', 1);
// error_reporting(E_ALL);
error_reporting(0);
ini_set('display_errors', 0);
ini_set('display_startuo_errors', 0);

	$serie_guia = $_GET["a"];
	$numero_guia = $_GET["b"];
	$id_remitente = $_GET["c"];
	$id_transportista = $_GET["d"];
	$guia_fecha = $_GET["e"];

	// 1. Obteniendo datos de la guía
		$nom_archivo = 'Remitente';
		$tipo_guia = mb_strtoupper($nom_archivo);

		$q_datos = "SELECT DISTINCT
											 V.guiaremitente_serie,
											 V.guiaremitente_numero,
											 V.guiatransportista_serie,
											 V.guiatransportista_numero,
											 V.guias_fecha,
											 V.guias_puntopartida,
											 V.guias_puntodestino,
											 V.balanza_placa,
											 V.unidad_idmarca,
											 UPPER(U.descripcion) AS MARCA,
											 V.unidad_constanciamtc,
											 V.guias_idchofer,
											 C.dni_licencia,
											 C.licencia_conducir,
											 UPPER(C.nombres) AS CONDUCTOR,
											 V.guias_destinatario,
											 ET.documento AS TRANSPORTISTA_RUC,
											 UPPER(ET.razon_social) AS TRANSPORTISTA_RAZONSOCIAL,
											 /*
											 UPPER(PM.proveedorminero_concesion) AS CONCESION,
											 UPPER(PM.proveedorminero_codigounico) AS CODIGO_UNICO,
											 UPPER(PM.proveedorminero_codigounico) AS CODIGO_UNICO,
											 */
											 UPPER(CS.descripcion) AS CONCESION,
											 UPPER(CS.codigo_unico) AS CODIGO_UNICO,
											 UPPER(V.guias_motivotraslado) AS MOTIVO_TRASLADO
								  FROM despachos_primertramo_validaciondatos V
								  		 LEFT JOIN tbconfig_unidadesmarca U ON V.unidad_idmarca = U.Id
								  		 LEFT JOIN tbconfig_conductores C ON V.guias_idchofer = C.Id
								  		 LEFT JOIN transporte T ON V.balanza_placa = T.cplaca
								  		 INNER JOIN tb_clientes ET ON T.id_Transportista = ET.Id
								  		 INNER JOIN tb_clientes PM ON V.lote_id_proveedorminero = PM.Id
								  		 INNER JOIN tbconfig_proveedoresmineros_concesion CS ON V.lote_id_proveedorminero_concesion = CS.Id
								 WHERE MD5(V.guiaremitente_serie) = '".$serie_guia."'
									 AND MD5(V.guiaremitente_numero) = '".$numero_guia."'
									 AND V.lote_id_proveedorminero = ".$id_remitente."
									 AND T.id_Transportista = ".$id_transportista."
									 AND V.guias_fecha = '".$guia_fecha."'";

		if ($res_datos = mysqli_query($enlace, $q_datos)){
      if (mysqli_num_rows($res_datos) > 0) {
        while($row_datos = mysqli_fetch_array($res_datos)){
        	$guiaR_serie = $row_datos["guiaremitente_serie"];
					$guiaR_numero = $row_datos["guiaremitente_numero"];
					$guiaR = $guiaR_serie.'-'.$guiaR_numero;

					$guiaT_serie = $row_datos["guiatransportista_serie"];
					$guiaT_numero = $row_datos["guiatransportista_numero"];
					$guiaT = $guiaT_serie.'-'.$guiaT_numero;

					$nom_archivo_guia = $guiaR;

					$fecha_guia = $row_datos["guias_fecha"];
					$guias_puntopartida = $row_datos["guias_puntopartida"];
					$guias_puntodestino = $row_datos["guias_puntodestino"];
					$placa_1 = $row_datos["balanza_placa"];
					$marca_1 = $row_datos["MARCA"];
					$constancia_mtc_1 = mb_strtoupper($row_datos["unidad_constanciamtc"]);
					$conductor_dni = $row_datos["dni_licencia"];
					$licencia_conducir = $row_datos["licencia_conducir"];
					$conductor_nombres = $row_datos["CONDUCTOR"];
					$destinatario = $row_datos["guias_destinatario"];
					$transportista_ruc = $row_datos["TRANSPORTISTA_RUC"];
					$transportista_razonsocial = $row_datos["TRANSPORTISTA_RAZONSOCIAL"];
					$concesion = $row_datos["CONCESION"];
					$codigo_unico = $row_datos["CODIGO_UNICO"];
					$motivo_traslado = $row_datos["MOTIVO_TRASLADO"];
        }
      }
    }

	// 1. Arma la estructura de Cabeceera
    $html = '	<!DOCTYPE html>
						 	<html lang="es">
								<head>
									<title>Modelo de Guía de '.$nom_archivo.' - '.$nom_archivo_guia.'</title>

									<style>
										@font-face {
									    font-family : "AgencyFB";
									    src: url("fonts/AgencyFB.ttf");
										}

										@font-face {
									    font-family : "AgencyFBb";
									    src: url("fonts/AgencyFB-Bold.ttf");
										}

										.fstyle{
											font: AgencyFB;
										}

										.fstyleb{
											font: AgencyFBb;
										}

										html, body{
											font-family: Arial;
											margin: 0;
											padding: -5;
											margin-bottom: -15px;
											font-size: 14px;
										}

										@page{
											margin: 0;
											pading: 0;
										}
									</style>
								</head>

								<body style="margin-left: 10px; margin-right: 10px;">
									<div class="row">
										<table style="width: 100%; margin-top: 60px;">
											<tr style="font-size: 14px;">
												<td style="text-align: center; vertical-align: bottom; width: 60%; height: 40px;">
													<table style="width: 100%; margin-top: 0px;">
														<tr>
															<td style="text-align: center; vertical-align: middle; width: 100%; padding: 0px;">
																<table style="width: 100%; border-spacing: 0px;">
																	<tr>
																		<td style="font-family: AgencyFBb; font-size: 14px; width: 60%;">
																			<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #4A4F59; color: #ffffff; padding: 5px;">
																				FECHA EMISION
																			</div>
																		</td>

																		<td style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; font-family: AgencyFBb; font-size: 14px; padding: 5px; text-align: center; width: 40%;">
																			'.$fecha_guia.'
																		</td>
																	</tr>
																</table>
															</td>
														</tr>

														<tr>
															<td style="text-align: center; vertical-align: middle; width: 100%; padding: 0px;">
																<table style="width: 100%; border-spacing: 0px;">
																	<tr>
																		<td style="font-family: AgencyFBb; font-size: 14px; width: 60%;">
																			<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #4A4F59; color: #ffffff; padding: 5px;">
																				FECHA INICIO TRASLADO
																			</div>
																		</td>

																		<td style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; font-family: AgencyFBb; font-size: 14px; padding: 5px; text-align: center; width: 40%;">
																			'.$fecha_guia.'
																		</td>
																	</tr>
																</table>
															</td>
														</tr>

														<tr>
															<td style="text-align: center; vertical-align: middle; width: 100%; padding: 0px;">
																<table style="width: 100%; border-spacing: 0px;">
																	<tr>
																		<td style="font-family: AgencyFBb; font-size: 14px; width: 60%;">
																			<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #4A4F59; color: #ffffff; padding: 5px;">
																				FECHA DE ENTREGA DE BIENES AL TRANSPORTISTA
																			</div>
																		</td>

																		<td style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; font-family: AgencyFBb; font-size: 14px; padding: 5px; text-align: center; width: 40%;">
																			'.$fecha_guia.'
																		</td>
																	</tr>
																</table>
															</td>
														</tr>
													</table>
												</td>

												<td style="text-align: center; vertical-align: middle; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; width: 40%; padding: 0px;">
													<div style="font-size: 20px; font-family: AgencyFBb;">
														GUIA REMISIÓN
													</div>

													<div style="background-color: #4A4F59; color: #ffffff; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding-bottom: 5px;">
														<label style="font-size: 25px; font-family: AgencyFBb;">
															'.$tipo_guia.'
														</label>
													</div>

													<div style="margin-top: -5px;">
														<label style="font-size: 20px; font-family: AgencyFBb;">
															'.$nom_archivo_guia.'
														</label>
													</div>
												</td>
											</tr>
										</table>
									</div>

									<div class="row">
										<table style="width: 100%;">
											<tr style="font-size: 14px;">
												<td style="text-align: center; vertical-align: top; width: 50%;">
													<table style="width: 100%;">
														<tr>
															<td style="text-align: center; vertical-align: middle; width: 50%; padding: 0px;">
																<table style="width: 100%; border-spacing: 0px;">
																	<tr>
																		<td style="font-family: AgencyFBb; font-size: 14px; padding: 0px;">
																			<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #4A4F59; color: #ffffff; padding: 5px; text-align: center;">
																				DIRECCIÓN DE PUNTO DE PARTIDA
																			</div>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>

														<tr>
															<td style="text-align: center; vertical-align: middle; width: 50%; padding: 0px;">
																<table style="width: 100%; border-spacing: 0px;">
																	<tr>
																		<td style="vertical-align: middle; font-family: AgencyFBb; font-size: 14px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 5px; text-align: center; height: 45px; vertical-align: middle;">
																			'.$guias_puntopartida.'
																		</td>
																	</tr>
																</table>
															</td>
														</tr>
													</table>
												</td>

												<td style="text-align: center; vertical-align: top; width: 50%;">
													<table style="width: 100%;">
														<tr>
															<td style="text-align: center; vertical-align: middle; width: 50%; padding: 0px;">
																<table style="width: 100%; border-spacing: 0px;">
																	<tr>
																		<td style="font-family: AgencyFBb; font-size: 14px; padding: 0px;">
																			<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #4A4F59; color: #ffffff; padding: 5px; text-align: center;">
																				DIRECCIÓN DE PUNTO DE LLEGADA
																			</div>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>

														<tr>
															<td style="text-align: center; vertical-align: middle; width: 50%; padding: 0px;">
																<table style="width: 100%; border-spacing: 0px;">
																	<tr>
																		<td style="vertical-align: middle; font-family: AgencyFBb; font-size: 14px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 5px; text-align: center; height: 45px; vertical-align: middle;">
																			'.$guias_puntodestino.'
																		</td>
																	</tr>
																</table>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</div>

									<div class="row">
										<table style="width: 100%;">
											<tr style="font-size: 14px;">
												<td style="text-align: center; vertical-align: top; width: 50%;">
													<table style="width: 100%;">
														<tr>
															<td colspan="2" style="text-align: center; vertical-align: middle; width: 50%; padding: 0px;">
																<table style="width: 100%; border-spacing: 0px;">
																	<tr>
																		<td style="font-family: AgencyFBb; font-size: 14px; padding: 0px;">
																			<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #4A4F59; color: #ffffff; padding: 5px; text-align: center;">
																				UNIDAD DE TRANSPORTE Y CONDUCTOR
																			</div>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>

														<tr>
															<td style="width: 30%; font-family: AgencyFBb; font-size: 14px; width: 50%;">
																<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #4A4F59; color: #ffffff; padding: 5px;">
																	MARCA
																</div>
															</td>

															<td style="width: 70%; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; font-family: AgencyFBb; font-size: 14px; padding: 5px; text-align: center;">
																'.$marca_1.'
															</td>
														</tr>

														<tr>
															<td style="width: 30%; font-family: AgencyFBb; font-size: 14px; width: 50%;">
																<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #4A4F59; color: #ffffff; padding: 5px;">
																	PLACA
																</div>
															</td>

															<td style="width: 70%; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; font-family: AgencyFBb; font-size: 14px; padding: 5px; text-align: center;">
																'.$placa_1.'
															</td>
														</tr>

														<tr>
															<td style="width: 30%; font-family: AgencyFBb; font-size: 14px; width: 50%;">
																<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #4A4F59; color: #ffffff; padding: 5px;">
																	N° CONSTANCIA MTC
																</div>
															</td>

															<td style="width: 70%; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; font-family: AgencyFBb; font-size: 14px; padding: 5px; text-align: center;">
																'.$constancia_mtc_1.'
															</td>
														</tr>

														<tr>
															<td style="width: 30%; font-family: AgencyFBb; font-size: 14px; width: 50%;">
																<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #4A4F59; color: #ffffff; padding: 5px;">
																	CONDUCTOR
																</div>
															</td>

															<td style="width: 70%; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; font-family: AgencyFBb; font-size: 14px; padding: 5px; text-align: center;">
																'.$licencia_conducir.' - '.$conductor_nombres.'
															</td>
														</tr>
													</table>
												</td>

												<td style="text-align: center; vertical-align: top; width: 50%; margin-top: -10px;">
													<table style="width: 100%;">
														<tr>
															<td style="text-align: center; vertical-align: middle; width: 50%; padding: 0px;">
																<table style="width: 100%; border-spacing: 0px;">
																	<tr>
																		<td style="font-family: AgencyFBb; font-size: 14px; padding: 0px;">
																			<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #4A4F59; color: #ffffff; padding: 5px; text-align: center;">
																				DESTINATARIO
																			</div>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>

														<tr>
															<td style="text-align: center; vertical-align: middle; width: 50%; padding: 0px;">
																<table style="width: 100%; border-spacing: 0px;">
																	<tr>
																		<td style="vertical-align: middle; font-family: AgencyFBb; font-size: 14px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 5px; text-align: center; height: 45px; vertical-align: middle;">
																			'.$destinatario.'
																		</td>
																	</tr>
																</table>
															</td>
														</tr>

														<tr>
															<td style="text-align: center; vertical-align: middle; width: 50%; padding: 0px;">
																<table style="width: 100%; border-spacing: 0px;">
																	<tr>
																		<td style="font-family: AgencyFBb; font-size: 14px; padding: 0px;">
																			<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #4A4F59; color: #ffffff; padding: 5px; text-align: center;">
																				EMPRESA DE TRANSPORTES
																			</div>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>

														<tr>
															<td style="text-align: center; vertical-align: middle; width: 50%; padding: 0px;">
																<table style="width: 100%; border-spacing: 0px;">
																	<tr>
																		<td style="vertical-align: middle; font-family: AgencyFBb; font-size: 14px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 5px; text-align: center; height: 45px; vertical-align: middle;">
																			'.$transportista_ruc.' - '.$transportista_razonsocial.'
																		</td>
																	</tr>
																</table>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</div>

									<div class="row" style="margin-left: 5px; margin-right: 5px;">
										<table style="width: 100%; border-spacing: 0px; background-color: #ffffff; border-color: #ffffff;">
											<thead>
												<tr style="font-size: 14px; font-family: AgencyFBb;">
													<td style="text-align: center; border: solid; border-width: 1px; background-color: #4A4F59; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														N°
													</td>

													<td style="text-align: center; border: solid; border-width: 1px; background-color: #4A4F59; border-color: #ffffff; color: #ffffff; vertical-align: middle; color: #ffffff;">
														DESCRIPCIÓN
													</td>

													<td style="text-align: center; border: solid; border-width: 1px; background-color: #4A4F59; border-color: #ffffff; color: #ffffff; vertical-align: middle; color: #ffffff;">
														UNIDAD<br>MEDIDA
													</td>

													<td style="text-align: center; border: solid; border-width: 1px; background-color: #4A4F59; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														PESO<br>TOTAL
													</td>
												</tr>
											</thead>

											<tbody>';

	// 2. Arma la estructura de Detalle
		$d = 1;

		$q_datos = "SELECT VD.lote_cod_lote,
											 VD.guias_pesonetoajustado
								  FROM despachos_primertramo_validaciondatos VD
											 LEFT JOIN transporte T ON VD.balanza_placa = T.cplaca
								  		 INNER JOIN tb_clientes ET ON T.id_Transportista = ET.Id
								 WHERE MD5(VD.guiaremitente_serie) = '".$serie_guia."'
									 AND MD5(VD.guiaremitente_numero) = '".$numero_guia."'
									 AND VD.lote_id_proveedorminero = ".$id_remitente."
									 AND T.id_Transportista = ".$id_transportista."
									 AND VD.guias_fecha = '".$guia_fecha."'
								ORDER BY VD.lote_cod_lote, VD.lote_num_ticket, VD.lote_ticket_orden";

		if ($res_datos = mysqli_query($enlace, $q_datos)){
      if (mysqli_num_rows($res_datos) > 0) {
        while($row_datos = mysqli_fetch_array($res_datos)){
        	$html .= '					<tr style="font-size: 14px; font-family: AgencyFB;">';
        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
        	$html .= '							'.$d;
        	$html .= '						</td>';

        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
        	$html .= '							MINERAL AURÍFERO EN BRUTO SIN PROCESAR';
        	$html .= '						</td>';

        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
        	$html .= '							TNE';
        	$html .= '						</td>';

        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-family: AgencyFBb;">';
        	$html .= '							'.number_format($row_datos["guias_pesonetoajustado"], 2, '.', '');
        	$html .= '						</td>';

					$html .= '					</tr>';

					$d ++;
        }

        // Completa con líneas adicionales
        	while ($d < 9){
        		$html .= '					<tr style="font-size: 14px; font-family: AgencyFB;">';
	        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; height: 25px;">';
	        	$html .= '						</td>';

	        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
	        	$html .= '						</td>';

	        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
	        	$html .= '						</td>';

	        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-family: AgencyFBb;">';
	        	$html .= '						</td>';

						$html .= '					</tr>';

        		$d ++;
        	}
      }
    }

		$html .= '					</tbody>
										</table>
									</div>';

	// 3. Cerrando guia
		$html .= '		<div class="row">
										<table style="width: 100%;">
											<tr style="font-size: 14px;">
												<td style="text-align: center; vertical-align: top; width: 40%; padding: 0px;">
													<table style="width: 100%; border-spacing: 0px;">
														<tr>
															<td style="font-family: AgencyFBb; font-size: 14px; padding: 0px;">
																<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #4A4F59; color: #ffffff; padding: 5px; text-align: center;">
																	CONCESION
																</div>
															</td>

															<td style="font-family: AgencyFBb; font-size: 14px; padding: 0px;">
																<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 5px; text-align: center;">
																	'.$concesion.'
																</div>
															</td>
														</tr>
													</table>
												<td>

												<td style="text-align: center; vertical-align: top; width: 30%; padding: 0px;">
													<table style="width: 100%; border-spacing: 0px;">
														<tr>
															<td style="font-family: AgencyFBb; font-size: 14px; padding: 0px;">
																<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #4A4F59; color: #ffffff; padding: 5px; text-align: center;">
																	CÓDIGO ÚNICO
																</div>
															</td>

															<td style="font-family: AgencyFBb; font-size: 14px; padding: 0px;">
																<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 5px; text-align: center;">
																	'.$codigo_unico.'
																</div>
															</td>
														</tr>
													</table>
												<td>

												<td style="text-align: center; vertical-align: top; width: 30%; padding: 0px;">
													<table style="width: 100%; border-spacing: 0px;">
														<tr>
															<td style="font-family: AgencyFBb; font-size: 14px; padding: 0px;">
																<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #4A4F59; color: #ffffff; padding: 5px; text-align: center;">
																	SEGÚN GRT
																</div>
															</td>

															<td style="font-family: AgencyFBb; font-size: 14px; padding: 0px;">
																<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 5px; text-align: center;">
																	'.$guiaT.'
																</div>
															</td>
														</tr>
													</table>
												<td>
											</tr>
										</table>
									</div>

									<div class="row">
										<table style="width: 100%;">
											<tr style="font-size: 14px;">
												<td style="text-align: center; vertical-align: top; padding: 0px; width: 100%;">
													<table style="width: 100%; border-spacing: 0px;">
														<tr>
															<td style="font-family: AgencyFBb; font-size: 14px; padding: 0px;">
																<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #4A4F59; color: #ffffff; padding: 5px; text-align: center;">
																	MOTIVO DEL TRASLADO
																</div>
															</td>

															<td style="font-family: AgencyFBb; font-size: 14px; padding: 0px;">
																<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 5px; text-align: center;">
																	'.$motivo_traslado.'
																</div>
															</td>
														</tr>
													</table>
												<td>
											</tr>
										</table>
									</div>';

	// Cierra html
    $html .= '	</body>
							</html>';
// echo '$html: '.$html;
// return;
	$options = new Options();
  $options->set('isRemoteEnabled', TRUE);
  $document = new Dompdf($options);

	$document -> loadHtml($html, 'UTF-8');
	$document -> setPaper('A4', 'portrait');
	$document -> render();
	$document -> stream('Modelo de Guía de '.$nom_archivo.' - '.$nom_archivo_guia, array('Attachment' => 0));

?>
