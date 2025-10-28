/* Lucas Setup - Admin Scripts */

jQuery(document).ready(function ($) {

    // Toggle entre "Todas as páginas" e páginas específicas
    $('#show_all_pages').on('change', function () {
        if ($(this).is(':checked')) {
            $('#specific_pages input[type="checkbox"]').prop('checked', false);
        }
    });

    $('#specific_pages input[type="checkbox"]').on('change', function () {
        if ($(this).is(':checked')) {
            $('#show_all_pages').prop('checked', false);
        }
    });

    // Preview do número de telefone
    $('#phone_numbers, #default_message').on('input', function () {
        updatePreview();
    });

    function updatePreview() {
        var phones = $('#phone_numbers').val().split('\n').filter(function (line) {
            return line.trim() !== '';
        });

        var message = $('#default_message').val();

        if (phones.length > 0) {
            var randomPhone = phones[Math.floor(Math.random() * phones.length)].trim();
            var encodedMessage = encodeURIComponent(message);
            var previewLink = 'https://wa.me/' + randomPhone + '?text=' + encodedMessage;

            console.log('Preview WhatsApp Link:', previewLink);
        }
    }

    // Validação de formulário
    $('form').on('submit', function (e) {
        var phones = $('#phone_numbers').val().trim();

        if (phones === '') {
            alert('Por favor, adicione pelo menos um número de telefone.');
            e.preventDefault();
            return false;
        }

        var phoneLines = phones.split('\n');
        var invalidPhones = [];

        phoneLines.forEach(function (phone) {
            phone = phone.trim();
            if (phone !== '' && !/^\d{10,15}$/.test(phone)) {
                invalidPhones.push(phone);
            }
        });

        if (invalidPhones.length > 0) {
            alert('Os seguintes números parecem inválidos:\n\n' + invalidPhones.join('\n') + '\n\nCertifique-se de incluir o código do país e DDD sem espaços ou caracteres especiais.');
            e.preventDefault();
            return false;
        }
    });

    // Adicionar tooltip
    $('.description').each(function () {
        $(this).attr('title', $(this).text());
    });

});
