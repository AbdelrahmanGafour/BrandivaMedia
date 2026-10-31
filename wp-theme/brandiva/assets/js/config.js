// Runtime settings are injected by the theme (wp_localize_script → window.BV) from Appearance → Theme Options.
const BV = window.BV || {};
export const CONFIG = {
  waNumber: BV.waNumber || '',
  email: BV.email || '',
  site: BV.site || location.origin,
  tz: BV.tz || 'Africa/Cairo'
};
export const FX = Object.assign({ animations: true, smooth: true, cursor: true, preloader: true }, BV.fx || {});
export const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches || !FX.animations;
export const fine = matchMedia('(hover:hover) and (pointer:fine)').matches && FX.cursor;
