jq2 = jQuery.noConflict();
jq2(function ($) {

    $(document).on('dragover', '#dropzone', function (e) {
        e.preventDefault();
        $(this).addClass('bg-light')
    })

    $(document).on('dragleave', '#dropzone', function (e) {
        e.preventDefault();
        $(this).removeClass('bg-light')
    })

    $(document).on('drop', '#dropzone', function (e) {
        e.preventDefault();
        $(this).removeClass('bg-light');
        const files = e.dataTransfer.files;
        handleFiles(files);
    })

    $(document).on('click', '#dropzone', function (e) {
        $(e.delegateTarget).find('#file').trigger('click')
    })

    $(document).on('change', '#file', function (e) {
        const files = $(this)[0].files;
        handleFiles(files);
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
