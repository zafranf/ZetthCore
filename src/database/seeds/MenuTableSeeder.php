<?php

namespace ZetthCore\Seeder;

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
        $this->admin();
        $this->web();
    }

    public function admin()
    {
        $mainOrder = 1;
        /* menu dashboard */
        $dash = new Menu;
        $dash->name = 'Dasbor';
        $dash->description = 'Halaman utama situs';
        $dash->route_name = 'admin.dashboard.index';
        // $dash->icon = 'fa fa-dashboard';
        $dash->target = '_self';
        $dash->order = $mainOrder++;
        $dash->status = 'active';
        $dash->is_crud = 'yes';
        $dash->index = 'yes';
        $dash->save();

        $setOrder = 1;
        /* menu pengaturan (grup) */
        $set = new Menu;
        $set->name = 'Pengaturan';
        $set->description = 'Grup menu pegaturan';
        $set->route_name = 'admin.setting.index';
        // $set->icon = 'fa fa-cog';
        $set->order = $mainOrder++;
        $set->status = 'active';
        $set->is_crud = 'yes';
        $set->index = 'yes';
        $set->save();

        /* menu situs */
        $setApl = new Menu;
        $setApl->name = 'Situs';
        $setApl->description = 'Menu pengaturan situs';
        $setApl->route_name = 'admin.setting.site.index';
        // $setApl->icon = 'fa fa-desktop';
        $setApl->order = $setOrder++;
        $setApl->status = 'active';
        $setApl->parent_id = $set->id;
        $setApl->is_crud = 'yes';
        $setApl->index = 'yes';
        $setApl->create = 'no';
        $setApl->read = 'no';
        $setApl->update = 'yes';
        $setApl->delete = 'no';
        $setApl->save();

        /* menu menu */
        $setMenu = new Menu;
        $setMenu->name = 'Menu';
        $setMenu->description = 'Menu pengaturan menu grup';
        $setMenu->route_name = 'admin.setting.menu-groups.index';
        // $setMenu->icon = 'fa fa-menu';
        $setMenu->order = $setOrder++;
        $setMenu->status = 'active';
        $setMenu->parent_id = $set->id;
        $setMenu->is_crud = 'yes';
        $setMenu->index = 'yes';
        $setMenu->create = 'yes';
        $setMenu->read = 'no';
        $setMenu->update = 'yes';
        $setMenu->delete = 'yes';
        $setMenu->save();

        /* menu peran */
        $setRole = new Menu;
        $setRole->name = 'Peran dan Akses';
        $setRole->description = 'Menu pengaturan peran dan akses';
        $setRole->route_name = 'admin.setting.roles.index';
        // $setRole->icon = 'fa fa-key';
        $setRole->order = $setOrder++;
        $setRole->status = 'active';
        $setRole->parent_id = $set->id;
        $setRole->is_crud = 'yes';
        $setRole->index = 'yes';
        $setRole->create = 'yes';
        $setRole->read = 'no';
        $setRole->update = 'yes';
        $setRole->delete = 'yes';
        $setRole->save();

        $dataOrder = 1;
        /* menu data (grup) */
        $data = new Menu;
        $data->name = 'Data';
        $data->description = 'Grup menu data';
        $data->route_name = 'admin.data.index';
        // $data->icon = 'fa fa-cog';
        $data->order = $mainOrder++;
        $data->status = 'active';
        $data->is_crud = 'yes';
        $data->index = 'yes';
        $data->save();

        /* menu pengguna */
        $dataUser = new Menu;
        $dataUser->name = 'Pengguna';
        $dataUser->description = 'Menu pengaturan pengguna';
        $dataUser->route_name = 'admin.data.users.index';
        // $dataUser->icon = 'fa fa-user';
        $dataUser->order = $dataOrder++;
        $dataUser->status = 'active';
        $dataUser->parent_id = $data->id;
        $dataUser->is_crud = 'yes';
        $dataUser->index = 'yes';
        $dataUser->create = 'yes';
        $dataUser->read = 'yes';
        $dataUser->update = 'yes';
        $dataUser->delete = 'yes';
        $dataUser->save();

        /* menu kategori */
        $dataCat = new Menu;
        $dataCat->name = 'Kategori';
        $dataCat->description = 'Menu pengaturan kategori';
        $dataCat->route_name = 'admin.data.categories.index';
        // $dataCat->icon = 'pg-unordered_list';
        $dataCat->order = $dataOrder++;
        $dataCat->status = 'active';
        $dataCat->parent_id = $data->id;
        $dataCat->is_crud = 'yes';
        $dataCat->index = 'yes';
        $dataCat->create = 'yes';
        $dataCat->read = 'no';
        $dataCat->update = 'yes';
        $dataCat->delete = 'yes';
        $dataCat->save();

        /* menu label */
        $dataTag = new Menu;
        $dataTag->name = 'Label';
        $dataTag->description = 'Menu pengaturan label';
        $dataTag->route_name = 'admin.data.tags.index';
        // $dataTag->icon = 'fa fa-list';
        $dataTag->order = $dataOrder++;
        $dataTag->status = 'active';
        $dataTag->parent_id = $data->id;
        $dataTag->is_crud = 'yes';
        $dataTag->index = 'yes';
        $dataTag->create = 'yes';
        $dataTag->read = 'no';
        $dataTag->update = 'yes';
        $dataTag->delete = 'yes';
        $dataTag->save();

        $contentOrder = 1;
        /* menu konten (grup) */
        $content = new Menu;
        $content->name = 'Konten';
        $content->description = 'Grup menu konten';
        $content->route_name = 'admin.content.index';
        // $content->icon = 'fa fa-edit';
        $content->order = $mainOrder++;
        $content->status = 'active';
        $content->is_crud = 'yes';
        $content->index = 'yes';
        $content->save();

        /* menu spanduk */
        $contentBanner = new Menu;
        $contentBanner->name = 'Spanduk';
        $contentBanner->description = 'Menu pengaturan spanduk';
        $contentBanner->route_name = 'admin.content.banners.index';
        // $contentBanner->icon = 'pg-tablet';
        $contentBanner->order = $contentOrder++;
        $contentBanner->status = 'active';
        $contentBanner->parent_id = $content->id;
        $contentBanner->is_crud = 'yes';
        $contentBanner->index = 'yes';
        $contentBanner->create = 'yes';
        $contentBanner->read = 'no';
        $contentBanner->update = 'yes';
        $contentBanner->delete = 'yes';
        $contentBanner->save();

        /* menu artikel */
        $contentPost = new Menu;
        $contentPost->name = 'Artikel';
        $contentPost->description = 'Menu pengaturan artikel';
        $contentPost->route_name = 'admin.content.posts.index';
        // $contentPost->icon = 'fa fa-newspaper-o';
        $contentPost->order = $contentOrder++;
        $contentPost->status = 'active';
        $contentPost->parent_id = $content->id;
        $contentPost->is_crud = 'yes';
        $contentPost->index = 'yes';
        $contentPost->create = 'yes';
        $contentPost->read = 'no';
        $contentPost->update = 'yes';
        $contentPost->delete = 'yes';
        $contentPost->save();

        /* menu halaman */
        $contentPage = new Menu;
        $contentPage->name = 'Halaman';
        $contentPage->description = 'Menu pengaturan halaman';
        $contentPage->route_name = 'admin.content.pages.index';
        // $contentPage->icon = 'fa fa-file-text';
        $contentPage->order = $contentOrder++;
        $contentPage->status = 'active';
        $contentPage->parent_id = $content->id;
        $contentPage->is_crud = 'yes';
        $contentPage->index = 'yes';
        $contentPage->create = 'yes';
        $contentPage->read = 'no';
        $contentPage->update = 'yes';
        $contentPage->delete = 'yes';
        $contentPage->save();

        $galOrder = 1;
        /* menu galeri (grup) */
        $gallery = new Menu;
        $gallery->name = 'Galeri';
        $gallery->description = 'Grup menu galeri';
        $gallery->route_name = 'admin.content.gallery.index';
        // $gallery->icon = 'fa fa-camera';
        $gallery->order = $contentOrder++;
        $gallery->status = 'active';
        $gallery->parent_id = $content->id;
        $gallery->is_crud = 'yes';
        $gallery->index = 'yes';
        $gallery->save();

        /* menu foto */
        $galPhoto = new Menu;
        $galPhoto->name = 'Foto';
        $galPhoto->description = 'Menu pengaturan foto';
        $galPhoto->route_name = 'admin.content.gallery.photos.index';
        // $galPhoto->icon = 'fa fa-photo';
        $galPhoto->order = $galOrder++;
        $galPhoto->status = 'active';
        $galPhoto->parent_id = $gallery->id;
        $galPhoto->is_crud = 'yes';
        $galPhoto->index = 'yes';
        $galPhoto->create = 'yes';
        $galPhoto->read = 'no';
        $galPhoto->update = 'yes';
        $galPhoto->delete = 'yes';
        $galPhoto->save();

        /* menu video */
        $galVideo = new Menu;
        $galVideo->name = 'Video';
        $galVideo->description = 'Menu pengaturan video';
        $galVideo->route_name = 'admin.content.gallery.videos.index';
        // $galVideo->icon = 'pg-video';
        $galVideo->order = $galOrder++;
        $galVideo->status = 'active';
        $galVideo->parent_id = $gallery->id;
        $galVideo->is_crud = 'yes';
        $galVideo->index = 'yes';
        $galVideo->create = 'yes';
        $galVideo->read = 'no';
        $galVideo->update = 'yes';
        $galVideo->delete = 'yes';
        $galVideo->save();

        $repOrder = 1;
        /* menu laporan (grup) */
        $report = new Menu;
        $report->name = 'Laporan';
        $report->description = 'Grup menu laporan';
        $report->route_name = 'admin.report.index';
        // $report->icon = 'pg-charts';
        $report->order = $mainOrder++;
        $report->status = 'active';
        $report->is_crud = 'yes';
        $report->index = 'yes';
        $report->save();

        /* menu kontak masuk */
        $repInbox = new Menu;
        $repInbox->name = 'Kotak Masuk';
        $repInbox->description = 'Menu pengaturan kotak masuk';
        $repInbox->route_name = 'admin.report.inbox.index';
        // $repInbox->icon = 'pg-mail';
        $repInbox->order = $repOrder++;
        $repInbox->status = 'active';
        $repInbox->parent_id = $report->id;
        $repInbox->is_crud = 'yes';
        $repInbox->index = 'yes';
        $repInbox->create = 'no';
        $repInbox->read = 'yes';
        $repInbox->update = 'no';
        $repInbox->delete = 'yes';
        $repInbox->save();

        /* menu komentar */
        $repComment = new Menu;
        $repComment->name = 'Komentar';
        $repComment->description = 'Menu pengaturan komentar';
        $repComment->route_name = 'admin.report.comments.index';
        // $repComment->icon = 'fa fa-comments';
        $repComment->order = $repOrder++;
        $repComment->status = 'active';
        $repComment->parent_id = $report->id;
        $repComment->is_crud = 'yes';
        $repComment->index = 'yes';
        $repComment->create = 'yes';
        $repComment->read = 'yes';
        $repComment->update = 'yes';
        $repComment->delete = 'yes';
        $repComment->save();

        /* menu kata pencarian */
        $repInterm = new Menu;
        $repInterm->name = 'Kata Pencarian';
        $repInterm->description = 'Menu pengaturan kata pencarian';
        $repInterm->route_name = 'admin.report.incoming-terms.index';
        // $repInterm->icon = 'pg-search';
        $repInterm->order = $repOrder++;
        $repInterm->status = 'active';
        $repInterm->parent_id = $report->id;
        $repInterm->is_crud = 'yes';
        $repInterm->index = 'yes';
        $repInterm->create = 'no';
        $repInterm->read = 'no';
        $repInterm->update = 'no';
        $repInterm->delete = 'no';
        $repInterm->save();

        /* menu pelanggan info */
        $repSubscriber = new Menu;
        $repSubscriber->name = 'Pelanggan Info';
        $repSubscriber->description = 'Menu laporan pelanggan info';
        $repSubscriber->route_name = 'admin.report.subscribers.index';
        // $repSubscriber->icon = 'fa fa-users';
        $repSubscriber->order = $repOrder++;
        $repSubscriber->status = 'active';
        $repSubscriber->parent_id = $report->id;
        $repSubscriber->is_crud = 'yes';
        $repSubscriber->index = 'yes';
        $repSubscriber->create = 'no';
        $repSubscriber->read = 'no';
        $repSubscriber->update = 'yes';
        $repSubscriber->delete = 'yes';
        $repSubscriber->save();

        $logOrder = 1;
        /* menu catatan (grup) */
        $log = new Menu;
        $log->name = 'Catatan';
        $log->description = 'Grup menu catatan';
        $log->route_name = 'admin.log.index';
        // $log->icon = 'pg-note';
        $log->order = $mainOrder++;
        $log->status = 'active';
        $log->is_crud = 'yes';
        $log->index = 'yes';
        $log->save();

        /* menu catatan aktifitas */
        $logActivity = new Menu;
        $logActivity->name = 'Aktifitas';
        $logActivity->description = 'Menu catatan aktifitas';
        $logActivity->route_name = 'admin.log.activities.index';
        // $logActivity->icon = 'fa fa-list';
        $logActivity->order = $logOrder++;
        $logActivity->status = 'active';
        $logActivity->parent_id = $log->id;
        $logActivity->is_crud = 'yes';
        $logActivity->index = 'yes';
        $logActivity->create = 'no';
        $logActivity->read = 'yes';
        $logActivity->update = 'no';
        $logActivity->delete = 'no';
        $logActivity->save();

        /* menu catatan galat */
        $logError = new Menu;
        $logError->name = 'Galat';
        $logError->description = 'Menu catatan galat';
        $logError->route_name = 'admin.log.errors.index';
        // $logError->icon = 'fa fa-list-ul';
        $logError->order = $logOrder++;
        $logError->status = 'active';
        $logError->parent_id = $log->id;
        $logError->is_crud = 'yes';
        $logError->index = 'yes';
        $logError->create = 'no';
        $logError->read = 'yes';
        $logError->update = 'no';
        $logError->delete = 'no';
        $logError->save();

        /* menu catatan kunjungan */
        $logVisitor = new Menu;
        $logVisitor->name = 'Kunjungan';
        $logVisitor->description = 'Menu catatan kunjungan';
        $logVisitor->route_name = 'admin.log.visitors.index';
        // $logVisitor->icon = 'fa fa-list-ul';
        $logVisitor->order = $logOrder++;
        $logVisitor->status = 'active';
        $logVisitor->parent_id = $log->id;
        $logVisitor->is_crud = 'yes';
        $logVisitor->index = 'yes';
        $logVisitor->create = 'no';
        $logVisitor->read = 'yes';
        $logVisitor->update = 'no';
        $logVisitor->delete = 'no';
        $logVisitor->save();
    }

    public function web()
    {
        $mainOrder = 1;
        /* menu home */
        $dash = new Menu;
        $dash->name = 'Beranda';
        $dash->description = 'Halaman utama';
        $dash->url = route('web.root');
        $dash->target = '_self';
        $dash->order = $mainOrder++;
        $dash->status = 'active';
        $dash->group_id = 2;
        $dash->save();

        /* menu about */
        $about = new Menu;
        $about->name = 'Tentang';
        $about->description = 'Halaman tentang situs';
        $about->url = '/about';
        $about->target = '_self';
        $about->order = $mainOrder++;
        $about->status = 'active';
        $about->group_id = 2;
        $about->save();

        /* menu contact */
        $contact = new Menu;
        $contact->name = 'Hubungi Kami';
        $contact->description = 'Halaman hubungi kami';
        $contact->url = route('web.contact');
        $contact->target = '_self';
        $contact->order = $mainOrder++;
        $contact->status = 'active';
        $contact->group_id = 2;
        $contact->save();
    }
}
