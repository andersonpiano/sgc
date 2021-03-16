$(document).ready(function(){
    $(document).on('keyup', '.charcount', function() {
        var component = $(this).val();
        //var output = 
        charcount(component);
    });
});


$(window).load(function(){

});

function charcount(component) {
    return component.length;
}