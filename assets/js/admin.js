

/* ===========================
   SIDEBAR DROPDOWN ACTIVE
=========================== */


document.addEventListener("DOMContentLoaded", function(){



const dropdownLinks = document.querySelectorAll(
    ".sidebar-link[data-bs-toggle='collapse']"
);





dropdownLinks.forEach(function(link){



link.addEventListener("click", function(){



let target = this.getAttribute("href");



let menu = document.querySelector(target);





if(menu){



menu.classList.toggle("show");



this.classList.toggle("collapsed");



}



});



});



});

/* ===========================
   MOBILE SIDEBAR TOGGLE
=========================== */


document.addEventListener("DOMContentLoaded", function(){



const sidebar = document.querySelector(".admin-sidebar");

const toggleBtn = document.querySelector(".sidebar-toggle");





if(toggleBtn && sidebar){



toggleBtn.addEventListener("click",function(){



sidebar.classList.toggle("show");



});



}





});









/* ===========================
   ACTIVE MENU LINK
=========================== */


document.addEventListener("DOMContentLoaded", function(){



let currentUrl = window.location.pathname;



let links = document.querySelectorAll(
    ".sidebar-link, .sidebar-submenu a"
);





links.forEach(function(link){



let href = link.getAttribute("href");





if(href && currentUrl.includes(href)){



link.classList.add("active");





let parentMenu = link.closest(".collapse");



if(parentMenu){



parentMenu.classList.add("show");



}



}



});



});