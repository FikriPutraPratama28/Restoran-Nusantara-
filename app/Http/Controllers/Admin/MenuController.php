<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::query();

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $menus = $query->orderBy('sort_order')->orderBy('id')->get();

        return view('admin.menu', compact('menus'));
    }

    public function store(Request $request)
    {
        $this->sanitizePriceInputs($request);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'category'       => 'required|in:makanan,minuman,dessert,snack,paket,seafood,aneka-snack,aneka-sayur,nasi-kotak,acara-khusus,iga',
            'price'          => 'required|integer|min:0',
            'original_price' => 'nullable|integer|min:0',
            'label'          => 'nullable|in:best-seller,popular,new,',
            'is_stock'       => 'boolean',
            'is_promo'       => 'boolean',
            'is_new'         => 'boolean',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_url'      => 'nullable|url',
        ]);

        // Upload gambar jika ada
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('menus', 'public');
        }

        $validated['is_stock'] = $request->boolean('is_stock', true);
        $validated['is_promo'] = $request->boolean('is_promo', false);
        $validated['is_new']   = $request->boolean('is_new', false);
        $validated['is_active'] = true;
        $validated['sort_order'] = Menu::max('sort_order') + 1;

        Menu::create($validated);

        ActivityLog::log('create_menu', 'Menu', "Menambahkan menu baru: \"{$validated['name']}\" (kategori: {$validated['category']}, harga: Rp " . number_format($validated['price'], 0, ',', '.') . ')');

        return redirect()->route('admin.menu')
            ->with('success', 'Menu "' . $validated['name'] . '" berhasil ditambahkan!');
    }

    public function update(Request $request, Menu $menu)
    {
        $this->sanitizePriceInputs($request);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'category'       => 'required|in:makanan,minuman,dessert,snack,paket,seafood,aneka-snack,aneka-sayur,nasi-kotak,acara-khusus,iga',
            'price'          => 'required|integer|min:0',
            'original_price' => 'nullable|integer|min:0',
            'label'          => 'nullable|in:best-seller,popular,new,',
            'is_stock'       => 'boolean',
            'is_promo'       => 'boolean',
            'is_new'         => 'boolean',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_url'      => 'nullable|url',
        ]);

        // Upload gambar baru jika ada
        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            $validated['image'] = $request->file('image')->store('menus', 'public');
        }

        // Hapus gambar jika diminta
        if ($request->input('remove_image') === '1') {
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            $validated['image'] = null;
        }

        $validated['is_stock'] = $request->boolean('is_stock', true);
        $validated['is_promo'] = $request->boolean('is_promo', false);
        $validated['is_new']   = $request->boolean('is_new', false);

        $menu->update($validated);

        ActivityLog::log('update_menu', 'Menu', "Memperbarui menu: \"{$menu->name}\" (kategori: {$menu->category}, harga: Rp " . number_format($menu->price, 0, ',', '.') . ')', $menu);

        return redirect()->route('admin.menu')
            ->with('success', 'Menu "' . $menu->name . '" berhasil diperbarui!');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }
        $name = $menu->name;
        $menu->delete();

        ActivityLog::log('delete_menu', 'Menu', "Menghapus menu: \"{$name}\"");

        return redirect()->route('admin.menu')
            ->with('success', 'Menu "' . $name . '" berhasil dihapus!');
    }

    public function toggleStock(Menu $menu)
    {
        $menu->update(['is_stock' => !$menu->is_stock]);
        ActivityLog::log('toggle_stock', 'Menu', "Mengubah stok menu \"{$menu->name}\" menjadi " . ($menu->is_stock ? 'tersedia' : 'habis'), $menu);
        return response()->json(['is_stock' => $menu->is_stock]);
    }

    /**
     * Bersihkan input harga: buang pemisah ribuan sehingga '35.000' menjadi '35000'.
     */
    private function sanitizePriceInputs(Request $request): void
    {
        foreach (['price', 'original_price'] as $field) {
            if ($request->filled($field)) {
                $request->merge([$field => preg_replace('/[^0-9]/', '', $request->input($field))]);
            }
        }
    }
}
