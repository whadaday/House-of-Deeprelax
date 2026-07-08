<?php
/**
 * SVG-sanitizer voor klant-geüploade SVG's die inline geëcho'd worden.
 *
 * ACF-image-velden (logo's, icoon-uploads) kunnen SVG's zijn; die worden in dit
 * thema met file_get_contents() rauw in de DOM gezet. Een SVG kan <script>,
 * event-handlers (onload=…) of foreignObject bevatten → stored-XSS. Deze helper
 * haalt de SVG door een wp_kses-allowlist die alléén tekenende elementen +
 * presentatie-attributen toestaat, en strip zo scripts/handlers/externe refs.
 *
 * Bundel-SVG's uit get_template_directory() zijn theme-eigen (niet klant-
 * beheerd) en hoeven hier NIET doorheen — zie de call-sites. Prefix "_" zodat
 * dit vóór de andere functions-bestanden laadt.
 */

if (!defined('ABSPATH')) { exit; }

// wp_kses draait de protocol-filter (javascript: strippen) alléén op de vaste
// URI-attribuutlijst; xlink:href staat daar niet in. Voeg 'm toe zodat een
// SVG-<use>/<pattern> met xlink:href="javascript:..." óók gefilterd wordt
// (fix audit r2, defense-in-depth).
add_filter('wp_kses_uri_attributes', function ($attrs) {
    $attrs[] = 'xlink:href';
    return array_unique($attrs);
});

/**
 * Sanitize een inline SVG-string tegen XSS. Retourneert '' bij lege input.
 *
 * @param string $svg Rauwe SVG-markup.
 * @return string     Ge-sanitizede SVG.
 */
function hod_kses_svg($svg) {
    if (!is_string($svg) || $svg === '') {
        return '';
    }

    // Presentatie-/geometrie-attributen die op vrijwel elk SVG-element mogen.
    $common = array(
        'class' => true, 'id' => true, 'style' => true, 'fill' => true,
        'fill-rule' => true, 'fill-opacity' => true, 'stroke' => true,
        'stroke-width' => true, 'stroke-linecap' => true, 'stroke-linejoin' => true,
        'stroke-miterlimit' => true, 'stroke-dasharray' => true, 'stroke-opacity' => true,
        'opacity' => true, 'transform' => true, 'clip-path' => true, 'clip-rule' => true,
        'x' => true, 'y' => true, 'x1' => true, 'y1' => true, 'x2' => true, 'y2' => true,
        'cx' => true, 'cy' => true, 'r' => true, 'rx' => true, 'ry' => true,
        'd' => true, 'points' => true, 'width' => true, 'height' => true,
        'viewbox' => true, 'preserveaspectratio' => true, 'xmlns' => true,
        'xmlns:xlink' => true, 'gradientunits' => true, 'gradienttransform' => true,
        'offset' => true, 'stop-color' => true, 'stop-opacity' => true,
        'aria-hidden' => true, 'aria-label' => true, 'role' => true, 'focusable' => true,
    );

    $allowed = array(
        'svg'            => $common,
        'g'              => $common,
        'path'           => $common,
        'circle'         => $common,
        'ellipse'        => $common,
        'rect'           => $common,
        'line'           => $common,
        'polyline'       => $common,
        'polygon'        => $common,
        'defs'           => $common,
        'clippath'       => $common,
        'lineargradient' => $common,
        'radialgradient' => $common,
        'stop'           => $common,
        'title'          => $common,
        'desc'           => $common,
        'use'            => $common + array('xlink:href' => true, 'href' => true),
        'text'           => $common + array('text-anchor' => true, 'font-family' => true, 'font-size' => true, 'font-weight' => true, 'letter-spacing' => true, 'dx' => true, 'dy' => true, 'dominant-baseline' => true),
        'tspan'          => $common + array('dx' => true, 'dy' => true),
        'mask'           => $common + array('maskunits' => true, 'maskcontentunits' => true),
        'pattern'        => $common + array('patternunits' => true, 'patterncontentunits' => true, 'patterntransform' => true, 'href' => true, 'xlink:href' => true),
        'symbol'         => $common,
    );

    return wp_kses($svg, $allowed);
}

/**
 * Gememoized inline-reader voor THEME-EIGEN SVG's (iconen, chevrons, sterren).
 * Voorkomt dat dezelfde file meermaals per render van schijf wordt gelezen
 * (HOD-16). Geen kses nodig: het pad is thema-intern, niet klant-beheerd.
 *
 * @param string $path Absoluut pad naar een theme-SVG.
 * @return string
 */
function hod_inline_svg($path) {
    static $cache = array();
    if (!array_key_exists($path, $cache)) {
        $cache[$path] = ($path && is_readable($path)) ? file_get_contents($path) : '';
    }
    return $cache[$path];
}
