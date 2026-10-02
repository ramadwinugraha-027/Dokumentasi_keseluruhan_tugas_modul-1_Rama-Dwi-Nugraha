<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ActivityPosterTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->category = Category::create([
            'name' => 'Workshop',
            'description' => 'Kategori Workshop',
        ]);
    }

    public function test_1_poster_valid_dapat_diunggah_dan_ditampilkan(): void
    {
        $file = UploadedFile::fake()->create('poster_workshop.jpg', 500, 'image/jpeg');

        $payload = [
            'code' => 'ACT-PST-01',
            'title' => 'Workshop Golang Microservices',
            'description' => 'Materi microservices.',
            'activity_date' => '2026-11-20',
            'category_id' => $this->category->id,
            'poster' => $file,
        ];

        $response = $this->post(route('activities.store'), $payload);

        $response->assertRedirect();
        $activity = Activity::where('code', 'ACT-PST-01')->first();
        $this->assertNotNull($activity->poster_path);

        Storage::disk('public')->assertExists($activity->poster_path);

        $responseShow = $this->get(route('activities.show', $activity));
        $responseShow->assertStatus(200);
        $responseShow->assertSee(Storage::url($activity->poster_path));
    }

    public function test_2_file_tidak_sesuai_ditolak_dengan_pesan_yang_jelas(): void
    {
        $textDoc = UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf');

        $payload = [
            'code' => 'ACT-PST-02',
            'title' => 'Workshop Invalid File',
            'activity_date' => '2026-11-20',
            'category_id' => $this->category->id,
            'poster' => $textDoc,
        ];

        $response = $this->post(route('activities.store'), $payload);

        $response->assertSessionHasErrors('poster');
        $this->assertDatabaseMissing('activities', ['code' => 'ACT-PST-02']);
    }

    public function test_3_penggantian_poster_tidak_meninggalkan_file_lama(): void
    {
        $fileLama = UploadedFile::fake()->create('poster_lama.jpg', 300, 'image/jpeg');

        $activity = Activity::create([
            'code' => 'ACT-PST-03',
            'title' => 'Workshop Docker',
            'description' => 'Materi container docker.',
            'activity_date' => '2026-11-22',
            'category_id' => $this->category->id,
            'status' => 'draft',
            'poster_path' => $fileLama->store('posters', 'public'),
        ]);

        $oldPath = $activity->poster_path;
        Storage::disk('public')->assertExists($oldPath);

        $fileBaru = UploadedFile::fake()->create('poster_baru.png', 400, 'image/png');

        $response = $this->put(route('activities.update', $activity), [
            'code' => 'ACT-PST-03',
            'title' => 'Workshop Docker Diperbarui',
            'activity_date' => '2026-11-22',
            'category_id' => $this->category->id,
            'poster' => $fileBaru,
        ]);

        $response->assertRedirect();
        $activity->refresh();

        $this->assertNotEquals($oldPath, $activity->poster_path);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($activity->poster_path);
    }

    public function test_4_soft_delete_tidak_merusak_kemungkinan_restore_poster(): void
    {
        $file = UploadedFile::fake()->create('poster_restore.jpg', 300, 'image/jpeg');

        $activity = Activity::create([
            'code' => 'ACT-PST-04',
            'title' => 'Workshop Kubernetes',
            'description' => 'Materi orkestrasi container.',
            'activity_date' => '2026-11-25',
            'category_id' => $this->category->id,
            'status' => 'draft',
            'poster_path' => $file->store('posters', 'public'),
        ]);

        $posterPath = $activity->poster_path;

        $activity->delete();
        $this->assertSoftDeleted('activities', ['id' => $activity->id]);
        Storage::disk('public')->assertExists($posterPath);

        $activity->restore();
        $this->assertNotSoftDeleted('activities', ['id' => $activity->id]);
        Storage::disk('public')->assertExists($posterPath);

        $responseShow = $this->get(route('activities.show', $activity));
        $responseShow->assertStatus(200);
        $responseShow->assertSee(Storage::url($posterPath));
    }

    public function test_5_force_delete_membersihkan_file_poster_dari_storage(): void
    {
        $file = UploadedFile::fake()->create('poster_force.jpg', 300, 'image/jpeg');

        $activity = Activity::create([
            'code' => 'ACT-PST-05',
            'title' => 'Workshop Hapus Permanen',
            'description' => 'Materi testing.',
            'activity_date' => '2026-11-28',
            'category_id' => $this->category->id,
            'status' => 'draft',
            'poster_path' => $file->store('posters', 'public'),
        ]);

        $posterPath = $activity->poster_path;
        Storage::disk('public')->assertExists($posterPath);

        $activity->delete();

        $this->delete(route('activities.force-delete', $activity->id));

        Storage::disk('public')->assertMissing($posterPath);
        $this->assertDatabaseMissing('activities', ['id' => $activity->id]);
    }
}
