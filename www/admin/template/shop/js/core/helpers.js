/**
 * Created by Sergiy on 23.12.13.
 */
(function (sr) {
    var h = sr.helpers;
    h.longestCommonSubstring = function (strings){
        // init max value
        var match,
            currentChar,
            longestCommonSubstring = '',
            length = Math.min.apply(Math,strings.map(function(s){
                return s.length;
            }));
        for(var i=0; i<length;i++){
            match = true;
            currentChar = strings[0].charAt(i);
            _.each(strings,function(s){
                if(s.charAt(i)!==currentChar) match = false;
            });
            if(!match){
                longestCommonSubstring = strings[0].substring(0,i-1);
                break;
            }
        }
        return longestCommonSubstring;
    };
    h.formatPrice = function(price){
        price = _.isNaN(parseFloat(price)) ? 0 : parseFloat(price);
        return price.toFixed(2).replace('.00','').replace('.',',');
    };
})(SerenityShop);