jq2 = jQuery.noConflict();
jq2(function ($) {

    $(document).on('dragover', '#dropzone', function(e){
        e.preventDefault();
        $(this).addClass('bg-light')
    })

    $(document).on('dragleave', '#dropzone', function(e){
        e.preventDefault();
        $(this).removeClass('bg-light')
    })

    $(document).on('drop', '#dropzone', function(e){
        e.preventDefault();
        $(this).removeClass('bg-light');
        const files = e.dataTransfer.files;
        handleFiles(files);
    })

    $(document).on('click', '#dropzone', function(e){
        $(e.delegateTarget).find('#file').trigger('click')
    })

    $(document).on('change', '#file', function(e){
        const files = $(this)[0].files;
        handleFiles(files);
    })

    $(document).on('submit', '#form-request-quote', function () {
        // Use a regular expression to check for the criteria:
        // At least 8 characters
        // At least one uppercase letter
        // At least one lowercase letter
        // At least one number
        // At least one special character (e.g., @, #, $, etc.)
        const form = $(this);
        const passwordElement = $('#quote-password');
        const strongPasswordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}$/;
        //if (strongPasswordRegex.test(passwordElement.val())) {
            const formData = new FormData(this);
            formData.append('action', 'cabling_request_quote');
            $.ajax({
                url: CABLING.ajax_url,
                type: 'POST',
                dataType: 'json',
                processData: false,
                contentType: false,
                data: formData,
                success: function (response) {
                    form.prepend(response.data);
                    if (response.success) {
                        setTimeout(function () {
                            window.location.reload();
                        }, 3000);
                    } else {
                        passwordElement.val('');
                    }
                },
                beforeSend: function () {
                    showLoading();
                }
            })
                .done(function () {
                    hideLoading();
                });
        // } else {
        //     passwordElement.addClass('invalid');
        //     passwordElement.focus();
        // }
        return false;
    })
});

function handleFiles(files) {
    const fileList = document.getElementById('file-list');
    fileList.innerHTML = '';

    for (let i = 0; i < files.length; i++) {
        const li = document.createElement('li');
        li.textContent = files[i].name;
        fileList.appendChild(li);
    }
}