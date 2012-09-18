var fp = {
  paginator_div : 'div.f-pagination',
  url           : baseUrl + "file-paginate/",

	 paginate:function(page){
	   var self    = this;
	   if( typeof(page)=="undefined" ){
	     return false;
	   }

	   var fp_type = jQuery(self.paginator_div).parent().attr('class');
	   loading( "div." + fp_type );

	   $.ajax({
	     type: 'GET',
	     url:  self.url + page,
	     success: function(response){
	       jQuery( "div." + fp_type ).html(response);
	       jQuery('a.cBox').colorbox({rel:'cBox'});
	     },
	     error: function(request, status, error){
	       jQuery( "div." + fp_type ).html("<div class='f-pagination-error'>No se encontraron archivos a mostrar</div>");
	     }
	   });
	 }

};