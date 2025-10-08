# JU_Lost-Found

This is the JU Lost & Found project.

## Search feature

New: a server-side search endpoint and UI.

- UI: `home.php` now has an expanded search form (item name, person name, location, type).
- Endpoint: `php/search_items.php` accepts GET parameters:
  - `item_name` - partial match on the item name
  - `person_name` - partial match on reporter's name
  - `location` - partial match on location fields
  - `type` - `lost`, `found`, or empty for both

Example URL:
`http://localhost/JU_Lost-Found/php/search_items.php?item_name=wallet&location=TSC&type=lost`

Notes:
- The search uses parameterized queries (PDO) to avoid SQL injection.
- To run a PHP syntax check locally: `php -l php/search_items.php` (requires PHP CLI installed).
