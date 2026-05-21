<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\HeroSlide;
use App\Models\Promo;
use App\Models\AboutSection;
use App\Models\TeamMember;
use App\Models\Facility;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class ContentController extends Controller
{
    // ─────────────────────────────────────────────
    // HERO SLIDES
    // ─────────────────────────────────────────────
    public function heroIndex() {
        if (!Schema::hasTable('hero_slides')) {
            $slides = collect();
        } else {
            $slides = HeroSlide::orderBy('sort_order')->get();
        }
        return view('admin.content.hero', compact('slides'));
    }

    public function heroStore(Request $request) {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'subtitle'      => 'nullable|string|max:255',
            'description'   => 'nullable|string',
            'cta_text'      => 'nullable|string|max:100',
            'cta_link'      => 'nullable|string|max:255',
            'media_type'    => 'required|in:image,video',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'image_url'     => 'nullable|url',
            'video_url'     => 'nullable|url',
            'overlay_color' => 'nullable|string',
            'is_active'     => 'boolean',
        ]);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('hero', 'public');
        }
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = HeroSlide::max('sort_order') + 1;
        HeroSlide::create($data);
        ActivityLog::log('update_hero', 'Content', "Menambahkan hero slide baru: \"{$data['title']}\"");
        return back()->with('success', 'Slide berhasil ditambahkan!');
    }

    public function heroUpdate(Request $request, HeroSlide $heroSlide) {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'subtitle'      => 'nullable|string|max:255',
            'description'   => 'nullable|string',
            'cta_text'      => 'nullable|string|max:100',
            'cta_link'      => 'nullable|string|max:255',
            'media_type'    => 'required|in:image,video',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'image_url'     => 'nullable|url',
            'video_url'     => 'nullable|url',
            'overlay_color' => 'nullable|string',
            'is_active'     => 'boolean',
        ]);
        if ($request->hasFile('image')) {
            if ($heroSlide->image) Storage::disk('public')->delete($heroSlide->image);
            $data['image'] = $request->file('image')->store('hero', 'public');
        }
        if ($request->input('remove_image') === '1') {
            if ($heroSlide->image) Storage::disk('public')->delete($heroSlide->image);
            $data['image'] = null;
        }
        $data['is_active'] = $request->boolean('is_active', true);
        $heroSlide->update($data);
        ActivityLog::log('update_hero', 'Content', "Memperbarui hero slide: \"{$heroSlide->title}\"");
        return back()->with('success', 'Slide berhasil diperbarui!');
    }

    public function heroDestroy(HeroSlide $heroSlide) {
        if ($heroSlide->image) Storage::disk('public')->delete($heroSlide->image);
        $title = $heroSlide->title;
        $heroSlide->delete();
        ActivityLog::log('update_hero', 'Content', "Menghapus hero slide: \"{$title}\"");
        return back()->with('success', 'Slide berhasil dihapus!');
    }

    // ─────────────────────────────────────────────
    // PROMO
    // ─────────────────────────────────────────────
    public function promoIndex() {
        if (!Schema::hasTable('promos')) {
            $promos = collect();
        } else {
            $promos = Promo::orderBy('sort_order')->get();
        }
        return view('admin.content.promo', compact('promos'));
    }

    public function promoStore(Request $request) {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'code'           => 'required|string|max:50|unique:promos,code',
            'discount_type'  => 'required|in:percent,fixed',
            'discount_value' => 'required|integer|min:1',
            'min_purchase'   => 'nullable|integer|min:0',
            'icon'           => 'nullable|string|max:10',
            'badge'          => 'nullable|string|max:50',
            'gradient'       => 'nullable|string|max:100',
            'expiry_label'   => 'nullable|string|max:100',
            'valid_until'    => 'nullable|date',
            'is_active'      => 'boolean',
        ]);
        $data['is_active']   = $request->boolean('is_active', true);
        $data['sort_order']  = Promo::max('sort_order') + 1;
        $data['code']        = strtoupper($data['code']);
        Promo::create($data);
        ActivityLog::log('create_promo', 'Content', "Menambahkan promo baru: \"{$data['code']}\" - {$data['title']}");
        return back()->with('success', 'Promo "'.$data['code'].'" berhasil ditambahkan!');
    }

    public function promoUpdate(Request $request, Promo $promo) {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'code'           => 'required|string|max:50|unique:promos,code,'.$promo->id,
            'discount_type'  => 'required|in:percent,fixed',
            'discount_value' => 'required|integer|min:1',
            'min_purchase'   => 'nullable|integer|min:0',
            'icon'           => 'nullable|string|max:10',
            'badge'          => 'nullable|string|max:50',
            'gradient'       => 'nullable|string|max:100',
            'expiry_label'   => 'nullable|string|max:100',
            'valid_until'    => 'nullable|date',
            'is_active'      => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['code']      = strtoupper($data['code']);
        $promo->update($data);
        ActivityLog::log('update_promo', 'Content', "Memperbarui promo: \"{$data['code']}\" - {$data['title']}");
        return back()->with('success', 'Promo berhasil diperbarui!');
    }

    public function promoDestroy(Promo $promo) {
        $code = $promo->code;
        $promo->delete();
        ActivityLog::log('delete_promo', 'Content', "Menghapus promo: \"{$code}\"");
        return back()->with('success', 'Promo berhasil dihapus!');
    }

    // ─────────────────────────────────────────────
    // ABOUT / CERITA KAMI
    // ─────────────────────────────────────────────
    public function aboutIndex() {
        if (!Schema::hasTable('about_sections')) {
            $about = new AboutSection();
        } else {
            $about = AboutSection::first() ?? new AboutSection();
        }
        if (!Schema::hasTable('team_members')) {
            $team = collect();
        } else {
            $team  = TeamMember::orderBy('sort_order')->get();
        }
        return view('admin.content.about', compact('about', 'team'));
    }

    // Team index (separate page for managing team members)
    public function teamIndex()
    {
        $team = TeamMember::orderBy('sort_order')->get();
        return view('admin.content.team', compact('team'));
    }

    public function aboutUpdate(Request $request) {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'subtitle'      => 'nullable|string|max:255',
            'description_1' => 'nullable|string',
            'description_2' => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'image_url'     => 'nullable|url',
            'stats'         => 'nullable|array',
            'stats.*.value' => 'nullable|string',
            'stats.*.label' => 'nullable|string',
        ]);
        $about = AboutSection::first();
        if ($request->hasFile('image')) {
            if ($about && $about->image) Storage::disk('public')->delete($about->image);
            $data['image'] = $request->file('image')->store('about', 'public');
        }
        if ($request->input('remove_image') === '1') {
            if ($about && $about->image) Storage::disk('public')->delete($about->image);
            $data['image'] = null;
        }
        $data['is_active'] = true;
        if ($about) {
            $about->update($data);
        } else {
            AboutSection::create($data);
        }
        ActivityLog::log('update_about', 'Content', "Memperbarui konten halaman Cerita Kami");
        return back()->with('success', 'Cerita kami berhasil diperbarui!');
    }

    // ─────────────────────────────────────────────
    // TEAM MEMBERS
    // ─────────────────────────────────────────────
    public function teamStore(Request $request) {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'role'      => 'required|string|max:255',
            'bio'       => 'nullable|string',
            'emoji'     => 'nullable|string|max:10',
            'gradient'  => 'nullable|string|max:100',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'image_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('team', 'public');
        }
        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = TeamMember::max('sort_order') + 1;
        TeamMember::create($data);
        ActivityLog::log('update_about', 'Content', "Menambahkan anggota tim baru: \"{$data['name']}\" ({$data['role']})");
        return back()->with('success', 'Anggota tim berhasil ditambahkan!');
    }

    public function teamUpdate(Request $request, TeamMember $teamMember) {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'role'      => 'required|string|max:255',
            'bio'       => 'nullable|string',
            'emoji'     => 'nullable|string|max:10',
            'gradient'  => 'nullable|string|max:100',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'image_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);
        if ($request->hasFile('image')) {
            if ($teamMember->image) Storage::disk('public')->delete($teamMember->image);
            $data['image'] = $request->file('image')->store('team', 'public');
        }
        if ($request->input('remove_image') === '1') {
            if ($teamMember->image) Storage::disk('public')->delete($teamMember->image);
            $data['image'] = null;
        }
        $data['is_active'] = $request->boolean('is_active', true);
        $teamMember->update($data);
        ActivityLog::log('update_about', 'Content', "Memperbarui anggota tim: \"{$teamMember->name}\"");
        return back()->with('success', 'Anggota tim berhasil diperbarui!');
    }

    public function teamDestroy(TeamMember $teamMember) {
        if ($teamMember->image) Storage::disk('public')->delete($teamMember->image);
        $name = $teamMember->name;
        $teamMember->delete();
        ActivityLog::log('update_about', 'Content', "Menghapus anggota tim: \"{$name}\"");
        return back()->with('success', 'Anggota tim berhasil dihapus!');
    }

    // ─────────────────────────────────────────────
    // FACILITIES
    // ─────────────────────────────────────────────
    public function facilityIndex() {
        if (!Schema::hasTable('facilities')) {
            $facilities = collect();
        } else {
            $facilities = Facility::orderBy('sort_order')->get();
        }
        return view('admin.content.facility', compact('facilities'));
    }

    public function facilityStore(Request $request) {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:10',
            'tag'         => 'nullable|string|max:50',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'image_url'   => 'nullable|url',
            'is_active'   => 'boolean',
        ]);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('facilities', 'public');
        }
        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = Facility::max('sort_order') + 1;
        Facility::create($data);
        ActivityLog::log('update_about', 'Content', "Menambahkan fasilitas baru: \"{$data['title']}\"");
        return back()->with('success', 'Fasilitas berhasil ditambahkan!');
    }

    public function facilityUpdate(Request $request, Facility $facility) {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:10',
            'tag'         => 'nullable|string|max:50',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'image_url'   => 'nullable|url',
            'is_active'   => 'boolean',
        ]);
        if ($request->hasFile('image')) {
            if ($facility->image) Storage::disk('public')->delete($facility->image);
            $data['image'] = $request->file('image')->store('facilities', 'public');
        }
        if ($request->input('remove_image') === '1') {
            if ($facility->image) Storage::disk('public')->delete($facility->image);
            $data['image'] = null;
        }
        $data['is_active'] = $request->boolean('is_active', true);
        $facility->update($data);
        ActivityLog::log('update_about', 'Content', "Memperbarui fasilitas: \"{$facility->title}\"");
        return back()->with('success', 'Fasilitas berhasil diperbarui!');
    }

    public function facilityDestroy(Facility $facility) {
        if ($facility->image) Storage::disk('public')->delete($facility->image);
        $title = $facility->title;
        $facility->delete();
        ActivityLog::log('update_about', 'Content', "Menghapus fasilitas: \"{$title}\"");
        return back()->with('success', 'Fasilitas berhasil dihapus!');
    }

    // ─────────────────────────────────────────────
    // GALLERY / MOMEN BERSAMA
    // ─────────────────────────────────────────────
    public function galleryIndex()
    {
        if (!Schema::hasTable('gallery_images')) {
            $images = collect();
        } else {
            $images = GalleryImage::orderBy('sort_order')->get();
        }
        return view('admin.content.gallery', compact('images'));
    }

    public function galleryStore(Request $request)
    {
        $data = $request->validate([
            'title'     => 'nullable|string|max:255',
            'caption'   => 'nullable|string',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'image_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('gallery', 'public');
        }
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = GalleryImage::max('sort_order') + 1;
        GalleryImage::create($data);
        ActivityLog::log('create_gallery', 'Content', "Menambahkan gambar galeri: \"{$data['title']}\"");
        return back()->with('success', 'Gambar galeri berhasil ditambahkan!');
    }

    public function galleryUpdate(Request $request, GalleryImage $galleryImage)
    {
        $data = $request->validate([
            'title'     => 'nullable|string|max:255',
            'caption'   => 'nullable|string',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'image_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);
        if ($request->hasFile('image')) {
            if ($galleryImage->image) Storage::disk('public')->delete($galleryImage->image);
            $data['image'] = $request->file('image')->store('gallery', 'public');
        }
        if ($request->input('remove_image') === '1') {
            if ($galleryImage->image) Storage::disk('public')->delete($galleryImage->image);
            $data['image'] = null;
        }
        $data['is_active'] = $request->boolean('is_active', true);
        $galleryImage->update($data);
        ActivityLog::log('update_gallery', 'Content', "Memperbarui gambar galeri: \"{$galleryImage->title}\"");
        return back()->with('success', 'Gambar galeri berhasil diperbarui!');
    }

    public function galleryDestroy(GalleryImage $galleryImage)
    {
        if ($galleryImage->image) Storage::disk('public')->delete($galleryImage->image);
        $title = $galleryImage->title;
        $galleryImage->delete();
        ActivityLog::log('delete_gallery', 'Content', "Menghapus gambar galeri: \"{$title}\"");
        return back()->with('success', 'Gambar galeri berhasil dihapus!');
    }
}
