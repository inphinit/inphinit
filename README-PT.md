* [Instalação](#Instalação)
* [Testes](#Testes)
* [NGINX](#NGINX)
* [Estrutura de pastas](#Estrutura-de-pastas)
* [Criando rotas](#Criando-rotas)
* [Agrupando rotas](#Agrupando-rotas)
* [Padrões de Rotas e URLs](#Padrões-de-Rotas-e-URLs)
* [Documentação](#Documentação)

## Instalação

1. Versão do PHP com suporte: [https://www.php.net/supported-versions.php](https://www.php.net/supported-versions.php)
    * Embora recomendemos atualizar o PHP para a melhor experiência, a compatibilidade retroativa é mantida até PHP 5.4, para usuários com limitações de atualização.
    * Para um servidor completo para Windows ou macOS, experimente: Wamp, Xampp, Laragon, EasyPHP, AMPPS, etc.   
2. Multibyte String (também GD) (opcional, usado apenas na classe `Inphinit\Utility\Strings`)
3. libiconv (opcional, usado apenas na classe `Inphinit\Utility\Strings`)
4. COM ou cURL (opcional, usado apenas na classe `Inphinit\Filesystem\Size`)

Após instalar o PHP, você pode instalar o Inphinit usando Composer ou Git.

Se usar o composer, execute o comando (mais detalhes em [https://getcomposer.org/doc/03-cli.md](https://getcomposer.org/doc/03-cli.md)):

```bash
php composer.phar create-project inphinit/inphinit minha-aplicacao
```

Se usar o composer global, execute:

```bash
composer create-project inphinit/inphinit minha-aplicacao
```

Instalando com Git:

```bash
git clone --recurse-submodules https://github.com/inphinit/inphinit.git minha-aplicacao
cd minha-aplicacao
```

## Testes

Após navegar até a pasta, você deve executar o seguinte comando, se quiser usar o [servidor web embutido do PHP](https://www.php.net/manual/en/features.commandline.webserver.php):

```bash
php -S localhost:5000 -t public index.php
```

E acesse no seu navegador `http://localhost:5000/`

## NGINX

Se quiser experimentar com um servidor web como o NGINX, você pode usar o exemplo abaixo para configurar seu `nginx.conf`:

```nginx
location / {
    root /home/foo/bar/my-application;

    # Redirect page errors to route system
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
        # Replace by your FPM or FastCGI
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

> **Nota:** Para FPM use `fastcgi_pass unix:/var/run/php/php<versao>-fpm.sock` (substitua `<versao>` pela versão do PHP no seu servidor)

## Estrutura de pastas

```bash
├───.htaccess                  # Configuração do Apache para a aplicação
├───index.php                  # Modifique apenas os valores das constantes existentes, e só se necessário
├───server                     # Atalho para iniciar o servidor web embutido no Linux e macOS
├───server.bat                 # Atalho para iniciar o servidor web embutido no Windows
├───web.config                 # Configuração do IIS para a aplicação
├───public/                    # Pode conter arquivos estáticos ou scripts PHP independentes da aplicação principal
│   └───.htaccess              # Configuração do Apache para scripts e arquivos adicionais
└───system/                    # Contém o código da aplicação    
    ├───dev.php                # Semelhante ao main.php, mas usado apenas em modo de desenvolvimento
    ├───errors.php             # Define comportamento de páginas de erro (ex.: 404 ou 405), permitindo exibir arquivos estáticos ou views
    ├───main.php               # Arquivo principal para definir rotas e eventos, usado em desenvolvimento e produção
    ├───boot/                  # Configurações para o inphinit_autoload, semelhante ao composer_autoload
    │   ├───importpackages.php #
    │   └───namespaces.php     #
    ├───configs/               # Contém arquivos de configuração variados, recomenda-se não versionar essa pasta
    │   ├───app.php            # Não adicione novas chaves, apenas altere os valores existentes se necessário
    │   └───debug.php          # Não adicione novas chaves, apenas altere os valores existentes se necessário
    ├───Controllers/           # Deve conter as classes de controladores usadas nas rotas    
    ├───storage/               #
    ├───vendor/                # Contém pacotes de terceiros e o framework
    └───views/                 # Deve conter suas views
```

Em modo de desenvolvimento, o script `system/dev.php` será executado primeiro, seguido de `system/main.php`, e em caso de erro (como 404 ou 405), o último será `system/errors.php`.

## Criando rotas

Para criar uma nova rota, edite o arquivo `system/main.php`. Se quiser que a rota só esteja disponível em modo de desenvolvimento, edite `system/dev.php`.

O sistema de rotas suporta _controllers_, [_callables_](https://www.php.net/manual/pt_BR/language.types.callable.php) e [_funções anônimas_](https://www.php.net/manual/pt_BR/functions.anonymous.php), por exemplo:

```php
<?php

// Usando função anonima
$app->action('GET', '/closure', function () {
    return 'Hello "closure"!';
});

function foobar() {
    return 'Hello "function"!';
}

// Usando função
$app->action('GET', '/function', 'foobar');

// Usando método de uma classe (Nota: o autoload irá incluir o arquivo, não é necessário usar `include` ou `require`)
$app->action('GET', '/class-static-method', ['MyNameSpace\Foo\Bar', 'hello']);

// Método de uma classe instanciada
$foo = new Sample;
$app->action('GET', '/class-method', [$foo, 'hello']);


// Usando método de um controller.
// Nota: Não adicione o prefixo Controllers\, o próprio framework irá se encarregar disso
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

O agrupamento de rotas agora é baseado na URL completa. Você pode usar o caractere curinga `*` e os mesmos padrões disponíveis para rotas, por exemplo:

```php
<?php

/*
 * Rotas acessíveis apenas se o caminho começar com /blog/
 * 
 * Exemplos:
 * 
 * http://localhost:5000/blog/
 * http://localhost:5000/blog/post
 * http://localhost:5000/blog/search
 */
$app->scope('*://*/blog/', function ($app, $params) {
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

// Usando o padrão para obter o subdomínio:
$app->scope('*://<subdomain>.main.org/', function ($app, $params) {
    $subdomain = $params['subdomain'];
    ...
});

// Usando o padrão para obter o caminho:
$app->scope('*://*/users/<id:num>/<user>', function ($app, $params) {
    $id = $params['id'];
    $username = $params['user'];
    ...
});
```

Veja mais exemplos no arquivo `system/dev.php`.

## Padrões de Rotas e URLs

Tipo | Exemplo | Descrição
---|---|---
`alnum` | `$app->action('GET', '/baz/<video:alnum>', ...);`       | Aceita apenas parâmetros com formato alfanumérico e `$params` retorna `['video' => ...]`
`alpha` | `$app->action('GET', '/foo/bar/<name:alpha>', ...);`    | Aceita apenas parâmetros com formato alfanumérico e `$params` retorna `['name' => ...]`
`decimal` | `$app->action('GET', '/baz/<price:decimal>', ...);`   | Aceita apenas parâmetros com formato decimal e `$params` retorna `['price' => ...]`
`num` | `$app->action('GET', '/foo/<id:num>', ...);`              | Aceita apenas parâmetros com formato inteiro e `$params` retorna `['id' => ...]`
`nospace` | `$app->action('GET', '/foo/<nospace:nospace>', ...);` | Aceita qualquer caractere, exceto espaços, como espaços em branco (`%20`), tabulações (`%0A`) e outros (consulte `\S` em expressões regulares)
`uuid` | `$app->action('GET', '/bar/<barcode:alnum>', ...);`      | Aceita apenas parâmetros com formato uuid e `$params` retorna `['barcode' => ...]`
`version` | `$app->action('GET', '/baz/<api:version>', ...);`     | Aceita apenas parâmetros com formato _Semantic Versioning 2.0.0 (semversion)_ e `$params` retorna `['api' => ...]`

Você pode criar ou alterar padrões com `$app->setPattern(nome, regex)`, exemplo:

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

$app->setPattern('locale', '[a-z]{1,8}(\-[A-Z\d]{1,8})?'); // examplos: en, en-US, en-GB, pt-BR, pt
$app->setPattern('customid', '[A-Z]\d+'); // examplos: A0001, B002, J007
```

Alterando um padrão existente:

```php
<?php

// Replace semversion by <major>.<minor>.<revision>.<build>
$app->setPattern('version', '\d+\.\d+.\d+.\d+');

// Replace semversion by <major>.<minor> (maybe it's interesting for web APIs)
$app->setPattern('version', '\d+\.\d+');
```

## Documentação

* Português: (em breve)
* API: [https://inphinit.github.io/api/](https://inphinit.github.io/api/)

A documentação é mantida neste [repositório no GitHub](https://github.com/inphinit/inphinit.github.io).
