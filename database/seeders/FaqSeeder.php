<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\FaqModel;

class FaqSeeder extends Seeder
{
    public function run()
    {
        DB::table('m_faq')->insert([
            [
                'pertanyaan' => 'Apa itu AnstheLabel?',
                'jawaban' => 'AnstheLabel adalah sebuah platform e-commerce yang menyediakan berbagai produk fashion berkualitas tinggi dengan desain unik dan modern. Kami berkomitmen untuk memberikan pengalaman berbelanja yang menyenangkan dan memuaskan bagi pelanggan kami.'
            ],
            [
                'pertanyaan' => 'Bagaimana cara saya melakukan pemesanan?',
                'jawaban' => 'Anda dapat melakukan pemesanan dengan memilih produk yang Anda inginkan, menambahkannya ke keranjang belanja, dan melanjutkan ke halaman checkout. Ikuti langkah-langkah yang ada untuk mengisi informasi pengiriman dan memilih metode pembayaran.'
            ],
            [
                'pertanyaan' => 'Metode pembayaran apa saja yang tersedia?',
                'jawaban' => 'Kami menerima pembayaran melalui transfer bank (BCA, BRI), dan dompet digital (OVO, Dana).'
            ],
            [
                'pertanyaan' => 'Apakah pesanan saya bisa dibatalkan atau diubah?',
                'jawaban' => 'Pembatalan atau perubahan pesanan hanya bisa dilakukan jika pesanan belum diproses. Silakan hubungi layanan pelanggan kami secepatnya dengan menyertakan nomor invoice Anda.'
            ],
            [
                'pertanyaan' => 'Berapa lama waktu pengiriman?',
                'jawaban' => 'Waktu pengiriman standar kami adalah 2-5 hari kerja untuk wilayah Jabodetabek dan 3-7 hari kerja untuk luar Jabodetabek, terhitung setelah pembayaran terverifikasi.'
            ],
            [
                'pertanyaan' => 'Apakah saya bisa mengembalikan produk jika tidak cocok?',
                'jawaban' => 'Ya, kami memiliki kebijakan pengembalian dalam waktu 7 hari setelah barang diterima. Produk harus dalam kondisi asli, belum digunakan, dan lengkap dengan label. <a href="#">Kebijakan Pengembalian</a> kami untuk informasi lebih detail.'
            ],
            [
                'pertanyaan' => 'Apa yang harus saya lakukan jika produk yang saya terima rusak?',
                'jawaban' => 'Jika produk yang Anda terima dalam kondisi rusak, segera hubungi layanan pelanggan kami dalam 1x24 jam setelah barang diterima dengan menyertakan foto produk dan nomor pesanan. Kami akan segera membantu proses penukaran atau pengembalian dana.'
            ],
            [
                'pertanyaan' => 'Bagaimana cara saya mengetahui ukuran yang tepat?',
                'jawaban' => 'Kami menyertakan tabel ukuran (size chart) pada setiap halaman produk. Silakan ukur dengan teliti dan sesuaikan dengan tabel yang ada untuk mendapatkan ukuran yang paling pas.'
            ],
            [
                'pertanyaan' => 'Apakah produk yang ditampilkan di situs sesuai dengan aslinya?',
                'jawaban' => 'Kami berusaha menampilkan produk dengan foto yang paling akurat. Perlu diketahui bahwa ada kemungkinan sedikit perbedaan warna karena pencahayaan dan resolusi layar perangkat yang Anda gunakan.'
            ],
            [
                'pertanyaan' => 'Kapan produk yang habis stok akan tersedia lagi?',
                'jawaban' => 'Ketersediaan stok produk akan diinformasikan melalui media sosial kami. Anda juga bisa menekan tombol "Beritahu Saya" di halaman produk untuk mendapatkan notifikasi saat produk tersebut sudah tersedia kembali.'
            ],
            [
                'pertanyaan' => 'Apakah saya harus membuat akun untuk berbelanja?',
                'jawaban' => 'Anda tidak harus membuat akun untuk berbelanja. Namun, dengan membuat akun, Anda dapat menyimpan alamat pengiriman, melacak riwayat pesanan, dan mempercepat proses checkout di kemudian hari.'
            ],
            [
                'pertanyaan' => 'Bagaimana jika saya lupa kata sandi?',
                'jawaban' => 'Anda bisa menggunakan fitur "Lupa Kata Sandi?" di halaman login. Masukkan email yang terdaftar dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.'
            ],
            [
                'pertanyaan' => 'Bagaimana saya bisa menghubungi layanan pelanggan?',
                'jawaban' => 'Anda dapat menghubungi kami melalui email di <strong>[email@ansthelabel.com]</strong> atau melalui WhatsApp di <strong>[nomor_whatsapp]</strong>. Jam operasional kami adalah hari Senin-Jumat, pukul 09.00 - 17.00 WIB.'
            ]
        ]);
    }
}
