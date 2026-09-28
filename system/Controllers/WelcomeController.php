<?php
namespace Controllers;

use Inphinit\App;
use Inphinit\Experimental\Utility\Markdown;
use Inphinit\Http\Negotiation;
use Inphinit\Packages\Package;
use Inphinit\Viewing\View;

class WelcomeController
{
    public function index()
    {
        $version = Package::info('inphinit/framework', Package::VERSION);

        $markdown = new Markdown();
        $negotiation = new Negotiation();
        $portuguese = strpos($negotiation->topLanguage(), 'pt') === 0;

        View::data('environment', App::config('environment'));
        View::data('markdown', $markdown);
        View::data('portuguese', $portuguese);

        if ($portuguese) {
            $items = self::portuguese();
        } else {
            $items = self::english();
        }

        View::render('welcome', [
            'items' => $items,
            'time' => null,
            'version' => $version ? $version : ''
        ]);
    }

    private static function english()
    {
        return [
            [
                'title' => 'Web Routing',
                'link' => 'https://inphinit.github.io/en/docs/routing/',
                'body' => '**Routes** define how HTTP requests are associated with the actions that should be executed by the application. They allow **HTTP methods**, and URL paths to be mapped to **closures, functions, object methods, or controller methods**. The routing system also provides features for **grouping routes into scopes**, restricting them by paths, schemes (`http` or `https`), domains, or subdomains. In addition, it is possible to use **parameters and matching patterns** in the URL, including predefined patterns, or to define custom patterns using regular expressions.',
                'experimental' => false,
            ],

            [
                'title' => 'Controllers for Routes',
                'link' => 'https://inphinit.github.io/en/docs/controllers.html',
                'body' => '**Controllers** are an optional way to organize the logic responsible for handling HTTP requests and should be stored in the `system/Controllers/` directory. Instead of concentrating all actions directly in route definitions, the application can distribute these responsibilities among **classes and namespaces**, providing greater organization and making larger projects easier to maintain. The use of controllers is not mandatory. Depending on the project\'s needs, routes can use **closures, functions, or other types of callbacks**.',
                'experimental' => false,
            ],

            [
                'title' => 'Resource Routes',
                'link' => 'https://inphinit.github.io/en/docs/routing/resource.html',
                'body' => '**Resource routes** provide a convention for representing typical **CRUD** (*Create, Read, Update and Delete*) operations. Instead of declaring each route manually, the framework associates standardized controller methods with specific HTTP methods and paths. In this way, a conventional set of routes can be registered with a single call using the framework\'s resource mechanism. Controller methods are **optional**, allowing only the operations required for each resource to be implemented.',
                'experimental' => false,
            ],

            [
                'title' => 'Implicit Route Controllers',
                'link' => 'https://inphinit.github.io/en/docs/routing/implicit-route-controllers.html',
                'body' => '**Implicit route controllers** allow routes to be defined directly from the method names of a class. Each method name follows a convention that combines the **HTTP method** with the **route path**. For example, a method named `getInfo()` represents the route `GET /info`, while `postPing()` represents `POST /ping`. The framework interprets this convention and automatically registers the corresponding routes. This mechanism reduces the need to individually declare each call to `$app->action()`, concentrating in a single class both the implementation of the action and the implicit definition of the HTTP method and route path.',
                'experimental' => false,
            ],

            [
                'title' => 'HTTP',
                'link' => 'https://inphinit.github.io/en/docs/http/request.html',
                'body' => 'Inphinit provides additional features to facilitate the processing of **HTTP requests and responses**. The request API allows you to retrieve information such as **HTTP headers**, the request method, URL path, query parameters, values submitted via `GET` and `POST`, and cookies. Specific checks are also available to identify request characteristics, such as the use of **HTTPS**, **XHR**, **Pjax**, *prefetch*, and `Save-Data`. The framework also supports **content negotiation**, allowing applications to work with information provided by clients through headers such as `Accept`, `Accept-Encoding`, and `Accept-Language`.',
                'experimental' => false,
            ],

            [
                'title' => 'DOM, XML, and HTML',
                'link' => 'https://inphinit.github.io/en/docs/dom/',
                'body' => 'Inphinit provides its own API for working with **HTML and XML** documents, based on the **DOM** (*Document Object Model*). It allows documents to be loaded from strings or files. A standout feature is its support for **CSS selectors in PHP**, offering a more convenient alternative to XPath for locating elements within documents. The library also enables **bidirectional conversion between DOM documents and PHP arrays**, facilitating specific data transformation and processing tasks. These capabilities can be utilized, for example, in building **crawlers and document analysis tools**.',
                'experimental' => false,
            ],

            [
                'title' => 'Debugger',
                'link' => 'https://inphinit.github.io/en/docs/debugging.html',
                'body' => 'Inphinit\'s **development mode** enables validation and debugging mechanisms designed to facilitate error identification during development, including a strict mode. Errors, warnings, and exceptions can be displayed directly in the browser, along with information regarding the **file, the problematic line, and the code snippet associated with the failure**. The mechanism can also display issues encountered during the processing of HTML and XML documents via the DOM API. Another feature is integration with **code editors and external services**, including search engines and AI assistants. Error messages can generate links that forward the issue to services configured by the developer, streamlining the investigation process during development.',
                'experimental' => false,
            ],

            [
                'title' => 'Configuration',
                'link' => 'https://inphinit.github.io/en/docs/configurations.html',
                'body' => 'Inphinit\'s **configuration system** provides different mechanisms for organizing and defining the parameters used by the application, including the use of **environment variables**. These variables can be defined directly in the execution environment, through server configuration, or by using a `.env` file. One of the main variables is `APP_ENVIRONMENT`, which defines the application\'s current environment. When set to `development`, it enables development mode and the framework\'s debugging features.',
                'experimental' => false,
            ],

            [
                'title' => 'Maintenance Mode',
                'link' => 'https://inphinit.github.io/en/docs/maintenance.html',
                'body' => '**Maintenance mode** allows the application to be temporarily unavailable for new HTTP requests while adjustments, code updates, or configuration changes are being carried out. When maintenance mode is active, HTTP requests receive the **`503 Service Unavailable`** status code, indicating that the service is temporarily unavailable. This mode can be enabled or disabled through the built-in command-line command or programmatically, using the `Inphinit\App::down()` and `Inphinit\App::up()` methods.',
                'experimental' => false,
            ],

            [
                'title' => 'X-Accel-Redirect and X-Sendfile',
                'link' => 'https://inphinit.github.io/en/docs/production/sendfile.html',
                'body' => '`X-Accel-Redirect` and `X-Sendfile` are mechanisms used to delegate file delivery to the **web server** for files that, in certain situations, would otherwise be processed directly by PHP. This approach is especially useful for **protected files**, because the application can perform authentication and authorization steps before asking the web server to deliver the file. As a result, the content does not need to be transferred entirely through the PHP process, allowing the web server to use its own delivery mechanisms. In a development environment, when using the **PHP built-in web server**, the framework provides simulators for these headers, eliminating the need for additional configuration.',
                'experimental' => false,
            ],

            [
                'title' => 'Console Commands',
                'link' => 'https://inphinit.github.io/en/docs/console-commands.html',
                'body' => 'The **console command** system allows custom commands to be created for execution in the **CLI** environment. A command can be implemented as a *closure*, a `callable`, or a class method, allowing command-line task logic to be organized similarly to the organization used with controllers in the HTTP context. These commands are located in the `system/Commands/` directory, providing a dedicated structure for organizing functionality executed through the command line.',
                'experimental' => true,
            ],

            [
                'title' => 'Scheduled Tasks',
                'link' => 'https://inphinit.github.io/en/docs/task-scheduling.html',
                'body' => 'The **task scheduling** system allows functions, existing commands, and operating system commands to be scheduled for automatic execution using syntax similar to *crontab*. Although it uses familiar *crontab* syntax, the mechanism was designed to integrate with different types of schedulers provided by the operating system, such as **CRON, Windows Task Scheduler, Systemd, and Supervisor**. The framework itself manages the timing and state of the tasks, centralizing execution control.',
                'experimental' => true,
            ],

            [
                'title' => 'Sessions',
                'link' => 'https://inphinit.github.io/en/docs/session.html',
                'body' => 'Inphinit offers its own **session** system, which uses a *LOCK* mechanism only during read and write operations. This provides a better experience for users navigating multiple pages, including across different tabs or windows, by reducing unnecessary blocking during session access. Additionally, **session cookie** settings are centralized within the configuration system, offering greater flexibility for customization and adjustment.',
            ],

            [
                'title' => 'Utilities',
                'link' => 'https://inphinit.github.io/en/docs/utilities/',
                'body' => 'Although Inphinit prioritizes the use of **native PHP features**, it also provides specific utilities for common tasks required in web applications. These include methods for handling arrays, objects, and strings, as well as parsing URLs (allowing for the conversion of paths and full URLs) and **version strings**.',
            ],

            [
                'title' => 'Experimental Features',
                'link' => 'https://inphinit.github.io/en/docs/experimental/',
                'body' => '**Experimental features** are functionalities made available in advance for testing, evaluation, and feedback collection before possible incorporation into the framework\'s main API. Although most of these features are later promoted to non-experimental status, some may undergo **API changes, redesigns, or even be removed** in future versions. For this reason, their use should take into account the possibility of behavioral changes or incompatibilities in later versions.',
                'experimental' => true,
            ],
        ];
    }

