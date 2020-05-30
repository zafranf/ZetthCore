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
            'cover' => '/themes/admin/AdminSC/images/help/dasbor.png',
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
            'cover' => '/themes/admin/AdminSC/images/help/pengaturan-situs.png',
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
            'slug' => 'pengaturan-infoutama',
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
            'slug' => 'pengaturan-mediasosial',
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
            'slug' => 'pengaturan-seo',
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
            'slug' => 'pengaturan-lokasi',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $setSiteOrder++,
            'parent_id' => $setSite->id,
            'status' => 'active',
        ]);

        /* Pengaturan - (Grup) Menu */
        $content = '<p>Pengaturan menu situs. Kolom <code>Grup</code> sebagai nama grup menu, kolom <code>Deskripsi</code> merupakan penjelasan singkat mengenai grup menu, kolom <code>Status</code> memperlihatkan apakah grup menu aktif atau tidak, serta kolom <code>Akses</code> yang merupakan tombol untuk mengatur grup menu tersebut. Tombol <a class="btn btn-default btn-xs"><span class="fa fa-edit"><span></a> <code>Edit</code> untuk mengedit grup menu dan tombol <a class="btn btn-default btn-xs"><span class="fa fa-trash"><span></a> <code>Hapus</code> untuk menghapus grup menu. Anda dapat menambahkan grup menu dengan menekan tombol <a class="btn btn-default btn-xs"><span class="fa fa-plus"></span> TAMBAH</a>.</p>';
        $content .= '<h3 id="menu-delete">Hapus</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/help/pengaturan-grupmenu-hapus.png" target="_blank"><img src="/themes/admin/AdminSC/images/help/pengaturan-grupmenu-hapus.png" width="100%"></a></center></p>';
        $content .= '<p>Saat Anda menekan tombol hapus pada salah satu daftar menu, akan muncul konfirmasi sebelum data dihapus. Di dalam konfirmasi terdapat fitur <code>Delete Permanently</code> yang fungsinya adalah untuk benar-benar menghapus data dari database. Jika Anda mencentang fitur ini, maka data tidak dapat dikembalikan sama sekali jika suatu saat dibutuhkan.</p>';
        $setMenu = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/help/pengaturan-grupmenu-daftar.png',
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
        $content = 'Halaman untuk menambahkan data grup menu.';
        $setMenuAdd = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/help/pengaturan-grupmenu-tambah.png',
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
            'cover' => '/themes/admin/AdminSC/images/help/pengaturan-grupmenu-edit.png',
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
            'cover' => '/themes/admin/AdminSC/images/help/pengaturan-grupmenu-edit-tambah.png',
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
            'cover' => '/themes/admin/AdminSC/images/help/pengaturan-grupmenu-edit-hapus.png',
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
            'cover' => '/themes/admin/AdminSC/images/help/pengaturan-grupmenu-hapus.png',
            'title' => 'Hapus',
            'slug' => 'pengaturan-grupmenu-hapus',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $setMenuOrder++,
            'parent_id' => $setMenu->id,
            'status' => 'active',
        ]);

        /* Pengaturan - Peran & Akses */
        $content = '<p>Daftar grup untuk user. Rinciannya adalah kolom <code>Group Name</code> sebagai nama tampilan grup (<code>admin</code>, <code>author</code>, <code>editor</code> dan <code>user</code> merupakan grup default), kolom <code>Description</code> merupakan penjelasan singkat mengenai grup tersebut, kolom <code>Status</code> memperlihatkan apakah grup aktif atau tidak, serta kolom <code>Action</code> yang merupakan tombol untuk mengatur grup-grup tersebut. Tombol <a class="btn btn-default btn-xs"><span class="fa fa-edit"><span></a> <code>Edit</code> untuk mengedit grup dan tombol <a class="btn btn-default btn-xs"><span class="fa fa-trash"><span></a> <code>Delete</code> untuk menghapus grup. Anda dapat menambahkannya dengan menekan tombol <a class="btn btn-default btn-sm">+ ADD</a>.</p>';
        $content .= '<h3 id="group-add-edit">Add & Edit</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/help/004 Group - 02 Overview Edit.png" target="_blank"><img src="/themes/admin/AdminSC/images/help/004 Group - 02 Overview Edit.png" width="100%"></a></center></p>';
        $content .= '<ul>';
        $content .= '<li><b>Group Name</b>, nama grup yang akan tampil di situs.</li>';
        $content .= '<li><b>Description</b>, berikan sedikit penjelasan mengenai grup tersebut.</li>';
        $content .= '<li><b>Active</b>, centang untuk mengaktifkan grup.</li>';
        $content .= '</ul>';
        $content .= '<h3 id="group-delete">Delete</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/help/004 Group - 03 Overview Delete.png" target="_blank"><img src="/themes/admin/AdminSC/images/help/004 Group - 03 Overview Delete.png" width="100%"></a></center></p>';
        $content .= '<p>Saat Anda menekan tombol hapus pada salah satu daftar grup, akan ada pop-up konfirmasi sebelum data dihapus. Di dalam konfirmasi terdapat fitur <code>Delete Permanently</code> yang fungsinya adalah untuk benar-benar menghapus data dari database. Jika Anda mencentang fitur ini, maka data tidak dapat dikembalikan sama sekali jika suatu saat dibutuhkan.</p>';
        $setRole = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/help/004-Role-00-Overview.png',
            'title' => 'Peran & Akses',
            'slug' => 'pengaturan-peran-akses',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $setOrder++,
            'parent_id' => $set->id,
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

        /* Data - Pengguna*/
        $content = '<p>Daftar user situs baik pengurus maupun member. Rinciannya adalah kolom <code>Photo</code> untuk menampilkan foto user, <code>Username</code> menampilkan nama login user, kolom <code>Fullname</code> merupakan nama lengkap user, kolom <code>Email</code> merupakan alamat email user untuk kepentingan komunikasi melalui email, kolom <code>Status</code> memperlihatkan apakah user aktif atau tidak, serta kolom <code>Action</code> yang merupakan tombol untuk mengatur user-user tersebut. Tombol <a class="btn btn-default btn-xs"><span class="fa fa-edit"><span></a> <code>Edit</code> untuk mengedit user dan tombol <a class="btn btn-default btn-xs"><span class="fa fa-trash"><span></a> <code>Delete</code> untuk menghapus user. Anda dapat menambahkannya dengan menekan tombol <a class="btn btn-default btn-sm">+ ADD</a>.</p>';
        $content .= '<h3 id="user-add-edit">Add & Edit</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/help/005 User - 02 Overview Edit.png" target="_blank"><img src="/themes/admin/AdminSC/images/help/005 User - 02 Overview Edit.png" width="100%"></a></center></p>';
        $content .= '<h4 id="user-main-info">Main Info</h4>';
        $content .= '<ul>';
        $content .= '<li><b>Username</b>, nama yang akan digunakan untuk login. Maksimal 20 karakter dan tidak dapat diubah apabila data sudah disimpan.</li>';
        $content .= '<li><b>Email</b>, alamat email user untuk kepentingan komunikasi melalui email. Input dengan alamat email yang valid.</li>';
        $content .= '<li><b>Password</b>, digunakan untuk validasi login. Minimal 6 karakter.</li>';
        $content .= '<li><b>Retype Password</b>, untuk konfirmasi password login.</li>';
        $content .= '<li><b>Fullname</b>, nama lengkap yang akan tampil di situs. Maksimal 50 karakter.</li>';
        $content .= '<li><b>Biography</b>, sedikit ulasan tentang user.</li>';
        $content .= '<li><b>Photo</b>, ukuran foto maksimal lebar 500px dan tinggi 500px.</li>';
        $content .= '<li><b>Group</b>, tentukan grup user untuk mengakses situs.</li>';
        $content .= '<li><b>Active</b>, centang untuk mengaktifkan user.</li>';
        $content .= '</ul>';
        $content .= '<h4 id="user-social-media">Social Media</h4>';
        $content .= 'Sebagai daftar akun social media user. Klik tombol <a class="btn btn-default btn-xs">+ Add</a> untuk menambahkan akun social media lainnya.';
        $content .= '<h3 id="user-delete">Delete</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/help/005 User - 03 Overview Delete.png" target="_blank"><img src="/themes/admin/AdminSC/images/help/005 User - 03 Overview Delete.png" width="100%"></a></center></p>';
        $content .= '<p>Saat Anda menekan tombol hapus pada salah satu daftar user, akan ada pop-up konfirmasi sebelum data dihapus. Di dalam konfirmasi terdapat fitur <code>Delete Permanently</code> yang fungsinya adalah untuk benar-benar menghapus data dari database. Jika Anda mencentang fitur ini, maka data tidak dapat dikembalikan sama sekali jika suatu saat dibutuhkan.</p>';
        $dataUser = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/help/005-User-00-Overview.png',
            'title' => 'Pengguna',
            'slug' => 'data-pengguna',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $dataOrder++,
            'parent_id' => $data->id,
            'status' => 'active',
        ]);

        /* Data - Kategori */
        $content = '<p>Daftar kategori artikel. Rinciannya adalah kolom <code>Category</code> sebagai nama tampilan kategori (<code>Uncategorized</code> merupakan kategori default), kolom <code>Description</code> merupakan penjelasan singkat mengenai kategori tersebut, kolom <code>Parent</code> merupakan atasan dari (sub)kategori, kolom <code>Status</code> memperlihatkan apakah kategori aktif atau tidak, serta kolom <code>Action</code> yang merupakan tombol untuk mengatur kategori-kategori tersebut. Tombol <a class="btn btn-default btn-xs"><span class="fa fa-edit"><span></a> <code>Edit</code> untuk mengedit kategori dan tombol <a class="btn btn-default btn-xs"><span class="fa fa-trash"><span></a> <code>Delete</code> untuk menghapus kategori. Anda dapat menambahkannya dengan menekan tombol <a class="btn btn-default btn-sm">+ ADD</a>.</p>';
        $content .= '<h3 id="categories-add-edit">Add & Edit</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/help/006 Category - 02 Overview Edit.png" target="_blank"><img src="/themes/admin/AdminSC/images/help/006 Category - 02 Overview Edit.png" width="100%"></a></center></p>';
        $content .= '<ul>';
        $content .= '<li><b>Category Name</b>, nama kategori yang akan tampil di situs.</li>';
        $content .= '<li><b>Description</b>, berikan sedikit penjelasan mengenai kategori tersebut.</li>';
        $content .= '<li><b>Parent</b>, tentukan parent apabila kategori yang sedang dibuat merupakan sub-kategori.</li>';
        $content .= '<li><b>Active</b>, centang untuk mengaktifkan kategori.</li>';
        $content .= '</ul>';
        $content .= '<h3 id="categories-delete">Delete</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/help/006 Category - 03 Overview Delete.png" target="_blank"><img src="/themes/admin/AdminSC/images/help/006 Category - 03 Overview Delete.png" width="100%"></a></center></p>';
        $content .= '<p>Saat Anda menekan tombol hapus pada salah satu daftar kategori, akan ada pop-up konfirmasi sebelum data dihapus. Di dalam konfirmasi terdapat fitur <code>Delete Permanently</code> yang fungsinya adalah untuk benar-benar menghapus data dari database. Jika Anda mencentang fitur ini, maka data tidak dapat dikembalikan sama sekali jika suatu saat dibutuhkan.</p>';
        $dataCategory = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/help/006-Category-00-Overview.png',
            'title' => 'Kategori',
            'slug' => 'data-kategori',
            'content' => $content,
            'roles' => 'all',
            'order' => $dataOrder++,
            'parent_id' => $data->id,
            'status' => 'active',
        ]);

        /* Data - Label */
        $content = '<p>Daftar tag artikel. Rinciannya adalah kolom <code>Tag</code> sebagai nama tampilan tag, kolom <code>Description</code> merupakan penjelasan singkat mengenai tag tersebut, kolom <code>Status</code> memperlihatkan apakah tag aktif atau tidak, serta kolom <code>Action</code> yang merupakan tombol untuk mengatur tag-tag tersebut. Tombol <a class="btn btn-default btn-xs"><span class="fa fa-edit"><span></a> <code>Edit</code> untuk mengedit tag dan tombol <a class="btn btn-default btn-xs"><span class="fa fa-trash"><span></a> <code>Delete</code> untuk menghapus tag. Anda dapat menambahkannya dengan menekan tombol <a class="btn btn-default btn-sm">+ ADD</a>.</p>';
        $content .= '<h3 id="tags-add-edit">Add & Edit</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/help/007 Tag - 02 Overview Edit.png" target="_blank"><img src="/themes/admin/AdminSC/images/help/007 Tag - 02 Overview Edit.png" width="100%"></a></center></p>';
        $content .= '<ul>';
        $content .= '<li><b>Tag Name</b>, nama tag yang akan tampil di situs.</li>';
        $content .= '<li><b>Description</b>, berikan sedikit penjelasan mengenai tag tersebut.</li>';
        $content .= '<li><b>Active</b>, centang untuk mengaktifkan tag.</li>';
        $content .= '</ul>';
        $content .= '<h3 id="tags-delete">Delete</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/help/007 Tag - 03 Overview Delete.png" target="_blank"><img src="/themes/admin/AdminSC/images/help/007 Tag - 03 Overview Delete.png" width="100%"></a></center></p>';
        $content .= '<p>Saat Anda menekan tombol hapus pada salah satu daftar tag, akan ada pop-up konfirmasi sebelum data dihapus. Di dalam konfirmasi terdapat fitur <code>Delete Permanently</code> yang fungsinya adalah untuk benar-benar menghapus data dari database. Jika Anda mencentang fitur ini, maka data tidak dapat dikembalikan sama sekali jika suatu saat dibutuhkan.</p>';
        $dataTag = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/help/007-Tag-00-Overview.png',
            'title' => 'Label',
            'slug' => 'data-label',
            'content' => $content,
            'roles' => 'all',
            'order' => $dataOrder++,
            'parent_id' => $data->id,
            'status' => 'active',
        ]);

        $contentOrder = 1;
        /* Konten */
        $content = '<p>Atur konten situs Anda melalui grup menu ini.</p>';
        $cont = Guide::create([
            'title' => 'Konten',
            'slug' => 'konten',
            'content' => $content,
            'roles' => 'all',
            'order' => $mainOrder++,
            'status' => 'active',
        ]);

        /* Konten - Spanduk */
        $content = '<p>Daftar banner halaman depan situs. Rinciannya adalah kolom <code>Image</code> merupakan preview dari gambar banner, kolom <code>Banner Name</code> sebagai nama tampilan banner, kolom <code>URL</code> merupakan URL yang mengarahkan banner tersebut, kolom <code>Status</code> memperlihatkan apakah banner aktif atau tidak, serta kolom <code>Action</code> yang merupakan tombol untuk mengatur banner-banner tersebut. Tombol <a class="btn btn-default btn-xs"><span class="fa fa-edit"><span></a> <code>Edit</code> untuk mengedit banner dan tombol <a class="btn btn-default btn-xs"><span class="fa fa-trash"><span></a> <code>Delete</code> untuk menghapus banner. Anda dapat menambahkannya dengan menekan tombol <a class="btn btn-default btn-sm">+ ADD</a>.</p>';
        $content .= '<h3 id="banner-add-edit">Add & Edit</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/help/008 Banner - 02 Overview Edit.png" target="_blank"><img src="/themes/admin/AdminSC/images/help/008 Banner - 02 Overview Edit.png" width="100%"></a></center></p>';
        $content .= '<ul>';
        $content .= '<li><b>Banner Image</b>, upload gambar banner Anda di sini. Harap ukuran gambar disesuaikan terlebih dahulu sebelum diupload.</li>';
        $content .= '<li><b>Title</b>, berikan judul banner.</li>';
        $content .= '<li><b>Description</b>, berikan sedikit penjelasan mengenai banner tersebut.</li>';
        $content .= '<li><b>URL</b>, sebagai penghubung banner tersebut. Terdapat pilihan dari daftar <code>Page</code> dan juga <code>Article</code>. Anda juga dapat menggunakan tautan luar dengan memilih <code>External Link</code>, jangan lupa untuk menggunakan <code>http://</code> di awal URL.</li>';
        $content .= '<li><b>Target</b>, <code>_self</code> untuk membuka tautan pada tab browser yang sedang aktif. <code>_blank</code> untuk membuka tautan dengan tab browser yang baru. <code>_parent</code> dan <code>_top</code> dapat digunakan jika situs berada di dalam iframe.</li>';
        $content .= '<li><b>Active</b>, centang untuk mengaktifkan banner.</li>';
        $content .= '<li><b>Image Only</b>, centang apabila hanya ingin menampilkan gambar banner saja (tanpa judul dan keterangan).</li>';
        $content .= '</ul>';
        $content .= '<h3 id="banner-delete">Delete</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/help/008 Banner - 03 Overview Delete.png" target="_blank"><img src="/themes/admin/AdminSC/images/help/007 Tag - 03 Overview Delete.png" width="100%"></a></center></p>';
        $content .= '<p>Saat Anda menekan tombol hapus pada salah satu daftar banner, akan ada pop-up konfirmasi sebelum data dihapus. Di dalam konfirmasi terdapat fitur <code>Delete Permanently</code> yang fungsinya adalah untuk benar-benar menghapus data dari database. Jika Anda mencentang fitur ini, maka data tidak dapat dikembalikan sama sekali jika suatu saat dibutuhkan.</p>';
        $contBanner = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/help/008-Banner-00-Overview.png',
            'title' => 'Spanduk',
            'slug' => 'konten-spanduk',
            'content' => $content,
            'roles' => 'super,admin',
            'order' => $dataOrder++,
            'parent_id' => $cont->id,
            'status' => 'active',
        ]);

        /* Konten - Artikel */
        $content = '<p>Daftar artikel situs. Rinciannya adalah kolom <code>Cover</code> untuk preview gambar sampul artikel, kolom <code>Title</code> berisi judul artikel, nama penulis, kategori dan juga tombol share, kolom <code>Stats</code> merupakan statistik kunjungan, like, share dan komentar, kolom <code>Status</code> memperlihatkan apakah artikel aktif atau tidak, serta kolom <code>Action</code> yang merupakan tombol untuk mengatur artikel-artikel tersebut. Tombol <a class="btn btn-default btn-xs"><span class="fa fa-list"><span></a> <code>Detail</code> untuk melihat artikel secara keseluruhan, tombol <a class="btn btn-default btn-xs"><span class="fa fa-edit"><span></a> <code>Edit</code> untuk mengedit artikel dan tombol <a class="btn btn-default btn-xs"><span class="fa fa-trash"><span></a> <code>Delete</code> untuk menghapus artikel. Anda dapat menambahkannya dengan menekan tombol <a class="btn btn-default btn-sm">+ ADD</a>.</p>';
        $content .= '<h3 id="posts-detail">Detail</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/help/009 Post - 01 Overview Detail.png" target="_blank"><img src="/themes/admin/AdminSC/images/help/009 Post - 01 Overview Detail.png" width="100%"></a></center></p>';
        $content .= '<p>Merupakan detail tampilan dari artikel yang dipilih. Di dalamnya terdapat <code>Judul</code>, <code>Tanggal</code>, <code>Kategori</code>, tombol <code>Share</code> yang disertai tombol <code>Action</code>, <code>Konten</code> dan juga <code>Tag</code>.</p>';
        $content .= '<h3 id="posts-add-edit">Add & Edit</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/help/009 Post - 03 Overview Edit.png" target="_blank"><img src="/themes/admin/AdminSC/images/help/009 Post - 03 Overview Edit.png" width="100%"></a></center></p>';
        $content .= '<ul>';
        $content .= '<li><b>Title</b>, judul artikel yang akan ditulis.</li>';
        $content .= '<li><b>Friendly URL</b>, URL untuk menuju artikel yang ditulis. Kolom ini menyesuaikan dengan apa yang ada di kolom judul, Anda perlu mengklik 2 (dua) kali untuk mengubahnya.</li>';
        $content .= '<li><b>Excerption</b>, tambahkan kutipan jika ada.</li>';
        $content .= '<li><b>Content</b>, ketikkan tulisan Anda disini. Gunakan toolbar untuk mempercantik tulisan Anda.</li>';
        $content .= '<li><b>Cover</b>, akan lebih baik jika Anda menambahkan gambar untuk sampul artikel tersebut. <code>No Cover</code> untuk membatalkan penggunaan cover di artikel.</li>';
        $content .= '<li><b>Category</b>, tambahkan kategori untuk artike] tersebut. Sifatnya adalah wajib isi. Ketikkan minimal 1 (satu) huruf untuk menampilkan daftar kategori yang tersedia. Anda bisa menambahkannya dengan menekan tombol <a class="btn btn-default btn-xs">+ Add</a>.</li>';
        $content .= '<li><b>Tag</b>, tambahkan tag untuk artikel tersebut. Sifatnya adalah wajib isi. Ketikkan minimal 1 (satu) huruf untuk menampilkan daftar tag yang tersedia. Atau langsung tekan enter untuk menambahkan tag baru.</li>';
        $content .= '<li><b>Time</b>, tanggal dan jam. Tentukan waktu untuk publish artikel.</li>';
        $content .= '<li><b>Visitor</b>, fitur tambahan untuk artikel yang ditujukan untuk pengunjung. Fitur ini terintegrasi dengan pengaturan <code>Site Config</code>. Apabila salah satu dari pengaturan ini dan <code>Site Config</code> tersebut tidak aktif maka fitur ini tidak akan tampil.</li>';
        $content .= '<li><b>Publish</b>, pilih <code>Yes</code> untuk langsung mempublish artikel.</li>';
        $content .= '</ul>';
        $content .= '<h3 id="posts-delete">Delete</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/help/009 Post - 04 Overview Delete.png" target="_blank"><img src="/themes/admin/AdminSC/images/help/009 Post - 04 Overview Delete.png" width="100%"></a></center></p>';
        $content .= '<p>Saat Anda menekan tombol hapus pada salah satu daftar artikel, akan ada pop-up konfirmasi sebelum data dihapus. Di dalam konfirmasi terdapat fitur <code>Delete Permanently</code> yang fungsinya adalah untuk benar-benar menghapus data dari database. Jika Anda mencentang fitur ini, maka data tidak dapat dikembalikan sama sekali jika suatu saat dibutuhkan.</p>';
        $contPost = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/help/009-Post-00-Overview.png',
            'title' => 'Artikel',
            'slug' => 'konten-artikel',
            'content' => $content,
            'roles' => 'all',
            'order' => $dataOrder++,
            'parent_id' => $cont->id,
            'status' => 'active',
        ]);

        /* Konten - Halaman */
        $content = '<p>Page merupakan halaman tambahan untuk pendukung situs. Rinciannya adalah kolom <code>Page Title</code> sebagai nama tampilan page (<code>About</code> merupakan page default), kolom <code>URL</code> merupakan URL yang mengarahkan page tersebut, kolom <code>Status</code> memperlihatkan apakah page aktif atau tidak, serta kolom <code>Action</code> yang merupakan tombol untuk mengatur page-page tersebut. Tombol <a class="btn btn-default btn-xs"><span class="fa fa-edit"><span></a> <code>Edit</code> untuk mengedit page dan tombol <a class="btn btn-default btn-xs"><span class="fa fa-trash"><span></a> <code>Delete</code> untuk menghapus page. Anda dapat menambahkannya dengan menekan tombol <a class="btn btn-default btn-sm">+ ADD</a>.</p>';
        $content .= '<h3 id="page-add-edit">Add & Edit</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/help/010 Page - 02 Overview Edit.png" target="_blank"><img src="/themes/admin/AdminSC/images/help/010 Page - 02 Overview Edit.png" width="100%"></a></center></p>';
        $content .= '<ul>';
        $content .= '<li><b>Page Title</b>, judul halaman yang dibuat.</li>';
        $content .= '<li><b>URL Name</b>, sebagai penghubung page tersebut. Ini akan muncul di daftar URL menu bagian Page.</li>';
        $content .= '<li><b>Content</b>, berikan isi mengenai page tersebut.</li>';
        $content .= '<li><b>Active</b>, centang untuk mengaktifkan page.</li>';
        $content .= '</ul>';
        $content .= '<h3 id="page-delete">Delete</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/help/010 Page - 03 Overview Delete.png" target="_blank"><img src="/themes/admin/AdminSC/images/help/010 Page - 03 Overview Delete.png" width="100%"></a></center></p>';
        $content .= '<p>Saat Anda menekan tombol hapus pada salah satu daftar page, akan ada pop-up konfirmasi sebelum data dihapus. Di dalam konfirmasi terdapat fitur <code>Delete Permanently</code> yang fungsinya adalah untuk benar-benar menghapus data dari database. Jika Anda mencentang fitur ini, maka data tidak dapat dikembalikan sama sekali jika suatu saat dibutuhkan.</p>';
        $contPage = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/help/010-Page-00-Overview.png',
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
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/help/011 Photo - 02 Overview Edit.png" target="_blank"><img src="/themes/admin/AdminSC/images/help/011 Photo - 02 Overview Edit.png" width="100%"></a></center></p>';
        $content .= '<ul>';
        $content .= '<li><b>Album Name</b>, nama album yang dibuat.</li>';
        $content .= '<li><b>Description</b>, berikan penjelasan singkat mengenai album tersebut.</li>';
        $content .= '<li><b>Active</b>, centang untuk mengaktifkan album.</li>';
        $content .= '<li><b>Photo Section</b>, klik pada tombol <code>Add Photo</code> untuk menambahkan foto-foto untuk album tersebut.</li>';
        $content .= '</ul>';
        $content .= '<h3 id="album-delete">Delete</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/help/011 Photo - 03 Overview Delete.png" target="_blank"><img src="/themes/admin/AdminSC/images/help/011 Photo - 03 Overview Delete.png" width="100%"></a></center></p>';
        $content .= '<p>Saat Anda menekan tombol hapus pada salah satu daftar album, akan ada pop-up konfirmasi sebelum data dihapus. Di dalam konfirmasi terdapat fitur <code>Delete Permanently</code> yang fungsinya adalah untuk benar-benar menghapus data dari database. Jika Anda mencentang fitur ini, maka data tidak dapat dikembalikan sama sekali jika suatu saat dibutuhkan.</p>';
        $contGalPhoto = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/help/011-Photo-00-Overview.png',
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
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/help/012 Video - 02 Overview Edit.png" target="_blank"><img src="/themes/admin/AdminSC/images/help/012 Video - 02 Overview Edit.png" width="100%"></a></center></p>';
        $content .= '<ul>';
        $content .= '<li><b>Youtube ID</b>, kode unik video youtube yang diambil dari URL Video Youtube. Contoh: <code>https://www.youtube.com/watch?v=<b>BUiEQyEtVmw</b></code>, copy kode unik yang bercetak tebal tersebut ke dalam kolom Youtube ID.</li>';
        $content .= '<li><b>Video Title</b>, berikan judul untuk video tersebut.</li>';
        $content .= '<li><b>Description</b>, berikan penjelasan singkat mengenai video tersebut.</li>';
        $content .= '<li><b>Active</b>, centang untuk mengaktifkan video.</li>';
        $content .= '</ul>';
        $content .= '<h3 id="video-delete">Delete</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/help/012 Video - 03 Overview Delete.png" target="_blank"><img src="/themes/admin/AdminSC/images/help/012 Video - 03 Overview Delete.png" width="100%"></a></center></p>';
        $content .= '<p>Saat Anda menekan tombol hapus pada salah satu daftar video, akan ada pop-up konfirmasi sebelum data dihapus. Di dalam konfirmasi terdapat fitur <code>Delete Permanently</code> yang fungsinya adalah untuk benar-benar menghapus data dari database. Jika Anda mencentang fitur ini, maka data tidak dapat dikembalikan sama sekali jika suatu saat dibutuhkan.</p>';
        $contGalVideo = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/help/012-Video-00-Overview.png',
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
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/help/013 Inbox - 01 Overview Detail.png" target="_blank"><img src="/themes/admin/AdminSC/images/help/013 Inbox - 01 Overview Detail.png" width="100%"></a></center></p>';
        $content .= '<p>Merupakan detail tampilan dari inbox yang dipilih.</p>';
        $content .= '<h3 id="inbox-delete">Delete</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/help/013 Inbox - 02 Overview Delete.png" target="_blank"><img src="/themes/admin/AdminSC/images/help/013 Inbox - 02 Overview Delete.png" width="100%"></a></center></p>';
        $content .= '<p>Saat Anda menekan tombol hapus pada salah satu daftar inbox, akan ada pop-up konfirmasi sebelum data dihapus. Di dalam konfirmasi terdapat fitur <code>Delete Permanently</code> yang fungsinya adalah untuk benar-benar menghapus data dari database. Jika Anda mencentang fitur ini, maka data tidak dapat dikembalikan sama sekali jika suatu saat dibutuhkan.</p>';
        $repInbox = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/help/013-Inbox-00-Overview.png',
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
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/help/014 Comment - 01 Overview Reply.png" target="_blank"><img src="/themes/admin/AdminSC/images/help/014 Comment - 01 Overview Reply.png" width="100%"></a></center></p>';
        $content .= '<ul>';
        $content .= '<li><b>Reply To</b>, preview komentar yang akan dibalas.</li>';
        $content .= '<li><b>Content</b>, kolom untuk mengisi komentar balasan.</li>';
        $content .= '<li><b>Approve</b>, centang untuk langsung menyetujui komentar.</li>';
        $content .= '</ul>';
        $content .= '<h3 id="comments-detail">Detail</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/help/014 Comment - 02 Overview Detail.png" target="_blank"><img src="/themes/admin/AdminSC/images/help/014 Comment - 02 Overview Detail.png" width="100%"></a></center></p>';
        $content .= '<p>Merupakan detail tampilan dari komentar yang dipilih. Isinya terdiri dari <code>nama</code>, <code>email</code>, <code>situs</code>, <code>tanggal</code> dan juga <code>komentar</code> dari pengunjung.</p>';
        $content .= '<h3 id="comments-detail">Edit</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/help/014 Comment - 03 Overview Edit.png" target="_blank"><img src="/themes/admin/AdminSC/images/help/014 Comment - 03 Overview Edit.png" width="100%"></a></center></p>';
        $content .= '<p>Panel edit komentar, untuk mengubah nama ataupun isi dari komentar.</p>';
        $content .= '<h3 id="comments-delete">Delete</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/help/014 Comment - 04 Overview Delete.png" target="_blank"><img src="/themes/admin/AdminSC/images/help/014 Comment - 04 Overview Delete.png" width="100%"></a></center></p>';
        $content .= '<p>Saat Anda menekan tombol hapus pada salah satu daftar komentar, akan ada pop-up konfirmasi sebelum data dihapus. Di dalam konfirmasi terdapat fitur <code>Delete Permanently</code> yang fungsinya adalah untuk benar-benar menghapus data dari database. Jika Anda mencentang fitur ini, maka data tidak dapat dikembalikan sama sekali jika suatu saat dibutuhkan.</p>';
        $repComment = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/help/014-Comment-00-Overview.png',
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
            'cover' => '/themes/admin/AdminSC/images/help/015-Interm-00-Overview.png',
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
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/help/015 Subscribe - 01 Overview Edit.png" target="_blank"><img src="/themes/admin/AdminSC/images/help/015 Subscribe - 01 Overview Edit.png" width="100%"></a></center></p>';
        $content .= '<p>Panel edit email, untuk mengubah email atau status.</p>';
        $content .= '<h3 id="subscribers-delete">Delete</h3>';
        $content .= '<p><center><a href="/themes/admin/AdminSC/images/help/015 Subscribe - 02 Overview Delete.png" target="_blank"><img src="/themes/admin/AdminSC/images/help/015 Subscribe - 02 Overview Delete.png" width="100%"></a></center></p>';
        $content .= '<p>Saat Anda menekan tombol hapus pada salah satu daftar email, akan ada pop-up konfirmasi sebelum data dihapus. Di dalam konfirmasi terdapat fitur <code>Delete Permanently</code> yang fungsinya adalah untuk benar-benar menghapus data dari database. Jika Anda mencentang fitur ini, maka data tidak dapat dikembalikan sama sekali jika suatu saat dibutuhkan.</p>';
        $repSubscribers = Guide::create([
            'cover' => '/themes/admin/AdminSC/images/help/016-Subscribers-00-Overview.png',
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
