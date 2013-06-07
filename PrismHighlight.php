<?php
if ( ! defined( 'MEDIAWIKI' ) )
    die();
/**#@+
 * A code highlighter using the prismjs.com code highlighter 
 *
 * @file 
 * @link http://
 * @ingroup Extensions
 * @author Andrew Senetar <arsenetar@gmail.com>
 * @copyright (c) 2013 by Andrew Senetar
 * @license http://opensource.org/licenses/MIT MIT License 
 */

$wgExtensionCredits['other'][] = array(
    'path' => __FILE__,
    'name' => 'PrismHighlight',
    'version' => 0.1,
    'author' => 'Andrew Senetar',
    //'url' => 'http://',
    'descriptionmsg' =>'prismhighlight-desc', 
);

/**
 * Configuration variables for the extension
 */
 
 // Default languages to load
if (! isset($wgPrismHighlightLanguages)) {
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
}

// List of plugins to load (none by default)
// autolinker does not appear to work nor does show-invisibles 
// line-number and line-highlight should not be used together since it breaks visual
if (! isset($wgPrismHighlightPlugins)) {
  $wgPrismHighlightPlugins = array(
  );
}

// Allow highlighting source tags
if (! isset($wgPrismHighlightSource)) {
  $wgPrismHighlightSource = true;
}

// The theme to use (false for default)
if (! isset($wgPrismHighlightTheme)) {
  $wgPrismHighlightTheme = false;
}

// Build the list for the js includes
function efPrismHighlight_Scripts() {
    global $wgPrismHighlightPlugins, $wgPrismHighlightLanguages;
    
    // some languages depend on others
    $plugin_deps = array(
        'bash' => 'clike',
        'c' => 'clike',
        'coffeescript' => 'javascript',
        'cpp' => 'c',
        'groovy' => 'clike',
        'java' => 'clike',
        'javascript' => 'clike',
        'php' => 'clike',
        'scss' => 'css',  
    );
    
    // make sure all needed languages are loaded
    $langs = $wgPrismHighlightLanguages;
    foreach($langs  as $lang ){
        if( array_key_exists( $lang, $plugin_deps ) )
            $langs[] = $plugin_deps[$lang];
    }
    // remove duplicates order matters...
    $langs = array_unique( array_reverse( $langs ) );
    
    // load plugins
    foreach( $wgPrismHighlightPlugins as $plugin )
        $scripts[] = "prism/plugins/prism-$plugin.js";
        
    // load languages
    foreach( $langs as $lang )  
        $scripts[] = "prism/components/prism-$lang.js";
    return $scripts;    
}

// build list of styles to include
function efPrismHighlight_Styles() {
    global $wgPrismHighlightPlugins, $wgPrismHighlightTheme;
    
    // stop mediawiki from causing problems
    $styles[] = 'overrides.css';
    
    // load the theme or not
    if( $wgPrismHighlightTheme != false )
        $styles[] = "prism/themes/prism-$wgPrismHighlightTheme.css";
    else
        $styles[] = "prism/prism.css";
        
    // load plugin styles
    foreach($wgPrismHighlightPlugins as $plugin)
        $styles[] = "prism/plugins/prism-$plugin.css";
    return $styles;    
}

/**
 *  Setup for the extension
 */
$wgAutoloadClasses['PrismHighlight'] = dirname(__FILE__) . '/PrismHighlight.base.php';
$wgExtensionMessagesFiles['PrismHighlight'] = dirname(__FILE__) . '/PrismHighlight.i18n.php';

/**
 * Resource Modules
 */
 
 // styles and scripts to load after core is in place
$wgResourceModules['ext.PrismHighlight'] = array(
    'localBasePath' => dirname(__FILE__),
    'remoteExtPath' => 'PrismHighlight',
    'styles' => efPrismHighlight_Styles(),
    'scripts' => efPrismHighlight_Scripts(),
    'dependencies' => 'ext.PrismHighlight.core',
);

// the core
$wgResourceModules['ext.PrismHighlight.core'] = array(
    'localBasePath' => dirname(__FILE__),
    'remoteExtPath' => 'PrismHighlight',
    'scripts' => array( 'prism/prism-core.js', 'init.js' ),
);

/**
 * Hooks
 */
// Register parser hook
$wgHooks['ParserFirstCallInit'][] = 'efPrismHighlight_Setup';
// Register before display hook to load css / js files
$wgHooks['BeforePageDisplay'][] = 'PrismHighlight::beforePageDisplay';

/**
 * Register parser hook
 */
function efPrismHighlight_Setup( &$parser ) {
    global $wgPrismHighlightSource;
    if( $wgPrismHighlightSource)
        $parser->setHook('source', array('PrismHighlight', 'parserHook'));
    $parser->setHook('syntaxhighlight', array('PrismHighlight', 'parserHook'));
    return true;
}
