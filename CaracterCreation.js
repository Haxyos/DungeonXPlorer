let divBouclier = document.getElementById("shield")
let divSort = document.getElementById("sort");
let select = document.getElementById("select")

select.addEventListener('change', function(){
    if (this.value == "Wizard"){
        divBouclier.classList.add("hidden")
        divSort.classList.remove("hidden")
    }
    else if (this.value == "Warrior"){
        divBouclier.classList.remove("hidden")
        divSort.classList.add("hidden")
    }
    else{
        divBouclier.classList.add("hidden")
        divSort.classList.add("hidden")
    }
});