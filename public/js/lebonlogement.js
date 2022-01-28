var imagesbien = document.querySelectorAll('.imagesbien');
var modal = document.getElementById("myModal");
var modalImg = document.getElementById("img01");
var captionText = document.getElementById("caption");
imagesbien.forEach(img => {
    img.onclick = function(){
        modal.style.display = "block";
        modalImg.setAttribute('src',img.src)
        captionText.innerHTML = img.alt;
    }
});

var span = document.querySelector('.close')
span.onclick = function() {
  modal.style.display = "none";
}