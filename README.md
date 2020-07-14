#PHP Wrapper for the gooラボ Japanese Analysis API
Just a simple and compact PHP Wrapper for the [gooラボ Japanese Morphological Analysis API](https://labs.goo.ne.jp/api/jp/morphological-analysis/).

## Documentation
You can find gooラボ's documentation (in japanese) on https://labs.goo.ne.jp/api/jp/morphological-analysis/
## Installation

You can install the package via composer:

```bash
composer require mntbllr/goolab-php
```

## Usage

### Morphological Analysis

```php
$goo = new Mntbllr\GooLab\GooLab('YOUR_APP_ID');
$goo->morphology($sentence,$info_filter,$pos_filter,$translate_pos);
```

To run a full sentence breakdown, which is probably the case, you can pass just the `$sentence` argument:

``` php
$goo = new Mntbllr\GooLab\GooLab('YOUR_APP_ID');
$goo->morphology('PHPはすごく面白いですね');

/*
array (
    0 =>
        array (
            0 => 'PHP',
            1 => 'noun',
            2 => 'ピーエイチピー',
        ),
    1 =>
        array (
            0 => 'は',
            1 => 'continuous particle',
            2 => 'ハ',
        ),
    2 =>
        array (
            0 => 'すごく',
            1 => 'conjunction',
            2 => 'スゴク',
        ),
    3 =>
        array (
            0 => '面白',
            1 => 'adjective stem',
            2 => 'オモシロ',
        ),
    4 =>
        array (
            0 => 'いです',
            1 => 'adjective suffix',
            2 => 'イデス',
        ),
    5 =>
        array (
            0 => 'ね',
            1 => 'ending particle',
            2 => 'ネ',
        ),
)
*/
```

### Options

`$info_filter`: array that filters the type of information linguistic information to be returned from the API:

| value  | description  |
|---|---|
| form  | The breakdown of separated words only |
| pos  | The part of speech break down (noun, particle...) |
| read  | The pronunciation breakdown by word in katakana |  

Example:
``` php
$goo = new Mntbllr\GooLab\GooLab('YOUR_APP_ID');
$goo->morphology('PHPはすごく面白いですね',['form','read']);

/*
array (
    0 =>
        array (
            0 => 'PHP',
            2 => 'ピーエイチピー',
        ),
    1 =>
        array (
            0 => 'は',
            2 => 'ハ',
        ),
    2 =>
        array (
            0 => 'すごく',
            2 => 'スゴク',
        ),
    3 =>
        array (
            0 => '面白',
            2 => 'オモシロ',
        ),
    4 =>
        array (
            0 => 'いです',
            2 => 'イデス',
        ),
    5 =>
        array (
            0 => 'ね',
            2 => 'ネ',
        ),
)
*/
```

Omitting this parameter will give a full sentence breakdown using all the options above.

`$pos_filter`: array that filters the sentence breakdown by part of speech:
(If you set the `$translate_pos` to `false` follow [this link](https://labs.goo.ne.jp/api/jp/morphological-analysis-pos_filter/) to check the list of parts of speech in japanese)

| value  |
|---|
| adjective suffix |
| verb conjugative suffix |
| adjective stem |
| noun suffix |
| independent word |
| connection suffix |
| english suffix |
| verb suffix |
| verb stem |
| quote particle |
| continuous particle |
| auxiliary noun |
| adjective prefix |
| auxiliary number |
| ending particle |
| adverb |
| conjunction |
| independent word |
| adjective |
| discriminant |
| verb prefix |
| adnominal |
| pronoun |
| interjection |
| case particle |
| prefix number |
| counter suffix |
| particle |
| verb |
| noun |
| brackets |
| punctuation |
| comma |
| blank |

Example:
``` php
$goo = new Mntbllr\GooLab\GooLab('YOUR_APP_ID');
$goo->morphology('PHPはすごく面白いですね',['form','read'],['noun','adjective stem']);

/*
array (
    0 =>
        array (
            0 => 'PHP',
            1 => 'noun',
            2 => 'ピーエイチピー',
        ),
    1 =>
        array (
            0 => '面白',
            1 => 'adjective stem',
            2 => 'オモシロ',
        ),
    )
)
*/
```

Omitting this parameter will give a full sentence breakdown using all the options above.

`$translate_pos`: pass `false` to use the japanese names of part of speech in your api call and response. Translates them to english if omitted or `true`.

Example:
``` php
$goo = new Mntbllr\GooLab\GooLab('YOUR_APP_ID');
$goo->morphology('PHPはすごく面白いですね',['form','read'],['noun','adjective stem'],false);

/*
array (
    0 =>
        array (
            0 => 'PHP',
            1 => '名詞',
            2 => 'ピーエイチピー',
        ),
    1 =>
        array (
            0 => '面白',
            1 => '形容詞語幹',
            2 => 'オモシロ',
        ),
    )
)
*/
```

## Converting strings to hiragana or katakana
You can use the methods `toHiragana()` and `toKatakana()` to convert sentences or words accordingly.

Example:
``` php
$goo = new Mntbllr\GooLab\GooLab('YOUR_APP_ID');
$sentence = 'PHPはすごく面白いですね';
echo $goo->toHiragana($sentence);
//ぴーえいちぴーは すごく おもしろいですね

echo $goo->toKatakana($sentence);
//ピーエイチピーハ スゴク オモシロイデスネ
```

## Security

If you discover any security related issues, please email hi@mntbllr.dev instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
