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
    let add_img_upload = '';
    let selector_div = document.querySelector(selector_identifier);
    if(selector_div.dataset.add_img_upload == '1'){
        add_img_upload = ' image ';
    }
    global_media_library_url = media_library_url;
    tinymce.init({

        setup: (editor) => {
            editor.ui.registry.addButton('videoWrap', {
                text: '(fix-video)',
                onAction: function (_) {
                    editor.focus();
                    editor.selection.setContent('<div class="video-container">' + editor.selection.getContent() + '</div>');
                }
            });
            editor.ui.registry.addButton('noP', {
                text: '<no-p>',
                onAction: function (_) {
                    editor.focus();
                    editor.selection.setContent('<div class="no-p">' + editor.selection.getContent() + '</div>');
                }
            });
          },

          
        selector: selector_identifier,
        plugins: 'image media code link lists codesample advlist autosave emoticons fullscreen help insertdatetime nonbreaking preview searchreplace table' ,
        toolbar: ['undo redo | '+ add_img_upload +' media videoWrap code align link hr insertdatetime | numlist bullist table | noP',
            'bold italic underline blocks | forecolor backcolor fontsize fontfamily styles | restoredraft preview | nonbreaking codesample emoticons | fullscreen help'],
        contextmenu: "link image inserttable | cell row column deletetable",
        directionality : "rtl",
        insertdatetime_dateformat: "%Y-%m-%d",
        /* without images_upload_url set, Upload tab won't show up*/
        images_upload_url: media_uploader_url,
        content_style: 'img {max-width: 100%; height:auto;}',
        file_picker_callback: function(callback, value, meta) {
            return tiny_mce_callback_handler.init(callback, value, meta);
        },
        image_class_list: [
            { title: 'None', value: '' },
            { title: 'Left', value: 'leftish-image' },
            { title: 'Right', value: 'rightish-imag' }
        ],
        content_style: "body .leftish-image{ float: left; } body .rightish-image{ float: right; }"
    });

}

function cancelTinymce(selector_identifier){
    tinymce.remove(selector_identifier);
}

tiny_mce_callback_handler = new Tiny_mce_callback_handler_class();
global_media_library_url = null;
