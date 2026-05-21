<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            ['name'=>'Nasi Goreng Spesial',   'category'=>'makanan', 'price'=>35000, 'original_price'=>45000, 'image_url'=>'https://images.unsplash.com/photo-1603133872878-684f208fb84b?w=400&h=300&fit=crop', 'rating'=>4.8, 'review_count'=>234, 'label'=>'best-seller', 'is_promo'=>true,  'is_new'=>false, 'description'=>'Nasi goreng dengan bumbu rahasia, telur mata sapi, dan ayam crispy'],
            ['name'=>'Ayam Bakar Madu',        'category'=>'makanan', 'price'=>45000, 'original_price'=>null,  'image_url'=>'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?w=400&h=300&fit=crop', 'rating'=>4.9, 'review_count'=>189, 'label'=>'best-seller', 'is_promo'=>false, 'is_new'=>false, 'description'=>'Ayam bakar dengan saus madu spesial dan lalapan segar'],
            ['name'=>'Mie Goreng Seafood',     'category'=>'makanan', 'price'=>40000, 'original_price'=>null,  'image_url'=>'https://images.unsplash.com/photo-1569050467447-ce54b3bbc37d?w=400&h=300&fit=crop', 'rating'=>4.7, 'review_count'=>156, 'label'=>'',           'is_promo'=>false, 'is_new'=>true,  'description'=>'Mie goreng dengan udang, cumi, dan sayuran segar'],
            ['name'=>'Sate Ayam Madura',       'category'=>'makanan', 'price'=>30000, 'original_price'=>38000, 'image_url'=>'https://images.unsplash.com/photo-1529563021893-cc83c992d75d?w=400&h=300&fit=crop', 'rating'=>4.6, 'review_count'=>312, 'label'=>'popular',     'is_promo'=>true,  'is_new'=>false, 'description'=>'10 tusuk sate ayam dengan bumbu kacang khas Madura'],
            ['name'=>'Gado-Gado',              'category'=>'makanan', 'price'=>28000, 'original_price'=>null,  'image_url'=>'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=400&h=300&fit=crop', 'rating'=>4.6, 'review_count'=>143, 'label'=>'',           'is_promo'=>false, 'is_new'=>false, 'description'=>'Gado-gado dengan bumbu kacang khas dan kerupuk'],
            ['name'=>'Es Teh Manis',           'category'=>'minuman', 'price'=>8000,  'original_price'=>null,  'image_url'=>'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400&h=300&fit=crop', 'rating'=>4.5, 'review_count'=>445, 'label'=>'popular',     'is_promo'=>false, 'is_new'=>false, 'description'=>'Teh manis segar dengan es batu pilihan'],
            ['name'=>'Jus Alpukat',            'category'=>'minuman', 'price'=>18000, 'original_price'=>null,  'image_url'=>'https://images.unsplash.com/photo-1623065422902-30a2d299bbe4?w=400&h=300&fit=crop', 'rating'=>4.8, 'review_count'=>198, 'label'=>'best-seller', 'is_promo'=>false, 'is_new'=>false, 'description'=>'Jus alpukat segar dengan susu kental manis'],
            ['name'=>'Kopi Susu Kekinian',     'category'=>'minuman', 'price'=>22000, 'original_price'=>28000, 'image_url'=>'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=400&h=300&fit=crop', 'rating'=>4.9, 'review_count'=>567, 'label'=>'best-seller', 'is_promo'=>true,  'is_new'=>false, 'description'=>'Kopi susu dengan espresso shot dan susu segar'],
            ['name'=>'Matcha Latte',           'category'=>'minuman', 'price'=>25000, 'original_price'=>null,  'image_url'=>'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?w=400&h=300&fit=crop', 'rating'=>4.7, 'review_count'=>123, 'label'=>'',           'is_promo'=>false, 'is_new'=>true,  'description'=>'Matcha premium dengan susu oat dan foam lembut'],
            ['name'=>'Cheesecake Strawberry',  'category'=>'dessert', 'price'=>32000, 'original_price'=>null,  'image_url'=>'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=400&h=300&fit=crop', 'rating'=>4.8, 'review_count'=>89,  'label'=>'new',         'is_promo'=>false, 'is_new'=>true,  'description'=>'Cheesecake lembut dengan topping strawberry segar'],
            ['name'=>'Brownies Coklat',        'category'=>'dessert', 'price'=>25000, 'original_price'=>30000, 'image_url'=>'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=400&h=300&fit=crop', 'rating'=>4.6, 'review_count'=>145, 'label'=>'popular',     'is_promo'=>true,  'is_new'=>false, 'description'=>'Brownies coklat premium dengan topping almond'],
            ['name'=>'Es Krim Gelato',         'category'=>'dessert', 'price'=>28000, 'original_price'=>null,  'image_url'=>'https://images.unsplash.com/photo-1497034825429-c343d7c6a68f?w=400&h=300&fit=crop', 'rating'=>4.7, 'review_count'=>201, 'label'=>'best-seller', 'is_promo'=>false, 'is_new'=>false, 'description'=>'Gelato Italia asli dengan berbagai pilihan rasa'],
            ['name'=>'Smoothie Bowl',          'category'=>'dessert', 'price'=>38000, 'original_price'=>null,  'image_url'=>'https://images.unsplash.com/photo-1490323914169-4b57d0054c5a?w=400&h=300&fit=crop', 'rating'=>4.9, 'review_count'=>76,  'label'=>'new',         'is_promo'=>false, 'is_new'=>true,  'description'=>'Smoothie bowl dengan granola, buah segar, dan madu'],
            ['name'=>'French Fries',           'category'=>'snack',   'price'=>20000, 'original_price'=>null,  'image_url'=>'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=400&h=300&fit=crop', 'rating'=>4.5, 'review_count'=>334, 'label'=>'popular',     'is_promo'=>false, 'is_new'=>false, 'description'=>'Kentang goreng crispy dengan saus pilihan'],
            ['name'=>'Onion Ring',             'category'=>'snack',   'price'=>22000, 'original_price'=>27000, 'image_url'=>'https://images.unsplash.com/photo-1639024471283-03518883512d?w=400&h=300&fit=crop', 'rating'=>4.4, 'review_count'=>167, 'label'=>'',           'is_promo'=>true,  'is_new'=>false, 'description'=>'Bawang bombay goreng crispy dengan saus ranch'],
            ['name'=>'Chicken Wings',          'category'=>'snack',   'price'=>35000, 'original_price'=>null,  'image_url'=>'https://images.unsplash.com/photo-1527477396000-e27163b481c2?w=400&h=300&fit=crop', 'rating'=>4.8, 'review_count'=>278, 'label'=>'best-seller', 'is_promo'=>false, 'is_new'=>false, 'description'=>'6 pcs chicken wings dengan saus BBQ pedas'],
            ['name'=>'Paket Hemat A',          'category'=>'paket',   'price'=>55000, 'original_price'=>75000, 'image_url'=>'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400&h=300&fit=crop', 'rating'=>4.8, 'review_count'=>312, 'label'=>'best-seller', 'is_promo'=>true,  'is_new'=>false, 'description'=>'Nasi Goreng + Ayam Goreng + Es Teh Manis'],
            ['name'=>'Paket Keluarga',         'category'=>'paket',   'price'=>150000,'original_price'=>200000,'image_url'=>'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=400&h=300&fit=crop', 'rating'=>4.9, 'review_count'=>189, 'label'=>'best-seller', 'is_promo'=>true,  'is_new'=>false, 'description'=>'4 Nasi + 4 Lauk pilihan + 4 Minuman + Dessert'],
            ['name'=>'Paket Romantis',         'category'=>'paket',   'price'=>120000,'original_price'=>160000,'image_url'=>'https://images.unsplash.com/photo-1559339352-11d035aa65de?w=400&h=300&fit=crop', 'rating'=>4.7, 'review_count'=>98,  'label'=>'popular',     'is_promo'=>true,  'is_new'=>true,  'description'=>'2 Steak + 2 Minuman + Dessert + Candle Light'],
            ['name'=>'Paket Bisnis',           'category'=>'paket',   'price'=>45000, 'original_price'=>60000, 'image_url'=>'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=300&fit=crop', 'rating'=>4.5, 'review_count'=>234, 'label'=>'',           'is_promo'=>true,  'is_new'=>false, 'description'=>'Nasi + Lauk + Sayur + Minuman (cocok untuk makan siang)'],
        ];

        foreach ($menus as $i => $menu) {
            Menu::create(array_merge($menu, [
                'is_stock'   => true,
                'is_active'  => true,
                'sold_count' => $menu['review_count'],
                'sort_order' => $i + 1,
            ]));
        }
    }
}
