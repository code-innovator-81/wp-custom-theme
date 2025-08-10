# WP Custom Theme

This is a custom WordPress theme I built from scratch to demonstrate both front-end and back-end development skills. It includes a custom Projects post type, REST API endpoints, AJAX filtering, multi-level navigation, and is fully responsive.

---

## Setup

**Requirements:**
- WordPress 6.x+
- PHP 7.4 or newer
- MySQL/MariaDB

**Installation:**
1. Download or clone this repo into `wp-content/themes/wp-custom-theme`:
    ```
    git clone https://github.com/code-innovator-81/wp-custom-theme.git
    ```
2. Go to Appearance > Themes in the WordPress admin and activate "WP Custom Theme".

---

## Theme Structure

- `functions.php` — Theme setup and custom functions
- `inc/custom-post-types.php` — Registers the "Projects" post type
- `inc/custom-fields.php` — Adds custom fields for projects
- `inc/api-endpoints.php` — Custom REST API endpoints
- `js/main.js` — Navigation, filtering, and other JS
- `style.css` — Main stylesheet (required by WP)
- `css/main.css` — Extra styles
- `css/responsive.css` — Responsive styles
- `single-project.php` — Single project template
- `archive-project.php` — Projects archive and filter
- `templates/home.php` — Home page template
- `templates/blog.php` — Blog page template

---

## Features

- **Custom Post Type:** "Projects" with custom fields (name, description, dates, URL)
- **Custom Templates:** Archive and single templates for projects
- **Navigation:** Dynamic, multi-level menu using `wp_nav_menu()`
- **REST API:**  
  - List projects: `/wp-json/wp-custom/v1/projects`
  - Single project by slug: `/wp-json/wp-custom/v1/project-by-slug/{slug}`
- **AJAX Filtering:** Filter projects by date and category on the archive page
- **Responsive Design:** Mobile-friendly navigation and layouts
- **Security:** Sanitized input, escaped output, nonces for AJAX

---

## Usage

- **Add Projects:**  
  Go to Projects in the admin and add new items with all required fields.
- **Menus:**  
  Set up your navigation under Appearance > Menus.
- **Page Templates:**  
  Assign Home or Blog templates to pages via Page Attributes.

---

## API Testing

You can test the API endpoints with curl or Postman:
```
curl http://localhost/wp-json/wp-custom/v1/projects
curl http://localhost/wp-json/wp-custom/v1/project-by-slug/my-project-slug
```

---

## Customization

Edit styles in `style.css`, `css/main.css`, or `css/responsive.css`.  
Add new fields or endpoints in the `inc/` folder as needed.

---

## Troubleshooting

- If AJAX filtering doesn’t work, check the nonce and action in your JS.
- If REST API endpoints return 404, make sure permalinks are set (not "Plain") and the theme is active.

---

## License

MIT

---

## Credits

Theme by Atul