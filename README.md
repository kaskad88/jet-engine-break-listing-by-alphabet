# JetEngine - break listing by alphabet.

Allow to break single listing grid into sections separated by a letter of the alphabet based on post title. Something like this:

<img width="1115" alt="Screenshot at Feb 22 13-11-34" src="https://github.com/kaskad88/jet-engine-break-listing-by-alphabet/assets/14362877/6cd3282e-eaad-4977-b3d0-d0bcec76d6ba">

Plugin works only with Query Builder, so you can break only listings where you get the posts with Query Builder.

And last note - plugin doesn't sort posts by title itself, it's only adding breaks based on comparison of posts titles. So you need to sort post by your self with Query settings.

## Setup
- Download and install the plugin,
- Define configuration constants in the end of functions.php file of your active theme,
- Add `break_alphabet` into Query ID option of Query builder (maybe changed with configuration constants):
<img width="822" alt="Screenshot at Feb 22 13-12-24" src="https://github.com/kaskad88/jet-engine-break-listing-by-alphabet/assets/14362877/f6405d63-5243-4477-8e14-55b3b762ce91">

**Note!** If you using Listing Grid in combination with JetSmartFilters, you need to set `break_alphabet` also as listing ID and filter query ID

**Allowed constants:**

- `JET_ENGINE_BREAK_ALPHABET_BY_QUERY_ID` - by default `break_alphabet`. Trigger for breaking current listing
- `JET_ENGINE_BREAK_ALPHABET_OPEN_HTML` - by default `<h4 class="jet-engine-break-listing" style="width:100%; flex: 0 0 100%;">` - opening HTML markup for a letter of the alphabet. Please note - `"style="width:100%; flex: 0 0 100%;"` is important for multi-column layout
- `JET_ENGINE_BREAK_ALPHABET_CLOSE_HTML` - by default `</h4>` - closing HTML markup
- `JET_ENGINE_BREAK_ALPHABET_BY_PROP` - by default `false` - breaks items by current object property. With this option you can break by a letter of the alphabet not only posts listings but also custom queries

Configuration example:

``` php
  define( 'JET_ENGINE_BREAK_ALPHABET_BY_PROP', 'name' );
```
