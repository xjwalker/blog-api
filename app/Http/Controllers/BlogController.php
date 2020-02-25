<?php

namespace App\Http\Controllers;

use App\Http\BlogRepository;
use App\Http\Requests\DashboardRequest;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

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

    public function getDashboard(DashboardRequest $request)
    {
        $user = $request->user();
        $lastId = $request->get('page_number');

        /** @var LengthAwarePaginator $blog */
        $blog = $this->blogRepository->getMainBlogPosts($lastId);

        return response()->json([
            'data' => Collect($blog->items())->map(function (Blog $blog) use ($user) {
                return $this->formatPost($blog, $user);
            }),
            'pagination_data' => [
                'has_more' => $blog->hasMorePages(),
                'page' => $blog->currentPage(),
            ]
        ]);
    }

    public function getUserDashboard()
    {
        // todo; get all the entries for the specific user
        // todo; get twitter posts?
        // todo; paginate blog
        // todo; paginate twitter posts?
    }

    public function createBlog(Request $request)
    {
        $user = $request->user();
        $title = $request->input('title');
        $content = $request->input('content');

        $blog = $this->blogRepository->create($user, $title, $content);

        return response()->json(['data' => ['message' => 'Blog post created', 'blog' => $blog]]);
    }

    public function getBlogContent(Request $request)
    {
        $user = $request->user();
        $blog = $this->blogRepository->getBlogPost($request->get('blog_id'));

        return response()->json(['data' => $this->formatPost($blog, $user)]);
    }

    /**
     * @param Request $request
     * @throws \Exception
     */
    public function deleteBlog(Request $request)
    {
        $blog = $this->blogRepository->delete($request->input('blog_id'));
        return response()->json(['data' => ['message' => 'Blog deleted', 'blog' => $blog]]);
    }

    private function formatPost(Blog $blog, $user = null)
    {
        return [
            'id' => $blog->id,
            'author_id' => $blog->user_id,
            'author_name' => $blog->user->name,
            'title' => $blog->title,
            'content' => $blog->content,
            'is_editable' => !is_null($user) ? $user->id == $blog->user_id : false,
            'created_at' => $blog->created_at->toDateTimeString(),
        ];
    }
}
