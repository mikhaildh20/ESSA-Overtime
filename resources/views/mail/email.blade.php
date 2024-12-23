<h1>Halo, {{ $name }}</h1>

<p>Dengan hormat,</p>
<p>Selamat datang di <strong>ESSA Politeknik Astra</strong>. Kami informasikan bahwa akun karyawan Anda telah berhasil dibuat. Berikut detail informasi login Anda:</p>

<table style="border-collapse: collapse; width: 100%; max-width: 600px;">
    <tr>
        <td style="padding: 8px; border: 1px solid #ddd;"><strong>Username:</strong></td>
        <td style="padding: 8px; border: 1px solid #ddd;">{{ $username }}</td>
    </tr>
    <tr>
        <td style="padding: 8px; border: 1px solid #ddd;"><strong>Password:</strong></td>
        <td style="padding: 8px; border: 1px solid #ddd;">{{ $password }}</td>
    </tr>
</table>

<p><em>Catatan:</em> Demi keamanan, harap segera mengganti password Anda setelah login pertama.</p>

<p>Apabila Anda memiliki pertanyaan atau memerlukan bantuan lebih lanjut, jangan ragu untuk menghubungi tim IT kami melalui email atau nomor telepon yang tersedia.</p>

<p>Terima kasih atas perhatian dan kerja sama Anda.</p>

<p>Hormat kami,</p>
<p><strong>{{ $admin }}</strong><br>
Tim IT ESSA Politeknik Astra</p>
