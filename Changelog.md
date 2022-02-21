## CIDRAM (Classless Inter-Domain Routing Access Manager) Changelog.

*CIDRAM adopts and adheres to [Semantic Versioning](https://semver.org/).*

### v3.0.0

*CIDRAM v3 branches from v2 at v2.8.0 (2022.02.14).*

*At the time of branching, CIDRAM's changelog was more than 2,300 lines long and was becoming difficult to read due to both its length and its format. To improve readability, I've decided to clear out the old changelogs from the v3 branch, and to switch from plain-text format to markdown format from v3 onward. The old changelogs will continue at previous branches and remain accessible from there.*

#### Backwards-incompatible changes.
- Completely removed support for webfonts.
- Completely dropped support for INI files in favour of using just YAML files instead.

#### Other changes.
- Improved IP address resolution strategy (#286).
- Changed the `enable_apcu` default value to `true` and the `prefix` default value to `CIDRAM_`.
