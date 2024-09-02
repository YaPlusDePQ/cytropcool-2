function showHide(show, hide){
    document.getElementById(show).hidden = false
    document.getElementById(hide).hidden = true
}

function updateFillLevel(liquidId, value) {
    const liquid = document.getElementById(liquidId);fillAlcool
    const liquidinput = document.getElementById(liquidId+"-input");
    liquid.style.height = value + '%';
    liquidinput.value = ((value*100)/90).toFixed(0)
}

function manualUpdateFillLevel(liquidId, value){
    const liquid = document.getElementById(liquidId);fillAlcool
    const liquidinput = document.getElementById('alcool_quantity');
    liquid.style.height = ((value*90)/100).toFixed(0) + '%';
    liquidinput.value = ((value*90)/100).toFixed(0)
}

function changeHideClass(className, to=false){
    let targets = document.getElementsByClassName(className);
    for(let i in targets){
        targets[i].hidden = to;
    }
}

function changeActive(className, active){
    let targets = document.getElementsByClassName(className);
    for(let i in targets){
        targets[i].className = className;
    }
    active.className += ' pushed';
}

function ShortCut(size, alcool, alcoolDegre, fillAlcool, number, bottomsUp){
    document.getElementById('size').value = size;
    window.addAlcool.addOption({value:alcool, text:alcool},user_created=false);
    window.addAlcool.addItem(alcool);
    document.getElementById('alcool_degre').value = alcoolDegre;
    document.getElementById('fillAlcool-input').value = fillAlcool;
    document.getElementById('fillAlcool-input').onchange();
    document.getElementById('number').value = number;
    document.getElementById('bottoms_up').value = bottomsUp;
}

function setAlcool(alcool, alcoolDegreField, alcoolQuantityField){
    switch(alcool){
        case "Vodka":
        case "Tequila":
        case "Jäger":
        case "Rhum":
            document.getElementById(alcoolDegreField).value = 35
            document.getElementById(alcoolQuantityField).value = 33
            break
        case "Bière":
            document.getElementById(alcoolDegreField).value = 6
            document.getElementById(alcoolQuantityField).value = 100
            break
        case "Jet 27":
            document.getElementById(alcoolDegreField).value = 18
            document.getElementById(alcoolQuantityField).value = 33
            break
        case "Autre":
        case "Whisky":
            document.getElementById(alcoolDegreField).value = 40
            document.getElementById(alcoolQuantityField).value = 33
            break
        case "Ricard":
            document.getElementById(alcoolDegreField).value = 45
            document.getElementById(alcoolQuantityField).value = 33
            break
        default:
            document.getElementById(alcoolDegreField).value = 40
            document.getElementById(alcoolQuantityField).value = 33
            break
    }

    if(document.getElementById(alcoolQuantityField).onchange){
        document.getElementById(alcoolQuantityField).onchange();
    }
}

function setUpdateForm(name, id, size, alcool, alcoolDegre, alcoolQuantity, number, bottomsUp, drinkAt){
    document.getElementById('updateName').innerHTML = 'Modifier: '+name;
    document.getElementById('updateMethod').value = 'PUT';
    document.getElementById('updateId').value = id;
    document.getElementById('updateSize').value = size;
    window.updateAlcool.addOption({value:alcool, text:alcool},user_created=false);
    window.updateAlcool.addItem(alcool);
    document.getElementById('updateAlcoolDegre').value = alcoolDegre;
    document.getElementById('updateAlcoolQuantity').value = alcoolQuantity;
    document.getElementById('updateNumber').value = number;
    document.getElementById('updateBottomsUp').value = bottomsUp;
    document.getElementById('updateDrinkAt').value = drinkAt;
}