import { readFileSync, writeFileSync, existsSync } from 'fs';
import { transformSync } from 'esbuild';

// Same bundle as the old CodeKit @codekit-prepend list, in the same order.
// GSAP/ScrollMagic removal + jQuery-dedupe are separate backlog items
// jQuery is NIET meer gebundeld (HOD-10): main.js hangt via de enqueue
// af van WP-core jQuery (array('jquery')). GSAP/ScrollMagic-removal blijft
// een apart item. debug.addIndicators.js: hoofdletter I (Linux-CI case-fix).
// Note: debug.addIndicators.js has a capital I — the CodeKit source used
// lowercase, which works on macOS (case-insensitive) but breaks Linux CI.
const vendors = [
  'node_modules/gsap/dist/gsap.js',
  'node_modules/scrollmagic/scrollmagic/uncompressed/ScrollMagic.js',
  'node_modules/scrollmagic/scrollmagic/uncompressed/plugins/animation.gsap.js',
  'node_modules/scrollmagic/scrollmagic/uncompressed/plugins/debug.addIndicators.js',
  'node_modules/scrollmagic/scrollmagic/uncompressed/plugins/jquery.ScrollMagic.js',
  'node_modules/headroom.js/dist/headroom.min.js',
  'node_modules/headroom.js/dist/jQuery.headroom.min.js',
  'node_modules/@fancyapps/ui/dist/fancybox/fancybox.umd.js',
  'node_modules/swiper/swiper-bundle.js',
  'assets/vendor/beam-popup.js',
  'assets/javascript/navigation.js',
];

const APP_SOURCE = 'assets/javascript/main.js';
const isProd = process.argv.includes('--production');

// Strip the leading @codekit-prepend comment block from the app source — the
// vendors above replace it. Everything from the first non-comment line stays.
function appCode() {
  const raw = readFileSync(APP_SOURCE, 'utf8');
  return raw.replace(/^(?:\s*\/\/.*\n)+/, '');
}

try {
  for (const f of vendors) {
    if (!existsSync(f)) {
      throw new Error(`Vendor file not found: ${f}\nRun 'npm install' (and 'npm run copy:vendor') first.`);
    }
  }

  // De oude gebundelde jQuery zette een globale `$`; WP-core jQuery draait in
  // noConflict (geen globale `$`). Theme-code (navigation.js/headroom) leunt op
  // de bare `$` buiten een `jQuery(fn($){})`-wrapper — herstel de alias zodat
  // het gedrag identiek blijft aan vóór de jQuery-dedupe (HOD-10).
  const jqAliasShim = 'window.$ = window.$ || window.jQuery;\n';
  const vendorCode = vendors.map((f) => readFileSync(f, 'utf8')).join('\n;\n');
  const combined = jqAliasShim + vendorCode + '\n;\n' + appCode();

  if (isProd) {
    const result = transformSync(combined, { minify: true, loader: 'js', legalComments: 'none' });
    writeFileSync('main.js', result.code);
    console.log(`[prod] main.js (${(result.code.length / 1024).toFixed(0)}K, minified)`);
  } else {
    writeFileSync('main.js', combined);
    console.log(`[dev]  main.js (${(combined.length / 1024).toFixed(0)}K)`);
  }
} catch (err) {
  console.error('\x1b[31mError building main.js:\x1b[0m');
  console.error(err.message);
  process.exit(1);
}
