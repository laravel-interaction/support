<?php

namespace LaravelInteraction\Support\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Support\Str;

class Interaction extends MorphPivot
{
    protected $interaction;

    protected $tableNameKey;

    protected $morphTypeName;

    protected function uuids(): bool
    {
        return (bool) config($this->interaction . '.uuids');
    }

    public function getIncrementing(): bool
    {
        return $this->uuids() ? true : parent::getIncrementing();
    }

    public function getKeyName(): string
    {
        return $this->uuids() ? 'uuid' : parent::getKeyName();
    }

    public function getKeyType(): string
    {
        return $this->uuids() ? 'string' : parent::getKeyType();
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(
            function (self $interaction): void {
                if ($interaction->uuids()) {
                    $interaction->{$interaction->getKeyName()} = Str::orderedUuid();
                }
            }
        );
    }

    public function getTable()
    {
        return config($this->interaction . '.table_names.' . $this->tableNameKey) ?: parent::getTable();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config($this->interaction . '.models.user'), config($this->interaction . '.column_names.user_foreign_key'));
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithType(Builder $query, string $type): Builder
    {
        return $query->where($this->morphTypeName . '_type', app($type)->getMorphClass());
    }
}
