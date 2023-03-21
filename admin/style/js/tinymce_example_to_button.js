Tiny_mce_callback_handler_class = function(){
    this.init = function(callback, value, meta){
        this.meta = meta;
        this.callback = callback;
        this.value = value;
        window.open(global_media_library_url,'media_library_window','popup');
    }

    this.call_callbeck = function(filename, payload){
        this.callback(filename, payload);
    }
}

function update_from_media_library(file_name){
    tiny_mce_callback_handler.call_callbeck(file_name);
}

function init_tinymce(selector_identifier,media_uploader_url, media_library_url){
    global_media_library_url = media_library_url;
    tinymce.init({
        selector: selector_identifier,
        plugins: 'image code link lists codesample advlist autosave emoticons fullscreen help hr insertdatetime nonbreaking paste preview print searchreplace table' ,
        toolbar: ['undo redo | image code link hr insertdatetime | numlist bullist table',
            'bold italic underline blocks | forecolor backcolor fontsize fontfamily styles | restoredraft paste preview print | nonbreaking codesample emoticons | fullscreen help'],
        contextmenu: "link image inserttable | cell row column deletetable",
        directionality : "rtl",
        insertdatetime_dateformat: "%Y-%m-%d",
        /* without images_upload_url set, Upload tab won't show up*/
        images_upload_url: media_uploader_url,
        content_style: 'img {max-width: 100%; height:auto;}',
        file_picker_callback: function(callback, value, meta) {
            return tiny_mce_callback_handler.init(callback, value, meta);
        }
        
    });

}

tiny_mce_callback_handler = new Tiny_mce_callback_handler_class();
global_media_library_url = null;



tinymce.init({

  selector: selector_identifier,
  mode: "textareas",
  plugins: [
      "advlist autolink lists link image charmap print preview hr anchor pagebreak",
      "searchreplace wordcount visualblocks visualchars code fullscreen",
      "insertdatetime media nonbreaking save table contextmenu directionality",
      "emoticons template paste"
  ],
  toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
  toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | inserttime preview | forecolor backcolor",
  toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft | mybutton",


  setup: function (editor) {
   editor.addButton('mybutton', {
      type: 'menubutton',
      text: 'My split button',
      icon: false,
      onclick : function() {
        //js example
        var content = tinymce.get('area2').getContent();
        content.style.color='red'
        content.style.font='bold'

      }
})
}

});