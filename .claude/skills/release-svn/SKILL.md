---
name: release-svn
description: Deploy a tagged plugin release to WordPress.org Subversion (SVN) with sanity checks, asset syncing, and tag creation
---

# Deploy Plugin Release to WordPress.org SVN

Automates staging, tagging, and committing new plugin versions to the WordPress.org Subversion repository.

## Pre-flight Checklist (Run before triggering release)

1. **Working directory clean on master:**
   All release changes must be finalized, tested, merged into `master`, and tagged in Git:
   ```bash
   git checkout master
   git status  # must be clean
   ```
2. **Version synchronized:**
   All 4 locations (`wpwing-table-of-contents-block.php`, `readme.txt`, `package.json`, `block.json`) must match:
   ```bash
   make check
   ```
3. **Quality checks green:**
   ```bash
   npm run lint:js
   npm run lint:css
   npm run test:e2e
   ```
4. **Git release tag created:**
   ```bash
   git tag -a vX.Y.Z -m "Release vX.Y.Z"
   ```

## Deployment Procedure

### 1. Dry Run (Preview changes)
Always preview the pending SVN changes before committing to avoid accidental file deletions or missing assets:
```bash
make release-svn-dry
```
Verify that:
- `trunk/` includes only shippable files (`dist/` structure).
- Source directories like `assets/src/` are NOT in `trunk/`.
- `assets/` includes listing banners, icons, and screenshot assets from `wporg-assets/`.
- Newly added files are marked with `A` and removed files with `D`.

### 2. Live Deployment
When the dry-run output is verified, execute the live release:
```bash
make release-svn
```
Confirm the prompt when asked. The script will:
1. Re-verify version strings via `make check`.
2. Update the local SVN checkout in `svn/`.
3. Build fresh distribution assets via `make dist`.
4. Sync clean files to `svn/trunk/` and listing assets to `svn/assets/`.
5. Schedule missing files for deletion (`svn rm`) and new files for addition (`svn add`).
6. Copy `svn/trunk` to `svn/tags/<version>` to guarantee a clean tag without server-side path conflicts.
7. Commit changes to WordPress.org SVN with message `Release version v<version>`.

## Post-release Verification

1. Inspect the new tag online:
   `https://plugins.trac.wordpress.org/browser/wpwing-table-of-contents-block/tags/<version>`
2. Check the listing page after 5-10 minutes to verify "Stable tag" and "Last updated":
   `https://wordpress.org/plugins/wpwing-table-of-contents-block/`

## Troubleshooting

- **Path not found / out of date (`E155011` / `E160013`):**
  Never delete files from a tag that does not exist on the server. Always update `trunk/` first and create the tag directly from `trunk/` (`svn cp trunk tags/<version>`).
- **SVN authentication:**
  Subversion uses credentials cached in `~/.subversion/`. If prompted, provide your WordPress.org username and application password.
