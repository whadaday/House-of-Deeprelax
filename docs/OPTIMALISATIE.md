# House of Deeprelax — Optimalisatiebacklog

> Audit 2026-07-07 (theme v0.8.8.3) tegen de WSM-standaard:
> `wp-site-manager/docs/plan/site-optimization-standard.md`.
> Uitgevoerd met 4 parallelle lenzen (performance, PHP/queries, security,
> a11y/SEO). Status: `open` / `bezig` / `done`. Prioriteit: 🔴 hoog · 🟡 middel · ⚪ laag.
>
> Zusteraudit: `premiumbusiness/docs/OPTIMALISATIE.md` — zelfde Beam-familie,
> veel overlappende items. HoD is zwaarder (grotere bundels, meer options-reads,
> twee carousel-libs) maar heeft óók zaken die PB fout had al goed
> (options-page-capability, viewport, geen FAQ-schema-scopebug).

## Uitgangssituatie

Beam-familie thema, CodeKit-build (`config.codekit3`), **geen git-repo**
(→ geen deploy-workflow). Plugins: ACF Pro, RankMath Pro, WP Rocket,
WPForms(+ActiveCampaign), beam-popup, instagram-feed, catfolders-pro,
slick-carousel. Content-rijk: CPT's book/guide/kennisbank/landingpage +
faq-taxonomie. `main.js` **1,1 MB** / `style.css` **644 KB**, beide als één
bundel op elke pagina. LocalWP-site draaide niet tijdens de audit — payload
gemeten op bestandsgrootte, niet op gerenderde pagina.

## Randvoorwaarde

| ID | Bevinding | Fix | Prio | Status |
|----|-----------|-----|------|--------|
| HOD-00 | **Geen git-repo** in de theme-map → er is geen deploy-pad; niets kan via CI live. (PB draait op per-repo GitHub Actions rsync.) | `git init` + GitHub-repo + deploy-workflow (kopie van premiumbusiness `.github/workflows/deploy.yml`) + `DEPLOY_TARGETS`-variable. Randvoorwaarde vóór élke live-uitrol | 🔴 | **done** (remote + repo bestonden al; deploy.yml toegevoegd) |

## Bugs (los van optimalisatie — eerst fixen)

| ID | Bevinding | Locatie | Fix | Prio | Status |
|----|-----------|---------|-----|------|--------|
| HOD-01 | `print_r($fields)` echo't op de **publieke loginpagina** (debug-restant in `login_form_fields`-filter) — identiek aan PB-13 | `theme-login.php:26` | Regel verwijderen | 🔴 | **done** |
| HOD-02 | Assignment-bug + placeholder-tekst: `if ($custom_title = 'je moeder')` (toewijzing i.p.v. vergelijking, altijd true) | `theme-blocks.php:383-384` | Dode code verwijderen | 🔴 | **done** |
| HOD-03 | Ongeldige query-arg `'orderby' => 'DESC'` (moet `'order' => 'DESC'`) in related-kennisbank → sortering werkt niet | `theme-kennisbank.php:99` | `'order' => 'DESC'` (+ echte `orderby`) | 🟡 | **done** |
| HOD-04 | PHP 8.2-deprecation `"options-${lang}"` (bevestigd door linter) | `theme-options.php:36` | `"options-{$lang}"` | 🟡 | **done** |
| HOD-05 | `array_search()`-resultaat in truthy-check: bij index 0 wordt niet ge-unset (logic-bug) | `theme-navigation.php:75-76` | `$k = array_search(...); if ($k !== false) unset(...)` | ⚪ | **done** |
| HOD-06 | `$query[0]->ID` zonder empty-guard bij lege `get_posts` → PHP 8-warning | `beam-functions.php:336,351` | empty-check vóór indexering | ⚪ | **done** |

## Performance (gemeten: 1,1 MB JS + 644 KB CSS per pageload)

