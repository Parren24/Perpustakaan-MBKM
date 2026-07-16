<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Struk Peminjaman</title>
    <style>
        /* Desain CSS khusus Kertas Thermal 58mm */
        @page {
            margin: 0;
        }
        body {
            width: 170px; /* Standar lebar area cetak kertas 58mm */
            margin: 5px;
            padding: 0;
            font-family: 'Courier New', Courier, monospace; /* Font khas kasir */
            font-size: 11px;
            color: #000;
        }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .line { border-top: 1px dashed #000; margin: 5px 0; }
        .item-box { margin-bottom: 7px; }
    </style>
</head>
<body>

    <div class="text-center text-bold">PERPUSTAKAAN PCR</div>
    <div class="text-center">Struk Peminjaman</div>
    <div class="line"></div>

    <div>ID Anggota : {{ request('member_id') }}</div>
    <div>Nama       : {{ request('member_name') }}</div>
    <div>Tanggal    : {{ date('d/m/Y') }}</div>
    <div class="line"></div>

    <div class="text-bold" style="margin-bottom: 10px;">Daftar Buku:</div>
    @if(request('data'))
        @foreach(json_decode(request('data'), true) as $item)
            <div class="item-box">
                <div>• {{ $item['title'] ?? 'Buku' }}</div>
                <div style="padding-left: 15px; margin-bottom: 5px;">
                    Kode: {{ $item['item_code'] ?? 'N/A' }}<br>
                    Tempo: {{ isset($item['due_date']) ? date('d/m/Y', strtotime($item['due_date'])) : 'N/A' }}
                </div>
            </div>
        @endforeach
    @endif

    <div class="line"></div>
    <div class="text-center">
        Harap kembalikan buku<br>tepat pada waktunya.<br>
        Terima Kasih!
    </div>
    <div class="line"></div>

    <div class="text-center">
        <p></p>
        <p>==ROBEK==</p>
        <p></p>
    </div>

    <div class="line"></div>
    <p></p>
    <div>NIM : {{ request('member_id') }}</div>
    <div>Nama :       {{ request('member_name') }}</div>
    <div>Tanggal :    {{ date('d/m/Y') }}</div>
    <p></p>
    <div class="line"></div>
    <p></p>
    <div class="text-bold" style="margin-bottom: 10px;">Daftar Buku:</div>
    @if(request('data'))
        @foreach(json_decode(request('data'), true) as $item)
            <div class="item-box">
                <div>• {{ $item['title'] ?? 'Buku' }}</div>
                <div style="padding-left: 15px; margin-bottom: 5px;">
                    Kode: {{ $item['item_code'] ?? 'N/A' }}<br>
                    Tempo: {{ isset($item['due_date']) ? date('d/m/Y', strtotime($item['due_date'])) : 'N/A' }}
                </div>
                
            </div>
        @endforeach
    @endif

    <div class="line"></div>

    <script>
        window.onload = function() {
            // Perintahkan browser untuk mencetak
            window.print();
            
            // Beri jeda 500ms lalu tutup pop-up otomatis
            setTimeout(function() {
                window.close();
            }, 500);
        };
    </script>
</body>
</html>