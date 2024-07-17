function hideshow(id, setHidden){
    target = document.getElementById(id)
    if(setHidden) target.hidden = true
    else target.hidden = !target.hidden
}