| ID | § Std | Bevinding | Locatie | Fix | Prio | Status |
|----|-------|-----------|---------|-----|------|--------|
| HOD-07 | 9.16 | **Geen options-cache: 85 losse `get_field(...,'theme'/$lang)`-reads/pageload** over ~37 bestanden, geen memo/transient/invalidatie | 37 bestanden; zwaartepunten `theme-colors.php` (9), `page-footer.php` (7), `theme-shortcodes.php` (7), `theme-navigation.php`, `header.php` | `hod_option()`-helper: prime top-level velden via `get_fields()` (**afwijkend van PB** — HoD heeft diep-geneste ACF-groups; per-rij-enumeratie gaf 349 subveld-rijen in de transient), transient + static memo per post_id, invalidatie op `acf/save_post`, miss-fallback naar `get_field()` voor stale/absente velden | 🔴 | **done** |
| HOD-08 | 9.16 | **`getLang()` doet ongecachte `get_field('site-lang','theme')` bij 30 call-sites** — en draait N+1 in de nav-walker (`start_lvl`/`end_lvl`/`start_el`, dus per menu-item) | `theme-languages.php:3-12`; walker `theme-navigation.php:21-49` | Static memo in `getLang()`; nav-options één keer buiten de walker ophalen | 🔴 | **done** |
| HOD-09 | 1 | CodeKit-build; `package.json` is CodeKit-starter zonder scripts | `config.codekit3`, `package.json` | Migreren naar npm/esbuild + sass (kopie van premiumbusiness `build/*.mjs`) — randvoorwaarde voor HOD-10/11/12 | 🔴 | **done** |
| HOD-10 | 4 | **Dubbele jQuery** (bundel + WP-core, geverifieerd: `jQuery.fn.jquery`=3.7.1 WP-core wint) + GSAP/ScrollMagic in bundel. (slick-carousel bleek **ongebruikt** — als dode dep verwijderd bij HOD-09; alleen Swiper is in gebruik) | `main.js`; `theme-scripts.php:8`; `package.json` | jQuery uit bundel (`dep array('jquery')`); consolideer naar één carousel-lib conditioneel; GSAP/ScrollMagic → IntersectionObserver | 🔴 | **deels done** (jQuery-dedupe + slick weg; GSAP/ScrollMagic-removal open) |
| HOD-11 | 4 | main.js zonder `defer`; render-blocking | `theme-scripts.php:8` | `wp_script_add_data(strategy defer)` — maar main.js laadt al in de footer (niet render-blocking); defer gaf geen extra winst en de globale-`$`-afhankelijkheid maakt het fragiel tot de JS gemodulariseerd is. Uitgesteld tot na GSAP/ScrollMagic-refactor | 🟡 | open |
| HOD-12 | 5.15/5.2 | Fonts via remote `@import` in `wp_head` (render-blocking) + `font-display: block` (FOIT) + geen preload/preconnect | `theme-fonts.php:17,35` | Lokaal hosten of preload+async; `swap`; preconnect + woff2-preload | 🔴 | **done** |
| HOD-13 | 5.7 | `style.css` 644 KB als één bundel incl. Swiper/slick CSS | `theme-scripts.php:7` | Library-CSS uit style.css splitsen; kritieke CSS overwegen | 🟡 | open |
| HOD-14 | 2 | Images: geen `cf_img()`/`cf_srcset()`, geen `sizes`-attribuut, JS-lazy (`data-src`) i.p.v. native, blur-placeholder als aparte HTTP-request i.p.v. base64-LQIP; `cover` 2500px-varianten | `image.php:37-107`; `theme-images.php:3-8` | CF Image Transformations + native lazy + base64 `_blur_data_url` + context-`sizes`; breakpoints cappen | 🔴 | **deels done** (base64-LQIP i.p.v. losse placeholder-HTTP-request + alt-escaping; cf_img/cf_srcset N.V.T. — HoD draait zonder Cloudflare; breakpoint-cap uitgesteld i.v.m. thumbnail-regeneratie) |
| HOD-15 | 9.8/9.9 | Geen preconnect/dns-prefetch; externe sync-scripts (sleak.chat chatbot, Instagram-feed globaal op home) | `header.php`, `footer.php:12`, `page-instagram.php` | Preconnect naar font/CDN-origins; chatbot `defer`; Instagram-feed conditioneel + lazy | 🟡 | **done** |
| HOD-16 | 9.18 | `file_get_contents()` op dezelfde SVG's meermaals per render zonder memo | `theme-shortcodes.php:221-225`, `theme-navigation.php`, `page-footer.php`, `socials.php` | `static $cache[$path] ??= file_get_contents()` of SVG-sprite | ⚪ | **done** |
| HOD-17 | 3 | ACF-embed/YouTube/Vimeo iframes eager geladen | `partials/elements/embed.php:4`, `blocks/embed/embed.php:22` | `loading="lazy"` + poster-facade | 🟡 | **done** |

