const searchBar = document.querySelector(".users .search input"),
sarchBtn = document.querySelector(".users .search button");

sarchBtn.onclick = () =>{
    searchBar.classList.toggle("active");
    searchBar.focus();
    sarchBtn.classList.toggle("active");
}