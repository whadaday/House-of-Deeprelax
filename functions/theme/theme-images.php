<?php

add_image_size( 'placeholder', 20, 9999 );
add_image_size( 'cover', 2500, 9999 );
update_option( 'uploads_use_yearmonth_folders', 1 );

// completely disable image size threshold
add_filter( 'big_image_size_threshold', '__return_false' );

/**
 * Base64-LQIP: geeft de 20px 'placeholder'-afbeelding terug als inline
 * data-URI i.p.v. een aparte HTTP-request (HOD-14). Gecached (static memo +
 * 7d transient). Valt terug op de originele URL als base64'en niet lukt.
 * HoD draait zonder Cloudflare, dus cf_img/cf_srcset zijn niet beschikbaar —
 * dit is de toepasbare LQIP-winst.
 *
 * @param string $url URL van de placeholder-size.
 * @return string     data:-URI of (fallback) de originele URL.
 */
function hod_lqip($url) {
    if (!$url) { return ''; }
    static $memo = array();
    if (isset($memo[$url])) { return $memo[$url]; }

    $tkey   = 'hod_lqip_' . md5($url);
    $cached = get_transient($tkey);
    if ($cached !== false) { $memo[$url] = $cached; return $cached; }

    $uploads = wp_get_upload_dir();
    $path    = str_replace($uploads['baseurl'], $uploads['basedir'], $url);
    $data    = '';
    if ($path !== $url && is_readable($path)) {
        $bin = file_get_contents($path);
        if ($bin !== false) {
            $ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            $mime = $ext === 'png' ? 'image/png' : ($ext === 'webp' ? 'image/webp' : 'image/jpeg');
            $data = 'data:' . $mime . ';base64,' . base64_encode($bin);
        }
    }
    if ($data === '') { $data = $url; }

    set_transient($tkey, $data, 7 * DAY_IN_SECONDS);
    $memo[$url] = $data;
    return $data;
}
