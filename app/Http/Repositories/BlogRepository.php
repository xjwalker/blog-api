<?php

namespace App\Http;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class BlogRepository
{
    public const LIMIT = 3;

    /**
     * @param User $user
     * @param $title
     * @param $content
     * @return Blog
     */
    public function create(User $user, $title, $content)
    {
        $blog = new Blog();
        $blog->title = $title;
        $blog->content = $content;
        $blog->user_id = $user->id;
        $blog->save();

        return $blog;
    }

    /**
     * @param $blogId
     * @return Blog
     */
    public function getBlogPost($blogId)
    {
        return Blog::with('user')->find($blogId);
    }

    /**
     * @param null $pageNumber
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getMainBlogPosts($pageNumber = null)
    {
        return Blog::query()
            ->with('user')
            ->orderBy('created_at', 'DESC')
            ->paginate(self::LIMIT);
    }
}
