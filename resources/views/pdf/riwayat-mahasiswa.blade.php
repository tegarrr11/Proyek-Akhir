<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Peminjaman</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <h2>Riwayat Peminjaman Mahasiswa</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Gedung</th>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($peminjaman as $i => $pinjam)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $pinjam->judul_kegiatan }}</td>
                <td>{{ $pinjam->gedung->nama }}</td>
                <td>{{ $pinjam->tgl_kegiatan }}</td>
                <td>{{ ucfirst($pinjam->status_pengembalian) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
