<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\HeroSlide;
use App\Models\Promo;
use App\Models\AboutSection;
use App\Models\TeamMember;
use App\Models\Facility;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        // Menu
        $menus = Menu::active()->orderBy('sort_order')->orderBy('id')->get()
            ->map(fn($m) => [
                'id'            => $m->id,
                'name'          => $m->name,
                'description'   => $m->description ?? '',
                'category'      => $m->category,
                'price'         => $m->price,
                'originalPrice' => $m->original_price,
                'image'         => $m->image_src,
                'rating'        => $m->rating,
                'reviews'       => $m->review_count,
                'label'         => $m->label,
                'isNew'         => $m->is_new,
                'isPromo'       => $m->is_promo,
                'isStock'       => $m->is_stock,
            ]);

        // Hero slides
        $heroSlides = HeroSlide::active()->orderBy('sort_order')->get();
        $heroSlide  = $heroSlides->first(); // slide utama

        // Promo
        $promos = Promo::active()->orderBy('sort_order')->get();

        // About
        $about = AboutSection::active()->first();

        // Team
        $team = TeamMember::active()->orderBy('sort_order')->get();

        // Facilities
        $facilities = Facility::active()->orderBy('sort_order')->get();

        return view('pages.home', compact(
            'menus', 'heroSlide', 'heroSlides', 'promos', 'about', 'team', 'facilities'
        ));
    }

    public function menu()
    {
        $menus = Menu::active()->orderBy('sort_order')->orderBy('id')->get()
            ->map(fn($m) => [
                'id'            => $m->id,
                'name'          => $m->name,
                'description'   => $m->description ?? '',
                'category'      => $m->category,
                'price'         => $m->price,
                'originalPrice' => $m->original_price,
                'image'         => $m->image_src,
                'rating'        => $m->rating,
                'reviews'       => $m->review_count,
                'label'         => $m->label,
                'isNew'         => $m->is_new,
                'isPromo'       => $m->is_promo,
                'isStock'       => $m->is_stock,
            ]);

        return view('pages.menu', compact('menus'));
    }

    public function checkout()
    {
        return view('pages.checkout');
    }
}
