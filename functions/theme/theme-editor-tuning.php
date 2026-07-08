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

// ── HOD-18: revisions cappen ────────────────────────────────────────────
// 5 houdt een redelijk vangnet zonder de wp_posts/wp_postmeta-tabellen te
// laten ontploffen met ACF-meta-kopieën per save. Overschrijfbaar via een
// eigen filter met hogere prioriteit.
add_filter('wp_revisions_to_keep', function ($num, $post) {
    return 5;
}, 10, 2);
