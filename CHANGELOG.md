# Wordsmith Changelog

All notable changes to the Wordsmith plugin will be documented in this file.

The format of this changelog is based on ["Keep a Changelog"](http://keepachangelog.com/).

This project adheres to [Semantic Versioning](http://semver.org/). Version numbers follow the pattern: `MAJOR.FEATURE.BUILD`


## 3.0.3 - 2018-09-04

### Changed

- Add `craftcms/cms` as a requirement (Required by plugin store)

### Fixed

- Fixed order-of-loading conflicts that could generate an error when Wordsmith tried to add Twig functions/globals. ([#6](https://github.com/TopShelfCraft/Wordsmith/issues/6))
- Fixed a bug where an extra paragraph was prepended to the input of the `chop` function in paragraph mode. ([#7](https://github.com/TopShelfCraft/Wordsmith/issues/7))


## 3.0.1 - 2017-11-11

### Added

- Initial release!
