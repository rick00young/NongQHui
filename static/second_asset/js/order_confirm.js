/**
 * Created by rick on 15/5/10.
 */
var Order_confirm = function() {
    return {
        //status: {numberOK: !1,passwdOk: !1,number: "",passwd: ""},
        init: function() {

            this.bindEditAddressEvent();
            this.bindCreateAddressEvent();
            this.bindSaveAddressEvent();
            this.bindSetOrderAddressEvent();
            this.setOrderAddress('');

        },

        bindEditAddressEvent:function(){
            var that = this;
            var $address_body = $('.address_body');
            $address_body.undelegate('a.address_edit','click');
            $address_body.delegate('a.address_edit','click',function(){
                $this = $(this);
                $('#save_address').data('edit_address', $this);
                var address_id = $this.attr('address_id');

                $('#addressModalLabel').text('编辑收货人信息').attr('address_id', address_id);

                var receiver = $this.attr('receiver');
                var province = $this.attr('province');
                var city = $this.attr('city');
                var district = $this.attr('district');
                var street = $this.attr('street');
                var address = $this.attr('address');
                var mobile = $this.attr('mobile');
                var email = $this.attr('email');

                var $receiver_name = $('#receiver_name');
                var $province =  $('#province');
                var $city =  $('#city');
                var $district =  $('#district');
                var $receiver_detail_address = $('#receiver_detail_address');
                var $receiver_mobile = $('#receiver_mobile');
                var $receiver_email = $('#receiver_email');

                if(CONFIG['shipment_address'] && CONFIG['shipment_address']['address_' + address_id]){

                }else{
                    CONFIG['shipment_address']['address_' + address_id] = {
                        'id':address_id,
                        'receiver':receiver,
                        'province':province,
                        'city':city,
                        'district':district,
                        'street':street,
                        'address':address,
                        'mobile':mobile,
                        'email':email
                    };
                }
                var shipment = CONFIG['shipment_address']['address_' + address_id]
                $receiver_name.val(shipment.receiver);
                $receiver_detail_address.val(shipment.address);
                $receiver_mobile.val(shipment.mobile);
                $receiver_email.val(shipment.email);




                //页在初始化时,省份已加载完成,所以可以直接setProvince
                //而城市与地区则需要事件触发,所以需提前将用户选好的数据放到option里

                //<option value="">城市</option> 因为通过元素个数来判断数据是否已经写入option,个数为1则没有写入,>1则已经写入
                var city_option = '<option value="" selected="selected">'+shipment.city+'</option>';
                $city.html(city_option);

                //<option value="">地区</option> 同上
                //var district_option = '<option value="" selected="selected">'+district+'</option>';
                //$district.html(district_option);


                var dfd = $.Deferred();
                dfd.done(function(){
                        Address.setProvince(shipment.province);
                        //console.log('1');
                }).done(function(){
                        Address.triggerEvent('province');
                        //console.log('2');
                }).done(function(){
                        Address.setCity(shipment.city);
                }).done(function(){
                        Address.triggerEvent('city');
                        //console.log('3');
                }).done(function(){
                        Address.setDistrict(shipment.district);
                });

                dfd.resolve();
                //$('#pop_address_modal').click();
//                return false;
//                Address.setProvince(province);
//                Address.triggerEevent('province', function(){
//                    Address.setCity(city);
//                });
//
//                var district_option = '<option>地区</option><option selected="selected">'+district+'</option>';
//                $district.html(district_option);


                $('#pop_address_modal').click();

                //console.log(CONFIG['shipment_address']['address_' + address_id]);


            });

        },

        bindCreateAddressEvent:function(){
            var that = this;
            $('#address_create').click(function(){
                $this = $(this);

                var address_id = $this.attr('address_id');

                $('#addressModalLabel').text('添加收货人信息').attr('address_id', 0);


                var $receiver_name = $('#receiver_name');
                //var $province =  $('#province');
                //var $city =  $('#city');
                //var $district =  $('#district');
                var $receiver_detail_address = $('#receiver_detail_address');
                var $receiver_mobile = $('#receiver_mobile');
                var $receiver_email = $('#receiver_email');

                $receiver_name.val('');
                Address.setProvince('');
                Address.setCity('');
                Address.setDistrict('');

                $receiver_detail_address.val('');
                $receiver_mobile.val('');
                $receiver_email.val('');
                $('#pop_address_modal').click();
                return ;
            });

        },

        bindSetOrderAddressEvent:function(){
            var that = this;
            var $address_title = $('.address_title');
            $address_title.undelegate('button.option-item', 'click')
            $address_title.delegate('button.option-item', 'click', function(){
                //alert('ppp');
                var $this = $(this);
                $('.address_title').each(function(){
                    $(this).find('div').find('button').removeClass('item-selected');
                });
                $this.addClass('item-selected');

                var address_id = $this.attr('address_id');
                var receiver = $this.attr('receiver');
                var province = $this.attr('province');
                var city = $this.attr('city');
                var district = $this.attr('district');
                var street = $this.attr('street');
                var address = $this.attr('address');
                var mobile = $this.attr('mobile');
                var email = $this.attr('email');

                if(CONFIG['shipment_address'] && CONFIG['shipment_address']['address_' + address_id]){

                }else{
                    CONFIG['shipment_address']['address_' + address_id] = {
                        'id':address_id,
                        'receiver':receiver,
                        'province':province,
                        'city':city,
                        'district':district,
                        'street':street,
                        'address':address,
                        'mobile':mobile,
                        'email':email
                    };
                }

                //最终定单地址
                //CONFIG['order_address'] = CONFIG['shipment_address']['address_' + address_id];
                that.setOrderAddress(CONFIG['shipment_address']['address_' + address_id]);
            })
        },

        bindSaveAddressEvent:function(){
            var that = this;
            $('#save_address').click(function(){

                $this = $(this);
                var $edit_address = $this.data('edit_address');
                //Debug.log($edit_address);
                var address_id = $('#addressModalLabel').attr('address_id');

                var $receiver_name = $('#receiver_name');
                var $province =  $('#province');
                var $city =  $('#city');
                var $district =  $('#district');
                var $receiver_detail_address = $('#receiver_detail_address');
                var $receiver_mobile = $('#receiver_mobile');
                var $receiver_email = $('#receiver_email');

                var receiver = $receiver_name.val();
                var province_id = $province.val();
                var province = $province.find("option:selected").text();
                var city_id = $city.val();
                var city = $city.find("option:selected").text();
                var district_id = $district.val();
                var district = $district.find("option:selected").text();
                var address = $receiver_detail_address.val();
                var mobile = $receiver_mobile.val();
                var email = $receiver_email.val();

                if(!receiver){
                    alert('请填写正确的收货人姓名!');
                    return false
                }

                if(!province || province == '省份'){
                    alert('请选择省份!');
                    return false;
                }

                if(!city || city == '城市'){
                    alert('请选择城市!');
                    return false;
                }

                if(!district || district == '地区'){
                    alert('请选择地区!');
                    return;
                }

                if(!address){
                    alert('请填写正确的详细的地址!');
                    return false;
                }
                if(!mobile || isNaN(mobile) || mobile.length !== 11 || !RegExp(/^(0|86|17951)?(13[0-9]|15[012356789]|18[0-9]|14[57])[0-9]{8}$/).test(mobile)){
                    alert('请填写正确的电话号码!');
                    return false;
                }

                if(!email || !RegExp(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/).test(email)){
                    alert('请填写正确的邮箱!');
                    return false;
                }

                var query = {
                    'id':address_id,
                    'receiver':receiver,
                    'province':province,
                    'city':city,
                    'district':district,
                    'address':address,
                    'mobile':mobile,
                    'email':email
                };

                $.post('/index/save_shipment_address', query, function(result){
                    if(0 != result.errno){
                        alert('服务器忙,请您稍后再试!');
                        return false;
                    }
                    query['id'] = result.data.id;
                    CONFIG['shipment_address']['address_' + result.data.id] = query;
                    $('.modal-backdrop').click();

                    //render html
                    var shipment = CONFIG['shipment_address']['address_' + result.data.id];
                    var detail_address = shipment.receiver + ' '+ shipment.province + ' ' + shipment.city +' '+ shipment.district + ' ' + shipment.address + ' ' + shipment.mobile.replace(/(\d{3})\d{4}(\d{4})/, "$1****$2");
                    Debug.log(detail_address);
                    Debug.log($this.parent('span').prev('span.detail_address'));

                    //新地址会设为默认地址
                    that.setOrderAddress(shipment);

                    if(address_id && address_id > 0){
                        //更新 记录更新为新的地址
                        $edit_address.parent('span').prev('span.detail_address').text(detail_address);
                    }else{
                        //新增地址 渲染页面并将此新地址设为默认
                        if(that.renderNewAddress(shipment, detail_address)){
                            that.bindEditAddressEvent();
                            that.bindSetOrderAddressEvent();
                        }else{
                            return;//如要出错,则返回
                        }
                        //$('#address_' + shipment.id).find('.address_title').click();
                    }

                    $('.address_title').each(function(){
                        var $button = $(this).find('div').find('button')
                        if($button.attr('address_id') ==  shipment.id){
                            $button.addClass('item-selected');
                        }else{
                            $button.removeClass('item-selected');
                        }
                    });

                });


            });
        },

        //页面初始化获取默认地址  手动设置新地址
        setOrderAddress:function(data){
            if(data){
                CONFIG['order_address'] = data;
                return true;
            }

            $('.address_title').each(function(){
                var $this = $(this);
                var $button = $this.find('div').find('button.option-item ');

                if( $button && $button.hasClass('item-selected') ){
                    var address_id = $button.attr('address_id');
                    var receiver = $button.attr('receiver');
                    var province = $button.attr('province');
                    var city = $button.attr('city');
                    var district = $button.attr('district');
                    var street = $button.attr('street');
                    var address = $button.attr('address');
                    var mobile = $button.attr('mobile');
                    var email = $button.attr('email');

                    if(CONFIG['shipment_address'] && CONFIG['shipment_address']['address_' + address_id]){

                    }else{
                        CONFIG['shipment_address']['address_' + address_id] = {
                            'id':address_id,
                            'receiver':receiver,
                            'province':province,
                            'city':city,
                            'district':district,
                            'street':street,
                            'address':address,
                            'mobile':mobile,
                            'email':email
                        };
                    }

                    //最终定单地址
                    CONFIG['order_address'] = CONFIG['shipment_address']['address_' + address_id];
                }

            });
        },
        renderNewAddress:function(data, detail_address){
            if(!data || !detail_address) return false;
            var tpl = ''
            +'<div class="row row-table" id="address_'+data.id+'">'
            +    '<div class="col-xs-3 address_title">'
            +    '<div class="panel-body">'
            +        '<button class="option-item item-selected" address_id="'+data.id+'" receiver="'+data.receiver+'" province="'+data.province+'" city="'+data.city+'" district="'+data.district+'" street="'+data.street+'" address="'+data.address+'" mobile="'+data.mobile+'" email="'+data.email+'">'
            +            '<span>'+data.receiver+' '+data.province+'</span>'
            +        '</button>'
            +    '</div>'
            +    '</div>'
            +    '<div class="col-xs-9 address_body">'
            +        '<div class="panel-body">'
            +            '<span class="detail_address">'
            +            detail_address
            +            '</span>'
            +            '<span class="pull-right">'
            +                '<button class="hide">设为默认地址</button>'
            +                '<a href="javascript:void(0)" class="address_edit" address_id="'+data.id+'" receiver="'+data.receiver+'" province="'+data.province+'" city="'+data.city+'" district="'+data.district+'" street="'+data.street+'" address="'+data.address+'" mobile="'+data.mobile+'" email="'+data.email+'">编辑</a>'
            +                '<button class="hide">删除</button>'
            +            '</span>'
            +        '</div>'
            +    '</div>'
            +'</div>';

            if($('#address_container').append(tpl)){
                return true;
            };
            return false;
        }

    }
}();

var address_modal = function() {
    return {
        init: function() {
            this.bindEditEvent();

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
    Order_confirm.init()
});