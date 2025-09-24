<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_contact()
    {
        $contact = Contact::factory()->create();

        $this->assertDatabaseHas('contact', [
            'id' => $contact->id,
            'email' => $contact->email,
            'subject' => $contact->subject,
        ]);
    }

    /** @test */
    public function it_can_be_soft_deleted()
    {
        $contact = Contact::factory()->create();

        $contact->delete();

        $this->assertSoftDeleted('contact', [
            'id' => $contact->id,
        ]);
    }

    /** @test */
    public function it_has_a_valid_subject()
    {
        $contact = Contact::factory()->create();

        $this->assertContains($contact->subject, Contact::getSubjects());
    }
}
