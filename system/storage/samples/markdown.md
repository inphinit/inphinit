# Get started

Foo Bar Baz h~2~o, 5^th^

___test___, `___test___`

`foo bar \` baz`

This is *italic* text.
This is \*a plain asterisk\* text.

snake_case snake_case

Olá, Mündô! <file:///home/bar/baz>, <https://inphinit.github.io/en/>, <foo@bar>

Link image: [![alt](/favicon.ico "caption")](#fragment "title")

## Horizontal lines

Horizontal `***`:

***

Horizontal `---`:

---

Horizontal `___`:

___

Horizontal `******`:

******

Horizontal `------`:

------

Horizontal `______`:

______


## HTML

<b>Bold</b>
<i>Italic</i>
<mark>Mark</mark>

# Non-standard (custom inline)

- highlight: !!highlight!!
- variable: %%variable%%
- inserted: ++inserted++
- deleted: --deleted--

## System requirements

Task:

- [x] Write the press release
- [ ] Update the website
- [ ] Contact the media
- [] other

Unorded lists:

* A
* B
* C
  - D
  - E
  - F
    * G
    * H
    * I
      * J
      * K
      * L
* M
* N
* O

Orded lists:

1. A
1. B
1. C
  1. D
  1. E
  1. F
    1. G
    1. H
    1. I
  1. J
  1. K
  1. L
1. J
1. K
1. L

Example:

* Alpha
* Beta
* Gamma
  - [Omicron](#fragment)
  - Pi
    1. Foo <b>bold</b> bar
    1. Bar <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="> baz
    1. Baz
  - [Sigma](https://inphinit.github.io)
  - Omega
* Delta
* Epsilon

## System requirements

Foo bar

## Install using Git

Table:

| Syntax      | Description | Test Text     |
| :---        |    :----:   |          ---: |
| Header      | Title       | Here's this   |
| Paragraph   | Text        | And more      |

Blockquote + Table:

> Table:
>
> | Syntax      | Description | Test Text     |
> | :---        |    :----:   |          ---: |
> | Header      | Title       | Here's this   |
> | Paragraph   | Text        | And more      |
>
> For production see [web servers](#web-servers).

## Create routes

To create a new route, edit the `system/main.php` file, if you want the route to only be available in development mode, then edit the `system/dev.php` file.

The route system supports controllers, callables and anonymous functions, examples:

```php
<?php

// anonymous functions
$app->action('GET', '/closure', function () {
    return 'Hello "closure"!';
});
```

## Web Servers

Here are basic instructions to configure Inphinit on servers:

* [Apache](https://inphinit.github.io/en/docs/web-servers/apache.html)
* [Caddy & FrankenPHP](https://inphinit.github.io/en/docs/web-servers/caddy-frankenphp.html)
* [IIS](https://inphinit.github.io/en/docs/web-servers/iis.html)
* [IIS Express](https://inphinit.github.io/en/docs/web-servers/iis-express.html)
* [Nginx](https://inphinit.github.io/en/docs/web-servers/nginx.html)
