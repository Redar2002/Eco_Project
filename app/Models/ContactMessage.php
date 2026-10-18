<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessage extends Model
{
    /** Sujets proposés dans le formulaire de contact. */
    public const SUBJECTS = [
        'commande' => 'Suivi de commande',
        'produit' => 'Question sur un produit',
        'retour' => 'Retour / échange',
        'partenariat' => 'Partenariat',
        'autre' => 'Autre',
    ];

    protected $fillable = ['user_id', 'name', 'email', 'phone', 'subject', 'message'];

    protected function casts(): array
    {
        return ['read_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('read_at');
    }

    public function getSubjectLabelAttribute(): string
    {
        return self::SUBJECTS[$this->subject] ?? ucfirst($this->subject);
    }

    public function markAsRead(): void
    {
        if (! $this->read_at) {
            $this->forceFill(['read_at' => now()])->save();
        }
    }
}
