let list = document.querySelectorAll(".navigation li");

function activeLink() {
  list.forEach((item) => {
    item.classList.remove("hovered");
  });
  this.classList.add("hovered");
}
list.forEach((item) => item.addEventListener("mouseover", activeLink));


let toggle = document.querySelector(".toggle");
let navigation = document.querySelector(".navigation");
let main = document.querySelector(".main");

toggle.onclick = function () {
  navigation.classList.toggle("active");
  main.classList.toggle("active");
};
document.addEventListener('DOMContentLoaded', function() {
  const amisToggle = document.querySelector('.amis-toggle');
  const boiteDesAmis = document.querySelector('.boite_des_amis');
  
  if (amisToggle && boiteDesAmis) {
    amisToggle.addEventListener('click', function() {
      boiteDesAmis.classList.toggle('active');
    });
  }
});
