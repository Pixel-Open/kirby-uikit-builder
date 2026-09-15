// Finds a short preview text in the first child block that carries one (heading, text, quote, button, cta…).
function blockPreviewText(blocks) {
  try {
    const rows = Array.isArray(blocks) ? blocks : JSON.parse(blocks || "[]");
    for (const block of rows) {
      const c = block && block.content;
      const text = c && (c.text || c.heading);
      if (text) {
        const stripped = String(text).replace(/<[^>]*>/g, "").trim();
        if (stripped) return stripped.length > 60 ? stripped.slice(0, 60) + "…" : stripped;
      }
    }
  } catch (e) {}
  return null;
}

panel.plugin("pixelopen/kirby-uikit-builder", {
  blocks: {

    // ─── Button ─────────────────────────────────────────────────────────────
    button: {
      template: `
        <div style="padding:4px 0">
          <span v-if="content.text" class="uk-button uk-button-default uk-button-small" style="pointer-events:none">
            {{ content.text }}
          </span>
          <span v-else style="opacity:.4">Button…</span>
        </div>
      `
    },

    // ─── Spacer ─────────────────────────────────────────────────────────────
    spacer: {
      template: `
        <div style="display:flex;align-items:center;gap:8px;color:#999;font-size:12px;padding:4px 0">
          <span uk-icon="minus" style="width:16px;height:16px"></span>
          <span>Spacer · {{ content.height || 40 }}px</span>
        </div>
      `
    },

    // ─── Alert ──────────────────────────────────────────────────────────────
    alert: {
      computed: {
        typeColor() {
          return {
            primary: "#1e87f0",
            success: "#32d296",
            warning: "#faa05a",
            danger:  "#f0506e"
          }[this.content.alert_type] || "#1e87f0";
        }
      },
      template: `
        <div :style="'border-left:4px solid ' + typeColor + ';padding:8px 12px;background:#f8f8f8;border-radius:2px'">
          <strong v-if="content.title" style="display:block;margin-bottom:2px">{{ content.title }}</strong>
          <span v-if="content.body" v-html="content.body" style="font-size:13px"></span>
          <span v-else style="opacity:.4;font-size:13px">Alert content…</span>
        </div>
      `
    },

    // ─── CTA ────────────────────────────────────────────────────────────────
    cta: {
      template: `
        <div style="text-align:center;padding:12px 8px;background:#f8f8f8;border-radius:4px">
          <strong v-if="content.heading" style="display:block;font-size:16px;margin-bottom:4px">{{ content.heading }}</strong>
          <span v-else style="opacity:.4;display:block;margin-bottom:4px">CTA heading…</span>
          <div style="margin-top:8px;display:flex;gap:8px;justify-content:center;flex-wrap:wrap">
            <span v-if="content.btn1_label" class="uk-button uk-button-primary uk-button-small" style="pointer-events:none">{{ content.btn1_label }}</span>
            <span v-if="content.btn2_label" class="uk-button uk-button-default uk-button-small" style="pointer-events:none">{{ content.btn2_label }}</span>
          </div>
        </div>
      `
    },

    // ─── List ───────────────────────────────────────────────────────────────
    "list": {
      computed: {
        rows() {
          try {
            const v = this.content.items;
            return Array.isArray(v) ? v : (JSON.parse(v) || []);
          } catch(e) { return []; }
        },
        bullet() {
          return { bullet:"•", disc:"●", decimal:"1.", hyphen:"–", check:"✓" }[this.content.list_type] || "•";
        }
      },
      template: `
        <div style="font-size:13px">
          <div v-if="rows.length" v-for="(row, i) in rows.slice(0,4)" :key="i" style="display:flex;gap:6px;margin-bottom:2px">
            <span style="opacity:.5">{{ bullet }}</span>
            <span>{{ row.text }}</span>
          </div>
          <span v-if="!rows.length" style="opacity:.4">List items…</span>
          <span v-if="rows.length > 4" style="opacity:.5;font-size:11px">+ {{ rows.length - 4 }} more</span>
        </div>
      `
    },

    // ─── Stats ──────────────────────────────────────────────────────────────
    stats: {
      computed: {
        rows() {
          try {
            const v = this.content.items;
            return Array.isArray(v) ? v : (JSON.parse(v) || []);
          } catch(e) { return []; }
        }
      },
      template: `
        <div style="display:flex;gap:16px;flex-wrap:wrap">
          <div v-if="rows.length" v-for="(row, i) in rows" :key="i" style="text-align:center;min-width:60px">
            <div style="font-size:20px;font-weight:bold;line-height:1">
              {{ row.prefix }}{{ row.value }}{{ row.suffix }}
            </div>
            <div style="font-size:11px;opacity:.6;margin-top:2px">{{ row.label }}</div>
          </div>
          <span v-if="!rows.length" style="opacity:.4">Stats items…</span>
        </div>
      `
    },

    // ─── Pricing ────────────────────────────────────────────────────────────
    pricing: {
      computed: {
        rows() {
          try {
            const v = this.content.items;
            return Array.isArray(v) ? v : (JSON.parse(v) || []);
          } catch(e) { return []; }
        }
      },
      template: `
        <div style="display:flex;gap:8px;flex-wrap:wrap">
          <div v-if="rows.length" v-for="(row, i) in rows" :key="i"
               :style="'flex:1;min-width:80px;border:1px solid ' + (row.highlighted === 'true' ? '#1e87f0' : '#e5e5e5') + ';border-radius:4px;padding:8px;text-align:center'">
            <div style="font-size:11px;font-weight:600;opacity:.7">{{ row.name }}</div>
            <div style="font-size:16px;font-weight:bold">{{ row.price }}</div>
          </div>
          <span v-if="!rows.length" style="opacity:.4">Pricing plans…</span>
        </div>
      `
    },

    // ─── Icon box ───────────────────────────────────────────────────────────
    "icon-box": {
      computed: {
        rows() {
          try {
            const v = this.content.items;
            return Array.isArray(v) ? v : (JSON.parse(v) || []);
          } catch(e) { return []; }
        }
      },
      template: `
        <div style="display:flex;gap:12px;flex-wrap:wrap">
          <div v-if="rows.length" v-for="(row, i) in rows.slice(0,4)" :key="i" style="display:flex;flex-direction:column;align-items:center;min-width:56px;gap:4px">
            <span v-if="row.icon" :uk-icon="'icon: ' + row.icon + '; ratio: 1.5'" style="color:#1e87f0"></span>
            <span v-else style="opacity:.3;font-size:18px">□</span>
            <span style="font-size:11px;text-align:center">{{ row.title }}</span>
          </div>
          <span v-if="!rows.length" style="opacity:.4">Icon boxes…</span>
        </div>
      `
    },

    // ─── Video ──────────────────────────────────────────────────────────────
    video: {
      computed: {
        label() {
          return { youtube:"YouTube", vimeo:"Vimeo", file:"File" }[this.content.source] || "Video";
        },
        thumbnail() {
          const f = this.content.thumbnail;
          return Array.isArray(f) ? f[0] : null;
        }
      },
      template: `
        <div style="position:relative;background:#111;border-radius:4px;overflow:hidden;aspect-ratio:16/9;display:flex;align-items:center;justify-content:center">
          <img v-if="thumbnail && thumbnail.url" :src="thumbnail.url" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.6">
          <div style="position:relative;z-index:1;display:flex;flex-direction:column;align-items:center;gap:6px;color:#fff">
            <span uk-icon="icon: play-circle; ratio: 2"></span>
            <span style="font-size:12px;opacity:.8">{{ label }}</span>
            <span v-if="content.video_title" style="font-size:11px;opacity:.6">{{ content.video_title }}</span>
          </div>
        </div>
      `
    },

    // ─── Team ───────────────────────────────────────────────────────────────
    team: {
      computed: {
        rows() {
          try {
            const v = this.content.items;
            return Array.isArray(v) ? v : (JSON.parse(v) || []);
          } catch(e) { return []; }
        }
      },
      template: `
        <div style="display:flex;gap:12px;flex-wrap:wrap">
          <div v-if="rows.length" v-for="(row, i) in rows.slice(0,6)" :key="i" style="display:flex;flex-direction:column;align-items:center;gap:4px;min-width:60px">
            <div style="width:40px;height:40px;border-radius:50%;background:#e5e5e5;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:bold;color:#999">
              {{ row.name ? row.name.charAt(0).toUpperCase() : '?' }}
            </div>
            <span style="font-size:11px;text-align:center;max-width:70px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis">{{ row.name }}</span>
            <span style="font-size:10px;opacity:.5;text-align:center;max-width:70px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis">{{ row.role }}</span>
          </div>
          <span v-if="!rows.length" style="opacity:.4">Team members…</span>
          <span v-if="rows.length > 6" style="align-self:center;opacity:.4;font-size:11px">+{{ rows.length - 6 }}</span>
        </div>
      `
    },

    // ─── Logo grid ──────────────────────────────────────────────────────────
    "logo-grid": {
      computed: {
        rows() {
          try {
            const v = this.content.items;
            return Array.isArray(v) ? v : (JSON.parse(v) || []);
          } catch(e) { return []; }
        }
      },
      template: `
        <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
          <template v-if="rows.length">
            <div v-for="(row, i) in rows.slice(0,8)" :key="i"
                 style="padding:6px 10px;border:1px solid #e5e5e5;border-radius:4px;min-width:48px;display:flex;align-items:center;justify-content:center">
              <span style="font-size:11px;opacity:.6">{{ row.name || 'Logo' }}</span>
            </div>
            <span v-if="rows.length > 8" style="opacity:.4;font-size:11px">+{{ rows.length - 8 }}</span>
          </template>
          <span v-else style="opacity:.4">Logos…</span>
        </div>
      `
    },

    // ─── Accordion ──────────────────────────────────────────────────────────
    accordion: {
      computed: {
        rows() {
          try {
            const v = this.content.items;
            return Array.isArray(v) ? v : (JSON.parse(v) || []);
          } catch(e) { return []; }
        }
      },
      template: `
        <div style="font-size:13px">
          <div v-if="rows.length" v-for="(row, i) in rows.slice(0,4)" :key="i"
               style="display:flex;align-items:center;gap:8px;padding:4px 0;border-bottom:1px solid #eee">
            <span uk-icon="icon: chevron-down; ratio:.8" style="opacity:.4;flex-shrink:0"></span>
            <span>{{ row.title }}</span>
          </div>
          <span v-if="!rows.length" style="opacity:.4">Accordion items…</span>
          <span v-if="rows.length > 4" style="opacity:.4;font-size:11px">+ {{ rows.length - 4 }} more</span>
        </div>
      `
    },

    // ─── FAQ ────────────────────────────────────────────────────────────────
    faq: {
      computed: {
        rows() {
          try {
            const v = this.content.items;
            return Array.isArray(v) ? v : (JSON.parse(v) || []);
          } catch(e) { return []; }
        }
      },
      template: `
        <div style="font-size:13px">
          <div v-if="content.title" style="font-weight:bold;margin-bottom:6px">{{ content.title }}</div>
          <div v-if="rows.length" v-for="(row, i) in rows.slice(0,3)" :key="i"
               style="display:flex;align-items:center;gap:8px;padding:3px 0;border-bottom:1px solid #eee">
            <span style="opacity:.4;font-size:11px">Q</span>
            <span>{{ row.question }}</span>
          </div>
          <span v-if="!rows.length" style="opacity:.4">FAQ items…</span>
          <span v-if="rows.length > 3" style="opacity:.4;font-size:11px">+ {{ rows.length - 3 }} more</span>
        </div>
      `
    },

    // ─── Testimonial ────────────────────────────────────────────────────────
    testimonial: {
      computed: {
        rows() {
          try {
            const v = this.content.items;
            return Array.isArray(v) ? v : (JSON.parse(v) || []);
          } catch(e) { return []; }
        }
      },
      template: `
        <div style="display:flex;gap:8px;flex-wrap:wrap">
          <div v-if="rows.length" v-for="(row, i) in rows.slice(0,3)" :key="i"
               style="flex:1;min-width:120px;border-left:3px solid #e5e5e5;padding:4px 8px">
            <div style="font-size:12px;font-style:italic;opacity:.7;margin-bottom:4px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical">{{ row.quote }}</div>
            <div style="font-size:11px;font-weight:600">{{ row.author }}</div>
          </div>
          <span v-if="!rows.length" style="opacity:.4">Testimonials…</span>
        </div>
      `
    },

    // ─── Tabs ───────────────────────────────────────────────────────────────
    tabs: {
      computed: {
        rows() {
          try {
            const v = this.content.items;
            return Array.isArray(v) ? v : (JSON.parse(v) || []);
          } catch(e) { return []; }
        }
      },
      template: `
        <div>
          <div style="display:flex;gap:2px;border-bottom:2px solid #e5e5e5;margin-bottom:8px">
            <div v-if="rows.length" v-for="(row, i) in rows.slice(0,5)" :key="i"
                 :style="'padding:4px 10px;font-size:12px;border-bottom:2px solid ' + (i === 0 ? '#1e87f0' : 'transparent') + ';margin-bottom:-2px'">
              {{ row.title }}
            </div>
          </div>
          <span v-if="!rows.length" style="opacity:.4">Tab items…</span>
        </div>
      `
    },

    // ─── Gallery ────────────────────────────────────────────────────────────
    gallery: {
      computed: {
        images() {
          const f = this.content.files;
          return Array.isArray(f) ? f : [];
        }
      },
      template: `
        <div style="display:flex;gap:4px;flex-wrap:wrap">
          <template v-if="images.length">
            <div v-for="(img, i) in images.slice(0,8)" :key="i"
                 style="width:40px;height:40px;border-radius:2px;overflow:hidden;background:#e5e5e5">
              <img v-if="img.url" :src="img.url" alt="" style="width:100%;height:100%;object-fit:cover;display:block">
            </div>
            <div v-if="images.length > 8"
                 style="width:40px;height:40px;border-radius:2px;background:#e5e5e5;display:flex;align-items:center;justify-content:center;font-size:11px;color:#999">
              +{{ images.length - 8 }}
            </div>
          </template>
          <span v-else style="opacity:.4">Gallery images…</span>
        </div>
      `
    },

    // ─── Carousel ───────────────────────────────────────────────────────────
    carousel: {
      computed: {
        rows() {
          try {
            const v = this.content.items;
            return Array.isArray(v) ? v : (JSON.parse(v) || []);
          } catch(e) { return []; }
        }
      },
      template: `
        <div style="display:flex;gap:4px;overflow:hidden">
          <template v-if="rows.length">
            <div v-for="(row, i) in rows.slice(0,4)" :key="i"
                 style="flex:1;min-width:80px;border-radius:4px;overflow:hidden;background:#f0f0f0;aspect-ratio:4/3;position:relative">
              <img v-if="row.image && row.image[0]" :src="row.image[0].url" alt="" style="width:100%;height:100%;object-fit:cover;display:block">
              <div v-else style="width:100%;height:100%;display:flex;align-items:center;justify-content:center">
                <span uk-icon="image" style="opacity:.3"></span>
              </div>
              <div v-if="row.title" style="position:absolute;bottom:0;left:0;right:0;background:rgba(0,0,0,.5);color:#fff;font-size:10px;padding:2px 4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ row.title }}</div>
            </div>
            <span v-if="rows.length > 4" style="align-self:center;opacity:.4;font-size:11px;padding:0 4px">+{{ rows.length - 4 }}</span>
          </template>
          <span v-else style="opacity:.4">Carousel items…</span>
        </div>
      `
    },

    // ─── Table ──────────────────────────────────────────────────────────────
    table: {
      computed: {
        cols() {
          try {
            const v = this.content.columns;
            return Array.isArray(v) ? v : (JSON.parse(v) || []);
          } catch(e) { return []; }
        },
        rows() {
          try {
            const v = this.content.rows;
            return Array.isArray(v) ? v : (JSON.parse(v) || []);
          } catch(e) { return []; }
        }
      },
      template: `
        <div style="font-size:12px;overflow:hidden">
          <div v-if="cols.length" style="display:flex;gap:4px;border-bottom:2px solid #e5e5e5;padding-bottom:4px;margin-bottom:4px">
            <span v-for="(col, i) in cols.slice(0,5)" :key="i" style="flex:1;font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ col.label }}</span>
          </div>
          <div v-for="(row, i) in rows.slice(0,3)" :key="i" style="display:flex;gap:4px;padding:2px 0;border-bottom:1px solid #f0f0f0;opacity:.6">
            <span v-for="(col, j) in cols.slice(0,5)" :key="j" style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">-</span>
          </div>
          <span v-if="!cols.length && !rows.length" style="opacity:.4">Table…</span>
        </div>
      `
    },

    // ─── Slider ─────────────────────────────────────────────────────────────
    slider: {
      computed: {
        rows() {
          const v = this.content.slides;
          try { return Array.isArray(v) ? v : (JSON.parse(v) || []); } catch(e) { return []; }
        },
        firstImage() {
          const row = this.rows[0];
          const f = row && row.slide_image;
          return Array.isArray(f) ? f[0] : null;
        }
      },
      template: `
        <div style="position:relative;border-radius:4px;overflow:hidden;background:#111;aspect-ratio:16/9;display:flex;align-items:center;justify-content:center">
          <img v-if="firstImage && firstImage.url" :src="firstImage.url" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.7">
          <span v-if="!firstImage" uk-icon="icon: image; ratio: 1.5" style="opacity:.3;position:relative;z-index:1;color:#fff"></span>
          <div v-if="rows.length" style="position:relative;z-index:1;background:rgba(0,0,0,.6);color:#fff;font-size:11px;padding:2px 8px;border-radius:10px">
            {{ rows.length }} slide<span v-if="rows.length > 1">s</span>
          </div>
        </div>
      `
    },

    // ─── Card ───────────────────────────────────────────────────────────────
    card: {
      computed: {
        badgeColor() {
          if (this.content.card_color === "custom") return this.content.card_bg_color || "#999";
          return { "uk-card-primary": "#1e87f0", "uk-card-secondary": "#222" }[this.content.card_color] || "#999";
        },
        image() {
          const enabled = this.content.media_enable === true || this.content.media_enable === "true";
          const f = this.content.media_image;
          return enabled && Array.isArray(f) ? f[0] : null;
        },
        firstText() {
          return blockPreviewText(this.content.blocks);
        }
      },
      template: `
        <div style="display:flex;gap:8px;align-items:center">
          <div v-if="image" style="width:40px;height:40px;border-radius:4px;overflow:hidden;background:#e5e5e5;flex-shrink:0">
            <img v-if="image.url" :src="image.url" alt="" style="width:100%;height:100%;object-fit:cover;display:block">
          </div>
          <div style="font-size:12px;overflow:hidden;flex:1">
            <span v-if="content.card_badge" :style="'display:inline-block;font-size:10px;padding:1px 6px;border-radius:10px;color:#fff;margin-bottom:2px;background:' + badgeColor">{{ content.card_badge }}</span>
            <div v-if="firstText" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ firstText }}</div>
            <span v-else-if="!content.card_badge" style="opacity:.4">Card…</span>
          </div>
        </div>
      `
    },

    // ─── Media object ───────────────────────────────────────────────────────
    "media-object": {
      computed: {
        image() {
          const f = this.content.media_image;
          return Array.isArray(f) ? f[0] : null;
        },
        position() {
          return this.content.image_position || "left";
        },
        firstText() {
          return blockPreviewText(this.content.content_blocks);
        }
      },
      template: `
        <div :style="'display:flex;gap:8px;align-items:center;' + (position === 'right' ? 'flex-direction:row-reverse' : '')">
          <div style="width:48px;height:48px;border-radius:4px;overflow:hidden;background:#e5e5e5;flex-shrink:0;display:flex;align-items:center;justify-content:center">
            <img v-if="image && image.url" :src="image.url" alt="" style="width:100%;height:100%;object-fit:cover;display:block">
            <span v-else uk-icon="image" style="opacity:.3"></span>
          </div>
          <div style="font-size:12px;overflow:hidden">
            <span v-if="firstText">{{ firstText }}</span>
            <span v-else style="opacity:.4">Media object…</span>
          </div>
        </div>
      `
    },

    // ─── Timeline ───────────────────────────────────────────────────────────
    timeline: {
      computed: {
        rows() {
          const v = this.content.items;
          try { return Array.isArray(v) ? v : (JSON.parse(v) || []); } catch(e) { return []; }
        }
      },
      template: `
        <div style="font-size:12px">
          <div v-if="rows.length" v-for="(row, i) in rows.slice(0,4)" :key="i" style="display:flex;gap:8px;align-items:baseline;margin-bottom:4px">
            <span style="width:6px;height:6px;border-radius:50%;background:#1e87f0;flex-shrink:0"></span>
            <span style="opacity:.5;white-space:nowrap">{{ row.date }}</span>
            <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ row.title }}</span>
          </div>
          <span v-if="!rows.length" style="opacity:.4">Timeline items…</span>
          <span v-if="rows.length > 4" style="opacity:.5;font-size:11px">+ {{ rows.length - 4 }} more</span>
        </div>
      `
    },

  }
});
