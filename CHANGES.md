[unreleased]: https://github.com/123lens/laravel-intrapost-rest-client/compare/

[1.0.0]: https://github.com/123lens/laravel-intrapost-rest-client/releases/tag/v1.0.0
[1.0.1]: https://github.com/123lens/laravel-intrapost-rest-client/releases/tag/v1.0.1
[1.1.0]: https://github.com/123lens/laravel-intrapost-rest-client/releases/tag/v1.1.0

# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.1.0]

### Added
- `accountNumber()` method on `Builders/PickupPointsBuilder` so pickup point lookups can be scoped to a specific Intrapost account

### Changes
- Cast accountNumber to string for `Respones/Shipment`
- Rewrote the README fully in English, including the API selection guide, the Mail Piece vs. Track & Trace comparison, 
the overview of all available calls and the typical usage flow

## [1.0.1]

### Changes 
- Type definitions fixes

## [1.0.0]

### Added
- 1st release
