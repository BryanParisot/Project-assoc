//j'attends que la page soit totalement chargé 
window.onload = function () {
  function apiRna_gouv() {
    //variables api
    const apiUrl = "https://entreprise.data.gouv.fr/api/rna/v1/id/W";
    // let idUrl = "791001609"

    //variable input
    let rna              = document.getElementById("registration_numeroRna").value;
    const nomAssociation = document.getElementById( "registration_nomAssociation" );
    const ville          = document.getElementById("registration_ville");
    const adresse        = document.getElementById("registration_adresse");
    const numero         = document.getElementById("registration_numeroTelephone");
    const email          = document.getElementById("registration_email");

    //const erreur/validation
    const erreur     = document.getElementById("erreur_rna");
    const validation = document.getElementById("validation_rna");

    //class display
    var classDisplay = document.getElementsByClassName("display_form");

    let fullApi = apiUrl + rna;

    fetch(fullApi)

    //on tronsforme notre json en donnée 
      .then((response) => response.json())
      .then((results) => {
        console.log(results);

        if (results.message === "no results found") {
          // console.log('Une erreur est survenue')
          erreur.innerText =
            "Une erreur est survenue avec le numéro de rna, si le problème persite contactez-nous";
        } else {
          //on boucle sur la classDisplay qui représente plusieurs class du meme nom
          for (let i = 0; i < classDisplay.length; i++) {
            const element = classDisplay[i];

            //on change le display none en block pour afficher les input après avoir entré les numéro de rna
            element.style.display = "block";
          }

          //variable de l'api pour récupérer les données
          let villeApi          = results.association.adresse_libelle_commune;
          let nomAssociationApi = results.association.titre;
          let adresseApi        = results.association.adresse_gestion_libelle_voie;
          let numeroVoieApi     = results.association.adresse_numero_voie;
          let libelleVoieApi    = results.association.adresse_type_voie;
          let telephoneApi      = results.association.telephone;
          let emailApi          = results.association.email;

          // on donne la valeur du tableau json dans l'input
          valide(nomAssociationApi, nomAssociation);
          valide(numeroVoieApi, ville);
          valide(libelleVoieApi, ville);
          valide(villeApi, ville);
          valide(adresseApi, adresse);
          valide(telephoneApi, numero);
          valide(emailApi, email);

          erreur.style.display = "none";
          validation.innerText = " votre numéro de rna est correct";
        }
      });
  }

  document.getElementById("btn_rna").addEventListener("click", apiRna_gouv);

  //fonction qui permet de renvoyer une string vide si le resultat est null
  function valide(valeurApi, valeurInput) {
    if (valeurApi == null) {
      valeurInput.value = "";
    } else {
      valeurInput.value = valeurApi;
    }
  }
};
