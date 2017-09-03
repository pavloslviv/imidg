/**
 * Created by Sergiy on 16.10.13.
 */
(function (sr) {
    sr.classes.views.ColorSelector = sr.classes.views.Popup.extend({

        events: _.extend({},sr.classes.views.Popup.prototype.events,{
            'change .color-sample-input': 'onColorSampleSelected',
            'click canvas': 'onSampleClick',
            'mousemove canvas': 'onSamplePreview'
        }),

        template: sr.core.loadTemplate('tmpl_color_selector'),
        initialize: function(initObj){
            _.bindAll(this,'destroy','show','hide');
            initObj = initObj || {};
            this.sampleImage = initObj.sampleImage || sr.data.sampleImage;
            this.onSelected = initObj.onSelected;
            this.render();
        },

        render: function(){
            this.$el.html(this.template({
                noSample: !this.sampleImage
            }));
            if(this.sampleImage){
                this.renderSample();
            }
            return this;
        },

        renderSample: function () {
            var width, height,
                image = this.sampleImage,
                $canvas = this.$('canvas'),
                canvas = $canvas.get(0),
                srcWidth = image.width,
                srcHeight = image.height;
            if(srcHeight>srcWidth){
                height = Math.min(srcHeight,550);
                width = Math.round(height*srcWidth/srcHeight);
            } else {
                width = Math.min(srcWidth,550);
                height = Math.round(srcHeight*width/srcWidth);
            }
            console.log('!!',image.width,image.height);
            canvas.width = width;
            canvas.height = height;
            canvas.getContext('2d').drawImage(image, 0, 0, width, height);
            this.$preview = this.$('#color-preview');
        },

        onColorSampleSelected: function (e) {
            var that = this,
                file = e.currentTarget.files && e.currentTarget.files[0],
                reader;
            if(!file || _.isUndefined(FileReader)) return;
            this.toggleSpinner(true);
            reader = new FileReader();
            reader.onload = function (e) {
                sr.data.lastUsedSample=e.target.result;
                var image = new Image();
                image.src = sr.data.lastUsedSample;
                image.addEventListener('load',function(){
                    that.sampleImage = sr.data.sampleImage = image;
                    that.toggleSpinner(false);
                    that.render();
                });
            }
            reader.readAsDataURL(file);
        },
        rgbToHex: function (r,g,b) {
            function componentToHex(c) {
                var hex = c.toString(16);
                return hex.length == 1 ? "0" + hex : hex;
            }

            return "" + componentToHex(r) + componentToHex(g) + componentToHex(b);
        },

        onSampleClick: function (e) {
            var pixelData = e.currentTarget.getContext('2d').getImageData(e.offsetX, e.offsetY, 1, 1).data;
            var color = this.rgbToHex(pixelData[0],pixelData[1],pixelData[2].toString(16));
            if(this.onSelected){
                this.onSelected(color);
            }
        },

        onSamplePreview: function (e) {
            var pixelData = e.currentTarget.getContext('2d').getImageData(e.offsetX, e.offsetY, 1, 1).data;
            this.$preview.css('background-color','rgba('+pixelData[0]+','+pixelData[1]+','+pixelData[2]+',1');
        },

        onCreate: function(){
        },
        onDestroy: function(){

        }
    });
})(SerenityShop);