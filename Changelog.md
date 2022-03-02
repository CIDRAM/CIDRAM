## CIDRAM (Classless Inter-Domain Routing Access Manager) Changelog.

*CIDRAM adopts and adheres to [Semantic Versioning](https://semver.org/).*

### v3.0.0

*CIDRAM v3 branches from v2 at v2.8.0 (2022.02.14).*

*At the time of branching, CIDRAM's changelog was more than 2,300 lines long and was becoming difficult to read due to both its length and its format. To improve readability, I've decided to clear out the old changelogs from the v3 branch, and to switch from plain-text format to markdown format from v3 onward. The old changelogs will continue at previous branches and remain accessible from there.*

#### Backwards-incompatible changes.
- Removed support for webfonts (this expands upon other changes made in the past in response to potential legal concerns; #66).
- Removed support for INI files in favour of using just YAML files instead (this is necessary because of changes to the configuration directives available made for v3 and how those changes are intended to work for v3).
- Reorganised how CIDRAM handles L10N data.
- Configuration directives `hide_version`, `empty_fields`, `omit_ip`, `omit_hostname`, and `omit_ua` have been replaced by a new configuration directive, `fields`. The new configuration directive is capable of providing the same functionality provided previously, as well as some other, new, related functionality.
- Configuration directive `error_log_stages` has been replaced by a new configuration directive, `stages`. The new configuration directive is capable of providing the same functionality provided previously, as well as some other, new, related functionality.
- Configuration directive `statistics` has been changed from a boolean to a checkbox, to enable users to specify exactly *which* statistics they want to track. It's also now possible to track non-blocked requests (#204).

#### Other changes.
- Improved IP address resolution strategy (#286).
- Changed the `enable_apcu` default value to `true` and the `prefix` default value to `CIDRAM_`.
- Checkbox configuration directives are now delimited in the configuration by newlines instead of commas.
- The `Output` stage of the execution chain has been split into four distinct, separate stages for easier configurability and control.
- Added a mechanism to the front-end IP test and IP tracking pages to enable the copying of IPs displayed there.