## Admin & queries

| ID | § Std | Bevinding | Locatie | Fix | Prio | Status |
|----|-------|-----------|---------|-----|------|--------|
| HOD-18 | 14 | **Geen revision-tuning**: `landingpage` (flex-content CPT) kopieert alle ACF-meta per save; geen `wp_revisions_to_keep`/meta-skip | thema-breed; `functions-landingpages.php:38` | recipe-revisions-optimizer: skip revision bij alleen-meta, `wp_revisions_to_keep=1` voor pages | 🔴 | **done** |
| HOD-19 | 12.3 | **Gutenberg niet uit voor posts/pages** (`use_block_editor_for_post` ontbreekt; geen `remove_theme_support` core-block-patterns/templates) → ~50 MB zwaarder edit-screen | `custom-widgets.php:3-5` (alleen widgets) | Classic editor forceren + theme-supports strippen | 🔴 | **done** |
| HOD-20 | 9.19/9.1 | Query-hygiëne: `posts_per_page=-1` (guides, blocks, landingpage-rewrite, reviews/blog/kennisbank), geen `no_found_rows`/cache-flags, geen transient-caching op CPT-queries; N+1 in `card-blog` (per item `get_the_category`+`get_term_by`+`acf_get_attachment`) | `theme-guide.php:82`, `theme-blocks.php:397`, `functions-landingpages.php:71`, `card-blog.php:14-19` e.a. | Caps + `no_found_rows` + `_prime_post_caches()` vóór loops; 1h-transient taal-gescoped | 🟡 | **deels done** (guide no_found_rows; conditionele -1-queries + card-blog N+1 open — bewust conservatief i.v.m. pagineringsrisico) |
| HOD-21 | 13 | ACF-pipeline: geen picker-query-tuning (`post_object` in pagebuilder → N+1 AJAX), ACF JSON load-paths niet gecached (`glob()` per request), `getBlocks()` (`-1`) op elk edit-screen via `acf/prepare_field` | `theme-acf.php:103-139`, `theme-blocks.php:392-444` | §13-recipes: query-tuning-filters, JSON-paths in 12h-transient, static-select cache | 🟡 | **done** |
| HOD-22 | 12 | `set_default_page()` draait op élke `admin_head` (meerdere `get_option`/`update_field`); geen heartbeat-tuning; `$menu[4][4]` zonder `isset()` | `admin.php:229-342`, `admin.php:50-51` | Verplaats naar `after_switch_theme`/one-time flag; `heartbeat_settings`; isset-guard | 🟡 | **done** |
| HOD-23 | 13.6 | CPT's exposen onnodig REST-schema: `landingpage`/`faq`/`book`/`banner` `show_in_rest=true` | `functions-landingpages.php:54`, `theme-faq.php`, `functions-book.php:53`, `theme-banners.php:56` | `show_in_rest=false` waar geen REST nodig | ⚪ | **done** |

## Security (concretisering §7)

