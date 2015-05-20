/**
 * Created by rick on 15/5/10.
 */
var Debug = function() {
    return {
        env:'dev',
        log:function(data){
            if(this.env == 'dev' && window.console){
                console.log(data);
            }
        }
    }
}();