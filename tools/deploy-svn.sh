#!/usr/bin/env bash
set -euo pipefail

# Deploy to WordPress.org SVN
# Usage:
#   bash tools/deploy-svn.sh [--dry-run]

DRY_RUN=false
for arg in "$@"; do
	case "$arg" in
		--dry-run)
			DRY_RUN=true
			;;
	esac
done

PLUGIN_SLUG="wpwing-table-of-contents-block"
SVN_URL="https://plugins.svn.wordpress.org/${PLUGIN_SLUG}"
SVN_DIR="svn"
SRC_DIR="src"
WPORG_ASSETS_DIR="wporg-assets"

echo "=== WordPress.org SVN Deployment ==="

# 1. Verify svn CLI is available
command -v svn >/dev/null 2>&1 || { echo "Error: svn command not found." >&2; exit 1; }

# 2. Check version consistency
echo "Verifying version strings..."
make check

VERSION=$(grep 'Version:' "${SRC_DIR}/${PLUGIN_SLUG}.php" | grep -o '[0-9]\+\.[0-9]\+\.[0-9]\+' | head -1)
echo "Target version: ${VERSION}"

# 3. Ensure local SVN checkout exists and is updated
if [ ! -d "${SVN_DIR}/.svn" ]; then
	echo "Checking out SVN repository into ${SVN_DIR}..."
	svn checkout "${SVN_URL}" "${SVN_DIR}"
else
	echo "Updating SVN repository..."
	svn update "${SVN_DIR}"
fi

# 4. Build fresh assets and staging files
echo "Building clean distribution..."
make dist

# 5. Sync shippable files to svn/trunk
echo "Syncing files to ${SVN_DIR}/trunk/..."
mkdir -p "${SVN_DIR}/trunk"
rsync -rc --delete --exclude-from=.distignore "${SRC_DIR}/" "${SVN_DIR}/trunk/"

# 6. Sync wporg assets (banners, icons, screenshots) to svn/assets if present
if [ -d "${WPORG_ASSETS_DIR}" ]; then
	echo "Syncing assets to ${SVN_DIR}/assets/..."
	mkdir -p "${SVN_DIR}/assets"
	rsync -rc "${WPORG_ASSETS_DIR}/" "${SVN_DIR}/assets/"
fi

# 7. Update SVN tracking (handle removed and added files)
echo "Updating SVN tracking status..."
cd "${SVN_DIR}"

# Remove deleted files from SVN
(svn status trunk assets 2>/dev/null | grep '^\!' || true) | awk '{print $2}' | xargs -r svn rm

# Add newly created files to SVN
(svn status trunk assets 2>/dev/null | grep '^\?' || true) | awk '{print $2}' | xargs -r svn add

# 8. Create tag from updated trunk
if [ -d "tags/${VERSION}" ]; then
	echo "Notice: tags/${VERSION} already exists in SVN."
else
	echo "Creating tag: tags/${VERSION}..."
	svn cp trunk "tags/${VERSION}"
fi

# 9. Review SVN status
echo ""
echo "=== Pending SVN Changes ==="
svn status
echo "==========================="

if [ "${DRY_RUN}" = true ]; then
	echo ""
	echo "Dry-run mode: Reverting local SVN working copy changes..."
	svn revert -R .
	echo "Local SVN working copy clean. No changes were committed."
	exit 0
fi

# 10. Commit to WordPress.org
echo ""
read -r -p "Commit version ${VERSION} to WordPress.org SVN? (y/N) " confirm
if [[ "${confirm}" =~ ^[Yy]$ ]]; then
	svn commit -m "Release version v${VERSION}"
	echo ""
	echo "Successfully released v${VERSION} to WordPress.org SVN!"
else
	echo "Release aborted by user."
	exit 1
fi
