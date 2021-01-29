<?php
    require_once('../../scripts/initialize.php'); 
?>
<?php $page_title = "Home"; ?>
<?php include(LAYOUT_PATH . '/admin_header.php') ?>

    <div>
        <p>Welcome to the admin area</p>
        <div>
            <nav>
                <ul>
                    <li><a href="<?php echo admin_url('/subjects/index.php'); ?>">All Subjects</a></li>
                    <li><a href="<?php echo admin_url('/pages/index.php'); ?>">All Pages</a></li>
                </ul>
            </nav>
        </div>
    </div>

<?php include(LAYOUT_PATH . '/admin_footer.php') ?>

