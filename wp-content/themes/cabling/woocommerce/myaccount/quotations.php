<h2>My Quotes</h2>
<?php if (!empty($data)): ?>
    <table class="table hidden">
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
        <?php foreach($data as $datum): ?>
            <?php $o_ring = unserialize($datum->data_o_ring); ?>
            <tr>
                <td>
                    <?php if (empty($datum->object_id)): ?>
                        <span>*</span>
                    <?php else: ?>
                        <a href="<?php echo get_the_permalink($datum->object_id) ?>"><?php echo get_the_title($datum->object_id) ?></a>
                    <?php endif ?>
                </td>
                <td>
                    <div><strong>When Needed:</strong><?php echo $datum->when_needed ?? '*' ?></div>
                    <div><strong>Quantity Needed:</strong><?php echo $datum->volume ?? '*' ?></div>
                    <div><strong>Dimension:</strong><?php echo $datum->dimension ?? '*' ?></div>
                    <div><strong>Part Number:</strong><?php echo $datum->part_number ?? '*' ?></div>
                </td>
                <td>
                    <?php if (is_array($o_ring)): ?>
                        <?php foreach ($o_ring as $key => $value): ?>
                            <div><strong><?php echo ucfirst(str_replace('-', ' ', $key)) ?? '*' ?>:</strong><?php echo $value ?? '*' ?></div>
                        <?php endforeach ?>
                    <?php endif ?>
                </td>
                <td>
                    <?php echo $datum->additional_information ?? '*' ?>
                </td>
                <td>
                    <?php /*if (is_array($datum->files)):  */?><!--
                        <?php /*foreach ($datum->files as $file): */?>
                            <a href="<?php /*echo wp_get_attachment_url($datum->files) */?>">View</a><br>
                        <?php /*endforeach */?>
                    --><?php /*endif */?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif ?>
