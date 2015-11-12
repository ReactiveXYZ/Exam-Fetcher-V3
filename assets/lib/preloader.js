/*! Prelodr v1.0.5 | MIT (c) 2015 José Luis Quintana */
function _typeof(e){return e&&"undefined"!=typeof Symbol&&e.constructor===Symbol?"symbol":typeof e}function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}var _createClass=function(){function e(e,t){for(var i=0;i<t.length;i++){var n=t[i];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}return function(t,i,n){return i&&e(t.prototype,i),n&&e(t,n),t}}(),Prelodr=function(){function e(t,i){_classCallCheck(this,e),this.queu={queue:[],offset:0,add:function(e){this.queue.push(e),this.offset+=1},shift:function(){var e=null;return this.queue.length>0&&(e=this.queue.shift()),e},first:function(){var e=null;return this.queue.length>0&&(e=this.queue[0]),e}},this._interval=0,this.isShown=!1,this.isAnimating=!1,this.isStart=!1,this.options={duration:750,prefixClass:"prelodr",show:null,hide:null},"object"===("undefined"==typeof t?"undefined":_typeof(t))?t.nodeName?(this.setContainer(t),this.setOptions(i)):(this.setContainer(window.document.body),this.setOptions(t)):this.container=window.document.body}return _createClass(e,[{key:"setOptions",value:function(e){this.options=this._merge(this.options,e)}},{key:"_merge",value:function(e,t){var i=void 0;if(t)for(i in t)t&&t[i]&&(e[i]=t[i]);return e}},{key:"setContainer",value:function(e){this.container=e}},{key:"_getId",value:function(){return Math.random().toString(36).slice(2)}},{key:"_show",value:function(e,t){var i=this;this.wrapper=window.document.createElement("span");var n=window.document.createElement("span"),s=window.document.createElement("span");n.appendChild(window.document.createTextNode(e)),this.wrapper.appendChild(n),n.appendChild(s),s.className=this.options.prefixClass+"-progressbar",this.element||(this.element=window.document.createElement("span"),this.element.className=this.options.prefixClass,this.container.appendChild(this.element)),this.element.appendChild(this.wrapper),setTimeout(function(){var e=i.options.prefixClass+" "+i.options.prefixClass+"-in";i.wrapper.children[0].className=i.options.prefixClass+"-in",i.element.className=e,setTimeout(function(){i.isShown=!0,i.isAnimating=!1,i.queu.shift(),i.options.show&&i.options.show(i,i.element),t&&t()},i.options.duration)},10)}},{key:"_hide",value:function(e){this.isShown&&(this.isShown=!1,this._prepOut(e))}},{key:"_queueWalk",value:function(){var e=this,t=this.queu.first();t&&"in"===t.is&&(this.isShown=!0,t.fn(function(){var t=e.queu.first();e.isStart=!1,t&&"out"===t.is&&t.fn(function(){e._queueWalk()})}))}},{key:"in",value:function(e){var t=this,i={id:this._getId(),is:"in",fn:function(i){return t._show(e,i)}};return this.queu.add(i),this.isStart||(this.isStart=!0,this._queueWalk()),this}},{key:"out",value:function(e){var t=this;if(this.queu.add({id:this._getId(),is:"out",fn:function(i){e&&"function"==typeof e?e(function(){t._hide(i)}):t._hide(i)}}),!this.isStart){var i=this.queu.first();i&&"out"===i.is&&i.fn(this._queueWalk)}return this}},{key:"_prepOut",value:function(e){var t=this;this.isAnimating=!0,this.queu.shift();var i=this.queu.first();this.wrapper.children[0].className="",setTimeout(function(){if(t.options.hide&&t.options.hide(t,[]),i)t.element.removeChild(t.wrapper);else{var n=t.options.prefixClass+" "+t.options.prefixClass+"-out";t.element.className=n,setTimeout(function(){t.container.removeChild(t.element),t.element=null},t.options.duration/1.5)}t.isAnimating=!1,t.isShown=!1,e&&e()},this.options.duration)}},{key:"isVisible",value:function(){return this.isShown}}]),e}();!function(){"object"===("undefined"==typeof module?"undefined":_typeof(module))&&"object"===_typeof(module.exports)?module.exports=Prelodr:"function"==typeof define&&define.amd?define([],function(){return Prelodr}):(window.Prelodr=Prelodr,window.jQuery&&(window.jQuery.fn.prelodr=function(e){var t=void 0,i=void 0;if("string"==typeof e){if(i=this.data("prelodr"),t=i[e],"undefined"!==i&&"string"==typeof e&&t)return t.apply(i,Array.prototype.slice.call(arguments,1));var n="Method "+e+" is not supported by jQuery.prelodr.";window.jQuery.error(n)}else i=new window.Prelodr(this[0],e),this.data("prelodr",i);return this}))}();