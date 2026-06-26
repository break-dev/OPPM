<?php

  session_start();

  include('cnx/cnx.php');
  include('global/variables.php');

  require('libs/phpqrcode/qrlib.php');
  require_once 'dompdf/autoload.inc.php';

  use Dompdf\Dompdf;
  use Dompdf\Options;

  // Funciones
    function formatearFecha($fecha){
      // Separar fecha
      $dia = str_pad(explode('-', $fecha)[2], 2, '0', STR_PAD_LEFT);
      $mes = nombre_meses(explode('-', $fecha)[1]);
      $anho = explode('-', $fecha)[0];

      return $dia . ' de ' . $mes . ' del ' . $anho;
    }

    function nombre_meses($num_mes){
      if ($num_mes == 1) {
        return "ENERO";
      }
      if ($num_mes == 2) {
        return "FEBRERO";
      }
      if ($num_mes == 3) {
        return "MARZO";
      }
      if ($num_mes == 4) {
        return "ABRIL";
      }
      if ($num_mes == 5) {
        return "MAYO";
      }
      if ($num_mes == 6) {
        return "JUNIO";
      }
      if ($num_mes == 7) {
        return "JULIO";
      }
      if ($num_mes == 8) {
        return "AGOSTO";
      }
      if ($num_mes == 9) {
        return "SEPTIEMBRE";
      }
      if ($num_mes == 10) {
        return "OCTUBRE";
      }
      if ($num_mes == 11) {
        return "NOVIEMBRE";
      }
      if ($num_mes == 12) {
        return "DICIEMBRE";
      }
    }

  // Recuperando variables
    $origen_datos = $_GET["o"]; // Origen de datos
    $id_rol = $_GET["r"]; // Rol obtenido de la sesión
    $cod_lote = $_GET["l"]; // Rol obtenido de la sesión

  // Ruta imágenes
    $ruta_images_x = 'https://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    $ruta_images = substr($ruta_images_x, 0, strpos($ruta_images_x, 'print_controlinterno_reportecumplimiento_lotes.php')) . 'images/';
    $ruta_images_qr = substr($ruta_images_x, 0, strpos($ruta_images_x, 'print_controlinterno_reportecumplimiento_lotes.php')) . '/';

  // Obteniendo el Logo del Informe según Modalidad de Envío
    $informes_logo = '';

    $q_datos = "SELECT is_comercializacion,
                       informes_logo
                  FROM tbconfig_modalidadenvio
                 WHERE Id IN (SELECT DISTINCT despacho_id_modalidadenvio
                               FROM despachos_primertramo_validaciondatos
                              WHERE lote_cod_lote = '".$cod_lote."')";

    if ($res_datos = mysqli_query($enlace, $q_datos)){
      if (mysqli_num_rows($res_datos) > 0) {
        while($row_datos = mysqli_fetch_array($res_datos)){
          if ($row_datos["is_comercializacion"] == 1){
            $informes_logo = $url_images.$row_datos["informes_logo"];
          }
          else{
            $q_datos = "SELECT DISTINCT P.descripcion,
                               P.imagen_logo
                          FROM despachos_primertramo_validaciondatos VD
                               INNER JOIN tbconfig_plantas P ON VD.despacho_id_destinoplanta = P.Id
                         WHERE VD.lote_cod_lote = '".$cod_lote."'";

            if ($res_datos = mysqli_query($enlace, $q_datos)){
              if (mysqli_num_rows($res_datos) > 0) {
                while($row_datos = mysqli_fetch_array($res_datos)){

                }
              }
            }-
          }
        }
      }
    }

  // 1. Obteniendo información de cabecera

    
  // 1. Arma la estructura de Cabeceera
    $html = ' <!DOCTYPE html>
                  <html lang="es">
                    <head>
                      <title>'.$nom_archivo.'</title>

                      <style>
                        html, body{
                          font-family: Arial, sans-serif;
                          margin: 0;
                          padding: 0;
                          margin: 15px;
                          font-size: 11px;
                        }

                        @page{
                          margin: 0;
                          pading: 0;
                        }
                      </style>
                    </head>

                    <body>
                      <table style="width: 100%; border-spacing: -1px;">
                        <tr>
                          <td rowspan="5" style="border: solid; border-width: 1px; vertical-align: middle; text-align: center; width: 150px;">
                            <div>
                              <img src="'.$ruta_images_qr.$informes_logo.'" style="width: 150px; padding: 0px;">
                            </div>
                          </td>

                          <td colspan="3" rowspan="2" style="border: solid; border-width: 1px; vertical-align: middle; text-align: center; font-weight: bold;">
                            CONTROL  DE EXPEDIENTE DE MINERAL
                          </td>

                          <td style="border: solid; border-width: 1px; vertical-align: middle; width: 100px;">
                            Modalidad:
                          </td>

                          <td id="cab_1" style="border: solid; border-width: 1px; vertical-align: middle; text-align: center;">

                          </td>
                        </tr>

                        <tr>
                          <td style="border: solid; border-width: 1px; vertical-align: middle;">
                            Destino:
                          </td>

                          <td id="cab_2" style="border: solid; border-width: 1px; vertical-align: middle; text-align: center;">

                          </td>
                        </tr>

                        <tr>
                          <td style="border: solid; border-width: 1px; vertical-align: middle; text-align: center; font-weight: bold; width: 110px;">
                            Código de Lote
                          </td>

                          <td colspan="2" style="border: solid; border-width: 1px; vertical-align: middle; text-align: center; font-weight: bold; min-width: 350px;">
                            Proveedor
                          </td>

                          <td style="border: solid; border-width: 1px; vertical-align: middle;">
                            Código de envío:
                          </td>

                          <td id="cab_3" style="border: solid; border-width: 1px; vertical-align: middle; text-align: center;">

                          </td>
                        </tr>

                        <tr>
                          <td rowspan="2" style="border: solid; border-width: 1px; vertical-align: middle; text-align: center;">

                          </td>

                          <td colspan="2" rowspan="2" style="border: solid; border-width: 1px; vertical-align: middle; text-align: center;">

                          </td>

                          <td style="border: solid; border-width: 1px; vertical-align: middle;">
                            Fecha de ingreso:
                          </td>

                          <td id="cab_3" style="border: solid; border-width: 1px; vertical-align: middle; text-align: center;">

                          </td>
                        </tr>

                        <tr>
                          <td style="border: solid; border-width: 1px; vertical-align: middle;">
                            Fecha de envío:
                          </td>

                          <td id="cab_3" style="border: solid; border-width: 1px; vertical-align: middle; text-align: center;">

                          </td>
                        </tr>
                      </table>

            ';

  // Cierra html
    $html .= '  </body>
              </html>';

    $options = new Options();
    $options->set('isRemoteEnabled', TRUE);
    $document = new Dompdf($options);

    $document->loadHtml($html, 'UTF-8');
    $document->setPaper('A4', 'portrait');
    $document->render();
    $document->stream('Modelo de Guía de ' . $nom_archivo . ' - ' . $nom_archivo_guia, array('Attachment' => 0));

?>