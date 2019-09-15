<?php

use Illuminate\Database\Seeder;
use ZetthCore\Models\Menu;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->super();
        $this->admin();
        $this->web();
    }

    public function super()
    {
        $mainOrder = 1;
        /* menu dashboard */
        $dash = new Menu;
        $dash->name = 'Dasbor';
        $dash->description = 'Halaman utama aplikasi';
        $dash->route_name = 'admin.dashboard.index';
        // $dash->icon = 'fa fa-dashboard';
        $dash->target = '_self';
        $dash->order = $mainOrder++;
        $dash->status = 1;
        $dash->index = 1;
        $dash->save();

        $setOrder = 1;
        /* menu pengaturan (grup) */
        $set = new Menu;
        $set->name = 'Pengaturan';
        $set->description = 'Grup menu pegaturan';
        $set->route_name = 'admin.setting.index';
        // $set->icon = 'fa fa-cog';
        $set->order = $mainOrder++;
        $set->status = 1;
        $set->index = 1;
        $set->save();

        /* menu aplikasi */
        $setApl = new Menu;
        $setApl->name = 'Aplikasi';
        $setApl->description = 'Menu pengaturan aplikasi';
        $setApl->route_name = 'admin.setting.application.index';
        // $setApl->icon = 'fa fa-desktop';
        $setApl->order = $setOrder++;
        $setApl->status = 1;
        $setApl->parent_id = $set->id;
        $setApl->index = 1;
        $setApl->create = 0;
        $setApl->read = 0;
        $setApl->update = 1;
        $setApl->delete = 0;
        $setApl->save();

        /* menu menu */
        $setMenu = new Menu;
        $setMenu->name = 'Menu';
        $setMenu->description = 'Menu pengaturan menu grup';
        $setMenu->route_name = 'admin.setting.menu-groups.index';
        // $setMenu->icon = 'fa fa-menu';
        $setMenu->order = $setOrder++;
        $setMenu->status = 1;
        $setMenu->parent_id = $set->id;
        $setMenu->index = 1;
        $setMenu->create = 1;
        $setMenu->read = 0;
        $setMenu->update = 1;
        $setMenu->delete = 1;
        $setMenu->save();

        /* menu peran */
        $setRole = new Menu;
        $setRole->name = 'Peran dan Akses';
        $setRole->description = 'Menu pengaturan peran dan akses';
        $setRole->route_name = 'admin.setting.roles.index';
        // $setRole->icon = 'fa fa-key';
        $setRole->order = $setOrder++;
        $setRole->status = 1;
        $setRole->parent_id = $set->id;
        $setRole->index = 1;
        $setRole->create = 1;
        $setRole->read = 0;
        $setRole->update = 1;
        $setRole->delete = 1;
        $setRole->save();

        $dataOrder = 1;
        /* menu data (grup) */
        $data = new Menu;
        $data->name = 'Data';
        $data->description = 'Grup menu data';
        $data->route_name = 'admin.data.index';
        // $data->icon = 'fa fa-cog';
        $data->order = $mainOrder++;
        $data->status = 1;
        $data->index = 1;
        $data->save();

        /* menu pengguna */
        $dataUser = new Menu;
        $dataUser->name = 'Pengguna';
        $dataUser->description = 'Menu pengaturan pengguna';
        $dataUser->route_name = 'admin.data.users.index';
        // $dataUser->icon = 'fa fa-user';
        $dataUser->order = $dataOrder++;
        $dataUser->status = 1;
        $dataUser->parent_id = $data->id;
        $dataUser->index = 1;
        $dataUser->create = 1;
        $dataUser->read = 1;
        $dataUser->update = 1;
        $dataUser->delete = 1;
        $dataUser->save();

        /* menu kategori */
        $dataCat = new Menu;
        $dataCat->name = 'Kategori';
        $dataCat->description = 'Menu pengaturan kategori';
        $dataCat->route_name = 'admin.data.categories.index';
        // $dataCat->icon = 'pg-unordered_list';
        $dataCat->order = $dataOrder++;
        $dataCat->status = 1;
        $dataCat->parent_id = $data->id;
        $dataCat->index = 1;
        $dataCat->create = 1;
        $dataCat->read = 0;
        $dataCat->update = 1;
        $dataCat->delete = 1;
        $dataCat->save();

        /* menu label */
        $dataTag = new Menu;
        $dataTag->name = 'Label';
        $dataTag->description = 'Menu pengaturan label';
        $dataTag->route_name = 'admin.data.tags.index';
        // $dataTag->icon = 'fa fa-list';
        $dataTag->order = $dataOrder++;
        $dataTag->status = 1;
        $dataTag->parent_id = $data->id;
        $dataTag->index = 1;
        $dataTag->create = 1;
        $dataTag->read = 0;
        $dataTag->update = 1;
        $dataTag->delete = 1;
        $dataTag->save();

        $contentOrder = 1;
        /* menu konten (grup) */
        $content = new Menu;
        $content->name = 'Konten';
        $content->description = 'Grup menu konten';
        $content->route_name = 'admin.content.index';
        // $content->icon = 'fa fa-edit';
        $content->order = $mainOrder++;
        $content->status = 1;
        $content->index = 1;
        $content->save();

        /* menu spanduk */
        $contentBanner = new Menu;
        $contentBanner->name = 'Spanduk';
        $contentBanner->description = 'Menu pengaturan spanduk';
        $contentBanner->route_name = 'admin.content.banners.index';
        // $contentBanner->icon = 'pg-tablet';
        $contentBanner->order = $contentOrder++;
        $contentBanner->status = 1;
        $contentBanner->parent_id = $content->id;
        $contentBanner->index = 1;
        $contentBanner->create = 1;
        $contentBanner->read = 0;
        $contentBanner->update = 1;
        $contentBanner->delete = 1;
        $contentBanner->save();

        /* menu halaman */
        $contentPage = new Menu;
        $contentPage->name = 'Halaman';
        $contentPage->description = 'Menu pengaturan halaman';
        $contentPage->route_name = 'admin.content.pages.index';
        // $contentPage->icon = 'fa fa-file-text';
        $contentPage->order = $contentOrder++;
        $contentPage->status = 1;
        $contentPage->parent_id = $content->id;
        $contentPage->index = 1;
        $contentPage->create = 1;
        $contentPage->read = 1;
        $contentPage->update = 1;
        $contentPage->delete = 1;
        $contentPage->save();

        /* menu artikel */
        $contentPost = new Menu;
        $contentPost->name = 'Artikel';
        $contentPost->description = 'Menu pengaturan artikel';
        $contentPost->route_name = 'admin.content.posts.index';
        // $contentPost->icon = 'fa fa-newspaper-o';
        $contentPost->order = $contentOrder++;
        $contentPost->status = 1;
        $contentPost->parent_id = $content->id;
        $contentPost->index = 1;
        $contentPost->create = 1;
        $contentPost->read = 1;
        $contentPost->update = 1;
        $contentPost->delete = 1;
        $contentPost->save();

        $galOrder = 1;
        /* menu galeri (grup) */
        $gallery = new Menu;
        $gallery->name = 'Galeri';
        $gallery->description = 'Grup menu galeri';
        $gallery->route_name = 'admin.content.gallery.index';
        // $gallery->icon = 'fa fa-camera';
        $gallery->order = $contentOrder++;
        $gallery->status = 1;
        $gallery->parent_id = $content->id;
        $gallery->index = 1;
        $gallery->save();

        /* menu foto */
        $galPhoto = new Menu;
        $galPhoto->name = 'Foto';
        $galPhoto->description = 'Menu pengaturan foto';
        $galPhoto->route_name = 'admin.content.gallery.photos.index';
        // $galPhoto->icon = 'fa fa-photo';
        $galPhoto->order = $galOrder++;
        $galPhoto->status = 1;
        $galPhoto->parent_id = $gallery->id;
        $galPhoto->index = 1;
        $galPhoto->create = 1;
        $galPhoto->read = 0;
        $galPhoto->update = 1;
        $galPhoto->delete = 1;
        $galPhoto->save();

        /* menu video */
        $galVideo = new Menu;
        $galVideo->name = 'Video';
        $galVideo->description = 'Menu pengaturan video';
        $galVideo->route_name = 'admin.content.gallery.videos.index';
        // $galVideo->icon = 'pg-video';
        $galVideo->order = $galOrder++;
        $galVideo->status = 1;
        $galVideo->parent_id = $gallery->id;
        $galVideo->index = 1;
        $galVideo->create = 1;
        $galVideo->read = 0;
        $galVideo->update = 1;
        $galVideo->delete = 1;
        $galVideo->save();

        $repOrder = 1;
        /* menu laporan (grup) */
        $report = new Menu;
        $report->name = 'Laporan';
        $report->description = 'Grup menu laporan';
        $report->route_name = 'admin.report.index';
        // $report->icon = 'pg-charts';
        $report->order = $mainOrder++;
        $report->status = 1;
        $report->index = 1;
        $report->save();

        /* menu kontak masuk */
        $repInbox = new Menu;
        $repInbox->name = 'Kotak Masuk';
        $repInbox->description = 'Menu pengaturan kotak masuk';
        $repInbox->route_name = 'admin.report.inbox.index';
        // $repInbox->icon = 'pg-mail';
        $repInbox->order = $repOrder++;
        $repInbox->status = 1;
        $repInbox->parent_id = $report->id;
        $repInbox->index = 1;
        $repInbox->create = 0;
        $repInbox->read = 1;
        $repInbox->update = 0;
        $repInbox->delete = 1;
        $repInbox->save();

        /* menu komentar */
        $repComment = new Menu;
        $repComment->name = 'Komentar';
        $repComment->description = 'Menu pengaturan komentar';
        $repComment->route_name = 'admin.report.comments.index';
        // $repComment->icon = 'fa fa-comments';
        $repComment->order = $repOrder++;
        $repComment->status = 1;
        $repComment->parent_id = $report->id;
        $repComment->index = 1;
        $repComment->create = 1;
        $repComment->read = 1;
        $repComment->update = 1;
        $repComment->delete = 1;
        $repComment->save();

        /* menu kata pencarian */
        $repInterm = new Menu;
        $repInterm->name = 'Kata Pencarian';
        $repInterm->description = 'Menu pengaturan kata pencarian';
        $repInterm->route_name = 'admin.report.incoming-terms.index';
        // $repInterm->icon = 'pg-search';
        $repInterm->order = $repOrder++;
        $repInterm->status = 1;
        $repInterm->parent_id = $report->id;
        $repInterm->index = 1;
        $repInterm->create = 0;
        $repInterm->read = 0;
        $repInterm->update = 0;
        $repInterm->delete = 0;
        $repInterm->save();

        /* menu pelanggan info */
        $repSubscriber = new Menu;
        $repSubscriber->name = 'Pelanggan Info';
        $repSubscriber->description = 'Menu laporan pelanggan info';
        $repSubscriber->route_name = 'admin.report.subscribers.index';
        // $repSubscriber->icon = 'fa fa-users';
        $repSubscriber->order = $repOrder++;
        $repSubscriber->status = 1;
        $repSubscriber->parent_id = $report->id;
        $repSubscriber->index = 1;
        $repSubscriber->create = 0;
        $repSubscriber->read = 0;
        $repSubscriber->update = 1;
        $repSubscriber->delete = 1;
        $repSubscriber->save();

        $logOrder = 1;
        /* menu catatan (grup) */
        $log = new Menu;
        $log->name = 'Catatan';
        $log->description = 'Grup menu catatan';
        $log->route_name = 'admin.log.index';
        // $log->icon = 'pg-note';
        $log->order = $mainOrder++;
        $log->status = 1;
        $log->index = 1;
        $log->save();

        /* menu catatan aktifitas */
        $logActivity = new Menu;
        $logActivity->name = 'Aktifitas';
        $logActivity->description = 'Menu catatan aktifitas';
        $logActivity->route_name = 'admin.log.activities.index';
        // $logActivity->icon = 'fa fa-list';
        $logActivity->order = $logOrder++;
        $logActivity->status = 1;
        $logActivity->parent_id = $log->id;
        $logActivity->index = 1;
        $logActivity->create = 0;
        $logActivity->read = 1;
        $logActivity->update = 0;
        $logActivity->delete = 0;
        $logActivity->save();

        /* menu catatan galat */
        $logError = new Menu;
        $logError->name = 'Galat';
        $logError->description = 'Menu catatan galat';
        $logError->route_name = 'admin.log.errors.index';
        // $logError->icon = 'fa fa-list-ul';
        $logError->order = $logOrder++;
        $logError->status = 1;
        $logError->parent_id = $log->id;
        $logError->index = 1;
        $logError->create = 0;
        $logError->read = 1;
        $logError->update = 0;
        $logError->delete = 0;
        $logError->save();

        /* menu catatan kunjungan */
        $logVisitor = new Menu;
        $logVisitor->name = 'Kunjungan';
        $logVisitor->description = 'Menu catatan kunjungan';
        $logVisitor->route_name = 'admin.log.visitors.index';
        // $logVisitor->icon = 'fa fa-list-ul';
        $logVisitor->order = $logOrder++;
        $logVisitor->status = 1;
        $logVisitor->parent_id = $log->id;
        $logVisitor->index = 1;
        $logVisitor->create = 0;
        $logVisitor->read = 1;
        $logVisitor->update = 0;
        $logVisitor->delete = 0;
        $logVisitor->save();
    }

    public function admin()
    {
        $mainOrder = 1;
        /* menu dashboard */
        $dash = new Menu;
        $dash->name = 'Dasbor';
        $dash->description = 'Halaman utama aplikasi';
        $dash->route_name = 'admin.dashboard.index';
        // $dash->icon = 'fa fa-dashboard';
        $dash->target = '_self';
        $dash->order = $mainOrder++;
        $dash->status = 1;
        $dash->index = 1;
        $dash->group_id = 2;
        $dash->save();

        $setOrder = 1;
        /* menu pengaturan (grup) */
        $set = new Menu;
        $set->name = 'Pengaturan';
        $set->description = 'Grup menu pegaturan';
        $set->route_name = 'admin.setting.index';
        // $set->icon = 'fa fa-cog';
        $set->order = $mainOrder++;
        $set->status = 1;
        $set->index = 1;
        $set->group_id = 2;
        $set->save();

        /* menu aplikasi */
        $setApl = new Menu;
        $setApl->name = 'Aplikasi';
        $setApl->description = 'Menu pengaturan aplikasi';
        $setApl->route_name = 'admin.setting.application.index';
        // $setApl->icon = 'fa fa-desktop';
        $setApl->order = $setOrder++;
        $setApl->status = 1;
        $setApl->parent_id = $set->id;
        $setApl->index = 1;
        $setApl->create = 0;
        $setApl->read = 0;
        $setApl->update = 1;
        $setApl->delete = 0;
        $setApl->group_id = 2;
        $setApl->save();

        /* menu menu */
        $setMenu = new Menu;
        $setMenu->name = 'Menu';
        $setMenu->description = 'Menu pengaturan menu grup';
        $setMenu->route_name = 'admin.setting.menu-groups.index';
        // $setMenu->icon = 'fa fa-menu';
        $setMenu->order = $setOrder++;
        $setMenu->status = 1;
        $setMenu->parent_id = $set->id;
        $setMenu->index = 1;
        $setMenu->create = 1;
        $setMenu->read = 0;
        $setMenu->update = 1;
        $setMenu->delete = 1;
        $setMenu->group_id = 2;
        $setMenu->save();

        /* menu peran */
        $setRole = new Menu;
        $setRole->name = 'Peran dan Akses';
        $setRole->description = 'Menu pengaturan peran dan akses';
        $setRole->route_name = 'admin.setting.roles.index';
        // $setRole->icon = 'fa fa-key';
        $setRole->order = $setOrder++;
        $setRole->status = 1;
        $setRole->parent_id = $set->id;
        $setRole->index = 1;
        $setRole->create = 1;
        $setRole->read = 0;
        $setRole->update = 1;
        $setRole->delete = 1;
        $setRole->group_id = 2;
        $setRole->save();

        $dataOrder = 1;
        /* menu data (grup) */
        $data = new Menu;
        $data->name = 'Data';
        $data->description = 'Grup menu data';
        $data->route_name = 'admin.data.index';
        // $data->icon = 'fa fa-cog';
        $data->order = $mainOrder++;
        $data->status = 1;
        $data->index = 1;
        $data->group_id = 2;
        $data->save();

        /* menu pengguna */
        $dataUser = new Menu;
        $dataUser->name = 'Pengguna';
        $dataUser->description = 'Menu pengaturan pengguna';
        $dataUser->route_name = 'admin.data.users.index';
        // $dataUser->icon = 'fa fa-user';
        $dataUser->order = $dataOrder++;
        $dataUser->status = 1;
        $dataUser->parent_id = $data->id;
        $dataUser->index = 1;
        $dataUser->create = 1;
        $dataUser->read = 1;
        $dataUser->update = 1;
        $dataUser->delete = 1;
        $dataUser->group_id = 2;
        $dataUser->save();

        /* menu kategori */
        $dataCat = new Menu;
        $dataCat->name = 'Kategori';
        $dataCat->description = 'Menu pengaturan kategori';
        $dataCat->route_name = 'admin.data.categories.index';
        // $dataCat->icon = 'pg-unordered_list';
        $dataCat->order = $dataOrder++;
        $dataCat->status = 1;
        $dataCat->parent_id = $data->id;
        $dataCat->index = 1;
        $dataCat->create = 1;
        $dataCat->read = 0;
        $dataCat->update = 1;
        $dataCat->delete = 1;
        $dataCat->group_id = 2;
        $dataCat->save();

        /* menu label */
        $dataTag = new Menu;
        $dataTag->name = 'Label';
        $dataTag->description = 'Menu pengaturan label';
        $dataTag->route_name = 'admin.data.tags.index';
        // $dataTag->icon = 'fa fa-list';
        $dataTag->order = $dataOrder++;
        $dataTag->status = 1;
        $dataTag->parent_id = $data->id;
        $dataTag->index = 1;
        $dataTag->create = 1;
        $dataTag->read = 0;
        $dataTag->update = 1;
        $dataTag->delete = 1;
        $dataTag->group_id = 2;
        $dataTag->save();

        $contentOrder = 1;
        /* menu konten (grup) */
        $content = new Menu;
        $content->name = 'Konten';
        $content->description = 'Grup menu konten';
        $content->route_name = 'admin.content.index';
        // $content->icon = 'fa fa-edit';
        $content->order = $mainOrder++;
        $content->status = 1;
        $content->index = 1;
        $content->group_id = 2;
        $content->save();

        /* menu spanduk */
        $contentBanner = new Menu;
        $contentBanner->name = 'Spanduk';
        $contentBanner->description = 'Menu pengaturan spanduk';
        $contentBanner->route_name = 'admin.content.banners.index';
        // $contentBanner->icon = 'pg-tablet';
        $contentBanner->order = $contentOrder++;
        $contentBanner->status = 1;
        $contentBanner->parent_id = $content->id;
        $contentBanner->index = 1;
        $contentBanner->create = 1;
        $contentBanner->read = 0;
        $contentBanner->update = 1;
        $contentBanner->delete = 1;
        $contentBanner->group_id = 2;
        $contentBanner->save();

        /* menu halaman */
        $contentPage = new Menu;
        $contentPage->name = 'Halaman';
        $contentPage->description = 'Menu pengaturan halaman';
        $contentPage->route_name = 'admin.content.pages.index';
        // $contentPage->icon = 'fa fa-file-text';
        $contentPage->order = $contentOrder++;
        $contentPage->status = 1;
        $contentPage->parent_id = $content->id;
        $contentPage->index = 1;
        $contentPage->create = 1;
        $contentPage->read = 1;
        $contentPage->update = 1;
        $contentPage->delete = 1;
        $contentPage->group_id = 2;
        $contentPage->save();

        /* menu artikel */
        $contentPost = new Menu;
        $contentPost->name = 'Artikel';
        $contentPost->description = 'Menu pengaturan artikel';
        $contentPost->route_name = 'admin.content.posts.index';
        // $contentPost->icon = 'fa fa-newspaper-o';
        $contentPost->order = $contentOrder++;
        $contentPost->status = 1;
        $contentPost->parent_id = $content->id;
        $contentPost->index = 1;
        $contentPost->create = 1;
        $contentPost->read = 1;
        $contentPost->update = 1;
        $contentPost->delete = 1;
        $contentPost->group_id = 2;
        $contentPost->save();

        $galOrder = 1;
        /* menu galeri (grup) */
        $gallery = new Menu;
        $gallery->name = 'Galeri';
        $gallery->description = 'Grup menu galeri';
        $gallery->route_name = 'admin.content.gallery.index';
        // $gallery->icon = 'fa fa-camera';
        $gallery->order = $contentOrder++;
        $gallery->status = 1;
        $gallery->parent_id = $content->id;
        $gallery->index = 1;
        $gallery->group_id = 2;
        $gallery->save();

        /* menu foto */
        $galPhoto = new Menu;
        $galPhoto->name = 'Foto';
        $galPhoto->description = 'Menu pengaturan foto';
        $galPhoto->route_name = 'admin.content.gallery.photos.index';
        // $galPhoto->icon = 'fa fa-photo';
        $galPhoto->order = $galOrder++;
        $galPhoto->status = 1;
        $galPhoto->parent_id = $gallery->id;
        $galPhoto->index = 1;
        $galPhoto->create = 1;
        $galPhoto->read = 0;
        $galPhoto->update = 1;
        $galPhoto->delete = 1;
        $galPhoto->group_id = 2;
        $galPhoto->save();

        /* menu video */
        $galVideo = new Menu;
        $galVideo->name = 'Video';
        $galVideo->description = 'Menu pengaturan video';
        $galVideo->route_name = 'admin.content.gallery.videos.index';
        // $galVideo->icon = 'pg-video';
        $galVideo->order = $galOrder++;
        $galVideo->status = 1;
        $galVideo->parent_id = $gallery->id;
        $galVideo->index = 1;
        $galVideo->create = 1;
        $galVideo->read = 0;
        $galVideo->update = 1;
        $galVideo->delete = 1;
        $galVideo->group_id = 2;
        $galVideo->save();

        $repOrder = 1;
        /* menu laporan (grup) */
        $report = new Menu;
        $report->name = 'Laporan';
        $report->description = 'Grup menu laporan';
        $report->route_name = 'admin.report.index';
        // $report->icon = 'pg-charts';
        $report->order = $mainOrder++;
        $report->status = 1;
        $report->index = 1;
        $report->group_id = 2;
        $report->save();

        /* menu kontak masuk */
        $repInbox = new Menu;
        $repInbox->name = 'Kotak Masuk';
        $repInbox->description = 'Menu pengaturan kotak masuk';
        $repInbox->route_name = 'admin.report.inbox.index';
        // $repInbox->icon = 'pg-mail';
        $repInbox->order = $repOrder++;
        $repInbox->status = 1;
        $repInbox->parent_id = $report->id;
        $repInbox->index = 1;
        $repInbox->create = 0;
        $repInbox->read = 1;
        $repInbox->update = 0;
        $repInbox->delete = 1;
        $repInbox->group_id = 2;
        $repInbox->save();

        /* menu komentar */
        $repComment = new Menu;
        $repComment->name = 'Komentar';
        $repComment->description = 'Menu pengaturan komentar';
        $repComment->route_name = 'admin.report.comments.index';
        // $repComment->icon = 'fa fa-comments';
        $repComment->order = $repOrder++;
        $repComment->status = 1;
        $repComment->parent_id = $report->id;
        $repComment->index = 1;
        $repComment->create = 1;
        $repComment->read = 1;
        $repComment->update = 1;
        $repComment->delete = 1;
        $repComment->group_id = 2;
        $repComment->save();

        /* menu kata pencarian */
        $repInterm = new Menu;
        $repInterm->name = 'Kata Pencarian';
        $repInterm->description = 'Menu pengaturan kata pencarian';
        $repInterm->route_name = 'admin.report.incoming-terms.index';
        // $repInterm->icon = 'pg-search';
        $repInterm->order = $repOrder++;
        $repInterm->status = 1;
        $repInterm->parent_id = $report->id;
        $repInterm->index = 1;
        $repInterm->create = 0;
        $repInterm->read = 0;
        $repInterm->update = 0;
        $repInterm->delete = 0;
        $repInterm->group_id = 2;
        $repInterm->save();

        /* menu pelanggan info */
        $repSubscriber = new Menu;
        $repSubscriber->name = 'Pelanggan Info';
        $repSubscriber->description = 'Menu laporan pelanggan info';
        $repSubscriber->route_name = 'admin.report.subscribers.index';
        // $repSubscriber->icon = 'fa fa-users';
        $repSubscriber->order = $repOrder++;
        $repSubscriber->status = 1;
        $repSubscriber->parent_id = $report->id;
        $repSubscriber->index = 1;
        $repSubscriber->create = 0;
        $repSubscriber->read = 0;
        $repSubscriber->update = 1;
        $repSubscriber->delete = 1;
        $repSubscriber->group_id = 2;
        $repSubscriber->save();

        $logOrder = 1;
        /* menu catatan (grup) */
        /* $log = new Menu;
        $log->name = 'Catatan';
        $log->description = 'Grup menu catatan';
        $log->route_name = 'admin.log.index';
        // $log->icon = 'pg-note';
        $log->order = $mainOrder++;
        $log->status = 1;
        $log->index = 1;
        $log->group_id = 2;
        $log->save(); */

        /* menu catatan aktifitas */
        /* $logActivity = new Menu;
        $logActivity->name = 'Aktifitas';
        $logActivity->description = 'Menu catatan aktifitas';
        $logActivity->route_name = 'admin.log.activities.index';
        // $logActivity->icon = 'fa fa-list';
        $logActivity->order = $logOrder++;
        $logActivity->status = 1;
        $logActivity->parent_id = $log->id;
        $logActivity->index = 1;
        $logActivity->create = 0;
        $logActivity->read = 1;
        $logActivity->update = 0;
        $logActivity->delete = 0;
        $logActivity->group_id = 2;
        $logActivity->save(); */

        /* menu catatan galat */
        /* $logError = new Menu;
        $logError->name = 'Galat';
        $logError->description = 'Menu catatan galat';
        $logError->route_name = 'admin.log.errors.index';
        // $logError->icon = 'fa fa-list-ul';
        $logError->order = $logOrder++;
        $logError->status = 1;
        $logError->parent_id = $log->id;
        $logError->index = 1;
        $logError->create = 0;
        $logError->read = 1;
        $logError->update = 0;
        $logError->delete = 0;
        $logError->group_id = 2;
        $logError->save(); */

        /* menu catatan kunjungan */
        /* $logVisitor = new Menu;
        $logVisitor->name = 'Kunjungan';
        $logVisitor->description = 'Menu catatan kunjungan';
        $logVisitor->route_name = 'admin.log.visitors.index';
        // $logVisitor->icon = 'fa fa-list-ul';
        $logVisitor->order = $logOrder++;
        $logVisitor->status = 1;
        $logVisitor->parent_id = $log->id;
        $logVisitor->index = 1;
        $logVisitor->create = 0;
        $logVisitor->read = 1;
        $logVisitor->update = 0;
        $logVisitor->delete = 0;
        $logVisitor->group_id = 2;
        $logVisitor->save(); */
    }

    public function web()
    {
        /* menu dashboard */
        $dash = new Menu;
        $dash->name = 'Beranda Website';
        $dash->description = 'Halaman utama website';
        $dash->route_name = 'root';
        // $dash->icon = 'fa fa-dashboard';
        $dash->target = '_self';
        // $dash->order = $mainOrder++;
        $dash->status = 1;
        $dash->is_crud = 0;
        $dash->index = 1;
        $dash->group_id = 3;
        $dash->save();
    }
}
