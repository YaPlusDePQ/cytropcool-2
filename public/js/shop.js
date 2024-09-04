function setBuyForm(element, name, price, id){
    document.getElementById('hold-name').innerHTML = 'Acheter : '+name;
    document.getElementById('hold-id').value = id;
    document.getElementById('form-displayer').innerHTML = element.children[0].innerHTML;
    document.getElementById('hold-buy-button').innerHTML = price+' ©';

}