> Positief vooraf: de code-injectie-velden (`code-head` e.d.) staan op de
> `theme`-options-page mét `capability => 'administrator'` — de PB-fout
> (pattern a) is hier **niet** aanwezig. De `options-{lang}`/Site-options
> sub-pages vallen wél terug op `edit_posts`; daar leven de socials/contact/
> appstore/privacy-velden (bron van onderstaande punten).

| ID | Bevinding | Locatie | Risico | Fix | Prio | Status |
|----|-----------|---------|--------|-----|------|--------|
| HOD-24 | **`og:url` uit ongevalideerde `HTTP_HOST` + `REQUEST_URI`** rauw in OpenGraph-meta (§6.14-patroon) | `plugin-rankmath.php:12` | Host-header-injectie / cache-poisoning | `home_url(add_query_arg(NULL,NULL))` of `esc_url` + host-whitelist | 🟡 | **done** |
| HOD-25 | Klant-SVG's rauw via `file_get_contents` geëcho'd (stored-XSS) — 3 plekken | `benefits-grid.php:39`, `beam-functions.php:20` (showLogo), `theme-shortcodes.php:94` (logo) | Stored-XSS via klant-SVG-upload | SVG-`wp_kses`-whitelist (The-Matter `svg-kses.php` als referentie) | 🟡 | **done** |
| HOD-26 | Open-redirects via `wp_redirect()` met klant-ACF-URL's (privacy/terms-PDF, appstore) | `theme-redirects.php:12,31`, `download-app.php:28,33,62,67` | Open redirect naar externe URL | `wp_safe_redirect(esc_url_raw($url))` | 🟡 | **done** |
| HOD-27 | Ongeëscapete klant-content: `contact-info` rauwe HTML-echo, socials-URL rauw in `href` (+ `target="_blank"` zonder `rel`), auteurvelden, `data-color-text` | `theme-shortcodes.php:139,202`, `partials/elements/socials.php:16`, `article-author.php:38-49`, `header.php:82` | Stored-XSS / attribuut-injectie / tabnabbing | `wp_kses_post`/`esc_html`/`esc_url`/`esc_attr` + `rel="noopener noreferrer"` | 🟡 | **done** |
| HOD-28 | Dev-mode alleen ACF-gated (niet environment): zet `blog_public=0` (de-index) + toont grid-overlay aan álle bezoekers | `functions-dev-mode.php:6-9,16-21` | Per-ongeluk-aan in productie = de-index + dev-UI | Gate op `wp_get_environment_type() !== 'production'` / `manage_options` | 🟡 | **done** |
| HOD-29 | `nopriv` AJAX-endpoint `load_templates` met ongesanitized `$_POST['post_id']` + rauwe `echo json_encode` (nonce is wél aanwezig) | `theme-blocks.php:454,499,505` | Laag (nonce beschermt), onnodige unauth-exposure | `nopriv`-registratie weg; `absint()`; `wp_send_json()` | ⚪ | **done** (nopriv weg + current_user_can(edit_posts) + nonce; audit-r2/r3) |

## Accessibility & SEO

