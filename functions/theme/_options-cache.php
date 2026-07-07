<?php
/**
 * Cached access to ACF options pages ('theme' + language pages like 'nl').
 *
 * Instead of a separate get_field($name, 'theme') lookup per field (each one
 * hits two wp_options rows: the value and the field-key reference), all saved
 * fields of an options page are loaded once and stored in a transient +
 * in-request static memo. The transient is cleared whenever the options page
 * is saved, so edits are visible immediately.
 *
 * The cache is primed per field via get_field() — NOT via get_fields() —
 * because get_fields() silently skips fields whose definition no longer
 * resolves (values saved by a since-changed field group), while get_field()
 * falls back to the raw stored value. Call sites relied on that fallback.
 *
 * This file is prefixed with "_" so the glob-include in functions.php loads
 * it before any other functions file uses hod_option().
 */

if (!defined('ABSPATH')) { exit; }

define('HOD_OPTIONS_TTL', 12 * HOUR_IN_SECONDS);
define('HOD_OPTIONS_VER', 'v1'); // bump to invalidate previously primed caches

/**
 * All saved fields of one options page, from static memo -> transient -> DB.
 *
 * @param string $post_id Options page post_id ('theme', 'nl', ...).
 * @param bool   $flush   Internal: drop the static memo for this post_id.
 * @return array
 */
function hod_options($post_id = 'theme', $flush = false) {
    static $memo = array();

    if ($flush) {
        unset($memo[$post_id]);
        return array();
    }

    if (isset($memo[$post_id])) {
        return $memo[$post_id];
    }

    $key    = 'hod_options_' . HOD_OPTIONS_VER . '_' . $post_id;
    $fields = get_transient($key);

    if (!is_array($fields)) {
        $fields = hod_options_build($post_id);
        set_transient($key, $fields, HOD_OPTIONS_TTL);
    }

    $memo[$post_id] = $fields;

    return $fields;
}

/**
 * Build the full field map for an options page. One query enumerates the
 * saved field names (a value row "{post_id}_{name}" paired with an ACF
 * reference row "_{post_id}_{name}"); each value then loads through
 * get_field() so formatting matches the old per-call behaviour exactly.
 *
 * @param string $post_id Options page post_id.
 * @return array
 */
function hod_options_build($post_id) {
    // Prime met get_fields(): dat geeft alléén de TOP-LEVEL velden terug,
    // geformatteerd — geen repeater-/group-subveld-rijen. Op dit content-rijke
    // thema scheelt dat een transient van honderden subveld-rijen (349) t.o.v.
    // ~40 echte velden. get_fields() slaat velden over waarvan de definitie
    // niet meer resolvet (stale velden), maar dat is precies waar de
    // hod_option()-miss-fallback voor is: die valt terug op get_field() (rauwe
    // DB-waarde). Netto identiek gedrag aan de oude per-veld get_field-reads,
    // getest op pariteit. Zie docs/OPTIMALISATIE.md HOD-07.
    $fields = function_exists('get_fields') ? get_fields($post_id) : false;

    return is_array($fields) ? $fields : array();
}

/**
 * Drop-in replacement for get_field($name, 'theme') / get_field($name, $lang).
 *
 * @param string $name    Field name.
 * @param string $post_id Options page post_id.
 * @return mixed
 */
function hod_option($name, $post_id = 'theme') {
    // Before acf/init field groups are not registered yet (some functions
    // files read options at include time) — priming the cache then would
    // store unformatted values. Fall through to a plain uncached get_field().
    if (!did_action('acf/init')) {
        return function_exists('get_field') ? get_field($name, $post_id) : null;
    }

    $options = hod_options($post_id);

    if (array_key_exists($name, $options)) {
        return $options[$name];
    }

    // Field exists in the group but was never saved (e.g. a default value)
    // -> not in the enumerated cache. Resolve once per request.
    static $miss = array();
    $miss_key = $post_id . ':' . $name;
    if (!array_key_exists($miss_key, $miss)) {
        $miss[$miss_key] = get_field($name, $post_id);
    }

    return $miss[$miss_key];
}

/**
 * Clear the cache when an options page is saved. Priority 20: ACF persists
 * the submitted values at priority 10, so the next read re-primes fresh.
 */
add_action('acf/save_post', function ($post_id) {
    // Options pages have string post_ids ('theme', 'nl', ...); regular posts
    // are numeric. Deleting a transient that does not exist is a no-op.
    if (is_numeric($post_id)) {
        return;
    }

    delete_transient('hod_options_' . HOD_OPTIONS_VER . '_' . $post_id);
    hod_options($post_id, true);
}, 20);
