<?php

	// Url's
		$url_images = 'images/';
		// $url_lims = 'https://oppm.intelli-apps.com/';
		$url_lims = 'https://oppmerp.com/';

	// Variables
		$nom_app = 'ERP Operaciones';
		$favicon = $url_images.'favicon.png';
		$img_logo = $url_images.'logo.png';
		$img_logo2 = $url_images.'logo2.png';
		$img_logo_aum = $url_images.'logo_aum.png';
		$img_waiting = $url_images.'waiting.gif';
		$img_fondohall = $url_images.'fondo_hall.png';
		$mp4_login = $url_images.'login.mp4';
		$mp4_waitingroom = $url_images.'waiting_room.mp4';
		$img_email = $url_images.'email.png';
		$dash_circle = $url_images.'dash_circle.png';
		$dash_fondo1 = $url_images.'dash_fondo1.png';
		$barcode_laser = $url_images.'barcode_laser.png';
		$img_IE = $url_images.'informe_ensayos.png';
		$downloading = $url_images.'downloading.gif';
		$btn_add = $url_images.'button_add.png';
		$img_camara = $url_images.'camara.png';
		$img_view = $url_images.'view.png';
		$img_check = $url_images.'check.png';
		$img_check_red = $url_images.'check_red.png';
		$img_print = $url_images.'print.png';
		$img_codebar = $url_images.'codebar.png';
		$img_colores = $url_images.'rueda_colores.png';
		$img_refresh = $url_images.'refresh.png';
		$img_excel = $url_images.'excel.png';
		$img_delete = $url_images.'delete.png';
		$img_circlered = $url_images.'circle_red.png';
		$img_circleyellow = $url_images.'circle_yellow.png';
		$img_circlegreen = $url_images.'circle_green.png';
		$img_select = $url_images.'select.png';
		$img_verificacion = $url_images.'vigilancia_ingresos.png';
		$img_informe = $url_images.'informe.png';
		$img_informe2 = $url_images.'informe2.png';
		$img_logocolibri = $url_images.'logo_colibri.png';
		$img_select2 = $url_images.'play_select.png';
		$img_find = $url_images.'search.png';
		$img_despachos = $url_images.'despachos.png';
		$img_circle_1 = $url_images.'circle_1.png';
		$img_circle_2 = $url_images.'circle_2.png';
		$img_circle_3 = $url_images.'circle_3.png';
		$img_circle_4 = $url_images.'circle_4.png';
		$img_circle_5 = $url_images.'circle_5.png';
		$img_circle_6 = $url_images.'circle_6.png';
		$img_button_play = $url_images.'button_play.png';
		$img_button_pause = $url_images.'button_pause.png';
		$img_button_finish = $url_images.'button_finish.png';
		$img_button_cancel = $url_images.'button_cancel.png';
		$img_button_up = $url_images.'button_up.png';
		$img_button_down = $url_images.'button_down.png';

	// Matriz de 50 colores aleatorios
		$arr_colores = [
								    "#FFCDD2", "#F8BBD0", "#E1BEE7", "#D1C4E9", "#C5CAE9",
								    "#BBDEFB", "#B3E5FC", "#B2EBF2", "#B2DFDB", "#C8E6C9",
								    "#DCEDC8", "#F0F4C3", "#FFF9C4", "#FFECB3", "#FFE0B2",
								    "#FFCCBC", "#D7CCC8", "#F5F5F5", "#CFD8DC", "#EF9A9A",
								    "#F48FB1", "#CE93D8", "#B39DDB", "#9FA8DA", "#90CAF9",
								    "#81D4FA", "#80DEEA", "#80CBC4", "#A5D6A7", "#C5E1A5",
								    "#E6EE9C", "#FFF176", "#FFD54F", "#FFB74D", "#FF8A65",
								    "#A1887F", "#E0E0E0", "#B0BEC5", "#D32F2F", "#C2185B",
								    "#7B1FA2", "#512DA8", "#303F9F", "#1976D2", "#0288D1",
								    "#0097A7", "#00796B", "#388E3C", "#689F38", "#AFB42B"
									 ];


	// Fecha y hora del sistema
		date_default_timezone_set("America/Lima"); 

		$g_date = date('Y-m-d');
		$g_time = date('H:i:s');

		$g_fecha = $g_date.' ' .$g_time;
		$g_anho = date('Y');
		$g_mes = date('n');
		$g_dia = date('d');

	// Funciones
		function sin_tildes($texto){
			$texto = strtolower($texto);

			$texto = str_replace('á', 'a', $texto);
			$texto = str_replace('é', 'e', $texto);
			$texto = str_replace('í', 'i', $texto);
			$texto = str_replace('ó', 'o', $texto);
			$texto = str_replace('ú', 'u', $texto);

			return strtoupper($texto);
		}

		function get_nombre_dia($fecha){
	  	$fecha = strtotime($fecha); //pasamos a timestamp

			switch (date('w', $fecha)){
			    case 0: return "Domingo"; break;
			    case 1: return "Lunes"; break;
			    case 2: return "Martes"; break;
			    case 3: return "Miercoles"; break;
			    case 4: return "Jueves"; break;
			    case 5: return "Viernes"; break;
			    case 6: return "Sabado"; break;
			}
		}

		function get_nombre_mes($num_mes){
			if ($num_mes == 1){
				return "ENERO";
			}
			if ($num_mes == 2){
				return "FEBRERO";
			}
			if ($num_mes == 3){
				return "MARZO";
			}
			if ($num_mes == 4){
				return "ABRIL";
			}
			if ($num_mes == 5){
				return "MAYO";
			}
			if ($num_mes == 6){
				return "JUNIO";
			}
			if ($num_mes == 7){
				return "JULIO";
			}
			if ($num_mes == 8){
				return "AGOSTO";
			}
			if ($num_mes == 9){
				return "SEPTIEMBRE";
			}
			if ($num_mes == 10){
				return "OCTUBRE";
			}
			if ($num_mes == 11){
				return "NOVIEMBRE";
			}
			if ($num_mes == 12){
				return "DICIEMBRE";
			}
		}

		function calcularDiferenciaHoras($fecha_inicio, $fecha_fin) {
	    // Convertir las fechas en objetos DateTime
	    $inicio = new DateTime($fecha_inicio);
	    $fin = new DateTime($fecha_fin);

	    // Calcular la diferencia en horas
	    $diferencia = $fin->diff($inicio);

	    // Obtener la diferencia total de horas
	    $horas = $diferencia->h + ($diferencia->days * 24);

	    // Si quieres incluir los minutos fraccionales también, puedes hacerlo de esta manera:
	    $horas += $diferencia->i / 60;

	    return $horas;
	}
?>
