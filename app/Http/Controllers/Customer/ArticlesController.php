<?php

namespace App\Http\Controllers\Customer;

use App\Articles;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TmpServices\DBConnService;
use App\Http\Requests\NewsProcessRequest;
use App\NewsCategory;
use Illuminate\Http\Response;


class ArticlesController extends Controller
{
    public function index(int $category_id, int $id):string {
//        $new=DBConnService::selectSingleRow('SELECT * FROM v_news WHERE category_id=? AND id=?',[$category_id,$id]);
        $new=Articles::whereCategoryId($category_id)->where('id',$id)->first();
        if ($new==null) {
            abort(404,'Новость с таким идентификатором отсутствует');
        }
        return view('customer.article',['new'=>$new]);

    }

    private function getFromForm(Articles $article, NewsProcessRequest $request) {
        $article->title=$request->get('title');
        $article->announcement=$request->get('announcement');
        $article->article_body=$request->get('article_body');
        $article->is_private=$request->get('is_private',0);
        $article->category_id=$request->get('category_id');
        $article->save();
    }

    public function add(int $category_id) {
        $categories=NewsCategory::orderBy('name')->get();
        return view('customer.article-add',[
            'id'=>$category_id,
            'categoryList'=>$categories
        ]);
    }

    public function insert(NewsProcessRequest $request) {
        $new=new Articles();
        $this->getFromForm($new, $request);
        session()->flash('proceed_status','Новость добавлена');
        return redirect()->back();
//        return 'Hello';
    }

    public function edit(Articles $article) {
        $categories=NewsCategory::orderBy('name')->get();
        return view('customer.article-add',[
            'id'=>$article->category_id,
            'categoryList'=>$categories
        ]);
    }

    public function update(Articles $article, NewsProcessRequest $request) {
        $this->getFromForm($article, $request);
        session()->flash('proceed_status','Новость изменена');
        return redirect()->route('categories.articlesOfCategory',['id'=>$article->category_id]);
    }
}
