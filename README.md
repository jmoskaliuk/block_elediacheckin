# eLeDia Check-In Block (block_elediacheckin)

Compact sidebar companion to the
[eLeDia Check-In activity module](https://github.com/jmoskaliuk/mod_elediacheckin).
Adds a launcher button or an inline impulse card to any course sidebar or the
Moodle frontpage — so teachers and students reach Check-in content without
navigating into the activity itself.

## Features

- Displays a random impulse card (question, reflection, or quote) directly in
  the block
- Alternatively shows a compact launcher link to the associated Check-in
  activity
- Syncs with the activity's content pool in real time via the shared service
  layer (no separate DB tables)
- Configurable title and target activity per block instance
- Works on course pages, the frontpage, and My Dashboard

## Requirements

- Moodle 4.5 or later (tested up to 5.1)
- PHP 8.1+
- `mod_elediacheckin` must be installed (hard dependency)

## Installation

1. Download the latest release ZIP from
   [GitHub Releases](https://github.com/jmoskaliuk/block_elediacheckin/releases).
2. In Moodle, go to *Site administration → Plugins → Install plugins* and
   upload the ZIP.
3. Follow the on-screen upgrade prompts.
4. Add the block to any page via *Edit mode → Add a block → Check-in*.

## Dependencies

Requires `mod_elediacheckin`. Install that plugin first.

## Bug tracker

[GitHub Issues](https://github.com/jmoskaliuk/block_elediacheckin/issues)

## License

GNU GPL v3 or later — see [COPYING](https://www.gnu.org/licenses/gpl-3.0.html).

## Credits

Developed by [eLeDia GmbH](https://www.eledia.de) (info@eledia.de).
