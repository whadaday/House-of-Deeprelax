import * as sass from 'sass';
import postcss from 'postcss';
import autoprefixer from 'autoprefixer';
import { readFileSync, writeFileSync } from 'fs';
import { resolve, dirname } from 'path';
import { pathToFileURL } from 'url';
import { globSync } from 'glob';

const isProd = process.argv.includes('--production');

// CodeKit supported glob @imports ('theme/*.scss', '../../blocks/**/*');
// dart-sass does not. Expand them to sorted explicit imports, filtered to
// stylesheet sources (.scss/.css) so a bare '**/*' doesn't pull in .php etc.
function expandGlobs(filePath) {
  const content = readFileSync(filePath, 'utf8');
  const dir = dirname(filePath);

  return content.replace(/@import\s+['"]([^'"]*\*[^'"]*)['"]\s*;/g, (match, pattern) => {
    let files = globSync(pattern, { cwd: dir, nodir: true });
    // A pattern without an explicit extension (e.g. '../../blocks/**/*')
    // matches every file — keep only stylesheet sources.
    if (!/\.(scss|css)$/.test(pattern)) {
      files = files.filter((f) => /\.(scss|css)$/.test(f));
    }
    if (files.length === 0) {
      console.warn(`Warning: no files matched glob pattern '${pattern}' in ${dir}`);
      return `/* no files matched: ${pattern} */`;
    }
    return files.sort().map((f) => `@import '${f}';`).join('\n');
  });
}

const sassFiles = [
  { input: 'assets/stylesheets/style.scss', output: 'style.css' },
  { input: 'assets/stylesheets/style-editor.scss', output: 'style-editor.css' },
  { input: 'assets/stylesheets/style-acf.scss', output: 'assets/stylesheets/style-acf.css' },
  { input: 'assets/stylesheets/style-admin.scss', output: 'assets/stylesheets/style-admin.css' },
];

let hasError = false;

for (const { input, output } of sassFiles) {
  try {
    const expandedScss = expandGlobs(input);

    const result = sass.compileString(expandedScss, {
      url: pathToFileURL(resolve(input)),
      style: isProd ? 'compressed' : 'expanded',
      quietDeps: true,
      silenceDeprecations: ['import', 'slash-div', 'global-builtin', 'color-functions', 'mixed-decls'],
    });

    const prefixed = await postcss([autoprefixer]).process(result.css, {
      from: input,
      to: output,
      map: false,
    });

    writeFileSync(output, prefixed.css);
    console.log(`${isProd ? '[prod]' : '[dev] '} ${input} → ${output} (${(prefixed.css.length / 1024).toFixed(0)}K)`);
  } catch (err) {
    hasError = true;
    console.error(`\x1b[31mError compiling ${input}:\x1b[0m`);
    console.error(err.message);
  }
}

if (hasError) process.exit(1);
