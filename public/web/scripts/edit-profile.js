$(document).ready(function(){
    
    
      /////////more forms
    $('#button-more').click(function() {
        ///todo dodaje nowe okno formularza
        i++;    
        
       $('#languages-forms').append('<div> <input type="LangList" class="bio lang down" placeholder="Język" maxlength="50" id="lang" list="LangList"><datalist id="LangList"></datalist> <button type="button" id="button-less" class="less btad">-</button></div>');
      
        
      ///////////
    });
    
    
     $('#languages-forms').on("click", ".less", function(e) {
        e.preventDefault();
        $(this).parent('div').remove();
        i--;
    })
    
    /////////////////
    
     $('#button-moret').click(function() {
        ///todo dodaje nowe okno formularza
        i++;    
        
       $('#tech-forms').append('<div> <input type="TechList" class="bio tech" id="tech" placeholder="Technologie" maxlength="50" list="TechList"><datalist id="TechList"></datalist> <button type="button" id="button-less" class="less btad">-</button></div>');
      
        
      ///////////
    });
    
    
     $('#tech-forms').on("click", ".less", function(e) {
        e.preventDefault();
        $(this).parent('div').remove();
        i--;
    })
    
    
    //////Function for getting technologies
  $('#tech-forms').on("keyup",".tech",function(callback)           
                       {
  
  var tech = $(this).val()
       
    ///console.log(tech);
    if(tech!=''){     
        var requestTech = new XMLHttpRequest()
        var exists = 0;
        
requestTech.open('GET', 'http://127.0.0.1:8000/filter/technology/'+ tech, true)
requestTech.onload = function() {
    
  var data = JSON.parse(this.response)
$('#TechList').html('');
  
  if (requestTech.status >= 200 && requestTech.status < 400) {
    
      for (i = 0; i<data.names.length;i++){
      ////console.log(data.names[i].name);
          
          if(data.names[i].name != tech){
          
      $('#TechList').append('<option value="' + data.names[i].name + '">');
      }
  }
  } else {
    console.log('error')
  }
  
  
  
}

requestTech .send()    
    }
    else {     
    }
    });
//////

  
    
    ////getting languages
    $('#languages-forms').on("keyup",".lang",function(callback)           
                       {
 // console.log("a");
  var lang = $(this).val()
       
   // console.log(lang);
    if(lang!=''){
        
         
        
        
        var requestLang = new XMLHttpRequest()
        var exists = 0;
        
requestLang.open('GET', 'http://127.0.0.1:8000/filter/language/'+ lang, true)
requestLang.onload = function() {
    
  var data = JSON.parse(this.response)
$('#LangList').html('');
  
  if (requestLang.status >= 200 && requestLang.status < 400) {
    
      for (i = 0; i<data.names.length;i++){
      ////console.log(data.names[i].name);
          
          if(data.names[i].name != lang){
          
      $('#LangList').append('<option value="' + data.names[i].name + '">');
      }
  }
  } else {
    console.log('error')
  }
  
  
  
}

requestLang.send()
        
        
    }
    else {
         
    }
    
    });
  /////////////////////////////
    
    
    
    
     ////getting cities
    $('#city').keyup(function(callback)           
                       {
  
  var city = $('#city').val()
       
    ///console.log(lang);
    if(city!=''){
        
         
        
        
        var request = new XMLHttpRequest()
        var exists = 0;
        
request.open('GET', 'http://127.0.0.1:8000/filter/city/'+ city, true)
request.onload = function() {
    
  var data = JSON.parse(this.response)
$('#CityList').html('');
  
  if (request.status >= 200 && request.status < 400) {
    
      for (i = 0; i<data.names.length;i++){
      ////console.log(data.names[i].name);
          
          if(data.names[i].name != city){
          
      $('#CityList').append('<option value="' + data.names[i].name + '">');
      }
  }
  } else {
    console.log('error')
  }
  
  
  
}

request.send()
        
        
    }
    else {
         
    }
    
    });
  /////////////////////////////
    
    
    
    
    //////////////photo preview
    function readURL(input) {

  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      $('#photo-edit').attr('src', e.target.result);
    }

    reader.readAsDataURL(input.files[0]);
  }
}

$("#fileToUpload").change(function() {
  readURL(this);
});
   //// 
    
    
        var i=1;
    
  
    
    
    
    
    //////// TODO  function for submiting form
    
    $('#register-form').click(function() {
        if(username_free){ //if form is valid submit form
			return true;
		}
         $('#username').css('border','1.5px solid red');
		event.preventDefault();
        
    }
           
        
        
    
                              );
    
    
    
});




