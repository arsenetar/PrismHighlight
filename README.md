PrismHighlight
===============
 Prism highlight is a syntax highlighting extension for [Mediawiki](http://www.mediawiki.org/ "MediaWiki") which uses the [Prism](http://prismjs.com/ "Prism") JavaScript syntax highlighter.

Installation
-------------
To install the extension clone the source into your extensions directory:
~~~bash
cd /path/to/wiki/extensions
git clone https://github.com/arsenetar/PrismHighlight.git 
~~~
Alternatively you may download the source via [zip](https://github.com/arsenetar/PrismHighlight/archive/master.zip).

To activate the extension open your `LocalSettings.php` file and add the following:
~~~php
require_once("$IP/extensions/PrismHighlight/PrismHighlight.php");
~~~

Configuration
--------------
The following are the configuration options for the `Localsettings.php` file ( defaults shown ):
~~~php
/**
 * Theme to use
 * options: dark, funky, okaidia, tomoorrow, twilight, false ( default )
 * /
$wgPrismHighlightTheme = false;

/**
 * Allow to highlight <source> tags
 * options: true / false
 */
$wgPrismHighlightSource = true;

/**
 * Plugins to use
 * options: autolinker, file-highlight, ie8, line-highlight, line-numbers,
 *   show-invisibles, wpd 
 * note: autolinker and show-invisibles do not appear to work for some reason
 */
$wgPrismHighlightPlugins = array(
  );

/**
 * Languages to load
 * options: bash, c, clike, coffeescript, cpp, css, css-extras, groovy, 
 *   java, javascript, markup, php, python, scss, sql
 */
$wgPrismHighlightLanguages = array(
    'bash',
    'c',
    'css',
    'javascript',
    'python',
    'php',
    'sql',
    'markup',
  );
~~~

Use
----
To use the syntax highlighter in wiki markup create the following:
~~~html
<source>
 //[...]
</source>
~~~
Place your code where `//[...]` is.

Now the following parameters may be passed to the `<source>` tag:

- `lang` or `language` - language to use (none specified uses markup)
- `data-start` - the starting line number for numbering or highlight
- `data-lines` - the lines to highlight
- `line-numbers` - used as a flag to specify line numbers should be used

### Example
~~~html
<source lang=php data-start=29 line-numbers>
    foreach( $args as $key => $value ){
        switch ($key) {
            case 'lang':
            case 'language':
                $code_classes .= " language-$value";
                break;
            case 'class':
                $code_classes .= " $value";
                break;
            case 'line-numbers':
                $pre_args .= " class=$key";
                break;
            case 'data-start':
                $pre_args .= " data-line-offset=$value"; //should make sure this is set
            default;
                $pre_args .= " $key=$value";
        }
    }  
</source>
~~~
