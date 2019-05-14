$(document).ready(function(){
  
    
    
    
    $('#username').keyup(function()
                       
                       {
        var username = $('#username').val()
       
        
    
        /////////
       
 
       
        
       
        
        
        
        
    
    //////////////
    if(username!=''){
        
         
        
        
        var request = new XMLHttpRequest()
        var exists = 0;
        
request.open('GET', 'http://127.0.0.1:8000/user_exists/'+username, true)
request.onload = function() {
  // Begin accessing JSON data here
  var data = JSON.parse(this.response)

  if (request.status >= 200 && request.status < 400) {
  
       // console.log(username);   
        //console.log(data.user_exists);    
                
      
      if (data.user_exists == true){
         // console.log("ISTNIEJE");
              $('#status').html('<p>ISTNIEJE</p>');
      }
      else {
         // console.log("WOLNE");
              $('#status').html('<p>WOLNE</p>');
      }
      
  } else {
    console.log('error')
  }
}

request.send()
        
        
    }
    else {
        $('#status').html('');
    }
    
    });


  
    
    
    
    
    
    
});




