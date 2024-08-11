<?php

namespace App\Http\Controllers;

use App\DataTables\ArticleDataTable;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\MasterData\Tag;
use App\Models\User;
use App\Notifications\ArticleNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ArticleDataTable $articleDataTable)
    {
        return $articleDataTable->render('pages.article');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.article-form',[
            'action' => route('articles.store'),
            'data' => new Article(),
            'tags' => Tag::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleRequest $request, Article $article)
    {
        DB::beginTransaction();
        try {
            $article->fill($request->validated());
            $article->slug = \Illuminate\Support\Str::slug($request->title);
            $article->save();

            $article->tags()->sync($request->tags);

            $target = User::role('publisher')->get();
            
            Notification::send($target, new ArticleNotification($article, [
                'title' => 'Article',
                'body' => 'Ada artikel baru yang perlu di review, dengan judul: '. $article->title
            ]));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return responseError($th);
        }
        return responseSuccess();
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $article->load('tags');
        return view('pages.article-form',[
            'action' => null,
            'data' => $article,
            'tags' => Tag::all()
        ]);
    }

    public function approve(Article $article)
    {
        $article->load('tags');
        $action = route('articles.storeApprove', $article->id);

        if ($article->published_at) {
            // $action = null;
        }
        return view('pages.article-form',[
            'action' => $action,
            'data' => $article,
            'tags' => Tag::all()
        ]);
    }

    public function storeApprove(ArticleRequest $request, Article $article)
    {
        if ($request->approval == 1) {
            $article->published_at = now();
        }
        $article->status_approve = $request->approval;
        $article->user_approve_id = user('id');
        $article->keterangan_approve = $request->keterangan_approve;
        $article->save();

        user()->unreadNotifications()->where('data->referensi_id', $article->id)->update(['read_at' => now()]);

        return responseSuccess(true);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        $article->load('tags');
        return view('pages.article-form',[
            'action' => route('articles.update', $article->id),
            'data' => $article,
            'tags' => Tag::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleRequest $request, Article $article)
    {
        DB::beginTransaction();
        try {
            $article->fill($request->validated());
            $article->save();
            $article->tags()->sync($request->tags);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return responseError($th);
        }
        return responseSuccess(true);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        //
    }
}
