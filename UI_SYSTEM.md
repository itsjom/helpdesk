# Design System — Neutral Minimalism

Follow this design system strictly and consistently across every component, page, and feature you build. Never deviate from it.

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
CORE PHILOSOPHY
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

- **Almost no chroma.** Hierarchy is built through typography weight, spacing, and contrast. Primary UI uses **soft charcoal** (`#2d2d2d`) instead of pure black so long reading sessions and large dark surfaces (sidebar, hero metrics) feel calmer and less stark.
- **Every element must justify its existence.** If it doesn't add meaning, remove it.
- **Whitespace is a design element**, not empty space. Be generous with it.

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
COLOR PALETTE (Strictly these only)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

- **Ink (Primary):** `#2d2d2d` — headings, primary buttons, active states, key borders (replaces harsh `#000` / `#0a0a0a`)
- **Ink hover:** `#454545` — hover state for filled primary controls (buttons, file inputs)
- **Dark Gray:** `#555555` — body text, secondary content
- **Mid Gray:** `#999999` — labels, muted text, placeholders
- **Light Gray:** `#f0f0f0` — hover states, tag backgrounds
- **Surface Gray:** `#f7f7f7` — card backgrounds, input fills
- **Border:** `#e5e5e5` — all borders and dividers
- **White:** `#ffffff` — page background, text on dark surfaces

> [!IMPORTANT]
> DO NOT introduce chromatic accent colors (e.g. blue links). Use **ink** (`#2d2d2d`) or dark gray for emphasis, dark gray for warnings, mid gray for disabled states. If red must be used for destructive actions, avoid filled solid blocks (use outlines or text only).

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
LAYOUT & TYPOGRAPHY
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

### Page Background
- background: `#ffffff`
- font-family: `'Inter', system-ui, -apple-system, sans-serif`
- font-size base: `14px`
- line-height: `1.6`

### Typography Scale
- **Page title:** font-size `24px`, font-weight `600`, color `#2d2d2d`
- **Section header:** font-size `16px`, font-weight `600`, color `#2d2d2d`
- **Body:** font-size `14px`, font-weight `400`, color `#555`
- **Small/label:** font-size `12px`, font-weight `400`, color `#999`
- **Overline:** font-size `11px`, font-weight `500`, color `#999`, uppercase, letter-spacing `0.07em`

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
COMPONENTS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

### Cards & Panels
- background: `#ffffff`
- border: `1px solid #e5e5e5`
- border-radius: `12px`
- padding: `20px 24px`
- No shadows unless absolutely needed — prefer border over shadow.
- If shadow is needed: `box-shadow: 0 1px 4px rgba(0,0,0,0.06)` only.

### Inputs (Text, Select, Textarea)
- background: `#f7f7f7`
- border: `1px solid #e5e5e5`
- border-radius: `8px`
- padding: `10px 14px`
- font-size: `14px`
- color: `#2d2d2d`
- **On focus:** background `#fff`, border-color `#2d2d2d`, outline: `none`
- **Placeholder:** color `#999`

### Buttons
- **Primary:** background `#2d2d2d`, color `#fff`, border-radius `8px`, padding `9px 20px`, font-weight `500`
  - *On hover:* background `#454545`
- **Secondary:** background `#fff`, color `#2d2d2d`, border `1px solid #2d2d2d`, border-radius `8px`, padding `9px 20px`
  - *On hover:* background `#f7f7f7`
- **Ghost:** background `transparent`, color `#555`, border `1px solid #e5e5e5`
  - *On hover:* background `#f7f7f7`, border-color `#ccc`
- **Danger:** background `transparent`, color `red`, border `1px solid red` (Avoid using filled solid color when using red).

### Ticket Status Badges
- **Open:** background `#f0f0f0`, color `#555`, border `1px solid #e0e0e0`
- **In Progress:** background `#2d2d2d`, color `#ffffff`, border `none`
- **Resolved:** background `#f0f0f0`, color `#999`, border `1px solid #e0e0e0`
- **Closed:** background `#f7f7f7`, color `#bbb`, border `1px solid #efefef`
- **Critical:** background `#2d2d2d`, color `#ffffff`, border `none`
- **High:** background `#f0f0f0`, color `#333`, border `1px solid #ccc`
- **All Badges:** border-radius `20px`, padding `3px 12px`, font-size `11px`, font-weight `500`

### Metric Stat Cards
- **Light:** background `#f7f7f7`, border-radius `10px`, padding `16px`, label `#999`, number `#2d2d2d`
- **Dark:** background `#2d2d2d`, border-radius `10px`, padding `16px`, label `rgba(255,255,255,0.45)`, number `#fff` (Use sparingly)

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
TABLES & LISTS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

- Table has no background — sits on white
- **Header row:** background `#f7f7f7`, font-size `11px`, color `#999`, uppercase, letter-spacing `0.06em`, padding `10px 16px`
- **Data row:** padding `14px 16px`, border-bottom `1px solid #f0f0f0`
- **Row hover:** background `#fafafa`
- **Cell text:** font-size `13px`, color `#2d2d2d` for primary, `#999` for secondary

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
CONSISTENCY RULES (Always follow these)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

1. **Minimal color** — avoid blue links (use underline + #2d2d2d instead). When using red, avoid filled solid colors.
2. **Hierarchy comes from weight and size only.**
3. **Every surface is white or #f7f7f7** — nothing else.
4. **Sharp Edges Only:** Border-radius must be `0px` for all elements (cards, inputs, buttons, badges). Avoid any smooth or rounded edges.
5. **Borders are always #e5e5e5** — never darker or colored.
6. **Spacing is always multiples of 4px.**
7. When in doubt, **add more whitespace.**
8. **No decorative elements**, illustrations, or gradients — ever.
