<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Category;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'name' => 'Workshop',
            'description' => 'Kategori Pelatihan Workshop',
        ]);
    }

    /**
     * Skenario 1: Create dengan kategori valid -> Data tersimpan dan relasi benar
     */
    public function test_create_dengan_kategori_valid(): void
    {
        $payload = [
            'code' => 'ACT-101',
            'title' => 'Workshop Git Dasar',
            'description' => 'Latihan kolaborasi repository.',
            'activity_date' => '2026-10-05',
            'category_id' => $this->category->id,
            'status' => 'Planned',
        ];

        $response = $this->post(route('activities.store'), $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('activities', [
            'code' => 'ACT-101',
            'title' => 'Workshop Git Dasar',
            'category_id' => $this->category->id,
            'status' => 'Planned',
        ]);

        $activity = Activity::where('code', 'ACT-101')->first();
        $this->assertEquals($this->category->id, $activity->category->id);
    }

    /**
     * Skenario 2: Create dengan category_id tidak valid -> Request ditolak
     */
    public function test_create_dengan_category_id_tidak_valid(): void
    {
        $payload = [
            'code' => 'ACT-102',
            'title' => 'Workshop Tanpa Kategori Valid',
            'activity_date' => '2026-10-05',
            'category_id' => 99999, // Kategori tidak ada di DB
            'status' => 'Planned',
        ];

        $response = $this->post(route('activities.store'), $payload);

        $response->assertSessionHasErrors('category_id');
        $this->assertDatabaseMissing('activities', ['code' => 'ACT-102']);
    }

    /**
     * Skenario 3: Create kode duplikat -> Request ditolak
     */
    public function test_create_kode_duplikat(): void
    {
        Activity::create([
            'code' => 'ACT-103',
            'title' => 'Kegiatan Pertama',
            'activity_date' => '2026-10-10',
            'category_id' => $this->category->id,
            'status' => 'Planned',
        ]);

        $payload = [
            'code' => 'ACT-103',
            'title' => 'Kegiatan Duplikat',
            'activity_date' => '2026-10-12',
            'category_id' => $this->category->id,
            'status' => 'Planned',
        ];

        $response = $this->post(route('activities.store'), $payload);

        $response->assertSessionHasErrors('code');
    }

    /**
     * Skenario 4: Update tanpa mengganti kode -> Tidak dianggap duplikat terhadap diri sendiri
     */
    public function test_update_tanpa_mengganti_kode(): void
    {
        $activity = Activity::create([
            'code' => 'ACT-104',
            'title' => 'Judul Awal',
            'activity_date' => '2026-10-15',
            'category_id' => $this->category->id,
            'status' => 'Planned',
        ]);

        $payload = [
            'code' => 'ACT-104', // Kode tetap sama
            'title' => 'Judul Diperbarui',
            'activity_date' => '2026-10-15',
            'category_id' => $this->category->id,
            'status' => 'Ongoing',
        ];

        $response = $this->put(route('activities.update', $activity), $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('activities', [
            'id' => $activity->id,
            'code' => 'ACT-104',
            'title' => 'Judul Diperbarui',
            'status' => 'Ongoing',
        ]);
    }

    /**
     * Skenario 5: Hapus kategori yang masih dipakai -> Ditolak dengan pesan yang dapat dipahami pengguna (Poin 6 / BR-08)
     */
    public function test_hapus_kategori_yang_masih_dipakai(): void
    {
        Activity::create([
            'code' => 'ACT-105',
            'title' => 'Kegiatan Terkait Kategori',
            'activity_date' => '2026-10-20',
            'category_id' => $this->category->id,
            'status' => 'Planned',
        ]);

        // Menghapus kategori yang masih dipakai akan memicu pesan aplikasi
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Kategori 'Workshop' tidak dapat dihapus karena masih digunakan oleh kegiatan aktif.");

        $this->category->delete();
    }

    /**
     * Skenario 6: Hapus kategori kosong -> Berhasil
     */
    public function test_hapus_kategori_kosong(): void
    {
        $emptyCategory = Category::create([
            'name' => 'Kategori Belum Dipakai',
            'description' => 'Kategori sementara',
        ]);

        $isDeleted = $emptyCategory->delete();

        $this->assertTrue((bool) $isDeleted);
        $this->assertDatabaseMissing('categories', ['id' => $emptyCategory->id]);
    }

    /**
     * Poin 7: Tampilkan nama kategori pada index dan detail Activity menggunakan relationship
     */
    public function test_tampilkan_nama_kategori_pada_index_dan_detail_menggunakan_relationship(): void
    {
        $activity = Activity::create([
            'code' => 'ACT-106',
            'title' => 'Seminar Web Testing',
            'activity_date' => '2026-10-25',
            'category_id' => $this->category->id,
            'status' => 'Planned',
        ]);

        // Test halaman Index menampilkan nama kategori
        $responseIndex = $this->get(route('activities.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Workshop');

        // Test halaman Detail (Show) menampilkan nama kategori
        $responseShow = $this->get(route('activities.show', $activity));
        $responseShow->assertStatus(200);
        $responseShow->assertSee('Workshop');
        $responseShow->assertSee('ACT-106');
    }
}
