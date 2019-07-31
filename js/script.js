var slideIndex = 1;
var firstLoad = 1;
showSlides(slideIndex);

function plusSlides(n)
{
	slideIndex += n;
	showSlides(slideIndex);
}

function currentSlide(n)
{
	slideIndex = n;
	showSlides(slideIndex);
}

function showSlides(n)
{
	var i;
	if (firstLoad == 1)
	{
		firstLoad = 0;
	}
	var slides = document.getElementsByClassName("Poze");
	var dots = document.getElementsByClassName("demo");
	//var captionText = document.getElementById("caption");
	if ( n > slides.length)
	{
		slideIndex = 1;
	}
	
	if ( n < 1 )
	{
		slideIndex = slides.length;
	}
	for ( i = 0; i < slides.length; i++)
	{
		slides[i].style.display = "none";
	}
	
	for ( i = 0; i < dots.length; i++)
	{
		dots[i].className = dots[i].className.replace("active","");
	}
	slides[slideIndex-1].style.display = "block";
	dots[slideIndex-1].className += " active";
	//captionText.innerHTML = dots[slideIndex - 1].alt;
}

function CumparareNereusita()
{
	alert('Nu aveti fonduri suficiente in portofel');
}

function CumparareReusita()
{
	alert('Tranzactie reusita');
}

function loadDoc(pagina) {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("demo").innerHTML =
      this.responseText;
    }
  };
  xhttp.open("GET",pagina, true);
  xhttp.send();
}


function formValidation()
{
	var x;
	// y, z, w;
	x = document.getElementById('CardNumber').value;	
	//z = document.getElementById("SecCode").value;
	//w = document.getElementById("Suma").value;
	alert(x);
	//console.log(y);
	//console.log(z);
	//console.log(w);
	
	if (x == "" || isNaN(x) || (x.toString().length != 12) )
	{
		alert("Numar de card invalid");
	}
	
	/*if (y == "")
	{
		alert("Nume de pe card invalid");
	}
	
	if (z == "" || isNaN(x) || (x.toString().length != 3) )
	{
		alert("Cod de securitate invalid");
	}
	
	if (w == "" || isNaN(x))
	{
		alert("Suma invalida");
	}*/
}