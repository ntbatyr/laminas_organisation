document.addEventListener('DOMContentLoaded', function () {
    let flashAlert = document.getElementsByClassName('auto-remove');
    let bsAlert = new bootstrap.Alert(flashAlert[0]);
    setTimeout(function () { bsAlert.close(); }, 7000);

    let localeForm = document.getElementById('localeSetForm');

    localeForm.addEventListener('change', function (ev) {
        document.querySelector('input[type="hidden"][name="url_back"]').value = window.location.pathname;
        ev.target.closest('form#localeSetForm').submit();
    });
});