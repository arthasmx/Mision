
  jQuery(document).ready(function(){

    jQuery(document).on("click", "ul.star-rating li a", function(){
      rate( jQuery( jQuery(this).parent() ).index() + 1 );
    });

    jQuery(document).on("mouseenter", "ul.star-rating li a", function(){
      jQuery('ul.star-rating li.rate-description').removeClass('error').text( jQuery(this).text() );
    });
    jQuery(document).on("mouseleave", "ul.star-rating li a", function(){
      jQuery('ul.star-rating li.rate-description').text('');
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
        jQuery('ul.star-rating li.rate-description').addClass('error').text(rating_on_error);
      }
    });

  }
