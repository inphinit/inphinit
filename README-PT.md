* [Instalação](#instalação)
* [Testes](#testes)
* [NGINX](#nginx)
* [Estrutura de pastas](#estrutura-de-pastas)
* [Criando rotas](#criando-rotas)
* [Agrupando rotas](#agrupando-rotas)
* [Padrões de rotas e URLs](#padrões-de-rotas-e-urls)
* [Documentação](#documentação)

## Instalação

Requisitos:

1. Veja as versões de PHP atualmente suportadas: [https://www.php.net/supported-versions.php](https://www.php.net/supported-versions.php)

   * Mínimo: *PHP 5.4* (a compatibilidade retroativa é mantida para usuários com limitações de atualização).
   * Se precisar de um servidor completo para Windows ou macOS, você pode usar WampServer, XAMPP, Laragon, EasyPHP ou AMPPS.
2. (Opcional) Extensão Intl do PHP para usar a classe `Inphinit\Utility\Strings`.
3. (Opcional) Extensão COM ou cURL do PHP para usar a classe `Inphinit\Filesystem\Size`.

Após instalar o PHP, instale o Inphinit via Composer ou Git.

Se você usa o Composer localmente, execute o comando (mais detalhes em [https://getcomposer.org/doc/03-cli.md](https://getcomposer.org/doc/03-cli.md)):

```bash
php composer.phar create-project inphinit/inphinit my-application
```

Se você usa o Composer globalmente, execute:

```bash
composer create-project inphinit/inphinit my-application
```

Instalando via Git:

```bash
git clone --recurse-submodules https://github.com/inphinit/inphinit.git my-application
cd my-application
```

## Testes

Após navegar até o diretório do seu projeto, execute o comando abaixo se quiser usar o [servidor web embutido do PHP](https://www.php.net/manual/en/features.commandline.webserver.php):

```bash
php -S localhost:5000 -t public index.php
```

E acesse no navegador: `http://localhost:5000/`

## NGINX

Se quiser usar um servidor web como o NGINX, você pode usar o exemplo abaixo para configurar seu `nginx.conf`:

```none
location / {
    root /home/foo/bar/my-application;

    # Redireciona erros de página para o sistema de rotas
    error_page 403 /index.php/RESERVED.INPHINIT-403.html;
    error_page 500 /index.php/RESERVED.INPHINIT-500.html;

    try_files /public$uri /index.php?$query_string;

    location = / {
        try_files $uri /index.php?$query_string;
    }

    location ~ /\. {
        try_files /index.php$uri /index.php?$query_string;
    }

    location ~ \.php$ {
        # Substitua pelo seu FPM ou FastCGI
        fastcgi_pass 127.0.0.1:9000;

        fastcgi_index index.php;
        include fastcgi_params;

        set $teeny_suffix "";

        if ($uri != "/index.php") {
            set $teeny_suffix "/public";
        }

        fastcgi_param SCRIPT_FILENAME $realpath_root$teeny_suffix$fastcgi_script_name;
    }
}
```

> **Nota:** Para PHP-FPM (FastCGI Process Manager) use `fastcgi_pass unix:/var/run/php/php<versao>-fpm.sock` (substitua `<versao>` pela versão do PHP no seu servidor)

## Estrutura de pastas

```bash
├───.htaccess                  # Arquivo de configuração do Apache para tratamento de requisições e roteamento
├───index.php                  # Ponto de entrada principal; modifique apenas valores das constantes se necessário
├───server                     # Atalho para iniciar o servidor embutido do PHP no Linux ou macOS
├───server.bat                 # Atalho para iniciar o servidor embutido do PHP no Windows
├───web.config                 # Arquivo de configuração do IIS para reescrita de URLs e roteamento
├───public/                    # Contém arquivos estáticos e scripts PHP independentes do fluxo principal do app
│   └───.htaccess              # Configuração do Apache para servir arquivos estáticos ou scripts independentes
└───system/                    # Contém todo o código-fonte e configurações da aplicação
    ├───dev.php                # Ponto de entrada no modo de desenvolvimento; executado antes do main.php
    ├───errors.php             # Trata páginas de erro (ex.: 404, 405) e pode renderizar arquivos estáticos ou views
    ├───main.php               # Arquivo principal de definição de rotas e eventos para todos os ambientes
    ├───boot/                  # Configurações de autoload e inicialização (semelhante ao autoload do Composer)
    │   ├───importpackages.php # Define pacotes adicionais para o autoloader
    │   └───namespaces.php     # Mapeia namespaces para diretórios de classes
    ├───configs/               # Contém arquivos de configuração; evite versionar dados sensíveis
    │   ├───app.php            # Configuração da aplicação; modifique apenas os valores necessários
    │   └───debug.php          # Configuração de depuração; modifique apenas os valores necessários
    ├───Controllers/           # Contém as classes de controladores referenciadas nas definições de rotas
    ├───storage/               # Usado para arquivos temporários, logs ou cache
    ├───vendor/                # Dependências de terceiros e núcleo do framework
    └───views/                 # Contém templates e arquivos de visualização
```

No modo de desenvolvimento, o script `system/dev.php` sempre será executado primeiro, depois `system/main.php`, e caso ocorra um erro (como 404 ou 405), o último script executado será `system/errors.php`.

## Criando rotas

Para criar uma nova rota, edite o arquivo `system/main.php`.
Se quiser que a rota esteja disponível apenas em modo de desenvolvimento, edite o arquivo `system/dev.php`.

O sistema de rotas suporta *controladores*, [*callables*](https://www.php.net/manual/en/language.types.callable.php) e [*funções anônimas*](https://www.php.net/manual/en/functions.anonymous.php), exemplos:

```php
<?php

// Funções anônimas
$app->action('GET', '/closure', function () {
    return 'Olá "closure"!';
});

function foobar() {
    return 'Olá "function"!';
}

// Função callable
$app->action('GET', '/function', 'foobar');

// Método estático de classe callable — O autoloader inclui o arquivo automaticamente
$app->action('GET', '/class-static-method', ['MyNameSpace\Foo\Bar', 'hello']);

// Método de instância callable
$foo = new Sample;
$app->action('GET', '/class-method', [$foo, 'hello']);

// Não inclua o prefixo de namespace Controllers — o framework o adiciona automaticamente
$app->action('GET', '/controller', 'Boo\Bar::xyz');

/**
 * Controlador em `./system/Controllers/Boo/Bar.php`:
 *
 * <?php
 * namespace Controllers\Boo;
 *
 * class Bar {
 *    public function xyz() {
 *        ...
 *    }
 * }
 */
```

## Agrupando rotas

O sistema de agrupamento de rotas agora é bem mais simples — ele é baseado no caminho ou URL completo, e permite usar o caractere curinga `*` e os mesmos padrões disponíveis para rotas, por exemplo:

```php
<?php

/*
 * As rotas só serão adicionadas se o caminho começar com /blog/
 * 
 * Exemplos:
 * 
 * http://localhost:5000/blog/
 * http://localhost:5000/blog/post
 * http://localhost:5000/blog/search
 */
$app->scope('/blog/', function ($app, $params) {
    $app->action('GET', '/', function () { ... });
    $app->action('POST', '/post', function () { ... });
    $app->action('GET', '/search', function () { ... });
});

// Rotas adicionadas apenas se o acesso for via HTTPS
$app->scope('https://*', function ($app, $params) {
    ...
});

// Rotas adicionadas apenas se o acesso for via HTTP
$app->scope('http://*', function ($app, $params) {
    ...
});

// Rotas adicionadas apenas se o host for mysite2.org
$app->scope('*://mysite2.org/', function ($app, $params) {
    ...
});

// Rotas adicionadas apenas se for um subdomínio de main.org, ex.: site1.main.org
$app->scope('*://*.main.org/', function ($app, $params) {
    ...
});

// Usando padrão para capturar o subdomínio:
$app->scope('*://<subdomain>.main.org/', function ($app, $params) {
    $subdomain = $params['subdomain'];
    ...
});

// Usando padrão para capturar o caminho:
$app->scope('*://*/users/<id:num>/<user>', function ($app, $params) {
    $id = $params['id'];
    $username = $params['user'];
    ...
});
```

Veja mais exemplos no arquivo `system/dev.php`.

## Padrões de rotas e URLs

Tipo | Exemplo | Descrição
--- | --- | ---
`alnum` | `$app->action('GET', '/baz/<video:alnum>', ...);` | Aceita apenas parâmetros alfanuméricos; `$params` retorna `['video' => ...]`
`alpha` | `$app->action('GET', '/foo/bar/<name:alpha>', ...);` | Aceita apenas letras; `$params` retorna `['name' => ...]`
`decimal` | `$app->action('GET', '/baz/<price:decimal>', ...);` | Aceita apenas números decimais; `$params` retorna `['price' => ...]`
`num` | `$app->action('GET', '/foo/<id:num>', ...);` | Aceita apenas números inteiros; `$params` retorna `['id' => ...]`
`nospace` | `$app->action('GET', '/foo/<nospace:nospace>', ...);` | Aceita qualquer caractere exceto espaços, como espaços (`%20`), tabs (`%0A`) e outros (veja `\S` em regex)
`uuid` | `$app->action('GET', '/bar/<barcode:uuid>', ...);` | Aceita apenas formato UUID; `$params` retorna `['barcode' => ...]`
`version` | `$app->action('GET', '/baz/<api:version>', ...);` | Aceita apenas formato *SemVer (Semantic Versioning 2.0.0)*; `$params` retorna `['api' => ...]`

É possível adicionar ou modificar padrões existentes com o método `$app->setPattern(nome, regex)`. Criando um novo padrão:

```php
<?php
use Inphinit\Viewing\View;

$app->action('GET', '/about/<lang:locale>', function ($params) {
    $lang = $params['lang'];
    ...
});

$app->action('GET', '/product/<id:customid>', function ($params) {
    $id = $params['id'];
    ...
});

$app->setPattern('locale', '[a-z]{1,8}(\-[A-Z\d]{1,8})?'); // exemplos: en, en-US, en-GB, pt-BR, pt
$app->setPattern('customid', '[A-Z]\d+'); // exemplos: A0001, B002, J007
```

Modificando um padrão existente:

```php
<?php

// Substitui SemVer por <major>.<minor>.<revision>.<build>
$app->setPattern('version', '\d+\.\d+\.\d+\.\d+');

// Substitui SemVer por <major>.<minor> (útil para APIs web)
$app->setPattern('version', '\d+\.\d+');
```

## Documentação

* Inglês: [https://inphinit.github.io/en/docs/](https://inphinit.github.io/en/docs/)
* Português: (Em breve)
* API: [https://inphinit.github.io/api/](https://inphinit.github.io/api/)

A documentação é mantida em seu próprio [repositório GitHub](https://github.com/inphinit/inphinit.github.io).
