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
                <li><a href="<?= $this->Url->build(['controller' => 'Admin', 'action' => 'index']) ?>" class="nav-link">📊 Overview</a></li>
            </ul>

            <div class="nav-section-title">Sports</div>
            <ul class="nav-items">
                <li><a href="#" class="nav-link active">⚽ Football Teams</a></li>
                <li><a href="#" class="nav-link">🏐 Beach Volleyball</a></li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <button class="mobile-toggle" id="mobileToggle">☰</button>
        
        <div class="table-container">
            <div class="table-header">
                <h2 class="table-title">Teams</h2>
                <div class="table-actions">
                    <div class="table-search">
                        <input type="text" placeholder="Search...">
                    </div>
                    <a href="#" class="btn btn-primary">+ Add Team</a>
                </div>
            </div>

            <table class="simple-table">
                <thead>
                    <tr>
                        <th>Team</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>Eagles FC</strong><br>
                            <small style="color: #666;">Ahmed Hassan</small>
                        </td>
                        <td>Senior</td>
                        <td><span class="status-badge verified">Verified</span></td>
                        <td>Jan 15</td>
                        <td>
                            <div class="action-dropdown">
                                <button class="action-btn" onclick="toggleDropdown(this)">⋮</button>
                                <div class="dropdown-menu">
                                    <a href="#" class="dropdown-item">View</a>
                                    <a href="#" class="dropdown-item">Edit</a>
                                    <a href="#" class="dropdown-item danger">Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Lions United</strong><br>
                            <small style="color: #666;">Mohamed Ali</small>
                        </td>
                        <td>Junior</td>
                        <td><span class="status-badge pending">Pending</span></td>
                        <td>Jan 14</td>
                        <td>
                            <div class="action-dropdown">
                                <button class="action-btn" onclick="toggleDropdown(this)">⋮</button>
                                <div class="dropdown-menu">
                                    <a href="#" class="dropdown-item">View</a>
                                    <a href="#" class="dropdown-item">Edit</a>
                                    <a href="#" class="dropdown-item danger">Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Thunder Bolts</strong><br>
                            <small style="color: #666;">Youssef Idrissi</small>
                        </td>
                        <td>Youth</td>
                        <td><span class="status-badge rejected">Rejected</span></td>
                        <td>Jan 13</td>
                        <td>
                            <div class="action-dropdown">
                                <button class="action-btn" onclick="toggleDropdown(this)">⋮</button>
                                <div class="dropdown-menu">
                                    <a href="#" class="dropdown-item">View</a>
                                    <a href="#" class="dropdown-item">Edit</a>
                                    <a href="#" class="dropdown-item danger">Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Royal Rangers</strong><br>
                            <small style="color: #666;">Fatima Zahra</small>
                        </td>
                        <td>Senior</td>
                        <td><span class="status-badge verified">Verified</span></td>
                        <td>Jan 12</td>
                        <td>
                            <div class="action-dropdown">
                                <button class="action-btn" onclick="toggleDropdown(this)">⋮</button>
                                <div class="dropdown-menu">
                                    <a href="#" class="dropdown-item">View</a>
                                    <a href="#" class="dropdown-item">Edit</a>
                                    <a href="#" class="dropdown-item danger">Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Galaxy Stars</strong><br>
                            <small style="color: #666;">Omar Benjelloun</small>
                        </td>
                        <td>Junior</td>
                        <td><span class="status-badge draft">Draft</span></td>
                        <td>Jan 11</td>
                        <td>
                            <div class="action-dropdown">
                                <button class="action-btn" onclick="toggleDropdown(this)">⋮</button>
                                <div class="dropdown-menu">
                                    <a href="#" class="dropdown-item">View</a>
                                    <a href="#" class="dropdown-item">Edit</a>
                                    <a href="#" class="dropdown-item danger">Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="table-pagination">
                <div class="pagination-info">
                    Showing 1 to 5 of 23 results
                </div>
                <div class="pagination-controls">
                    <a href="#" class="pagination-btn">Previous</a>
                    <a href="#" class="pagination-btn active">1</a>
                    <a href="#" class="pagination-btn">2</a>
                    <a href="#" class="pagination-btn">3</a>
                    <a href="#" class="pagination-btn">Next</a>
                </div>
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
    
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.action-dropdown')) {
            document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
                menu.classList.remove('show');
            });
        }
    });
});

function toggleDropdown(button) {
    const dropdown = button.nextElementSibling;
    const isOpen = dropdown.classList.contains('show');
    
    document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
        menu.classList.remove('show');
    });
    
    if (!isOpen) {
        dropdown.classList.add('show');
    }
}
</script>