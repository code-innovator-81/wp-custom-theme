# WP Custom Theme

A custom WordPress theme built from scratch, showcasing front-end and back-end development skills. Features include custom post types, custom fields, REST API endpoints, AJAX filtering, multi-level responsive navigation, and security best practices.

---

## 🚀 Setup Instructions

### 1. Requirements
- WordPress 6.x or later
- PHP 7.4+
- MySQL/MariaDB

### 2. Installation
1. Clone or download this repository into your WordPress `wp-content/themes` directory:
    ```
    git clone https://github.com/code-innovator-81/wp-custom-theme.git
    ```
2. Activate the theme from the WordPress admin dashboard (`Appearance > Themes`).

---

## 📁 Theme Structure

- `functions.php` — Theme setup, scripts, custom functions
- `inc/custom-post-types.php` — Registers custom post types (e.g., Project)
- `inc/custom-fields.php` — Registers custom fields/meta for projects
- `inc/api-endpoints.php` — Custom REST API endpoints
- `js/main.js` — Main JavaScript (navigation, filtering, etc.)
- `style.css` — Main stylesheet (in theme root, required by WordPress)
- `css/main.css` — Additional main styles
- `css/responsive.css` — Responsive styles
- `single-project.php` — Single project template
- `archive-project.php` — Project archive & filter template
- `templates/home.php` — Custom Home page template
- `templates/blog.php` — Custom Blog page template

---

## 🏗️ Features & Implementation

### 1. Theme Development
- Built from scratch, no page builders.
- Follows WordPress theme development best practices.
- Includes at least two custom page templates: Home and Blog.

### 2. Custom Post Types
- **Projects**: Created via code (no plugins).
- Custom fields for each project:
  - Project Name
  - Project Description
  - Project Start Date
  - Project End Date
  - Project URL

### 3. Custom Archive and Single Pages
- Custom templates for project archive and single project.
- Displays custom fields in a visually appealing way.

### 4. Dynamic Navigation Menu
- Uses `wp_nav_menu()` for dynamic, multi-level navigation.
- Supports nested dropdowns and is mobile-friendly.

### 5. Custom REST API Endpoints
- **List Projects:**  
  `GET /wp-json/wp-custom/v1/projects`
- **Single Project by Slug:**  
  `GET /wp-json/wp-custom/v1/project/{slug}`  
  Returns: Project Title, Project URL, Project Start Date, Project End Date

### 6. Responsive Design
- Fully responsive and mobile-friendly.
- Uses CSS and minimal JavaScript/jQuery for navigation and layout.

### 7. Basic Security
- All user input is sanitized and output is escaped using WordPress functions.
- Nonces are used for AJAX requests to prevent CSRF.

### 8. Bonus: AJAX Project Filtering
- Filter projects by start date, end date, and category on the archive page.
- Uses AJAX (`admin-ajax.php`) for fast, dynamic filtering.

---

## 🧑‍💻 Usage

### How to Add Projects
- Go to **Projects** in the WordPress admin dashboard.
- Add new projects and fill in the custom fields.

### How to Assign Menus
- Go to **Appearance > Menus** to create and assign your navigation menu.

### How to Use Page Templates
- Assign the Home or Blog template to a page via the Page Attributes panel in the editor.

---

## 🔌 API Testing

- Use browser, Postman, or curl:
    ```
    curl http://localhost/wp-json/wp-custom/v1/projects
    curl http://localhost/wp-json/wp-custom/v1/project-by-slug/my-project-slug
    ```

---

## 🛠️ Customization

- Edit `style.css` and `responsive.css` for design changes.
- Add new fields or endpoints in the `inc/` directory.
- Use the WordPress Customizer for logo, colors, etc.

---

## 📝 Troubleshooting

- If AJAX filtering fails, check that the nonce and action are sent correctly.
- If REST API endpoints return 404, ensure permalinks are not set to "Plain" and theme is active.

---

## 📄 License

MIT (or your preferred license)

---

## ✨ Credits

Developed by Atul
