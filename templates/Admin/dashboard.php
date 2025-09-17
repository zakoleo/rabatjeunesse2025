<?= $this->Html->css('admin-dashboard') ?>

<div class="app-container">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="#" class="sidebar-brand">
                <span>🏆</span>
                <span>Sports Admin</span>
            </a>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-title">Dashboard</div>
            <ul class="nav-items">
                <li><a href="<?= $this->Url->build(['controller' => 'Admin', 'action' => 'index']) ?>" class="nav-link active">
                    📊 Overview
                </a></li>
                <li><a href="#" class="nav-link">📈 Analytics</a></li>
            </ul>

            <div class="nav-section-title">Sports</div>
            <ul class="nav-items">
                <li><a href="<?= $this->Url->build(['controller' => 'Teams', 'action' => 'index']) ?>" class="nav-link">⚽ Football</a></li>
                <li><a href="<?= $this->Url->build(['controller' => 'BeachvolleyTeams', 'action' => 'index']) ?>" class="nav-link">🏐 Beach Volleyball</a></li>
                <li><a href="<?= $this->Url->build(['controller' => 'HandballTeams', 'action' => 'index']) ?>" class="nav-link">🤾 Handball</a></li>
                <li><a href="<?= $this->Url->build(['controller' => 'VolleyballTeams', 'action' => 'index']) ?>" class="nav-link">🏐 Volleyball</a></li>
            </ul>

            <div class="nav-section-title">Management</div>
            <ul class="nav-items">
                <li><a href="#" class="nav-link">👥 Users</a></li>
                <li><a href="#" class="nav-link">⚙️ Settings</a></li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <button class="mobile-toggle" id="mobileToggle">☰</button>
        
        <div class="admin-dashboard">
            <div class="welcome-section">
                <h2>Sports Dashboard</h2>
                <p>Manage teams for Rabat Jeunesse 2025</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Football</h3>
                    <div class="stat-number"><?= $footballTeams ?? 15 ?></div>
                    <div class="stat-label">Total Teams</div>
                    <div class="stat-status">
                        <span class="status-verified">12</span>
                        <span class="status-pending">3</span>
                    </div>
                </div>

                <div class="stat-card">
                    <h3>Beach Volleyball</h3>
                    <div class="stat-number"><?= $beachvolleyTeams ?? 6 ?></div>
                    <div class="stat-label">Total Teams</div>
                    <div class="stat-status">
                        <span class="status-verified">4</span>
                        <span class="status-pending">2</span>
                    </div>
                </div>

                <div class="stat-card">
                    <h3>Handball</h3>
                    <div class="stat-number"><?= $handballTeams ?? 8 ?></div>
                    <div class="stat-label">Total Teams</div>
                    <div class="stat-status">
                        <span class="status-verified">6</span>
                        <span class="status-pending">2</span>
                    </div>
                </div>

                <div class="stat-card">
                    <h3>Volleyball</h3>
                    <div class="stat-number"><?= $volleyballTeams ?? 6 ?></div>
                    <div class="stat-label">Total Teams</div>
                    <div class="stat-status">
                        <span class="status-verified">5</span>
                        <span class="status-pending">1</span>
                    </div>
                </div>
            </div>

            <div class="quick-actions">
                <h3>Quick Actions</h3>
                <div class="actions-grid">
                    <a href="<?= $this->Url->build(['controller' => 'Teams', 'action' => 'add']) ?>" class="action-card">
                        <h4>Add Football Team</h4>
                        <p>Create new team</p>
                    </a>
                    <a href="<?= $this->Url->build(['controller' => 'BeachvolleyTeams', 'action' => 'add']) ?>" class="action-card">
                        <h4>Add Beach Volleyball</h4>
                        <p>Register team</p>
                    </a>
                    <a href="<?= $this->Url->build(['controller' => 'HandballTeams', 'action' => 'add']) ?>" class="action-card">
                        <h4>Add Handball Team</h4>
                        <p>Create entry</p>
                    </a>
                    <a href="<?= $this->Url->build(['controller' => 'VolleyballTeams', 'action' => 'add']) ?>" class="action-card">
                        <h4>Add Volleyball Team</h4>
                        <p>Register team</p>
                    </a>
                </div>
            </div>

            <div class="recent-activity">
                <h3>Recent Activity</h3>
                <ul class="activity-list">
                    <li class="activity-item">
                        <div class="activity-icon new-team">+</div>
                        <div class="activity-content">
                            <h4>New Team Registered</h4>
                            <p>Eagles FC registered for Football</p>
                        </div>
                        <div class="activity-time">2h ago</div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon status-change">✓</div>
                        <div class="activity-content">
                            <h4>Team Verified</h4>
                            <p>Thunder Bolts Handball team verified</p>
                        </div>
                        <div class="activity-time">4h ago</div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon new-team">+</div>
                        <div class="activity-content">
                            <h4>New Team Registered</h4>
                            <p>Beach Warriors for Beach Volleyball</p>
                        </div>
                        <div class="activity-time">1d ago</div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon verification">⏰</div>
                        <div class="activity-content">
                            <h4>Pending Verification</h4>
                            <p>3 teams awaiting verification</p>
                        </div>
                        <div class="activity-time">2d ago</div>
                    </li>
                </ul>
            </div>
        </div>
    </main>
</div>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggle = document.getElementById('mobileToggle');
    
    if (toggle) {
        toggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        });
    }
    
    if (overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        });
    }
    
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        }
    });
});
</script>