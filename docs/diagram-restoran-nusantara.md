# Diagram Sistem Website Restoran Nusantara

Dokumen ini merangkum alur dari sisi front-end hingga back-end website Restoran Nusantara berdasarkan struktur route, controller, model, dan view yang ada.

## 1. Use Case Diagram

```mermaid
flowchart TD
    User[Pengunjung / Pelanggan] --> UC1[Melihat homepage dan menu]
    User --> UC2[Melihat promo, tentang, galeri, fasilitas]
    User --> UC3[Melakukan reservasi]
    User --> UC4[Menerima struk reservasi]

    Admin[Admin] --> UC5[Login ke panel admin]
    Admin --> UC6[Melihat dashboard dan laporan]
    Admin --> UC7[Mengelola menu]
    Admin --> UC8[Mengelola konten hero/promo/about/team/facility/gallery]
    Admin --> UC9[Mengelola status reservasi]
    Admin --> UC10[Melihat aktivitas log]

    System[(Laravel + Database)] --> UC1
    System --> UC3
    System --> UC5
    System --> UC6
    System --> UC7
    System --> UC8
    System --> UC9
    System --> UC10
```

## 2. Activity Diagram — Alur Reservasi

```mermaid
flowchart TD
    A[Pengunjung membuka halaman /reservasi] --> B[Controller PageController menyiapkan data menu]
    B --> C[Pengunjung mengisi form reservasi]
    C --> D[Validasi request di ReservationController]
    D --> E{Data valid?}
    E -- Tidak --> F[Tampilkan error validasi]
    E -- Ya --> G[Ambil data menu dari database]
    G --> H[Buat record Reservation]
    H --> I[Log aktivitas ke ActivityLog]
    I --> J[Return response JSON + redirect ke receipt]
    J --> K[Pengunjung melihat halaman struk /reservasi/{code}]
```

## 3. Class Diagram (Konseptual)

```mermaid
classDiagram
    class PageController {
        +home()
        +menu()
        +reservation()
        +about()
        +contact()
        +checkout()
    }

    class ReservationController {
        +store(Request)
        +receipt(code)
        +myReservations(Request)
        +cancel(Reservation)
    }

    class DashboardController {
        +loginPage()
        +loginPost(Request)
        +logout(Request)
        +index()
        +orders()
        +customers()
        +reservations()
        +reports()
        +updateReservationStatus(Request, Reservation)
    }

    class Menu {
        +name
        +price
        +category
        +is_active
        +is_stock
        +getImageSrcAttribute()
    }

    class Reservation {
        +reservation_code
        +reservation_date
        +reservation_time
        +number_of_guests
        +status
        +payment_method
        +ordered_items
        +getTotalPriceAttribute()
        +generateCode()
    }

    class HeroSlide
    class Promo
    class AboutSection
    class TeamMember
    class Facility
    class Notification
    class ActivityLog

    PageController ..> Menu : uses
    PageController ..> HeroSlide : uses
    PageController ..> Promo : uses
    PageController ..> AboutSection : uses
    PageController ..> TeamMember : uses
    PageController ..> Facility : uses

    ReservationController ..> Reservation : creates/reads
    ReservationController ..> ActivityLog : logs
    DashboardController ..> Reservation : monitors/updates
    DashboardController ..> Menu : analyzes
    DashboardController ..> ActivityLog : logs

    Reservation "1" --> "1" User : belongs to
    Menu "1" --> "many" Reservation : ordered in
```

## 4. Flowchart Sistem (End-to-End)

```mermaid
flowchart LR
    A[Browser / User] --> B[Route web.php]
    B --> C[PageController / ReservationController / DashboardController]
    C --> D[Model: Menu, Reservation, HeroSlide, Promo, AboutSection, TeamMember, Facility]
    D --> E[(Database MySQL)]
    C --> F[Blade View: resources/views/pages + resources/views/admin]
    F --> G[Response HTML / JSON / Redirect]
    G --> A
```

## 5. Ringkasan arsitektur

- Front-end: Blade view di resources/views/pages dan resources/views/admin.
- Backend: Laravel controller di app/Http/Controllers.
- Data layer: Eloquent model di app/Models.
- Routing: routes/web.php untuk halaman publik dan admin.
- Persistensi: database MySQL melalui migrations.
