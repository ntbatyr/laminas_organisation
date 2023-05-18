document.addEventListener('DOMContentLoaded', function () {
    let flashAlert = document.getElementsByClassName('auto-remove');
    let bsAlert = new bootstrap.Alert(flashAlert[0]);
    setTimeout(function () { bsAlert.close(); }, 7000);
});