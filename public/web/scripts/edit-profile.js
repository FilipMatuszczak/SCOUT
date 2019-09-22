$(document).ready(function(){





/////////////wczytywanie technologii uzytkownika

    var userID=document.getElementById('UserID').value;

    var requestTechUser = new XMLHttpRequest()


    requestTechUser.open('GET', 'http://127.0.0.1:8000/technologies/' + userID, true)
    requestTechUser.onload = function () {

        var data = JSON.parse(this.response)


        if (requestTechUser.status >= 200 && requestTechUser.status < 400) {
            //console.log(userID);
            for (i = 1; i < data.length; i++) {
                //console.log(data[i]);

                $('#tech-forms').append('<div> <input type="TechList" class="bio tech" id="tech" placeholder="Technologie" name = "technologies[]"  maxlength="50" list="TechList"><datalist id="TechList"></datalist> <button type="button" id="button-less" class="less btad">-</button></div>');

            }
            if (data.length>0) {
            var i=0;
            $(".tech").each(function() {
                var element = $(this);
                if (element.val() == "") {
                    element.val(data[i]);
                    i++;
                }
            });}

        } else {
            console.log('error')
        }


    }

    requestTechUser.send()

/////end technologii


/////////////wczytywanie języków uzytkownika

    var requestLangUser = new XMLHttpRequest()


    requestLangUser.open('GET', 'http://127.0.0.1:8000/languages/' + userID, true)
    requestLangUser.onload = function () {

        var data = JSON.parse(this.response)


        if (requestLangUser.status >= 200 && requestLangUser.status < 400) {
            //console.log(userID);
            for (i = 1; i < data.length; i++) {
                //console.log(data[i]);

                $('#languages-forms').append('<div> <input type="LangList" class="bio lang down" placeholder="Język" name="languages[]" maxlength="50" id="lang" list="LangList"><datalist id="LangList"></datalist> <button type="button" id="button-less" class="less btad">-</button></div>');

            }
            if (data.length>0) {
                var i = 0;
                $(".lang").each(function () {
                    var element = $(this);
                    if (element.val() == "") {
                        element.val(data[i]);
                        i++;
                    }
                });
            }
        } else {
            console.log('error')
        }


    }

    requestLangUser.send()

/////end technologii



/////////////wczytywanie miast uzytkownika

    var requestCityUser = new XMLHttpRequest()


    requestCityUser.open('GET', 'http://127.0.0.1:8000/cities/' + userID, true)
    requestCityUser.onload = function () {

        var dataC = JSON.parse(this.response)


        if (requestCityUser.status >= 200 && requestCityUser.status < 400) {
            //console.log(userID);
            for (i = 1; i < dataC.length; i++) {
                //console.log(data[i]);

                $('#cities-forms').append('<div> <input type="CityList" class="bio city down" placeholder="Miasto" maxlength="50" id="city" name="cities[]" list="CityList"><datalist id="CityList"></datalist> <button type="button" id="button-less" class="less btad">-</button></div>');

            }
            if (dataC.length>0) {

            var i = 0;
            $(".city").each(function () {
                var element = $(this);
                if (element.val() == "") {
                    element.val(dataC[i]);
                    i++;
                }
            });
        }
        } else {
            console.log('error')
        }


    }

    requestCityUser.send()

/////end miast





/////////more forms
    $('#button-moreC').click(function() {
        ///todo dodaje nowe okno formularza
        i++;

        $('#cities-forms').append('<div> <input type="text" class="bio city down" placeholder="Miasto" maxlength="50" id="lang" name="cities[]" list="CityList"><datalist id="CityList"></datalist> <button type="button" id="button-less" class="less btad">-</button></div>');


        ///////////
    });



    /////////more forms
    $('#button-more').click(function() {
        ///todo dodaje nowe okno formularza
        i++;    
        
       $('#languages-forms').append('<div> <input type="text" class="bio lang down" placeholder="Język" maxlength="50" id="lang" name="languages[]" list="LangList"><datalist id="LangList"></datalist> <button type="button" id="button-less" class="less btad">-</button></div>');
      
        
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
        
       $('#tech-forms').append('<div> <input type="text" class="bio tech" id="tech" placeholder="Technologie" maxlength="50" name="technologies[]" list="TechList"><datalist id="TechList"></datalist> <button type="button" id="button-less" class="less btad">-</button></div>');
      
        
      ///////////
    });
    
    
     $('#tech-forms').on("click", ".less", function(e) {
        e.preventDefault();
        $(this).parent('div').remove();
        i--;
    })


    $('#cities-forms').on("click", ".less", function(e) {
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
    $('#cities-forms').on("keyup",".city",function(callback)
                       {
  
  var city =  $(this).val()
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




