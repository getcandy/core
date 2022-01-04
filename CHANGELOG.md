Version 2.0-beta

# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

## [Unreleased]

### Fixes
- Fixes [Issue 24](https://github.com/getcandy/getcandy/issues/24) where URL relationship is `elements` when it should be `element`
- Fixed an issue where `now()->year` would return an int on single digit months, but we need to have a leading zero.

## 2.0-beta3 - 2021-12-24

[View Changes](https://github.com/getcandy/core/compare/2.0-beta2...2.0-beta3)

### Fixes
- Fixed and issue where the meilisearch set up wasn't creating the indexes it needed if they didn't exist.

## 2.0-beta2 - 2021-12-23

[View Changes](https://github.com/getcandy/core/compare/2.0-beta...2.0-beta2)

### Added
- Added a default collection group
### Changes
- Install command no longer publishes hub assets
### Fixes
- Default currency has `enabled` set to true.

## 2.0-beta - 2021-12-22

Initial release.
