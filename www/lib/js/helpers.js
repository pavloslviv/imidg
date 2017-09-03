(function(h){
    h.html5Upload = function uploadFile(url, file, postData, callback) {
        var data = new FormData();
        var xhr = new XMLHttpRequest();


        if (typeof callback !== 'function') callback = function () {};

        xhr.upload.addEventListener("progress", function (e) {
            if (e.lengthComputable) {
                var progress = Math.round((e.loaded * 100) / e.total);
                callback({progress:progress, status:'uploading',loaded: e.loaded, total: e.total});
            }
        }, false);

        xhr.onreadystatechange = function () {
            if (this.readyState == 4) {
                if (this.status == 200) {
                    callback({progress:100, status:'finished', response: this.responseText});
                } else {
                    callback({progress:100, status:'error'});
                }
            }
        };

        xhr.open("POST", url);
        xhr.withCredentials=true;
        data.append('file', file);
        if (typeof postData== 'object'){
            for (i in postData){
                data.append(i,postData[i]);
            }
        }

        xhr.send(data);
    };
    window.Helpers=h;
})({})
$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};