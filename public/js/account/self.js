
function onClickHoldElement(id, category){
    
    document.getElementById('load-preview').hidden = false;

    let other = document.querySelectorAll('option[category="'+category+'"]');
    for(let i in other){
        other[i].selected = false;
    }
    document.getElementById('save-hold-'+id).selected = true;
    previewUpdate();
}

function previewUpdate(){
    var form = new FormData(document.getElementById("save-holdable-form"));
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200){
            document.getElementById('preview').innerHTML = this.response;
            document.getElementById('load-preview').hidden = true;

        }
        else if(this.readyState == 4 && this.status == 200){
            document.getElementById('load-preview').hidden = true;
            
        }
        
    }

    xhttp.open("POST", "./holdable/preview");
    xhttp.send(form)
}