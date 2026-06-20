# Portfolio Upgrade Status

Project sudah dipoles untuk kebutuhan portfolio Laravel e-commerce.

## Sudah diperbaiki

- Admin layout dipisahkan dari layout customer.
- Homepage, shop, product card, cart, dan checkout dibuat lebih modern.
- Admin dashboard diberi statistik revenue, pending order, dan low stock.
- Admin product page diberi search, filter category, filter status, dan low stock filter.
- Admin order page diberi search dan filter status/payment.
- Staff access bug diperbaiki dengan middleware `permission:access admin`.
- Product, category, checkout, dan order status validation dipindah ke Form Request.
- Checkout logic dipindah ke `CheckoutService`.
- Cart helper dipindah ke `CartService`.
- Stock reserve/restore dipindah ke `StockService`.
- Upload gambar produk menggunakan Laravel Storage.
- Midtrans notification wajib signature dan memakai `hash_equals`.
- Stok otomatis kembali saat order dibatalkan.
- PHPUnit config dan smoke test dasar ditambahkan.

## Catatan packaging

Untuk GitHub/portfolio, jangan upload:

- `.env`
- `vendor/`
- `database/database.sqlite`
- `storage/logs/*.log`
- file cache lokal

Gunakan `.env.example`, `composer.lock`, migration, dan seeder supaya project bisa direbuild dengan rapi.
