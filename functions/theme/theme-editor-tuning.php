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

// ── HOD-18/24: revisions ────────────────────────
// Revisies staan AAN voor ÁLLE content-types, mét inhoud (de ACF-velden gaan
// mee), en zijn globaal begrensd via `define('WP_POST_REVISIONS', 3)` in
// wp-config. Zo kan de klant overal terugrollen, maar loopt geen post ooit vol
// (was 117/pagina, ~1800 meta-rijen/save). Bewuste keuze van de klant:
// revisie-geschiedenis boven een maximaal-lichte DB.
//
// Er staan hier GEEN per-type lean-filters meer (die strippten de ACF-meta uit
// revisions → geschiedenis onbruikbaar). De eenmalige cleanup van de oude
// opgeblazen historie is al gedraaid; de cap houdt 'm voortaan begrensd.
// Zie docs/plan/recipe-revisions-optimizer.md + §14.

// ── HOD-22: heartbeat rustiger ──────────────────────────────────────────
// Beperkt de admin-ajax-heartbeat (WPForms/ACF-zware edit-screens).
add_filter('heartbeat_settings', function ($settings) {
    $settings['interval'] = 60; // seconden (WP staat 15-120 toe); rustiger dan default
    return $settings;
});
// NB: AUTOSAVE_INTERVAL kan NIET vanuit het thema — core definieert 'm in
// wp_functionality_constants() (wp-settings.php) vóór setup_theme/admin_init.
// Zet 'm desgewenst in wp-config.php: define('AUTOSAVE_INTERVAL', 120);
