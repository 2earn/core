<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SimulationOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'order_deal',
        'bfssTables',
        'simulation_data',
    ];

    protected $casts = [
        'order_deal' => 'array',
        'bfssTables' => 'array',
        'simulation_data' => 'array',
    ];

    /**
     * Get the order that owns the simulation
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Create a new simulation order record
     *
     * @param int $orderId
     * @param array|null $simulation
     * @return static
     */
    public static function createFromSimulation(int $orderId, ?array $simulation): self
    {
        return self::create([
            'order_id' => $orderId,
            'order_deal' => $simulation['order_deal'] ?? null,
            'bfssTables' => $simulation['bfssTables'] ?? null,
            'simulation_data' => $simulation,
        ]);
    }

    /**
     * Get the latest simulation for an order
     *
     * @param int $orderId
     * @return static|null
     */
    public static function getLatestForOrder(int $orderId): ?self
    {
        return self::where('order_id', $orderId)
            ->latest()
            ->first();
    }

    /**
     * Get all simulations for an order
     *
     * @param int $orderId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getForOrder(int $orderId)
    {
        return self::where('order_id', $orderId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
