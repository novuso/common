# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [2.0.0] - 2021-01-13

### Added

- Started keeping a changelog
- Added `Cache` interface for simple cache loading and reading with timeout
- Added `TokenService` class for managing web tokens
- Added `TokenEncoder` interface for encoding web tokens
- Added `TokenDecoder` interface for decoding web tokens

### Changed

- `DateTime::weekDay` returns `WeekDay` instance instead of `int` now
- `HttpClient` exceptions no longer set status codes as the exception code
    - `Exception::getStatusCode` is now supported as an alternative
- `HttpClient` interface now supports sending `options` to the implementing library
    - Leaky abstraction, but it allowed needed features to be supported

### Removed

- Removed support for PSR-18: HTTP Client.
    - Existing `HttpClient` interface is the preferred abstraction

[Unreleased]: https://github.com/novuso/common/compare/master...develop
[2.0.0]: https://github.com/novuso/common/compare/1.0.0...2.0.0
