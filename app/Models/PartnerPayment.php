<?php

namespace App\Models;

use App\Traits\HasAuditing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerPayment extends Model
{
    use HasFactory, HasAuditing;

    protected $fillable = [
        'amount',
        'method',
        'payment_date',
        'user_id',
        'partner_id',
        'validated_by',
        'validated_at',
        'rejected_by',
        'rejected_at',
        'rejection_reason',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'validated_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    /**
     * Get the user who made the payment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the partner who received the payment.
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

    /**
     * Get the user who validated the payment.
     */
    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    /**
     * Get the user who rejected the payment.
     */
    public function rejector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Check if the payment has been validated.
     */
    public function isValidated(): bool
    {
        return !is_null($this->validated_at) && !is_null($this->validated_by);
    }

    /**
     * Check if the payment has been rejected.
     */
    public function isRejected(): bool
    {
        return !is_null($this->rejected_at) && !is_null($this->rejected_by);
    }

    /**
     * Validate the payment.
     */
    public function validate(int $validatorId): bool
    {
        $this->validated_by = $validatorId;
        $this->validated_at = now();
        return $this->save();
    }

    /**
     * Reject the payment.
     */
    public function reject(int $rejectorId, string $reason = ''): bool
    {
        $this->rejected_by = $rejectorId;
        $this->rejected_at = now();
        $this->rejection_reason = $reason;
        return $this->save();
    }
}

