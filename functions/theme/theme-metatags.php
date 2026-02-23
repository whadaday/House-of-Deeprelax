<?php

function add_meta_tags() {
?>
  <meta name="apple-itunes-app" content="app-id=1478269557">
  <meta name="p:domain_verify" content="1e70cb608fe48aec02d905fb65fe4616"/>
  <meta name="facebook-domain-verification" content="r3roczzrfz01u64k7vp0t1kkwness1" />
<?php }

add_action('wp_head', 'add_meta_tags');