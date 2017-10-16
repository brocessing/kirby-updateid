<h1 align="center">Kirby UpdateID</h1>
<h3 align="center">Automatically update references to a page id</h3>

<div align="center">
    <img alt="version" src="https://img.shields.io/badge/version-0.1.1-green.svg?style=flat-square"/>
    <img alt="kirby_version" src="https://img.shields.io/badge/kirby-2.3+-red.svg?style=flat-square"/>
    <img alt="license" src="https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square"/>
    <br>
    <br>
</div>

<br>
<br>

## About

- Sometimes you need to reference pages to each other, usually by the page ID
- When you update a page URI, the ID can change and references to this page from other page/fields can be lost.
- This plugin automatically updates selected fields when a referenced ID change.

<br>

## Installation

Use one of the alternatives below.

### 1. Using [`kirby-webpack`](https://github.com/brocessing/kirby-webpack)

Simply use the built-in **Kirby Package Manager** by running:

```sh
$ npm run kirby:add
$ [?] Git URL: https://github.com/brocessing/kirby-updateid
$ [?] Module name: updateid
$ [?] Category: plugins
```

### 2. Kirby CLI

If you are using the [Kirby CLI](https://github.com/getkirby/cli) you can install this plugin by running the following commands in your shell:

```sh
$ cd path/to/kirby
$ kirby plugin:install brocessing/kirby-updateid
```

### 3. Clone or download

1. [Clone](https://github.com/brocessing/kirby-updateid.git) or [download](https://github.com/brocessing/kirby-updateid/archive/master.zip) this repository.
2. Unzip the archive if needed and rename the folder to `updateid`.

**Make sure that the plugin folder structure looks like this:**

```text
site/plugins/updateid/
```

### 4. Git Submodule

If you know your way around Git, you can download this plugin as a submodule:

```sh
$ cd path/to/kirby
$ git submodule add https://github.com/brocessing/kirby-updateid site/plugins/updateid
```

<br>

## Setup & Usage

**Use `c::set('plugin.updateid', array $config)` to specify which fields can be updated.**

##### Basic configuration:
```php
c::set('plugin.updateid', array(
  // On the homepage, page ids from the field featured_works will be auto-updated
  array(
    'pages'  => 'home',
    'fields' => 'featured_works'
  ),
  // You can add other pages
  // And use arrays to specify multiple pages & multiple fields to update
  array(
    'pages'  => ['about', 'contact'],
    'fields' => ['emails', 'authors']
  )
));
```

##### You can also use a function to select a collection of pages
```php
c::set('plugin.updateid', array(
  // Auto-update client ID on each project page
  array(
    'pages'  => function () { return site()->find('work')->children(); },
    'fields' => 'client'
  )
));
```

<br>

## Requirements

- [**Kirby**](https://getkirby.com/) 2.3+

<br>

## Disclaimer

This field is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/brocessing/kirby-updateid/issues/new).

<br>

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this field in any project that promotes racism, sexism, homophobia, animal abuse, violence or any other form of hate speech.
