var bible = {
  separator : " ",
  dom:{
    form           : "form#bible-search",
    summary_form   : "form#summary-selection",
    search_field   : "input#search",
    search_empty   : "dt#button-label",
    button         : "button#button",
    btn_cancel     : "button#cancel",
    btn_close      : "div.option button#close",
    book_container : "select#f_seo",

    bible_options  : "div#bible-options",
    book_options   : "div#book-options",
    chapter_options: "div#chapter-options",
    search_options : "div#search-options",
    verse_options  : "div#verse-options",
    opt_options    : "div.option",
    opt_search     : "div.search",
    opt_info       : "div.info",
    opt_books      : "div.books",
    opt_chapter    : "div.chapter",
    opt_summary    : "div.summary",
    opt_verse      : "div.verse",
    search_result_container: "div.search-data"
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

  jQuery('form#book-selection select, form#chapter-selection select, form#verse-selection select').change(function(){
    window.location = baseUrl + route_bible + $(this).val();
  });

  jQuery(bible.dom.summary_form + ' select').change(function(){
    if( ! $(this).find('option:selected').hasClass('remove-attr') ){
      add_hidden_element("f_seo",       $(this).val(), bible.dom.summary_form);
      add_hidden_element("f_testament", $(this).find('option:selected').attr('data-testament'), bible.dom.summary_form);
    }
    jQuery(bible.dom.summary_form).submit();
  });

  jQuery( bible.dom.bible_options + bible.separator + "li, " + bible.dom.book_options + bible.separator + "li, " + bible.dom.chapter_options + bible.separator + "li, " + bible.dom.search_options + bible.separator + "li, " + bible.dom.verse_options + bible.separator + "li" ).click(function(){
    hide_div(bible.dom.opt_options);
    switch ( jQuery(this).attr('class') ){
      case 'info':
        show_div(bible.dom.opt_info);
        break;
      case 'books':
        show_div(bible.dom.opt_books);
        break;
      case 'chapter':
        show_div(bible.dom.opt_chapter);
        break;
      case 'search':
        show_div(bible.dom.opt_search);
        break;
      case 'summary':
        show_div(bible.dom.opt_summary);
        break;
      case 'verse':
        show_div(bible.dom.opt_verse);
        break;
      default:
        return false;
    }
  });
  jQuery( bible.dom.btn_close ).click(function(){
    hide_div(bible.dom.opt_options);
    return false;
  });

});