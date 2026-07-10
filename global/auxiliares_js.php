<?php

	// Loading modal
		echo '	<div id="divmodal_Loading" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="divmodal_LoadingLabel" aria-hidden="true" style="margin-top: 300px; overflow-y: hidden;">
							<div class="modal-dialog" style="height: 100vh; opacity: 0.5; width: 100%;"><center>
								<img src="'.$url_images.'/loading.gif" width="200px" style="border: solid; border-width: 1px; border-color: #D9D9D9;  border-radius: 15px;"></img>
							</center></div>
						</div>';

	include('modal_global.php');

?>

<script type="text/javascript">
	const modal_loading = new bootstrap.Modal(document.getElementById('divmodal_Loading'), {});

	// Seteando eventos iniciales para formularios
		$(window).on('load', function() {
		
		});

	// Para cuando se abra un modal
		$('.modal').on('shown.bs.modal', function() {
		$('.modal-backdrop').css('width', '100%');
		$('.modal-backdrop').css('height', '100%');
		});

	// Abriendo Waiting modals
		$(document).ajaxStart(function() {
			// modal_loading.show();
		});

	// Cerrando Waiting modals
		$(document).ajaxStop(function() {
			// modal_loading.hide();
		});

	function f_GetMenuPrincipal(){
		$.post( "apis/backend.php", { accion: "get_menus" }, 
			function( data ) {
				if(data.estado == 1){
					$("#div_menu1").html(data.html);
				}
				else{
					$("#div_menu1").html('');
				}

			}, "json");
	}

	function f_OpenMenu(_id_window){
		window.open(_id_window, '_self');
	}

	function f_ShowSubMenu(_id_elemento){
		if ($('#div_submenu_' + _id_elemento).is(':visible')) {
	    $('#div_submenu_' + _id_elemento).attr("style", "display: none !important");

	    $("#img_SubMenu_" + _id_elemento).removeClass("bi-arrow-bar-up").addClass("bi-arrow-bar-down");
		}
		else {
	    $('#div_submenu_' + _id_elemento).show(350);

	    $("#img_SubMenu_" + _id_elemento).removeClass("bi-arrow-bar-down").addClass("bi-arrow-bar-up");
		}
	}

	function f_ShowSubMenu2(_id_subelemento){
		if ($('#div_submenu2_' + _id_subelemento).is(':visible')) {
	    $('#div_submenu2_' + _id_subelemento).attr("style", "display: none !important");

	    $("#img_SubMenu2_" + _id_subelemento).removeClass("bi-arrow-bar-up").addClass("bi-arrow-bar-down");
		}
		else {
	    $('#div_submenu2_' + _id_subelemento).show(350);

	    $("#img_SubMenu2_" + _id_subelemento).removeClass("bi-arrow-bar-down").addClass("bi-arrow-bar-up");
		}
	}

	function f_GetSubmenu1(_cod_seccion, _nom_seccion){
		$.post( "apis/backend.php", { accion: "get_submenu1", cod_seccion: _cod_seccion }, 
			function( data ) {
				if(data.estado == 1){
					$("#div_submenu1").html(data.html);

					$("#sb1_titulo").html(_nom_seccion);

					$(".offcanvas-backdrop").css('width', '100%');
					$(".offcanvas-backdrop").css('height', '100%');
				}
				else{
					$("#div_submenu1").html('');

					window.open('index.php', '_self');
				}

			}, "json");
	}

	function f_CheckEMail(_id_object){
		var _estado = 1;

		if($("#" + _id_object).val().indexOf('@', 0) == -1 || $("#" + _id_object).val().indexOf('.', 0) == -1) {
	  	_estado = 0;
		}

		return _estado;
	}

	function f_OpenModal(_id_modal){
		$("#" + _id_modal).modal("show");
	};

	function f_cerrarModal(_id_modal){
		$("#" + _id_modal).modal('hide');
  };

	function f_CerrarDiv(accion, _id_div){
		var _div = document.getElementById(_id_div);

		if (accion == 'A'){
			_div.style.display = 'block'
		}
		else{
			 _div.style.display = 'none';
		}
	};

	function f_CleanInjection(_val){
		_val = ((_val == null) ? '' : _val);
		_val = _val.toString().trim().replace(/'/g, '');

		return _val;
	};

	function f_CerrarSesion(){
		window.open('cerrar_sesion.php', '_self');
	};

	function f_ReplaceClass(_Id, _oldClass, _newClass) {
		var _obj = $("#" + _Id);

		if (_obj.hasClass(_oldClass)) {
			_obj.removeClass(_oldClass);
		}
		_obj.addClass(_newClass);
	}

	function f_CheckClientesCredito(){
		$("#tst_container").html('');

		$.post( "apis/backend.php", { accion: "check_clientescredito" }, 
			function( data ) {
				if(data.estado == 1){
					$("#tst_container").html(data.html);

					setTimeout('f_CheckClientesCredito()', 600000);

					return;
				}

			}, "json");
	}

	function f_AutorizarVisita(_id_visita, _is_autorizado){
		$.post( "apis/backend.php", { accion: "update_AutorizacionVisita", id_visita: _id_visita, is_autorizado: _is_autorizado }, 
			function( data ) {
				if(data.estado == 1){
					f_CheckVisitas();
				}
				else{
					alert("Ocurrió un error al momento de Grabar la autorización de la visita.");
				}

			}, "json");
	}

	function f_RedondearDecimales(_numero, _cantidadDecimales) {
    var partes = parseFloat(_numero).toFixed(_cantidadDecimales).toString().split('.');
    var entero = partes[0];
    var decimal = partes[1] || '';

    if (decimal.length < _cantidadDecimales) {
        decimal = decimal.padEnd(_cantidadDecimales, '0');
    }

    if (decimal.endsWith(".")){
    	decimal = decimal.slice(0, -1);
    }

    return `${parseFloat(`${entero}.${decimal}`).toLocaleString('es-PE', { minimumFractionDigits: 2 })}`
	}

	function f_CalcularDiferenciaHoras(_fecha_inicio, _fecha_fin) {
    // Convertir las fechas en objetos Date
	    var inicio = new Date(_fecha_inicio);
	    var fin = new Date(_fecha_fin);

    // Calcular la diferencia en milisegundos
    	var diferencia = fin - inicio;

    // Calcular la diferencia en horas con un decimal
	    var horas = diferencia / (1000 * 60 * 60);
	    horas = horas.toFixed(1); // Redondear a 1 decimal

    return horas;
	}

	function f_ModoAuditoria(_on){
		if (_on == 1){
			if (!confirm("¿Está seguro de Iniciar el Modo Auditoría?")){
				return;
			}
		}

		// Grabar la ejecución
			$.post("../apis/backend.php", { accion: "grabar_ModoAuditoria", is_on: _on }, 
        function ( data ) {
          if (data.estado == 1){
            if (_on == 1){
            	window.open('resumen_balanza.php', '_self');
            }
            else{
            	window.open('inicio.php', '_self');

            	// f_CheckModoAuditoria();
            }
          }

        }, "json");
	}

	function f_CheckVisitas(){
		$("#tst_visitas").html('');

		// $.post( "apis/backend.php", { accion: "check_visitas" }, 
		// 	function( data ) {
		// 		if(data.estado == 1){
		// 			$("#tst_visitas").html(data.html);

		// 			setTimeout('f_CheckVisitas()', 20000);

		// 			return;
		// 		}

		// 	}, "json");
	}

	function f_CheckModoAuditoria(){
		$.post( "apis/backend.php", { accion: "check_ModoAuditoria" }, 
			function( data ) {
				if(data.estado == 1){
					if (data.is_on == 1){
						if (data.is_ejecucion == 0){
							window.open('resumen_balanza.php', '_self');
						}
					}

					setTimeout('f_CheckModoAuditoria()', 5000);
				}

			}, "json");
	}

	// Abre las listas modales para dispositivos móviles
		function f_ShowListaModal(_select){
			// Cierra la lista desplegable
				$(_select).blur();

			// Setea título
				var modal_titulo = _select.getAttribute("data-titulo");

				$("#modalglobal_Titulo").html(modal_titulo);

				f_OpenModal('modal_global');

			// Identifica el Select
				var id_select = _select.id;

			// Crea html para la tabla
				var _val = '';
				var _text = '';
				var _html = '';

				for (let option of _select.options) {
					if (option.value != 'x' && option.value.trim().length > 0){
						_html += '<tr style="font-size: 18px; color: #ffffff;" onclick="f_ListaModal_Select(' + "'" + id_select + "', '" + option.value + "'" + ');">';
		        _html += '	<td hidden>';
		        _html += '		' + option.value;
		        _html += '	</td>';

		        _html += '	<td style="text-align: center; width: 40px; vertical-align: middle; height: 50px;">';
		        _html += '		<img src="<?php echo $img_select2 ?>" style="width: 20px;">';
		        _html += '	</td>';

		        _html += '	<td style="vertical-align: middle;">';
		        _html += '		' + option.text;
		        _html += '	</td>';
		        _html += '</tr>';
					}
		    }

		    $("#modalglobal_TablaOpciones").html(_html);
		}

	// Asignar el valor seleccionado de la lista modal global
		function f_ListaModal_Select(_id_select, _val){
			$("#" + _id_select).val(_val);

			// Ejecutar el onchange del select
				var _select = document.getElementById(_id_select);
				_select.dispatchEvent(new Event("change")); // Disparar el evento onchange del select

			// Cerrando la ventana modal
				f_cerrarModal('modal_global');
		}

	// Busca coincidencias en la tabla de resultados del Modal Global
		function f_ModalGlobal_Finding(){
			var _find = $("#modalglobal_find").val().trim().toLowerCase(); // Obtiene el valor en minúsculas

	    $("#modalglobal_TablaOpciones tr").each(function() {
        var descripcion = $(this).find("td:nth-child(3)").text().toLowerCase(); // Obtiene el texto del tercer td

        if (descripcion.includes(_find)) {
          $(this).show(); // Muestra la fila si coincide
        }
        else {
          $(this).hide(); // Oculta la fila si no coincide
        }
	    });
		}

	// Coloca el cursor en el Input de búsqueda del Modal Global
		$("#modal_global").on('shown.bs.modal', function(){
    	$("#modalglobal_find").focus();
  	});

	// f_CheckClientesCredito();
	f_CheckVisitas();
	// f_CheckModoAuditoria();
</script>

<script type="text/javascript">
	var voiceList = document.querySelector('#voiceList');
	var tts = window.speechSynthesis;
	var voices = [];

	GetVoices();

	if (speechSynthesis !== undefined){
		speechSynthesis.onvoiceschanged = GetVoices;
	}

	function GetVoices(){
		voices = tts.getVoices();
		voiceList.innerHTML = '';
		voices.forEach((voice)=>{
			var listItem = document.createElement('option');
			listItem.textContent = voice.name;
			listItem.setAttribute('data-lang', voice.lang);
			listItem.setAttribute('data-name', voice.name);
			voiceList.appendChild(listItem);
		});

		voiceList.selectedIndex = 0;
	}

	function f_StartToSpeech(_sexo, _nom_usuario){
		var txtMsg = 'Bienvenid' + ((_sexo == 0) ? 'o' : 'a') + ' ' + _nom_usuario + ', este es el ERP Operaciones de O P P M  SAC.';
		var toSpeak = new SpeechSynthesisUtterance(txtMsg);
		var selectedVoiceName = voiceList.selectedOptions[0].getAttribute('data-name');
		var _ok = 0;

		voices.forEach((voice) => {
			if (voice.name === 'Google español de Estados Unidos'){
				toSpeak.voice = voice;

				_ok = 1;
			}
		});

		if (_ok == 0){
			voices.forEach((voice) => {
				if (voice.name === selectedVoiceName){
					toSpeak.voice = voice;
				}
			});
		}

		tts.speak(toSpeak);
	}

	let _speechSynth
	let _voices
	const _cache = {}

	/**
	 * retries until there have been voices loaded. No stopper flag included in this example. 
	 * Note that this function assumes, that there are voices installed on the host system.
	 */

	function loadVoicesWhenAvailable (onComplete = () => {}) {
	  _speechSynth = window.speechSynthesis
	  const voices = _speechSynth.getVoices()

	  if (voices.length !== 0) {
	    _voices = voices
	    onComplete()
	  } else {
	    return setTimeout(function () { loadVoicesWhenAvailable(onComplete) }, 100)
	  }
	}

	/**
	 * Returns the first found voice for a given language code.
	 */

	function getVoices (locale) {
	  if (!_speechSynth) {
	    throw new Error('Browser does not support speech synthesis')
	  }
	  if (_cache[locale]) return _cache[locale]

	  _cache[locale] = _voices.filter(voice => voice.lang === locale)
	  return _cache[locale]
	}

	/**
	 * Speak a certain text 
	 * @param locale the locale this voice requires
	 * @param text the text to speak
	 * @param onEnd callback if tts is finished
	 */

	function playByText (locale, text, onEnd) {
	  const voices = getVoices(locale)

	  // TODO load preference here, e.g. male / female etc.
	  // TODO but for now we just use the first occurrence
	  const utterance = new window.SpeechSynthesisUtterance()
	  utterance.voice = voices[0]
	  utterance.pitch = 1
	  utterance.rate = 1
	  utterance.voiceURI = 'Sara'
	  utterance.volume = 1
	  utterance.rate = 1.05
	  utterance.pitch = 0.8
	  utterance.text = text
	  utterance.lang = locale

	  if (onEnd) {
	    utterance.onend = onEnd
	  }

	  _speechSynth.cancel() // cancel current speak, if any is running
	  _speechSynth.speak(utterance)
	}

	// on document ready
	loadVoicesWhenAvailable(function () {
	 console.log("loaded") 
	})

	function speak (_sexo, _nom_usuario) {
		var txtMsg = 'Bienvenid' + ((_sexo == 1) ? 'o' : 'a') + ' ' + _nom_usuario + ', este es el ERP Operaciones de O P P M sac.';

	  setTimeout(() => playByText("es-PE", txtMsg), 300)
	}

	function f_SendRecordatorio_ClientesCredito(_is_vencido, _cliente, _correo, _vencimiento){
		// Seteando parámetros enviados
			$("#clientescredito_cliente").val(_cliente);
			$("#clientescredito_correo").val(_correo);

			var txt_correo = "Estimado cliente, buen día.\n\nLe recordamos que su crédito";

			if (_is_vencido == 1){
				txt_correo += " venció el día " + _vencimiento.split('-')[2] + '/' + _vencimiento.split('-')[1] + '/' + _vencimiento.split('-')[0] + " y aún tiene facturas pendientes por pagar. Por tal motivo, solicitamos pueda cancelar la deuda ";
			}
			else{
				txt_correo += " vencerá el día " + _vencimiento.split('-')[2] + '/' + _vencimiento.split('-')[1] + '/' + _vencimiento.split('-')[0] + " y aún tiene facturas pendientes por pagar. Por tal motivo, le recordamos hacer el pago antes de la fecha indicada ";
			}

			txt_correo += "para continuar brindándole el servicio acostumbrado.\n\nQuedamos atentos a su pronta confirmación, muchas gracias.";

			$("#clientescredito_texto").val(txt_correo);

		// Abriendo ventana modal
			f_OpenModal('modal_clientescredito_sendemail');
	}

	function DownloadFileFromUrl(fileURL, fileName) {
      var link = document.createElement('a');
      link.href = fileURL;
      link.download = fileName;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    }
</script>