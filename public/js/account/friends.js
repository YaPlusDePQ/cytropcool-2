function acceptRequest(to){
    document.getElementById('accept-input').value = to;
    document.getElementById('accept').submit();
}

function deleteRequest(id){
    document.getElementById('delete-input').value = id;
    document.getElementById('delete').submit();
}

function deleteFriend(id){
    if(!confirm('Êtes-vous sûr de vouloir supprimer cet ami ?')){
        return;
    }
    document.getElementById('delete-input').value = id;
    document.getElementById('delete').submit();
}

function HideMenu(){
    let menu = document.getElementsByClassName('fmenu')
    for(let i=0; i<menu.length; i++){
        menu[i].style = 'display:none'
    }
}

function displayMenu(e){
    HideMenu();
    document.getElementById(e).style = '';
}