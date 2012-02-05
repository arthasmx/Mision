
  jQuery(document).ready(function(){

    jQuery(document).on("click", "ul.star-rating li a", function(){
      rate( jQuery( jQuery(this).parent() ).index() + 1 );
    });

  });

  function rate(rate){
    var url = baseUrl + "/article/rate/"+ rate_id +"/" + rate;

    $.ajax({
      url:  url,
      type: 'GET',
      success: function(response){
        jQuery('div.rate').html(response);
      },
      error: function(request, status, error){
        jQuery('div.rate').html(rating_on_error);
      }
    });

  }
