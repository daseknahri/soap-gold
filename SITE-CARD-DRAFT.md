# soapgold.getemoji.site — Soap Gold (scaffold built 2026-11-07) — DRAFT CARD

> DRAFT: written by the agent at local-build time. To be moved into
> `docs/sites/soap-gold.md` (plgin repo) and registered in `docs/sites/REGISTRY.md`
> when the site is created/deployed in the next phase. Nothing in this card means the
> site is live — it is NOT deployed yet. Concept: shortlist #1 "Soap Heritage", picked
> by the owner 2026-11-07; brand chosen under his standing branding delegation.

UK soaps heritage site for the owner's Facebook groups (~52K members: Coronation
Street News & Spoilers 11.7K; Emmerdale Fans Club 14.6K + 13.8K; Emmerdale Absolute
Spoilers & Gossips 14.4K). Repo **github.com/daseknahri/soap-gold** (TO BE CREATED —
local repo in `plgin/soap-gold/`, cloned from the dales-cobbles self-seeding repo,
fully rebranded; past-tense heritage positioning, structurally non-cannibalizing vs
Dales & Cobbles' current spoilers/news).

## Stack
Dockerfile (wordpress:php8.3-apache + wp-cli) · `docker-compose.yml` (Coolify magic
vars; SITE_URL default https://soapgold.getemoji.site, ADSENSE defaults
ca-pub-6869205417923902 / pub-6869205417923902, ADSENSE_ENABLE=0, GA_ENABLE=0,
HISTATS_ID=off) · `docker-compose.local.yml` (local QA only, port 8099,
WP_ENVIRONMENT_TYPE=local) · `docker/site-init.sh` · `seed/seed.php`
(`SG_SEED_VERSION` 1; idempotent; imports `data/bundles/*.zip` article bundles via
`wpap_publish_article`, pins `post_name` to planned slugs, unconditional rewrite
flush on every start). Plugin = **build-v9 9.48.0** (vendored from plgin `main` —
the released version; 9.48.1 on the unmerged BL-010 branch deliberately NOT
vendored), theme = viral-reader **1.9.37** + child theme **`soap-gold` 1.0.0**.
Admin user `editor`. Locale en_GB (Europe/London), admin en_US.

## Child theme facts
Brand in `wp-content/themes/soap-gold/BRAND.md`: golden vintage-TV + laurel emblem on
burgundy; tokens parchment #F7F0DC, burgundy #6E1423/#4A0E18, antique gold #C9A227,
ink #2B2118. Headlines + body Literata serif (self-hosted woff2, copied from the
sibling child theme). Category moods: Classic Storylines #6E1423, On This Day
#8C6A1D, Iconic Characters #7A3B2E, Anniversaries & Milestones #2F5D50, Sets
Locations & Tours #4A5D7E. End-of-article: reader question (`_sg_question` meta or
default), FB/WhatsApp share + More popover, next-article card + sticky bar,
unofficial-fan-site note. Ad architecture = sibling pattern but INERT at launch:
no AdSense slot IDs exist for this site yet and ADSENSE_ENABLE=0.
Brand assets wired per playbook §1.6: raster icon committed to `content/images/`,
icons/logo attached at seeding from the child theme `assets/brand/`.

## Seed categories (slugs fixed → CSS mood colours)
`classic-storylines` Classic Storylines Retold (also the default category) ·
`on-this-day` On This Day · `iconic-characters` Iconic Characters ·
`anniversaries-milestones` Anniversaries & Milestones · `sets-locations-tours`
Sets, Locations & Tours. `vr_noun=article`. Trust pages: about, contact,
privacy-policy, terms, disclaimer, editorial-policy, cookie-policy (privacy page
registered as wp_page_for_privacy_policy). Disclaimer + About carry the "unofficial
fan site, not affiliated with ITV" statement. Publishing note: publish by numeric
category ID — the `&` in two pillar names becomes `&amp;` in name matching and
duplicates (playbook §1.10). No posts seeded (no invented history); the first
batch lands via `data/bundles/`.

## Brand assets
Original AI-generated plates (no ITV stills/logos, no cast likenesses): golden-hour
cobbled village street hero → hero-1920.jpg (+ 640/768/960/1280 WebP srcset
variants); vintage-TV emblem → soap-gold-icon-512.png (+ favicon-32,
apple-touch-180); wordmark composited with Pillow (DejaVu Serif) → logo.png /
logo-light.png (+ WebP twins); og.jpg (hero + burgundy band, gold wordmark).

## Status / launch checklist
- 2026-11-07: local repo built + Docker QA (see agent report). NOT pushed, NOT
  deployed, no DNS, no Coolify app, no Facebook Page.
- Pending owner (next phase, each a separate go): create GitHub repo
  daseknahri/soap-gold + push; Hostinger DNS A `soapgold` → 85.31.239.111
  (getemoji.site zone); Coolify project + app from the repo; first Deploy click;
  AdSense stage 2 (ADSENSE_ENABLE=1) when ready — the getemoji.site domain is
  already approved, no new site submission expected; GSC property + sitemap;
  create the Soap Gold Facebook Page (RESPECT the account-level Page-creation
  cooldown — Storyline UK's Page was created 2026-11-07, so space this attempt);
  wire social auto-posting tokens if wanted; confirm contact email (seed defaults
  SITE_EMAIL/WP_ADMIN_EMAIL to daseknahri@gmail.com).

## Gotchas
Same family gotchas as the sibling cards (plugin ignores item `slug` → seed pins
`post_name`; categories by NAME duplicate on `&`; Coolify empty-env passthrough →
seed hardcodes the publisher fallback). Local QA needs Docker Desktop running;
port 8099 is this site's local port (dales 8096, storyline 8097, recipes 8098).
