function equalheight(container){
  var currentTallest = 0,
      currentRowStart = 0,
      rowDivs = new Array(),
      el,
      topPosition = 0;

  $(container).each(function() {
   el = $(this);
   $(el).height('auto')
   topPostion = el.position().top;

   if (currentRowStart != topPostion) {
    for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
    rowDivs[currentDiv].height(currentTallest);
    }
    rowDivs.length = 0; // empty the array
    currentRowStart = topPostion;
    currentTallest = el.height();
    rowDivs.push(el);
   } else {
    rowDivs.push(el);
    currentTallest = (currentTallest < el.height()) ? (el.height()) : (currentTallest);
   }
   for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
    rowDivs[currentDiv].height(currentTallest);
   }
  });
 } 
$(window).load(function() {
  if ($("body").width() > 479) {
    equalheight('.maxheight');
    equalheight('.maxheight1');
    equalheight('.maxheight2');
  }
}); 
$(document).ready(function(){
 $(window).resize(function () {
  $('.maxheight .height').height('auto');
   if ($("body").width() > 479) {
   equalheight('.maxheight');
   equalheight('.maxheight1');
   equalheight('.maxheight2');
   }
  }); 
}); 