| ID | § Std | Bevinding | Locatie | Fix | Prio | Status |
|----|-------|-----------|---------|-----|------|--------|
| HOD-30 | 10.3 | **`*:focus-visible { outline: none }` op de universele selector** — alle focus-indicatie site-breed weg | `components/elements.scss:10-11` | Regel weg of vervangen door zichtbare focus (`outline: 2px solid`/box-shadow) | 🔴 | **done** |
| HOD-31 | 10.1 | Geen skip-to-content link | `header.php` (na `wp_body_open`) | `<a class="skip-link" href="#main">` | 🟡 | **done** |
| HOD-32 | 10.9 | Geen `prefers-reduced-motion` (GSAP/ScrollMagic onvoorwaardelijk) | `main.js`; SCSS | `matchMedia`-guard + CSS-fallback | 🟡 | **done** (CSS reduced-motion media query; veilig want reveals zijn class-toggle, content standaard zichtbaar) |
| HOD-33 | 10.4/10.6 | Menu-toggle is `<a href="#">` zonder `aria-expanded`/`aria-controls`; mobiele overlay zonder dialog-semantiek; icon-only links (socials, nav-back, scroll-indicator) zonder toegankelijke naam | `navigation.php:95-97`, `navigation-mobile.php:10`, `socials.php:16` | `<button aria-expanded>` + `role="dialog"` + `aria-label`s | 🟡 | **done** |
| HOD-34 | 10.6 | **Verwisselde app-store aria-labels**: Apple-knop zegt "Google play", Play-knop zegt "App Store"; QR-`<img>` zonder alt | `appstore.php:14,22,10` | Labels omwisselen; `alt` toevoegen | 🟡 | **done** |
| HOD-35 | 6.6 | FAQ-schema niet JSON-veilig (rauwe echo — quote/newline breekt JSON-LD) + `http://schema.org`; FAQ-taxonomiepagina mist FAQPage-schema volledig | `blocks/faq/faq.php:91,99,102`, `taxonomy-faq-category.php:62` | `wp_json_encode()`, https, schema op de taxonomie-template | 🟡 | **done** |
| HOD-36 | 6.5 | Gemiste schema-kansen: geen `Book`-schema op single-book CPT, geen `Review`/`AggregateRating` bij reviews | `single-book.php`, `functions-reviews.php` | `Book`- + `Review`-JSON-LD (SERP-verrijking) | 🟡 | **vervalt** (book-CPT heeft geen auteur/ISBN/cover-velden, reviews geen rating → geen geldige Book/Review-schema mogelijk zonder datamodel-uitbreiding) |
| HOD-37 | — | Legacy favicons (alleen shortcut-icon PNG + apple-touch); geen 32/16/SVG/webmanifest/theme-color | `theme-favicons.php:8-9` | Moderne icon-set | ⚪ | **done** |

## Opschonen

| ID | Bevinding | Locatie | Fix | Prio | Status |
|----|-----------|---------|-----|------|--------|
| HOD-38 | Grote uitgecommentarieerde blokken + `// print_r`/`// die()` verspreid; `slick-carousel`+`@tinymce/tinymce-jquery` in deps mogelijk ongebruikt | `functions-blog.php`, `theme-kennisbank.php`, `theme-acf.php`, `theme-blocks.php`; `package.json` | Opruimen bij HOD-09 (build-migratie) | ⚪ | **deels done** (80 pure debug-comment-regels verwijderd; slick/tinymce-deps al weg bij HOD-09) |

## Wat al goed is (uit de audit)

- **Options-page-capability correct** op de code-injectie-velden (PB-fout niet aanwezig).
- **Viewport correct** (`width=device-width, initial-scale=1, viewport-fit=cover`, geen zoom-blokkade).
- **FAQ-schema-scopebug van PB niet aanwezig** — de loop refereert correct per-iteratie.
- `wp_head`-cleanup grotendeels compleet (emoji/embed/block-CSS/generator/REST/oEmbed), comments volledig uit, WooCommerce conditional dequeue.
- Block-CPT correct dichtgezet (`show_in_rest=false`, supports `title+custom-fields`).
- Login-hardening (§16) grotendeels aanwezig; dynamische ACF JSON save-paths; pretty-choices; WYSIWYG media-upload uit.
- Content-images hebben ACF-`alt`; landmarks + `lang` + Polylang-fallback aanwezig.

## Aanbevolen volgorde

1. **HOD-00 git-repo + deploy-workflow** — zonder dit gaat níéts live.
2. **HOD-01…06 bugs** — klein, direct (login-lek, assignment-bug, orderby, deprecation).
3. **HOD-09 build-migratie** — randvoorwaarde voor de JS/CSS-slag.
4. **HOD-07/08 options-cache + getLang N+1** — grootste backend-winst (85 reads → cache).
5. **HOD-10/12/14 JS-sanering + fonts + images** — grootste frontend-winst (dubbele jQuery, 2 carousels, fonts, LCP).
6. **HOD-24…28 security-pass** — vóór de klant meer toegang krijgt (og:url-injectie eerst).
7. **HOD-18/19 revisions + Gutenberg-uit** — edit-ervaring.
8. **HOD-30…36 a11y + schema** — focus-ring, skip-link, Book/Review-schema.
9. **HOD-13/15/16/20/21/22, HOD-37/38** — restwerk.

