<p align="center">
<a href="https://packagist.org/packages/inphinit/inphinit"><img src="https://img.shields.io/packagist/dt/inphinit/inphinit" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/inphinit/inphinit"><img src="https://img.shields.io/packagist/v/inphinit/inphinit" alt="Última versão estável"></a>
<a href="https://packagist.org/packages/inphinit/inphinit"><img src="https://img.shields.io/packagist/l/inphinit/inphinit" alt="Licença"></a>
</p>

- [Instalação](#instalação)
- [Testes](#testes)
- [NGINX](#nginx)
- [Estrutura de pastas](#estrutura-de-pastas)
- [Criando rotas](#criando-rotas)
- [Agrupando rotas](#agrupando-rotas)
- [Padrões de rotas e URLs](#padrões-de-rotas-e-urls)
- [Documentação](#documentação)

## Instalação

Requisitos:

1. Recomendado: *PHP 8* (consulte as versões atualmente suportadas em https://www.php.net/supported-versions.php)
   - Mínimo: *PHP 5.4* (a compatibilidade retroativa é mantida para ambientes com limitações de upgrade)
   - Se precisar de um servidor completo no Windows ou macOS, considere usar WampServer, XAMPP, Laragon, EasyPHP ou AMPPS.
2. (Opcional) Extensão PHP Intl, necessária para utilizar a classe `Inphinit\Utility\Strings`.
3. (Opcional) Extensão PHP COM ou cURL, necessárias para utilizar a classe `Inphinit\Filesystem\Size`.

Após instalar o PHP, você pode instalar o Inphinit via Composer ou Git.

Para instalar via Composer, execute o comando (veja mais detalhes em https://getcomposer.org/doc/03-cli.md):

```bash
php composer.phar create-project inphinit/inphinit my-application
```

Se o Composer estiver instalado globalmente, use:

```bash
composer create-project inphinit/inphinit my-application
```

Para instalar via Git:

```bash
git clone --recurse-submodules https://github.com/inphinit/inphinit.git my-application
cd my-application
```

## Testes

Após navegar até o diretório do seu projeto, execute o seguinte comando para iniciar o [servidor web integrado do PHP](https://www.php.net/manual/en/features.commandline.webserver.php):

```bash
./run serve
```

No Windows:

```bash
run serve
```

Em seguida, acesse no seu navegador em `http://localhost:5000/`.

## nginx

Se você deseja usar o *nginx*, pode configurar seu `nginx.conf` da seguinte forma:

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
        # Substitua pelo seu endereço FPM ou FastCGI
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

> **Nota:** Para PHP-FPM (FastCGI Process Manager), use `fastcgi_pass unix:/var/run/php/php<version>-fpm.sock` (substitua `<version>` pela versão do seu PHP).

## Estrutura de Pastas

```bash
├───.htaccess                  # Arquivo de configuração do Apache para lidar com requisições e roteamento
├───index.php                  # Ponto de entrada principal; modifique constantes apenas se necessário
├───server                     # Atalho para iniciar o servidor built-in do PHP no Linux ou macOS
├───server.bat                 # Atalho para iniciar o servidor built-in do PHP no Windows
├───web.config                 # Arquivo de configuração do IIS para reescrita de URL e roteamento
├───public/                    # Contém assets estáticos e scripts PHP standalone fora do fluxo principal
│   └───.htaccess              # Configuração do Apache para servir arquivos estáticos ou scripts standalone
└───system/                    # Contém todo o código-fonte e configuração da aplicação
    ├───dev.php                # Ponto de entrada para o modo de desenvolvimento; executa antes de main.php
    ├───errors.php             # Lida com páginas de erro (ex: 404, 405) e pode renderizar arquivos estáticos ou views
    ├───main.php               # Arquivo principal de roteamento e definição de eventos para todos os ambientes
    ├───boot/                  # Configurações de Autoload e inicialização (similar ao autoload do Composer)
    │   ├───importpackages.php # Define imports adicionais de pacotes para o autoloader
    │   └───namespaces.php     # Mapeia namespaces para diretórios para o autoloader de classes
    ├───configs/               # Contém arquivos de configuração; evite commitar dados sensíveis ao controle de versão
    │   ├───app.php            # Configuração da aplicação; modifique os valores existentes conforme necessário
    │   └───debug.php          # Configuração de debug; modifique os valores existentes conforme necessário
    ├───Controllers/           # Contém classes Controller referenciadas nas definições de rota
    ├───storage/               # Usado para arquivos temporários, logs ou dados de cache
    ├───vendor/                # Dependências de terceiros e o núcleo do framework
    └───views/                 # Contém templates e arquivos de view
```

No modo de desenvolvimento, o script `system/dev.php` é sempre executado primeiro, seguido por `system/main.php`. Se ocorrer um erro (ex: 404 ou 405), o último script a ser executado será `system/errors.php`.

## Criando Rotas

Para criar uma nova rota, edite o arquivo `system/main.php`. Se você quiser que a rota esteja disponível apenas no modo de desenvolvimento, edite o arquivo `system/dev.php`.

O sistema de roteamento suporta *controllers*, [*callables*](https://www.php.net/manual/en/language.types.callable.php) e [*funções anônimas*](https://www.php.net/manual/en/functions.anonymous.php). Por exemplo:

```php
<?php

// Função anônima
$app->action('GET', '/closure', function () {
    return 'Olá "closure"!';
});

function foobar() {
    return 'Olá "function"!';
}

// Função callable
$app->action('GET', '/function', 'foobar');

// Método estático de classe callable — o autoloader inclui o arquivo da classe automaticamente
$app->action('GET', '/class-static-method', ['MyNameSpace\Foo\Bar', 'hello']);

// Método de instância de classe callable
$foo = new Sample;
$app->action('GET', '/class-method', [$foo, 'hello']);

// Você não precisa incluir o prefixo do namespace Controllers — o framework o adiciona automaticamente
$app->action('GET', '/controller', 'Boo\Bar::xyz');

/**
 * Controller do arquivo `./system/Controllers/Boo/Bar.php`:
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

O sistema de agrupamento de rotas é simples e flexível. Ele é baseado na URL ou caminho completo e suporta o *wildcard* `*`, bem como os mesmos padrões disponíveis para rotas.

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

// As rotas só serão adicionadas se o acesso for via HTTPS
$app->scope('https://*', function ($app, $params) {
    ...
});

// As rotas só serão adicionadas se o acesso for via HTTP
$app->scope('http://*', function ($app, $params) {
    ...
});

// As rotas só serão adicionadas quando o host da requisição for mysite2.org
$app->scope('*://mysite2.org/', function ($app, $params) {
    ...
});

// As rotas só serão adicionadas se você estiver acessando um subdomínio de main.org, ex: site1.main.org
$app->scope('*://*.main.org/', function ($app, $params) {
    ...
});

// Usando um padrão para capturar o subdomínio:
$app->scope('*://<subdomain>.main.org/', function ($app, $params) {
    $subdomain = $params['subdomain'];
    ...
});

// Usando um padrão para capturar parâmetros do caminho:
$app->scope('*://*/users/<id:num>/<user>', function ($app, $params) {
    $id = $params['id'];
    $username = $params['user'];
    ...
});
```

Veja mais exemplos no arquivo `system/dev.php`.

## Padrões de rotas e URLs

| Tipo      | Exemplo                                                | Descrição                                                                                             |
| --------- | ------------------------------------------------------ | ----------------------------------------------------------------------------------------------------- |
| `alnum`   | `$app->action('GET', '/baz/<video:alnum>', ...);`      | Aceita apenas parâmetros alfanuméricos; `$params` retorna `['video' => ...]`                          |
| `alpha`   | `$app->action('GET', '/foo/bar/<name:alpha>', ...);`   | Aceita apenas parâmetros alfabéticos; `$params` retorna `['name' => ...]`                             |
| `decimal` | `$app->action('GET', '/baz/<price:decimal>', ...);`    | Aceita apenas parâmetros numéricos decimais; `$params` retorna `['price' => ...]`                     |
| `num`     | `$app->action('GET', '/foo/<id:num>', ...);`           | Aceita apenas parâmetros de número inteiro; `$params` retorna `['id' => ...]`                         |
| `nospace` | `$app->action('GET', '/foo/<nospace:nospace>', ...);`  | Aceita qualquer caractere exceto espaços, como `%20` ou tabs (veja o padrão regex `\S`)               |
| `uuid`    | `$app->action('GET', '/bar/<barcode:uuid>', ...);`     | Aceita parâmetros no formato UUID; `$params` retorna `['barcode' => ...]`                             |
| `version` | `$app->action('GET', '/baz/<api:version>', ...);`      | Aceita parâmetros no formato *Semantic Versioning 2.0.0 (SemVer)*; `$params` retorna `['api' => ...]` |

Você pode adicionar ou modificar padrões existentes usando o método `$app->setPattern(name, regex)`. Exemplo:

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

- Inglês: https://inphinit.github.io/en/docs/
- Português: https://inphinit.github.io/pt/docs/
- Referência da API: https://inphinit.github.io/api/

A documentação é mantida em um [repositório GitHub](https://github.com/inphinit/inphinit.github.io) separado.
