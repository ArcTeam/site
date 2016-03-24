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
function rotatey(){ $('.hover').animate({rotation: 179},{duration: 300, step: function(now, fx) {$(this).css({"transform": "rotateY("+now+"deg)"});}});}
window.onresize = function(){ map.updateSize();}
var w=$("#mainWrap").width();
var w2=$("section#main").width();
$("aside#mainAside").css("width",w-w2-20);
var hfooter = $( window ).height();
$("#mainWrap").css("min-height",hfooter);
$('.hover').on("mouseenter", function(){
  var el = $(this);
  if(el.hasClass('flip')){el.removeClass('flip');}else{el.addClass('flip');}
});

var headW = $("header#main").width();
var navPos = $("nav#login").offset();

$("nav#login").on({
  mouseenter:function(){$("#settingUl").fadeIn('fast');},
  mouseleave:function(){$("#settingUl").fadeOut('fast');}
});
$(".prevent").on("click", function(e){e.preventDefault();});