## Verificatie-notitie

De LocalWP-site draaide niet tijdens de audit; payload is op bestandsgrootte
gemeten. Vóór implementatie de site in LocalWP starten voor DB/cache-gevoelige
stappen (options-cache-pariteitstest, query-tellingen) — zie de premiumbusiness-
aanpak. Volgens afspraak: alle wijzigingen eerst lokaal testen, niets pushen.

## HOD-07/08 implementatienotities

- `hod_option($name, $post_id = 'theme')` vervangt `get_field($name, 'theme')`
  en `get_field($name, $lang)` — 84 call-sites omgezet.
- **Prime via `get_fields()`, niet per-veld** (afwijkend van premiumbusiness):
  HoD's options-pages hebben diep-geneste groups (`elements_body_font-family`,
  responsive breakpoints), waardoor de PB-aanpak (SQL-enumeratie + per-rij
  `get_field`) de transient tot **349 velden** vulde met subveld-rijen die
  nooit los gelezen worden. `get_fields()` geeft alléén top-level velden
  (~23/25). Stale velden die `get_fields()` overslaat worden opgevangen door
  de **miss-fallback**: `hod_option()` valt bij een cache-miss terug op
  `get_field()` (rauwe DB-waarde), per request gememoized. Netto exact
  hetzelfde gedrag als de oude reads.
- `getLang()` gememoized (static) — deed een read per aanroep (30×, o.a. per
  nav-item in de walker → N+1). Bovendien las de oude versie `site-lang` óók
  wanneer Polylang actief was en de waarde niet gebruikt werd; nu alleen in de
  fallback-tak.
- Invalidatie: `acf/save_post` prio 20 leegt de transient per string-`post_id`.
  TTL 12h als vangnet; een options-wijziging is direct zichtbaar.
- Cache-key geversioneerd (`hod_options_v1_*`).
- **Lokaal geverifieerd 2026-07-07** (LocalWP, port 10219): pariteitstest
  **49/49** call-site-velden identiek (`get_field` vs `hod_option`, incl. via
  miss-fallback), transient 23 (theme) + 25 (nl) velden, homepage + pagina's
  renderen 0 PHP-fouten, nav/footer/socials/logo (options-driven) intact.

## HOD-09/10 implementatienotities

- **Build**: CodeKit → npm/esbuild + dart-sass (kopie van premiumbusiness
  `build/*.mjs`). 4 SCSS-entrypoints; glob-expansie incl. recursieve
  `blocks/**/*` (gefilterd op `.scss`/`.css`). main.js 1,1M → 505K → **417K**
  (na jQuery-dedupe), geminified.
- **jQuery-dedupe (HOD-10)**: jQuery uit de bundel, enqueue met `array('jquery')`
  → één jQuery (WP-core 3.7.1) i.p.v. twee. **Valkuil**: de oude gebundelde
  jQuery zette een globale `$`; WP-core draait in noConflict (geen globale `$`),
  en theme-code (navigation.js/headroom) leunt op de bare `$` buiten een
  `jQuery(fn($){})`-wrapper. Symptoom: headroom-nav kreeg geen classes (Swiper,
  wél in een wrapper, werkte). Fix: `window.$ = window.$ || window.jQuery;` als
  eerste regel van de bundel (in `build/js-build.mjs`) — herstelt het gedrag van
  vóór de dedupe. Browser-geverifieerd: nav `headroom headroom--top`, 1 jQuery.
