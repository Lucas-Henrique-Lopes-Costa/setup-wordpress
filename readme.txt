=== Lucas Setup ===
Contributors: lucashenrique
Tags: whatsapp, button, floating, contact, custom-scroll
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 1.0.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Plugin completo para adicionar botão flutuante do WhatsApp com randomização de números, customizações de scroll e máscaras de telefone.

== Description ==

O **Lucas Setup** é uma solução completa para integrar o WhatsApp ao seu site WordPress. Com ele, você pode:

* ✅ Adicionar um botão flutuante do WhatsApp em qualquer página
* ✅ Configurar múltiplos números com randomização automática
* ✅ Personalizar mensagem padrão
* ✅ Substituir automaticamente links vazios (#) pelo WhatsApp
* ✅ Adicionar máscaras de telefone em formulários Elementor
* ✅ Customizar a barra de scroll
* ✅ Corrigir problemas de overflow responsivo

== Features ==

### Botão Flutuante do WhatsApp
- Imagem do WhatsApp oficial
- Totalmente customizável (posição, tamanho)
- Responsivo para mobile
- Efeito hover suave

### Múltiplos Números
- Adicione quantos números quiser
- Randomização automática a cada visita
- Distribui as conversas entre diferentes atendentes

### Controle Total
- Escolha em quais páginas exibir
- Ative/desative funcionalidades individualmente
- Interface amigável no painel do WordPress

### Customizações Incluídas
- Barra de scroll personalizada
- Máscaras automáticas em campos de telefone
- Correções de overflow para mobile e tablet
- Estilos otimizados

== Installation ==

1. Faça upload da pasta `whatsapp-setup-plugin` para o diretório `/wp-content/plugins/`
2. Ative o plugin através do menu 'Plugins' no WordPress
3. Vá em 'WhatsApp Setup' no menu lateral para configurar
4. Configure seus números de telefone e mensagem padrão
5. Salve e pronto!

== Frequently Asked Questions ==

= Como adicionar múltiplos números? =

No painel de configurações, adicione um número por linha no campo "Números de Telefone". O plugin irá escolher um aleatoriamente a cada visita.

= Como faço para o botão aparecer apenas em páginas específicas? =

No painel de configurações, desmarque "Todas as páginas" e selecione apenas as páginas onde deseja exibir o botão.

= O plugin funciona com o Elementor? =

Sim! O plugin adiciona automaticamente máscaras de telefone em campos de formulário do Elementor.

= Posso customizar a posição do botão? =

Sim, você pode ajustar a distância das bordas (direita e inferior) e também o tamanho do botão.

= O plugin afeta a performance do site? =

Não. O código é leve e otimizado, sendo carregado apenas no footer do site.

== Screenshots ==

1. Painel de configurações principal
2. Configurações do botão flutuante
3. Seleção de páginas para exibir
4. Exemplo do botão no site

== Changelog ==

= 1.0.0 =
* Versão inicial
* Botão flutuante do WhatsApp
* Randomização de múltiplos números
* Substituição de links vazios
* Máscaras de telefone
* Customizações de scroll
* Painel de configurações completo

== Upgrade Notice ==

= 1.0.0 =
Versão inicial do plugin.

== Developer Notes ==

### Hooks Disponíveis

O plugin oferece alguns hooks para desenvolvedores:

**Filtros:**
- `wasp_whatsapp_link` - Modifica o link do WhatsApp gerado
- `wasp_should_show_button` - Controla se o botão deve ser exibido
- `wasp_button_html` - Customiza o HTML do botão

**Ações:**
- `wasp_before_inject_code` - Executa antes de injetar o código
- `wasp_after_inject_code` - Executa depois de injetar o código

### Exemplo de uso:

```php
// Modificar o link do WhatsApp
add_filter('wasp_whatsapp_link', function($link, $phone, $message) {
    // Seu código aqui
    return $link;
}, 10, 3);
```

== Support ==

Para suporte, visite: https://github.com/Lucas-Henrique-Lopes-Costa/setup-wordpress

== Credits ==

Desenvolvido por Lucas Henrique
GitHub: https://github.com/Lucas-Henrique-Lopes-Costa
