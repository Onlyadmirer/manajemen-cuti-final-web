<?php

namespace App\Console\Commands;

use App\Services\FonnteService;
use Illuminate\Console\Command;

class TestFonnte extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fonnte:test {phone?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Fonnte WhatsApp notification service';

    protected $fonnteService;

    /**
     * Create a new command instance.
     */
    public function __construct(FonnteService $fonnteService)
    {
        parent::__construct();
        $this->fonnteService = $fonnteService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('╔════════════════════════════════════════╗');
        $this->info('║   FONNTE WHATSAPP TEST                ║');
        $this->info('╚════════════════════════════════════════╝');
        $this->newLine();

        // Cek token
        $token = config('services.fonnte.token');
        if (!$token) {
            $this->error('❌ FONNTE_TOKEN tidak ditemukan di .env!');
            $this->info('Tambahkan: FONNTE_TOKEN=your_token_here');
            return 1;
        }

        $this->info("✓ Token ditemukan: " . substr($token, 0, 10) . "...");
        $this->newLine();

        // Cek device status
        $this->info('🔍 Mengecek status device WhatsApp...');
        $deviceStatus = $this->fonnteService->checkDevice();
        
        if ($deviceStatus['success']) {
            $this->info('✓ Koneksi ke Fonnte API berhasil!');
            
            if (isset($deviceStatus['data'])) {
                $this->newLine();
                $this->info('📱 Status Device:');
                $this->table(
                    ['Key', 'Value'],
                    collect($deviceStatus['data'])->map(function ($value, $key) {
                        return [$key, is_array($value) ? json_encode($value) : $value];
                    })->toArray()
                );
            }
        } else {
            $this->error('❌ Gagal mengecek device!');
            $this->error('Error: ' . ($deviceStatus['error'] ?? 'Unknown error'));
            $this->newLine();
            $this->warn('💡 Pastikan:');
            $this->warn('   1. Token API sudah benar');
            $this->warn('   2. Device WhatsApp sudah terhubung di dashboard Fonnte');
            $this->warn('   3. Device dalam status "Connected"');
            return 1;
        }

        // Test kirim pesan jika ada nomor
        $phone = $this->argument('phone');
        if ($phone) {
            $this->newLine();
            $this->info("📤 Mengirim pesan test ke {$phone}...");
            
            $result = $this->fonnteService->testMessage($phone);
            
            if ($result['success']) {
                $this->info('✓ Pesan berhasil dikirim!');
                
                if (isset($result['data'])) {
                    $this->newLine();
                    $this->info('📊 Response:');
                    $this->line(json_encode($result['data'], JSON_PRETTY_PRINT));
                }
            } else {
                $this->error('❌ Gagal mengirim pesan!');
                $this->error('Error: ' . ($result['error'] ?? 'Unknown error'));
            }
        } else {
            $this->newLine();
            $this->info('💡 Untuk test kirim pesan, gunakan:');
            $this->info('   php artisan fonnte:test 08123456789');
        }

        $this->newLine();
        $this->info('✅ Test selesai!');
        
        return 0;
    }
}
