$(document).ready(function(){
      
      
      
      
             ////popup message
            $(".message-ico").on("click", function () {
                event.preventDefault();
                $('#modal-message').show();
            });
            //close modals
            $(".cancel-btn").on("click", function () {
                event.preventDefault();
                $('#mssgtxt').val("");
                $('#modal-message').hide();
            });
      
      
      
      
      
      
      
      
      
      
       $('img[image-zoom]').addClass('img-enlargable').click(function(){
    var src = $(this).attr('src');
    $('<div>').css({
        background: 'RGBA(0,0,0,.7) url('+src+') no-repeat center',
        backgroundSize: 'contain',
        width:'100%', height:'100%',
        position:'fixed',
        zIndex:'10000',
        top:'0', left:'0',
        cursor: 'zoom-out'
    }).click(function(){
        $(this).remove();
    }).appendTo('body');
      
      
      
      
      });
         });