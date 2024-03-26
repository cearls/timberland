# Timberland :evergreen_tree:

Timberland is an opinionated WordPress theme using [Timber](https://www.upstatement.com/timber/), [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/), [Vite](https://vitejs.dev/), [Tailwind](https://tailwindcss.com/) and [Alpine.js](https://github.com/alpinejs/alpine).

As of version 1.0, Timberland now uses the WordPress block editor to visually edit the site. This is made possible by the [ACF Blocks feature](https://www.advancedcustomfields.com/resources/blocks/).

## Installation

1. Download the zip for this theme (or clone it) and move it to `wp-content/themes` in your WordPress installation.
2. Run `composer install` in the theme directory.
3. Run `npm install` in the theme directory.
4. Activate the theme in Appearance > Themes.
5. Make sure you have installed [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/)

## Development

Timberland builds your css and js files using Vite. This allows you to use the latest Javascript and CSS features.

To get started:
1. Run `npm run build` to generate assets that can be used in the admin block editor. This only needs to be run as often as you want to see updated block previews in the admin.
2. Run `npm run dev` to start the Vite dev server.

### Live Reload

Live reload is enabled by default.

### Versioning

To assist with long-term caching, file hashing (e.g. `main-e1457bfd.js`) is enabled by default. This is useful for cache-busting purposes.

## Production

When you're ready for production, run `npm run build` from the theme directory. You can test production assets in development by setting the vite → environment property to "production" in config.json.

If you're developing locally and moving files to your production environment, only the `theme` and `vendor` directories are needed inside the `timberland` theme directory. The theme directory structure should look like the following:

```
  timberland/
  ├── theme/
  ├── vendor/
```

## Blocks

A block is a self-contained page section and includes its own template, script, style, functions and block.json files.

```
  example/
  ├── block.json
  ├── functions.php 
  ├── index.twig
  ├── script.js
  ├── style.css
```

To create a new block, create a directory in `theme/blocks`. Add your `index.twig` and `block.json` files and it's ready to be used with the WordPress block editor. You can optionally add style.css, script.js and functions.php files. An example block is provided for reference. Add editable fields by creating a new ACF field group and setting the location rule to your new block. You can now use these fields with your block in the block editor.

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

`theme/patterns/` contains all of your sites's block patterns. Block Patterns are a collection of predefined blocks that you can insert into pages and posts and then customize with your own content. 

`theme/views/` contains all of your Twig templates. These pretty much correspond 1 to 1 with the PHP files that respond to the WordPress template hierarchy. At the end of each PHP template, you'll notice a `Timber::render()` function whose first parameter is the Twig file where that data (or `$context`) will be used.

## License

MIT © Chris Earls
