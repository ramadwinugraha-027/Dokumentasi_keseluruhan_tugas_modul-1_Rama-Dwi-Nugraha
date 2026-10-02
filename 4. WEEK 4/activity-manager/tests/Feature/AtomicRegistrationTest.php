<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Category;
use App\Models\Registration;
use App\Services\RegistrationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

class AtomicRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;

    protected Activity $publishedActivity;

    protected RegistrationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new RegistrationService;

        $this->category = Category::create([
            'name' => 'Workshop',
            'description' => 'Kategori Workshop',
        ]);

        $this->publishedActivity = Activity::create([
            'code' => 'ACT-IC-01',
            'title' => 'Workshop Cloud Native',
            'description' => 'Materi container dan kubernetes.',
            'activity_date' => now()->addDays(5)->format('Y-m-d'),
            'category_id' => $this->category->id,
            'status' => 'published',
            'capacity' => 5,
            'registered_count' => 0,
        ]);
    }

    public function test_bukti_1_satu_pendaftaran_valid_berhasil(): void
    {
        $payload = [
            'participant_name' => 'Budi Santoso',
            'email' => 'budi.santoso@polban.ac.id',
            'participant_phone' => '081234567890',
        ];

        $response = $this->post(route('activities.registrations.store', $this->publishedActivity), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('registrations', [
            'activity_id' => $this->publishedActivity->id,
            'email' => 'budi.santoso@polban.ac.id',
            'participant_name' => 'Budi Santoso',
        ]);

        $this->publishedActivity->refresh();
        $this->assertEquals(1, $this->publishedActivity->registered_count);
    }

    public function test_bukti_2_email_duplikat_pada_kegiatan_yang_sama_ditolak(): void
    {
        $payload = [
            'participant_name' => 'Budi Santoso',
            'email' => 'budi.santoso@polban.ac.id',
        ];

        $this->post(route('activities.registrations.store', $this->publishedActivity), $payload);

        $responseDuplikat = $this->post(route('activities.registrations.store', $this->publishedActivity), $payload);

        $responseDuplikat->assertRedirect();
        $responseDuplikat->assertSessionHas('error', 'Email ini sudah terdaftar pada kegiatan yang sama.');

        $this->publishedActivity->refresh();
        $this->assertEquals(1, $this->publishedActivity->registered_count);
        $this->assertEquals(1, Registration::where('activity_id', $this->publishedActivity->id)->count());
    }

    public function test_bukti_3_kegiatan_draft_menolak_pendaftaran(): void
    {
        $draftActivity = Activity::create([
            'code' => 'ACT-IC-02',
            'title' => 'Workshop Masih Draft',
            'description' => 'Materi belum final.',
            'activity_date' => now()->addDays(3)->format('Y-m-d'),
            'category_id' => $this->category->id,
            'status' => 'draft',
            'capacity' => 10,
            'registered_count' => 0,
        ]);

        $payload = [
            'participant_name' => 'Siti Nurhaliza',
            'email' => 'siti@polban.ac.id',
        ];

        $response = $this->post(route('activities.registrations.store', $draftActivity), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseMissing('registrations', [
            'activity_id' => $draftActivity->id,
            'email' => 'siti@polban.ac.id',
        ]);
    }

    public function test_bukti_4_kegiatan_tanggal_lewat_menolak_pendaftaran(): void
    {
        $pastActivity = Activity::create([
            'code' => 'ACT-IC-03',
            'title' => 'Workshop Masa Lalu',
            'description' => 'Materi kemarin.',
            'activity_date' => now()->subDays(2)->format('Y-m-d'),
            'category_id' => $this->category->id,
            'status' => 'published',
            'capacity' => 10,
            'registered_count' => 0,
        ]);

        $payload = [
            'participant_name' => 'Ahmad Dahlan',
            'email' => 'ahmad@polban.ac.id',
        ];

        $response = $this->post(route('activities.registrations.store', $pastActivity), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseMissing('registrations', [
            'activity_id' => $pastActivity->id,
            'email' => 'ahmad@polban.ac.id',
        ]);
    }

    public function test_bukti_5_kapasitas_penuh_menolak_pendaftaran(): void
    {
        $fullActivity = Activity::create([
            'code' => 'ACT-IC-04',
            'title' => 'Workshop Kuota Terbatas',
            'description' => 'Materi eksklusif.',
            'activity_date' => now()->addDays(5)->format('Y-m-d'),
            'category_id' => $this->category->id,
            'status' => 'published',
            'capacity' => 2,
            'registered_count' => 2,
        ]);

        $payload = [
            'participant_name' => 'Peserta Ketiga',
            'email' => 'peserta3@polban.ac.id',
        ];

        $response = $this->post(route('activities.registrations.store', $fullActivity), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Pendaftaran ditolak karena kuota pendaftaran sudah penuh.');

        $this->assertDatabaseMissing('registrations', [
            'activity_id' => $fullActivity->id,
            'email' => 'peserta3@polban.ac.id',
        ]);
    }

    public function test_bukti_6_eksperimen_kegagalan_terkontrol_membuktikan_rollback_atomis(): void
    {
        $this->expectException(RuntimeException::class);

        try {
            $this->service->register(
                $this->publishedActivity,
                [
                    'participant_name' => 'Peserta Gagal',
                    'email' => 'gagal@polban.ac.id',
                ],
                simulateFail: true
            );
        } finally {
            $this->assertDatabaseMissing('registrations', [
                'activity_id' => $this->publishedActivity->id,
                'email' => 'gagal@polban.ac.id',
            ]);

            $this->publishedActivity->refresh();
            $this->assertEquals(0, $this->publishedActivity->registered_count);
        }
    }
}
