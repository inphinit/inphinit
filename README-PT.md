<p align="center">
<a href="https://packagist.org/packages/inphinit/inphinit">
<img src="https://img.shields.io/packagist/dt/inphinit/inphinit" alt="Total Downloads">
</a>
<a href="https://packagist.org/packages/inphinit/inphinit">
<img src="https://img.shields.io/packagist/v/inphinit/inphinit" alt="Última versão estável">
</a>
<a href="https://packagist.org/packages/inphinit/inphinit">
<img src="https://img.shields.io/packagist/l/inphinit/inphinit" alt="Licença">
</a>
</p>

- [Instalação](#instalação)
- [Iniciando um servidor de desenvolvimento](#iniciando-um-servidor-de-desenvolvimento)
- [NGINX](#nginx)
- [Estrutura de pastas](#estrutura-de-pastas)
- [Criando rotas](#criando-rotas)
- [Agrupando rotas](#agrupando-rotas)
- [Padrões de rotas e URLs](#padrões-de-rotas-e-urls)
- [Documentação](#documentação)

## Instalação

Requisitos:

1. Recomendado: *PHP 8* (consulte as versões atualmente suportadas em https://www.php.net/supported-versions.php)
   - Mínimo: *PHP 5.4* (a compatibilidade retroativa é mantida para ambientes com limitações para upgrades)
   - Se precisar de um servidor completo no Windows ou macOS, considere usar WampServer, XAMPP, Laragon, EasyPHP ou AMPPS.
1. (Opcional) A extensão PHP Intl é necessária para a classe `Inphinit\Utility\Strings`.
1. (Opcional) A extensão PHP COM ou cURL é necessária para a classe `Inphinit\Filesystem\Size`.

Após instalar o PHP, você pode instalar o Inphinit usando o Composer ou o Git.

Para instalar usando o Composer, execute o comando (consulte mais detalhes em https://getcomposer.org/doc/03-cli.md):

```bash
php composer.phar create-project inphinit/inphinit:^2.1-beta.13 my-application
```

Se o Composer estiver instalado globalmente, use:

```bash
composer create-project inphinit/inphinit:^2.1-beta.13 my-application
```

Para instalar usando o Git:

```bash
git clone --recurse-submodules -b 2.1-beta.13 https://github.com/inphinit/inphinit.git my-application
cd my-application
cp .env.sample .env
```

## Iniciando um servidor de desenvolvimento

Após acessar o diretório do seu projeto, execute o comando a seguir para iniciar o [servidor web integrado do PHP](https://www.php.net/manual/en/features.commandline.webserver.php):

```bash
./run serve
```

No Windows:

```bash
run serve
```

Em seguida, acesse pelo navegador:

`http://localhost:5000/`

## nginx

Se quiser usar o *nginx*, configure seu `nginx.conf` da seguinte forma:

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
        # Substitua pelo endereço do seu FPM ou FastCGI
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

> **Observação:** Para PHP-FPM (FastCGI Process Manager), use `fastcgi_pass unix:/var/run/php/php<version>-fpm.sock` (substitua `<version>` pela sua versão do PHP).

## Estrutura de pastas

```bash
├───.env.sample                # Durante a instalação, este arquivo é copiado automaticamente para `.env`.
├───.htaccess                  # Configura o roteamento e o tratamento de erros HTTP comuns para o servidor Apache.
├───web.config                 # Configura o roteamento e o tratamento de erros HTTP comuns para o servidor IIS.
├───Caddyfile                  # Configura o roteamento e o tratamento de erros HTTP comuns para Caddy e FrankenPHP.
├───index.php                  # Ponto de entrada da aplicação; configura o carregamento automático e a estrutura de diretórios.
├───run                        # Executa os comandos CLI definidos em `system/console.php`.
├───run.bat                    # Executa os comandos CLI definidos em `system/console.php` no Windows.
├───public/                    # Contém recursos estáticos e scripts PHP independentes voltados ao público.
│   └───.htaccess              # Configura o comportamento do Apache para recursos públicos e scripts independentes.
└───system/                    # Contém o código-fonte principal da aplicação.
    ├───console.php            # Define comandos CLI personalizados.
    ├───dev.php                # Atua como `main.php` especificamente para o ambiente de desenvolvimento.
    ├───errors.php             # Gerencia a renderização de páginas de erro personalizadas geradas pelo SAPI.
    ├───main.php               # Define o roteamento da aplicação e a lógica de inicialização.
    ├───boot/                  # Contém configurações do autoloader, tipos MIME e metadados.
    │   ├───importpackages.php # Importa os pacotes Composer instalados para o autoloader da aplicação.
    │   ├───media_types.php    # Mapeia extensões de arquivos para cabeçalhos Content-Type do servidor web integrado do PHP.
    │   └───namespaces.php     # Indexa os namespaces dos pacotes Composer (gerado por importpackages.php).
    ├───configs/               # Contém arquivos de configuração.
    │   └───debug.php          # Configura a depuração, um atalho para seu editor e o assistente.
    ├───Controllers/           # Contém classes de controladores chamadas pelos manipuladores de rotas.
    ├───Commands/              # Contém classes de comandos usadas pela interface CLI integrada.
    ├───storage/               # Contém arquivos gerados pela aplicação (por exemplo, cache, logs e arquivos temporários).
    ├───vendor/                # Contém dependências de terceiros gerenciadas pelo Composer e pelo framework principal.
    └───views/                 # Contém arquivos de visualização.
```

No modo de desenvolvimento, o script `system/dev.php` é sempre executado primeiro, seguido por `system/main.php`. Se ocorrer um erro (por exemplo, 404 ou 405), o último script executado será `system/errors.php`.

## Criando rotas

Para criar uma nova rota, edite o arquivo `system/main.php`. Se quiser que a rota esteja disponível apenas no modo de desenvolvimento, edite o arquivo `system/dev.php`.

O sistema de roteamento oferece suporte a *controllers*, [*callables*](https://www.php.net/manual/en/language.types.callable.php) e [*funções anônimas*](https://www.php.net/manual/en/functions.anonymous.php). Por exemplo:

```php
<?php

// Função anônima
$app->action('GET', '/closure', function () {
    return 'Hello "closure"!';
});

function foobar() {
    return 'Hello "function"!';
}

// Função chamável
$app->action('GET', '/function', 'foobar');

// Método estático de uma classe chamável — o autoloader inclui automaticamente o arquivo da classe
$app->action('GET', '/class-static-method', ['MyNameSpace\Foo\Bar', 'hello']);

// Método de uma classe chamável
$foo = new Sample;
$app->action('GET', '/class-method', [$foo, 'hello']);

// Não é necessário incluir o prefixo do namespace Controllers — o framework o adiciona automaticamente
$app->action('GET', '/controller', 'Boo\Bar::xyz');

/**
 * Controller de `./system/Controllers/Boo/Bar.php`:
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

O sistema de agrupamento de rotas é simples e flexível. Ele é baseado na URL ou no caminho completo e oferece suporte ao caractere curinga `*`, além dos mesmos padrões disponíveis para as rotas.

```php
<?php

/*
 * As rotas serão adicionadas somente se o caminho começar com /blog/
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

// As rotas serão adicionadas somente se o acesso for feito via HTTPS
$app->scope('https://*', function ($app, $params) {
    ...
});

// As rotas serão adicionadas somente se o acesso for feito via HTTP
$app->scope('http://*', function ($app, $params) {
    ...
});

// As rotas serão adicionadas somente quando o host da requisição for mysite2.org
$app->scope('*://mysite2.org/', function ($app, $params) {
    ...
});

// As rotas serão adicionadas somente se você estiver acessando um subdomínio de main.org, por exemplo, site1.main.org
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

| Tipo      | Exemplo                                               | Descrição                                                                                             |
| --------- | ----------------------------------------------------- | ----------------------------------------------------------------------------------------------------- |
| `alnum`   | `$app->action('GET', '/baz/<video:alnum>', ...);`     | Aceita apenas parâmetros alfanuméricos; `$params` retorna `['video' => ...]`                          |
| `alpha`   | `$app->action('GET', '/foo/bar/<name:alpha>', ...);`  | Aceita apenas parâmetros alfabéticos; `$params` retorna `['name' => ...]`                             |
| `decimal` | `$app->action('GET', '/baz/<price:decimal>', ...);`   | Aceita apenas parâmetros numéricos decimais; `$params` retorna `['price' => ...]`                     |
| `num`     | `$app->action('GET', '/foo/<id:num>', ...);`          | Aceita apenas parâmetros inteiros; `$params` retorna `['id' => ...]`                                  |
| `nospace` | `$app->action('GET', '/foo/<nospace:nospace>', ...);` | Aceita qualquer caractere, exceto espaços, como `%20` ou tabulações (consulte o padrão regex `\S`)    |
| `uuid`    | `$app->action('GET', '/bar/<barcode:uuid>', ...);`    | Aceita parâmetros no formato UUID; `$params` retorna `['barcode' => ...]`                             |
| `version` | `$app->action('GET', '/baz/<api:version>', ...);`     | Aceita parâmetros no formato *Semantic Versioning 2.0.0 (SemVer)*; `$params` retorna `['api' => ...]` |

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

A documentação é mantida em um [repositório separado no GitHub](https://github.com/inphinit/inphinit.github.io).
