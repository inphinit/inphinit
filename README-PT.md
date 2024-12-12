- [Installing](#instalando)
- [Testando](#testando)
- [NGINX](#nginx)
- [Estrutura de pastas](#estrutura-de-pastas)
- [Criando rotas](#criando-rotas)
- [Agrupando rotas](#agrupando-rotas)
- [Padrões de rotas e URL](#padrões-de-rotas-e-url)

## Decisões e o que vem a seguir

O objetivo deste framework sempre foi ser o mais eficiente possível, porém algo que sempre me preocupou foram os problemas de depuração, apesar de existirem diversas ferramentas, sempre busquei algo simples, mas claro que mesmo quem está trabalhando pela primeira vez vê o erro, então neste último ano tomei as seguintes decisões:

- No modo de desenvolvimento você deve trabalhar rigorosamente, checando qualquer detalhe
- Mudar a forma como as rotas funcionam, para torná-las mais rápidas e também conseguir prever falhas, quando usadas no desenvolvimento
- Alguns erros de digitação podem fazer com que certos recursos do PHP não respondam em tempo hábil, como o autoload, então o modo de desenvolvedor pré-carregará tudo o que você precisa antes que qualquer script interrompa o processo, permitindo que a depuração localize e exiba exatamente em qual linha o erro está.

Todas essas decisões estão embutidas no framework, algumas das quais já foram adicionadas à _versão 1.x_, para facilitar a portabilidade do projeto para a versão futura do framework.

Todas as APIs internas principais já são suportadas pela _versão 2.x_, algumas foram completamente reescritas, com foco em simplicidade e desempenho, então se você estiver migrando da _1.x_ é provável que precise reescrever algumas coisas. Felizmente, a maioria das classes é muito mais simples de usar.

## O que já alcançamos

Se você estiver usando a _versão 0.5_ e ainda não puder migrar para a _versão 2.x_, é altamente recomendável que você migre para a _versão 1.x_.

A _versão 0.5_ já tinha um desempenho excelente, mas ainda foi possível trazer alguns recursos de desempenho da _versão 2.x_ para a _versão 1.x_. Na _versão 2.x_ está um pouco melhor, então aqui está um exemplo dos testes, com o modo de desenvolvimento desativado:

Descrição                                                           | v0.5.x                        | v1.x                          | v2.x
---                                                                 | ---                           | ---                           | --- 
Tempo gasto para testes                                             | 0.528 segundos                | 0.429 segundos                | 0.391 segundos
Solicitações por segundo (média)                                    | 1892.46 [#/seg]               | 2330.74 [#/seg]               | 2557.07 [#/seg]
Tempo por solicitação (média)                                       | 5.284 [ms]                    | 4.290 [ms]                    | 3.911 [ms]
Tempo por solicitação (média, em todas as solicitações simultâneas) | 0.528 [ms]                    | 0.429 [ms]                    | 0.391 [ms]
Taxa de transferência                                               | 373.32 [Kbytes/seg] recebidos | 459.77 [Kbytes/sec] recebidos | 504.42 [Kbytes/seg] recebidos

Além do tempo de execução melhorado, nota-se que a _versão 2.x_ conseguiu processar em média 220 requisições a mais por segundo do que a _versão 1.x_, e comparado à _0.5.x_, conseguiu processar 600 requisições a mais por segundo.

## Sobre a documentação

Algo que vou mudar é a documentação, o Github Wiki funcionou por um tempo, mas notei alguns problemas:

- O menu gerado no github wiki não é tão intuitivo e notei que até mesmo alguns programadores experientes não conseguiam navegar por lá
- Organizar o conteúdo não foi tão fácil quanto eu queria, muitas coisas são manuais, o que levou muito tempo para editar algumas coisas
- O Github Desktop entra em conflito com repositórios do tipo wiki, é um [bug antigo](https://github.com/desktop/desktop/issues/3839#issue-290340050)

A documentação estará disponível em breve, inicialmente em inglês e português.

## Instalando

> **Nota:** Para instalar a _versão 1.x_ acesse: https://github.com/inphinit/inphinit/tree/1.x

É altamente recomendado migrar para a _versão 2.x_ para manter o suporte para versões futuras do PHP. Para instalá-lo você deve ter pelo menos o _PHP 5.4_, mas é recomendado que você use o _PHP 8_ devido a problemas de suporte ao PHP, leia:

- https://www.php.net/supported-versions.php
- https://www.php.net/eol.php

Após instalar o PHP, você pode instalar o Inphinit usando o Composer ou usando o Git.

Se você usa o composer, execute o comando (mais detalhes em https://getcomposer.org/doc/03-cli.md):

```bash
php composer.phar create-project inphinit/inphinit my-application
```

Se você usar composer global, execute o comando:

```bash
composer create-project inphinit/inphinit my-application
```

Instalando usando Git:

```bash
git clone --recurse-submodules https://github.com/inphinit/inphinit.git my-application
cd my-application
```

## Testando

After navigating to the folder you must execute the following command, if you want to use [PHP built-in web server](https://www.php.net/manual/en/features.commandline.webserver.php):

```bash
php -S localhost:5000 -t public index.php
```

E acesse no seu navegador `http://localhost:5000/`

## NGINX

Se você quiser experimentar um servidor web como o NGINX, você pode usar o seguinte exemplo para configurar seu `nginx.conf`:

```none
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

> **Note:** Para FPM use `fastcgi_pass unix:/var/run/php/php<version>-fpm.sock` (troque `<version>` pela versão do PHP version no seu servidor)

## Estrutura de pastas

```bash
├───.htaccess        # Configuração do servidor web Apache
├───index.php        # Altere apenas os valores das constantes e somente se necessário
├───server           # Atalho para iniciar o servidor web integrado no Linux e macOS
├───server.bat       # Atalho para iniciar o servidor web integrado no Windows
├───web.config       # Configuração do servidor web IIS
├───public/          # Nesta pasta você poderá colocar arquivos estáticos ou scripts PHP que serão independentes
└───system/          # Pasta contendo seu aplicativo
    ├───boot/        # Contém configurações para inphinit_autoload, semelhante ao composer_autoload
    ├───configs/     # Contém arquivos de configuração variados, é recomendado que você não versione esta pasta
    │   └───app.php  # Não adicione novas chaves, apenas altere os valores da existentes, se necessário
    ├───controllers/ # Deve conter as classes que serão os controladores utilizados nas rotas
    ├───vendor/      # Contém pacotes de terceiros e a estrutura
    ├───views/       # Deve conter suas opiniões
    ├───dev.php      # Tem a mesma finalidade do script "main.php", mas só funcionará em modo de desenvolvimento
    ├───errors.php   # Deve conter configurações de página de erro, como quando ocorre um erro 404 ou 405, você pode chamar arquivos estáticos ou use views
    └───main.php     # Este é o arquivo principal para rotas e eventos, estará disponível em modo de desenvolvimento e modo de produção
```

No modo de desenvolvimento, o script `system/dev.php` sempre será executado primeiro, depois `system/main.php` será executado, e se ocorrer um erro, como 404 ou 405, o último script a ser executado será `system/errors.php`

## Criando rotas

Para criar uma nova rota, edite o arquivo `system/main.php`, se quiser que a rota fique disponível apenas no modo de desenvolvimento, edite o arquivo `system/dev.php`.

O sistema de rotas suporta _controllers_, [_callables_](https://www.php.net/manual/en/language.types.callable.php) e [_anonymous functions_](https://www.php.net/manual/en/functions.anonymous.php), exemplos:

```php
<?php

//função anônima
$app->action('GET', '/closure', function () {
    return 'Hello "closure"!';
});

function foobar() {
    return 'Hello "function"!';
}

// função
$app->action('GET', '/function', 'foobar');

// método estático de uma classe (Nota: o autoload irá incluir o arquivo)
$app->action('GET', '/class-static-method', ['MyNameSpace\Foo\Bar', 'hello']);

// método de uma classe instanciada
$foo = new Sample;
$app->action('GET', '/class-method', [$foo, 'hello']);


// Não adicione o prefixo Controller, o próprio framework irá adiciona-lo
$app->action('GET', '/controller', 'Boo\Bar::xyz');

/**
 * Controller deverá estar localizado em `./system/controllers/Boo/Bar.php`:
 *
 * <?php
 * namespace Controller\Boo;
 *
 * class Bar {
 *    public function xyz() {
 *        ...
 *    }
 * }
 */
```

## Agrupando rotas

O sistema de agrupamento de rotas agora é muito mais simples, ele é baseado na URL completa, e você pode usar o caractere curinga `*` e também os mesmos padrões disponíveis para rotas, exemplos:

```php
<?php

/*
 * As rotas só serão adicionadas se o caminho começar com /blog/
 * 
 * Amostras:
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

Para mais exemplos, veja o arquivo `system/dev.php`

## Padrões de rotas e URL

Tipo | Examplo | Descrição
---|---|---
`alnum` | `$app->action('GET', '/baz/<video:alnum>', ...);`       | Aceita apenas parâmetros com formato alfanumérico e `$params` retorna `['video' => ...]`
`alpha` | `$app->action('GET', '/foo/bar/<name:alpha>', ...);`    | Aceita apenas parâmetros com formato alfa e `$params` retorna `['name' => ...]`
`decimal` | `$app->action('GET', '/baz/<price:decimal>', ...);`   | Aceita apenas parâmetros com formato decimal e `$params` retorna `['price' => ...]`
`num` | `$app->action('GET', '/foo/<id:num>', ...);`              | Aceita apenas parâmetros com formato inteiro e `$params` retorna `['id' => ...]`
`nospace` | `$app->action('GET', '/foo/<nospace:nospace>', ...);` | Aceita quaisquer caracteres exceto espaços, como espaços em branco (`%20`), tabulações (`%0A`) e outros (veja sobre `\S` em regex)
`uuid` | `$app->action('GET', '/bar/<barcode:alnum>', ...);`      | Aceita apenas parâmetros com formato uuid e `$params` retorna `['barcode' => ...]`
`version` | `$app->action('GET', '/baz/<api:version>', ...);`     | Aceita apenas parâmetros com formato _Semantic Versioning 2.0.0 (semversion)_ e `$params` retorna `['api' => ...]`

It is possible to add or modify existing patterns using the `$app->setPattern(name, regex)` method. Creating a new pattern:

```php
<?php
use Inphinit\Viewing\View;

$app->action('GET', '/about/<lang:locale>', function ($params) {
    $lang = $params['lang'];
    ...
});

$app->action('GET', '/product/<id:id>', function ($params) {
    $lang = $params['id'];
    ...
});

$app->setPattern('locale', '[a-z]{1,8}(\-[A-Z\d]{1,8})?'); // examplos: en, en-US, en-GB, pt-BR, pt
$app->setPattern('id', '[A-Z]\d+'); // examplos: A0001, B002, J007
```

Modifying an existing pattern:

```php
<?php

// Troca semversion por <major>.<minor>.<revision>.<build>
$app->setPattern('version', '\d+\.\d+.\d+.\d+');

// Troca semversion por <major>.<minor> (talvez seja interessante para APIs web)
$app->setPattern('version', '\d+\.\d+');
```
