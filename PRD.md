# Product Requirements Document (PRD)

# JapranMCEH

**Versi:** 2.0

**Tagline**

> Premium Minecraft Store & Bio Link Platform

---

# 1. Gambaran Produk

**JapranMCEH** adalah website resmi untuk menjual produk Minecraft dengan pengalaman pengguna (User Experience) yang premium, modern, dan cepat.

Website tidak menggunakan sistem checkout ataupun payment gateway. Seluruh pembelian dilakukan melalui WhatsApp sehingga proses transaksi menjadi lebih sederhana dan komunikasi dengan pelanggan lebih personal.

Selain sebagai toko Minecraft, JapranMCEH juga menyediakan halaman **Bio Link** yang berfungsi sebagai pusat seluruh tautan resmi seperti WhatsApp, Discord, YouTube, TikTok, Instagram, dan media sosial lainnya.

Target utama adalah menghadirkan website yang terlihat eksklusif, profesional, dan membangun kepercayaan pelanggan sejak kunjungan pertama.

---

# 2. Tujuan Produk

* Menjadi toko Minecraft yang terpercaya.
* Memberikan pengalaman pengguna yang modern dan premium.
* Mempermudah pembelian produk melalui WhatsApp.
* Menjadi pusat informasi seluruh media sosial JapranMCEH.
* Meningkatkan branding dan kepercayaan pelanggan.

---

# 3. Target Pengguna

### Primary User

* Gamer Minecraft
* Pembeli akun Minecraft
* Pembeli Minecoins
* Pembeli Gift Card
* Pengguna jasa pembuatan Skin Minecraft

### Administrator

Pemilik Website

---

# 4. Produk

* Minecraft Account
* Minecoins
* Gift Card
* Jasa Pembuatan Skin Minecraft

---

# 5. Teknologi

## Frontend

* Vue.js 3
* Vite
* Vue Router
* Pinia
* Axios
* Tailwind CSS
* PrimeVue
* Heroicons
* tsParticles
* GSAP
* Motion One atau Framer Motion Vue (jika dipilih)
* Lenis Smooth Scroll
* AOS (Animate On Scroll)

---

## Backend

* Laravel 12
* Sanctum
* REST API
* Eloquent ORM
* Laravel Storage

---

## Database

MySQL

---

## Deployment

Frontend

* Cloudflare Pages / Vercel

Backend

* VPS Ubuntu
* Nginx
* PHP 8.3
* Supervisor
* Redis (opsional untuk cache dan queue)

---

# 6. Konsep Desain

## Tema

**Premium**
**Minimalis**
**Elegant**
**Fancy**
**Gaming Modern**

Inspirasi visual:

* Apple
* Linear
* Vercel
* Steam
* Minecraft
* Discord

Desain harus bersih, fokus pada konten, dengan animasi yang halus tanpa mengganggu performa.

---

# 7. Palet Warna

## Primary

Royal Blue

```
#2563EB
```

---

## Secondary

Sky Blue

```
#3B82F6
```

---

## Background

```
#050816
```

---

## Surface

```
#0F172A
```

---

## Card

```
#111827
```

---

## Border

```
#1E293B
```

---

## Text Primary

```
#FFFFFF
```

---

## Text Secondary

```
#CBD5E1
```

---

## Accent

```
#38BDF8
```

---

# 8. Typography

Heading

* Poppins
* Sora

Body

* Inter

Button

* Inter SemiBold

---

# 9. UI Style

Semua komponen menggunakan:

* Rounded XL (16–20px)
* Soft Shadow
* Glassmorphism ringan pada Hero Section
* Blur Background
* Gradient Biru
* Hover Glow
* Animated Border
* Smooth Transition 300ms
* Skeleton Loading
* Ripple Effect pada tombol
* Elevasi halus pada card

---

# 10. Animasi

## Hero Background

Menggunakan **tsParticles**.

Efek:

* Floating particles
* Interactive mouse repulse
* Parallax
* Glow biru
* Density adaptif
* Performa dioptimalkan

---

## Scroll

Menggunakan **Lenis Smooth Scroll**.

Efek:

* Smooth scrolling
* Momentum scrolling
* Scroll interpolation

---

## Page Transition

Menggunakan **GSAP**.

Animasi:

* Fade
* Scale
* Blur
* Slide Up
* Stagger Animation

---

## Card Animation

Saat hover:

* Scale 1.03
* Glow Border
* Shadow bertambah
* Sedikit rotasi (maksimal 1°)
* Transisi halus

---

## Navbar

