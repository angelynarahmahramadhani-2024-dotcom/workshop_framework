<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\Vendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class OrderController extends Controller
{
    /**
     * Halaman pemesanan customer (tanpa login)
     */
    public function index()
    {
        $vendors = Vendor::orderBy('nama_vendor')->get(['id_vendor', 'nama_vendor']);

        return view('order.index', compact('vendors'));
    }

    /**
     * AJAX: Ambil daftar menu berdasar vendor
     */
    public function menuByVendor(Request $request): JsonResponse
    {
        $request->validate([
            'idvendor' => ['required', 'integer', 'exists:vendor,id_vendor'],
        ]);

        $menu = Menu::where('idvendor', $request->integer('idvendor'))
            ->orderBy('nama_menu')
            ->get(['idmenu', 'nama_menu', 'harga', 'path_gambar', 'idvendor']);

        return response()->json([
            'status' => 'success',
            'data' => $menu,
        ]);
    }

    /**
     * AJAX: Proses checkout pesanan + minta Snap Token dari Midtrans
     */
    public function checkout(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'idvendor' => ['required', 'integer', 'exists:vendor,id_vendor'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.idmenu' => ['required', 'integer', 'exists:menu,idmenu'],
            'items.*.jumlah' => ['required', 'integer', 'min:1'],
            'items.*.catatan' => ['nullable', 'string', 'max:255'],
        ]);

        $result = DB::transaction(function () use ($validated) {
            // Generate nama guest otomatis
            $guestName = $this->generateGuestName();

            // Ambil data menu dari database
            $menuMap = Menu::whereIn('idmenu', collect($validated['items'])->pluck('idmenu'))
                ->where('idvendor', $validated['idvendor'])
                ->get()
                ->keyBy('idmenu');

            $details = [];
            $total = 0;
            $itemDetails = [];

            foreach ($validated['items'] as $item) {
                if (!isset($menuMap[$item['idmenu']])) {
                    abort(422, 'Terdapat menu yang tidak sesuai vendor terpilih.');
                }

                $harga = (int) $menuMap[$item['idmenu']]->harga;
                $jumlah = (int) $item['jumlah'];
                $subtotal = $harga * $jumlah;

                $details[] = [
                    'idmenu' => $item['idmenu'],
                    'jumlah' => $jumlah,
                    'harga' => $harga,
                    'subtotal' => $subtotal,
                    'catatan' => $item['catatan'] ?? null,
                ];

                // Item details untuk Midtrans
                $itemDetails[] = [
                    'id' => (string) $item['idmenu'],
                    'price' => $harga,
                    'quantity' => $jumlah,
                    'name' => substr($menuMap[$item['idmenu']]->nama_menu, 0, 50),
                ];

                $total += $subtotal;
            }

            // Simpan pesanan
            $pesanan = Pesanan::create([
                'nama' => $guestName,
                'total' => $total,
                'metode_bayar' => 0, // belum dipilih, customer pilih di Snap
                'status_bayar' => 0, // pending
            ]);

            // Simpan detail pesanan
            foreach ($details as $detail) {
                DetailPesanan::create([
                    'idpesanan' => $pesanan->idpesanan,
                    'idmenu' => $detail['idmenu'],
                    'jumlah' => $detail['jumlah'],
                    'harga' => $detail['harga'],
                    'subtotal' => $detail['subtotal'],
                    'catatan' => $detail['catatan'],
                ]);
            }

            // Update payment_reference (unique per request)
            $orderId = 'ORDER-' . $pesanan->idpesanan . '-' . time();
            $pesanan->update(['payment_reference' => $orderId]);

            // ==========================================
            // KONFIGURASI MIDTRANS
            // ==========================================
            Config::$serverKey = config('services.midtrans.server_key');
            Config::$isProduction = (bool) config('services.midtrans.is_production', false);
            Config::$isSanitized = true;
            Config::$is3ds = true;
            Config::$overrideNotifUrl = config('services.midtrans.notification_url');

            // Siapkan parameter Snap
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $total,
                ],
                'customer_details' => [
                    'first_name' => $guestName,
                ],
                'item_details' => $itemDetails,
            ];

            // Minta Snap Token ke Midtrans
            try {
                $snapToken = Snap::getSnapToken($params);

                return [
                    'idpesanan' => $pesanan->idpesanan,
                    'nama' => $guestName,
                    'total' => $total,
                    'status_bayar' => $pesanan->status_bayar_label,
                    'snap_token' => $snapToken,
                    'order_id' => $orderId,
                ];
            } catch (\Exception $e) {
                // Jika Midtrans gagal, tetap return data pesanan
                return [
                    'idpesanan' => $pesanan->idpesanan,
                    'nama' => $guestName,
                    'total' => $total,
                    'status_bayar' => $pesanan->status_bayar_label,
                    'snap_token' => null,
                    'order_id' => $orderId,
                    'error' => $e->getMessage(),
                ];
            }
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.',
            'data' => $result,
        ]);
    }

    /**
     * Webhook callback dari Midtrans
     */
    public function midtransCallback(Request $request): JsonResponse
    {
        $serverKey = (string) config('services.midtrans.server_key');
        if ($serverKey === '') {
            return response()->json(['status' => 'error', 'message' => 'Server key belum diatur.'], 500);
        }

        $orderId = (string) $request->input('order_id', '');
        $statusCode = (string) $request->input('status_code', '');
        $grossAmount = (string) $request->input('gross_amount', '');
        $signatureKey = (string) $request->input('signature_key', '');
        $transactionStatus = (string) $request->input('transaction_status', '');
        $fraudStatus = (string) $request->input('fraud_status', '');
        $paymentType = (string) $request->input('payment_type', '');

        // Validasi signature
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        if (!hash_equals($expectedSignature, $signatureKey)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid signature.'], 403);
        }

        $pesanan = Pesanan::where('payment_reference', $orderId)->first();
        if (!$pesanan) {
            return response()->json(['status' => 'error', 'message' => 'Pesanan tidak ditemukan.'], 404);
        }

        $statusBayar = $this->mapMidtransStatus($transactionStatus, $fraudStatus);

        // Map metode bayar dari payment_type
        $metodeBayar = $this->mapPaymentType($paymentType);

        $updatePayload = [
            'payment_channel' => $paymentType,
            'status_bayar' => $statusBayar,
            'metode_bayar' => $metodeBayar,
        ];

        if ($statusBayar === 1) { // lunas
            $updatePayload['paid_at'] = now();
        }

        $pesanan->update($updatePayload);

        return response()->json(['status' => 'success']);
    }

    /**
     * Halaman QR Code setelah pembayaran berhasil
     */
    public function showQrCode($idpesanan)
    {
        $pesanan = Pesanan::with('detail.menu')->findOrFail($idpesanan);

        // Jika status masih pending, update ke lunas
        // (halaman ini hanya diakses setelah onSuccess dari Midtrans Snap)
        if ($pesanan->status_bayar == 0) {
            $pesanan->update([
                'status_bayar' => 1,
                'paid_at' => now(),
            ]);
            $pesanan->refresh();
        }

        // Generate QR Code sebagai SVG string
        $qrCode = QrCode::size(250)
            ->margin(2)
            ->generate((string) $pesanan->idpesanan);

        return view('order.qrcode', compact('pesanan', 'qrCode'));
    }

    /**
     * Generate nama guest otomatis: Guest_0000001, Guest_0000002, ...
     */
    private function generateGuestName(): string
    {
        $lastGuest = Pesanan::where('nama', 'like', 'Guest_%')
            ->orderByDesc('idpesanan')
            ->first();

        $nextNumber = 1;

        if ($lastGuest) {
            $suffix = (int) preg_replace('/[^0-9]/', '', (string) $lastGuest->nama);
            $nextNumber = $suffix + 1;
        }

        return 'Guest_' . str_pad((string) $nextNumber, 7, '0', STR_PAD_LEFT);
    }

    /**
     * Mapping status Midtrans → status_bayar integer
     */
    private function mapMidtransStatus(string $transactionStatus, string $fraudStatus = ''): int
    {
        if (in_array($transactionStatus, ['capture', 'settlement'], true)) {
            if ($transactionStatus === 'capture' && $fraudStatus !== 'accept') {
                return 0; // pending
            }
            return 1; // lunas
        }

        if ($transactionStatus === 'pending') {
            return 0; // pending
        }

        return 2; // gagal
    }

    /**
     * Mapping payment_type Midtrans → metode_bayar integer
     */
    private function mapPaymentType(string $paymentType): int
    {
        return match ($paymentType) {
            'bank_transfer', 'echannel' => 1, // VA
            'qris', 'gopay', 'shopeepay' => 2, // QRIS/E-Wallet
            default => 0,
        };
    }
}
