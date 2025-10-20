## Repository overview

This is a lightweight PHP authentication lab (no frameworks). It uses simple controller/model/action organization with procedural entry points under `actions/` and page templates in the project root and `login/` and `admin/` folders.

Key directories and files
- `settings/` — bootstrap, session helpers, and DB wrapper (`core.php`, `db_class.php`, `db_cred.php`).
- `classes/` — data layer (models) that extend `db_connection` (`customer_class.php`, `user_class.php`, `category_class.php`).
- `controllers/` — thin controller functions that call model methods (e.g. `customer_controller.php`, `user_controller.php`).
- `actions/` — AJAX/HTTP POST endpoints that return JSON and call controller functions (e.g. `register_user_action.php`, `login_customer_action.php`).
- `login/`, `admin/` — simple pages which use helpers in `settings/core.php` for auth checks.

Big-picture architecture and flow
- Entry points are either HTML/PHP pages (like `index.php`, `login/login.php`, `admin/category.php`) or small endpoint scripts in `actions/` that expect POST data and return JSON.
- Action scripts usually: start session, validate input, call controller functions under `controllers/`, and echo JSON responses. See `actions/register_user_action.php` for the pattern.
- Controllers instantiate model classes from `classes/` which extend the DB wrapper in `settings/db_class.php`. Models use mysqli prepared statements (or direct mysqli) to query the DB.

Project-specific conventions
- Database wrapper: `db_connection` provides `db_connect()`, `db_conn()` (returns mysqli object), `db_query()`, `db_write_query()`, and fetch helpers. Most model classes either call `parent::db_connect()` or set `$this->db = $this->db_conn()` in the constructor.
- Naming: HTML form fields map directly to POST keys used in `actions/*` files (e.g., `name`, `email`, `password`). Controller functions use `_ctr` suffix (e.g., `register_customer_ctr`).
- Roles: `settings/core.php` uses session keys like `customer_id`, `customer_name`, and `user_role`. Admin is role `1` by convention.
- Output: Action scripts return JSON with `status` and `message` keys. Preserve this shape when adding new endpoints.

Security and style notes (what already exists; do not remove)
- Passwords are hashed using `password_hash()` and verified with `password_verify()` in `classes/customer_class.php` and `classes/user_class.php`.
- Prepared statements are used in model methods; prefer those over string interpolation. Some legacy functions in `db_class.php` still accept raw SQL — prefer model-level prepared statements.

Testing, build and run
- This is a simple PHP app intended to run under XAMPP/Apache + MySQL. No build step.
- To run locally (developer machine with XAMPP): place the repo under `htdocs/` (already expected), import `db/dbforlab.sql` into MySQL, then open `http://localhost/authentication_lab/`.
- To test an action endpoint, send a POST to `actions/register_user_action.php` with form fields `name,email,password,phone_number,country,city,role` and expect JSON.

Common edit patterns for contributors
- When adding new endpoints: add a file in `actions/` that returns JSON and calls a controller function in `controllers/`. Keep sessions consistent by calling `session_start()` or using `settings/core.php` when rendering pages.
- When adding model logic: extend `db_connection`, call `$this->db = $this->db_conn()` in `__construct()` or `parent::db_connect()` and use prepared statements.
- When creating pages that require authentication, include `require_once __DIR__ . '/../settings/core.php'` and guard with `isLoggedIn()` or `isAdmin()`.

Files worth reading for examples
- `actions/register_user_action.php` — Typical action endpoint; shows POST handling, session, and JSON response.
- `classes/customer_class.php` — Good example of prepared statements, password hashing, and query patterns.
- `settings/db_class.php` — Central DB helper — read to understand helper methods used across models.
- `index.php` — Shows session usage for UI (menu tray) and how `settings/core.php` integrates.

Edge-cases for AI agents
- DB column names sometimes differ from variable names (e.g., `customer_email` vs `email`). Inspect SQL or `classes/*` to confirm field names.
- Some earlier code mixes different naming conventions (customer vs user). Use `controllers/*` to find the correct place to modify when changing behavior.

Do's and Don'ts for automated edits
- Do: Preserve existing JSON response shapes and session key names. Use prepared statements when adding SQL.
- Do: Keep simple procedural action files small — delegate logic to `controllers/` and `classes/`.
- Don't: Replace `db_class.php` helpers unless you update all callers. Many classes rely on either `db_connect()` or `db_conn()` patterns.

If you need more context
- Import `db/dbforlab.sql` into a local MySQL instance to inspect table columns and constraints.
- Read `controllers/*` first to find the thin adapter between action endpoints and model logic.

If anything in this file seems off or missing for your planned change, ask for the exact feature and which files you intend to modify; I will provide targeted guidance or updates.
