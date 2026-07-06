# Review Checklist

- [ ] WordPress hooks remain registered intentionally.
- [ ] Shortcode contracts remain compatible unless explicitly changed.
- [ ] Admin mutations check capability and nonce.
- [ ] AJAX endpoints verify nonce.
- [ ] Inputs are sanitized and outputs are escaped.
- [ ] User-controlled SQL is prepared or uses structured `$wpdb` methods.
- [ ] Database schema changes include compatibility or upgrade handling.
- [ ] User-facing strings use text domain `ifc-plugin`.
- [ ] Frontend CSS/JS changes preserve shortcode behavior and avoid broad theme damage.
- [ ] PHP lint and applicable checks ran or were skipped with reason.
