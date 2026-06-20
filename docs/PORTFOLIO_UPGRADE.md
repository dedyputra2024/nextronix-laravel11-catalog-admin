# Ringkasan Upgrade Portfolio

## Frontend

- Layout admin dibuat standalone agar dashboard tidak ikut navbar/footer toko.
- Homepage diganti dari copy template menjadi brand toko elektronik sungguhan.
- Product card diberi badge diskon, rating dummy, low stock badge, hover effect, dan CTA detail/cart.
- Shop page dibuat lebih rapi dengan sticky filter, result count, dan active filter chips.
- Cart dibuat card-based agar tidak terlihat seperti table mentah.
- Checkout diberi step indicator, payment method card, dan order summary sticky.

## Backend

- Middleware admin diubah menjadi permission-based agar staff bisa akses admin panel.
- Validasi dipindah ke Form Request.
- Upload image memakai `store('products', 'public')`.
- Checkout order creation dipindah ke `CheckoutService`.
- Cart calculation dipindah ke `CartService`.
- Stock checking, reserve, dan restore dipindah ke `StockService`.
- Webhook Midtrans diperketat: signature wajib dan dicek dengan `hash_equals`.
- Order cancelled mengembalikan stock secara otomatis.

## Next optional improvements

- Tambah wishlist.
- Tambah product reviews.
- Tambah upload bukti transfer untuk manual payment.
- Tambah dashboard chart bulanan.
- Deploy ke Railway/VPS/shared hosting dan tambahkan link demo di README.
