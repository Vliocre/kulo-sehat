<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiAssistantMessage extends Model
{
    protected $fillable = ['conversation_id', 'role', 'content', 'images'];

    protected function casts(): array
    {
        return ['images' => 'array'];
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(AiAssistantConversation::class, 'conversation_id');
    }
}