* Transparan di bagian atas
* Berubah menjadi solid saat scroll
* Efek blur (backdrop-filter)
* Logo muncul dengan animasi fade-in

---

## Hero Section

Animasi:

* Judul muncul dengan fade-up
* Subtitle muncul bertahap
* Tombol memiliki efek glow saat hover
* Ilustrasi bergerak perlahan (floating)

---

## Product Card

Hover:

* Zoom gambar
* Gradient overlay
* Tombol muncul dengan slide-up
* Badge premium bercahaya

---

## Statistik

Counter animation:

```
0

↓

100+

↓

500+

↓

1000+
```

Animasi menggunakan easing yang halus.

---

## Testimoni

Auto Slider

Animasi:

* Infinite loop
* Fade
* Scale
* Pause saat hover

---

## Link Bio

Animasi:

* Tombol membesar sedikit saat disentuh
* Ripple Effect
* Glow biru
* Hover Shadow

---

# 11. Struktur Halaman

## Homepage

* Premium Navbar
* Hero Section
* tsParticles Background
* Trusted Badge
* Statistik
* Featured Products
* Produk Terbaru
* Testimoni
* CTA WhatsApp
* Footer

---

## Semua Produk

* Search
* Filter
* Grid Produk
* Pagination

---

## Detail Produk

* Gallery Produk
* Nama
* Harga
* Status
* Deskripsi
* Tombol WhatsApp
* Produk Terkait

---

## Bio Link

* Avatar
* Nama
* Deskripsi
* Daftar Link
* Animasi Hover
* Footer Minimalis

---

## Tentang Kami

* Cerita Brand
* Visi
* Misi
* Keunggulan

---

## Kontak

* WhatsApp
* Discord
* Instagram
* TikTok
* YouTube

---

# 12. Dashboard Admin

## Sidebar

* Dashboard
* Produk
* Kategori
* Testimoni
* Link Bio
* Statistik
* Pengaturan
* Logout

---

## Dashboard

Widget:

* Total Produk
* Total Testimoni
* Jumlah Klik WhatsApp
* Produk Terpopuler
* Statistik Pengunjung (jika analitik diaktifkan)

---

# 13. Struktur Database

### users

* id
* name
* email
* password
* remember_token
* timestamps

### categories

* id
* name
* slug
* icon
* timestamps

### products

* id
* category_id
* name
* slug
* description
* price
* stock
* image
* featured
* status
* timestamps

### testimonials

* id
* name
* avatar
* rating
* message
* timestamps

### bio_links

* id
* title
* icon
* url
* sort_order
* is_active

### settings

* id
* website_name
* logo
* favicon
* hero_title
* hero_subtitle
* whatsapp_number
* discord_url
* youtube_url
* instagram_url
* tiktok_url
* created_at
* updated_at

### statistics

* id
* total_customer
* total_transaction
* total_product

---

# 14. REST API

### Authentication

* POST /login
* POST /logout

### Product

* GET /products
* GET /products/{slug}
* POST /products
* PUT /products/{id}
* DELETE /products/{id}

### Category

* CRUD Categories

### Testimonial

* CRUD Testimonials

### Bio Link

* CRUD Bio Links

### Settings

* GET Settings
* UPDATE Settings

---

# 15. Non Functional Requirements

* Mobile First
* Responsive
* SEO Friendly
* Core Web Vitals yang baik
* Lazy Loading untuk gambar
* Optimasi gambar menggunakan WebP
* Kompresi aset
* Caching
* Keamanan terhadap XSS, CSRF, dan SQL Injection
* Struktur kode modular dan mudah dikembangkan

---

# 16. Target MVP

Versi pertama harus mencakup:

* Homepage premium
* tsParticles Hero
* Daftar Produk
* Detail Produk
* Redirect WhatsApp dengan pesan otomatis
* Link Bio
* Dashboard Admin
* CRUD Produk
* CRUD Kategori
* CRUD Testimoni
* CRUD Link Bio
* Pengaturan Website

---

# 17. Visi Akhir

JapranMCEH ditujukan menjadi platform resmi yang menghadirkan pengalaman berbelanja produk Minecraft dengan tampilan premium, modern, dan ringan. Fokus utama adalah membangun kepercayaan melalui desain yang elegan, navigasi yang intuitif, performa tinggi, serta interaksi visual yang halus. Setiap elemen antarmuka dirancang untuk memberikan kesan eksklusif tanpa mengorbankan kemudahan penggunaan, sehingga website mampu menjadi identitas digital yang kuat bagi brand JapranMCEH.
