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

    // Mostrar/Esconder campo de link customizado dependendo do modo selecionado
    function toggleLinkModeFields() {
        var mode = $('input[name$="[link_mode]"]:checked').val();
        if (mode === 'custom') {
            $('.wasp-custom-link-row').show();
        } else {
            $('.wasp-custom-link-row').hide();
        }
    }

    // Iniciar toggle no carregamento
    try {
        toggleLinkModeFields();
    } catch (e) {
        // ignore if selector not available in this context
    }

    // Quando o modo mudar
    $(document).on('change', 'input[name$="[link_mode]"]', function () {
        toggleLinkModeFields();
    });

    // Preview (melhorado para mostrar link personalizado quando aplicável)
    $('#phone_numbers, #default_message, #custom_link').on('input', function () {
        updatePreview();
    });

    function updatePreview() {
        var mode = $('input[name$="[link_mode]"]:checked').val() || 'phone';
        var message = $('#default_message').val() || '';

        if (mode === 'custom') {
            var custom = $('#custom_link').val().trim();
            if (custom !== '') {
                console.log('Preview Custom Link:', custom);
                return;
            }
        }

        var phones = $('#phone_numbers').val().split('\n').filter(function (line) {
            return line.trim() !== '';
        });

        if (phones.length > 0) {
            var randomPhone = phones[Math.floor(Math.random() * phones.length)].trim();
            var encodedMessage = encodeURIComponent(message);
            var previewLink = 'https://wa.me/' + randomPhone + '?text=' + encodedMessage;
            console.log('Preview WhatsApp Link:', previewLink);
        }
    }

    // Adicionar tooltip
    $('.description').each(function () {
        $(this).attr('title', $(this).text());
    });

});
