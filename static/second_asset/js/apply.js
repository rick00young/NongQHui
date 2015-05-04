/**
 * Created by rick on 15/5/3.
 */
var apply = function() {
    return {
        //status: {numberOK: !1,passwdOk: !1,number: "",passwd: ""},
        init: function() {
            //this.bindEvent();
            this.bindApplyEvent();
            //this.bindReloadComment();
        },

        bindApplyEvent:function(t){
            var that = this;
            $('#apply_btn').click(function(){
                $this = $(this);
                var $apply_name = $('#apply_name');
                var $apply_phone =  $('#apply_phone');
                var $apply_address =  $('#apply_address');
                var $apply_email =  $('#apply_email');


                var apply_name = $apply_name.val();
                if(!apply_name){
                    alert('您还没有填写姓名.');
                    return false;
                }

                var apply_phone = $apply_phone.val();
                if(!apply_phone || apply_phone.length != 11){
                    alert('您还没有填写联系电话或填写的电话不正确.');
                    return false;
                }

                var apply_address = $apply_address.val();
                if(!apply_address){
                    alert('您还没有填写联系地址.');
                    return false;
                }

                var apply_email = $apply_email.val();
                if(!apply_email){
                    //alert('您还没有填写联系地址.');
                    //return false;
                }
                $this.text('正在提交');
                var query = {
                    'apply_name':apply_name,
                    'apply_phone':apply_phone,
                    'apply_email':apply_email,
                    'apply_address':apply_address
                };

                $.post('/index/apply_seller', query, function(result){
                    //console.log(result);
                    if(0 != result.errno){
                        alert('服务器出错了,请您稍后再试');
                        return false;
                    }
                    $this.text('发送');
                    $('.modal-backdrop').click();
                    if(confirm('申请已经成功提交,请您耐心等待!')){
                        location.href = location.href;
                    }else{
                        location.href = location.href;
                    }

                });
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
        }

    }
}();
$(function() {
    apply.init()
});/**
 * Created by rick on 15/5/4.
 */
