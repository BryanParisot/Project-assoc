const img = document.getElementById('img');

fetch('https://api.thecatapi.com/v1/images/search')
    .then(res => {
        //on verifie si c'est true
        if(res.ok){
            //on tronsforme notre json en donnée 
            res.json().then(data=>{
                //on renseigne l'url
                img.src = data[0].url
            })
        }else{
            //si l'image ne s'affiche pas. (meesage d'erreur)
            document.getElementById('erreur').innerHTML = "Une erreur est survenu avec le Chargment de l'image"
        }
    })


    .then(data => img.src =data[0].url)