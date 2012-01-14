<?php
class Module_Core_Repository_Model_Dates extends Core_Model_Repository_Model {

  /**
   * Formatea una fecha
   * @param string $formato | Indica el tipo de formato a aplicar a la fecha, como los siguientes
   * 0	= 2009-01-25 15:23:11 | OJO, esta es la fecha para guardar a mySQL y recibe en formato: 18/Dic/2009
   * 1	= 15/02/2009
   * 2	= 15/Feb/2009
   * 3	= Feb 15, 2009
   * 4	= Viernes 15/02/2009
   * 5	= Viernes 15/Feb/2009
   * 6	= Viernes 15 Feb 2009
  */
  function toDate($formato,$date,$timezone=false){
    if(!$timezone){
      date_default_timezone_set('America/Mazatlan');
    }
  
    $datetime = new DateTime($date);
    switch ($formato) {
      case 0:
        $fecha=$datetime->format('Y-m-j ').date('H:i:s');
        break;
      case 1:
        $fecha=$datetime->format('j/m/Y');
        break;
      case 2:
        $fecha=$datetime->format('j/M/Y');
        break;
      case 3:
        $fecha=$datetime->format('M j, Y');
        break;
      case 4:
        $fecha=$datetime->format('D j/m/Y');
        break;
      case 5:
        $fecha=$datetime->format('D j/M/Y');
        break;
      case 6:
        $fecha=$datetime->format('D j M Y');
        break;
      case 7:
        $fecha=$datetime->format('l j F Y');
        break;
      case 8:
        $fecha=$datetime->format('Y/m');
        break;
  
      default:
        $fecha=$datetime->format('M j, Y');
      break;
    }
  
    // Si locale NO es INGLES, traducimos; Porque ? porque el mySQL que tengo x default esta en ingles
    if(App::locale()->getLang()!='en'){
      if($formato>6){
        // Dias completos
        $englishDates=array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
        $spanishDates=array(App::xlat('dates_long_day_7'),App::xlat('dates_long_day_1'),App::xlat('dates_long_day_2'),App::xlat('dates_long_day_3'),App::xlat('dates_long_day_4'),App::xlat('dates_long_day_5'),App::xlat('dates_long_day_6'));
        $fecha = str_replace($englishDates,$spanishDates,$fecha);
        // Completos
        $englishDates=array('January','February','March','April','May','June','July','August','September','October','November','December');
        $spanishDates=array(App::xlat('dates_long_month_1'),App::xlat('dates_long_month_2'),App::xlat('dates_long_month_3'),App::xlat('dates_long_month_4'),App::xlat('dates_long_month_5'),App::xlat('dates_long_month_6'),App::xlat('dates_long_month_7'),App::xlat('dates_long_month_8'),App::xlat('dates_long_month_9'),App::xlat('dates_long_month_10'),App::xlat('dates_long_month_11'),App::xlat('dates_long_month_12'));
        $fecha = str_replace($englishDates,$spanishDates,$fecha);
        }else{
        // Dias
        $englishDates=array('Sun','Mon','Tue','Wed','Thu','Fri','Sat');
        $spanishDates=array(App::xlat('dates_short_day_7'),App::xlat('dates_short_day_1'),App::xlat('dates_short_day_2'),App::xlat('dates_short_day_3'),App::xlat('dates_short_day_4'),App::xlat('dates_short_day_5'),App::xlat('dates_short_day_6'));
        $fecha = str_replace($englishDates,$spanishDates,$fecha);
        // Meses
        $englishDates=array('Jan','Apr','Aug','Dec');
        $spanishDates=array(App::xlat('dates_short_month_1'),App::xlat('dates_short_month_4'),App::xlat('dates_short_month_8'),App::xlat('dates_short_month_12'));
        $fecha = str_replace($englishDates,$spanishDates,$fecha);
      }
    }
    return $fecha;
  }

}