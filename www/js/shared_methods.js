
function is_jason(data){
  if(typeof(data)=='undefined'){
    return false;
  }
  try{
    var data_checked = jQuery.parseJSON(data);
    return data_checked;
  }catch(e){
    return false;
  }
}

function loading(target) {
  jQuery(target).show().html("<div class='ajax-loading'></div>");
}

function show_error(msg,target_id,seconds_delay) {
  jQuery(target_id).html(msg).show();
  if( validate_param(seconds_delay) ){
    jQuery(target_id).delay(seconds_delay).fadeOut(seconds_delay);
  }
}

function numeric_values_only(value) {
  var chars = {
     allowed: "1234567890"
  };

  valid_value="";
  for (var i in value) {
   char=value[i];
   if (chars.allowed.indexOf(value[i])!=-1) {
    valid_value+=value[i];
   }
  }
 return valid_value;
}

function pop_up(route,id){
  nueva = window.open(route + "?id=" + escape(id) ,'',CONFIG='HEIGHT=230,WIDTH=300,TOOLBAR=no,MENUBAR=no,SCROLLBARS=no,RESIZABLE=no,LOCATION=no,DIRECTORIES=no,STATUS=no');
  return false;
}

function show_div(target){
  if(typeof(target)=='undefined'){
    return false;
  }
  jQuery(target).removeClass('hide').addClass('shown').hide().fadeIn('slow');
}

function hide_div(target){
  if(typeof(target)=='undefined'){
    return false;
  }
  jQuery(target).removeClass('shown').fadeOut('slow').addClass('hide');
}

function add_hidden_element(name, value, target){
  jQuery('<input />').attr('type', 'hidden').attr('name', name).attr('value', value).appendTo(target);
}

function set_element_position(from_this_element, to_this_element, left_value, top_value){
  var base_position = jQuery(from_this_element).position();

  if( base_position ){
    if( is_number(left_value) ) { jQuery(to_this_element).css('left', base_position.left + left_value) }
    if( is_number(top_value) )  { jQuery(to_this_element).css('top' , base_position.top  + top_value) }
  }
  return true;
}

function is_number(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

function validate_param(param){
  if( typeof(param) == "undefined" ){
    return false;
  }
  return ( param != false );
}

function saving_this_row(target,status){
  if(status=="on") {
    jQuery(target).addClass('saving');
  }
  if(status=="off") {
    jQuery(target).removeClass('saving');
  }
}

function string_to_seo(string){
  var to_replace = {".": "-"," ": "-","_": "-",",": "-",":": "-","á": "a","é": "e","í": "i","ó": "o","ú": "u","à": "a","è": "e","ì": "i","ò": "o","ù": "u","ä": "a","ë": "e","ï": "i","ö": "o","ü": "u","ñ": "n","ç": "c"};
  var allowed    = "abcdefghijklmnopqrstuvwxyz-1234567890";
  var string     = string.toLowerCase();
  var seo        = "";

  for (var i in to_replace){
    string = string.split(i).join(to_replace[i]);
  }

  for (var i in string){
    char = string[i];
    if (allowed.indexOf(string[i])!=-1) {
      seo += string[i];
    }
  }

  seo = seo.split("----").join("-").split("---").join("-").split("--").join("-");
  return seo;
}
