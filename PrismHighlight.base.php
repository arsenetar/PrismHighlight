<?php
if ( ! defined( 'MEDIAWIKI' ) )
    die();
/**#@+
 * A code highlighter using the prismjs.com code highlighter 
 * (c) 2013 Andrew Senetar <arsenetar@gmail.com>
 *
 * @file 
 * @link http://
 * @ingroup Extensions
 * @author Andrew Senetar <arsenetar@gmail.com>
 * @copyright (c) 2013 by Andrew Senetar
 * @license http://opensource.org/licenses/MIT MIT License 
 */

/**
 * Implements the Prismjs code highlighting extension
 * 
 * @author Andrew Senetar
 */

 class PrismHighlight {
    
    public static function parserHook($text, $args = array(), $parser) {
        $code_classes = '';
        $pre_args ='';
        if(!isset($args['lang']) || !isset($args['language']))
            $args['lang'] = 'markup';
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

        # Replace all '&', '<,' and '>' with their HTML entitites. Order is
        # important. You have to do '&' first.
        # not sure if this helps us
        $text = str_replace('&', '&amp;', $text);
        $text = str_replace('<', '&lt;', $text);
        $text = str_replace('>', '&gt;', $text);
        $text = trim($text); // trim any begining / end whitespace as it is ugly

        return"<pre $pre_args><code class=\"$code_classes\">$text</code></pre>";
    }
    
    // load modules
    public static function beforePageDisplay(&$wgOut, &$sk) {
        $wgOut->addModules('ext.PrismHighlight');
        $wgOut->addModules('ext.PrismHighlight.core');
        // Continue
        return true;
    }
 }
