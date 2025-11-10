# 📱 Lucas Setup - Plugin para WordPress

Plugin completo para adicionar botão flutuante do WhatsApp com randomização de números, customizações de scroll e máscaras de telefone.

![WordPress](https://img.shields.io/badge/WordPress-5.0%2B-blue)
![PHP](https://img.shields.io/badge/PHP-7.0%2B-purple)
![License](https://img.shields.io/badge/License-GPLv2-green)
![Version](https://img.shields.io/badge/Version-1.0.0-orange)

## 🚀 Features

### ✨ Principais Funcionalidades

- **Botão Flutuante do WhatsApp**: Botão fixo e responsivo com a imagem oficial do WhatsApp
- **Múltiplos Números**: Adicione vários números e o plugin randomiza automaticamente
- **Mensagem Personalizada**: Configure a mensagem padrão que será enviada
- **Substituição de Links**: Converte automaticamente todos os links `#` para WhatsApp
- **Máscaras de Telefone**: Adiciona máscaras automáticas em formulários Elementor
- **Scroll Customizado**: Barra de scroll personalizada com as cores do seu tema
- **Totalmente Responsivo**: Funciona perfeitamente em desktop, tablet e mobile
- **Painel Intuitivo**: Interface amigável no admin do WordPress

## 📦 Instalação

### Método 1: Upload Manual

1. Faça download deste repositório
2. Compacte os arquivos em um arquivo `.zip`
3. No WordPress, vá em **Plugins → Adicionar Novo**
4. Clique em **Enviar Plugin** e selecione o arquivo `.zip`
5. Ative o plugin

### Método 2: FTP

1. Faça download deste repositório
2. Extraia os arquivos para a pasta `/wp-content/plugins/whatsapp-setup-plugin`
3. No WordPress, vá em **Plugins** e ative o plugin

### Método 3: Git Clone

```bash
cd wp-content/plugins/
git clone https://github.com/Lucas-Henrique-Lopes-Costa/setup-wordpress.git whatsapp-setup-plugin
```

## ⚙️ Configuração

Após ativar o plugin, você verá um novo menu **WhatsApp Setup** no painel do WordPress.

### 1. Configurações do WhatsApp

- **Números de Telefone**: Adicione um ou mais números (um por linha)
  - Formato: `5537988347387` (código do país + DDD + número)
  - Exemplo: `5511999999999`
  
- **Mensagem Padrão**: Texto que será enviado automaticamente
  - Padrão: `Olá, vim pelo site!`

### 2. Configurações do Botão

- **Ativar Botão**: Ativa/desativa o botão flutuante
- **Substituir Links "#"**: Converte links vazios em links do WhatsApp
- **Lado do Botão**: Escolha se o botão fica à direita ou à esquerda
- **Tamanho do Botão**: Ajuste o tamanho (30-100px)
- **Posição**: Configure a distância das bordas (lateral e inferior)

### 3. Páginas para Exibir

- **Todas as páginas**: Exibe em todo o site
- **Páginas específicas**: Selecione apenas onde deseja mostrar
- **Página inicial**: Opção separada para a home

## 🎨 Customizações Incluídas

O plugin adiciona automaticamente:

- ✅ Scroll bar personalizada (Chrome, Firefox, Safari, Edge)
- ✅ Máscaras de telefone formato brasileiro: `(11) 99999-9999`
- ✅ Correção de overflow horizontal responsivo
- ✅ Iframe com altura ajustada
- ✅ Border-radius em seção de perguntas
- ✅ Efeito hover no botão do WhatsApp

## 📱 Como Usar Múltiplos Números

Adicione um número por linha no campo "Números de Telefone":

```
5537988347387
5511999999999
5521888888888
```

A cada visita, o plugin escolherá um número aleatoriamente. Isso é útil para:

- Distribuir atendimentos entre vários atendentes
- Balancear carga de trabalho
- Testes A/B

## 🔧 Código Técnico

### Estrutura do Plugin

```
whatsapp-setup-plugin/
├── whatsapp-setup-plugin.php   # Arquivo principal
├── admin-style.css              # Estilos do admin
├── admin-script.js              # Scripts do admin
├── readme.txt                   # Readme do WordPress
└── README.md                    # Este arquivo
```

### Hooks Disponíveis

Para desenvolvedores que desejam estender o plugin:

```php
// Modificar o link do WhatsApp
add_filter('wasp_whatsapp_link', function($link, $phone, $message) {
    // Seu código aqui
    return $link;
}, 10, 3);

// Controlar exibição do botão
add_filter('wasp_should_show_button', function($should_show) {
    // Sua lógica aqui
    return $should_show;
});

// Customizar HTML do botão
add_filter('wasp_button_html', function($html) {
    // Seu código aqui
    return $html;
});
```

## 🎯 Casos de Uso

### E-commerce
Adicione o botão em páginas de produtos para atendimento rápido.

### Landing Pages
Coloque apenas na home ou em páginas de conversão específicas.

### Sites Corporativos
Distribua atendimentos entre diferentes departamentos usando múltiplos números.

### Formulários
As máscaras de telefone funcionam automaticamente com Elementor.

## 🐛 Resolução de Problemas

### Botão não aparece

1. Verifique se está ativado em "Configurações do Botão"
2. Confirme que a página está na lista de "Páginas para Exibir"
3. Limpe o cache do site e navegador

### Número não está correto

- Formato deve ser: código do país + DDD + número
- Sem espaços, hífens ou parênteses
- Exemplo: `5537988347387`

### Máscaras não funcionam

- Certifique-se que o campo tem o name: `form_fields[telefone]`
- Funciona automaticamente com Elementor Pro

## 📄 Licença

Este plugin é licenciado sob a GPL v2 ou posterior.

```
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
```

## 👨‍💻 Desenvolvedor

**Lucas Henrique**

- GitHub: [@Lucas-Henrique-Lopes-Costa](https://github.com/Lucas-Henrique-Lopes-Costa)
- LinkedIn: [Lucas Henrique](https://linkedin.com/in/lucas-henrique)

## 🤝 Contribuindo

Contribuições são bem-vindas! Para contribuir:

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/MinhaFeature`)
3. Commit suas mudanças (`git commit -m 'Adiciona MinhaFeature'`)
4. Push para a branch (`git push origin feature/MinhaFeature`)
5. Abra um Pull Request

## 📝 Changelog

### Versão 1.0.0 (28/10/2025)

- 🎉 Lançamento inicial
- ✅ Botão flutuante do WhatsApp
- ✅ Randomização de múltiplos números
- ✅ Painel de configurações completo
- ✅ Máscaras de telefone
- ✅ Customizações de scroll
- ✅ Substituição de links vazios

## 🌟 Suporte

Se você gostou deste plugin, considere:

- ⭐ Dar uma estrela no GitHub
- 🐛 Reportar bugs ou sugerir melhorias
- 📢 Compartilhar com outros desenvolvedores

## 📞 Contato

Para suporte ou dúvidas:

- Issues no GitHub: [Criar Issue](https://github.com/Lucas-Henrique-Lopes-Costa/setup-wordpress/issues)
- Email: [seu-email@exemplo.com]

---

**Desenvolvido com ❤️ por Lucas Henrique**
