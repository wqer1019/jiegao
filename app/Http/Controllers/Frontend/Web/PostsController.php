<?php

namespace App\Http\Controllers\Frontend\Web;


use App\Events\PostHasBeenRead;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\Alert;
use App\Services\TemplateService;
use Illuminate\Http\Request;
use Auth;


class PostsController extends Controller
{
    /**
     * 正文
     *
     * @param  $cateSlug
     * @return \Illuminate\Contracts\View\View
     */
    public function show($slug, Request $request)
    {
        /**
         * @var $post Post
         */
        $queryBuilder = Post::bySlug($slug);
        if (Auth::check()) {

            $post = $queryBuilder->where(
                function ($query) {
                    $query->publishOrDraft();
                }
            )->withTrashed()->firstOrFail();

            if (!$post->isPublish() || $post->trashed()) {
                // 管理员预览草稿或未发布的文章
                app(Alert::class)->setDanger('当前文章未发布，此页面只有管理员可见!');
            }

        } else {
            $post = $queryBuilder->publishPost()->firstOrFail();
        }

        event(new PostHasBeenRead($post, $request->getClientIp()));

        $view = app(TemplateService::class)
            ->firstView([$post->slug, $post->template, $post->category->content_template], 'content');

        return view($view, ['post' => $post]);
    }
}
