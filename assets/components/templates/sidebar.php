<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <?php
        switch ($_SESSION['role']) {
            case 'Admin':
            case 'Officer':
            case 'Staff':
                ?>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="dashboard.php">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="users.php">
                        <i class="bi bi-people"></i>
                        <span>User Management</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="helpdesks.php">
                        <i class="bi bi-window-dock"></i>
                        <span>Helpdesks</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link collapsed" href="meetings.php">
                        <i class="bi bi-person-video2"></i>
                        <span>Meetings</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="reports.php">
                        <i class="bi bi-window-dock"></i>
                        <span>Reports</span>
                    </a>
                </li>
                <?php
                break;
            case 'Employee':
                ?>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="helpdesks.php">
                        <i class="bi bi-window-dock"></i>
                        <span>Helpdesks</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link collapsed" href="meetings.php">
                        <i class="bi bi-person-video2"></i>
                        <span>Meetings</span>
                    </a>
                </li>
                <?php
                break;
        }
        ?>

    </ul>

</aside><!-- End Sidebar-->