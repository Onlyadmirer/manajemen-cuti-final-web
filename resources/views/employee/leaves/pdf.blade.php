<!DOCTYPE html>
<html>
<head>
    <title>Surat Izin Cuti</title>
    <style>
        body { font-family: sans-serif; font-size: 11pt; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .title { font-size: 14pt; font-weight: bold; text-decoration: underline; text-align: center; margin-bottom: 30px; }
        table { width: 100%; margin-top: 10px; margin-bottom: 10px; }
        td { padding: 5px; vertical-align: top; }
        .footer { margin-top: 50px; text-align: right; }
        .signature { margin-top: 70px; font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="header">
        <h2>PT. MAJU MUNDUR SEJAHTERA</h2>
        <p>Jalan Teknologi No. 123, Jakarta Selatan, Indonesia</p>
    </div>

    <div class="title">SURAT IZIN CUTI</div>

    <p>Yang bertanda tangan di bawah ini, HRD PT. Maju Mundur Sejahtera memberikan izin kepada:</p>

    <table>
        <tr><td width="120">Nama</td><td>: <strong>{{ $leaveRequest->user->name }}</strong></td></tr>
        <tr><td>Email</td><td>: {{ $leaveRequest->user->email }}</td></tr>
        <tr><td>Divisi</td><td>: {{ $leaveRequest->user->division ? $leaveRequest->user->division->name : '-' }}</td></tr>
    </table>

    <p>Untuk melaksanakan cuti <strong>{{ $leaveRequest->leave_type == 'annual' ? 'TAHUNAN' : 'SAKIT' }}</strong> selama <strong>{{ $leaveRequest->total_days }} hari kerja</strong>.</p>
    
    <p>Terhitung mulai tanggal <strong>{{ $leaveRequest->start_date->isoFormat('D MMMM Y') }}</strong> sampai dengan <strong>{{ $leaveRequest->end_date->isoFormat('D MMMM Y') }}</strong>.</p>

    <p>Alasan Cuti:<br><em>"{{ $leaveRequest->reason }}"</em></p>

    <p>Demikian surat ini dibuat untuk dipergunakan sebagaimana mestinya.</p>

    <div class="footer">
        <p>Jakarta, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
        <p>Mengetahui,</p>
        <div class="signature">
            {{ $leaveRequest->hrApprover ? $leaveRequest->hrApprover->name : 'HRD Manager' }}
        </div>
        <div>HRD Manager</div>
    </div>
</body>
</html>