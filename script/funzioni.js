(function ($) {
   $.fn.clickToggle = function (func1, func2) {
       var funcs = [func1, func2];
       this.data('toggleclicked', 0);
       this.click(function () {
           var data = $(this).data();
           var tc = data.toggleclicked;
           $.proxy(funcs[tc], this)();
           data.toggleclicked = (tc + 1) % 2;
       });
       return this;
   };
}(jQuery));

function rotate(el,deg){ var rot = deg; el.animate({rotation: rot},{duration: 300, step: function(now, fx) {$(this).css({"transform": "rotate("+now+"deg)"});}});}
window.onresize = function(){ map.updateSize();}
