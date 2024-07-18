
function onClickHoldElement(){
    let data = JSON.parse(this.getAttribute("json"));
    window.currentHold[data["category"]] = data;
    document.getElementById('input-preview-'+data["category"]).value = data["id"];
    previewUpdate();
}

function previewUpdate(){
    let typeSpace = null;
    let styleCleaned = false;
    for(let category in window.currentHold){
        typeSpace = document.getElementById("preview-"+window.currentHold[category]["type"]);
        

        if(window.currentHold[category]["type"].includes("style")){
           
            if(styleCleaned){
                typeSpace.children[0].innerHTML += window.currentHold[category]["data"]+";";
            }
            else{
                typeSpace.children[0].innerHTML = "._preview {"+ window.currentHold[category]["data"]+";";
                styleCleaned = true;
            }
        }
        else{
            typeSpace.innerHTML = window.currentHold[category]["data"];
        }
    }
}


window.currentHold = {};

document.addEventListener('DOMContentLoaded', function() {
    previewUpdate();
    allHoldableContainer = document.querySelectorAll('li[json]');

    for(let i=0; i < allHoldableContainer.length; i++){
        allHoldableContainer[i].addEventListener("click", onClickHoldElement, false);
    }
}, false);
