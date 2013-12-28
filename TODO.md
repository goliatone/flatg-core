### Use Events.
Hook up events so that we can add different content parsers, markdown, 
tag replacer, etc.
Also, before/after rendering, so we can a apply oauth, include variables
globally, etc.
Event for after sync, if new content. So we can recreate index for search
and for suggested posts.

### Create plugin structure
So we can register a plugin, that can 
a) modify content
b) add a route, ie: /api/search/:term
   and register an event listener for 'sync.complete'
c) SEO plugin, handle post before rendering, so we can
   add metadata: description and keywords.


### Make Config object. Save/Update config opts.
Create configurator object, read from config file, and
initialize modules with the properties defined there.
Config::set('flatg.base_path', __DIR__); //Configures main object
Config::set('router.default_url', 'home'); //configures router prop

We need a way to update configuration options. IE: Update
theme, project name, active backend. Some should be 
done on install. Some should be available through 
the overall lifecycle of the site.   

### Implement FlatG::use('markdown', Markdown);
Lazy load modules. Really enforce FlatG to be a facade.
FlatG::markdown()->render($content);

### Collections
Add `Collector`s:
- Glob directory (path) for file extension
- Make index for cache
- Create collection with file => Model

NotesCollector => notes/
PagesCollector => pages/
ImagesCollector => images/

### Drafts:
How do we deal with drafts?
Do we have different folders per collection type?
Maybe just one drafts folder, and between date and type 
meta then on publish we can move to right path.

### Implement a version check.
Have a version.txt file on repo or flat-g.com
Ping file from installed packages, and notify if new version.

### Have a env detection. 
PROD / DEV

### Instalation process.
Finish up instalation process. Have a page that diagnoses the setup.

### Debug, general.
Implement logger. Implement error page that outputs error message and
system information to detect/diagnose.

### Implement simple IOC?

### Pages: Build menu from dir structure. 

### Asset manager and publisher
Move assets to public folder. When we register a plugin,
we can publish assets to folder.

### Responses.
Make http module. Router, Response, HttpCodes.
Separate module.

### Event Dispatcher: includes
Find way to autoload event handlers, so that we can register
plugin listeners without loading the module.

### Theme:
Add theme info file. Preview. Manager. How do we 
select active theme? We need to update configuration.

### Error Handling. 
Define how we handle errors and which ones should be 
server errors (ie 500) and which ones should be app errors (ie no config).

### Development mode:
A) Investigate built in PHP server. Can we map
http://localhost:2323 to mysite.lc?
If so, create an installer so that we can use
PHP server, node/Grunt for theme and make symlink
from myproject/app to www/mysite.lc
Ideally, we could use a Vagrant build to distribute
as build environment, and test there.

### File Cache.
Detect if route has a valid html cache file.
Add event listener to 'sync.complete' and purge
cache or rebuild.
We could take the further and make the whole site
_partially_ static(?)

### Decouple file handling, use File helper.

### Partials
How do we implement partials? We want to have partials
such as _header / _footer / _ganalytics

