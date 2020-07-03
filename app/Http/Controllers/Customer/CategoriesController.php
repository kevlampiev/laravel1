<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TmpServices\DBConnService;
use App\ArticlesCategory;
use App\Articles;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{
    public function articlesOfCategory(int $id)
    {
        $category=ArticlesCategory::find($id);
//        dd($category);
//        $category = DB::selectOne('SELECT id,name from news_categories WHERE id=?', [$id]);
        //DBConnService::selectSingleRow('SELECT id,name from news_categories WHERE id=?', [$id]);
        if ($category==null) {
            abort(404);
        }
        $newsOfCategory = Articles::where('category_id',$id)->get();
//            DB::select('SELECT * FROM articles WHERE category_id=?', [$id]);

            //DBConnService::selectRowsSet('SELECT * FROM v_news WHERE category_id=?', [$id]);

        return view('customer.articles-of-category', [
            'news' => $newsOfCategory,
            'category' => $category
        ]);
    }
}
