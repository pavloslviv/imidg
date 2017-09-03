function Home() {
    
    function initSome() {
        var item = $('body');
        
        if(item.length) {
            console.log('initSome');
        }    
    }      // private
    
    return {
        initOther: function() {
            var item = $('html');
    
            if(item.length) {
                console.log('initOther');
                initSome();
            }
        }    // public
    }
}

$(document).ready(function(){
    new Home().initOther();
});
