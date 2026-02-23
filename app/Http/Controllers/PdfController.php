<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    /**
     * Generate PDF Sertifikat (Landscape A4)
     */
    public function sertifikat(Request $request)
    {
        $data = [
            'nama' => $request->get('nama', 'Angelyna Rahmah Ramadhani'),
            'peran' => $request->get('peran', 'Panitia'),
            'kegiatan' => $request->get('kegiatan', 'AI, IoT dan Teknologi yang Mengubah Dunia'),
            'penyelenggara' => $request->get('penyelenggara', 'Himpunan Mahasiswa D4 Teknik Informatika'),
            'institusi' => $request->get('institusi', 'Universitas Airlangga'),
            'tanggal' => $request->get('tanggal', now()->format('d F Y')),
            'nomor_sertifikat' => 'SERT/' . date('Y') . '/' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
            'dekan_nama' => $request->get('dekan_nama', 'Prof. Dr. Anwar Maruf, drh., M.Kes'),
            'dekan_nip' => $request->get('dekan_nip', '196509051993031004'),
            'kaprodi_nama' => $request->get('kaprodi_nama', 'Fitri Retrialisca, S.Kom., M.Kom.'),
            'kaprodi_nip' => $request->get('kaprodi_nip', '199303302018083201'),
            'ketua_nama' => $request->get('ketua_nama', 'Angelyna Rahmah Ramadhani'),
            'ketua_nim' => $request->get('ketua_nim', '434241006'),
        ];

        $pdf = Pdf::loadView('pdf.sertifikat', $data)
                  ->setPaper('a4', 'landscape');

        return $pdf->stream('sertifikat.pdf');
    }

    /**
     * Generate PDF Undangan (Portrait A4 dengan Header)
     */
    public function undangan(Request $request)
    {
        $data = [
            'nama_penerima' => $request->get('nama', 'Yth. Bapak/Ibu'),
            'perihal' => $request->get('perihal', 'Undangan Rapat Koordinasi'),
            'tanggal_acara' => $request->get('tanggal', now()->addDays(7)->format('d F Y')),
            'waktu' => $request->get('waktu', '09:00 WIB'),
            'tempat' => $request->get('tempat', 'Ruang Rapat Lt. 2 Gedung Rektorat'),
            'nomor_surat' => 'UND/' . date('Y') . '/' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
        ];

        $pdf = Pdf::loadView('pdf.undangan', $data)
                  ->setPaper('a4', 'portrait');

        return $pdf->stream('undangan.pdf');
    }
}
