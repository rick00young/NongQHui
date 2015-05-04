/**
 * Created by rick on 15/5/3.
 */
var comment = function() {
    return {
        status: {numberOK: !1,passwdOk: !1,number: "",passwd: ""},
        init: function() {
            //this.bindEvent();
            this.bindAddComment();
            this.bindReloadComment();
            $('#more_comment').click();
        },
        bindEvent: function() {
            var t = this;
            $(".form-control.tel").blur(function() {
                t.checkNumber(this)
            }).click(function() {
                    t.removeAlert(this)
                }), $(".form-control.password").blur(function() {
                t.checkPWD(this)
            }).click(function() {
                    t.removeAlert(this)
                }), $(".submit .loginBtn").click(function() {
                t.checkInfo()
            }), document.onkeydown = function(s) {
                var i = s || window.event || arguments.callee.caller.arguments[0];
                13 == i.keyCode && t.checkInfo()
            }
        },
        bindAddComment:function(t){
            var that = this;
            $('#add_comment').click(function(){
                var content = $('#word_text').val();
                //alert(content);
                if(!content){
                    alert('您还没有填写评论.');
                    return false;
                }
                if(content.length > 800){
                    alert('您的评论内容太多了.');
                    return false;
                }
                var oid = $(this).attr('oid');
                if(!oid){
                    alert('出错了,请您稍后再试');
                }

                var query = {
                    'oid':oid,
                    'content':content
                };
                $.post('/index/comment_add', query, function(result){
                    //console.log(result);
                    if(0 != result.errno){
                        alert('服务器出错了,请您稍后再试');
                        return false;
                    }
                    that.createCommentTpl(result.data, 10);

                    $('#word_text').val('')
                });
            });

            $('#word_text').bind('input',function(){
                var content = $(this).val();
                var total = 800;
                var $word_count =  $('#word_count');
                if(content.length > total){
                    content = content.substr(0, total);
                    $(this).val(content);

                    //alert('to many');

                    $word_count.css({'color':'red'});

                    setTimeout(function(){
                        $word_count.css({'color':'#ccc'});
                    }, 2000);
                }
                var length = content.length;
                $word_count.text(length + '/' + total + '个字');

            });
        },
        bindReloadComment:function(t){
            var that = this;
            $('#more_comment').click(function(){
                var oid = $(this).attr('oid');
                var max_oid = $(this).attr('max_oid');
                var has_next = $(this).attr('has_next');
                var page = $(this).attr('page');
                var $this = $(this);

                if(!oid){
                    alert('出错了,请您稍后再试');
                    return false;
                }
                var query = {
                    'oid':oid,
                    'max_oid':max_oid,
                    'size':20
                };
                if('false' == has_next) { return false;}
                $.get('index/comment_list', query, function(result){
                    if(0 != result.errno){
                        alert('服务器忙,请您稍后再试!');
                        return false;
                    }
                    if(!result.data.has_next){
                        $this.attr('has_next', 'false');
                        $this.text('没有更多评论了');
                    }

                    for(var index in result.data['list']){
                        if(result.data['list'][index]._id >= max_oid){
                            max_oid = result.data['list'][index]._id;
                        }
                        that.createCommentTpl(result.data['list'][index], (page - 1) + index);
                    }
                    if(result.data['list'].length > 0){
                        $this.prev('p').hide();
                        $this.show();
                    }

                    $this.attr('page', page+1);
                    $this.attr('max_oid', max_oid);
                })
            });
        },
        createCommentTpl: function(obj, index) {
            //console.log(obj);
            var tpl = '';
            var content = obj.content;
            if(0 == obj.status){
                content = '该评论已删除.';
            }
            if(index > 0){
                tpl = '<div class="row row-space-2">'
                    +    '<div class="col-md-9 col-md-push-3">'
                    +    '<hr>'
                    +    '</div>'
                    +'</div>';
            }
            tpl += '<div class="row">'
                +'<div class="col-md-3 text-center row-space-2">'
                +    '<a href="#" class="media-photo media-round row-space-1 text-center" target="blank">'
                +       '<img alt="'+obj.from_user.nickname+'" style="width:68px;height:68px;" class="lazy"  height="68" src="'+obj.from_user.avator+'" title="'+obj.from_user.nickname+'" width="68" style="display: inline;">'
                +    '</a>'
                +    '<div class="name">'
                +        '<a href="#" class="text-muted link-reset" target="blank">'+obj.from_user.nickname+'</a>'
                +    '</div>'
                +'</div>'
                +'<div class="col-md-9">'
                +'<div class="row-space-2">'
                +    '<div data-review-id="29859696" data-original-text="" class="review-text expandable expandable-trigger-more expanded">'
                +        '<div class="expandable-content">'
                +            '<p>'+content+'</p>'
                +            '<div class="expandable-indicator expandable-indicator-light"></div>'
                +        '</div>'
                +        '<a class="expandable-trigger-more text-muted hide" href="#">'
                +            '<strong>更多内容</strong>'
                +        '</a>'
                +    '</div>'
                +    '<div class="text-muted review-subtext">'
                +        '<div class="review-translation-language" data-review-id="29859696">'
                +        '</div>'
                +        '<div class="date">'+obj.in_time+'</div>'
                +    '</div>'
                +'</div>'
                +'</div>'
            +'</div>';

            //console.log(tpl);
            $('#comment_down').before(tpl);
        }


    }
}();
$(function() {
    comment.init()
});