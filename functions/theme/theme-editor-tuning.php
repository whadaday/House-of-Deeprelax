<?php
/**
 * Editor- en revisie-tuning (HOD-18/19).
 *
 * De classic-editor-plugin zet Gutenberg al uit voor het bewerken; de filters
 * hier zijn defense-in-depth én stoppen twee dingen die de plugin niet dekt:
 * de remote block-pattern-directory-fetch (elke editor-load) en de registratie
 * van core-block-patterns/FSE-template-support. Daarnaast worden revisies
 * gecapt — de flex-content CPT's (landingpage) kopiëren álle ACF-meta naar elke
 * revision, wat de DB onbeperkt laat groeien.
 */

if (!defined('ABSPATH')) { exit; }

// ── HOD-19: Gutenberg / block-patterns uit ──────────────────────────────
add_filter('use_block_editor_for_post', '__return_false', 100);
add_filter('use_block_editor_for_post_type', '__return_false', 100);
// Voorkomt de HTTP-fetch naar de wordpress.org pattern-directory bij elke
// editor-/REST-load.
add_filter('should_load_remote_block_patterns', '__return_false');

add_action('after_setup_theme', function () {
    remove_theme_support('core-block-patterns');
    remove_theme_support('block-templates');
    remove_theme_support('block-template-parts');
}, 20);

// ── HOD-18/24: revisions optimaliseren (recipe-revisions-optimizer, §14) ──
// De count-cap alléén hield de DB NIET klein: WP kopieert bij elke save álle
// ACF-flexcontent-meta naar de revision (~1800 meta-rijen/save op contentrijke
// pagina's — de wp_postmeta-tabel liep zo naar honderden MB). Drie
// samenwerkende filters lossen dat op zonder preview te breken:
//   1. cap het aantal revisions per type (vangnet);
//   2. sla revision-creatie over als alléén meta wijzigde (niet de content);
//   3. houd postmeta volledig uit revisions (de echte bottleneck).
// Zie docs/plan/recipe-revisions-optimizer.md.

// Alle post-types met het 'content'-flexfield (zie beam_blocks-location in
// theme-blocks.php). Buiten deze lijst blijft WP-default gedrag intact.
function hod_revisioned_flex_types() {
    return array('page', 'block', 'guide', 'book', 'landingpage');
}

// 1. Max # revisions per type. 5 is nu goedkoop: er gaat geen meta meer mee.
add_filter('wp_revisions_to_keep', function ($num, $post) {
    if (!$post) { return $num; }
    return in_array($post->post_type, hod_revisioned_flex_types(), true) ? 5 : $num;
}, 10, 2);

// 2. Skip de revision-creatie als alleen ACF-meta wijzigde, niet de
//    content-kolommen. Bespaart de dure meta-kopie bij elke autosave/update.
add_filter('wp_save_post_revision_post_has_changed', function ($changed, $last_revision, $post) {
    if (!in_array($post->post_type, hod_revisioned_flex_types(), true)) {
        return $changed;
    }
    foreach (array('post_title', 'post_content', 'post_excerpt') as $f) {
        if ($last_revision->$f !== $post->$f) {
            return true; // echte content-wijziging → revision toestaan (preview werkt)
        }
    }
    return false; // alléén meta veranderde → geen nieuwe revision
}, 10, 3);

// 3. Kopieer geen postmeta naar revisions. Dit is de bottleneck-fix. Gevolg:
//    de ACF-"Revisions"-UI is leeg voor deze types — bewust; de content zit in
//    post_content/JSON, niet per-revision in meta.
add_filter('wp_post_revision_meta_keys', function ($keys) {
    global $post;
    if ($post && in_array($post->post_type, hod_revisioned_flex_types(), true)) {
        return array();
    }
    return $keys;
}, 100);

// ── HOD-22: heartbeat rustiger ──────────────────────────────────────────
// Beperkt de admin-ajax-heartbeat (WPForms/ACF-zware edit-screens).
add_filter('heartbeat_settings', function ($settings) {
    $settings['interval'] = 60; // seconden (WP staat 15-120 toe); rustiger dan default
    return $settings;
});
// NB: AUTOSAVE_INTERVAL kan NIET vanuit het thema — core definieert 'm in
// wp_functionality_constants() (wp-settings.php) vóór setup_theme/admin_init.
// Zet 'm desgewenst in wp-config.php: define('AUTOSAVE_INTERVAL', 120);
