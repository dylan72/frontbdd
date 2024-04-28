<?php include '../assets/menu.html'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une Séance</title>
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
                            <select class="form-control" id="batiment">
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="salle">Salle</label>
                            <select class="form-control" id="salle">
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="formation">Formation</label>
                            <select class="form-control" id="formation">
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
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.5/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const batimentSelect = document.getElementById('batiment');
    const salleSelect = document.getElementById('salle');
    const formationSelect = document.getElementById('formation');

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
                        option.textContent = salle.nomSalle;
                        salleSelect.appendChild(option);
                    }
                });
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

    batimentSelect.addEventListener('change', chargerSalles);

    chargerBatiments();
    chargerFormations();
});
</script>

</body>
</html>
