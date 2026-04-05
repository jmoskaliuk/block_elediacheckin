# block_elediacheckin

Compact sidebar companion to `mod_elediacheckin`. Shows either a launcher link to a
Check-in activity or a mini question generator directly in the block.

## Dependencies

Requires `mod_elediacheckin` to be installed — the block reuses that plugin's service
layer (`\mod_elediacheckin\local\service\question_provider`) and does not query any
database tables directly, per concept section 10.3 and Appendix C.

## Installation

Copy this directory to `blocks/elediacheckin/` in your Moodle installation.

## License

GNU GPL v3 or later.
