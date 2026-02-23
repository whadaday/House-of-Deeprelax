<?php

add_action('template_redirect', function () {
    // Alleen iets doen op het juiste subdomein
    $host = $_SERVER['HTTP_HOST'] ?? '';
    if (stripos($host, 'download.houseofdeeprelax.com') === false) {
        return;
    }

    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    // Check of het een mobiel device is
    $isMobile = preg_match(
        '/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i',
        $userAgent
    );

    if (! $isMobile) {
        wp_redirect('https://houseofdeeprelax.com/app', 302);
        exit;
    }

    $lang = getLang();
    $appstores = get_field('appstore', $lang);

    // iOS (iPhone, iPad, iPod)
    if (preg_match('/iPhone|iPad|iPod/i', $userAgent)) {
        wp_redirect($appstores['apple'], 302);
        exit;

    // Android
    } else {
         wp_redirect($appstores['android'], 302);
        exit;
    }
});

add_action('template_redirect', function () {
    /// Alleen op de pagina met slug 'download-app'
    if ( ! is_page('download-app') ) {
        return;
    }

    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    // Check of het een mobiel device is
    $isMobile = preg_match(
        '/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i',
        $userAgent
    );

    if (! $isMobile) {
        wp_redirect('https://houseofdeeprelax.com/app', 302);
        exit;
    }

    $lang = getLang();
    $appstores = get_field('appstore', $lang);

    // iOS (iPhone, iPad, iPod)
    if (preg_match('/iPhone|iPad|iPod/i', $userAgent)) {
        wp_redirect($appstores['apple'], 302);
        exit;

    // Android
    } else {
         wp_redirect($appstores['android'], 302);
        exit;
    }
});