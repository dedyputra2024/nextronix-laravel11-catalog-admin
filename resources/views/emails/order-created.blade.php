<p>Halo {{ $order->name }},</p>
<p>Pesanan <strong>{{ $order->order_number }}</strong> berhasil dibuat.</p>
<p>Total pembayaran: <strong>Rp {{ number_format((float)$order->total,0,',','.') }}</strong></p>
<p>Invoice PDF terlampir di email ini.</p>
<p>Terima kasih,<br>Nextronix</p>
