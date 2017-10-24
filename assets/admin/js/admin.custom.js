(function( $ ) {
 
    // Add Color Picker to all inputs that have 'color-field' class
    $(function() {
        $('.color-picker').wpColorPicker();
    });
    
    $('.range-slider').ionRangeSlider({
   		min: 1,
   		max: 10
	});
     
})( jQuery );