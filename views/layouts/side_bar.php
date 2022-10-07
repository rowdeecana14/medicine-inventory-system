<div class="sidebar sidebar-style-2" data-background-color="blue">			
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <div class="avatar-sm float-left mr-2">
                    <img src="../../public/assets/img/config/default.png" alt="..." class="avatar-img rounded-circle auth-image">
                </div>
                <div class="info">
                    <a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
                        <span>
                            <span class="auth-name">TEST ADMIN</span>
                            <span class="user-level auth-position">Administrator</span>
                            <span class="caret"></span>
                        </span>
                    </a>
                    <div class="clearfix"></div>

                    <div class="collapse in" id="collapseExample">
                        <ul class="nav">
                            <li>
                                <a href="../profile/">
                                   <span class="link-collapse"> Edit Profile</span>
                                </a>
                            </li>
                            <li>
                                <a class="logout" style="cursor: pointer" >
                                    <span class="link-collapse">Logout</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <ul class="nav nav-primary">
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Menu</h4>
                </li>
                <li class="nav-item <?= $navbar_active == 'dashboard' ? 'active' : ''; ?>">
                    <a href="../dashboard/">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item <?= $navbar_active == 'patients' ? 'active' : ''; ?>">
                    <a href="../patients/">
                        <i class="fas fa-users"></i>
                        <p>Patients</p>
                    </a>
                </li>
                <li class="nav-item <?= $navbar_active == 'health_officials' ? 'active' : ''; ?>">
                    <a href="../health-officials/">
                        <i class="fas fa-user-md"></i>
                        <p>Health Officials </p>
                    </a>
                </li>
                <li class="nav-item
                    <?= 
                        in_array($navbar_active, ['medicines_list', 'medicines_receiving', 'medicine_dispensing', 'medicines_expiration', 'medicines_inventory']) ? 'active submenu' : ''; 
                    ?>">
                    <a data-toggle="collapse" href="#medicines">
                        <i class="fas fa-medkit"></i>
                        <p>Medicines</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse  
                        <?= 
                            in_array($navbar_active,  ['medicines_list', 'medicines_receiving', 'medicines_dispensing', 'medicines_expiration', 'medicines_inventory']) ? 'show' : ''; 
                        ?>" 
                        id="medicines">
                        <ul class="nav nav-collapse">
                            <li class="<?= $navbar_active == 'medicines_list' ? 'active' : ''; ?>">
                            <a href="../medicines-list/">
                                    <span class="sub-item">Lists</span>
                                </a>
                            </li>
                            
                            <li class="<?= $navbar_active == 'medicines_receiving' ? 'active' : ''; ?>">
                                <a href="../medicines-receiving/">
                                    <span class="sub-item">Receiving</span>
                                </a>
                            </li>
                            <li class="<?= $navbar_active == 'medicines_dispensing' ? 'active' : ''; ?>">
                                <a href="../medicines-dispensing/">
                                    <span class="sub-item">Dispensing</span>
                                </a>
                            </li>
                            <li class="<?= $navbar_active == 'medicines_expiration' ? 'active' : ''; ?>">
                                <a href="../medicines-expiration/">
                                    <span class="sub-item">Expiration</span>
                                </a>
                            </li>
                            <li class="<?= $navbar_active == 'medicines_inventory' ? 'active' : ''; ?>">
                                <a href="../medicines-inventory/">
                                    <span class="sub-item">Inventory</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item
                    <?= 
                        in_array($navbar_active, ['reports_medicine', 'reports_receiving', 'reports_dispencing', 'reports_expiration', 'reporst_inventory']) ? 'active submenu' : ''; 
                    ?>">
                    <a data-toggle="collapse" href="#reports">
                        <i class="fas fa-list-alt"></i>
                        <p>Reports</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse  
                        <?= 
                            in_array($navbar_active, ['reports_medicine', 'reports_receiving', 'reports_dispencing', 'reports_expiration', 'reports_inventory']) ? 'show' : ''; 
                        ?>" 
                        id="reports">
                        <ul class="nav nav-collapse">
                            <li class="<?= $navbar_active == 'reports_medicine' ? 'active' : ''; ?>">
                            <a href="../reports-medicine-informations/">
                                    <span class="sub-item">Medicine Information</span>
                                </a>
                            </li>
                            
                            <li class="<?= $navbar_active == 'reports_receiving' ? 'active' : ''; ?>">
                                <a href="../reports-stocks-receiving/">
                                    <span class="sub-item">Medicine Receiving</span>
                                </a>
                            </li>
                            <li class="<?= $navbar_active == 'reports_dispencing' ? 'active' : ''; ?>">
                                <a href="../reports-stocks-dispencing/">
                                    <span class="sub-item">Medicine Dispensing</span>
                                </a>
                            </li>
                            <li class="<?= $navbar_active == 'reports_expiration' ? 'active' : ''; ?>">
                                <a href="../reports-stocks-expiration/">
                                    <span class="sub-item">Medicine Expiration</span>
                                </a>
                            </li>
                            <li class="<?= $navbar_active == 'reports_inventory' ? 'active' : ''; ?>">
                                <a href="../reports-stocks-inventory/">
                                    <span class="sub-item">Medicine Inventory</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item <?= $navbar_active == 'activity_logs' ? '  active' : ''; ?>">
                    <a href="../activity-logs/">
                        <i class="fas fa-history"></i>
                        <p>Activity Logs</p>
                    </a>
                </li>

                <li class="nav-item <?= $navbar_active == 'database_backups' ? '  active' : ''; ?>">
                    <a href="../database-backups/">
                        <i class="fas fa-database"></i>
                        <p>Database Backups</p>
                    </a>
                </li>
               
                <li class="nav-item
                    <?= 
                        in_array($navbar_active, [
                            'setting_users', 'setting_stock_levels', 'setting_categories', 'setting_types',  'setting_units',
                            'setting_baranggays', 'setting_puroks', 'setting_citizenships',
                            'setting_civil_statuses', 'setting_occupations', 'setting_genders', 'setting_relationships', 
                            'setting_person_disabilities', 'setting_educational_attainments', 'setting_blood_types',
                        ]) ? ' active submenu' : ''; 
                    ?>">
                    <a data-toggle="collapse" href="#settings">
                        <i class="fas fa-cogs"></i>
                        <p>Settings</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse
                        <?= 
                            in_array($navbar_active, [
                                'setting_users', 'setting_stock_levels', 'setting_categories', 'setting_types',  'setting_units',
                                'setting_baranggays', 'setting_puroks', 'setting_citizenships',
                                'setting_civil_statuses', 'setting_occupations', 'setting_genders', 'setting_relationships', 
                                'setting_person_disabilities', 'setting_educational_attainments', 'setting_blood_types',
                            ]) ? ' show' : ''; 
                        ?>" id="settings">
                        <ul class="nav nav-collapse">
                            <li class="<?= $navbar_active == 'setting_users' ? ' active' : ''; ?>"> 
                                <a href="../settings-users/">
                                    <span class="sub-item">Users</span>
                                </a>
                            </li>
                            <li class="<?= $navbar_active == 'setting_stock_levels' ? 'active' : ''; ?>"> 
                                <a href="../settings-stock-levels/">
                                    <span class="sub-item">Stock Levels</span>
                                </a>
                            </li>
                            <li class="<?= $navbar_active == 'setting_categories' ? 'active' : ''; ?>"> 
                                <a href="../settings-categories/">
                                    <span class="sub-item">Categories</span>
                                </a>
                            </li>
                            <li class="<?= $navbar_active == 'setting_types' ? 'active' : ''; ?>"> 
                                <a href="../settings-types/">
                                    <span class="sub-item">Types</span>
                                </a>
                            </li>
                            <li class="<?= $navbar_active == 'setting_blood_types' ? 'active' : ''; ?>"> 
                                <a href="../settings-blood-types/">
                                    <span class="sub-item">Blood Types </span>
                                </a>
                            </li>
                            <li class="<?= $navbar_active == 'setting_person_disabilities' ? 'active' : ''; ?>"> 
                                <a href="../settings-person-disabilities/">
                                    <span class="sub-item">Person Disabilities </span>
                                </a>
                            </li>
                            <li class="<?= $navbar_active == 'setting_baranggays' ? 'active' : ''; ?>"> 
                                <a href="../settings-baranggays/">
                                    <span class="sub-item">Baranggays</span>
                                </a>
                            </li>
                            <li class="<?= $navbar_active == 'setting_puroks' ? 'active' : ''; ?>"> 
                                <a href="../settings-puroks/">
                                    <span class="sub-item">Puroks</span>
                                </a>
                            </li>
                            <li class="<?= $navbar_active == 'setting_genders' ? 'active' : ''; ?>"> 
                                <a href="../settings-genders/">
                                    <span class="sub-item">Genders </span>
                                </a>
                            </li>
                            <li class="<?= $navbar_active == 'setting_occupations' ? 'active' : ''; ?>"> 
                                <a href="../settings-occupasions/">
                                    <span class="sub-item">Occupations </span>
                                </a>
                            </li>
                            
                            <li class="<?= $navbar_active == 'setting_citizenships' ? 'active' : ''; ?>"> 
                                <a href="../settings-citizenships/">
                                    <span class="sub-item">Citizenships </span>
                                </a>
                            </li>
                            <li class="<?= $navbar_active == 'setting_civil_statuses' ? 'active' : ''; ?>"> 
                                <a href="../settings-civil-statuses/">
                                    <span class="sub-item">Civil Statuses </span>
                                </a>
                            </li>
                            <li class="<?= $navbar_active == 'setting_educational_attainments' ? 'active' : ''; ?>"> 
                                <a href="../settings-educational-attainments/">
                                    <span class="sub-item">Educational Attainments </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>