Change Log
===============================
# Version 0.9.0
### Improvements
- Local date format added while using time conversion for blog posts
- APP_PATH constant added 
- Mobile_Detect class updated and moved to `vendors` folder
- GZIP definitions in `.htaccess` file improved

### Changes
- `.md` content moved to \_posts folder for keeping clean and template only files in app folder.

### Bugfixes
- `#` removed from slug method in order to define anchor links
- Broken links when url defined as an anchor link

# Version 0.8.2
### Improvements
- Array values now supporting in meta values in article. Tag list, related articles etc storing in an array list in html::$meta variable.

### Changes
- Date time output for RSS feeds revised in order to get more proper publish date time
- Code beautifier applied to Misto classes

# Version 0.8.1
### Bugfixes
- missing url::getPath() method fixed for getting the misto app path in the server

# Version 0.8
### Improvements
- Feed class added for rss feed creation. Currently only support for atom formatted xml rss feeds

### Changes
- New meta variables added to html class. _maybe using a meta array will be more appropriate_
- getContent() method added in md class for getting formatted output instead of printing.

### Bugfixes
- Wrong url creation in navigation class while having "www" in the url fixed. 


# Version 0.7
### Improvements
- Autoload support added for vendor classes which should be in the vendors folder :)
- MD support with [Michelf's](https://github.com/michelf/php-markdown) PHP Markdown & Extra added.
- md.php and md class is added for modifiying and displaying md files with meta content at the top of the file
-- Available meta contents are, comments, slug, title, date, externalCSS, externalJS, id, class, keywords, description etc.
-- Check all variables at the top of the html.php and html class

### Changes
- navigation methods modified in order to print navigation not only with ul li, but also, with span or div wrapper. Also support not wrapping at all.
- setRoute method added to router in order to modify page role for external content checking and regex based url matches.

### Bugfixes
- need some fixes and improvements for MD formatting.


# Version 0.6.1
### Improvements
- APP_PATH added in order to run misto app rather than the app folder.
- getPath() method added to get installation folder for the Misto app.

### Changes
- Autoload function revised for having custom classes for new app supporting namespaced classes and the classic ones.
- settings.php file move to misto root from app folder.

### Bugfixes
- fixed wrong plainHost detection
