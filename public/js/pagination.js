$(document).ready(function() {
    $(document).on('click', '.pagination a', function (e) {
       e.preventDefault();

       let url = $(this).attr('href');
       loadPaginatedResults(url);
    });

    function loadPaginatedResults(url) {
       $.ajax({
             url: url,
             type: 'GET',
             dataType: 'html',
             success: function(response) {
                console.log(response)
                $('#response-Ajax').html(response);
             },
             error: function(xhr, status, error) {
                // Gérer les erreurs éventuelles
             }
       });
    }

 });