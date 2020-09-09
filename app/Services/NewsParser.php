<?php


namespace App\Services;


use App\Models\TmpArticleData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Orchestra\Parser\Xml\Facade as XmlParser;

class NewsParser
{
    private $sourceUrl;


    public function __construct(string $sourceName)
    {
        $this->sourceUrl=$sourceName;
    }

    public function getData(): array
    {
        $xml = XmlParser::load($this->sourceUrl);
        $data = $xml->parse(
            [
                'source' => ['uses' => 'channel.link'],
                'category'=>['uses'=>'chanel.category'],
                'news' => ['uses' => 'channel.item[category,title,link,guid,enclosure::url,description,pubDate]'],
            ]
        );
        //хочу чтобы на выходе был стандарный массив, но не знаю как иначе
        $result = [];
        foreach ($data['news'] as $el) {
            $result[] = [
                'category' => isset($el['category'])?$el['category']:$data['category'],
                'slug' => Str::slug($el['category']),
                'source' => $data['source'],
                'title' => $el['title'],
                'announcement' => $el['title'],
                'article_body' => $el['description'],
                'img' => isset($el['enclosure::url'])?$el['enclosure::url']:'',
                'is_private' => false,
                'link' => $el['link'],
                'created_at' => $el['pubDate'],
                'guid'=>$el['guid']
            ];
        }
        return $result;
    }


    public function storeArticles()
    {
        $articles=$this->getData();
        //оптимальнее не получится с учетом того, что использую временные таблицы базы
        if (count($articles) > 0) {
            foreach ($articles as $el) {
                $article = new TmpArticleData();
                $article->fill($el);
                $article->save();
            }
        }
        //TODO сделать, чтобы возвращало количество добавленных записей
        DB::unprepared('CALL parse_articles()');
        session()->flash('proceed_status', 'Произведена зазрузка данных Lenta.ru');

    }


}