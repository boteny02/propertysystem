<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'location',
        'city',
        'property_type',
        'bedrooms',
        'bathrooms',
        'facilities',
        'rental_price',
        'rent_frequency',
        'status',
        'description',
        'featured_image',
        'additional_images',
        'current_tenant_id',
    ];

    protected function casts(): array
    {
        return [
            'facilities' => 'array',
            'additional_images' => 'array',
            'bedrooms' => 'integer',
            'bathrooms' => 'integer',
            'rental_price' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Property $property) {
            if (empty($property->slug)) {
                $property->slug = Str::slug($property->name).'-'.Str::random(5);
            }
        });
    }

    public function landlord(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function currentTenant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'current_tenant_id');
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function maintenanceRequests(): HasMany
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function getFormattedPriceAttribute(): string
    {
        return '₦'.number_format((float) $this->rental_price, 2).'/'.($this->rent_frequency === 'annually' ? 'yr' : 'mo');
    }

    public function getDisplayImageAttribute(): string
    {
        if ($this->featured_image) {
            if (str_starts_with($this->featured_image, 'http')) {
                return $this->featured_image;
            }

            return asset('storage/'.$this->featured_image);
        }

        // High quality curated architecture photos based on property type
        $typeImages = [
            'Apartment' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1000&q=80',
            'Studio' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1000&q=80',
            'Duplex' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1000&q=80',
            'Villa' => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=1000&q=80',
            'Townhouse' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1000&q=80',
            'Penthouse' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1000&q=80',
        ];

        return $typeImages[$this->property_type] ?? 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=1000&q=80';
    }
}
