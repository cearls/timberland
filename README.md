# Timberland :evergreen_tree:

Timberland is an opinionated WordPress theme using [Timber](https://www.upstatement.com/timber/), [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/), [Laravel Mix](https://github.com/JeffreyWay/laravel-mix), [Tailwind](https://tailwindcss.com/) and [Alpine.js](https://github.com/alpinejs/alpine).

## Installation

1. Download the zip for this theme (or clone it) and move it to `wp-content/themes` in your WordPress installation.
2. Run `composer install` in the theme directory.
3. Run `npm install` in the theme directory.
4. Activate the theme in Appearance > Themes.
5. Make sure you have installed [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/)

## Development

Timberland builds your css and js files using Laravel Mix. This allows you to use the latest Javascript and CSS features.

To build your assets for development, run `npm run development` or `npm run watch` from the theme directory in the terminal.

When you're ready for production, run `npm run production` from the theme directory in the terminal.

### Browsersync

To use Browsersync during local development, rename `browsersync.config-sample.js` to `browsersync.config.js` and update the proxy to match your local development URL. Other options can be seen in the [Browsersync documentation](https://browsersync.io/docs/options/).

### Versioning

To assist with long-term caching, file hashing (e.g. `app.js?id=8e5c48eadbfdd5458ec6`) is enabled by default. This is useful for cache-busting purposes.

## Blocks

A block is a self-contained page section and includes its own template, scripts and styles. 

```
  example/
  ├── index.twig
  ├── script.js
  ├── style.css
```

To create a new block, create a directory in `theme/blocks`. Add your `index.twig` and optional css and js files and it's ready to be used with the WordPress block editor. Add editable fields by creating a new ACF field group and setting the location rule to your new block. You can now use these fields with your block in the block editor.

### Accessing Fields

You access your block's fields in the index.twig file by using the `fields` variable. The example below shows how to display a block's field. We'll use "heading" as the example ACF field name, but it could be whatever name you give your field.

`{{ fields.heading }}`

Here's an example of how to loop through a repeater field where "features" is the ACF field name and the repeater field has a heading field.

```
{% for feature in fields.features %}
{{ feature.heading }}
{% endfor %}
```

## Directory Structure

`theme/` contains all of the WordPress core templates files.

`theme/acf-json/` contain all of your Advanced Custom Fields json files. These files are automatically created/updated using ACF's Local JSON feature.

`theme/assets/` contain all of your fonts, images, styles and scripts.

`theme/blocks/` contain all of your site's blocks. These blocks are available to use on any page via the block editor. Each block has its own template, script and style files.

`theme/views/` contains all of your Twig templates. These pretty much correspond 1 to 1 with the PHP files that respond to the WordPress template hierarchy. At the end of each PHP template, you'll notice a `Timber::render()` function whose first parameter is the Twig file where that data (or `$context`) will be used.

## License
MIT © Chris Earls