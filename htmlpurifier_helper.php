<?php

/**
 * Purify input using the HTMLPurifier standalone class.
 * Easily use multiple purifier configurations.
 *
 * @author     Tyler Brownell <tyler.brownell@mssociety.ca>, Windpie <wzf28@sina.com>,
 * @copyright  Public Domain
 *
 * @access  public
 * @param   string or array  $dirty_html  A string (or array of strings) to be cleaned.
 * @param   string           $config      The name of the configuration (switch case) to use.
 * @return  string or array               The cleaned string (or array of strings).
 */

// CodeIgniter 4 HTMLPurifier Helper

if (! function_exists('html_purify')) {
    /**
     *
     * @access  public
     * @param array|string $dirty_html      A string (or array of strings) to be cleaned.
     * @param string $config                The name of the configuration (switch case) to use.
     * @return array|string                 The cleaned string (or array of strings).
     */

    function html_purify($dirty_html, $config = false)
    {
        if (is_array($dirty_html)) {
            foreach ($dirty_html as $key => $val) {
                $clean_html[$key] = html_purify($val, $config);
            }
        } else {
            $charset = config('app')->charset;
            switch ($config) {
                case 'comment':
                    $config = \HTMLPurifier_Config::createDefault();
                    $config->set('Core.Encoding', $charset);
                    $config->set('HTML.Doctype', 'XHTML 1.0 Strict');
                    $config->set('HTML.Allowed', 'p,a[href|title],abbr[title],acronym[title],b,strong,blockquote[cite],code,em,i,strike');
                    $config->set('AutoFormat.AutoParagraph', true);
                    $config->set('AutoFormat.Linkify', true);
                    $config->set('AutoFormat.RemoveEmpty', true);
                    break;

                case false:
                    $config = \HTMLPurifier_Config::createDefault();
                    $config->set('Core.Encoding', $charset);
                    $config->set('HTML.Doctype', 'XHTML 1.0 Strict');
                    break;

                default:
                    show_error('The HTMLPurifier configuration labeled "'.htmlspecialchars($config, ENT_QUOTES, $charset).'" could not be found.');
            }

            $purifier = new \HTMLPurifier($config);
            $clean_html = $purifier->purify($dirty_html);
        }

        return $clean_html;
    }
}
