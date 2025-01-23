# Timberland :evergreen_tree:

Timberland is an opinionated WordPress theme using [Timber](https://www.upstatement.com/timber/), [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/), [Tailwind](https://tailwindcss.com/) and [&lt;is-land&gt;](https://is-land.11ty.dev/).

[Alpine.js](https://github.com/alpinejs/alpine) is preconfigured and can be enabled globally in `theme/views/base.twig`.

As of version 1.0, Timberland now uses the WordPress block editor to visually edit the site. This is made possible by the [ACF Blocks feature](https://www.advancedcustomfields.com/resources/blocks/).

## Installation

1. Download the zip for this theme (or clone it) and move it to `wp-content/themes` in your WordPress installation.
2. Run `composer install` in the theme directory.
3. Run `npm install` in the theme directory.
4. Activate the theme in Appearance > Themes.
5. Make sure you have installed [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/).

## Import Maps and Tailwind CLI

- JavaScript dependencies are now managed with the native JavaScript import map feature, configured in `theme/views/base.twig`.
- Tailwind CLI is used to process CSS.

## Development

To get started, run the following command to start the development server:

```bash
npm run dev
```

### Live Reload

Live reload is enabled by default using [Browsersync](https://browsersync.io/). Configure your local development URL in `dev.js`.

## Production

For production, ensure the following steps are completed:

1. Build the assets using:

   ```bash
   npm run build
   ```

2. Verify that all dependencies are correctly managed using the import map.

If you're developing locally and moving files to your production environment, only the `theme` and `vendor` directories are needed inside the `timberland` theme directory. The theme directory structure should look like this:

```
  timberland/
  ├── theme/
  ├── vendor/
```

To assist with long-term caching, file hashing (e.g., `main-e1457bfd.js`) is enabled by default, which is useful for cache-busting purposes.

## is-land.js and the Islands Architecture

The theme uses `is-land.js` to implement the islands architecture. This approach allows for the progressive enhancement of specific parts of the page, improving performance and user experience.

### What is Islands Architecture?

Islands architecture is a design pattern where interactive components (islands) are isolated from the rest of the page. Each island can be independently hydrated and updated without affecting the rest of the page. This results in faster initial page loads and more efficient updates.

### Using is-land.js

`is-land.js` has been preconfigured and is ready to use in views and blocks.

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

[Explore more examples of how to use is-land](https://is-land.11ty.dev/).

## Blocks

A block is a self-contained page section that includes its own template, functions, and `block.json` file.

### Steps to Create a Block

1. Create a directory under `theme/blocks`.
2. Add your `index.twig` and `block.json` files.
3. (Optional) Add a `functions.php` file for custom functionality.
4. Define editable fields by creating an ACF field group and linking it to the block using a location rule.

An example block is provided for reference.

### Accessing Fields

You can access your block's fields in the `index.twig` file using the `fields` variable. For example:

```twig
{{ fields.heading }}
```

To loop through a repeater field (e.g., "features") with a subfield (e.g., "heading"):

```twig
{% for feature in fields.features %}
  {{ feature.heading }}
{% endfor %}
```

## Directory Structure

- `theme/`: WordPress core template files.
- `theme/acf-json/`: Stores Advanced Custom Fields JSON files, automatically created/updated using ACF's Local JSON feature.
- `theme/assets/`: Contains fonts, images, styles, and scripts.
- `theme/blocks/`: Contains the site's blocks, available for use via the block editor. Each block has its own template, script, and style files.
- `theme/patterns/`: Contains the site's block patterns. Block patterns are predefined blocks you can insert into pages and posts and customize with your own content.
- `theme/views/`: Contains Twig templates. These correspond 1-to-1 with the PHP files that follow the WordPress template hierarchy. Each PHP template ends with a `Timber::render()` function to pass data to its associated Twig file.

## License

MIT © Chris Earls

