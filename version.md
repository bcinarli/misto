Change Log
===============================
# Version 0.7
### Improvements
- Autoload support added for vendor classes which should be in the vendors folder :)
- MD support with [Michelf's](https://github.com/michelf/php-markdown) PHP Markdown & Extra added.
- md.php and md class is added for modifiying and displaying md files with meta content at the top of the file
-- Available meta contents are, comments, slug, title, date, externalCSS, externalJS, id, class, keywords, description etc.
-- Check all variables at the top of the html.php and html class

### changes
- navigation methods modified in order to print navigation not only with ul li, but also, with span or div wrapper. Also support not wrapping at all.
- setRoute method added to router in order to modify page role for external content checking and regex based url matches.

### bugfixes
- need some fixes and improvements for MD formatting.


# Version 0.6.1
### Improvements
- APP_PATH added in order to run misto app rather than the app folder.
- getPath() method added to get installation folder for the Misto app.

### changes
- Autoload function revised for having custom classes for new app supporting namespaced classes and the classic ones.
- settings.php file move to misto root from app folder.

### bugfixes
- fixed wrong plainHost detection
