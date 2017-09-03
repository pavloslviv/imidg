(function(){var t,i,n,s,e,r,o,a,h;if(n={version:"2.3.3",name:"jQuery-runner"},o=this.jQuery||this.Zepto||this.$,!o||!o.fn)throw new Error("["+n.name+"] jQuery or jQuery-like library is required for this plugin to work");e={},s=function(t){return(10>t?"0":"")+t},h=1,r=function(){return"runner"+h++},a=function(t,i){return t["r"+i]||t["webkitR"+i]||t["mozR"+i]||t["msR"+i]||function(t){return setTimeout(t,30)}}(this,"equestAnimationFrame"),i=function(t,i){var n,e,r,o,a,h,u,p,f,l,c;for(i=i||{},p=[36e5,6e4,1e3,10],h=["",":",":","."],a="",o="",r=i.milliseconds,e=p.length,f=0,0>t&&(t=Math.abs(t),a="-"),n=l=0,c=p.length;c>l;n=++l)u=p[n],f=0,t>=u&&(f=Math.floor(t/u),t-=f*u),(f||n>1||o)&&(n!==e-1||r)&&(o+=(o?h[n]:"")+s(f));return a+o},t=function(){function t(i,n,s){var a;return this instanceof t?(this.items=i,a=this.id=r(),this.settings=o.extend({},this.settings,n),e[a]=this,i.each(function(t,i){o(i).data("runner",a)}),this.value(this.settings.startAt),void((s||this.settings.autostart)&&this.start())):new t(i,n,s)}return t.prototype.running=!1,t.prototype.updating=!1,t.prototype.finished=!1,t.prototype.interval=null,t.prototype.total=0,t.prototype.lastTime=0,t.prototype.startTime=0,t.prototype.lastLap=0,t.prototype.lapTime=0,t.prototype.settings={autostart:!1,countdown:!1,stopAt:null,startAt:0,milliseconds:!0,format:null},t.prototype.value=function(t){this.items.each(function(i){return function(n,s){var e;n=o(s),e=n.is("input")?"val":"text",n[e](i.format(t))}}(this))},t.prototype.format=function(t){var n;return n=this.settings.format,(n=o.isFunction(n)?n:i)(t,this.settings)},t.prototype.update=function(){var t,i,n,s,e;this.updating||(this.updating=!0,n=this.settings,e=o.now(),s=n.stopAt,t=n.countdown,i=e-this.lastTime,this.lastTime=e,t?this.total-=i:this.total+=i,null!==s&&(t&&this.total<=s||!t&&this.total>=s)&&(this.total=s,this.finished=!0,this.stop(),this.fire("runnerFinish")),this.value(this.total),this.updating=!1)},t.prototype.fire=function(t){this.items.trigger(t,this.info())},t.prototype.start=function(){var t;this.running||(this.running=!0,(!this.startTime||this.finished)&&this.reset(),this.lastTime=o.now(),t=function(i){return function(){i.running&&(i.update(),a(t))}}(this),a(t),this.fire("runnerStart"))},t.prototype.stop=function(){this.running&&(this.running=!1,this.update(),this.fire("runnerStop"))},t.prototype.toggle=function(){this.running?this.stop():this.start()},t.prototype.lap=function(){var t,i;return i=this.lastTime,t=i-this.lapTime,this.settings.countdown&&(t=-t),(this.running||t)&&(this.lastLap=t,this.lapTime=i),i=this.format(this.lastLap),this.fire("runnerLap"),i},t.prototype.reset=function(t){var i;t&&this.stop(),i=o.now(),"number"!=typeof this.settings.startAt||this.settings.countdown||(i-=this.settings.startAt),this.startTime=this.lapTime=this.lastTime=i,this.total=this.settings.startAt,this.value(this.total),this.finished=!1,this.fire("runnerReset")},t.prototype.info=function(){var t;return t=this.lastLap||0,{running:this.running,finished:this.finished,time:this.total,formattedTime:this.format(this.total),startTime:this.startTime,lapTime:t,formattedLapTime:this.format(t),settings:this.settings}},t}(),o.fn.runner=function(i,s,r){var a,h;switch(i||(i="init"),"object"==typeof i&&(r=s,s=i,i="init"),a=this.data("runner"),h=a?e[a]:!1,i){case"init":new t(this,s,r);break;case"info":if(h)return h.info();break;case"reset":h&&h.reset(s);break;case"lap":if(h)return h.lap();break;case"start":case"stop":case"toggle":if(h)return h[i]();break;case"version":return n.version;default:o.error("["+n.name+"] Method "+i+" does not exist")}return this},o.fn.runner.format=i}).call(this);