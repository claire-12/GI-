<h2>My Quotes</h2>
<?php if (!empty($data)): ?>
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Product Info</th>
                <th>O-RINGS</th>
                <th>Additional information</th>
                <th>Files</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($data as $datum): $quote = new ProductQuote($datum->email); var_dump($quote->get_product());  ?>
            <tr>
            <td><?php  ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif ?>
