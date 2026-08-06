<?php
$currPage = 'front_AGB';
include 'app/controller/PageController.php';
?>
<section id="content">
    <section class="content-row">
        <div class="container">
            <div class="col-md-12 legal-page">
                <h1 class="text-center"><?= htmlspecialchars($currPageName); ?></h1>
                <?= $helper->getLegalPage('agb'); ?>
            </div>
        </div>
    </section>
</section>
