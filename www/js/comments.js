var comments = {
 id: 0,
 current_reply: false,
  dom:{
   comment_container: "div#comments-form",
   comment_form:      "form#comment",
   form_button:       "button#button",

   reply_container:   "div.reply-form",
   reply_form:        "form#reply",

   data_reply:        "data-reply",
   main_container:    "div#comments div.comments div"
 },
	url: {
			comment: baseUrl + "comments/comment",
			reply:   baseUrl + "comments/reply"
	},

	comment:function(){
	  var self=this;
	  var params = jQuery(self.dom.comment_form).serialize();
	  loading(self.dom.comment_container);

   $.ajax({
     type: 'POST',
     data: params,
     url:  self.url.comment,
     success: function(response){
       if( resp = is_jason(response) ){
         var template = self.get_reply_template(resp);
         jQuery(self.dom.comment_container).html(template);
       }else{
         jQuery(self.dom.comment_container).html(response);
       }

     },
     error: function(request, status, error){
       jQuery(self.dom.comment_container).html(comment_error);
     }
   });
	},

 show_form:function(button_clicked){
   var self=this;
   self.remove_previous_reply();
   var parent_id      = jQuery(button_clicked).attr('data-parent');
   var child_id       = jQuery(button_clicked).attr('data-child');
   self.current_reply = jQuery(button_clicked).nextAll(self.dom.reply_container);

   loading(self.current_reply);

   $.ajax({
     type: 'GET',
     data:{ parent: parent_id, child: child_id},
     url:  self.url.reply,
     success: function(response){
       jQuery(self.current_reply).html(response).show().parent().addClass(self.dom.data_reply);
     },
     error: function(request, status, error){
       jQuery(self.current_reply).html(reply_error);
     }
   });

 },

 remove_previous_reply:function(){
   var self=this;
   jQuery( self.dom.reply_container ).hide().empty();
   jQuery(self.dom.main_container).removeClass(self.dom.data_reply);
 },

	reply:function(){
   var self=this;
   var params         = jQuery(self.dom.reply_form).serialize();
   self.current_reply = 'div.' + self.dom.data_reply;
   var reply_dom      = self.current_reply +' '+ self.dom.reply_container;
   loading(reply_dom);

   $.ajax({
     type: 'POST',
     data: params,
     url:  self.url.reply,
     success: function(response){
       if( resp = is_jason(response) ){
         var template = self.get_reply_template(resp);
         jQuery(self.current_reply).after(template);
         self.remove_previous_reply();
       }else{
         jQuery(reply_dom).html(response);
       }

     },
     error: function(request, status, error){
       jQuery( self.current_reply ).html(reply_error);
     }
   });
	},

	get_reply_template:function( json ){
	  var self=this;

   return '\
     <div class="level_' + ((json['level']<=9) ? json['level'] : "10") + ' pending">\
       <span class="written">' + json['created'] + '</span>\
       <p class="reply">' + json['comment'] + '</p>\
       <span class="pending-msg">'+ json['pending'] +'</span>\
     </div>';
	}

};


jQuery(document).ready(function(){

  jQuery(document).on("click", comments.dom.comment_form +" "+ comments.dom.form_button, function(){
    comments.comment();
  });

  jQuery(document).on("click", "a.reply", function(){
    comments.show_form(this);
    return false;
  });
  jQuery(document).on("click", "button#cancel", function(){
    comments.remove_previous_reply(this);
    return false;
  });

  jQuery(document).on("click", comments.dom.reply_form +" "+ comments.dom.form_button, function(){
    comments.reply();
  });

  jQuery('span.replies_hs').click(function(){
    var reply_id = jQuery(this).attr('id');
    jQuery('div.' + reply_id).slideToggle();
  });

  jQuery('h1.comment-title').click(function(){
    jQuery('div#comments-form').slideToggle();
  });

});