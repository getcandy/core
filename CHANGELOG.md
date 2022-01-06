# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

## [Unreleased]

### Added
- Made customers searchable via Scout
- Added Addresses relationship to the customer model

### Changed
- Customers `meta` column now uses Laravel's `casts` property and is cast to an object.

### Fixes
- Element relationship on URL changed to `element` instead of `elements` ([#24](https://github.com/getcandy/getcandy/issues/24))
- Fixed an issue where `now()->year` would return an int on single digit months, but we need to have a leading zero.
- Products and product option models now take in to account the Scout prefix, if set.

## 2.0-beta3 - 2021-12-24

### Fixes
- Fixed and issue where the meilisearch set up wasn't creating the indexes it needed if they didn't exist.

[View Changes](https://github.com/getcandy/core/compare/2.0-beta2...2.0-beta3)

## 2.0-beta2 - 2021-12-23

### Added
- Added a default collection group
### Changes
- Install command no longer publishes hub assets
### Fixes
- Default currency has `enabled` set to true.

[View Changes](https://github.com/getcandy/core/compare/2.0-beta...2.0-beta2)

## 2.0-beta - 2021-12-22

Initial release.
