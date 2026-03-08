#!/bin/bash
set -e

VERSION="${1:-$(grep "define( 'CAMPUSOS_THEME_VERSION'" wp-content/themes/campusos-academic/functions.php | grep -oP "'[0-9]+\.[0-9]+\.[0-9]+'" | tr -d "'" )}"

if [ -z "$VERSION" ]; then
    echo "Error: Could not determine version. Pass as argument: ./build.sh 1.2.2"
    exit 1
fi

echo "=== CampusOS Academic Build v${VERSION} ==="

THEME_DIR="wp-content/themes/campusos-academic"
PLUGIN_DIR="wp-content/plugins/campusos-academic-core"
DIST_DIR="dist"
TEMP_DIR="/tmp/campusos-build-$$"

# 1. Sync versions
echo "[1/6] Syncing versions to ${VERSION}..."
sed -i '' "s/^Version: .*/Version: ${VERSION}/" "${THEME_DIR}/style.css"
sed -i '' "s/define( 'CAMPUSOS_THEME_VERSION', '.*'/define( 'CAMPUSOS_THEME_VERSION', '${VERSION}'/" "${THEME_DIR}/functions.php"
sed -i '' "s/^ \* Version: .*/ * Version: ${VERSION}/" "${PLUGIN_DIR}/campusos-academic-core.php"
sed -i '' "s/define( 'CAMPUSOS_CORE_VERSION', '.*'/define( 'CAMPUSOS_CORE_VERSION', '${VERSION}'/" "${PLUGIN_DIR}/campusos-academic-core.php"
echo "   Versions synced."

# 2. Minify CSS
echo "[2/6] Minifying CSS..."
CSS_INPUT="${THEME_DIR}/assets/css/main.css"
CSS_OUTPUT="${THEME_DIR}/assets/css/main.min.css"

php -r "
\$css = file_get_contents('${CSS_INPUT}');
\$css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', \$css);
\$css = str_replace([\"\\r\\n\", \"\\r\", \"\\n\", \"\\t\"], '', \$css);
\$css = preg_replace('/\\s+/', ' ', \$css);
\$css = preg_replace('/\\s*([{}:;,>+~])\\s*/', '\\1', \$css);
\$css = str_replace(';}', '}', \$css);
\$css = trim(\$css);
file_put_contents('${CSS_OUTPUT}', \$css);
echo '   ' . round(filesize('${CSS_INPUT}')/1024, 1) . 'KB -> ' . round(strlen(\$css)/1024, 1) . 'KB' . PHP_EOL;
"

# 3. Minify JS
echo "[3/6] Minifying JS..."
JS_INPUT="${THEME_DIR}/assets/js/main.js"
JS_OUTPUT="${THEME_DIR}/assets/js/main.min.js"

php -r "
\$js = file_get_contents('${JS_INPUT}');
\$js = preg_replace('#(?<!:)//(?!/).*$#m', '', \$js);
\$js = preg_replace('!/\*.*?\*/!s', '', \$js);
\$js = preg_replace('/\\s+/', ' ', \$js);
\$js = trim(\$js);
file_put_contents('${JS_OUTPUT}', \$js);
echo '   ' . round(filesize('${JS_INPUT}')/1024, 1) . 'KB -> ' . round(strlen(\$js)/1024, 1) . 'KB' . PHP_EOL;
"

# 4. Prepare distribution directories
echo "[4/6] Preparing distribution..."
rm -rf "${TEMP_DIR}"
mkdir -p "${TEMP_DIR}/campusos-academic"
mkdir -p "${TEMP_DIR}/campusos-academic-core"
mkdir -p "${DIST_DIR}"

# 5. Copy files (excluding dev files)
echo "[5/6] Packaging..."
rsync -a \
    --exclude='.git' \
    --exclude='.gitignore' \
    --exclude='CLAUDE.md' \
    --exclude='docs/' \
    --exclude='agent-skills/' \
    --exclude='build.sh' \
    --exclude='.claude/' \
    --exclude='node_modules/' \
    --exclude='*.log' \
    "${THEME_DIR}/" "${TEMP_DIR}/campusos-academic/"

rsync -a \
    --exclude='.git' \
    --exclude='node_modules/' \
    --exclude='*.log' \
    "${PLUGIN_DIR}/" "${TEMP_DIR}/campusos-academic-core/"

# 6. Create ZIP files
echo "[6/6] Creating ZIP files..."
cd "${TEMP_DIR}"
zip -rq "${OLDPWD}/${DIST_DIR}/campusos-academic-theme-v${VERSION}.zip" "campusos-academic/"
zip -rq "${OLDPWD}/${DIST_DIR}/campusos-academic-core-v${VERSION}.zip" "campusos-academic-core/"
cd "${OLDPWD}"

rm -rf "${TEMP_DIR}"

echo ""
echo "=== Build Complete ==="
echo "Theme: ${DIST_DIR}/campusos-academic-theme-v${VERSION}.zip"
echo "Plugin: ${DIST_DIR}/campusos-academic-core-v${VERSION}.zip"
ls -lh "${DIST_DIR}/"*v${VERSION}*.zip
