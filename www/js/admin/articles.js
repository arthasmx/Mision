var articles = {

  form:{
  },
  url:{
    status : baseUrl + '/articles/status'
  },

  status:function(selected){
    var self = this;
    var form = jQuery(selected).parent().serialize();
    var tr   = jQuery(selected).parent().parent().parent();
    var td   = jQuery(selected).parent().parent();

    shared.status(selected, form, tr, td, self.url.status );
  }

};


jQuery(document).ready(function(){

  // status change
  jQuery(shared.form.status).change(function(){
    articles.status(this);
  });

});