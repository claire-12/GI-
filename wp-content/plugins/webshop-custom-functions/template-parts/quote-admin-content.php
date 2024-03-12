<div class="wrap">
    <h2>Request a Quote</h2>
    <div class="custom-select-filter">
        <div class="date-filter">
            <label for="custom-select-filter">Filter by Date:</label>
            <input type="date" id="date-filter">
        </div>
        <div class="status-filter">
            <label for="custom-select-filter">Filter by Status:</label>
            <select id="custom-select-filter">
                <option value="">All</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
        <div class="email-filter">
            <label for="email-select-filter">Filter by User:</label>
            <select id="email-select-filter">
                <option value="">All</option>
                <?php foreach ($users as $email => $user): ?>
                    <option value="<?php echo $email ?>"><?php echo empty($user) ? $email : $user['billing_company'] ?></option>
                <?php endforeach ?>
            </select>
        </div>
    </div>
    <table id="quote-table" class="widefat">
        <thead>
        <tr>
            <th>Status</th>
            <th>Email</th>
            <th>Date</th>
            <th>Company</th>
            <th>Product Of Interest</th>
            <th>When Needed</th>
            <th>Quantity needed</th>
            <th>Dimension</th>
            <th>Part Number</th>
            <th>O-ring</th>
            <th>Filters</th>
            <th>Additional Information</th>
            <th>Object ID</th>
            <th>Object Type</th>
            <th>Files</th>
            <!--<th></th>-->
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<style>
    .dataTables_wrapper .dataTables_length select {
        padding-right: 25px !important;
    }

    .dataTables_wrapper .dataTables_filter input {
        background-color: #fff
    }

    .custom-select-filter {
        display: flex;
        justify-content: end;
        align-items: center;
        margin-bottom: 5px;
    }

    .custom-select-filter label {
        margin-right: 5px;
    }
</style>
<script>
    // Custom function to parse the serialized string
    function parseSerializedString(serializedString, isKey) {
        const regex = /s:(\d+):"([^"]+)";/g;
        const result = [];
        let match;

        while ((match = regex.exec(serializedString)) !== null) {
            let [, keyLength, key] = match;
            if (key===''){
                key = '';
            }
            result.push(key)
        }

        const keyValuePairs = [];
        if (isKey){
            for (let i = 0; i < result.length; i += 1) {
                keyValuePairs.push(result[i]);
            }
        } else {
            for (let i = 0; i < result.length; i += 2) {
                const key = result[i];
                const value = result[i + 1] || '-'; // Use "-" if the value is empty
                keyValuePairs.push(`<b>${key}</b>: ${value}`);
            }
        }

        return keyValuePairs.join('<br>');
    }

    jQuery(document).ready(function ($) {
        const quoteTableHtml = $('#quote-table');
        const dataTable = quoteTableHtml.DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": ajaxurl,
                "type": "POST",
                "data": function (data) {
                    data.action = 'get_quote_data_ajax';
                }
            },
            "columns": [
                {"data": "status"},
                {"data": "email"},
                {"data": "date"},
                {
                    "data": "data_company",
                    "render": function (data) {
                        return parseSerializedString(data);
                    }
                },
                {"data": "product_of_interest"},
                {"data": "when_needed"},
                {"data": "volume"},
                {"data": "dimension"},
                {"data": "part_number"},
                {
                    "data": "data_o_ring",
                    "render": function (data) {
                        return parseSerializedString(data);
                    }
                },
                {
                    "data": "quote_filter",
                    "render": function (data) {
                        return parseSerializedString(data, true);
                    }
                },
                {"data": "additional_information"},
                {"data": "object_id"},
                {"data": "object_type"},
                {"data": "files"},
                /*{
                    "data": null,
                    "render": function (data, type, full, meta) {
                        return '<button class="edit-button" data-id="' + data.id + '">Edit</button><button class="remove-button" data-id="' + data.id + '">Remove</button>';
                    }
                }*/
            ]
        });
        // Add custom select filter event handler
        $('#custom-select-filter').on('change', function () {
            const selectedValue = $(this).val();
            dataTable.columns(0).search(selectedValue).draw();
        });
        $('#date-filter').on('change', function () {
            const selectedValue = $(this).val();
            dataTable.columns(2).search(selectedValue).draw();
        });
        $('#email-select-filter').on('change', function () {
            const selectedValue = $(this).val();
            dataTable.columns(1).search(selectedValue).draw();
        });
        // Handle edit button click
        quoteTableHtml.on('click', '.edit-button', function () {
            const id = $(this).data('id');
            // Implement your edit logic here, e.g., open a modal with the record for editing
        });

        // Handle remove button click
        quoteTableHtml.on('click', '.remove-button', function () {
            const id = $(this).data('id');
            // Implement your remove logic here, e.g., confirm and delete the record
        });
    });
</script>
