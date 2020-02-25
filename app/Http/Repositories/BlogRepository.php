<?php

namespace App\Http;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Collection;

class BlogRepository
{
    public const LIMIT = 3;

    /**
     * @param null $lastId
     * @return Collection
     */
    public function getMainBlogPosts($lastId = null)
    {
        $query = Blog::query()
            ->with('user')
            ->orderBy('created_at', 'DESC')
            ->limit(self::LIMIT + 1);

        if (!is_null($lastId)) {
            $query->where('id', '>', $lastId);
        }

        return $query->get();
    }
}
