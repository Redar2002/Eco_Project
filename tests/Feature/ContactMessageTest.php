<?php

namespace Tests\Feature;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Tests\TestCase;

class ContactMessageTest extends TestCase
{
    use RefreshDatabase;

    private array $data = [
        'name' => 'Salma Bennani',
        'email' => 'salma@example.ma',
        'phone' => '0612345678',
        'subject' => 'commande',
        'message' => 'Bonjour, où en est ma commande TS-123 ?',
    ];

    private function admin(): User
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => User::ROLE_ADMIN])->save();

        return $admin;
    }

    public function test_le_message_est_enregistre_et_transmis_a_la_boutique(): void
    {
        Mail::fake();
        config(['shop.contact_inbox' => 'boutique@example.ma']);

        $this->post(route('pages.contact.send'), $this->data)->assertSessionHas('success');

        $message = ContactMessage::firstOrFail();
        $this->assertSame('Salma Bennani', $message->name);
        $this->assertNull($message->read_at);

        Mail::assertSent(ContactMessageReceived::class, fn (ContactMessageReceived $mail) => $mail->hasTo('boutique@example.ma')
            && $mail->hasReplyTo('salma@example.ma'));
    }

    public function test_le_message_est_conserve_si_l_e_mail_echoue(): void
    {
        Mail::shouldReceive('to')->andThrow(new RuntimeException('SMTP indisponible'));
        config(['shop.contact_inbox' => 'boutique@example.ma']);

        $this->post(route('pages.contact.send'), $this->data)->assertSessionHas('success');

        $this->assertSame(1, ContactMessage::count());
    }

    public function test_le_champ_piege_bloque_les_robots(): void
    {
        $this->post(route('pages.contact.send'), [...$this->data, 'website' => 'spam.example'])->assertSessionHasErrors('website');

        $this->assertSame(0, ContactMessage::count());
    }

    public function test_l_admin_consulte_les_messages_et_ils_passent_en_lu(): void
    {
        $message = ContactMessage::create($this->data);
        $admin = $this->admin();

        $this->actingAs($admin)->get(route('admin.messages.index'))->assertOk()->assertSee('Salma Bennani');
        $this->actingAs($admin)->get(route('admin.messages.show', $message))->assertOk()->assertSee('TS-123');
        $this->assertNotNull($message->fresh()->read_at);

        $this->actingAs($admin)->patch(route('admin.messages.unread', $message))->assertRedirect();
        $this->assertNull($message->fresh()->read_at);

        $this->actingAs($admin)->delete(route('admin.messages.destroy', $message))->assertRedirect();
        $this->assertModelMissing($message);
    }

    public function test_les_messages_sont_reserves_aux_admins(): void
    {
        $message = ContactMessage::create($this->data);

        $this->get(route('admin.messages.index'))->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create())->get(route('admin.messages.show', $message))->assertForbidden();
    }
}
