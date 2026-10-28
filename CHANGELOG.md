# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.4.0] - 2026-10-28
### Added
- Added support for `symfony/event-dispatcher` v8.
- Added PHP 8.5 into the CI matrix.

### Changed
- Minimal PHP version is 8.3 (unchanged since 1.3.0).
- Changed `roave/security-advisories` to `dev-latest`.

### Removed
- Removed dead tooling: `.php_cs.dist`, `.travis.yml` and `.scrutinizer.yml`.

### Fixed
- `ComposedEventDispatcher::getListeners()` called without an event name now also returns listeners of events that are registered only in the local event dispatcher.

## [1.3.0] - 2025-05-29
### Added
- Added support for PHP 8.4.

### Changed
- Changed minimal PHP version to `^8.3`.
- Dropped support for `symfony/event-dispatcher` v5.
- Added dev environment (docker, makefile).
- Added Php-Cs-Fixer.
- Added PHPStan.
- Added GH actions.

### Fixed
- Fixed implicitly nullable parameter of `EventDispatcherAwareTrait::dispatchEvent()` (deprecated in PHP 8.4).

## [1.2.0] - 2024-08-06
### Added
- Added support for `symfony/event-dispatcher:^7.0`.

## [1.1.0] - 2023-01-03
### Added
- Added PHP 8.1 into `composer.json`.
- Added support for `symfony/event-dispatcher` v6.

## [1.0.2] - 2022-03-06
### Added
- Added shortcut methods `EventDispatcherAwareTrait::addEventListener()` and `EventDispatcherAwareTrait::dispatchEvent()`.
- `EventDispatcherFactory` can be now autowired.

## [1.0.1] - 2021-01-28
### Added
- Added missing description into `composer.json`.

## [1.0] - 2021-01-28
### Added
- Initial release.

[Unreleased]: https://github.com/68publishers/event-dispatcher-extra/compare/v1.4.0...HEAD
[1.4.0]: https://github.com/68publishers/event-dispatcher-extra/compare/v1.3.0...v1.4.0
[1.3.0]: https://github.com/68publishers/event-dispatcher-extra/compare/v1.2.0...v1.3.0
[1.2.0]: https://github.com/68publishers/event-dispatcher-extra/compare/v1.1.0...v1.2.0
[1.1.0]: https://github.com/68publishers/event-dispatcher-extra/compare/v1.0.2...v1.1.0
[1.0.2]: https://github.com/68publishers/event-dispatcher-extra/compare/v1.0.1...v1.0.2
[1.0.1]: https://github.com/68publishers/event-dispatcher-extra/compare/v1.0...v1.0.1
[1.0]: https://github.com/68publishers/event-dispatcher-extra/releases/tag/v1.0
