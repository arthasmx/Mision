var guestbook = {
  dom:{
   div:    "div#guestbook-form",
   form:   "form#guestbook",
   button: "button#button"
 },

	add:function(){
	  var self=this;
	  var params = jQuery(self.dom.form).serialize();
	  loading(self.dom.div);

   $.ajax({
     type: 'POST',
     data: params,
     url:  guestbook_add_url,
     success: function(response){
       jQuery(self.dom.div).html(response);
     },
     error: function(request, status, error){
       jQuery(self.dom.comment_container).html(guestbook_error);
     }
   });
	}

};


jQuery(document).ready(function(){

  jQuery(document).on("click", guestbook.dom.form +" "+ guestbook.dom.button, function(){
    guestbook.add();
  });

});