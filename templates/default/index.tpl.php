<?php echo $header; ?>
    <div id="content">
        <div class="total">Total movies found: <?php echo $total; ?> <p class="fr quote"><?php echo $quote; ?></p></div>
        <div id="movies">
            <?php echo $movies; ?>
        </div>
        <div id="pagination"><?php echo $pagination; ?></div>
    </div>
<?php echo $footer; ?>