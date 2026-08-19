<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
        'ticket_number',
        'user_id',
        'category_id',
        'sub_category_id',
        'location',
        'description',
        'complainant',
        'status',
        'assigned_to',
    ];

    // Jis student/employee ne complaint raise ki hai
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Jis category ki complaint hai
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Sub-category (agar ho toh)
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    // Jis concern employee ko ye complaint assign ki gayi hai
    public function assignedEmployee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}