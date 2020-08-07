# Wordsmith Changelog

All notable changes to the Wordsmith plugin will be documented in this file.

The format of this changelog is based on ["Keep a Changelog"](http://keepachangelog.com/).

This project adheres to [Semantic Versioning](http://semver.org/). Version numbers follow the pattern: `MAJOR.FEATURE.BUILD`


## 3.3.0.1 - 2020-08-07

## Improved
 
- Increased the `widont` line length threshold, to make `widont` a bit more liberal in closing up widowed lines. ([#5](https://github.com/TopShelfCraft/Wordsmith/issues/5))
- Updated the version constraint for [Stringy](https://github.com/voku/Stringy) to make Wordsmith compatible with Craft 3.5. ([#33](https://github.com/TopShelfCraft/Wordsmith/issues/33))
- _(Wordsmith 3.3.0.1 is a re-tag of version 3.3.0 with more flexible dependency constraints to avoid errors when updating to Craft 3.5.)_

## Deprecated

- The static `$plugin` accessor will be removed in v4. Use `Wordsmith::getInstance()` instead.


## 3.2.0 - 2020-04-20

### Improved

- Updated Emoji definitions to Unicode version 12.1 (October 2019).


## 3.1.2 - 2020-02-17

### Fixed

- Updated the version constraint for [Parsedown Extra](https://github.com/erusev/parsedown-extra). ([#29](https://github.com/TopShelfCraft/Wordsmith/issues/29))


## 3.1.1 - 2019-09-20

### Fixed

- Internalized the SubStringy library, to resolve dependency conflicts between different forks of Stringy.


## 3.1.0 - 2019-09-20

### Changed

- Updated Wordsmith's Stringy dependency to be consistent with [Craft's updated dependency](https://github.com/craftcms/cms/issues/4753). ([#25](https://github.com/TopShelfCraft/Wordsmith/issues/25)) 


## 3.0.5 - 2019-05-20

### Improved

- Updated the version constraint for [Parsedown Extra](https://github.com/erusev/parsedown-extra) to allow `0.8.0-beta-1`, which resolves a potential dependency version conflict with Doxter. ([#22](https://github.com/TopShelfCraft/Wordsmith/issues/22)) (Projects can use [`prefer-stable`](https://getcomposer.org/doc/04-schema.md#prefer-stable) to keep their Parsedown versions on the stable track in cases where Doxter is not also required.) 


## 3.0.4 - 2018-11-05

### Fixed

- Added legacy parameters to the `hacksaw()` method for better backwards compatibility. ([#2](https://github.com/TopShelfCraft/Wordsmith/issues/2))


## 3.0.3 - 2018-09-04

### Changed

- Add `craftcms/cms` as a requirement (Required by plugin store)

### Fixed

- Fixed order-of-loading conflicts that could generate an error when Wordsmith tried to add Twig functions/globals. ([#6](https://github.com/TopShelfCraft/Wordsmith/issues/6))
- Fixed a bug where an extra paragraph was prepended to the input of the `chop` function in paragraph mode. ([#7](https://github.com/TopShelfCraft/Wordsmith/issues/7))


## 3.0.1 - 2017-11-11

### Added

- Initial release!
