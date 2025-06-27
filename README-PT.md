* [Instalação](#Instalação)
* [Testes](#testes)
* [NGINX](#NGINX)
* [Estrutura de pastas](#Estrutura-de-pastas)
* [Criando rotas](#Criando-rotas)
* [Agrupando rotas](#Agrupando-rotas)
* [Padrões de rota e URLs](#Padrões-de-rota-e-URLs)
* [Documentação](#Documentação)

## Instalação

Requisitos:

1. Versão PHP atualmente suportada: https://www.php.net/supported-versions.php.
   * Mínimo _PHP 5.4_ (compatibilidade retroativa é mantida para usuários com limitações de atualização).
   * Se você precisa de um servidor completo para Windows ou macOS, tente: Wamp, Xampp, Laragon, EasyPHP, AMPPS, etc.
1. (Opcional) Extensão PHP Intl para usar a classe `Inphinit\Utility\Strings`.
1. (Opcional) Extensão PHP COM ou extensão PHP cURL para usar a classe `Inphinit\Filesystem\Size`.

Após instalar o PHP, você pode instalar o Inphinit usando Composer ou Git.

Se você usa o Composer, execute o comando (mais detalhes em https://getcomposer.org/doc/03-cli.md):

```bash
php composer.phar create-project inphinit/inphinit meu-aplicativo
````

Se você usa o Composer globalmente, execute o comando:

```bash
composer create-project inphinit/inphinit meu-aplicativo
```

Instalando usando Git:

```bash
git clone --recurse-submodules https://github.com/inphinit/inphinit.git meu-aplicativo
cd meu-aplicativo
```

## Testes

Após navegar para a pasta, você deve executar o seguinte comando, se quiser usar o [servidor web embutido do PHP](https://www.php.net/manual/en/features.commandline.webserver.php):

```bash
php -S localhost:5000 -t public index.php
```

E acesse em seu navegador `http://localhost:5000/`

## NGINX

Se você quiser experimentar com um servidor web como o NGINX, você pode usar o seguinte exemplo para configurar seu `nginx.conf`:

```none
location / {
    root /home/foo/bar/meu-aplicativo;

    # Redirecionar erros de página para o sistema de rotas
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

> **Nota:** Para FPM, use `fastcgi_pass unix:/var/run/php/php<version>-fpm.sock` (substitua `<version>` pela versão do PHP em seu servidor)

## Estrutura de pastas

```bash
├───.htaccess                  # Configuração do servidor web Apache para a aplicação
├───index.php                  # Modifique apenas os valores das constantes existentes, e somente se necessário
├───server                     # Atalho para iniciar o servidor web embutido no Linux e macOS
├───server.bat                 # Atalho para iniciar o servidor web embutido no Windows
├───web.config                 # Configuração do servidor web IIS para a aplicação
├───public/                    # Esta pasta pode conter arquivos estáticos ou scripts PHP que rodam independentemente da aplicação principal
│   └───.htaccess              # Configuração do servidor web Apache para scripts PHP adicionais e arquivos estáticos
└───system/                    # Pasta contendo o código da sua aplicação
    ├───dev.php                # Semelhante ao main.php, mas usado apenas em modo de desenvolvimento
    ├───errors.php             # Deve definir o comportamento da página de erro (ex: erros 404 ou 405), permitindo que arquivos estáticos ou views sejam servidos
    ├───main.php               # O arquivo principal para definir rotas e eventos, disponível tanto em modo de desenvolvimento quanto de produção
    ├───boot/                  # Contém configurações para inphinit_autoload, similar ao composer_autoload
    │   ├───importpackages.php #
    │   └───namespaces.php     #
    ├───configs/               # Contém arquivos de configuração variados, é recomendado que você não version esta pasta
    │   ├───app.php            # Não adicione novas chaves, apenas altere os valores das existentes se necessário
    │   └───debug.php          # Não adicione novas chaves, apenas altere os valores das existentes se necessário
    ├───Controllers/           # Deve conter as classes que serão controladores usados nas rotas
    ├───storage/               #
    ├───vendor/                # Contém pacotes de terceiros e o framework
    └───views/                 # Deve conter suas views
```

No modo de desenvolvimento, o script `system/dev.php` será sempre executado primeiro, então `system/main.php` será executado, e se ocorrer um erro, como 404 ou 405, o último script a ser executado será `system/errors.php`

## Criando rotas

Para criar uma nova rota, edite o arquivo `system/main.php`. Se você quiser que a rota esteja disponível apenas no modo de desenvolvimento, edite o arquivo `system/dev.php`.

O sistema de rotas suporta *controladores*, [*callables*](https://www.php.net/manual/en/language.types.callable.php) e [*funções anônimas*](https://www.php.net/manual/en/functions.anonymous.php), exemplos:

```php
<?php

// funções anônimas
$app->action('GET', '/closure', function () {
    return 'Hello "closure"!';
});

function foobar() {
    return 'Hello "function"!';
}

// função callable
$app->action('GET', '/function', 'foobar');

// método estático de classe callable (Nota: autoload incluirá o arquivo)
$app->action('GET', '/class-static-method', ['MyNameSpace\Foo\Bar', 'hello']);

// método de classe callable
$foo = new Sample;
$app->action('GET', '/class-method', [$foo, 'hello']);


// não adicione o prefixo Controllers, o próprio framework o adicionará
$app->action('GET', '/controller', 'Boo\Bar::xyz');

/**
 * Controlador de `./system/Controllers/Boo/Bar.php`:
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

O sistema de agrupamento de rotas agora é muito mais simples, é baseado na URL completa ou no caminho, e você pode usar o caractere curinga `*` e também os mesmos padrões disponíveis para rotas, exemplos:

```php
<?php

/*
 * As rotas só serão adicionadas se o caminho começar com /blog/
 * * Exemplos:
 * * http://localhost:5000/blog/
 * http://localhost:5000/blog/post
 * http://localhost:5000/blog/search
 */
$app->scope('/blog/', function ($app, $params) {
    $app->action('GET', '/', function () { ... });
    $app->action('POST', '/post', function () { ... });
    $app->action('GET', '/search', function () { ... });
});

// As rotas só serão adicionadas se você estiver acessando via HTTPS
$app->scope('https://*', function ($app, $params) {
    ...
});

// As rotas só serão adicionadas se você estiver acessando via HTTP
$app->scope('http://*', function ($app, $params) {
    ...
});

// As rotas só serão adicionadas se você estiver acessando o host mysite2.org
$app->scope('*://mysite2.org/', function ($app, $params) {
    ...
});

// As rotas só serão adicionadas se você estiver acessando um subdomínio de main.org, como: site1.main.org
$app->scope('*://*.main.org/', function ($app, $params) {
    ...
});

// Usando padrão para obter o subdomínio:
$app->scope('*://<subdomain>.main.org/', function ($app, $params) {
    $subdomain = $params['subdomain'];
    ...
});

// Usando padrão para obter o caminho:
$app->scope('*://*/users/<id:num>/<user>', function ($app, $params) {
    $id = $params['id'];
    $username = $params['user'];
    ...
});
```

Veja mais exemplos no arquivo `system/dev.php`

## Padrões de rota e URLs

Tipo | Exemplo | Descrição
---|---|---
`alnum` | `$app->action('GET', '/baz/<video:alnum>', ...);`       | Aceita apenas parâmetros com formato alfanumérico e `$params` retorna `['video' => ...]`
`alpha` | `$app->action('GET', '/foo/bar/<name:alpha>', ...);`    | Aceita apenas parâmetros com formato alfabético e `$params` retorna `['name' => ...]`
`decimal` | `$app->action('GET', '/baz/<price:decimal>', ...);`   | Aceita apenas parâmetros com formato decimal e `$params` retorna `['price' => ...]`
`num` | `$app->action('GET', '/foo/<id:num>', ...);`              | Aceita apenas parâmetros com formato inteiro e `$params` retorna `['id' => ...]`
`nospace` | `$app->action('GET', '/foo/<nospace:nospace>', ...);` | Aceita quaisquer caracteres, exceto espaços, como espaços em branco (`%20`), tabulações (`%0A`) e outros (veja sobre `\S` em regex)
`uuid` | `$app->action('GET', '/bar/<barcode:alnum>', ...);`      | Aceita apenas parâmetros com formato UUID e `$params` retorna `['barcode' => ...]`
`version` | `$app->action('GET', '/baz/<api:version>', ...);`     | Aceita apenas parâmetros com formato *Semantic Versioning 2.0.0 (semversion)* e `$params` retorna `['api' => ...]`

É possível adicionar ou modificar padrões existentes usando o método `$app->setPattern(nome, regex)`. Criando um novo padrão:

```php
<?php
use Inphinit\Viewing\View;

$app->action('GET', '/about/<lang:locale>', function ($params) {
    $lang = $params['lang'];
    ...
});

$app->action('GET', '/product/<id:customid>', function ($params) {
    $lang = $params['id'];
    ...
});

$app->setPattern('locale', '[a-z]{1,8}(\-[A-Z\d]{1,8})?'); // exemplos: en, en-US, en-GB, pt-BR, pt
$app->setPattern('customid', '[A-Z]\d+'); // exemplos: A0001, B002, J007
```

Modificando um padrão existente:

```php
<?php

// Substitui semversion por <major>.<minor>.<revision>.<build>
$app->setPattern('version', '\d+\.\d+.\d+.\d+');

// Substitui semversion por <major>.<minor> (talvez seja interessante para APIs web)
$app->setPattern('version', '\d+\.\d+');
```

## Documentação

* Inglês: https://inphinit.github.io/en/docs/
* Português: (em breve)
* API: https://inphinit.github.io/api/

A documentação é mantida em seu próprio [repositório GitHub](https://github.com/inphinit/inphinit.github.io).
