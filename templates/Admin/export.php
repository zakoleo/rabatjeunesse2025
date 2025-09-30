<?php
/**
 * @var \App\View\AppView $this
 * @var array $counts
 */
?>

<div class="admin-export-index">
    <div class="page-header mb-4">
        <h2><i class="fas fa-file-export"></i> Exportation des données</h2>
        <p class="text-muted">Exportez les données de chaque sport ou discipline au format Excel</p>
    </div>
    
    <div class="row">
        <!-- Sports collectifs -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-users"></i> Sports Collectifs</h4>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <!-- Football -->
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Football</h5>
                                <p class="mb-0 text-muted"><?= $counts['football'] ?> équipes inscrites</p>
                            </div>
                            <?= $this->Html->link('<i class="fas fa-download"></i> Exporter', 
                                ['action' => 'exportFootball'], 
                                ['class' => 'btn btn-success', 'escape' => false]
                            ) ?>
                        </div>
                        
                        <!-- Basketball -->
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Basketball</h5>
                                <p class="mb-0 text-muted"><?= $counts['basketball'] ?> équipes inscrites</p>
                            </div>
                            <?= $this->Html->link('<i class="fas fa-download"></i> Exporter', 
                                ['action' => 'exportBasketball'], 
                                ['class' => 'btn btn-success', 'escape' => false]
                            ) ?>
                        </div>
                        
                        <!-- Handball -->
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Handball</h5>
                                <p class="mb-0 text-muted"><?= $counts['handball'] ?> équipes inscrites</p>
                            </div>
                            <?= $this->Html->link('<i class="fas fa-download"></i> Exporter', 
                                ['action' => 'exportHandball'], 
                                ['class' => 'btn btn-success', 'escape' => false]
                            ) ?>
                        </div>
                        
                        <!-- Volleyball -->
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Volleyball</h5>
                                <p class="mb-0 text-muted"><?= $counts['volleyball'] ?> équipes inscrites</p>
                            </div>
                            <?= $this->Html->link('<i class="fas fa-download"></i> Exporter', 
                                ['action' => 'exportVolleyball'], 
                                ['class' => 'btn btn-success', 'escape' => false]
                            ) ?>
                        </div>
                        
                        <!-- Beach Volleyball -->
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Beach Volleyball</h5>
                                <p class="mb-0 text-muted"><?= $counts['beachvolley'] ?> équipes inscrites</p>
                            </div>
                            <?= $this->Html->link('<i class="fas fa-download"></i> Exporter', 
                                ['action' => 'exportBeachvolley'], 
                                ['class' => 'btn btn-success', 'escape' => false]
                            ) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sports individuels -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0"><i class="fas fa-user"></i> Sports Individuels & Concours</h4>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <!-- Cross Training -->
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Cross Training</h5>
                                <p class="mb-0 text-muted"><?= $counts['crosstraining'] ?> participants inscrits</p>
                            </div>
                            <?= $this->Html->link('<i class="fas fa-download"></i> Exporter', 
                                ['action' => 'exportCrosstraining'], 
                                ['class' => 'btn btn-success', 'escape' => false]
                            ) ?>
                        </div>
                        
                        <!-- Sports Urbains -->
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Sports Urbains</h5>
                                <p class="mb-0 text-muted"><?= $counts['sportsurbains'] ?> participants inscrits</p>
                            </div>
                            <?= $this->Html->link('<i class="fas fa-download"></i> Exporter', 
                                ['action' => 'exportSportsurbains'], 
                                ['class' => 'btn btn-success', 'escape' => false]
                            ) ?>
                        </div>
                        
                        <!-- Concours -->
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Concours</h5>
                                <p class="mb-0 text-muted"><?= $counts['concours'] ?> participants inscrits</p>
                            </div>
                            <?= $this->Html->link('<i class="fas fa-download"></i> Exporter', 
                                ['action' => 'exportConcours'], 
                                ['class' => 'btn btn-success', 'escape' => false]
                            ) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Export all data -->
    <div class="card">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0"><i class="fas fa-database"></i> Export complet</h4>
        </div>
        <div class="card-body text-center">
            <p class="mb-3">Exportez toutes les données en un seul fichier ZIP contenant tous les fichiers Excel</p>
            <button class="btn btn-lg btn-primary" onclick="exportAll()">
                <i class="fas fa-file-archive"></i> Exporter toutes les données
            </button>
        </div>
    </div>
</div>

<style>
.admin-export-index {
    padding: 20px 0;
}

.list-group-item {
    border: 1px solid #e0e0e0;
    margin-bottom: -1px;
}

.list-group-item h5 {
    color: #333;
    font-weight: 600;
}

.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-radius: 8px;
}

.card-header {
    border-radius: 8px 8px 0 0;
    padding: 15px 20px;
}

.btn {
    border-radius: 5px;
    padding: 8px 20px;
    font-weight: 500;
}

.btn-success {
    background-color: #28a745;
    border-color: #28a745;
}

.btn-success:hover {
    background-color: #218838;
    border-color: #1e7e34;
}
</style>

<script>
function exportAll() {
    if (confirm('Voulez-vous exporter toutes les données ? Cette opération peut prendre quelques instants.')) {
        // Create a temporary container for download links
        var container = document.createElement('div');
        container.style.display = 'none';
        document.body.appendChild(container);
        
        // List of all export actions
        var exports = [
            {name: 'football', url: '<?= $this->Url->build(['action' => 'exportFootball']) ?>'},
            {name: 'basketball', url: '<?= $this->Url->build(['action' => 'exportBasketball']) ?>'},
            {name: 'handball', url: '<?= $this->Url->build(['action' => 'exportHandball']) ?>'},
            {name: 'volleyball', url: '<?= $this->Url->build(['action' => 'exportVolleyball']) ?>'},
            {name: 'beachvolley', url: '<?= $this->Url->build(['action' => 'exportBeachvolley']) ?>'},
            {name: 'crosstraining', url: '<?= $this->Url->build(['action' => 'exportCrosstraining']) ?>'},
            {name: 'sportsurbains', url: '<?= $this->Url->build(['action' => 'exportSportsurbains']) ?>'},
            {name: 'concours', url: '<?= $this->Url->build(['action' => 'exportConcours']) ?>'}
        ];
        
        // Download each file with a delay
        exports.forEach(function(exp, index) {
            setTimeout(function() {
                var link = document.createElement('a');
                link.href = exp.url;
                link.download = exp.name + '_export.xls';
                container.appendChild(link);
                link.click();
                container.removeChild(link);
            }, index * 1000); // 1 second delay between downloads
        });
        
        // Clean up
        setTimeout(function() {
            document.body.removeChild(container);
        }, exports.length * 1000 + 1000);
    }
}
</script>