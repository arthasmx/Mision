
  jQuery(document).ready(function(){

    jQuery(document).on("click", "ol.poll-options li", function(){
      poll( jQuery(this).attr('class') );
    });

  });

  function poll(vote){
    var url = baseUrl + "/poll/vote/"+ poll_id +"/" + vote;

    $.ajax({
      url:  url,
      dataType: 'json',
      success: function(response){
        if( response=="false" ){
          jQuery('ol.poll-options li.voting').addClass('error').text(poll_on_error);
        }else{
          google.setOnLoadCallback(drawChart);
          drawChart(response.question, response.options);
        }
        return true;
      },
      error: function(request, status, error){
        jQuery('ol.poll-options li.voting').addClass('error').text(poll_on_error);
      }
    });

  }

