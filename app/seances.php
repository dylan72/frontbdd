<?php include '../assets/menu.html'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les Séances</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>
<body>
<div class="container">
    <button type="button" class="btn btn-primary buttonedit mt-4" data-toggle="modal" data-target="#modalCreerFormation">
        Créer une Séance
    </button>

    <div class="modal fade" id="modalCreerFormation" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Nouvelle Séance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formCreerFormation">
                        <div class="form-group">
                            <label for="batiment">Bâtiment</label>
                            <select class="form-control" id="batiment" required>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="salle">Salle</label>
                            <select class="form-control" id="salle" required>
                                <option value="">Choisir une salle</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="formation">Formation</label>
                            <select class="form-control" id="formation" required>
                                <option value="">Choisir une formation</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date">Date et heure</label>
                            <input type="datetime-local" class="form-control" id="date" required>
                        </div>
                        <div class="form-group">
                            <label for="duree">Durée</label>
                            <input type="number" class="form-control" id="duree" placeholder="Durée en heures" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary buttonedit" id="btnCreerFormation">Créer la séance</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de modification d'une séance -->
    <div class="modal fade" id="modalModifierSeance" tabindex="-1" role="dialog" aria-labelledby="modalModifierSeanceLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalModifierSeanceLabel">Modifier Séance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formModifierSeance">
                        <input type="hidden" id="editSeanceId">
                        <div class="form-group">
                            <label for="editBatiment">Bâtiment</label>
                            <select class="form-control" id="editBatiment" required>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editSalle">Salle</label>
                            <select class="form-control" id="editSalle" required>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editDate">Date et heure</label>
                            <input type="datetime-local" class="form-control" id="editDate" required>
                        </div>
                        <div class="form-group">
                            <label for="editDuree">Durée</label>
                            <input type="text" class="form-control" id="editDuree" required>
                        </div>
                        <div class="form-group">
                            <label for="editFormation">Formation</label>
                            <input type="text" class="form-control" id="editFormation" disabled>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary buttonedit" onclick="soumettreModificationSeance()">Enregistrer les changements</button>
                </div>
            </div>
        </div>
    </div>

    <div id="seancesContainer" class="row mt-4"></div>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.5/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const batimentSelect = document.getElementById('batiment');
    const salleSelect = document.getElementById('salle');
    const formationSelect = document.getElementById('formation');
    const btnCreerFormation = document.getElementById('btnCreerFormation');
    const form = document.getElementById('formCreerFormation');
    function formatterDate(dateISO) {
        const date = new Date(dateISO);
        const optionsDate = { year: 'numeric', month: '2-digit', day: '2-digit' };
        const optionsTime = { hour: '2-digit', minute: '2-digit', hour12: false };
        const formattedDate = date.toLocaleDateString('fr-FR', optionsDate);
        const formattedTime = date.toLocaleTimeString('fr-FR', optionsTime).slice(0, 5);
        return formattedDate + ' à ' + formattedTime.replace(':', 'h');
    }

function chargerSeances() {
    fetch('http://localhost:9000/seances/')
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(seances => {
        // Vérifie si le tableau des séances est vide
        if (seances.length === 0) {
            seancesContainer.innerHTML = '<p class="text-center">Pas de séance.</p>'; // Affiche un message si aucune séance n'est trouvée
            return;
        }
            seancesContainer.innerHTML = '';
              seances.forEach(seance => {
                  const formattedDate = formatterDate(seance.date);
                  const card = document.createElement('div');
                  card.className = 'col-md-4';
                  card.innerHTML = `
                      <div class="card mb-4 shadow-sm">
                          <div class="card-body">
                              <h5 class="card-title">${seance.nomFormation}</h5>
                              <p class="card-text">Date: ${formattedDate}</p>
                              <p class="card-text">Durée: ${seance.duree} heures</p>
                              <p class="card-text">Bâtiment: ${seance.batiment}</p>
                              <p class="card-text">Salle: ${seance.numeroSalle}</p>
                              <div>
                                  <button class="btn btn-sm btn-outline-secondary btnModifier" data-id="${seance.id}">
                                      <i class="fas fa-edit"></i>
                                  </button>
                                  <button class="btn btn-sm btn-danger btnSupprimer" onclick="supprimerSeance(${seance.id})">
                                      <i class="fas fa-trash"></i>
                                  </button>
                              </div>
                          </div>
                      </div>
                  `;
                  seancesContainer.appendChild(card);

                  card.querySelector('.btnModifier').addEventListener('click', function() {
                      ouvrirModalModificationSeance(seance);
                  });
              });
          })
          .catch(error => {
              console.error('Erreur lors du chargement des séances:', error);
              seancesContainer.innerHTML = '<p>Aucune séance existante</p>';
          });
      }

function ouvrirModalModificationSeance(seance) {
    document.getElementById('editSeanceId').value = seance.id;
    document.getElementById('editDate').value = seance.date;
    document.getElementById('editDuree').value = seance.duree.split(' ')[0];
    document.getElementById('editFormation').value = seance.nomFormation;
    document.getElementById('editBatiment').value = seance.batiment;

    chargerBatiments2(seance.batiment);
    chargerSalles2(seance.batiment, seance.numeroSalle);

    $('#modalModifierSeance').modal('show');
}

    chargerSeances();

