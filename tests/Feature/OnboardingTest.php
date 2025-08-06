<?php

namespace Tests\Feature;

use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Tests\TestCase;

class OnboardingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_merchants_must_consent_to_gdpr(): void
    {
        $response = $this->post('/onboarding', [
            'team_name' => 'Acme',
            'store_name' => 'Acme Store',
        ]);

        $response->assertSessionHasErrors('gdpr_consent');
    }

    public function test_onboarding_creates_team_and_sets_tenant_context(): void
    {
        $response = $this->post('/onboarding', [
            'team_name' => 'Acme',
            'store_name' => 'Acme Store',
            'gdpr_consent' => '1',
        ]);

        $response->assertRedirect('/');

        $team = Team::first();
        $this->assertNotNull($team);
        $this->assertEquals('Acme', $team->name);
        $this->assertNotNull($team->stores()->first()->gdpr_consented_at);

        $this->get('/');
        $this->assertTrue(app()->bound('tenant'));
        $this->assertEquals($team->id, app('tenant')->id);
    }
}
