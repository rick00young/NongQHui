function getPicTpl($src, $md5, $ext){

    var button = $('<button class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button>');
    button.click(function(){
    //TODO删除图片数据
        alert('delete data...');
        return false;
    });
    var $tpl = $('<div class="col-md-3">'
                    + '<div class="uploaded_pic">'
                    + '<img src="'+$src+'" alt="..." class="img-thumbnail">'
                        //+ '<button class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button>'
                        + '</div>'
                + '</div>');

    $tpl.find('.uploaded_pic').append(button);
    return $tpl;
}

    $("#good_photo").uploadify({
        'swf'           : '/static/plugin/uploadify/uploadify.swf',
        'uploader'      : '/admin/upload',
        'fileObjName' : 'file',
        'buttonText' : '请选择上传图片',
        'fileTypeExts' : '*.gif; *.jpg; *.png',
        'fileSizeLimit' : '3MB',
        'onSelectOnce'   : function(event,data) {

        },
        'onUploadSuccess' : function(file, data, response) {
            //alert('The file ' + file.name + ' was successfully uploaded with a response of ' + response + ':' + data);
            //{"status":1,"data":{"code":0,"info":"uplode success!","md5":"489bd2daea761d3d65019bbe2e9ff7cc","ext":"jpg"}}

        var res = $.parseJSON(data);
        if(1 != res.status){
            alert('图片上传出问题了....');
            return;
        }
        src = '/source/images/thb2/100x100/' + res.data.dir + '/' +res.data.md5 + '.' + res.data.ext;
        var picTpl =getPicTpl(src);
        $('#goodPhotoBox').find('.row').append(picTpl);
            logoJson = JSON.stringify({'md5':res.data.md5, 'ext': res.data.ext});
        }
    });

    $("#submit").click(function () {
        var shop_name =   $("#shop_name").val();
        var shop_slogan =   $("#shop_slogan").val();
        var shop_des =    $("#shop_des").val();
        var shop_lat  =   $("#shop_lat").val();
        var shop_long =   $("#shop_long").val();
        var shop_id = $('#shop_id').val();

        if(!shop_name || !shop_slogan || !shop_des || !shop_lat || !shop_long){
            alert("您填入的数据不完整,请你检查后重新提交!");
            return false;
        }

        $.post("/admin/savegood", {
            'shop_name':shop_name,
            'shop_slogan':shop_slogan,
            'shop_des': shop_des,
            'shop_lat': shop_lat,
            'shop_long': shop_long,
            'shop_logo':logoJson,
            'shop_id' : shop_id
            },function(response){
            //console.log(response);

            if(0 == response.errno){
            alert('保存成功.');
                location.href = '/admin/shoplist';
            }else{
                alert('好像出问题了.');
            }
        })
    })

    $('#goodSegment_box').find('.goodSegment').click(function(){
        var data_title = $(this).attr('data-title');
        var segments = {'buy_needKnow':true,'good_intro':true, 'buy_detail':true,'use_list':true};
        if(segments[data_title] && data_title == 'good_intro'){
            $('#')
        }

    });


    //ueditor
    var ue = UE.getEditor('editor');