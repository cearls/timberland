# Timberland :evergreen_tree:

Timberland is an opinionated WordPress theme using [Timber](https://www.upstatement.com/timber/), [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/), [Tailwind](https://tailwindcss.com/) and [&lt;is-land&gt;](https://github.com/11ty/is-land).

[Alpine.js](https://github.com/alpinejs/alpine) is preconfigured and can be enabled if needed.

As of version 1.0, Timberland now uses the WordPress block editor to visually edit the site. This is made possible by the [ACF Blocks feature](https://www.advancedcustomfields.com/resources/blocks/).

## Installation

1. Download the zip for this theme (or clone it) and move it to `wp-content/themes` in your WordPress installation.
2. Run `composer install` in the theme directory.
3. Run `npm install` in the theme directory.
4. Activate the theme in Appearance > Themes.
5. Make sure you have installed [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/)

## Import Maps and Tailwind CLI

Timberland now uses native JavaScript importmap to manage  JavaScript dependencies. This can be configured in `theme/views/base.twig`.

Tailwind CLI is used to compile CSS.

There is no frontend tooling beyond basic Javascript that is included in `dev.js` and `build.js`.

## Development

To get started, run `npm run dev` to start the development server.

### Live Reload

Live reload is enabled by default and your local development URL can be configured in `dev.js`.

## Production

For production, ensure that you have built the assets using `npm run build` and that all dependencies are correctly managed using the importmap.

If you're developing locally and moving files to your production environment, only the `theme` and `vendor` directories are needed inside the `timberland` theme directory. The theme directory structure should look like the following:

```
  timberland/
  ├── theme/
  ├── vendor/
```

To assist with long-term caching, file hashing (e.g. `main-e1457bfd.js`) is enabled by default. This is useful for cache-busting purposes.

## is-land.js and the Islands Architecture

The theme uses `is-land.js` to implement the islands architecture. This approach allows for the progressive enhancement of specific parts of the page, improving performance and user experience.

### What is Islands Architecture?

Islands architecture is a design pattern where interactive components (islands) are isolated from the rest of the page. Each island can be independently hydrated and updated without affecting the rest of the page. This results in faster initial page loads and more efficient updates.

### Using is-land.js

`is-land.js` has been preconfigured and is ready to use in your views and blocks.

Example:

```html
<is-land on:visible import="https://unpkg.com/alpinejs">
    <template>
        <div>
            <!-- Your interactive component here -->
        </div>
    </template>
</is-land>
```

## Blocks

A block is a self-contained page section and includes its own template, functions and block.json files.

```
  example/
  ├── block.json
  ├── functions.php 
  ├── index.twig
```

To create a new block, create a directory in `theme/blocks`. Add your `index.twig` and `block.json` files and it's ready to be used with the WordPress block editor. You can optionally add a functions.php file. An example block is provided for reference. Add editable fields by creating a new ACF field group and setting the location rule to your new block. You can now use these fields with your block in the block editor.

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
