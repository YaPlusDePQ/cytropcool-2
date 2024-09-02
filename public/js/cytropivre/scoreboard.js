
function update(){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200){
            document.getElementById('space').innerHTML = this.response;
        }
    }

    xhttp.open("GET", "./scoreboard/update");
    xhttp.send()
}

window.setInterval(function(){
    update();
}, 60000)