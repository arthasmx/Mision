
CKEDITOR.editorConfig = function( config ){

 config.resize_enabled     = false;
 config.baseHref           = baseUrl;
 config.autoGrow_onStartup = true;

 config.filebrowserBrowseUrl      = jsUrl + '/kcfinder/browse.php?type=files',
 config.filebrowserImageBrowseUrl = jsUrl + '/kcfinder/browse.php?type=images',
 config.filebrowserUploadUrl      = jsUrl + '/kcfinder/upload.php?type=files',
 config.filebrowserImageUploadUrl = jsUrl + '/kcfinder/upload.php?type=images'


 config.toolbar_simple = [ { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Smiley' ] } ];


 config.toolbar_articleCreate =
 [
  { name: 'document', items : [ 'Templates','NewPage','DocProps','Preview','Print' ] },
  { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
  { name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
  { name: 'forms', items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton',
         'HiddenField' ] },
  '/',
  { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
  { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
  '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
  { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
  { name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
  '/',
  { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
  { name: 'colors', items : [ 'TextColor','BGColor' ] },
  { name: 'tools', items : [ 'Maximize', 'ShowBlocks','Source' ] }
 ];

};