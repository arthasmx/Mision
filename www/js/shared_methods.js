
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
  jQuery(target_id).show().html(msg);
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