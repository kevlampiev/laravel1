<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*****************************************/
/*********** КЛИЕНТСКАЯ ЧАСТЬ ************/
/*****************************************/
Route::group([
    'namespace' => 'Customer'
],
    function () {
        Route::get('/', 'HomeController@index')->name('home');
        Route::get('/privacy', 'HomeController@privacy')->name('privacy');
        Route::get('/feedback', 'CustomerRequestController@addFeedback')->name('customer.feedback');
        Route::post('/feedback', 'CustomerRequestController@storeFeedback');

        Route::post('/search','SearchArticlesController@searchArticles')->name('customer.fearchResult');

        Route::group([
            'middleware' => ['auth']
        ],
            function () {
                Route::get('/info-enquiery', 'InfoEnquiryController@create')->name('customer.infoEnquiery');
                Route::post('/info-enquiery', 'InfoEnquiryController@store');
                Route::get('/personal-account', 'ManageProfileController@changePersonalAccount')->name('customer.personalAccount');
                Route::post('/personal-account', 'ManageProfileController@changePersonalAccount');
            });


        Route::group([
            'prefix' => '/auth'
        ],
            function () {
                Route::get('/vk', 'LoginController@loginVK')->name('vkLogin');
                Route::get('/vk/response', 'LoginController@responseVK')->name('vkResponse');
                Route::get('/fb', 'LoginController@loginFB')->name('fbLogin');
                Route::get('/fb/response', 'LoginController@responseFB')->name('fbResponse');
                Route::get('/github', 'LoginController@loginGH')->name('ghLogin');
                Route::get('/github/response', 'LoginController@responseGH')->name('ghResponse');
            });

        //данные по статьям и категориям /categories+
        Route::group([
            'prefix' => 'categories'
        ],
            function () {
                Route::get('/', 'HomeController@newsCategoriesList')->name('customer.categories');
                Route::get('/{slug}/articles', 'CategoryController@articlesOfCategory')->name('customer.articlesOfCategory');
                Route::get('/{slug}/articles/{id}', 'ArticleController@index')->name('customer.showArticle');
            }
        );

    });


/*****************************************/
/****** АДМИНИСТРАТИВНАЯ ЧАСТЬ ***********/
/*****************************************/
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => ['auth', 'is.admin']
],
    function () {
        Route::get('/', 'HomeController@index')->name('admin');

        //категории новостей /admin/categories +
        Route::group([
            'prefix' => 'categories'
        ],
            function () {
                Route::get('/', 'CategoryController@index')->name('admin.categoriesList');
                Route::get('add', 'CategoryController@create')->name('admin.addCategory');
                Route::post('add', 'CategoryController@insert');
                Route::get('{category}/edit', 'CategoryController@edit')->name('admin.editCategory');
                Route::post('{category}/edit', 'CategoryController@update');
                Route::post('{category}/delete', 'CategoryController@delete')->name('admin.deleteCategory');
                //статьи конкретной категории   /admin/categories/{slug}/articles +
                Route::group([
                    'prefix' => '{slug?}/articles'
                ], function () {
                    Route::get('/', 'CategoryController@articlesOfCategory')->name('admin.articlesOfCategory');
                    Route::get('add', 'ArticleController@add')->name('admin.addArticle');
                    Route::post('add', 'ArticleController@insert');
                    Route::get('{article}/edit', 'ArticleController@edit')->name('admin.editArticle');
                    Route::post('{article}/edit', 'ArticleController@update');
                    Route::post('{article}/delete', 'ArticleController@delete')->name('admin.deleteArticle');
                });
            }
        );

        Route::group(['prefix' => 'parse'], function () {
            Route::get('parseAll', 'ParserController@loadAllNews')->name('admin.loadAllNews');
        });

        //Нормальные источники новостей (из базы данных)  /admin/infosources +
        Route::group([
            'prefix' => 'infosources'
        ], function () {
            Route::get('/', 'InfoSourceController@index')->name('admin.infoSourcesList');
            Route::get('add', 'InfoSourceController@create')->name('admin.addInfoSource');
            Route::post('add', 'InfoSourceController@insert');
            Route::get('{source}/edit', 'InfoSourceController@edit')->name('admin.editInfoSource');
            Route::post('{source}/edit', 'InfoSourceController@update');
            Route::post('{source}/delete', 'InfoSourceController@delete')->name('admin.deleteInfoSource');
            Route::post('{source}/parse', 'ParserController@loadSingle')->name('admin.parseInfoSource');
        });

        Route::resource('infoEnquiries', 'InfoEnquiryController');

        //Нормальные источники новостей (из базы данных)  /admin/users +
        Route::group(['prefix' => 'users'], function () {
            Route::get('/', 'UserController@index')->name('admin.usersList');
            Route::post('/', 'UserController@switchRole');
            Route::get('add', 'UserController@create')->name('admin.addUser');
            Route::post('add', 'UserController@insert');
            Route::get('{user}/edit', 'UserController@edit')->name('admin.editUser');
            Route::post('{user}/edit', 'UserController@update');

        });
    }
);

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'is.admin', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});


Auth::routes();

