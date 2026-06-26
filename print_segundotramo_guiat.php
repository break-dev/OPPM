<?php

session_start();

include('cnx/cnx.php');
include('global/variables.php');

require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$id_unidad = $_GET["x"];
$serie_guia = $_GET["a"];
$numero_guia = $_GET["b"];

// 1. Obteniendo datos de la guía
$nom_archivo = 'Transportista';
$tipo_guia = mb_strtoupper($nom_archivo);

$q_datos = "SELECT DISTINCT
											 DL.guiaremitente_serie,
											 DL.guiaremitente_numero,
											 DL.guiatransportista_serie,
											 DL.guiatransportista_numero,
											 DL.guias_fecha,
											 DL.fechahora_emision,
											 DL.guias_puntopartida,
											 DL.guias_puntodestino,
											 DL.guias_placa1 AS cplaca,
											 TR.id_marca,
											 UPPER(M.descripcion) AS MARCA,
											 UPPER(TR.codigo_mtc) AS codigo_mtc,
											 DL.guias_placa2 AS PLACA2,
											 TR2.id_marca AS ID_MARCA2,
											 UPPER(M2.descripcion) AS MARCA2,
											 UPPER(TR2.codigo_mtc) AS CODIGO_MTC2,
											 DL.guias_idchofer,
											 C.dni_licencia,
											 UPPER(C.nombres) AS CONDUCTOR,
											 DL.guias_destinatario,
											 ET.documento AS TRANSPORTISTA_RUC,
											 UPPER(ET.razon_social) AS TRANSPORTISTA_RAZONSOCIAL,
											 UPPER(DL.guias_motivotraslado) AS MOTIVO_TRASLADO,
											 DL.id_tipocarga,
								       TC.descripcion AS TIPO_CARGA,
								       DL.num_bigbag,
								       DL.guias_remitenteruc,
								       DL.guias_remitenterazonsocial
								  FROM despachos_segundotramo_distribucion_lotes DL
								 			 INNER JOIN despachos_segundotramo_distribucion_unidades U ON DL.id_distribucionunidad = U.Id
								 			 INNER JOIN transporte TR ON DL.guias_placa1 = TR.cplaca
								 			 LEFT JOIN transporte TR2 ON DL.guias_placa2 = TR2.cplaca
								  		 LEFT JOIN tbconfig_unidadesmarca M ON TR.id_marca = M.Id
								  		 LEFT JOIN tbconfig_unidadesmarca M2 ON TR2.id_marca = M2.Id
								  		 LEFT JOIN tbconfig_conductores C ON DL.guias_idchofer = C.Id
								  		 INNER JOIN tb_clientes ET ON TR.id_Transportista = ET.Id
									  	 INNER JOIN tbconfig_tipocarga TC ON DL.id_tipocarga = TC.Id
								 WHERE U.Id = " . $id_unidad . "
								 	 AND MD5(DL.guiatransportista_serie) = '" . $serie_guia . "'
									 AND MD5(DL.guiatransportista_numero) = '" . $numero_guia . "'";

if ($res_datos = mysqli_query($enlace, $q_datos)) {
	if (mysqli_num_rows($res_datos) > 0) {
		while ($row_datos = mysqli_fetch_array($res_datos)) {
			$guiaR_serie = $row_datos["guiaremitente_serie"];
			$guiaR_numero = $row_datos["guiaremitente_numero"];
			$guiaR = $guiaR_serie . '-' . $guiaR_numero;

			$guiaT_serie = $row_datos["guiatransportista_serie"];
			$guiaT_numero = $row_datos["guiatransportista_numero"];
			$guiaT = $guiaT_serie . '-' . $guiaT_numero;

			$nom_archivo_guia = $guiaT;

			$fecha_guia = $row_datos["guias_fecha"];
			$fecha_emision = explode(' ', $row_datos["fechahora_emision"])[0];
			$guias_puntopartida = $row_datos["guias_puntopartida"];
			$guias_puntodestino = $row_datos["guias_puntodestino"];
			$placa_1 = $row_datos["cplaca"] . ((strlen($row_datos["PLACA2"]) == 0) ? '' : ' / ' . $row_datos["PLACA2"]);
			$marca_1 = $row_datos["MARCA"] . ((strlen($row_datos["MARCA2"]) == 0) ? '' : ' / ' . $row_datos["MARCA2"]);
			$constancia_mtc_1 = $row_datos["codigo_mtc"] . ((strlen($row_datos["CODIGO_MTC2"]) == 0) ? '' : ' / ' . $row_datos["CODIGO_MTC2"]);
			$conductor_licencia = $row_datos["dni_licencia"];
			$conductor_nombres = $row_datos["CONDUCTOR"];
			$destinatario = $row_datos["guias_destinatario"];
			$transportista_ruc = $row_datos["TRANSPORTISTA_RUC"];
			$transportista_razonsocial = $row_datos["TRANSPORTISTA_RAZONSOCIAL"];
			$remitente_ruc = $row_datos["guias_remitenteruc"];
			$remitente_razonsocial = $row_datos["guias_remitenterazonsocial"];
			$id_tipocarga = $row_datos["id_tipocarga"];
			$tipo_carga = $row_datos["TIPO_CARGA"];
			$num_bigbag = $row_datos["num_bigbag"];
		}
	}
}

