<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Blog
 * @package App\Models
 * @property int id
 * @property int user_id
 * @property string title
 * @property string content
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property User user
 */
class Blog extends Model
{
    protected $table = 'blogs';

    protected $fillable = ['title', 'content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
