hs.showCredits = false;
hs.outlineType = 'custom';
hs.dimmingOpacity = 0.75;
hs.fadeInOut = true;
hs.align = 'center';

// Add the slideshow controller
hs.addSlideshow({
 slideshowGroup: 'group1',
 interval: 5000,
 repeat: true,
 useControls: true,
 fixedControls: 'fit',
 overlayOptions: {
  opacity: 1,
  position: 'bottom center',
  offsetX: 0,
  offsetY: -15,
  hideOnMouseOut: true
 },
 thumbstrip: {
  mode: 'horizontal',
  position: 'bottom center',
  relativeTo: 'viewport'
 }

});

// Spanish language strings
hs.lang = {
 cssDirection: 'ltr',
 loadingText: 'Cargando...',
 loadingTitle: 'Click para cancelar',
 focusTitle: 'Click para traer al frente',
 fullExpandTitle: 'Expandir al tamaño actual',
 creditsText: 'Potenciado por <i>Highslide JS</i>',
 creditsTitle: 'Ir al home de Highslide JS',
 previousText: 'Anterior',
 nextText: 'Siguiente',
 moveText: 'Mover',
 closeText: 'Cerrar',
 closeTitle: 'Cerrar (esc)',
 resizeTitle: 'Redimensionar',
 playText: 'Iniciar',
 playTitle: 'Iniciar slideshow (barra espacio)',
 pauseText: 'Pausar',
 pauseTitle: 'Pausar slideshow (barra espacio)',
 previousTitle: 'Anterior (flecha izquierda)',
 nextTitle: 'Siguiente (flecha derecha)',
 moveTitle: 'Mover',
 fullExpandText: 'Tamaño real',
 number: 'Imagen %1 de %2',
 restoreTitle: 'Click para cerrar la imagen, click y arrastrar para mover. Usa las flechas del teclado para avanzar o retroceder.'
};

// gallery config object
var config1 = {
 slideshowGroup: 'group1',
 transitions: ['expand', 'crossfade']
};