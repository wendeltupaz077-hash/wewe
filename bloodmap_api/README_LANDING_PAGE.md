# Landing Page Implementation

This document summarizes the landing page styling, design, and interactive implementation for the BloodMap PH public homepage.

## Files involved

- `resources/views/public/home.blade.php`
  - Landing page HTML markup
  - Hero section, CTA buttons, stats cards, feature cards, escalation flow, download section
  - Includes the blood bag interactive visual component

- `public/css/smartblood.css`
  - Global site styling and landing page-specific CSS
  - Hero layout, typography, buttons, cards, animation, and responsive rules
  - Blood bag toggle visuals and state styles

- `public/js/smartblood.js`
  - Homepage interaction logic
  - Blood bag toggle state management
  - Floating-label input support used for other portal forms

## What was styled and designed

### Hero section

- Full-screen hero layout with a soft blood-themed gradient background
- Prominent headline and supporting text using responsive clamp sizing
- Primary CTA button (`Get Started`) and secondary CTA button (`Learn More`)
- Slight elevation and motion effects on buttons for a polished feel
- Large background `hero-bg` with subtle radial gradients and floating blood cell ambient effect
- Centered layout for desktop and stacked layout for mobile

### Blood bag visual toggle

- Implemented a clickable blood bag component on the hero right panel
- Uses two layered PNG images:
  - `blood-bag-empty.png`
  - `blood-bag-filled.png`
- The default state shows the empty bag image
- Toggle state fades the empty bag out and fades the filled bag in
- The wrapper includes a blurred aura and pulse rings to enhance the visual effect
- Keyboard-accessible via `Enter` or `Space` on the wrapper element
- Focus-visible styles ensure accessibility outlines appear for keyboard users

### Stats cards

- Four statistic cards display:
  - Partner Facilities
  - Registered Donors
  - Active Requests
  - Emergency Ready
- Each card includes a large numeric value and a label
- Subtle hover lift effect gives the cards a tactile feel
- Cards are responsive and stack nicely on smaller screens

### Feature section

- A grid of feature cards describing BloodMap PH capabilities
- Each card includes an emoji icon, title, and short description
- Designed for readability and quick scanning
- Responsive grid collapses nicely on tablets and mobile

### Emergency escalation flow

- Step-based escalation flow presented in a horizontal sequence
- Shows the system path from local stock to PRC chapter escalation
- Cards with icons and arrows reinforce the blood emergency workflow

### Download / app promo section

- Mobile app promo area with badge styling
- CTA button encouraging users to learn more about app availability
- Simple “Coming Soon” callout

## CSS and interaction details

### CSS locations

- General theme variables are defined in `public/css/smartblood.css`
  - `--blood-red`, `--blood-dark`, `--cream`, `--ink`, `--muted`
- Layout styling for `.hero`, `.hero-bg`, `.hero-grid`, `.hero-actions`
- Blood bag styling under `.blood-bag-wrapper`, `.blood-bag-stage`, `.blood-bag-aura`, `.blood-bag-img`
- Hover and focus transitions for interactive state changes
- Responsive breakpoints for mobile layout adjustments

### Interactive JavaScript

- `public/js/smartblood.js` manages the blood bag toggle logic:
  - toggles `.filled` class on `.blood-bag-wrapper`
  - updates `aria-pressed` for accessibility
  - listens for click and keyboard interaction
- This file also contains floating label behavior for form inputs elsewhere in the portal, ensuring consistent text field UX

## Summary of what was done

- Built the landing page hero with strong brand color, typography, and CTA placement
- Implemented the blood bag toggle interaction as a visual hero effect
- Added accessible keyboard interaction for the toggle component
- Styled responsive stats cards, feature cards, escalation flow, and download promo
- Centralized landing page appearance in `public/css/smartblood.css`
- Kept the landing page markup isolated in `resources/views/public/home.blade.php`

## Notes

- The landing page uses the main app layout from `resources/views/layouts/app.blade.php`
- The interactive blood bag is intended as a quick visual engagement element for the homepage
- Additional pages and portal forms reuse shared CSS and JS from the same `smartblood` assets