window.soumettreModificationSeance = function() {
    const id = document.getElementById('editSeanceId').value;
    console.log("Data envoyée pour modification: ", {
        date: document.getElementById('editDate').value,
        duree: document.getElementById('editDuree').value,
        batiment: document.getElementById('editBatiment').value,
        numeroSalle: parseInt(document.getElementById('editSalle').value)
    });
    const data = {
        date: document.getElementById('editDate').value,
        duree: document.getElementById('editDuree').value,
        batiment: document.getElementById('editBatiment').value,
        numeroSalle: parseInt(document.getElementById('editSalle').value)
    };

    fetch(`http://localhost:9000/seances/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(updatedSeance => {
        console.log('Séance modifiée:', updatedSeance);
        $('#modalModifierSeance').modal('hide');
        chargerSeances();
    })
    .catch(error => {
        console.error('Erreur lors de la modification de la séance:', error);
        alert('Erreur lors de la modification de la séance: ' + error.message);
    });
};

window.supprimerSeance = function(id) {
    if (confirm("Êtes-vous sûr de vouloir supprimer cette séance ?")) {
        fetch(`http://localhost:9000/seances/${id}`, {
            method: 'DELETE'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Échec de la suppression de la séance avec le statut ${response.status}`);
            }
            return response.text();
        })
        .then(text => {
            alert("Séance supprimée avec succès");
            chargerSeances();
        })
        .catch(error => {
            console.error('Erreur lors de la suppression de la séance:', error);
            alert('Erreur lors de la suppression de la séance: ' + error.message);
        });
    }
};


btnCreerFormation.addEventListener('click', function() {
    if (form.checkValidity()) {
        const data = {
            numeroSalle: parseInt(salleSelect.value),
            libelle: formationSelect.options[formationSelect.selectedIndex].text,
            batiment: batimentSelect.value,
            date: document.getElementById('date').value,
            duree: parseInt(document.getElementById('duree').value)
        };

        fetch('http://localhost:9000/seances/', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(result => {
            console.log('Séance créée:', result);
            alert('Séance créée avec succès !');
            $('#modalCreerFormation').modal('hide');
            chargerSeances();
        })
        .catch(error => {
            console.error('Erreur lors de la création de la séance:', error);
            alert('Erreur lors de la création de la séance: ' + error.message);
        });
    } else {
        alert("Veuillez remplir tous les champs du formulaire.");
    }
});


    function chargerBatiments() {
        fetch('http://localhost:9000/salles/')
            .then(response => response.json())
            .then(data => {
                const optionDefaut = document.createElement('option');
                optionDefaut.textContent = 'Veuillez choisir un bâtiment';
                optionDefaut.disabled = true;
                optionDefaut.selected = true;
                batimentSelect.appendChild(optionDefaut);

                let batiments = new Set();
                data.salles.forEach(salle => {
                    batiments.add(salle.batiment);
                });
                batiments.forEach(batiment => {
                    const option = document.createElement('option');
                    option.textContent = batiment;
                    batimentSelect.appendChild(option);
                });
            });
    }

    function chargerSalles() {
        fetch('http://localhost:9000/salles/')
            .then(response => response.json())
            .then(data => {
                salleSelect.innerHTML = '';
                data.salles.forEach(salle => {
                    if (salle.batiment === batimentSelect.value) {
                        const option = document.createElement('option');
                        option.textContent = salle.numeroSalle;
                        salleSelect.appendChild(option);
                    }
                });
            });
    }

function chargerBatiments2(batimentActuel) {
    fetch('http://localhost:9000/salles/')
        .then(response => response.json())
        .then(data => {
            const batimentSelect = document.getElementById('editBatiment');
            batimentSelect.innerHTML = '';

            let batiments = new Set();
            data.salles.forEach(salle => {
                batiments.add(salle.batiment);
            });

            batiments.forEach(batiment => {
                const option = document.createElement('option');
                option.textContent = batiment;
                option.value = batiment;
                if (batiment === batimentActuel) {
                    option.selected = true;
                }
                batimentSelect.appendChild(option);
            });
        }).catch(error => {
            console.error("Erreur lors du chargement des bâtiments: ", error);
        });
}


function chargerSalles2(batimentActuel, salleActuelle) {
    fetch('http://localhost:9000/salles/')
        .then(response => response.json())
        .then(data => {
            const salleSelect = document.getElementById('editSalle');
            salleSelect.innerHTML = '';

            // Création et ajout des options de salle basées sur le bâtiment actuel
            data.salles.forEach(salle => {
                if (salle.batiment === batimentActuel) {
                    const option = document.createElement('option');
                    option.textContent = salle.numeroSalle + " - " + salle.nomSalle;
                    option.value = salle.numeroSalle;
                    salleSelect.appendChild(option);
                }
            });

            salleSelect.value = salleActuelle;
        })
        .catch(error => {
            console.error("Erreur lors du chargement des salles pour le bâtiment sélectionné: ", error);
        });
}

    function chargerFormations() {
        fetch('http://localhost:9000/formations/')
            .then(response => response.json())
            .then(data => {
                data.forEach(formation => {
                    const option = document.createElement('option');
                    option.textContent = formation.libelle;
                    formationSelect.appendChild(option);
                });
            });
    }

    const editBatimentSelect = document.getElementById('editBatiment');

    editBatimentSelect.addEventListener('change', function() {
        chargerSalles2(this.value);
    });

document.getElementById('batiment').addEventListener('change', function() {
    chargerSalles(this.value);
});

    chargerBatiments();
    chargerFormations();
});
</script>

</body>
</html>
