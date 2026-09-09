# Sections

Each layout section exposes 4 settings tabs accessible via the ⚙ icon in the panel.

---

## Background tab

| Field | Type | Description |
|-------|------|-------------|
| `background` | select | Background color (UIkit or custom classes) |
| `bg_custom_color` | color | Start color (when background = custom) |
| `bg_custom_gradient` | toggle | Enable gradient (when background = custom) |
| `bg_custom_color2` | color | End color of the gradient |
| `bg_gradient_dir` | select | Gradient direction |
| `bg_image` | files | Background image |
| `bg_video_enable` | toggle | Background video (replaces image) |
| `bg_video_source` | select | Source: uploaded file · external URL |
| `bg_video_file` | files | Video file |
| `bg_video_url` | url | External URL (YouTube `/embed/[id]` or direct file) |
| `text_color` | select | Text color: auto · light · dark |
| `overlay_color` | color | Overlay color on top of the background image |
| `overlay_opacity` | number | Overlay opacity (0–100) |
| `overlay_gradient` | toggle | Gradient overlay |
| `overlay_color2` | color | Overlay gradient end color |
| `overlay_gradient_dir` | select | Overlay gradient direction |

---

## Layout tab

| Field | Type | Description |
|-------|------|-------------|
| `padding` | select | Section vertical spacing (`uk-section-*`) |
| `padding_remove` | multiselect | Remove padding on specific sides: Top · Bottom · Left · Right (combinable) |
| `container` | select | Content width (`uk-container-*`) |
| `grid_valign` | select | Column vertical alignment: stretch · top · center · bottom |
| `grid_halign` | select | Column horizontal alignment |
| `grid_gap` | select | Gap between columns |
| `grid_divider` | toggle | Vertical divider between columns |
| `grid_tablet` | toggle | Apply layout from tablet (640px instead of 960px) |

---

## Effects tab

### Scrollspy (entrance animation)

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `scrollspy` | toggle | `false` | Enable scroll animation (`uk-scrollspy`) |
| `scrollspy_cls` | select | `fade` | UIkit animation class |
| `scrollspy_delay` | number | `0` | Delay in ms (0–2000) |
| `scrollspy_repeat` | toggle | `false` | Replay on every viewport entry |

Default animations:

| UIkit class | Effect |
|-------------|--------|
| `uk-animation-fade` | Fade |
| `uk-animation-scale-up` | Scale up |
| `uk-animation-slide-top-medium` | Slide from top |
| `uk-animation-slide-bottom-medium` | Slide from bottom |
| `uk-animation-slide-left-medium` | Slide from left |
| `uk-animation-slide-right-medium` | Slide from right |

Replaceable via [`scrollspy-animations`](configuration.md#scrollspy-animations) in config.

### Parallax

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `parallax` | toggle | `false` | Parallax effect on the background image |
| `parallax_speed` | number | `80` | Displacement amplitude in px |

### Shape Divider

| Field | Type | Description |
|-------|------|-------------|
| `shape_divider` | toggle | Enable shape divider |
| `shape_divider_type` | select | Shape: curve · tilt · triangle · waves · mountains… |
| `shape_divider_position` | select | Position: bottom · top · top and bottom |
| `shape_divider_color` | color | Divider color |
| `shape_divider_height` | text | Height e.g. `100px` |

---

## Advanced tab

| Field | Type | Description |
|-------|------|-------------|
| `visibility` | select | Responsive visibility: all · tablet+ · desktop+ · mobile only… |
| `section_id` | text | Anchor ID: e.g. `contact` → link `#contact` |
| `aria_label` | text | ARIA label for screen readers |
| `css_classes` | multiselect | Predefined CSS classes |
| `css_classes_custom` | text | Custom CSS classes (space-separated) |
| `eager_image` | toggle | Priority background image (above-the-fold) |
