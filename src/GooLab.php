<?php

namespace Mntbllr\GooLab;

use GuzzleHttp\Client;

class GooLab
{
    private string $app_id;
    private array $parts_of_speech = [
        ['jp' => '形容詞接尾辞', 'en' => 'adjective suffix'],
        ['jp' => '動詞活用語尾', 'en' => 'verb conjugative suffix'],
        ['jp' => '形容詞語幹', 'en' => 'adjective stem'],
        ['jp' => '名詞接尾辞', 'en' => 'noun suffix'],
        ['jp' => '接続接尾辞', 'en' => 'independent word'],
        ['jp' => '接続接尾辞', 'en' => 'connection suffix'],
        ['jp' => '英語接尾辞', 'en' => 'english suffix'],
        ['jp' => '動詞接尾辞', 'en' => 'verb suffix'],
        ['jp' => '動詞語幹', 'en' => 'verb stem'],
        ['jp' => '引用助詞', 'en' => 'quote particle'],
        ['jp' => '連用助詞', 'en' => 'continuous particle'],
        ['jp' => '補助名詞', 'en' => 'auxiliary noun'],
        ['jp' => '冠形容詞', 'en' => 'adjective prefix'],
        ['jp' => '助助数詞', 'en' => 'auxiliary number'],
        ['jp' => '終助詞', 'en' => 'ending particle'],
        ['jp' => '連用詞', 'en' => 'adverb'],
        ['jp' => '接続詞', 'en' => 'conjunction'],
        ['jp' => '独立詞', 'en' => 'independent word'],
        ['jp' => '形容詞', 'en' => 'adjective'],
        ['jp' => '判定詞', 'en' => 'discriminant'],
        ['jp' => '冠動詞', 'en' => 'verb prefix'],
        ['jp' => '連体詞', 'en' => 'adnominal'],
        ['jp' => '冠名詞', 'en' => 'pronoun'],
        ['jp' => '間投詞', 'en' => 'interjection'],
        ['jp' => '格助詞', 'en' => 'case particle'],
        ['jp' => '冠数詞', 'en' => 'prefix number'],
        ['jp' => '助数詞', 'en' => 'counter suffix'],
        ['jp' => '助詞', 'en' => 'particle'],
        ['jp' => '動詞', 'en' => 'verb'],
        ['jp' => '名詞', 'en' => 'noun'],
        ['jp' => '括弧', 'en' => 'brackets'],
        ['jp' => '句点', 'en' => 'punctuation'],
        ['jp' => '読点', 'en' => 'comma'],
        ['jp' => '空白', 'en' => 'blank'],
    ];

    public function __construct($app_id)
    {
        $this->app_id = $app_id;
    }

    public function request($method, $data)
    {
        $client = new Client();

        $data['app_id'] = $this->app_id;
        $request = $client->request('POST', 'https://labs.goo.ne.jp/api/' . $method,
            [
                'headers' => [
                    'Accept' => 'application/json'
                ],
                'form_params' => $data,
            ]
        );
        if ($request->getStatusCode() == 200) {
            return json_decode($request->getBody()->getContents(), true);
        }
        return false;
    }

    /**
     * Calls the morph API endpoint
     * @param  string  $sentence
     * @param  array  $info_filter
     * @param  array  $pos_filter
     * @param  bool  $translate_pos
     * @return  bool|array
     */
    public function morphology($sentence, $info_filter = null, $pos_filter = null, $translate_pos = true)
    {
        if (is_array($info_filter)) {
            $info_filter = implode("|", $info_filter);
        }

        if (is_array($pos_filter)) {
            $pos_filter = implode("|", $pos_filter);
            if ($translate_pos) {
                $pos_filter = $this->translatePartOfSpeech($pos_filter, 'en', 'jp');
            }
        }

        $data = $this->request('morph', array(
            'info_filter' => $info_filter,
            'pos_filter' => $pos_filter,
            'sentence' => $sentence
        ));

        if ($translate_pos && $data['word_list']) {
            $word_list_json = json_encode($data['word_list'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $word_list_json = $this->translatePartOfSpeech($word_list_json, 'jp', 'en');
            $data['word_list'] = json_decode($word_list_json, true)[0];
        }
        return $data['word_list'] ?? false;
    }

    public function toKana($sentence, $kana_type = 'hiragana')
    {
        $data = $this->request('hiragana', [
            'output_type' => $kana_type,
            'sentence' => $sentence
        ]);

        return $data['converted'] ?? false;
    }

    /**
     * Calls the hiragana API endpoint
     * @param  string  $sentence Sentence to be converted
     * @return  string
     */
    public function toHiragana($sentence)
    {
        return $this->toKana($sentence, 'hiragana');
    }

    /**
     * Calls the hiragana API endpoint passing katakana as converting value
     * @param  string  $sentence Sentence to be converted
     * @return  string
     */
    public function toKatakana($sentence)
    {
        return $this->toKana($sentence, 'katakana');
    }

    public function translatePartOfSpeech($string, $from, $to)
    {
        foreach ($this->parts_of_speech as $pos) {
            $string = str_replace($pos[$from], $pos[$to], $string);
        }
        return $string;
    }
}
