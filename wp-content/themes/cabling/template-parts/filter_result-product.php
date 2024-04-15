<?php if (!empty($args['products'])): ?>
    <div class="list-categories">
        <?php if (!empty($args['category'])): ?>
            <h3><?php echo $args['category']->name; ?></h3>
        <?php endif ?>
        <div class="columns-3 product-grid ">
            <ul class="products columns-3">
                <?php
                echo $args['products'];
                ?>
            </ul>
        </div>
    </div>
<?php endif ?>
