

var myHeading = document.querySelector('h1');
myHeading.textContent = 'Apolinar to hui!';
alert('hello!');

var myImage = document.querySelector('img');

myImage.onclick = function() {
    var mySrc = myImage.getAttribute('src');
    if(mySrc === 'images/kot.jpg') {
      myImage.setAttribute ('src','images/krowa.jpg');
    } else {
      myImage.setAttribute ('src','images/kot.jpg');
    }
}


var myButton = document.querySelector('button');
var myHeading = document.querySelector('h1');

myButton.onclick = function() {
  setUserName();
}

function setUserName() {
  var myName = prompt('Please enter your name.');
  localStorage.setItem('name', myName);
  myHeading.textContent = 'Mozilla is cool, ' + myName;
}

