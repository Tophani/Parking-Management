$(function () {
    
    //Datetimepicker plugin
    $('.datetimepicker').datetimepicker({
        format:'Y-m-d H:i', 
        minDate:0,
        // inline:true,
        minDate: 0,
        step: 5,
    });
 
    //Datetimepicker plugin
    $('.datepicker').datepicker({
        dateFormat:'yy-mm-dd', 
        timepicker:false
    });
     

    $('.timepicker').datepicker({
        datepicker:false,
        format:'H:i',
        // allowTimes:[
        // '12:00', '13:00', '15:00', 
        // '17:00', '17:05', '17:20', '19:00', '20:00'
        // ]
    });


    //preloader
    $(window).load(function() {
        $(".loader").fadeOut("slow"); 
    });


    //button submit style
    var btn = $('button[type=submit]');
    btn.on('click', function(){ 
        var txt = $(this).text();
        if (txt == 'Update')
        {
            $(this).text('Updating...');
        }
        else if (txt == 'Save')
        {
            $(this).text('Saving...');
        } 
        else if (txt == 'Send')
        {
            $(this).text('Sending...');
        } 
    });
 
});



//print a div
function printContent(el){
    var restorepage  = $('body').html();
    var printcontent = $('#' + el).clone();
    $('body').empty().html(printcontent);
    window.print();
    $('body').html(restorepage); 
    history.go(0);
}


