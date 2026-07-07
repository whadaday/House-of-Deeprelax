import { copyFileSync, existsSync, mkdirSync } from 'fs';

// Assets that live in plugins OUTSIDE this repo but are compiled into the
// theme bundles (CodeKit used relative ../../../../plugins/ paths). Locally
// (LocalWP) the plugin dirs exist and we refresh the vendored copies; on CI
// only the committed copies in assets/vendor/ are available.
const PLUGIN_ASSETS = [
  { src: '../../plugins/beam-popup/beam-popup.js', dest: 'assets/vendor/beam-popup.js' },
  { src: '../../plugins/beam-popup/beam-popup.scss', dest: 'assets/vendor/beam-popup.scss' },
];

mkdirSync('assets/vendor', { recursive: true });

for (const { src, dest } of PLUGIN_ASSETS) {
  if (existsSync(src)) {
    copyFileSync(src, dest);
    console.log(`vendor: ${src} → ${dest}`);
  } else if (existsSync(dest)) {
    console.log(`vendor: ${src} niet gevonden — committed kopie ${dest} blijft in gebruik`);
  } else {
    console.error(`\x1b[31mvendor: ${src} ontbreekt en er is geen committed kopie ${dest}\x1b[0m`);
    process.exit(1);
  }
}
