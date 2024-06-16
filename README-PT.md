# Inphinit 2.0

- [Instalando](#instalando)
- [Testando](#testando)
- [NGINX](#nginx)
- [Estrutura das pastas](#estrutura-das-pastas)
- [Criando rotas](#criando-rotas)
- [Agrupando rotas](#agrupando-rotas)
- [Padrões de rotas e URL](#padrões-de-rotas-e-url)

## Decisões e o que vem a seguir

O objetivo deste framework sempre foi ser o mais eficiente possível, porém algo que sempre me preocupou foram problemas de depuração, apesar de existirem diversas ferramentas, sempre busquei algo simples, mas claro mesmo quem está trabalhando pela primeira vez veja o erro. Então, no ano passado, tomei as seguintes decisões:

- No modo de desenvolvimento a aplicação irá funcionar em modo estrito, verificando qualquer possível falha
- Alterar a forma como as rotas funcionam, para torná-las mais rápidas e também poder prever falhas, quando utilizadas no desenvolvimento
- Alguns erros de digitação podem fazer com que certos recursos do PHP não respondam em tempo hábil, como o carregamento automático, de modo que o modo de desenvolvedor pré-carregará tudo o que você precisa antes que qualquer script interrompa o processo, permitindo que a depuração localize e exiba exatamente em qual linha o erro está.

Todas essas decisões já estão incorporadas no framework, algumas das quais já foram adicionadas à versão 0.6, para facilitar a portabilidade do projeto para a versão futura do framework.

Todas as rotas e aplicação básica já estão estabelecidas, mas outras APIs internas, para outros usos, ainda estão em desenvolvimento, então estamos entrando nesta fase, e dentro de 4 semanas no máximo será lançado o primeiro beta, onde não irei incluir qualquer nova funcionalidade, será uma série de correções e possíveis regressões.

## O que já alcançamos

Sempre valorizei desempenho e simplicidade, parte do que foi implementado no _Inphinit 2.0_ já foi portado para _0.6_, o que proporcionou uma grande melhoria nessas versões antes do lançamento de 2.0, e mesmo que a _versão 0.5_ seja muito eficiente, o salto no desempenho foi incrível da _versão 0.6_ em diante. Na _versão 2.0_ é um pouco melhor, então aqui vai um exemplo dos testes, com o modo de desenvolvimento desligado:

Descrição                                                            | v0.5.19                       | v0.6                          | v2.0
---                                                                  | ---                           | ---                           | --- 
Tempo gasto para testes:                                             | 0.528 segundos                | 0.429 segundos                | 0.391 segundos
Solicitações por segundo (média):                                    | 1892.46 [#/seg]               | 2330.74 [#/seg]               | 2557.07 [#/seg]
Tempo por solicitação (média):                                       | 5.284 [ms]                    | 4.290 [ms]                    | 3.911 [ms]
Tempo por solicitação (média, em todas as solicitações simultâneas): | 0.528 [ms]                    | 0.429 [ms]                    | 0.391 [ms]
Taxa de transferência:                                               | 373.32 [Kbytes/seg] recebidos | 459.77 [Kbytes/sec] recebidos | 504.42 [Kbytes/seg] recebidos

Além da melhoria no tempo de execução, nota-se que a _versão 2.0_ conseguiu processar em média mais 220 solicitações por segundo do que a versão _0.6_, e quando comparado à _0.5.x_, conseguiu processar 600 requisições a mais, por segundo.

## Sobre documentação

Algo que vou mudar é a documentação, o Github Wiki funcionou por um tempo mas notei alguns problemas:

- O menu gerado no wiki do github não é tão intuitivo e notei que mesmo alguns programadores experientes não conseguiam navegar por lá
- Organizar o conteúdo não foi tão fácil quanto eu precisava, muitas coisas são manuais, o que levou muito tempo para editar coisas simples
- O Github Desktop entra em conflito com repositórios do tipo wiki, é um [bug antigo](https://github.com/desktop/desktop/issues/3839#issue-290340050)

Então tomei a decisão de migrar para outra plataforma, ou talvez criar algo próprio, com o objetivo de poder documentar rapidamente e ao mesmo tempo fornecer uma interface amigável aos leitores, podendo assim ganhar tempo para me focar em traduzir a documentação para 3 idiomas, pelo menos. A documentação será aberta, para qualquer colaborador poder enviar correções ou adicionar algo que falte.

## Instalando

> **Nota:** Para instalar a _versão 0.6_ vá até: https://github.com/inphinit/inphinit/tree/1.x

Observe que ainda estamos em fase de desenvolvimento, e em 4 semanas pretendemos lançar o primeiro beta, que estará disponível via composer, a _versão 2.0_ ainda não é recomendada para produção, então prefira utilizá-la apenas para testes ou críticas que você deseja fazer durante esta etapa.

Para instalá-lo você deve ter pelo menos o PHP 5.4, mas é **recomendado que você utilize o PHP 8** devido a problemas de suporte ao PHP, leia:

> - https://www.php.net/supported-versions.php
> - https://www.php.net/eol.php

Após instalar o PHP, você precisa ter o Git em sua máquina, então execute os seguintes comandos:

```bash
git clone --recurse-submodules https://github.com/inphinit/inphinit.git my-application
cd my-application
```

## Testando

Após navegar até a pasta você deve executar o seguinte comando, caso queira usar o [PHP built-in web server](https://www.php.net/manual/en/features.commandline.webserver.php):

```bash
php -S localhost:5000 -t public index.php
```

Então acesso `http://localhost:5000/` no seu navegador

## NGINX

Se quiser experimentar um servidor web como o NGINX, você pode usar o exemplo a seguir para configurar seu `nginx.conf`:

```none
location / {
    root /home/foo/bar/my-application;

    # Redirecionar erros de página para sistema de rotas
    error_page 403 /index.php/RESERVED.INPHINIT-403.html;
    error_page 500 /index.php/RESERVED.INPHINIT-500.html;
    error_page 501 /index.php/RESERVED.INPHINIT-501.html;

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

> **NotA:** Para FPM use `fastcgi_pass unix:/var/run/php/php<version>-fpm.sock` (troque `<version>` pela versão do PHP em seu servidor)

## Estrutura das pastas

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

No modo de desenvolvimento, o script `system/dev.php` sempre será executado primeiro, depois será executado `system/main.php`, e se ocorrer algum erro, como 404 ou 405, o último script a ser executado será `system/erros.php`

## Criando rotas

Para criar uma nova rota edite o arquivo `system/main.php`, se deseja que a rota fique só disponível no modo de desenvolvimento, então edite o arquivo `system/dev.php`.

O sistema de rotas suporta _Controllers_, [_callables_](https://www.php.net/manual/en/language.types.callable.php) e [_funções anônimas_](https://www.php.net/manual/en/functions.anonymous.php), exemplos:

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

O sistema de agrupamento de rotas agora está muito mais simples, é baseado na URL completa, podendo utilizar o caractere `*` como curinga e também os mesmos padrões disponíveis para rotas, exemplos:

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

É possivel adicionar ou modificar os padrões existentes, usando o método `$app->setPattern(nome, regex)`. Criando um novo padrão:

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

$app->setPattern('locale', '[a-z]{1,8}(\-[A-Z\d]{1,8})?'); // examples: en, en-US, en-GB, pt-BR, pt
$app->setPattern('id', '[A-Z]\d+'); // examples: A0001, B002, J007
```

Modificando um padrão existente:

```php
<?php

// Troca semversion por <major>.<minor>.<revision>.<build>
$app->setPattern('version', '\d+\.\d+.\d+.\d+');

// Troca semversion por <major>.<minor> (talvez seja interessante para APIs web)
$app->setPattern('version', '\d+\.\d+');
```
