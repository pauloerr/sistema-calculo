$(document).ready(function() {
    $('#logonForm').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();

        $.post('authenticate.php', formData, function(response) {
            var data = JSON.parse(response);
            if (data.success) {
                window.location.href = '../index.php';
            } else {
                $('#error-message').text(data.message).removeClass('d-none');
            }
        });
    });
});
