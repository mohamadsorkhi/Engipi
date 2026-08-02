<?php

namespace Tests\Feature;

use App\Models\Skill;
use App\Models\SkillDomain;
use App\Models\SkillSuggestion;
use App\Models\Subdomain;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SkillSuggestionWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_specialist_can_submit_a_skill_suggestion(): void
    {
        [$specialist, $subdomain] = $this->specialistAndSubdomain();

        $this->actingAs($specialist)
            ->withSession(['active_role' => 'specialist'])
            ->postJson(route('skill-suggestions.store'), [
                'skill_name' => '  بازرسی   جوش  ',
                'skill_type' => SkillSuggestion::TYPE_FIELD,
                'subdomain_id' => $subdomain->id,
                'description' => 'مهارت میدانی مورد نیاز پروژه‌های صنعتی',
            ])
            ->assertCreated()
            ->assertJsonPath('message', 'پیشنهاد شما ثبت شد و پس از بررسی به لیست مهارت‌ها اضافه خواهد شد.');

        $this->assertDatabaseHas('skill_suggestions', [
            'user_id' => $specialist->id,
            'skill_name' => 'بازرسی جوش',
            'skill_type' => SkillSuggestion::TYPE_FIELD,
            'normalized_name' => 'بازرسی جوش',
            'subdomain_id' => $subdomain->id,
            'status' => SkillSuggestion::STATUS_PENDING,
        ]);
    }

    public function test_user_cannot_submit_a_suggestion_for_another_person(): void
    {
        [$specialist, $subdomain] = $this->specialistAndSubdomain();
        $otherSpecialist = $this->createSpecialist();

        $this->actingAs($specialist)
            ->withSession(['active_role' => 'specialist'])
            ->postJson(route('skill-suggestions.store'), [
                'user_id' => $otherSpecialist->id,
                'skill_name' => 'کنترل ابعادی',
                'skill_type' => SkillSuggestion::TYPE_FIELD,
                'subdomain_id' => $subdomain->id,
            ])
            ->assertCreated();

        $this->assertDatabaseHas('skill_suggestions', [
            'user_id' => $specialist->id,
            'skill_name' => 'کنترل ابعادی',
            'skill_type' => SkillSuggestion::TYPE_FIELD,
        ]);
        $this->assertDatabaseMissing('skill_suggestions', [
            'user_id' => $otherSpecialist->id,
            'skill_name' => 'کنترل ابعادی',
            'skill_type' => SkillSuggestion::TYPE_FIELD,
        ]);
    }

    public function test_duplicate_existing_or_pending_suggestion_is_rejected_after_name_normalization(): void
    {
        [$specialist, $subdomain] = $this->specialistAndSubdomain();
        SkillSuggestion::query()->create([
            'user_id' => $specialist->id,
            'skill_name' => 'نقشه کشی صنعتی',
            'skill_type' => SkillSuggestion::TYPE_FIELD,
            'normalized_name' => SkillSuggestion::normalizeName('نقشه کشی صنعتی'),
            'pending_name' => SkillSuggestion::normalizeName('نقشه کشی صنعتی'),
            'subdomain_id' => $subdomain->id,
            'status' => SkillSuggestion::STATUS_PENDING,
        ]);

        $this->actingAs($specialist)
            ->withSession(['active_role' => 'specialist'])
            ->postJson(route('skill-suggestions.store'), [
                'skill_name' => '  نقشه   كشي صنعتی ',
                'skill_type' => SkillSuggestion::TYPE_FIELD,
                'subdomain_id' => $subdomain->id,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('skill_name');

        $this->assertDatabaseCount('skill_suggestions', 1);

        Skill::query()->create([
            'name' => 'بازرسی فنی',
            'skill_type' => 'field',
            'subdomain_id' => $subdomain->id,
        ]);

        $this->actingAs($specialist)
            ->withSession(['active_role' => 'specialist'])
            ->postJson(route('skill-suggestions.store'), [
                'skill_name' => ' بازرسی   فنی ',
                'skill_type' => SkillSuggestion::TYPE_FIELD,
                'subdomain_id' => $subdomain->id,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('skill_name');
    }

    public function test_admin_can_approve_a_pending_suggestion(): void
    {
        [$specialist, $subdomain] = $this->specialistAndSubdomain();
        $suggestion = $this->createSuggestion($specialist, $subdomain, 'تست غیر مخرب');
        $admin = User::factory()->create(['is_admin' => true, 'role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.skill-suggestions.approve', $suggestion))
            ->assertRedirect();

        $this->assertDatabaseHas('skill_suggestions', [
            'id' => $suggestion->id,
            'status' => SkillSuggestion::STATUS_APPROVED,
            'reviewed_by' => $admin->id,
        ]);
        $this->assertNotNull($suggestion->fresh()->reviewed_at);
    }

    public function test_non_admin_cannot_approve_or_reject_a_suggestion(): void
    {
        [$specialist, $subdomain] = $this->specialistAndSubdomain();
        $suggestion = $this->createSuggestion($specialist, $subdomain, 'ایمنی کارگاه');
        $ordinaryUser = User::factory()->create(['is_admin' => false]);

        $this->actingAs($ordinaryUser)
            ->post(route('admin.skill-suggestions.approve', $suggestion))
            ->assertForbidden();
        $this->actingAs($ordinaryUser)
            ->post(route('admin.skill-suggestions.reject', $suggestion))
            ->assertForbidden();

        $this->assertDatabaseHas('skill_suggestions', [
            'id' => $suggestion->id,
            'status' => SkillSuggestion::STATUS_PENDING,
            'reviewed_by' => null,
        ]);
    }

    public function test_approval_creates_a_field_skill_and_assigns_it_to_the_specialist(): void
    {
        [$specialist, $subdomain] = $this->specialistAndSubdomain();
        $suggestion = $this->createSuggestion($specialist, $subdomain, 'اندازه گیری ارتعاش');
        $admin = User::factory()->create(['is_admin' => true, 'role' => 'admin']);

        $this->actingAs($admin)->post(route('admin.skill-suggestions.approve', $suggestion));

        $skill = Skill::query()->where('name', 'اندازه گیری ارتعاش')->firstOrFail();
        $this->assertSame('field', $skill->skill_type);
        $this->assertSame($subdomain->id, $skill->subdomain_id);
        $this->assertDatabaseHas('user_skills', [
            'user_id' => $specialist->id,
            'skill_id' => $skill->id,
        ]);
    }

    public function test_approving_the_same_suggestion_twice_does_not_create_a_duplicate_skill(): void
    {
        [$specialist, $subdomain] = $this->specialistAndSubdomain();
        $suggestion = $this->createSuggestion($specialist, $subdomain, 'مونتاژ صنعتی');
        $admin = User::factory()->create(['is_admin' => true, 'role' => 'admin']);

        $this->actingAs($admin)->post(route('admin.skill-suggestions.approve', $suggestion));
        $this->actingAs($admin)->post(route('admin.skill-suggestions.approve', $suggestion));

        $normalizedMatches = Skill::query()->get()->filter(
            fn (Skill $skill): bool => SkillSuggestion::normalizeName($skill->name) === SkillSuggestion::normalizeName('مونتاژ صنعتی')
        );
        $this->assertCount(1, $normalizedMatches);
        $this->assertDatabaseCount('user_skills', 1);
    }

    public function test_same_name_is_independent_between_processing_and_field_types(): void
    {
        [$specialist, $subdomain] = $this->specialistAndSubdomain();
        Skill::query()->create(['name' => 'کنترل کیفیت', 'skill_type' => 'field', 'subdomain_id' => $subdomain->id]);

        $this->actingAs($specialist)->withSession(['active_role' => 'specialist'])
            ->postJson(route('skill-suggestions.store'), [
                'skill_name' => 'کنترل کیفیت',
                'skill_type' => SkillSuggestion::TYPE_PROCESSING,
                'subdomain_id' => $subdomain->id,
            ])->assertCreated();
    }

    public function test_approval_creates_processing_skill_and_process(): void
    {
        [$specialist, $subdomain] = $this->specialistAndSubdomain();
        $suggestion = $this->createSuggestion($specialist, $subdomain, 'تحلیل اجزای محدود');
        $suggestion->update(['skill_type' => SkillSuggestion::TYPE_PROCESSING]);
        $admin = User::factory()->create(['is_admin' => true, 'role' => 'admin']);

        $this->actingAs($admin)->post(route('admin.skill-suggestions.approve', $suggestion))->assertRedirect();

        $skill = Skill::query()->where('name', 'تحلیل اجزای محدود')->firstOrFail();
        $this->assertSame(SkillSuggestion::TYPE_PROCESSING, $skill->skill_type);
        $this->assertNotNull($skill->process_id);
        $this->assertDatabaseHas('processes', [
            'id' => $skill->process_id,
            'skill_domain_id' => $subdomain->skill_domain_id,
            'name' => 'تحلیل اجزای محدود',
        ]);
        $this->assertDatabaseHas('user_skills', ['user_id' => $specialist->id, 'skill_id' => $skill->id]);
    }
    private function specialistAndSubdomain(): array
    {
        $specialist = $this->createSpecialist();
        $domain = SkillDomain::query()->create(['name' => 'مهندسی مکانیک']);
        $subdomain = Subdomain::query()->create([
            'name' => 'ساخت و تولید',
            'skill_domain_id' => $domain->id,
        ]);

        return [$specialist, $subdomain];
    }

    private function createSpecialist(): User
    {
        $user = User::factory()->create(['is_admin' => false, 'role' => 'worker']);
        UserProfile::query()->create(['user_id' => $user->id, 'type' => 'specialist']);

        return $user;
    }

    private function createSuggestion(User $user, Subdomain $subdomain, string $name): SkillSuggestion
    {
        return SkillSuggestion::query()->create([
            'user_id' => $user->id,
            'skill_name' => $name,
            'skill_type' => SkillSuggestion::TYPE_FIELD,
            'normalized_name' => SkillSuggestion::normalizeName($name),
            'pending_name' => SkillSuggestion::normalizeName($name),
            'subdomain_id' => $subdomain->id,
            'status' => SkillSuggestion::STATUS_PENDING,
        ]);
    }
}