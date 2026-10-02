<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Category;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ActivityFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;

    protected Category $otherCategory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'name' => 'Workshop',
            'description' => 'Kategori Pelatihan Workshop',
        ]);

        $this->otherCategory = Category::create([
            'name' => 'Seminar',
            'description' => 'Kategori Seminar',
        ]);
    }

    public function test_create_dengan_kategori_valid_menghasilkan_status_draft(): void
    {
        $payload = [
            'code' => 'ACT-101',
            'title' => 'Workshop Git Dasar',
            'description' => 'Latihan kolaborasi repository.',
            'activity_date' => '2026-10-05',
            'category_id' => $this->category->id,
        ];

        $response = $this->post(route('activities.store'), $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('activities', [
            'code' => 'ACT-101',
            'title' => 'Workshop Git Dasar',
            'category_id' => $this->category->id,
            'status' => 'draft',
        ]);

        $activity = Activity::where('code', 'ACT-101')->first();
        $this->assertEquals($this->category->id, $activity->category->id);
        $this->assertEquals('draft', $activity->status);
    }

    public function test_create_dengan_category_id_tidak_valid(): void
    {
        $payload = [
            'code' => 'ACT-102',
            'title' => 'Workshop Tanpa Kategori Valid',
            'activity_date' => '2026-10-05',
            'category_id' => 99999,
        ];

        $response = $this->post(route('activities.store'), $payload);

        $response->assertSessionHasErrors('category_id');
        $this->assertDatabaseMissing('activities', ['code' => 'ACT-102']);
    }

    public function test_create_kode_duplikat(): void
    {
        Activity::create([
            'code' => 'ACT-103',
            'title' => 'Kegiatan Pertama',
            'activity_date' => '2026-10-10',
            'category_id' => $this->category->id,
            'status' => 'draft',
        ]);

        $payload = [
            'code' => 'ACT-103',
            'title' => 'Kegiatan Duplikat',
            'activity_date' => '2026-10-12',
            'category_id' => $this->category->id,
        ];

        $response = $this->post(route('activities.store'), $payload);

        $response->assertSessionHasErrors('code');
    }

    public function test_update_tanpa_mengganti_kode_dan_status_terlindungi(): void
    {
        $activity = Activity::create([
            'code' => 'ACT-104',
            'title' => 'Judul Awal',
            'description' => 'Deskripsi kegiatan awal',
            'activity_date' => '2026-10-15',
            'category_id' => $this->category->id,
            'status' => 'draft',
        ]);

        $payload = [
            'code' => 'ACT-104',
            'title' => 'Judul Diperbarui',
            'description' => 'Deskripsi diperbarui',
            'activity_date' => '2026-10-15',
            'category_id' => $this->category->id,
            'status' => 'completed',
        ];

        $response = $this->put(route('activities.update', $activity), $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('activities', [
            'id' => $activity->id,
            'code' => 'ACT-104',
            'title' => 'Judul Diperbarui',
            'status' => 'draft',
        ]);
    }

    public function test_skenario_draft_lengkap_ke_published_berhasil(): void
    {
        $activity = Activity::create([
            'code' => 'ACT-201',
            'title' => 'Workshop Lengkap',
            'description' => 'Deskripsi detail materi workshop.',
            'activity_date' => '2026-11-01',
            'category_id' => $this->category->id,
            'status' => 'draft',
        ]);

        $response = $this->patch(route('activities.publish', $activity));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('activities', [
            'id' => $activity->id,
            'status' => 'published',
        ]);
    }

    public function test_skenario_draft_tidak_lengkap_ke_published_ditolak_br05(): void
    {
        $activity = Activity::create([
            'code' => 'ACT-202',
            'title' => 'Draft Tanpa Deskripsi',
            'description' => null,
            'activity_date' => '2026-11-02',
            'category_id' => $this->category->id,
            'status' => 'draft',
        ]);

        $response = $this->patch(route('activities.publish', $activity));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('activities', [
            'id' => $activity->id,
            'status' => 'draft',
        ]);
    }

    public function test_skenario_published_ke_completed_berhasil(): void
    {
        $activity = Activity::create([
            'code' => 'ACT-203',
            'title' => 'Kegiatan Sedang Berlangsung',
            'description' => 'Deskripsi kegiatan.',
            'activity_date' => '2026-11-03',
            'category_id' => $this->category->id,
            'status' => 'published',
        ]);

        $response = $this->patch(route('activities.complete', $activity));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('activities', [
            'id' => $activity->id,
            'status' => 'completed',
        ]);
    }

    public function test_skenario_completed_ke_draft_ditolak(): void
    {
        $activity = Activity::create([
            'code' => 'ACT-204',
            'title' => 'Kegiatan Telah Selesai',
            'description' => 'Deskripsi kegiatan.',
            'activity_date' => '2026-11-04',
            'category_id' => $this->category->id,
            'status' => 'completed',
        ]);

        $responsePublish = $this->patch(route('activities.publish', $activity));
        $responsePublish->assertSessionHas('error');

        $responseUpdate = $this->put(route('activities.update', $activity), [
            'code' => 'ACT-204',
            'title' => 'Coba Ubah Status',
            'activity_date' => '2026-11-04',
            'category_id' => $this->category->id,
            'status' => 'draft',
        ]);
        $responseUpdate->assertRedirect();

        $activity->refresh();
        $this->assertEquals('completed', $activity->status);
    }

    public function test_skenario_kombinasi_search_category_dan_status(): void
    {
        Activity::create([
            'code' => 'ACT-301',
            'title' => 'Workshop Laravel Advanced',
            'description' => 'Belajar clean code.',
            'activity_date' => '2026-11-10',
            'category_id' => $this->category->id,
            'status' => 'published',
        ]);

        Activity::create([
            'code' => 'ACT-302',
            'title' => 'Seminar Laravel Advanced',
            'description' => 'Seminar arsitektur.',
            'activity_date' => '2026-11-11',
            'category_id' => $this->otherCategory->id,
            'status' => 'published',
        ]);

        Activity::create([
            'code' => 'ACT-303',
            'title' => 'Workshop Laravel Basic',
            'description' => 'Belajar dasar framework.',
            'activity_date' => '2026-11-12',
            'category_id' => $this->category->id,
            'status' => 'draft',
        ]);

        $response = $this->get(route('activities.index', [
            'search' => 'Advanced',
            'category_id' => $this->category->id,
            'status' => 'published',
        ]));

        $response->assertStatus(200);
        $response->assertSee('ACT-301');
        $response->assertDontSee('ACT-302');
        $response->assertDontSee('ACT-303');
    }

    public function test_skenario_pagination_halaman_2_mempertahankan_query_string(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            Activity::create([
                'code' => sprintf('PAG-%03d', $i),
                'title' => "Workshop Testing Seri {$i}",
                'description' => "Deskripsi materi seri {$i}",
                'activity_date' => date('2026-11-d', strtotime("+{$i} days", strtotime('2026-11-01'))),
                'category_id' => $this->category->id,
                'status' => 'published',
            ]);
        }

        $response = $this->get(route('activities.index', [
            'search' => 'Workshop',
            'category_id' => $this->category->id,
            'status' => 'published',
            'page' => 2,
        ]));

        $response->assertStatus(200);
        $response->assertSee('PAG-005');
        $response->assertSee('page=1');
        $response->assertSee('search=Workshop');
        $response->assertSee('status=published');
    }

    public function test_ac10_soft_delete_dan_tidak_tampil_di_index_normal(): void
    {
        $activity = Activity::create([
            'code' => 'ACT-401',
            'title' => 'Kegiatan Yang Akan Dihapus',
            'description' => 'Deskripsi kegiatan.',
            'activity_date' => '2026-11-01',
            'category_id' => $this->category->id,
            'status' => 'draft',
        ]);

        $response = $this->delete(route('activities.destroy', $activity));

        $response->assertRedirect(route('activities.index'));
        $this->assertSoftDeleted('activities', ['id' => $activity->id]);

        $responseIndex = $this->get(route('activities.index'));
        $responseIndex->assertDontSee('ACT-401');

        $responseTrash = $this->get(route('activities.trash'));
        $responseTrash->assertStatus(200);
        $responseTrash->assertSee('ACT-401');
    }

    public function test_ac11_restore_data_terhapus(): void
    {
        $activity = Activity::create([
            'code' => 'ACT-402',
            'title' => 'Kegiatan Untuk Direstore',
            'description' => 'Deskripsi kegiatan.',
            'activity_date' => '2026-11-02',
            'category_id' => $this->category->id,
            'status' => 'draft',
        ]);

        $activity->delete();
        $this->assertSoftDeleted('activities', ['id' => $activity->id]);

        $responseRestore = $this->patch(route('activities.restore', $activity->id));

        $responseRestore->assertRedirect(route('activities.trash'));
        $this->assertNotSoftDeleted('activities', ['id' => $activity->id]);

        $responseIndex = $this->get(route('activities.index'));
        $responseIndex->assertSee('ACT-402');
    }

    public function test_ac12_tidak_ada_pola_n_plus_1_pada_index(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Activity::create([
                'code' => sprintf('N1-%03d', $i),
                'title' => "Kegiatan N1 Testing {$i}",
                'description' => "Deskripsi {$i}",
                'activity_date' => '2026-11-05',
                'category_id' => ($i % 2 === 0) ? $this->category->id : $this->otherCategory->id,
                'status' => 'published',
            ]);
        }

        DB::flushQueryLog();
        DB::enableQueryLog();

        $response = $this->get(route('activities.index'));
        $response->assertStatus(200);

        $queries = DB::getQueryLog();

        $categoryQueries = array_filter($queries, function ($q) {
            return str_contains(strtolower($q['query']), 'from "categories"') || str_contains(strtolower($q['query']), 'from `categories`');
        });

        $this->assertLessThanOrEqual(2, count($categoryQueries));
    }

    public function test_hapus_kategori_yang_masih_dipakai(): void
    {
        Activity::create([
            'code' => 'ACT-105',
            'title' => 'Kegiatan Terkait Kategori',
            'activity_date' => '2026-10-20',
            'category_id' => $this->category->id,
            'status' => 'draft',
        ]);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Kategori 'Workshop' tidak dapat dihapus karena masih digunakan oleh kegiatan aktif.");

        $this->category->delete();
    }

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

    public function test_tampilkan_nama_kategori_pada_index_dan_detail_menggunakan_relationship(): void
    {
        $activity = Activity::create([
            'code' => 'ACT-106',
            'title' => 'Seminar Web Testing',
            'description' => 'Deskripsi pengujian.',
            'activity_date' => '2026-10-25',
            'category_id' => $this->category->id,
            'status' => 'draft',
        ]);

        $responseIndex = $this->get(route('activities.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Workshop');

        $responseShow = $this->get(route('activities.show', $activity));
        $responseShow->assertStatus(200);
        $responseShow->assertSee('Workshop');
        $responseShow->assertSee('ACT-106');
    }
}
