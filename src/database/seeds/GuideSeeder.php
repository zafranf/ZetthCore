<?php

namespace ZetthCore\Seeder;

use Illuminate\Database\Seeder;
use ZetthCore\Models\Guide;

class GuideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mainOrder = 1;

        $dashOrder = 1;
        /* Dasbor */
        $content = '<p>Ini adalah halaman utama panel admin. Fungsi halaman ini adalah untuk memonitor lalu lintas situs Anda. Anda dapat mengakses beberapa halaman dengan cepat melalui tombol pintas yang tersedia, melihat grafik kunjungan, melihat artikel populer dan juga komentar terbaru dari pengunjung Anda.</p>';
        $dash = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/dasbor.png',
            'title' => 'Dasbor',
            'slug' => 'dasbor',
            'content' => $content,
            'roles' => 'all',
            'order' => $mainOrder++,
            'status' => 'active',
        ]);

        /* Dasbor - Atur Tanggal */
        $content = '<div id="date-range-guide" class="pull-right"><i class="fa fa-calendar"></i>&nbsp;<span>[~dates]</span> <b class="caret"></b></div><div id="btn-refresh-guide" title="Segarkan" class="pull-right"><i class="fa fa-refresh"></i></div>';
        $content .= '<p>Posisi tombol ini berada di sebelah kanan atas di bawah nama pengguna. Tombol atur tanggal ini dapat digunakan untuk melihat grafik, artikel favorit serta komentar terbaru berdasarkan tanggal. Anda dapat mengaturnya menjadi <code>Hari ini</code> untuk melihat grafik hari ini di mulai dari pukul 00:00 hingga saat diakses, <code>Kemarin</code> untuk melihat grafik kemarin, <code>Minggu ini</code> untuk melihat grafik minggu ini (bukan 7 hari sebelumnya) dimulai dari Senin - Minggu pada minggu ini, <code>Bulan ini</code> untuk melihat grafik bulan ini mulai dari tanggal 1 sampai saat diakses dan <code>Semua</code> untuk melihat grafik situs Anda dari pertama kali pasang atau Anda dapat menyesuaikan tanggal sesuai dengan keinginan Anda melalui pilihan <code>Atur tanggal</code>.</p>';
        $content .= '<p>Anda dapat menekan tombol <a class="btn btn-default btn-xs"><span class="fa fa-refresh"></span></a> untuk memperbarui data.</p>';
        $dashTanggal = Guide::create([
            'title' => 'Atur Tanggal',
            'slug' => 'dasbor-aturtanggal',
            'content' => $content,
            'roles' => 'all',
            'order' => $dashOrder++,
            'parent_id' => $dash->id,
            'status' => 'active',
        ]);

        /* Dasbor - Tombol Pintas */
        $content = '<p>Tombol ini berfungsi sebagai akses langsung ke halaman yang dituju.</p>';
        $content .= '<ul>';
        $content .= '<li><a class="btn btn-default btn-xs"><i class="fa fa-edit"></i> Buat Artikel</a>, tombol pintas untuk langsung masuk ke halaman tambah artikel.</li>';
        $content .= '<li><a class="btn btn-default btn-xs"><i class="fa fa-file-o"></i> Buat Halaman</a>, tombol pintas untuk langsung masuk ke halaman tambah halaman (konten statis).</li>';
        $content .= '<li><a class="btn btn-default btn-xs"><i class="fa fa-comment-o"></i> Komentar</a>, tombol pintas untuk langsung masuk ke halaman daftar komentar. Terdapat angka sebagai penunjuk komentar yang belum dibaca.</li>';
        $content .= '<li><a class="btn btn-default btn-xs"><i class="fa fa-envelope-o"></i> Pesan Masuk</a>, tombol pintas untuk langsung masuk ke halaman daftar pesan. Terdapat angka sebagai petunjuk pesan yang belum dibaca.</li>';
        $content .= '</ul>';
        $dashTanggal = Guide::create([
            'title' => 'Tombol Pintas',
            'slug' => 'dasbor-tombolpintas',
            'content' => $content,
            'roles' => 'all',
            'order' => $dashOrder++,
            'parent_id' => $dash->id,
            'status' => 'active',
        ]);

        /* Dasbor - Kunjungan */
        $content = '<p>Grafik yang memperlihatkan seberapa banyak pengunjung Anda dalam rentang waktu yang terdapat pada yang telah ditentukan sebelumnya. Garis yang berwarna <span style="background:coral;color:white;padding:0 3px;">jingga</span> menandakan data banyaknya kunjungan, sedangkan garis yang berwarna <span style="background:grey;color:white;padding:0 3px;">abu-abu</span> adalah menandakan data banyaknya pengunjung yang unik (berdasarkan alamat IP Pengunjung). Arahkan kursor Anda ke titik/waktu yang ada di grafik untuk melihat detail kunjungan.</p>';
        $dashTanggal = Guide::create([
            'title' => 'Kunjungan',
            'slug' => 'dasbor-kunjungan',
            'content' => $content,
            'roles' => 'all',
            'order' => $dashOrder++,
            'parent_id' => $dash->id,
            'status' => 'active',
        ]);

        /* Dasbor - Artikel Populer */
        $content = '<p>Kolom ini menampilkan artikel terpopuler berdasarkan tanggal yang dipilih. Data yang tampil di kolom ini adalah <code>Judul Artikel</code>, <code>Kategori</code> dan <code>Jumlah Kunjungan</code>. Anda dapat melihat semua artikel dengan menekan tombol <a class="btn btn-default btn-xs"><span class="fa fa-eye"></span> Semua</a>.';
        $dashTanggal = Guide::create([
            'title' => 'Artikel Populer',
            'slug' => 'dasbor-artikelpopuler',
            'content' => $content,
            'roles' => 'all',
            'order' => $dashOrder++,
            'parent_id' => $dash->id,
            'status' => 'active',
        ]);

        /* Dasbor - Komentar Terbaru */
        $content = '<p>Pada kolom ini Anda akan diperlihatkan dengan komentar terbaru. Data yang tampil di kolom ini adalah <code>Isi Komentar</code>, <code>Judul Artikel</code>, <code>Waktu</code> dan <code>Pengirim</code>. Anda dapat melihat semua komentar dengan menekan tombol <a class="btn btn-default btn-xs"><span class="fa fa-eye"></span> Semua</a>.';
        $dashTanggal = Guide::create([
            'title' => 'Komentar Terbaru',
            'slug' => 'dasbor-komentarterbaru',
            'content' => $content,
            'roles' => 'all',
            'order' => $dashOrder++,
            'parent_id' => $dash->id,
            'status' => 'active',
        ]);

        $setOrder = 1;
        /* Pengaturan */
        $content = '<p>Pengaturan utama situs dapat melalui menu yang ada di grup ini.</p>';
        $set = Guide::create([
            'title' => 'Pengaturan',
            'slug' => 'pengaturan',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $mainOrder++,
            'status' => 'active',
        ]);

        /* Pengaturan - Situs */
        $content = '<p>Atur situs Anda melalui halaman ini.</p>';
        $setSite = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/pengaturan-situs.png',
            'title' => 'Situs',
            'slug' => 'pengaturan-situs',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $setOrder++,
            'parent_id' => $set->id,
            'status' => 'active',
        ]);

        $setSiteOrder = 1;
        /* Pengaturan - Situs - Informasi Utama */
        $content = '<ul>';
        $content .= '<li><b>Logo</b>, logo utama situs yang digunakan untuk setiap halaman. Maksimal ukuran yang diperbolehkan adalah dimensi lebar 512px dan tinggi 512px dengan ukuran maksimal 384KB.</li>';
        $content .= '<li><b>Ikon</b>, logo berukuran kecil yang digunakan untuk ikon pada bagian judul peramban, maksimal ukurannya adalah lebar 128px dan tinggi 128px dengan ukuran maksimal 96KB.</li>';
        $content .= '<li><b>Nama Situs</b>, merupakan judul/nama situs sebagai identitas situs Anda.</li>';
        $content .= '<li><b>Slogan</b>, tambahkan slogan jika ada.</li>';
        $content .= '<li><b>Surel</b>, ini akan ditampilkan sebagai alamat surel utama.</li>';
        $content .= '<li><b>No. Telepon</b>, nomor telepon pribadi atau perusahaan, digunakan sebagai kontak utama.</li>';
        $content .= '<li><b>Akses Pengunjung</b>, Anda dapat mengaktifkan atau mematikan fitur-fitur tersebut secara bawaan. <code>Langganan</code> berupa notifikasi untuk pengunjung apabila situs mempunyai berita baru. <code>Komentar</code>, <code>Suka</code> dan <code>Sebar</code> terintegrasi dengan pengaturan masing-masing artikel. Apabila salah satu (antara Pengaturan Situs dan Pengaturan Artikel) tidak aktif, maka fitur tersebut tidak akan ditampilkan di artikel.</li>';
        $content .= '<li><b>Status</b>, terdapat <code>Aktif</code>, <code>Segera Hadir</code> dan <code>Perbaikan</code>. Sesuaikan dengan kondisi situs.</li>';
        $content .= '</ul>';
        $setSiteInfo = Guide::create([
            'title' => 'Informasi Utama',
            'slug' => 'pengaturan-situs-infoutama',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $setSiteOrder++,
            'parent_id' => $setSite->id,
            'status' => 'active',
        ]);

        /* Pengaturan - Situs - Media Sosial */
        $content = '<p>Sebagai daftar akun media sosial Anda atau perusahaan Anda. Klik tombol <a class="btn btn-default btn-xs"><span class="fa fa-plus"></span></a> untuk menambahkan akun media sosial lainnya.</p>';
        $setSiteMedsos = Guide::create([
            'title' => 'Media Sosial',
            'slug' => 'pengaturan-situs-mediasosial',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $setSiteOrder++,
            'parent_id' => $setSite->id,
            'status' => 'active',
        ]);

        /* Pengaturan - Situs - SEO */
        $content = '<ul>';
        $content .= '<li><b>Kata Kunci</b>, gunakan kata kunci yang berfungsi untuk memudahkan web Anda ditemukan oleh pengunjung di mesin pencari seperti Google, Yahoo dan lain sebagainya. Berikan setidaknya minimal 3 kata kunci.</li>';
        $content .= '<li><b>Deskripsi</b>, berikan deskripsi singkat mengenai situs Anda.</li>';
        $content .= '<li><b>Google Analytics</b>, sebuah alat milik Google untuk menganalisis situs Anda. Anda dapat menaruh kode unik dari Google untuk memakai layanan tersebut. Anda dapat merujuk ke halaman ini <a href="https://www.google.com/analytics/" target="_blank">Google Analytics</a> untuk mendapatkan kode uniknya atau serahkan kepada pengembang situs.</li>';
        $content .= '</ul>';
        $setSiteMedsos = Guide::create([
            'title' => 'SEO (Search Engine Optimization)',
            'slug' => 'pengaturan-situs-seo',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $setSiteOrder++,
            'parent_id' => $setSite->id,
            'status' => 'active',
        ]);

        /* Pengaturan - Situs - Lokasi */
        $content = '<ul>';
        $content .= '<li><b>Alamat</b>, alamat rumah atau perusahaan Anda, digunakan sebagai alamat utama.</li>';
        $content .= '<li><b>Koordinat</b>, nama lokasi atau titik koordinat yang digunakan untuk menampilkan peta (jika ada). Anda bisa mendapatkannya melalui tautan ini <a href="https://www.google.co.id/maps/" target="_blank">Google Maps</a>.</li>';
        $content .= '</ul>';
        $setSiteMedsos = Guide::create([
            'title' => 'Lokasi',
            'slug' => 'pengaturan-situs-lokasi',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $setSiteOrder++,
            'parent_id' => $setSite->id,
            'status' => 'active',
        ]);

        /* Pengaturan - (Grup) Menu */
        $content = '<p>Pengaturan menu situs. Kolom <code>Grup</code> sebagai nama grup menu, kolom <code>Deskripsi</code> merupakan penjelasan singkat mengenai grup menu, kolom <code>Status</code> memperlihatkan apakah grup menu aktif atau tidak, serta kolom <code>Akses</code> yang merupakan tombol untuk mengatur data grup menu tersebut. Tombol <a class="btn btn-default btn-xs"><span class="fa fa-edit"><span></a> <code>Edit</code> untuk mengedit grup menu dan tombol <a class="btn btn-default btn-xs"><span class="fa fa-trash"><span></a> <code>Hapus</code> untuk menghapus grup menu. Anda dapat menambahkan grup menu dengan menekan tombol <a class="btn btn-default btn-xs"><span class="fa fa-plus"></span> TAMBAH</a>.</p>';
        $setMenu = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/pengaturan-grupmenu-daftar.png',
            'title' => 'Menu (Grup)',
            'slug' => 'pengaturan-grupmenu',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $setOrder++,
            'parent_id' => $set->id,
            'status' => 'active',
        ]);

        $setMenuOrder = 1;
        /* Pengaturan - (Grup) Menu - Tambah */
        $content = 'Halaman untuk menambahkan data grup menu. Ketika data berhasil tersimpan, Anda dapat langsung menambahkan daftar menu pada grup tersebut. Lihat bagian <a onclick="$(\'a[href=\\\'#pengaturan-grupmenu-edit\\\']\').click()" style="cursor:pointer;">Edit Menu (Grup)</a>.';
        $setMenuAdd = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/pengaturan-grupmenu-tambah.png',
            'title' => 'Tambah',
            'slug' => 'pengaturan-grupmenu-tambah',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $setMenuOrder++,
            'parent_id' => $setMenu->id,
            'status' => 'active',
        ]);

        $setMenuAddOrder = 1;
        /* Pengaturan - (Grup) Menu - Tambah - Informasi Utama */
        $content = '<ul>';
        $content .= '<li><b>Nama</b>, nama grup menu.</li>';
        $content .= '<li><b>Deskripsi</b>, berikan sedikit penjelasan mengenai grup menu tersebut.</li>';
        $content .= '<li><b>Aktif</b>, centang untuk mengaktifkan grup menu.</li>';
        $content .= '</ul>';
        $setMenuAddInfo = Guide::create([
            'title' => 'Informasi Utama',
            'slug' => 'pengaturan-grupmenu-tambah-infoutama',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $setMenuAddOrder++,
            'parent_id' => $setMenuAdd->id,
            'status' => 'active',
        ]);

        /* Pengaturan - (Grup) Menu - Edit */
        $content = 'Halaman untuk mengedit data grup menu. Anda juga dapat menghapusnya di sini dengan menekan tombol <a class="btn btn-danger btn-xs">Hapus</a>.';
        $setMenuEdit = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/pengaturan-grupmenu-edit.png',
            'title' => 'Edit',
            'slug' => 'pengaturan-grupmenu-edit',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $setMenuOrder++,
            'parent_id' => $setMenu->id,
            'status' => 'active',
        ]);

        $setMenuEditOrder = 1;
        /* Pengaturan - (Grup) Menu - Edit - Informasi Utama */
        $content = '<ul>';
        $content .= '<li><b>Nama</b>, nama grup menu.</li>';
        $content .= '<li><b>Deskripsi</b>, berikan sedikit penjelasan mengenai grup menu tersebut.</li>';
        $content .= '<li><b>Aktif</b>, centang untuk mengaktifkan grup menu.</li>';
        $content .= '</ul>';
        $setMenuEditInfo = Guide::create([
            'title' => 'Informasi Utama',
            'slug' => 'pengaturan-grupmenu-edit-infoutama',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $setMenuEditOrder++,
            'parent_id' => $setMenuEdit->id,
            'status' => 'active',
        ]);

        /* Pengaturan - (Grup) Menu - Edit - Daftar Menu */
        $content = '<p>Rincian daftar menu dari grup menu. Tombol <a class="btn btn-default btn-xs"><span class="fa fa-edit"><span></a> <code>Edit</code> untuk mengedit menu dan tombol <a class="btn btn-default btn-xs"><span class="fa fa-trash"><span></a> <code>Hapus</code> untuk menghapus menu. Anda dapat menambahkannya dengan menekan tombol <a class="btn btn-default btn-xs"><span class="fa fa-plus"></span></a> dan juga mengubah urutan posisi dengan menekan baris menunya (kursor berbentuk <span class="fa fa-arrows" style="font-size:small;"></span>) lalu geser sesuai posisi yang diinginkan.</p>';
        $setMenuEditDaftar = Guide::create([
            'title' => 'Daftar Menu',
            'slug' => 'pengaturan-grupmenu-edit-daftarmenu',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $setMenuEditOrder++,
            'parent_id' => $setMenuEdit->id,
            'status' => 'active',
        ]);

        $setMenuEditMenuOrder = 1;
        /* Pengaturan - (Grup) Menu - Edit - Tambah & Edit */
        $content = '<p>Halaman untuk menambahkan atau mengedit data menu dalam grup. Anda juga dapat menghapus data dari halaman ini jika dalam mode edit.</p>';
        $content .= '<ul>';
        $content .= '<li><b>Nama</b>, nama menu yang akan tampil di situs.</li>';
        $content .= '<li><b>Deskripsi</b>, berikan sedikit penjelasan mengenai menu tersebut.</li>';
        $content .= '<li><b>Tautan</b>, sebagai penghubung menu tersebut. Terdapat pilihan dari daftar <code>Halaman</code> dan <code>Artikel</code>. Anda juga dapat menggunakan tautan luar dengan memilih <code>Tautan Luar</code>, pastikan selalu menggunakan <code>http://</code> atau <code>http://</code> di awal tautan.</li>';
        $content .= '<li><b>Ikon</b>, Anda dapat menggunakan ikon dari <a href="https://fontawesome.com/icons" target="_blank">sini</a>.</li>';
        $content .= '<li><b>Target</b>, untuk membuka tautan pada jendela yang sedang aktif atau buka jendela baru.</li>';
        $content .= '<li><b>Aktif</b>, centang untuk mengaktifkan menu.</li>';
        $content .= '</ul>';
        $setMenuEditMenuAdd = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/pengaturan-grupmenu-edit-tambah.png',
            'title' => 'Tambah & Edit',
            'slug' => 'pengaturan-grupmenu-edit-daftarmenu-tambahedit',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $setMenuEditMenuOrder++,
            'parent_id' => $setMenuEditDaftar->id,
            'status' => 'active',
        ]);

        /* Pengaturan - (Grup) Menu - Edit - Hapus */
        $content = '<p>Saat Anda menekan tombol hapus pada salah satu daftar menu, akan muncul konfirmasi sebelum data dihapus untuk mencegah terjadinya kesalahan hapus data.</p>';
        $setMenuEditMenuDel = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/pengaturan-grupmenu-edit-hapus.png',
            'title' => 'Hapus',
            'slug' => 'pengaturan-grupmenu-edit-daftarmenu-hapus',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $setMenuEditMenuOrder++,
            'parent_id' => $setMenuEditDaftar->id,
            'status' => 'active',
        ]);

        /* Pengaturan - (Grup) Menu - Hapus */
        $content = '<p>Saat Anda menekan tombol hapus pada salah satu daftar grup menu, akan muncul konfirmasi sebelum data dihapus untuk mencegah terjadinya kesalahan hapus data.</p>';
        $setMenuDel = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/pengaturan-grupmenu-hapus.png',
            'title' => 'Hapus',
            'slug' => 'pengaturan-grupmenu-hapus',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $setMenuOrder++,
            'parent_id' => $setMenu->id,
            'status' => 'active',
        ]);

        $setRoleOrder = 1;
        /* Pengaturan - Peran & Akses */
        $content = '<p>Daftar peran untuk pengelompokan akses pengguna. Kolom <code>Peran</code> sebagai nama peram, kolom <code>Deskripsi</code> merupakan penjelasan singkat mengenai peran tersebut, kolom <code>Status</code> memperlihatkan apakah peran aktif atau tidak, serta kolom <code>Akses</code> yang merupakan tombol untuk mengatur data peran tersebut. Tombol <a class="btn btn-default btn-xs"><span class="fa fa-edit"><span></a> <code>Edit</code> untuk mengedit peran dan tombol <a class="btn btn-default btn-xs"><span class="fa fa-trash"><span></a> <code>Hapus</code> untuk menghapus peran. Anda dapat menambahkan peran dengan menekan tombol <a class="btn btn-default btn-xs"><span class="fa fa-plus"></span> TAMBAH</a>.</p>';
        $setRole = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/pengaturan-peran.png',
            'title' => 'Peran dan Akses',
            'slug' => 'pengaturan-peran',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $setOrder++,
            'parent_id' => $set->id,
            'status' => 'active',
        ]);

        /* Pengaturan - Peran & Akses - Tambah */
        $content = '<p>Halaman untuk menambahkan data peran. Ketika data berhasil tersimpan, Anda dapat langsung menambahkan akses pada peran tersebut. Lihat bagian <a onclick="$(\'a[href=\\\'#pengaturan-peran-edit\\\']\').click()" style="cursor:pointer;">Edit Peran</a>.</p>';
        $content .= '<ul>';
        $content .= '<li><b>Nama Peran</b>, nama peran yang akan digunakan.</li>';
        $content .= '<li><b>Deskripsi</b>, berikan sedikit penjelasan mengenai peran tersebut.</li>';
        $content .= '<li><b>Grup Menu</b>, grup menu yang dapat dikelola oleh pengguna yang memiliki peran tersebut. Anda dapat menambahkannya lebih dari 1 (satu).</li>';
        $content .= '<li><b>Aktif</b>, centang untuk mengaktifkan peran.</li>';
        $content .= '</ul>';
        $data = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/pengaturan-peran-tambah.png',
            'title' => 'Tambah',
            'slug' => 'pengaturan-peran-tambah',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $setRoleOrder++,
            'parent_id' => $setRole->id,
            'status' => 'active',
        ]);

        /* Pengaturan - Peran & Akses - Edit */
        $content = '<p>Halaman untuk mengedit data peran. Anda juga dapat menghapusnya di sini dengan menekan tombol <a class="btn btn-danger btn-xs">Hapus</a></p>';
        $content .= '<ul>';
        $content .= '<li><b>Nama Peran</b>, nama peran yang akan digunakan.</li>';
        $content .= '<li><b>Deskripsi</b>, berikan sedikit penjelasan mengenai peran tersebut.</li>';
        $content .= '<li><b>Grup Menu</b>, grup menu yang dapat dikelola oleh pengguna yang memiliki peran tersebut. Anda dapat menambahkannya lebih dari 1 (satu).</li>';
        $content .= '<li><b>Aktif</b>, centang untuk mengaktifkan peran.</li>';
        $content .= '</ul>';
        $content .= '<p>Anda dapat menentukan akses mana saja yang dapat diberikan kepada peran tersebut dengan mencentang daftar akses pada kolom <b>Akses</b>.</p>';
        $data = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/pengaturan-peran-edit.png',
            'title' => 'Edit',
            'slug' => 'pengaturan-peran-edit',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $setRoleOrder++,
            'parent_id' => $setRole->id,
            'status' => 'active',
        ]);

        /* Pengaturan - Peran & Akses - Hapus */
        $content = '<p>Saat Anda menekan tombol hapus pada salah satu daftar peran, akan muncul konfirmasi sebelum data dihapus untuk mencegah terjadinya kesalahan hapus data.</p>';
        $data = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/pengaturan-peran-hapus.png',
            'title' => 'Hapus',
            'slug' => 'pengaturan-peran-hapus',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $setRoleOrder++,
            'parent_id' => $setRole->id,
            'status' => 'active',
        ]);

        $dataOrder = 1;
        /* Data */
        $content = '<p>Kumpulan data yang mendukung informasi di situs.</p>';
        $data = Guide::create([
            'title' => 'Data',
            'slug' => 'data',
            'content' => $content,
            'roles' => 'all',
            'order' => $mainOrder++,
            'status' => 'active',
        ]);

        $dataUserOrder = 1;
        /* Data - Pengguna*/
        $content = '<p>Daftar pengguna situs baik pengelola maupun anggota. Kolom <code>Nama Akses</code> menampilkan nama akses pengguna, kolom <code>Nama Lengkap</code> merupakan nama lengkap pengguna, kolom <code>Status</code> memperlihatkan apakah pengguna aktif atau tidak, serta kolom <code>Akses</code> yang merupakan tombol untuk mengatur data pengguna tersebut. Tombol <a class="btn btn-default btn-xs"><span class="fa fa-eye"><span></a> <code>Detail</code> untuk melihat data pengguna secara lengkap, tombol <a class="btn btn-default btn-xs"><span class="fa fa-edit"><span></a> <code>Edit</code> untuk mengedit data pengguna dan tombol <a class="btn btn-default btn-xs"><span class="fa fa-trash"><span></a> <code>Hapus</code> untuk menghapus data pengguna. Anda dapat menambahkan pengguna dengan menekan tombol <a class="btn btn-default btn-xs"><span class="fa fa-plus"></span> TAMBAH</a>.</p>';
        $dataUser = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/data-pengguna.png',
            'title' => 'Pengguna',
            'slug' => 'data-pengguna',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $dataOrder++,
            'parent_id' => $data->id,
            'status' => 'active',
        ]);

        /* Data - Pengguna - Detail */
        $content = '<p><i>Sedang dalam pengembangan..</i></p>';
        $dataUserDetail = Guide::create([
            'title' => 'Detail',
            'slug' => 'data-pengguna-detail',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $dataUserOrder++,
            'parent_id' => $dataUser->id,
            'status' => 'active',
        ]);

        $dataUserAddOrder = 1;
        /* Data - Pengguna - Tambah & Edit */
        $content = 'Halaman untuk menambahkan atau mengedit data pengguna. Anda juga dapat menghapus data dari halaman ini jika dalam mode edit.';
        $dataUserAdd = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/data-pengguna-tambah.png',
            'title' => 'Tambah & Edit',
            'slug' => 'data-pengguna-tambahedit',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $dataUserOrder++,
            'parent_id' => $dataUser->id,
            'status' => 'active',
        ]);

        /* Data - Pengguna - Tambah - Informasi Utama*/
        $content = '<ul>';
        $content .= '<li><b>Nama Akses</b>, nama yang akan digunakan untuk akses masuk.</li>';
        $content .= '<li><b>Email</b>, alamat surel pengguna untuk kepentingan komunikasi melalui surel. Dapat juga digunakan sebagak akses masuk.</li>';
        $content .= '<li><b>Sandi</b>, digunakan untuk sandi akses amsuk. Minimal 6 karakter.</li>';
        $content .= '<li><b>Ulangi Sandi</b>, untuk konfirmasi sandi.</li>';
        $content .= '<li><b>Nama Lengkap</b>, nama lengkap pengguna yang akan tampil di situs.</li>';
        $content .= '<li><b>Tentang</b>, sedikit ulasan tentang pengguna.</li>';
        $content .= '<li><b>Foto</b>, maksimal dimensi foto adalah lebar 512px dan tinggi 512px dengan rasio 1:1 dan ukuran maksimal 384KB.</li>';
        $content .= '<li><b>Peran</b>, tentukan peran pengguna untuk mengakses situs.</li>';
        $content .= '<li><b>Aktif</b>, centang untuk mengaktifkan pengguna.</li>';
        $content .= '</ul>';
        $dataUserAddInfo = Guide::create([
            'title' => 'Informasi Utama',
            'slug' => 'data-pengguna-tambahedit-infoutama',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $dataUserAddOrder++,
            'parent_id' => $dataUserAdd->id,
            'status' => 'active',
        ]);

        /* Data - Pengguna - Tambah - Media Sosial*/
        $content = '<p>Sebagai daftar akun media sosial pengguna. Klik tombol <a class="btn btn-default btn-xs"><span class="fa fa-plus"></span></a> untuk menambahkan akun media sosial lainnya.</p>';
        $dataUserAddInfo = Guide::create([
            'title' => 'Media Sosial',
            'slug' => 'data-pengguna-tambahedit-mediasosial',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $dataUserAddOrder++,
            'parent_id' => $dataUserAdd->id,
            'status' => 'active',
        ]);

        /* Data - Pengguna - Hapus */
        $content = '<p>Saat Anda menekan tombol hapus pada salah satu daftar pengguna, akan muncul konfirmasi sebelum data dihapus untuk mencegah terjadinya kesalahan hapus data.</p>';
        $dataUserDel = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/data-pengguna-hapus.png',
            'title' => 'Hapus',
            'slug' => 'data-pengguna-hapus',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $dataUserOrder++,
            'parent_id' => $dataUser->id,
            'status' => 'active',
        ]);

        $dataCategoryOrder = 1;
        /* Data - Kategori */
        $content = '<p>Daftar kategori untuk dipakai di artikel. Kolom <code>Kategori</code> sebagai nama tampilan kategori, kolom <code>Deskripsi</code> merupakan penjelasan singkat mengenai kategori tersebut, kolom <code>Status</code> memperlihatkan apakah kategori aktif atau tidak, serta kolom <code>Akses</code> yang merupakan tombol untuk mengatur data kategori tersebut. Tombol <a class="btn btn-default btn-xs"><span class="fa fa-edit"><span></a> <code>Edit</code> untuk mengedit kategori dan tombol <a class="btn btn-default btn-xs"><span class="fa fa-trash"><span></a> <code>Hapus</code> untuk menghapus kategori. Anda dapat menambahkan kategori dengan menekan tombol <a class="btn btn-default btn-xs"><span class="fa fa-plus"></span> TAMBAH</a>.</p>';
        $dataCategory = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/data-kategori.png',
            'title' => 'Kategori',
            'slug' => 'data-kategori',
            'content' => $content,
            'roles' => 'all',
            'order' => $dataOrder++,
            'parent_id' => $data->id,
            'status' => 'active',
        ]);

        /* Data - Kategori - Tambah & Edit */
        $content = 'Halaman untuk menambahkan atau mengedit data kategori. Anda juga dapat menghapus data dari halaman ini jika dalam mode edit.';
        $content .= '<ul>';
        $content .= '<li><b>Kategori</b>, nama kategori yang akan tampil di situs.</li>';
        $content .= '<li><b>Deskripsi</b>, berikan sedikit penjelasan mengenai kategori tersebut.</li>';
        $content .= '<li><b>Induk</b>, tentukan induk apabila kategori yang sedang dibuat merupakan subkategori.</li>';
        $content .= '<li><b>Aktif</b>, centang untuk mengaktifkan kategori.</li>';
        $content .= '</ul>';
        $dataCategoryAdd = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/data-kategori-tambah.png',
            'title' => 'Tambah & Edit',
            'slug' => 'data-kategori-tambahedit',
            'content' => $content,
            'roles' => 'all',
            'order' => $dataCategoryOrder++,
            'parent_id' => $dataCategory->id,
            'status' => 'active',
        ]);

        /* Data - Kategori - Hapus */
        $content = '<p>Saat Anda menekan tombol hapus pada salah satu daftar kategori, akan muncul konfirmasi sebelum data dihapus untuk mencegah terjadinya kesalahan hapus data.</p>';
        $dataCategoryDel = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/data-kategori-hapus.png',
            'title' => 'Hapus',
            'slug' => 'data-kategori-hapus',
            'content' => $content,
            'roles' => 'all',
            'order' => $dataCategoryOrder++,
            'parent_id' => $dataCategory->id,
            'status' => 'active',
        ]);

        $dataLabelOrder = 1;
        /* Data - Label */
        $content = '<p>Daftar label untuk dipakai di artikel. Kolom <code>Kategori</code> sebagai nama tampilan label, kolom <code>Deskripsi</code> merupakan penjelasan singkat mengenai label tersebut, kolom <code>Status</code> memperlihatkan apakah label aktif atau tidak, serta kolom <code>Akses</code> yang merupakan tombol untuk mengatur data label tersebut. Tombol <a class="btn btn-default btn-xs"><span class="fa fa-edit"><span></a> <code>Edit</code> untuk mengedit label dan tombol <a class="btn btn-default btn-xs"><span class="fa fa-trash"><span></a> <code>Hapus</code> untuk menghapus label. Anda dapat menambahkan label dengan menekan tombol <a class="btn btn-default btn-xs"><span class="fa fa-plus"></span> TAMBAH</a>.</p>';
        $dataLabel = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/data-label.png',
            'title' => 'Label',
            'slug' => 'data-label',
            'content' => $content,
            'roles' => 'all',
            'order' => $dataOrder++,
            'parent_id' => $data->id,
            'status' => 'active',
        ]);

        /* Data - Label - Tambah & Edit */
        $content = 'Halaman untuk menambahkan atau mengedit data label. Anda juga dapat menghapus data dari halaman ini jika dalam mode edit.';
        $content .= '<ul>';
        $content .= '<li><b>Label</b>, nama label yang akan tampil di situs.</li>';
        $content .= '<li><b>Deskripsi</b>, berikan sedikit penjelasan mengenai label tersebut.</li>';
        $content .= '<li><b>Aktif</b>, centang untuk mengaktifkan label.</li>';
        $content .= '</ul>';
        $dataLabelAdd = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/data-label-tambah.png',
            'title' => 'Tambah & Edit',
            'slug' => 'data-label-tambahedit',
            'content' => $content,
            'roles' => 'all',
            'order' => $dataLabelOrder++,
            'parent_id' => $dataLabel->id,
            'status' => 'active',
        ]);

        /* Data - Label - Hapus */
        $content = '<p>Saat Anda menekan tombol hapus pada salah satu daftar label, akan muncul konfirmasi sebelum data dihapus untuk mencegah terjadinya kesalahan hapus data.</p>';
        $dataLabelDel = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/data-label-hapus.png',
            'title' => 'Hapus',
            'slug' => 'data-label-hapus',
            'content' => $content,
            'roles' => 'all',
            'order' => $dataLabelOrder++,
            'parent_id' => $dataLabel->id,
            'status' => 'active',
        ]);

        $contOrder = 1;
        /* Konten */
        $content = '<p>Kelola konten situs Anda melalui grup menu ini.</p>';
        $cont = Guide::create([
            'title' => 'Konten',
            'slug' => 'konten',
            'content' => $content,
            'roles' => 'all',
            'order' => $mainOrder++,
            'status' => 'active',
        ]);

        $contBannerOrder = 1;
        /* Konten - Spanduk */
        $content = '<p>Daftar spanduk untuk halaman depan situs. Kolom <code>Gambar</code> merupakan cuplikan dari gambar spanduk, kolom <code>Judul</code> sebagai judul spanduk, kolom <code>Status</code> memperlihatkan apakah spanduk aktif atau tidak, serta kolom <code>Akses</code> yang merupakan tombol untuk mengatur data spanduk tersebut. Tombol <a class="btn btn-default btn-xs"><span class="fa fa-edit"><span></a> <code>Edit</code> untuk mengedit spanduk dan tombol <a class="btn btn-default btn-xs"><span class="fa fa-trash"><span></a> <code>Hapus</code> untuk menghapus spanduk. Anda dapat menambahkan spanduk dengan menekan tombol <a class="btn btn-default btn-xs"><span class="fa fa-plus"></span> TAMBAH</a>.</p>';
        $content .= '<p>Jika templat hanya membutuhkan 1 (satu) spanduk saja, maka otomatis akan menampilkan halaman <a onclick="$(\'a[href=\\\'#konten-spanduk-tambahedit\\\']\').click()" style="cursor:pointer;">Tambah & Edit Spanduk</a></p>';
        $contBanner = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/konten-spanduk.png',
            'title' => 'Spanduk',
            'slug' => 'konten-spanduk',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $contOrder++,
            'parent_id' => $cont->id,
            'status' => 'active',
        ]);

        /* Konten - Spanduk - Tambah & Edit */
        $content = 'Halaman untuk menambahkan atau mengedit data spanduk. Anda juga dapat menghapus data dari halaman ini jika dalam mode edit.';
        $content .= '<ul>';
        $content .= '<li><b>Gambar Spanduk</b>, gambar yang akan ditampilkan di situs. Ukuran tergantung pada templat situs yang digunakan.</li>';
        $content .= '<li><b>Judul</b>, judul utama dari spanduk.</li>';
        $content .= '<li><b>Subjudul</b>, judul tambahan atau deskripsi singkat untuk detail spanduk.</li>';
        $content .= '<li><b>Tautan</b>, sebagai penghubung menu tersebut. Terdapat pilihan dari daftar <code>Halaman</code> dan <code>Artikel</code>. Anda juga dapat menggunakan tautan luar dengan memilih <code>Tautan Luar</code>, pastikan selalu menggunakan <code>http://</code> atau <code>http://</code> di awal tautan.</li>';
        $content .= '<li><b>Urutan</b>, sesuaikan urutan spanduk.</li>';
        $content .= '<li><b>Aktif</b>, centang untuk mengaktifkan label.</li>';
        $content .= '<li><b>Hanya Gambar</b>, centang jika hanya ingin menampilkan gambar (misal gambar sudah dilengkapi keterangan sehingga tidak perlu tambahan judul maupun subjudul).</li>';
        $content .= '</ul>';
        $contBannerAdd = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/konten-spanduk-tambah.png',
            'title' => 'Tambah & Edit',
            'slug' => 'konten-spanduk-tambahedit',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $contBannerOrder++,
            'parent_id' => $contBanner->id,
            'status' => 'active',
        ]);

        /* Konten - Spanduk - Hapus */
        $content = '<p>Saat Anda menekan tombol hapus pada salah satu daftar spanduk, akan muncul konfirmasi sebelum data dihapus untuk mencegah terjadinya kesalahan hapus data.</p>';
        $contBannerDel = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/konten-spanduk-hapus.png',
            'title' => 'Hapus',
            'slug' => 'konten-spanduk-hapus',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $contBannerOrder++,
            'parent_id' => $contBanner->id,
            'status' => 'active',
        ]);

        $contPostOrder = 1;
        /* Konten - Artikel */
        $content = '<p>Daftar artikel situs. Kolom <code>Sampul</code> merupakan cuplikan dari gambar sampul, kolom <code>Judul</code> berisi judul artikel, nama penulis, dan juga tombol untuk sebar ke media sosial, kolom <code>Status</code> memperlihatkan apakah artikel aktif atau tidak, serta kolom <code>Akses</code> yang merupakan tombol untuk mengatur data artikel tersebut. Tombol <a class="btn btn-default btn-xs"><span class="fa fa-edit"><span></a> <code>Edit</code> untuk mengedit artikel dan tombol <a class="btn btn-default btn-xs"><span class="fa fa-trash"><span></a> <code>Hapus</code> untuk menghapus artikel. Anda dapat menambahkan artikel dengan menekan tombol <a class="btn btn-default btn-xs"><span class="fa fa-plus"></span> TAMBAH</a>.</p>';
        $contPost = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/konten-artikel.png',
            'title' => 'Artikel',
            'slug' => 'konten-artikel',
            'content' => $content,
            'roles' => 'all',
            'order' => $contOrder++,
            'parent_id' => $cont->id,
            'status' => 'active',
        ]);

        /* Konten - Artikel - Tambah & Edit */
        $content = 'Halaman untuk menambahkan atau mengedit data artikel. Anda juga dapat menghapus data dari halaman ini jika dalam mode edit.';
        $content .= '<ul>';
        $content .= '<li><b>Judul</b>, judul dari artikel.</li>';
        $content .= '<li><b>Tautan otomatis</b>, tautan untuk artikel akan otomatis muncul ketika Anda mengetik judul. Anda juga dapat mengubahnya dengan menekan kolom tautan 2 (dua) kali. Tidak dapat diubah ketika mode edit.</li>';
        $content .= '<li><b>Kutipan/deskripsi</b>, sebagai kalimat atau paragraf cuplikan untuk muncul di halaman daftar artikel atau ketika disebar ke media sosial. Jika tidak diisi maka kutipan akan mengambil data dari konten.</li>';
        $content .= '<li><b>Konten/tulisan</b>, ketikkan tulisan artikel Anda di sini. Anda dapat menformatnya sesuai kebutuhan dengan menggunakan bilah alat yang terdapat di atas kolom konten.</li>';
        $content .= '<li><b>Sampul</b>, gambar yang akan ditampilkan di situs. Ukuran tergantung pada templat situs yang digunakan.</li>';
        $content .= '<li><b>Keterangan Sampul</b>, keterangan untuk menjelaskan sampul secara singkat.</li>';
        $content .= '<li><b>Pelanggan Artikel</b>, untuk memberikan notifikasi kepada pelanggan info/artikel yang sudah terdaftar dan aktif.</li>';
        $content .= '<li><b>Pengunjung</b>, pilihan fitur untuk mendapatkan interaksi dari pengunjung situs terutama pembaca artikel. Fitur ini berhubungan juga dengan <a onclick="$(\'a[href=\\\'#pengaturan-situs-infoutama\\\']\').click()" style="cursor:pointer;">Pengaturan -> Situs -> Akses Pengunjung</a>.</li>';
        $content .= '<li><b>Terbitkan</b>, Anda dapat langsung menerbitkan tulisan artikel Anda atau menyesuaikan waktu untuk menampilkan artikel. Anda juga bisa menyimpannya sebagai draf atau bahkan hanya sekedar catatan tanpa ada tujuan untuk ditampilkan.</li>';
        $content .= '<li><b>Kategori</b>, ketikkan nama kategori untuk mencarinya atau Anda dapat menambahkan kategori baru dengan menekan tombol <a class="btn btn-default btn-xs"><span class="fa fa-plus"></span></a> kemudian isi data di jendela yang muncul.</li>';
        $content .= '<li><b>Label</b>, Anda dapat langsung mencari dan menambahkannya jika label tidak tersedia sebelumnya hanya dengan menekan tombol <i>enter</i>.</li>';
        $content .= '</ul>';
        $contPostAdd = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/konten-artikel-tambah.png',
            'title' => 'Tambah & Edit',
            'slug' => 'konten-artikel-tambahedit',
            'content' => $content,
            'roles' => 'all',
            'order' => $contPostOrder++,
            'parent_id' => $contPost->id,
            'status' => 'active',
        ]);

        /* Konten - Artikel - Hapus */
        $content = '<p>Saat Anda menekan tombol hapus pada salah satu daftar artikel, akan muncul konfirmasi sebelum data dihapus untuk mencegah terjadinya kesalahan hapus data.</p>';
        $contPostDel = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/konten-artikel-hapus.png',
            'title' => 'Hapus',
            'slug' => 'konten-artikel-hapus',
            'content' => $content,
            'roles' => 'all',
            'order' => $contPostOrder++,
            'parent_id' => $contPost->id,
            'status' => 'active',
        ]);

        /* Konten - Halaman */
        $content = '<p>Page merupakan halaman tambahan untuk pendukung situs. Rinciannya adalah kolom <code>Page Title</code> sebagai nama tampilan page (<code>About</code> merupakan page default), kolom <code>URL</code> merupakan URL yang mengarahkan page tersebut, kolom <code>Status</code> memperlihatkan apakah page aktif atau tidak, serta kolom <code>Action</code> yang merupakan tombol untuk mengatur page-page tersebut. Tombol <a class="btn btn-default btn-xs"><span class="fa fa-edit"><span></a> <code>Edit</code> untuk mengedit page dan tombol <a class="btn btn-default btn-xs"><span class="fa fa-trash"><span></a> <code>Delete</code> untuk menghapus page. Anda dapat menambahkannya dengan menekan tombol <a class="btn btn-default btn-sm">+ ADD</a>.</p>';
        $content .= '<h3 id="page-add-edit">Add & Edit</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/guide/010 Page - 02 Overview Edit.png" target="_blank"><img src="/themes/admin/AdminSC/images/guide/010 Page - 02 Overview Edit.png" width="100%"></a></center></p>';
        $content .= '<ul>';
        $content .= '<li><b>Page Title</b>, judul halaman yang dibuat.</li>';
        $content .= '<li><b>URL Name</b>, sebagai penghubung page tersebut. Ini akan muncul di daftar URL menu bagian Page.</li>';
        $content .= '<li><b>Content</b>, berikan isi mengenai page tersebut.</li>';
        $content .= '<li><b>Active</b>, centang untuk mengaktifkan page.</li>';
        $content .= '</ul>';
        $content .= '<h3 id="page-delete">Delete</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/guide/010 Page - 03 Overview Delete.png" target="_blank"><img src="/themes/admin/AdminSC/images/guide/010 Page - 03 Overview Delete.png" width="100%"></a></center></p>';
        $content .= '<p>Saat Anda menekan tombol hapus pada salah satu daftar page, akan ada pop-up konfirmasi sebelum data dihapus. Di dalam konfirmasi terdapat fitur <code>Delete Permanently</code> yang fungsinya adalah untuk benar-benar menghapus data dari database. Jika Anda mencentang fitur ini, maka data tidak dapat dikembalikan sama sekali jika suatu saat dibutuhkan.</p>';
        $contPage = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/010-Page-00-Overview.png',
            'title' => 'Halaman',
            'slug' => 'konten-halaman',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $dataOrder++,
            'parent_id' => $cont->id,
            'status' => 'active',
        ]);

        $galOrder = 1;
        /* Galeri */
        $content = '<p>Grup menu foto dan video.</p>';
        $contGal = Guide::create([
            'title' => 'Galeri',
            'slug' => 'konten-galeri',
            'content' => $content,
            'order' => $dataOrder++,
            'roles' => 'super,admin',
            'parent_id' => $cont->id,
            'status' => 'active',
        ]);

        /* Konten - Galeri - Foto */
        $content = '<p>Daftar album foto. Rinciannya adalah kolom <code>Photo</code> preview foto album, kolom <code>Album</code> adalah nama album, kolom <code>Photos</code> merupakan jumlah foto yang ada di dalam album, kolom <code>Status</code> memperlihatkan apakah album aktif atau tidak, serta kolom <code>Action</code> yang merupakan tombol untuk mengatur album-album tersebut. Tombol <a class="btn btn-default btn-xs"><span class="fa fa-edit"><span></a> <code>Edit</code> untuk mengedit album dan tombol <a class="btn btn-default btn-xs"><span class="fa fa-trash"><span></a> <code>Delete</code> untuk menghapus album. Anda dapat menambahkannya dengan menekan tombol <a class="btn btn-default btn-sm">+ ADD</a>.</p>';
        $content .= '<h3 id="album-add-edit">Add & Edit</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/guide/011 Photo - 02 Overview Edit.png" target="_blank"><img src="/themes/admin/AdminSC/images/guide/011 Photo - 02 Overview Edit.png" width="100%"></a></center></p>';
        $content .= '<ul>';
        $content .= '<li><b>Album Name</b>, nama album yang dibuat.</li>';
        $content .= '<li><b>Description</b>, berikan penjelasan singkat mengenai album tersebut.</li>';
        $content .= '<li><b>Active</b>, centang untuk mengaktifkan album.</li>';
        $content .= '<li><b>Photo Section</b>, klik pada tombol <code>Add Photo</code> untuk menambahkan foto-foto untuk album tersebut.</li>';
        $content .= '</ul>';
        $content .= '<h3 id="album-delete">Delete</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/guide/011 Photo - 03 Overview Delete.png" target="_blank"><img src="/themes/admin/AdminSC/images/guide/011 Photo - 03 Overview Delete.png" width="100%"></a></center></p>';
        $content .= '<p>Saat Anda menekan tombol hapus pada salah satu daftar album, akan ada pop-up konfirmasi sebelum data dihapus. Di dalam konfirmasi terdapat fitur <code>Delete Permanently</code> yang fungsinya adalah untuk benar-benar menghapus data dari database. Jika Anda mencentang fitur ini, maka data tidak dapat dikembalikan sama sekali jika suatu saat dibutuhkan.</p>';
        $contGalPhoto = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/011-Photo-00-Overview.png',
            'title' => 'Foto',
            'slug' => 'konten-galeri-foto',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $galOrder++,
            'parent_id' => $contGal->id,
            'status' => 'active',
        ]);

        /* Konten - Galeri - Video */
        $content = '<p>Daftar video. Rinciannya adalah kolom <code>Photo</code> preview dari video, kolom <code>Video Title</code> adalah nama video, kolom <code>Views</code> merupakan jumlah kunjungan ke halaman video, kolom <code>Status</code> memperlihatkan apakah video aktif atau tidak, serta kolom <code>Action</code> yang merupakan tombol untuk mengatur video-video tersebut. Tombol <a class="btn btn-default btn-xs"><span class="fa fa-edit"><span></a> <code>Edit</code> untuk mengedit video dan tombol <a class="btn btn-default btn-xs"><span class="fa fa-trash"><span></a> <code>Delete</code> untuk menghapus video. Anda dapat menambahkannya dengan menekan tombol <a class="btn btn-default btn-sm">+ ADD</a>.</p>';
        $content .= '<h3 id="video-add-edit">Add & Edit</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/guide/012 Video - 02 Overview Edit.png" target="_blank"><img src="/themes/admin/AdminSC/images/guide/012 Video - 02 Overview Edit.png" width="100%"></a></center></p>';
        $content .= '<ul>';
        $content .= '<li><b>Youtube ID</b>, kode unik video youtube yang diambil dari URL Video Youtube. Contoh: <code>https://www.youtube.com/watch?v=<b>BUiEQyEtVmw</b></code>, copy kode unik yang bercetak tebal tersebut ke dalam kolom Youtube ID.</li>';
        $content .= '<li><b>Video Title</b>, berikan judul untuk video tersebut.</li>';
        $content .= '<li><b>Description</b>, berikan penjelasan singkat mengenai video tersebut.</li>';
        $content .= '<li><b>Active</b>, centang untuk mengaktifkan video.</li>';
        $content .= '</ul>';
        $content .= '<h3 id="video-delete">Delete</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/guide/012 Video - 03 Overview Delete.png" target="_blank"><img src="/themes/admin/AdminSC/images/guide/012 Video - 03 Overview Delete.png" width="100%"></a></center></p>';
        $content .= '<p>Saat Anda menekan tombol hapus pada salah satu daftar video, akan ada pop-up konfirmasi sebelum data dihapus. Di dalam konfirmasi terdapat fitur <code>Delete Permanently</code> yang fungsinya adalah untuk benar-benar menghapus data dari database. Jika Anda mencentang fitur ini, maka data tidak dapat dikembalikan sama sekali jika suatu saat dibutuhkan.</p>';
        $contGalVideo = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/012-Video-00-Overview.png',
            'title' => 'Video',
            'slug' => 'konten-galeri-video',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $galOrder++,
            'parent_id' => $contGal->id,
            'status' => 'active',
        ]);

        $repOrder = 1;
        /* Data */
        $content = '<p>Grup menu ini mengumpulkan data yang dihasilkan dari inputan user, seperti <code>komentar</code> dan juga <code>kontak (inbox)</code>.</p>';
        $report = Guide::create([
            'title' => 'Laporan',
            'slug' => 'laporan',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $mainOrder++,
            'status' => 'active',
        ]);

        /* Laporan - Kotak Masuk */
        $content = '<p>Inbox merupakan pesan masuk yang diinput dari halaman <code>Contact</code>. Rinciannya adalah kolom <code>Name</code> adalah nama pengirim, kolom <code>Email</code> adalah email pengirim, kolom <code>Message</code> merupakan isi pesan yang diberikan oleh pengirim, kolom <code>Status</code> memperlihatkan apakah inbox sudah dibaca atau belum, serta kolom <code>Action</code> yang merupakan tombol untuk mengatur pesan-pesan tersebut. Tombol <a class="btn btn-default btn-xs"><span class="fa fa-list"><span></a> <code>Detail</code> untuk melihat pesan secara keseluruhan dan tombol <a class="btn btn-default btn-xs"><span class="fa fa-trash"><span></a> <code>Delete</code> untuk menghapus inbox.</p>';
        $content .= '<h3 id="inbox-detail">Detail</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/guide/013 Inbox - 01 Overview Detail.png" target="_blank"><img src="/themes/admin/AdminSC/images/guide/013 Inbox - 01 Overview Detail.png" width="100%"></a></center></p>';
        $content .= '<p>Merupakan detail tampilan dari inbox yang dipilih.</p>';
        $content .= '<h3 id="inbox-delete">Delete</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/guide/013 Inbox - 02 Overview Delete.png" target="_blank"><img src="/themes/admin/AdminSC/images/guide/013 Inbox - 02 Overview Delete.png" width="100%"></a></center></p>';
        $content .= '<p>Saat Anda menekan tombol hapus pada salah satu daftar inbox, akan ada pop-up konfirmasi sebelum data dihapus. Di dalam konfirmasi terdapat fitur <code>Delete Permanently</code> yang fungsinya adalah untuk benar-benar menghapus data dari database. Jika Anda mencentang fitur ini, maka data tidak dapat dikembalikan sama sekali jika suatu saat dibutuhkan.</p>';
        $repInbox = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/013-Inbox-00-Overview.png',
            'title' => 'Kotak Masuk',
            'slug' => 'laporan-kotakmasuk',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $repOrder++,
            'parent_id' => $report->id,
            'status' => 'active',
        ]);

        /* Laporan - Komentar */
        $content = '<p>Inbox merupakan pesan masuk yang diinput dari halaman <code>Contact</code>. Rinciannya adalah kolom <code>Name</code> berisi nama dan email pengirim, kolom <code>Comment</code> berisi potongan komentar dan judul artikel yang dikomentari, kolom <code>Approved By</code> nama user yang menyetujui komentar agar bisa ditampilkan di halaman artikel, kolom <code>Status</code> memperlihatkan apakah komentar sudah disetujui atau belum, serta kolom <code>Action</code> yang merupakan tombol untuk mengatur pesan-pesan tersebut. Tombol <a class="btn btn-default btn-xs"><span class="fa fa-reply"><span></a> <code>Reply</code> untuk membalas komentar, <a class="btn btn-default btn-xs"><span class="fa fa-list"><span></a> <code>Detail</code> untuk melihat komentar secara, <a class="btn btn-default btn-xs"><span class="fa fa-list"><span></a> <code>Edit</code> untuk mengedit pesan apabila ada kata yang perlu diubah dan tombol <a class="btn btn-default btn-xs"><span class="fa fa-trash"><span></a> <code>Delete</code> untuk menghapus komentar.</p>';
        $content .= '<h3 id="comments-detail">Reply</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/guide/014 Comment - 01 Overview Reply.png" target="_blank"><img src="/themes/admin/AdminSC/images/guide/014 Comment - 01 Overview Reply.png" width="100%"></a></center></p>';
        $content .= '<ul>';
        $content .= '<li><b>Reply To</b>, preview komentar yang akan dibalas.</li>';
        $content .= '<li><b>Content</b>, kolom untuk mengisi komentar balasan.</li>';
        $content .= '<li><b>Approve</b>, centang untuk langsung menyetujui komentar.</li>';
        $content .= '</ul>';
        $content .= '<h3 id="comments-detail">Detail</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/guide/014 Comment - 02 Overview Detail.png" target="_blank"><img src="/themes/admin/AdminSC/images/guide/014 Comment - 02 Overview Detail.png" width="100%"></a></center></p>';
        $content .= '<p>Merupakan detail tampilan dari komentar yang dipilih. Isinya terdiri dari <code>nama</code>, <code>email</code>, <code>situs</code>, <code>tanggal</code> dan juga <code>komentar</code> dari pengunjung.</p>';
        $content .= '<h3 id="comments-detail">Edit</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/guide/014 Comment - 03 Overview Edit.png" target="_blank"><img src="/themes/admin/AdminSC/images/guide/014 Comment - 03 Overview Edit.png" width="100%"></a></center></p>';
        $content .= '<p>Panel edit komentar, untuk mengubah nama ataupun isi dari komentar.</p>';
        $content .= '<h3 id="comments-delete">Delete</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/guide/014 Comment - 04 Overview Delete.png" target="_blank"><img src="/themes/admin/AdminSC/images/guide/014 Comment - 04 Overview Delete.png" width="100%"></a></center></p>';
        $content .= '<p>Saat Anda menekan tombol hapus pada salah satu daftar komentar, akan ada pop-up konfirmasi sebelum data dihapus. Di dalam konfirmasi terdapat fitur <code>Delete Permanently</code> yang fungsinya adalah untuk benar-benar menghapus data dari database. Jika Anda mencentang fitur ini, maka data tidak dapat dikembalikan sama sekali jika suatu saat dibutuhkan.</p>';
        $repComment = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/014-Comment-00-Overview.png',
            'title' => 'Komentar',
            'slug' => 'laporan-komentar',
            'content' => $content,
            'roles' => 'super,admin,author',
            'order' => $repOrder++,
            'parent_id' => $report->id,
            'status' => 'active',
        ]);

        /* Laporan - Kata Pencarian */
        $content = '<p>Incoming terms merupakan keyword yang masuk berdasarkan hasil pencarian dari mesin pencari atau dari fungsi pencarian di situs. Modul ini berguna sebagai analisis pencarian apa saja yang sering pengunjung lakukan. Rinciannya adalah kolom <code>Host</code> adalah nama mesin pencari yang digunakan, kolom <code>Text</code> adalah keyword yang digunakan pengunjung untuk mencari artikel/halaman, kolom <code>Count</code> untuk menghitung berapa banyak keyword itu digunakan, serta kolom <code>Action</code> yang merupakan tombol untuk mengatur keyword tersebut. Tombol <a class="btn btn-default btn-xs"><span class="fa fa-trash"><span></a> <code>Delete</code> untuk menghapus keyword.</p>';
        $repInterm = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/015-Interm-00-Overview.png',
            'title' => 'Kata Pencarian',
            'slug' => 'laporan-katapencarian',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $repOrder++,
            'parent_id' => $report->id,
            'status' => 'active',
        ]);

        /* Laporan - Pelanggan Info */
        $content = '<p>Daftar email pengunjung yang ingin berlangganan info terbaru. Rinciannya adalah kolom <code>Email</code> adalah email pengirim, kolom <code>Status</code> memperlihatkan apakah status masih berlangganan atau tidak, serta kolom <code>Action</code> yang merupakan tombol untuk mengatur email-email tersebut. Tombol <a class="btn btn-default btn-xs"><span class="fa fa-list"><span></a> <code>Edit</code> untuk mengedit email atau status dan tombol <a class="btn btn-default btn-xs"><span class="fa fa-trash"><span></a> <code>Delete</code> untuk menghapus email.</p>';
        $content .= '<h3 id="subscribers-detail">Edit</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/guide/015 Subscribe - 01 Overview Edit.png" target="_blank"><img src="/themes/admin/AdminSC/images/guide/015 Subscribe - 01 Overview Edit.png" width="100%"></a></center></p>';
        $content .= '<p>Panel edit email, untuk mengubah email atau status.</p>';
        $content .= '<h3 id="subscribers-delete">Delete</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/guide/015 Subscribe - 02 Overview Delete.png" target="_blank"><img src="/themes/admin/AdminSC/images/guide/015 Subscribe - 02 Overview Delete.png" width="100%"></a></center></p>';
        $content .= '<p>Saat Anda menekan tombol hapus pada salah satu daftar email, akan ada pop-up konfirmasi sebelum data dihapus. Di dalam konfirmasi terdapat fitur <code>Delete Permanently</code> yang fungsinya adalah untuk benar-benar menghapus data dari database. Jika Anda mencentang fitur ini, maka data tidak dapat dikembalikan sama sekali jika suatu saat dibutuhkan.</p>';
        $repSubscribers = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/guide/016-Subscribers-00-Overview.png',
            'title' => 'Pelanggan Info',
            'slug' => 'laporan-pelangganinfo',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $repOrder++,
            'parent_id' => $report->id,
            'status' => 'active',
        ]);
    }
}
