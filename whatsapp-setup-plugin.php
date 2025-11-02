<?php

/**
 * Plugin Name: Lucas Setup
 * Plugin URI: https://github.com/Lucas-Henrique-Lopes-Costa/setup-wordpress
 * Description: Plugin completo para adicionar botão flutuante do WhatsApp, customizações de scroll e máscaras de telefone
 * Version: 1.0.0
 * Author: Lucas Henrique
 * Author URI: https://github.com/Lucas-Henrique-Lopes-Costa
 * License: GPL v2 or later
 * Text Domain: lucas-setup
 */

// Impedir acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Definir constantes
define('WASP_VERSION', '1.0.0');
define('WASP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WASP_PLUGIN_URL', plugin_dir_url(__FILE__));

class WhatsApp_Setup_Plugin
{

    private $option_name = 'wasp_settings';

    public function __construct()
    {
        // Ações de ativação e desativação
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));

        // Hooks
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('wp_footer', array($this, 'inject_code'), 999);
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    /**
     * Ativação do plugin
     */
    public function activate()
    {
        // Configurações padrão
        $default_settings = array(
            'phone_numbers' => array('5537988347387'),
            'default_message' => 'Olá, vim pelo site!',
            'button_enabled' => true,
            'replace_hash_links' => true,
            'pages_to_show' => array('all'),
            'link_mode' => 'phone',
            'custom_link' => '',
            // array page_id => custom link
            'page_custom_links' => array(),
            'button_position_right' => '20',
            'button_position_bottom' => '20',
            'button_size' => '50'
        );

        if (!get_option($this->option_name)) {
            add_option($this->option_name, $default_settings);
        }
    }

    /**
     * Desativação do plugin
     */
    public function deactivate()
    {
        // Limpar cache se necessário
    }

    /**
     * Adicionar menu no admin
     */
    public function add_admin_menu()
    {
        add_menu_page(
            'Lucas Setup',
            'Lucas Setup',
            'manage_options',
            'whatsapp-setup',
            array($this, 'settings_page'),
            'dashicons-whatsapp',
            100
        );
    }

    /**
     * Registrar configurações
     */
    public function register_settings()
    {
        register_setting('wasp_settings_group', $this->option_name, array($this, 'sanitize_settings'));
    }

    /**
     * Sanitizar configurações
     */
    public function sanitize_settings($input)
    {
        $sanitized = array();

        // Sanitizar telefones
        if (isset($input['phone_numbers'])) {
            $phones = explode("\n", $input['phone_numbers']);
            $sanitized['phone_numbers'] = array_map('trim', $phones);
            $sanitized['phone_numbers'] = array_filter($sanitized['phone_numbers']);
        }

        // Sanitizar mensagem
        $sanitized['default_message'] = isset($input['default_message']) ? sanitize_text_field($input['default_message']) : 'Olá, vim pelo site!';

        // Checkboxes
        $sanitized['button_enabled'] = isset($input['button_enabled']) ? true : false;
        $sanitized['replace_hash_links'] = isset($input['replace_hash_links']) ? true : false;

        // Páginas para mostrar
        $sanitized['pages_to_show'] = isset($input['pages_to_show']) ? $input['pages_to_show'] : array('all');

        // Modo de link: 'phone' (usar números) ou 'custom' (usar link personalizado)
        $sanitized['link_mode'] = (isset($input['link_mode']) && $input['link_mode'] === 'custom') ? 'custom' : 'phone';

        // Link personalizado global (opcional)
        $sanitized['custom_link'] = isset($input['custom_link']) ? esc_url_raw($input['custom_link']) : '';

        // Links personalizados por página (opcional)
        $sanitized['page_custom_links'] = array();
        if (isset($input['page_custom_links']) && is_array($input['page_custom_links'])) {
            foreach ($input['page_custom_links'] as $page_id => $link) {
                $page_id = intval($page_id);
                $link = trim($link);
                if ($link !== '') {
                    $sanitized['page_custom_links'][$page_id] = esc_url_raw($link);
                }
            }
        }

        // Posições e tamanho
        $sanitized['button_position_right'] = isset($input['button_position_right']) ? intval($input['button_position_right']) : 20;
        $sanitized['button_position_bottom'] = isset($input['button_position_bottom']) ? intval($input['button_position_bottom']) : 20;
        $sanitized['button_size'] = isset($input['button_size']) ? intval($input['button_size']) : 50;

        return $sanitized;
    }

    /**
     * Scripts do admin
     */
    public function enqueue_admin_scripts($hook)
    {
        if ($hook != 'toplevel_page_whatsapp-setup') {
            return;
        }

        wp_enqueue_style('wasp-admin-style', WASP_PLUGIN_URL . 'admin-style.css', array(), WASP_VERSION);
        wp_enqueue_script('wasp-admin-script', WASP_PLUGIN_URL . 'admin-script.js', array('jquery'), WASP_VERSION, true);
    }

    /**
     * Verificar se deve mostrar em uma página específica
     */
    private function should_show_button()
    {
        $settings = get_option($this->option_name);

        if (!$settings['button_enabled']) {
            return false;
        }

        $pages_to_show = isset($settings['pages_to_show']) ? $settings['pages_to_show'] : array('all');

        if (in_array('all', $pages_to_show)) {
            return true;
        }

        $current_id = get_the_ID();

        if (is_front_page() && in_array('home', $pages_to_show)) {
            return true;
        }

        if (in_array($current_id, $pages_to_show)) {
            return true;
        }

        return false;
    }

    /**
     * Injetar código no footer
     */
    public function inject_code()
    {
        $settings = get_option($this->option_name);

        if (!$settings) {
            return;
        }

        // Preparar número de telefone (randomizar se houver múltiplos)
        $phones = isset($settings['phone_numbers']) ? $settings['phone_numbers'] : array('5537988347387');
        $random_phone = $phones[array_rand($phones)];

        // Mensagem padrão
        $message = isset($settings['default_message']) ? urlencode($settings['default_message']) : urlencode('Olá, vim pelo site!');

        // Criar link do WhatsApp
        $whatsapp_link = "https://wa.me/{$random_phone}?text={$message}";

        // Posições e tamanho
        $right = isset($settings['button_position_right']) ? $settings['button_position_right'] : 20;
        $bottom = isset($settings['button_position_bottom']) ? $settings['button_position_bottom'] : 20;
        $size = isset($settings['button_size']) ? $settings['button_size'] : 50;

        // URL da imagem do WhatsApp
        $whatsapp_image = WASP_PLUGIN_URL . 'whatsapp.png';

        // Determinar link de substituição seguindo o modo selecionado
        $current_page_id = get_the_ID();
        $replacement_link = '';
        if (isset($settings['link_mode']) && $settings['link_mode'] === 'custom') {
            // Se estiver no modo custom, prioriza link por página, depois link global
            if (!empty($settings['page_custom_links']) && isset($settings['page_custom_links'][$current_page_id])) {
                $replacement_link = $settings['page_custom_links'][$current_page_id];
            } elseif (!empty($settings['custom_link'])) {
                $replacement_link = $settings['custom_link'];
            } else {
                // fallback para WhatsApp se não houver custom link
                $replacement_link = $whatsapp_link;
            }
        } else {
            // modo 'phone' (padrão) - usa link montado com telefone
            $replacement_link = $whatsapp_link;
        }

?>

        <!-- Lucas Setup Plugin -->
        <?php if ($this->should_show_button()): ?>
            <a id="whatsapp" href="<?php echo esc_url($replacement_link); ?>" target="_blank" rel="noopener noreferrer">
                <img src="<?php echo esc_url($whatsapp_image); ?>"
                    alt="WhatsApp"
                    width="<?php echo esc_attr($size); ?>px"
                    height="<?php echo esc_attr($size); ?>px">
            </a>
        <?php endif; ?>

        <style>
            /* Personalizar barra de scroll vertical */

            /* Chrome, Edge e Safari */
            body::-webkit-scrollbar {
                width: 8px !important;
                /*largura da barra*/
            }

            body::-webkit-scrollbar-thumb {
                background-color: var(--e-global-color-primary);
                /*cor do controle do scroll*/
                border-radius: 10px;
                /*arredondamento*/
                border: 0px solid #A447D9;
                /*borda*/
            }

            body::-webkit-scrollbar-track {
                background: #030014;
                /*cor do fundo*/
            }

            /* Firefox */
            @-moz-document url-prefix() {
                * {
                    scrollbar-width: 8px;
                    scrollbar-color:
                        var(--e-global-color-primary)
                        /*cor do controle do scroll*/
                        #A5A5A5;
                    /*cor do fundo*/
                    -moz-appearance: scrollbar;
                }
            }

            iframe {
                height: 60%
            }

            #perguntas div {
                border-radius: 15px;
            }

            body {
                overflow-x: hidden;
            }

            @media (max-width: 768px) {

                body,
                html {
                    overflow-x: hidden !important;
                }
            }

            @media (max-width: 1024px) and (min-width: 769px) {

                body,
                html {
                    overflow-x: hidden;
                }
            }

            #whatsapp {
                position: fixed;
                right: <?php echo esc_attr($right); ?>px;
                bottom: <?php echo esc_attr($bottom); ?>px;
                z-index: 9999;
                transition: transform 0.3s ease;
            }

            #whatsapp:hover {
                transform: scale(1.1);
            }

            @media (max-width: 768px) {
                #whatsapp {
                    right: 10px;
                    bottom: 10px;
                }
            }

            .site {
                hyphens: none !important;
            }
        </style>

        <script>
            <?php if (isset($settings['replace_hash_links']) && $settings['replace_hash_links']): ?>
                document.addEventListener("DOMContentLoaded", function() {
                    // Seleciona todos os elementos <a> que possuem href="#"
                    var buttons = document.querySelectorAll('a[href="#"]');

                    // Novo link do WhatsApp
                    var newLink = "<?php echo esc_js($replacement_link); ?>";

                    // Loop através de todos os botões encontrados e altera o href
                    buttons.forEach(function(button) {
                        button.href = newLink;
                        button.target = "_blank";
                        button.rel = "noopener noreferrer";
                    });
                });
            <?php endif; ?>

            // Máscara de telefone
            function mascara(o, f) {
                v_obj = o
                v_fun = f
                setTimeout("execmascara()", 1)
            }

            function execmascara() {
                v_obj.value = v_fun(v_obj.value)
            }

            function mtel(v) {
                v = v.replace(/\D/g, ""); //Remove tudo o que não é dígito
                v = v.replace(/^(\d{2})(\d)/g, "($1) $2"); //Coloca parênteses em volta dos dois primeiros dígitos
                v = v.replace(/(\d)(\d{4})$/, "$1-$2"); //Coloca hífen entre o quarto e o quinto dígitos
                return v;
            }

            function id(el) {
                return document.getElementById(el);
            }

            window.addEventListener('load', function() {
                var telefone = document.querySelectorAll('.elementor-form input[name="form_fields[telefone]"]');

                for (i = 0; i < telefone.length; i++) {
                    telefone[i].setAttribute('pattern', '.{14,}');
                    telefone[i].setAttribute('maxlength', 15);
                    telefone[i].onkeypress = function() {
                        mascara(this, mtel);
                    }
                }
            });
        </script>
        <!-- /Lucas Setup Plugin -->

    <?php
    }

    /**
     * Página de configurações
     */
    public function settings_page()
    {
        $settings = get_option($this->option_name);

        // Obter todas as páginas e posts
        $pages = get_pages();
    ?>

        <div class="wrap wasp-settings">
            <h1>
                <span class="dashicons dashicons-whatsapp" style="font-size: 30px; margin-right: 10px;"></span>
                Lucas Setup
            </h1>

            <form method="post" action="options.php">
                <?php settings_fields('wasp_settings_group'); ?>

                <div class="wasp-card">
                    <h2>📱 Configurações do WhatsApp</h2>

                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="phone_numbers">Números de Telefone</label>
                            </th>
                            <td>
                                <textarea
                                    name="<?php echo esc_attr($this->option_name); ?>[phone_numbers]"
                                    id="phone_numbers"
                                    rows="5"
                                    class="large-text"
                                    placeholder="5537988347387"><?php echo esc_textarea(implode("\n", $settings['phone_numbers'])); ?></textarea>
                                <p class="description">
                                    Digite um número por linha (com código do país e DDD). <br>
                                    Se houver múltiplos números, um será escolhido aleatoriamente a cada visita.
                                </p>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">
                                <label for="default_message">Mensagem Padrão</label>
                            </th>
                            <td>
                                <input
                                    type="text"
                                    name="<?php echo esc_attr($this->option_name); ?>[default_message]"
                                    id="default_message"
                                    value="<?php echo esc_attr($settings['default_message']); ?>"
                                    class="regular-text"
                                    placeholder="Olá, vim pelo site!">
                                <p class="description">Mensagem que será enviada automaticamente ao clicar no botão.</p>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">Modo de Link</th>
                            <td>
                                <label style="margin-right:15px;">
                                    <input type="radio" name="<?php echo esc_attr($this->option_name); ?>[link_mode]" value="phone" <?php checked(isset($settings['link_mode']) ? $settings['link_mode'] : 'phone', 'phone'); ?>> Usar números de telefone
                                </label>
                                <label>
                                    <input type="radio" name="<?php echo esc_attr($this->option_name); ?>[link_mode]" value="custom" <?php checked(isset($settings['link_mode']) ? $settings['link_mode'] : '', 'custom'); ?>> Usar link personalizado
                                </label>
                                <p class="description">Escolha se o botão deve abrir um link montado com os números (aleatório) ou um link totalmente personalizado.</p>
                            </td>
                        </tr>

                        <tr class="wasp-custom-link-row" style="<?php echo (isset($settings['link_mode']) && $settings['link_mode'] === 'custom') ? '' : 'display:none;'; ?>">
                            <th scope="row">
                                <label for="custom_link">Link Personalizado (opcional)</label>
                            </th>
                            <td>
                                <input
                                    type="text"
                                    name="<?php echo esc_attr($this->option_name); ?>[custom_link]"
                                    id="custom_link"
                                    value="<?php echo esc_attr(isset($settings['custom_link']) ? $settings['custom_link'] : ''); ?>"
                                    class="regular-text"
                                    placeholder="https://exemplo.com/lead?utm=site">
                                <p class="description">Se preenchido e o modo for "Usar link personalizado", este link será utilizado em vez do WhatsApp.</p>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="wasp-card">
                    <h2>🎨 Configurações do Botão Flutuante</h2>

                    <table class="form-table">
                        <tr>
                            <th scope="row">Ativar Botão</th>
                            <td>
                                <label>
                                    <input
                                        type="checkbox"
                                        name="<?php echo esc_attr($this->option_name); ?>[button_enabled]"
                                        value="1"
                                        <?php checked($settings['button_enabled'], true); ?>>
                                    Mostrar botão flutuante do WhatsApp
                                </label>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">Substituir Links "#"</th>
                            <td>
                                <label>
                                    <input
                                        type="checkbox"
                                        name="<?php echo esc_attr($this->option_name); ?>[replace_hash_links]"
                                        value="1"
                                        <?php checked($settings['replace_hash_links'], true); ?>>
                                    Substituir todos os links com href="#" pelo link do WhatsApp
                                </label>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">Tamanho do Botão</th>
                            <td>
                                <input
                                    type="number"
                                    name="<?php echo esc_attr($this->option_name); ?>[button_size]"
                                    value="<?php echo esc_attr($settings['button_size']); ?>"
                                    min="30"
                                    max="100"
                                    class="small-text"> px
                                <p class="description">Tamanho do botão em pixels (padrão: 50px)</p>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">Posição do Botão</th>
                            <td>
                                <label>
                                    Direita:
                                    <input
                                        type="number"
                                        name="<?php echo esc_attr($this->option_name); ?>[button_position_right]"
                                        value="<?php echo esc_attr($settings['button_position_right']); ?>"
                                        min="0"
                                        max="500"
                                        class="small-text"> px
                                </label>
                                <br><br>
                                <label>
                                    Inferior:
                                    <input
                                        type="number"
                                        name="<?php echo esc_attr($this->option_name); ?>[button_position_bottom]"
                                        value="<?php echo esc_attr($settings['button_position_bottom']); ?>"
                                        min="0"
                                        max="500"
                                        class="small-text"> px
                                </label>
                                <p class="description">Distância das bordas da tela (padrão: 20px)</p>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="wasp-card">
                    <h2>📄 Páginas para Exibir</h2>

                    <table class="form-table">
                        <tr>
                            <th scope="row">Exibir em:</th>
                            <td>
                                <label>
                                    <input
                                        type="checkbox"
                                        name="<?php echo esc_attr($this->option_name); ?>[pages_to_show][]"
                                        value="all"
                                        <?php checked(in_array('all', $settings['pages_to_show']), true); ?>
                                        id="show_all_pages">
                                    <strong>Todas as páginas</strong>
                                </label>
                                <br><br>

                                <div id="specific_pages" style="margin-left: 20px;">
                                    <p><em>Ou selecione páginas específicas:</em></p>

                                    <label>
                                        <input
                                            type="checkbox"
                                            name="<?php echo esc_attr($this->option_name); ?>[pages_to_show][]"
                                            value="home"
                                            <?php checked(in_array('home', $settings['pages_to_show']), true); ?>>
                                        Página Inicial
                                    </label><br>

                                    <?php foreach ($pages as $page): ?>
                                        <label>
                                            <input
                                                type="checkbox"
                                                name="<?php echo esc_attr($this->option_name); ?>[pages_to_show][]"
                                                value="<?php echo esc_attr($page->ID); ?>"
                                                <?php checked(in_array($page->ID, $settings['pages_to_show']), true); ?>>
                                            <?php echo esc_html($page->post_title); ?>
                                        </label><br>
                                        <div style="margin-left:20px; margin-bottom:8px;">
                                            <label style="font-size:13px;color:#666;">Link personalizado para esta página (opcional):</label>
                                            <input
                                                type="text"
                                                name="<?php echo esc_attr($this->option_name); ?>[page_custom_links][<?php echo esc_attr($page->ID); ?>]"
                                                value="<?php echo isset($settings['page_custom_links'][$page->ID]) ? esc_attr($settings['page_custom_links'][$page->ID]) : ''; ?>"
                                                placeholder="https://seusite.com/minha-pagina"
                                                class="regular-text">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <?php submit_button('Salvar Configurações'); ?>
            </form>

        </div>

<?php
    }
}

// Inicializar o plugin
new WhatsApp_Setup_Plugin();
