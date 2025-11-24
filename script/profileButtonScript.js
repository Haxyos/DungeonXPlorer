let profileButton = document.getElementById("profileButton");
let profileText = document.getElementById("profileText");

function showProfileLinks() {
    if (profileText.style.visibility == 'hidden') {
        profileText.style.visibility = 'visible';  
    }
    else {
        profileText.style.visibility = 'hidden';
    }
}

profileButton.addEventListener("click", showProfileLinks);