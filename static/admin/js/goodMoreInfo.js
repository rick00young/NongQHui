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
    //ueditor
    var editor = UE.getEditor('editor');

    var GOODINFO = {
        'good_intro': good_intro,
        'buy_needKnow': buy_needKnow,
        'buy_detail': buy_detail,
        'use_list': use_list
    };
    var CURRENT_OPERATION = 'good_intro';

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
            //logoJson = JSON.stringify({'md5':res.data.md5, 'ext': res.data.ext});
        }
    });

    $("#submit").click(function () {
        var good_name =   $("#good_name").val();
        var good_slogan =   $("#good_slogan").val();
        var good_des =    $("#good_des").val();
        var good_lat  =   $("#good_lat").val();
        var good_long =   $("#good_long").val();
        var good_id = $('#good_id').val();

        if(!good_name || !good_slogan || !good_des || !good_lat || !good_long){
            alert("您填入的数据不完整,请你检查后重新提交!");
            return false;
        }

        $.post("/admin/savegood", {
            'good_name':good_name,
            'good_slogan':good_slogan,
            'good_des': good_des,
            'good_lat': good_lat,
            'good_long': good_long,
            'good_logo':logoJson,
            'good_id' : good_id
            },function(response){
            //console.log(response);

            if(0 == response.errno){
            alert('保存成功.');
                location.href = '/admin/goodlist';
            }else{
                alert('好像出问题了.');
            }
        })
    })

    var $segments = $('#goodSegment_box').find('.goodSegment');
    $segments.click(function(){
        var data_title = $(this).attr('data-title');
        var segments = {'buy_needKnow':'购买须知','good_intro':'good_intro', 'buy_detail':'订购详情','use_list':'使用流程'};


        if(segments[data_title] && data_title == 'good_intro'){
            $('#upload, #goodPhotoBox').slideDown();
            $('#info_editor').slideUp();
        }else{
            //editor
            $('#upload, #goodPhotoBox').slideUp();
            $('#info_editor').slideDown();
            $('#segment_title').text(segments[data_title]);

        }
        saveCacheDataByType(CURRENT_OPERATION);
        CURRENT_OPERATION = data_title;
        getCacheDataByType(CURRENT_OPERATION);

        $segments.removeClass('btn-info').addClass('btn-default');
        $(this).addClass('btn-info').removeClass('btn-default');
    });




    function saveCacheDataByType(type){
        if('good_intro' == type){

        }else{
            GOODINFO[type] = editor.getContent();
            //console.log(GOODINFO[type]);
        }

    }

    function getCacheDataByType(type){
        var content = GOODINFO[type];
        editor.setContent(content);
    }