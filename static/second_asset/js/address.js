/**
 * Created by rick on 15/5/3.
 */
var Address = function() {
    return {

        init: function() {
            //this.bindEvent();
            this.getProvince();
            this.bindProvinceEvent();
            this.bindCityEvent();
        },

        getProvince:function(t){
            var that = this;

            $this = $(this);

            if(CONFIG['province']){
                that.handleProvinceSelect(CONFIG['province']);
            }else{
                var query = {
                    'operation':'get_province'
                };
                $.get('/index/get_address', query, function(result){
                    //console.log(result);
                    if(0 != result.errno){
                        alert('服务器出错了,请您稍后再试');
                        return false;
                    }
                    CONFIG['province'] = result.data;
                    that.handleProvinceSelect(CONFIG['province']);
                });
            }

        },
        bindProvinceEvent:function(t){
            var that = this;
            $('#province').change(function(){

                var $this = $(this);
                var province_id = $this.val();

                if(!province_id) {
                    that.handleCitySelect({});
                    that.handleDistrictSelect({});

                    return false;
                }

                var query = {
                    'id':province_id,
                    'operation':'get_city'
                };

                if(CONFIG['city'] && CONFIG['city'][province_id]){
                    that.handleCitySelect(CONFIG['city'][province_id]['list']);
                }else{
                    $.get('index/get_address', query, function(result){
                        if(0 != result.errno){
                            alert('服务器忙,请您稍后再试!');
                            return false;
                        }
                        CONFIG['city'] ?  CONFIG['city'] : CONFIG['city'] = {};
                        CONFIG['city'][province_id] = result.data;
                        that.handleCitySelect(CONFIG['city'][province_id]['list']);
                    })
                }
                that.handleDistrictSelect({});

            });
        },

        bindCityEvent:function(t){
            var that = this;
            $('#city').change(function(){

                var $this = $(this);

                var city_id = $this.val();
Debug.log('on_chang ' + city_id);
                if(!city_id) {
                    that.handleDistrictSelect({});
                    return false;
                }

                var query = {
                    'id':city_id,
                    'operation':'get_district'
                };

                if(CONFIG['district'] && CONFIG['district'][city_id]){
                    that.handleDistrictSelect(CONFIG['district'][city_id]['list']);
                }else{
                    $.get('index/get_address', query, function(result){
                        if(0 != result.errno){
                            alert('服务器忙,请您稍后再试!');
                            return false;
                        }
                        CONFIG['district'] ? CONFIG['district'] :  CONFIG['district'] = {};
                        CONFIG['district'][city_id] = result.data;
                        that.handleDistrictSelect(CONFIG['district'][city_id]['list']);
                    })
                }

            });
        },

        handleProvinceSelect:function(t){

            var that = this;
            var $province = $('#province');
            var province_id = $province.val();
            var province_text = $province.find("option:selected").text();
            //alert(province_text);

            var options = '';
            options += '<option value="">省份</option>';
            for(var i in t){
                if(t[i].id == province_id || province_text == t[i].name){
                    options += '<option value="'+t[i].id+'" selected="selected">'+t[i].name+'</option>';
                }else{
                    options += '<option value="'+t[i].id+'">'+t[i].name+'</option>';
                }
            }
            //console.log(options);
            $province.html(options);
        },

        handleCitySelect:function(t){
            var that = this;
            var $city = $('#city');
            var city_id = $city.val();
            var city_text = $city.find("option:selected").text();

            var options = '';
            options += '<option value="">城市</option>';
            for(var i in t){
                if(t[i].id == city_id || city_text == t[i].name){
                    options += '<option value="'+t[i].id+'" selected="selected">'+t[i].name+'</option>';
                }else{
                    options += '<option value="'+t[i].id+'">'+t[i].name+'</option>';
                }
            }
            //console.log(options);
            $city.html(options);
        },

        handleDistrictSelect:function(t){
            var that = this;
            var $district = $('#district');
            var district_id = $district.val();
            var district_text = $district.find("option:selected").text();
//Debug.log('handleDistrictSelect');
            var options = '';
            options += '<option value="">地区</option>';

            for(var i in t){
                if(t[i].id == district_id || district_text == t[i].name){
                    options += '<option value="'+t[i].id+'" selected="selected">'+t[i].name+'</option>';
                }else{
                    options += '<option value="'+t[i].id+'">'+t[i].name+'</option>';
                }
            }
            //console.log(options);
            $district.html(options);
        },

        setProvince:function(province){
            if(!province) return false;
            var that = this;
            var $province = $('#province');
            $province.find('option').each(function(agr){
                var text = $(this).text();
                if(text == province){
                    $(this).attr('selected','selected');
                }else{
                    $(this).removeAttr('selected');
                }
            });
        },
        triggerEvent:function(name){
            if(!name) return false;

            if('province' == name){
                $('#province').change();

            }else if('city' == name){
                //哎,太乱了,这个逻辑,他妈的老子服了
                var $city = $('#city');
                var count = 0

                var intervalID = setInterval(function(){
                    if($city.find('option').size() >= 2 ){

                        clearInterval(intervalID);
                        $city.change();
                    }
                    count++;
                    if(count >= 10){
                        clearInterval(intervalID);
                    }
                }, 500);

            }else if('district' == name){

                $('#district').change();
            }
        },

        setCity:function(city){
            if(!city) return false;

            var that = this;
            var $city = $('#city');
            var count = 0
            var intervalID = setInterval(function(){
                if($city.find('option').size() >= 2 ){

                    clearInterval(intervalID);

                    $city.find('option').each(function(agr){
                        var text = $(this).text();
                        if(text == city){
                            $(this).attr('selected','selected');
                        }else{
                            $(this).removeAttr('selected');
                        }
                        Debug.log('setCity-' + city);
                    });

                }
                count++;
                if(count >= 10){
                    clearInterval(intervalID);
                }
            }, 500);


        },

        setDistrict:function(district){
            if(!district) return false;

            var that = this;
            var $district = $('#district');
            var count = 0
            var intervalID = setInterval(function(){
                if($district.find('option').size() >= 2 ){

                    clearInterval(intervalID);

                    $district.find('option').each(function(agr){
                        var text = $(this).text();
                        if(text == district){
                            $(this).attr('selected','selected');
                        }else{
                            $(this).removeAttr('selected');
                        }
                        Debug.log('setDistrict-' + district);
                    });

                }
                count++;
                if(count >= 10){
                    clearInterval(intervalID);
                }
            }, 500);


        }

    }
}();

$(function() {
    Address.init()
});
