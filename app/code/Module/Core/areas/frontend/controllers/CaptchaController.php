<?php

require_once 'Local/Controller/Action.php';

class Core_CaptchaController extends Local_Controller_Action   {

	function getAction() {
		require_once('Xplora/Captcha.php');
		$captcha=new Xplora_Captcha(
			array(	// Opciones por defecto del captcha
				"chars"		=> 5,
				"width"		=> 210,
				"height"	=> 70,
				"font"		=> array(	// Iterar por cada letra las siguientes fuentes en formato 'nombre_fuente'=>tamaño_fuente
					"avenir"			=> 30,			// Geométrica
					"bluehighway"		=> 30,			// Geométrica
					"continuum"			=> 30,			// Geométrica
					"intrepid"			=> 30,			// Geométrica
					"micro"				=> 30,			// Geométrica
				),
				"chamaleon"	=> false,		// Mimetizar colores con los del fondo en lugar de utilizar el array de colores (debe existir una imagen de fondo)
				"color"		=> array(		// Iterar por cada letra los siguientes colores en formato array R,G,B (min 1, max 255)
							array(122,95,68),
						),
				"bgimage"			=> array(					// Imagen de fondo o pattern a utilizar en formato gif, si no se pone la ruta ni el .gif, se cargaran de la libreria, si se quieren personalizar, se pueden usar rutas completas, no importa el tamaño del pattern
					$this->getSkinPath("/art/generic/captcha/pattern_captcha.gif"),
				),
				"chamaleon"			=> false,
				"outline"			=> array(255,255,255),		// Color del outline de las letras, si no se quiere outline, se puede poner false, puede ponerse "auto" y usará el color más claro que haya disponible en el array de colores indicado o en el array de colores "camaleonicos"
				"rotation_jitter"	=> array(-5,5),				// Rango de rotación de las letras array min,max (en grados)
				"size_jitter"		=> array(0,30),			// Rango de tamaño array min,max (en porcentaje)
				"word"				=> true,
				"shuffle"			=> true,
			)
		);
		$captcha->render();
		exit;
	}

}