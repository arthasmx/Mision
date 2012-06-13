var bible = {
  separator : " ",
  dom:{
    form           : "form#bible-search",
    search_field   : "input#search",
    search_empty   : "dt#button-label",
    button         : "button#button",
    book_container : "select#f_seo",
    search_url     : baseUrl + "/bible/ajax-search",
    search_result_container: "div#search-results"
 },

	load_books:function(testament){
	  var self=this;

   if( testament=="null" || ( typeof(testament)=="undefined" ) ){
     jQuery( self.dom.form + " dl.field-f_seo").addClass("hide");
     jQuery( self.dom.form + self.separator + self.dom.book_container ).html('<option selected value="old">'+ choose_an_option +'</option>');
   }else{
     jQuery( self.dom.form + self.separator + self.dom.book_container ).html('<option selected>'+ ajax_loading +'</option>');
     jQuery( self.dom.form + " dl.field-f_seo").removeClass("hide");
     $.ajax({
       data: { testament: testament },
       dataType: 'json',
       url:  load_books_url,
       success: function(response){
         jQuery( self.dom.form + self.separator + self.dom.book_container ).empty();
         $.each(response, function(key, val) {
           jQuery( self.dom.form + self.separator + self.dom.book_container ).append('<option value="'+ key +'">'+ val +'</option>');
         });
       },
       error: function(request, status, error){
         jQuery( self.dom.form + self.separator + self.dom.form ).html(bad_request);
       }
     });
   }

	},

	paginate:function(div,url){
	  var self=this;
	  loading( self.dom.search_result_container );

	  $.ajax({
     type: 'GET',
     url:  url,
     success: function(response){
       jQuery( self.dom.search_result_container ).html(response);
     },
     error: function(request, status, error){
       jQuery( self.dom.search_result_container ).html("<div class='bad-search-result'>"+ ajax_loading +"</div>");
     }
   });

	}

};


jQuery(document).ready(function(){

  jQuery( bible.dom.form + bible.separator + bible.dom.button ).click(function(){

    var search = jQuery(bible.dom.search_field).val();
    if( ! search  ){
      jQuery(bible.dom.search_empty).html(empty_search);
      return false;
    }
    jQuery(bible.dom.form).submit();

  });

  jQuery('form#book-selection select, form#chapter-selection select').change(function(){
    window.location = baseUrl + route_bible + $(this).val();
  });

});