// 1. Arma la estructura de Cabeceera
$html = '	<!DOCTYPE html>
						 	<html lang="es">
								<head>
									<title>Modelo de Guía de ' . $nom_archivo . ' - ' . $nom_archivo_guia . '</title>

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
																			' . $fecha_emision . '
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
																			' . $fecha_guia . '
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
																				N° G/R REMITENTE O C/P
																			</div>
																		</td>

																		<td style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; font-family: AgencyFBb; font-size: 14px; padding: 5px; text-align: center; width: 40%;">
																			' . $guiaR . '
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
															' . $tipo_guia . '
														</label>
													</div>

													<div style="margin-top: -5px;">
														<label style="font-size: 20px; font-family: AgencyFBb;">
															' . $nom_archivo_guia . '
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
																			' . $guias_puntopartida . '
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
																			' . $guias_puntodestino . '
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
												<td style="text-align: center; vertical-align: top; width: 50%; margin-top: -10px;">
													<table style="width: 100%;">
														<tr>
															<td style="text-align: center; vertical-align: middle; width: 50%; padding: 0px;">
																<table style="width: 100%; border-spacing: 0px;">
																	<tr>
																		<td style="font-family: AgencyFBb; font-size: 14px; padding: 0px;">
																			<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #4A4F59; color: #ffffff; padding: 5px; text-align: center;">
																				DATOS DEL REMITENTE
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
																			' . $remitente_ruc . ' - ' . $remitente_razonsocial . '
																		</td>
																	</tr>
																</table>
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
																				DATOS DEL DESTINATARIO
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
																			' . $destinatario . '
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

$q_datos = "SELECT DL.cod_lote,
											 DB.descripcion AS DESCRIPCION_BIEN,
											 DL.guias_pesonetoajustado,
											 PD.codigo_planta,
											 DL.cod_lote,
											 DL.num_parte,
											 DL.id_tipocarga,
								       TC.descripcion AS TIPO_CARGA,
								       DL.num_bigbag
								  FROM despachos_segundotramo_programacion_detalle PD
											 INNER JOIN despachos_segundotramo_programacion P ON PD.id_programacion = P.Id
										   INNER JOIN despachos_segundotramo_distribucion_unidades U ON P.Id = U.id_programacion
							         INNER JOIN despachos_segundotramo_distribucion_lotes DL ON U.Id = DL.id_distribucionunidad
							           AND PD.cod_lote = DL.cod_lote
								  		 INNER JOIN tbconfig_segundotramo_guiasdescripcionbien DB ON DL.guias_iddescripcionbien = DB.Id
									  	 INNER JOIN tbconfig_tipocarga TC ON DL.id_tipocarga = TC.Id
								 WHERE U.Id = " . $id_unidad . "
								 	 AND MD5(DL.guiatransportista_serie) = '" . $serie_guia . "'
									 AND MD5(DL.guiatransportista_numero) = '" . $numero_guia . "'
								ORDER BY DL.cod_lote";

if ($res_datos = mysqli_query($enlace, $q_datos)) {
	if (mysqli_num_rows($res_datos) > 0) {
		while ($row_datos = mysqli_fetch_array($res_datos)) {
			$cod_planta = $row_datos["codigo_planta"];
			$cod_lote = $row_datos["cod_lote"];
			$num_parte = $row_datos["num_parte"];
			$id_tipocarga = $row_datos["id_tipocarga"];
			$tipo_carga = $row_datos["TIPO_CARGA"];
			$num_bigbag = $row_datos["num_bigbag"];

			$html .= '					<tr style="font-size: 14px; font-family: AgencyFB;">';
			$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
			$html .= '							' . $d;
			$html .= '						</td>';

			$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
			$html .= '							' . mb_strtoupper($row_datos["DESCRIPCION_BIEN"]);

			if ($id_tipocarga == 1) {
				$html .= ' - A ' . mb_strtoupper($tipo_carga);
			} else {
				$html .= $num_bigbag . ' ' . mb_strtoupper($tipo_carga);
			}
			$html .= '						</td>';

			$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
			$html .= '							TNE';
			$html .= '						</td>';

			$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-family: AgencyFBb;">';
			$html .= '							' . number_format($row_datos["guias_pesonetoajustado"], 2, '.', '');
			$html .= '						</td>';

			$html .= '					</tr>';

			$d++;
		}

		// Completa con líneas adicionales
		while ($d < 9) {
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

			$d++;
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
																' . $marca_1 . '
															</td>
														</tr>

														<tr>
															<td style="width: 30%; font-family: AgencyFBb; font-size: 14px; width: 50%;">
																<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #4A4F59; color: #ffffff; padding: 5px;">
																	PLACA
																</div>
															</td>

															<td style="width: 70%; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; font-family: AgencyFBb; font-size: 14px; padding: 5px; text-align: center;">
																' . $placa_1 . '
															</td>
														</tr>

														<tr>
															<td style="width: 30%; font-family: AgencyFBb; font-size: 14px; width: 50%;">
																<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #4A4F59; color: #ffffff; padding: 5px;">
																	N° CONSTANCIA MTC
																</div>
															</td>

															<td style="width: 70%; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; font-family: AgencyFBb; font-size: 14px; padding: 5px; text-align: center;">
																' . $constancia_mtc_1 . '
															</td>
														</tr>

														<tr>
															<td style="width: 30%; font-family: AgencyFBb; font-size: 14px; width: 50%;">
																<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #4A4F59; color: #ffffff; padding: 5px;">
																	CONDUCTOR
																</div>
															</td>

															<td style="width: 70%; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; font-family: AgencyFBb; font-size: 14px; padding: 5px; text-align: center;">
																' . $conductor_licencia . ' - ' . $conductor_nombres . '
															</td>
														</tr>
													</table>
												</td>
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

$document->loadHtml($html, 'UTF-8');
$document->setPaper('A4', 'portrait');
$document->render();
$document->stream('Modelo de Guía de ' . $nom_archivo . ' - ' . $nom_archivo_guia, array('Attachment' => 0));
