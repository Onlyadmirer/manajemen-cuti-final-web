<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk integrasi WhatsApp menggunakan Fonnte API
 * Menangani pengiriman notifikasi persetujuan/penolakan cuti
 * 
 * @package App\Services
 * @author Sistem Manajemen Cuti
 */
class FonnteService
{
    /**
     * Token autentikasi Fonnte API
     * @var string
     */
    protected $token;
    
    /**
     * Base URL Fonnte API
     * @var string
     */
    protected $baseUrl = 'https://api.fonnte.com';

    /**
     * Constructor - Inisialisasi token dari konfigurasi
     */
    public function __construct()
    {
        $this->token = config('services.fonnte.token');
    }

    /**
     * Mengirim pesan WhatsApp ke nomor tujuan
     * 
     * @param  string  $phone  Nomor telepon tujuan (format: 628xxxxxxxxxx)
     * @param  string  $message  Isi pesan yang akan dikirim
     * @return array  Response hasil pengiriman
     */
    public function sendMessage($phone, $message)
    {
        try {
            // Format nomor telepon (hapus +, spasi, dan karakter lain)
            $phone = $this->formatPhoneNumber($phone);

            // Fonnte API menggunakan form data, bukan JSON
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => $this->token,
                ])->asForm()->post($this->baseUrl . '/send', [
                    'target' => $phone,
                    'message' => $message,
                ]);

            $result = $response->json();

            // Log hasil
            Log::info('Fonnte WhatsApp Sent', [
                'phone' => $phone,
                'status' => $response->successful(),
                'response' => $result
            ]);

            return [
                'success' => $response->successful(),
                'data' => $result
            ];

        } catch (\Exception $e) {
            Log::error('Fonnte WhatsApp Error', [
                'phone' => $phone,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Memformat nomor telepon ke format internasional Indonesia
     * Menghapus karakter non-numerik dan menambahkan kode negara 62
     * 
     * @param  string  $phone  Nomor telepon yang akan diformat
     * @return string  Nomor telepon terformat (62xxxxxxxxxx)
     */
    protected function formatPhoneNumber($phone)
    {
        // Hapus karakter selain angka
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Jika diawali dengan 0, ganti dengan 62
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        // Jika belum ada kode negara, tambahkan 62
        if (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }

        return $phone;
    }

    /**
     * Memeriksa status koneksi device WhatsApp di Fonnte
     * 
     * @return array  Status device dan informasi koneksi
     */
    public function checkDevice()
    {
        try {
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => $this->token,
                ])->post($this->baseUrl . '/device');

            $result = $response->json();

            Log::info('Fonnte Device Status', [
                'status' => $response->successful(),
                'response' => $result
            ]);

            return [
                'success' => $response->successful(),
                'data' => $result
            ];

        } catch (\Exception $e) {
            Log::error('Fonnte Device Check Error', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Mengirim pesan test untuk memverifikasi konfigurasi Fonnte
     * 
     * @param  string  $phone  Nomor telepon tujuan untuk test
     * @return array  Response hasil pengiriman
     */
    public function testMessage($phone)
    {
        $message = "🧪 *TEST NOTIFIKASI*\n\n";
        $message .= "Ini adalah pesan test dari Sistem Manajemen Cuti.\n\n";
        $message .= "Jika Anda menerima pesan ini, berarti konfigurasi Fonnte sudah benar.\n\n";
        $message .= "_Waktu: " . now()->format('d/m/Y H:i:s') . "_";

        return $this->sendMessage($phone, $message);
    }

    /**
     * Mengirim notifikasi WhatsApp terkait persetujuan/penolakan cuti
     * ke kontak darurat yang terdaftar pada pengajuan
     * 
     * @param  \App\Models\LeaveRequest  $leaveRequest  Data pengajuan cuti
     * @param  string  $status  Status pengajuan ('approved' atau 'rejected')
     * @param  string|null  $rejectionReason  Alasan penolakan jika ditolak
     * @return array  Response hasil pengiriman
     */
    public function sendLeaveNotification($leaveRequest, $status, $rejectionReason = null)
    {
        if (!$leaveRequest->emergency_contact) {
            Log::warning('No emergency contact for leave request', [
                'leave_request_id' => $leaveRequest->id
            ]);
            return ['success' => false, 'error' => 'No emergency contact'];
        }

        $employee = $leaveRequest->user;
        $leaveType = $leaveRequest->leave_type === 'annual' ? 'Cuti Tahunan' : 'Cuti Sakit';
        
        // Format tanggal
        $startDate = $leaveRequest->start_date->format('d/m/Y');
        $endDate = $leaveRequest->end_date->format('d/m/Y');
        $totalDays = $leaveRequest->total_days;

        if ($status === 'approved') {
            $message = "*NOTIFIKASI PERSETUJUAN CUTI*\n\n";
            $message .= "Yth. Keluarga {$employee->name},\n\n";
            $message .= "Pengajuan cuti telah *DISETUJUI*\n\n";
            $message .= "*Detail Cuti:*\n";
            $message .= "• Nama: {$employee->name}\n";
            $message .= "• Jenis Cuti: {$leaveType}\n";
            $message .= "• Tanggal: {$startDate} - {$endDate}\n";
            $message .= "• Durasi: {$totalDays} hari\n";
            $message .= "• Alasan: {$leaveRequest->reason}\n\n";
            
            if ($leaveRequest->address_during_leave) {
                $message .= "• Alamat Selama Cuti:\n  {$leaveRequest->address_during_leave}\n\n";
            }
            
            $message .= "Terima kasih atas perhatiannya.\n\n";
            $message .= "_Pesan otomatis dari Sistem Manajemen Cuti_";
            
        } else {
            $message = "*NOTIFIKASI PENOLAKAN CUTI*\n\n";
            $message .= "Yth. Keluarga {$employee->name},\n\n";
            $message .= "Pengajuan cuti telah *DITOLAK*\n\n";
            $message .= "*Detail Cuti:*\n";
            $message .= "• Nama: {$employee->name}\n";
            $message .= "• Jenis Cuti: {$leaveType}\n";
            $message .= "• Tanggal: {$startDate} - {$endDate}\n";
            $message .= "• Durasi: {$totalDays} hari\n";
            $message .= "• Alasan Pengajuan: {$leaveRequest->reason}\n\n";
            
            if ($rejectionReason) {
                $message .= "*Alasan Penolakan:*\n{$rejectionReason}\n\n";
            }
            
            $message .= "Mohon koordinasi lebih lanjut dengan atasan.\n\n";
            $message .= "_Pesan otomatis dari Sistem Manajemen Cuti_";
        }

        return $this->sendMessage($leaveRequest->emergency_contact, $message);
    }
}