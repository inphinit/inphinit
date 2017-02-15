<?php
/*
 * Inphinit
 *
 * Copyright (c) 2017 Guilherme Nascimento (brcontainer@yahoo.com.br)
 *
 * Released under the MIT license
 *
 * Usage with command line: php generate-nginx.php
 * Or navigate to: http://[your website]/generate-nginx.php
 * Copy content in ouput and put in nginx.conf
 */

if (
    empty($_SERVER['SERVER_SOFTWARE']) ||
    stripos($_SERVER['SERVER_SOFTWARE'], 'microsoft-iis') === false
) {
    echo 'Use this script only with IIS or IIS Express';
    exit;
}

$base = dirname($_SERVER['PHP_SELF']);
$base = rtrim(strtr($base, '\\', '/'), '/');

$data = '<?xml version="1.0" encoding="UTF-8"?>
<configuration>
    <system.webServer>
        <defaultDocument>
            <files>
                <clear />
                <add value="index.php" />
            </files>
        </defaultDocument>
        <httpErrors>
            <remove statusCode="401" subStatusCode="-1" />
            <remove statusCode="403" subStatusCode="-1" />
            <remove statusCode="501" subStatusCode="-1" />
            <error statusCode="401"
                   responseMode="ExecuteURL"
                   path="' . $base . '/index.php/RESERVED.INPHINIT-401.html?RESERVED_IISREDIRECT=1" />
            <error statusCode="403"
                   responseMode="ExecuteURL"
                   path="' . $base . '/index.php/RESERVED.INPHINIT-403.html?RESERVED_IISREDIRECT=1" />
            <error statusCode="501"
                   responseMode="ExecuteURL"
                   path="' . $base . '/index.php/RESERVED.INPHINIT-501.html?RESERVED_IISREDIRECT=1" />
        </httpErrors>
        <rewrite>
            <rules>
                <rule name="Ignore system folder" stopProcessing="true">
                    <match url="^system/" ignoreCase="false" />
                    <action type="Rewrite" url="index.php" />
                </rule>
                <rule name="Redirect to routes" stopProcessing="true">
                    <conditions>
                        <add input="{REQUEST_FILENAME}" matchType="IsFile" negate="true" />
                        <add input="{REQUEST_FILENAME}" matchType="IsDirectory" negate="true" />
                    </conditions>
                    <match url="^" ignoreCase="false" />
                    <action type="Rewrite" url="index.php" />
                </rule>
            </rules>
        </rewrite>
    </system.webServer>
</configuration>
';

file_put_contents('web.config', $data);

echo '<pre>', htmlspecialchars($data), '</pre>';
