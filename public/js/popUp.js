document.addEventListener('DOMContentLoaded', function() {
    var popUpButton = document.getElementById('pop-up');
    var form2Container = document.getElementById('form2-container');

    popUpButton.addEventListener('click', function() {
        form2Container.classList.toggle('hidden');
    });
});
