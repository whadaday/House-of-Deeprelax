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
// Model = identiek aan de referentie steffy.nl ("werkt goed"):
//   • Globale cap WP_POST_REVISIONS = 3 in wp-config → geen enkele post loopt
//     ooit vol (was 117/pagina, ~1800 meta-rijen/save).
//   • De drie lean-filters hieronder draaien ALLÉÉN op 'page' — het zwaarste
//     flex-type dat we bewust zonder revisie-geschiedenis houden. Alle andere
//     content-types (landingpage/book/guide/…) vallen terug op normale WP-
//     revisies MÉT inhoud (gecapt op 3), zodat de klant dáár wél kan
//     terugrollen. Zónder deze scoping zag je overal 0 revisies.
// De filters: 1) cap 'page' op 1; 2) skip revisie als alléén ACF-meta wijzigde;
// 3) houd postmeta uit 'page'-revisions (de bloat-bottleneck).
// Zie docs/plan/recipe-revisions-optimizer.md.

// Alléén 'page' krijgt de lean-behandeling (net als steffy.nl). Andere types
// houden normale, gecapte revisies mét inhoud.
function hod_revisioned_flex_types() {
    return array('page');
}

// 1. Cap 'page' op 1 revisie (preview blijft werken); goedkoop want geen meta.
add_filter('wp_revisions_to_keep', function ($num, $post) {
    if (!$post) { return $num; }
    return in_array($post->post_type, hod_revisioned_flex_types(), true) ? 1 : $num;
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
