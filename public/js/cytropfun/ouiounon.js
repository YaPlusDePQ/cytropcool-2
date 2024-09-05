function clear(){
    let space =  document.getElementById("result")
    space.style.backgroundColor = ""
    space.innerHTML = ""
}

function oui(){
    let space =  document.getElementById("result")
    space.style.backgroundColor = "green"
    space.innerHTML = "oui"
}

function non(){
    let space =  document.getElementById("result")
    space.style.backgroundColor = "red"
    space.innerHTML = "non"
}

function ouiounon(q){
    if(q.includes("doi") && q.includes("boir") && !q.includes("pa")) return 1
    if(q.includes("doi") && q.includes("boir") && q.includes("pa")) return 0
    if(q.includes("sur") && !q.includes("pa")) return 1
    return Math.floor(Math.random() * 2);
}

function answer(){
    clear()
    let question = document.getElementById("ask-input").value
    let answer = ouiounon(question)
    if(answer) setTimeout(oui, 50)
    else setTimeout(non, 50)
}