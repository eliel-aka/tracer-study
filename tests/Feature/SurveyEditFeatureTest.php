<?php

namespace Tests\Feature;

use App\Models\Survey;
use App\Models\SurveyBlock;
use App\Models\TemplateJawaban;
use App\Models\TemplatePertanyaan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SurveyEditFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_survey_update_persists_basic_fields_and_form_builder_changes(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->create();
        $survey = Survey::create([
            'nama' => 'Survey Lama',
            'tanggal_mulai' => now()->subDays(7)->toDateString(),
            'tanggal_selesai' => now()->addDays(7)->toDateString(),
            'type_survei' => 'pengguna_lulusan',
            'deskripsi' => 'Deskripsi lama',
            'created_by' => $user->id,
        ]);

        $block = SurveyBlock::create([
            'survey_id' => $survey->id,
            'kode' => 'BLOCK_01',
            'nama' => 'Block Lama',
            'deskripsi' => 'Deskripsi block lama',
            'urutan' => 1,
            'navigation_type' => 'next',
            'is_terminal' => false,
        ]);

        $question = TemplatePertanyaan::create([
            'id_survey' => $survey->id,
            'block_id' => $block->id,
            'pertanyaan' => 'Pertanyaan lama',
            'deskripsi_pertanyaan' => 'Deskripsi pertanyaan lama',
            'tipe' => 'radio',
            'urutan' => 1,
            'visualisasi' => 'bar',
            'is_required' => false,
        ]);

        TemplateJawaban::create([
            'id_template_pertanyaan' => $question->id,
            'pilihan_jawaban' => 'Pilihan lama 1',
            'urutan' => 1,
            'navigation_target' => 'next',
        ]);

        TemplateJawaban::create([
            'id_template_pertanyaan' => $question->id,
            'pilihan_jawaban' => 'Pilihan lama 2',
            'urutan' => 2,
            'navigation_target' => 'end',
        ]);

        $payload = [
            'nama' => 'Survey Baru',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addDays(14)->toDateString(),
            'type_survei' => 'pengguna_lulusan',
            'deskripsi' => 'Deskripsi baru',
            'sections' => [
                [
                    'section_name' => 'Block Baru',
                    'section_description' => 'Deskripsi block baru',
                    'navigation_type' => 'end',
                    'questions' => [
                        [
                            'question' => 'Pertanyaan baru',
                            'description' => 'Deskripsi pertanyaan baru',
                            'type' => 'radio',
                            'required' => '1',
                            'visualization' => 'pie',
                            'options' => ['Pilihan baru 1', 'Pilihan baru 2'],
                            'option_navigation' => ['next', 'end'],
                        ],
                    ],
                ],
            ],
        ];

        $response = $this->actingAs($user)->from('/admin/survey/' . $survey->id . '/edit')->putJson(
            route('admin.survey.update', $survey->id),
            $payload,
            ['Accept' => 'application/json']
        );

        $response
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $survey->refresh();

        $this->assertSame('Survey Baru', $survey->nama);
        $this->assertSame('Deskripsi baru', $survey->deskripsi);
        $this->assertSame('penggunaLulusan', $survey->type_survei);

        $this->assertDatabaseHas('survey_blocks', [
            'survey_id' => $survey->id,
            'nama' => 'Block Baru',
            'navigation_type' => 'end',
        ]);

        $this->assertDatabaseHas('template_pertanyaan', [
            'id_survey' => $survey->id,
            'pertanyaan' => 'Pertanyaan baru',
            'tipe' => 'radio',
            'visualisasi' => 'pie',
            'is_required' => 1,
        ]);

        $this->assertDatabaseHas('template_jawaban', [
            'pilihan_jawaban' => 'Pilihan baru 1',
            'navigation_target' => 'next',
        ]);

        $this->assertDatabaseHas('template_jawaban', [
            'pilihan_jawaban' => 'Pilihan baru 2',
            'navigation_target' => 'end',
        ]);
    }

    public function test_survey_edit_accepts_canonical_type_value_from_form(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->create();
        $survey = Survey::create([
            'nama' => 'Survey Type Test',
            'tanggal_mulai' => now()->subDay()->toDateString(),
            'tanggal_selesai' => now()->addDays(7)->toDateString(),
            'type_survei' => 'lulusan',
            'deskripsi' => 'desc',
            'created_by' => $user->id,
        ]);

        $payload = [
            'nama' => 'Survey Type Test Updated',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addDays(10)->toDateString(),
            'type_survei' => 'penggunaLulusan',
            'deskripsi' => 'desc update',
            'sections' => [
                [
                    'section_name' => 'Block 1',
                    'section_description' => 'Desc block 1',
                    'navigation_type' => 'end',
                    'questions' => [
                        [
                            'question' => 'Q1',
                            'description' => '',
                            'type' => 'text',
                            'visualization' => '',
                        ],
                    ],
                ],
            ],
        ];

        $response = $this->actingAs($user)->putJson(route('admin.survey.update', $survey->id), $payload, [
            'Accept' => 'application/json',
        ]);

        $response
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $survey->refresh();
        $this->assertSame('penggunaLulusan', $survey->type_survei);
    }
}