    private static function portuguese()
    {
        return [
            [
                'title' => 'Rotas Web',
                'link' => 'https://inphinit.github.io/pt/docs/routing/',
                'body' => 'As **rotas** definem como as requisições HTTP são associadas às ações que devem ser executadas pela aplicação. Elas permitem mapear **métodos HTTP**, e caminhos de URL para **closures, funções, métodos de objetos ou métodos de controladores**. O sistema de roteamento também oferece recursos para **agrupar rotas em escopos**, restringindo-as por caminhos, esquemas (`http` ou `https`), domínios ou subdomínios. Além disso, é possível utilizar **parâmetros e padrões de correspondência** na URL, incluindo padrões predefinidos, ou definir padrões personalizados por meio de expressões regulares.',
                'experimental' => false,
            ],

            [
                'title' => 'Controladores para Rotas',
                'link' => 'https://inphinit.github.io/pt/docs/controllers.html',
                'body' => 'Os **controladores** são uma forma opcional de organizar a lógica responsável pelo tratamento das requisições HTTP e devem ser armazenados no diretório `system/Controllers/`. Em vez de concentrar todas as ações diretamente nas definições das rotas, a aplicação pode distribuir essas responsabilidades entre **classes e namespaces**, proporcionando maior organização e facilitando a manutenção de projetos maiores. A utilização de controladores não é obrigatória. Dependendo das necessidades do projeto, as rotas podem utilizar **closures, funções ou outros tipos de callbacks**.',
                'experimental' => false,
            ],

            [
                'title' => 'Rotas de Recursos',
                'link' => 'https://inphinit.github.io/pt/docs/routing/resource.html',
                'body' => 'As **rotas de recursos** fornecem uma convenção para representar operações típicas de **CRUD** (*Create, Read, Update and Delete*), ou, em português, criação, leitura, atualização e exclusão. Em vez de declarar cada rota manualmente, o framework associa métodos padronizados do controlador a métodos e caminhos HTTP específicos. Dessa forma, um conjunto convencional de rotas pode ser registrado a partir de uma única chamada utilizando o mecanismo de recursos do framework. Os métodos do controlador são **opcionais**, permitindo implementar apenas as operações necessárias para cada recurso.',
                'experimental' => false,
            ],

            [
                'title' => 'Controladores de Rota Implícitos',
                'link' => 'https://inphinit.github.io/pt/docs/routing/implicit-route-controllers.html',
                'body' => 'Os **controladores de rota implícitos** permitem definir rotas diretamente a partir dos nomes dos métodos de uma classe. O nome de cada método segue uma convenção que combina o **método HTTP** com o **caminho da rota**. Por exemplo, um método chamado `getInfo()` representa a rota `GET /info`, enquanto `postPing()` representa `POST /ping`. O framework interpreta essa convenção e registra automaticamente as rotas correspondentes. Esse mecanismo reduz a necessidade de declarar individualmente cada chamada a `$app->action()`, concentrando em uma única classe tanto a implementação da ação quanto a definição implícita do método HTTP e do caminho da rota.',
                'experimental' => false,
            ],

            [
                'title' => 'HTTP',
                'link' => 'https://inphinit.github.io/pt/docs/http/request.html',
                'body' => 'O Inphinit fornece recursos adicionais para facilitar o processamento de **requisições e respostas HTTP**. A API de requisição permite consultar informações como **cabeçalhos HTTP**, método da requisição, caminho da URL, parâmetros de consulta, valores enviados por `GET` e `POST` e cookies. Também estão disponíveis verificações específicas para identificar características da requisição, como o uso de **HTTPS**, **XHR**, **Pjax**, *prefetch* e `Save-Data`. O framework oferece ainda suporte à **negociação de conteúdo**, permitindo trabalhar com informações fornecidas pelos clientes por meio de cabeçalhos como `Accept`, `Accept-Encoding` e `Accept-Language`.',
                'experimental' => false,
            ],

            [
                'title' => 'DOM, XML e HTML',
                'link' => 'https://inphinit.github.io/pt/docs/dom/',
                'body' => 'O Inphinit disponibiliza uma API própria para trabalhar com documentos **HTML e XML**, baseada no modelo **DOM** (*Document Object Model*). Ela permite carregar documentos a partir de strings ou arquivos. Um dos recursos de destaque é o suporte a **seletores CSS em PHP**, oferecendo uma alternativa mais conveniente ao XPath para localizar elementos em documentos. A biblioteca também permite a **conversão bidirecional entre documentos DOM e arrays PHP**, facilitando determinadas operações de transformação e processamento de dados. Esses recursos podem ser utilizados, por exemplo, na construção de **crawlers e ferramentas de análise de documentos**.',
                'experimental' => false,
            ],

            [
                'title' => 'Depurador',
                'link' => 'https://inphinit.github.io/pt/docs/debugging.html',
                'body' => 'O **modo de desenvolvimento** do Inphinit habilita mecanismos de validação e depuração destinados a facilitar a identificação de erros durante o desenvolvimento, incluindo um modo estrito. Erros, avisos e exceções podem ser exibidos diretamente no navegador, juntamente com informações sobre o **arquivo, a linha problemática e o trecho de código relacionado à falha**. O mecanismo também pode exibir problemas encontrados durante o processamento de documentos HTML e XML via API DOM. Outro recurso é a integração com **editores de código e serviços externos**, incluindo mecanismos de busca e assistentes de IA. As mensagens de erro podem gerar links que encaminham o problema para serviços configurados pelo desenvolvedor, facilitando a investigação durante o desenvolvimento.',
                'experimental' => false,
            ],

            [
                'title' => 'Configurações',
                'link' => 'https://inphinit.github.io/pt/docs/configurations.html',
                'body' => 'O sistema de **configurações** do Inphinit oferece diferentes mecanismos para organizar e definir os parâmetros utilizados pela aplicação, incluindo o uso de **variáveis de ambiente**. Essas variáveis podem ser definidas diretamente no ambiente de execução, por meio das configurações do servidor ou utilizando um arquivo `.env`. Uma das principais variáveis é `APP_ENVIRONMENT`, que define o ambiente atual da aplicação. Quando configurada como `development`, ela ativa o modo de desenvolvimento e os recursos de depuração do framework.',
                'experimental' => false,
            ],

            [
                'title' => 'Modo de Manutenção',
                'link' => 'https://inphinit.github.io/pt/docs/maintenance.html',
                'body' => 'O **modo de manutenção** permite tornar a aplicação temporariamente indisponível para novas requisições HTTP enquanto são realizados ajustes, atualizações de código ou alterações de configuração. Quando o modo de manutenção está ativo, as requisições HTTP recebem o código de status **`503 Service Unavailable`**, indicando que o serviço está temporariamente indisponível. Esse modo pode ser ativado ou desativado por meio do comando integrado de linha de comando (*built-in*) ou programaticamente, utilizando os métodos `Inphinit\App::down()` e `Inphinit\App::up()`.',
                'experimental' => false,
            ],

            [
                'title' => 'X-Accel-Redirect e X-Sendfile',
                'link' => 'https://inphinit.github.io/pt/docs/production/sendfile.html',
                'body' => '`X-Accel-Redirect` e `X-Sendfile` são mecanismos utilizados para delegar ao **servidor web** a entrega de arquivos que, em determinadas situações, seriam processados diretamente pelo PHP. Essa abordagem é especialmente útil para **arquivos protegidos**, pois a aplicação pode realizar as etapas de autenticação e autorização antes de solicitar ao servidor web que entregue o arquivo. Dessa forma, o conteúdo não precisa ser transferido integralmente pelo processo do PHP, permitindo que o servidor web utilize seus próprios mecanismos de entrega. Em ambiente de desenvolvimento, utilizando o **PHP built-in web server**, o framework disponibiliza simuladores para esses cabeçalhos, dispensando configurações adicionais.',
                'experimental' => false,
            ],

            [
                'title' => 'Comandos de Console',
                'link' => 'https://inphinit.github.io/pt/docs/console-commands.html',
                'body' => 'O sistema de **comandos de console** permite criar comandos personalizados para execução no ambiente **CLI**. Um comando pode ser implementado como uma *closure*, um `callable` ou um método de uma classe, permitindo organizar a lógica das tarefas de linha de comando de forma semelhante à organização adotada com controladores no contexto HTTP. Esses comandos são localizados no diretório `system/Commands/`, proporcionando uma estrutura própria para organização das funcionalidades executadas via linha de comando.',
                'experimental' => true,
            ],

            [
                'title' => 'Tarefas Agendadas',
                'link' => 'https://inphinit.github.io/pt/docs/task-scheduling.html',
                'body' => 'O sistema de **agendamento de tarefas** permite programar a execução automática de funções, comandos existentes e comandos do sistema operacional utilizando uma sintaxe semelhante à do *crontab*. Embora utilize uma sintaxe familiar ao *crontab*, o mecanismo foi projetado para integração com diferentes tipos de agendadores fornecidos pelo sistema operacional, como **CRON, Windows Task Scheduler, Systemd e Supervisor**. O próprio framework gerencia o tempo e o estado das tarefas, centralizando o controle da execução.',
                'experimental' => true,
            ],

            [
                'title' => 'Sessões',
                'link' => 'https://inphinit.github.io/pt/docs/session.html',
                'body' => 'O Inphinit oferece um sistema próprio de **sessões**, que utiliza o mecanismo de *LOCK* apenas durante as operações de leitura e gravação. Isso proporciona uma melhor experiência ao usuário que navega por múltiplas páginas, inclusive em diferentes abas ou janelas, reduzindo bloqueios desnecessários durante o acesso à sessão. Além disso, as configurações dos **cookies de sessão** são centralizadas no sistema de configurações, proporcionando maior flexibilidade para sua personalização e ajuste.',
            ],

            [
                'title' => 'Utilitários',
                'link' => 'https://inphinit.github.io/pt/docs/utilities/',
                'body' => 'Embora o Inphinit siga a filosofia de priorizar o uso dos **recursos nativos do PHP**, ele também disponibiliza alguns utilitários específicos para tarefas recorrentes e necessárias em aplicações web. Entre eles, métodos para operações com arrays, objetos, strings, parse de URLs (que permite converter caminhos e URLs inteiras), e parse de **Strings de versão**.',
            ],

            [
                'title' => 'Recursos Experimentais',
                'link' => 'https://inphinit.github.io/pt/docs/experimental/',
                'body' => 'Os **recursos experimentais** são funcionalidades disponibilizadas antecipadamente para testes, avaliação e coleta de feedback antes de uma eventual incorporação à API principal do framework. Embora a maioria dessas funcionalidades seja posteriormente promovida ao estado não experimental, algumas podem sofrer **alterações de API, reformulações ou até mesmo ser removidas** em versões futuras. Por esse motivo, seu uso deve considerar a possibilidade de mudanças de comportamento ou incompatibilidades em versões posteriores.',
                'experimental' => true,
            ],
        ];
    }
}
