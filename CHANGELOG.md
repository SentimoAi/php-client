# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

### Changed
- (List any improvements or changes made that are not new features)

### Deprecated
- (List any features or functionality that are deprecated)

### Removed
- (List any features or functionality that have been removed)

### Fixed
- (List any bug fixes)

### Security
- (List any security fixes)

## [1.2.0] - 2025-05-02

### Changed
- Use api key authentication instead of JWT token for the Client class.

## [1.1.4] - 2025-01-15

### Fixed
- Fix pagination when fetching reviews.

### Changed
- Change response handling when fetching reviews due to api response changes.

## [1.1.3] - 2025-01-14

### Fixed
- Fix Typo in Param request Builder.

## [1.1.2] - 2024-12-11

### Fixed
- Fix Changelog content.

## [1.1.1] - 2024-12-11

### Fixed
- Fix refresh token retrieval in the Client class.

## [1.1.0] - 2024-09-12

### Changed
- Add compatibility with symfony 7.

## [1.0.1] - 2024-09-03

### Changed
- Add compatibility with symfony/dependency-injection 5.4 and symfony/config 5.4.

### Fixed
- Fix price type in the Product data model.

## [1.0.0] - 2024-09-02
### Added
- Initial release of the Sentimo PHP Client SDK.
- Implemented `Client` class for interacting with the Sentimo API.
- Support for posting and retrieving reviews.
- `ClientFactory` for easy client instantiation.
- Error handling with `LocalizedException`.
- Methods `getReviews` and `postReviews` in `Client` class.
- `ReviewGetRequestParamBuilder` and `ReviewPostRequestParamBuilder` for flexible API requests.
