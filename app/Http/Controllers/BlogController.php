<?php

namespace App\Http\Controllers;

use App\Http\BlogRepository;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * @var BlogRepository
     */
    private $blogRepository;

    /**
     * BlogController constructor.
     * @param BlogRepository $blogRepository
     */
    public function __construct(BlogRepository $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }

    public function getDashboard(Request $request)
    {
        $user = $request->user();
        $lastId = $request->get('last_id');
        $hasMore = false;

        $blog = $this->blogRepository->getMainBlogPosts($lastId)->map(function (Blog $blog) use ($user) {
            return [
                'id' => $blog->id,
                'author_id' => $blog->user->id,
                'author_name' => $blog->user->name,
                'title' => $blog->title,
                'content' => $blog->content,
                'is_editable' => $this->isEditable($user, $blog->user->id),
                'created_at' => $blog->created_at->toDateTimeString(),
            ];
        });

        if ($blog->count() > BlogRepository::LIMIT) {
            $hasMore = true;
            $lastId = $blog->pop()['id'];
        }

        return response()->json([
            'data' => $blog,
            'pagination_data' => [
                'has_more' => $hasMore,
                'last_id' => $lastId ?? $blog->last()->id,
            ]
        ]);
    }

    /**
     * @param $user
     * @param $userId
     * @return bool
     */
    private function isEditable($user, $userId)
    {
        return !is_null($user) ? $user->id == $userId : false;
    }

    public function getUserDashboard()
    {
        // todo; get all the entries for the specific user
    }
}
