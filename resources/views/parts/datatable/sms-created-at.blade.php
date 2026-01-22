<div>
    <div>{{ $created_at->format(config('app.date_format')) }}</div>
    <small class="text-muted">{{ $created_at->format('H:i:s') }}</small>
</div>