- **slick-carousel** bleek ongebruikt → als dode dep verwijderd (alleen Swiper).
- **Gevonden bugs bij de build-strictheid**: ontbrekende `)` in een `calc()`
  (`blocks/faq/faq.scss`) die CodeKit's sass stil tolereerde; case-fout
  `debug.addindicators.js` → `addIndicators` (Linux-CI).
- **HOD-11 (defer)**: teruggedraaid — main.js laadt al in de footer (dus niet
  render-blocking), en defer gaf geen meetbare winst terwijl het door de
  globale-`$`-afhankelijkheid fragiel is. Heropenen na de GSAP/ScrollMagic →
  IntersectionObserver-refactor (dan is de bundel niet meer jQuery-globaal).

## Bewust uitgesteld (met reden)

- **HOD-13 (CSS-split Swiper/Fancybox)**: lage ROI op deze site — Swiper staat
  op vrijwel elke pagina (home heeft er 6), dus conditioneel laden bespaart
  weinig, terwijl het FOUC-risico + build-complexiteit introduceert. De echte
  CSS-omvang zit in de eigen component-/block-styles, niet de libraries.
- **GSAP/ScrollMagic → IntersectionObserver (rest van HOD-10)**: grote,
  risicovolle JS-rewrite (ScrollMagic ~200×, GSAP ~108× in de bundel) die alle
  scroll-animaties/parallax/reveals raakt. Vereist een eigen sessie met
  grondige regressietests per animatie; niet verantwoord om er even doorheen te
  jagen. De jQuery-dedupe (HOD-10-deel) is wél gedaan.
- **HOD-14 cf_img/breakpoint-cap**: cf_img N.V.T. (geen Cloudflare); cover-cap
  vereist thumbnail-regeneratie + design-review.

## Audit-rondes (3×) — 2026-07-07

Na de implementatie zijn 3 adversariële audit-rondes gedraaid (elk 3-4
parallelle lenzen: PHP-correctheid, security, a11y/render, build/JS). Elke
ronde-bevinding is geverifieerd en gefixt (commits `fix(audit-r1..r3)`).

**Ronde 1** — ving een KRITIEKE functionele regressie: de security-pass
(HOD-26) had `wp_redirect`→`wp_safe_redirect` gezet, maar `wp_safe_redirect`
weigert externe hosts → app-store/PDF-redirects stuurden bezoekers naar
`/wp-admin`. Fix: `allowed_redirect_hosts`-whitelist. Plus 12 correctheids/
a11y-fixes (AUTOSAVE dode code, ACF-cache-invalidatie, SVG-kses uitbreiding,
LQIP scheme-normalisatie, getLang-sentinel, focus-kleur, admin.js-dep) +
`.github/workflows/deploy.yml` toegevoegd (HOD-00).

**Ronde 2** — de focus-ring (HOD-30) bleek nog overal onderdrukt door
achtergebleven `outline:none`/`outline:0` in de btn/link-mixins + form-velden;
opgelost met één autoritatieve `!important`-regel. Plus: nopriv-AJAX-endpoint
(`load_templates`) dichtgezet (IDOR), systemische `esc_url`-pass (33 rauwe
ACF-URL-echo's over 26 bestanden), xlink:href-protocolfilter, nav-mobile
`<div>`→`<nav>`.

**Ronde 3** — finale validatie. Ving één CI-blocker (`package-lock.json`
out-of-sync na de deps-opschoning → `npm ci` faalt) + escaping-restanten
(CSS-vars/aria-labels/login-logo) + de nopriv-regel die in r2 niet matchte.
Alle r1/r2-fixes adversarieel bevestigd correct; build/perf/a11y volledig.

**Bewust niet gedaan** (met reden): `wp_kses_post` op het hoofd-WYSIWYG
(`text.php` — zou legit editor-embeds strippen), de brede systemische
`data-*`-esc_attr-pass (vaste ACF-keuzelijsten, laag risico), HOD-13 CSS-split,
GSAP/ScrollMagic-rewrite, HOD-14 cf_img (geen Cloudflare).