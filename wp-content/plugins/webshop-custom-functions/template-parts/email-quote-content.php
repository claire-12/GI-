<div class="quote-content">
    <?php if (!empty($data['object_id'])): ?>
        <p>
            <span>Product:</span>
            <span><?php echo get_the_title($data['object_id']) ?></span>
        </p>
    <?php endif ?>
    <p>
        <span>Email Address:</span>
        <span><?php echo $data['email'] ?></span>
    </p>
    <p>
        <span>Name:</span>
        <span><?php echo $data['name'] ?></span>
    </p>
    <p>
        <span>Company:</span>
        <span><?php echo $data['company'] ?></span>
    </p>
    <p>
        <span>Company Sector</span>
        <span><?php echo $data['company-sector'] ?></span>
    </p>
    <p>
        <span>Company Address:</span>
        <span><?php echo $data['company-address'] ?></span>
    </p>
    <p>
        <span>Timing Required:</span>
        <span><?php echo $data['timing-required'] ?></span>
    </p>
    <p>
        <span>Qty:</span>
        <span><?php echo $data['product-qty'] ?></span>
    </p>
    <p>
        <span>Additional information:</span>
        <span><?php echo $data['additional-information'] ?></span>
    </p>
</div>