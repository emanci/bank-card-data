<?php

/**
 * 数据来源：
 * http://blog.lastww.com/2015/09/26/codings-of-cards.
 */
class MergeBanks
{
    /**
     * 字典分为两张表
     * 第一张：[
     *     short_code => bank_name
     * ]
     * 第二张：[[
     *     bank_name => xxxx,
     *     short_code => xxxx2,
     *     patterns => [
     *         [
     *             reg => yyyy,
     *             type => zzzz
     *         ]
     *     ]
     * ]].
     *
     * @var [type]
     */
    /*protected $banks = [
        [
            'name' => '中国邮政储蓄银行',
            'code' => 'PSBC',
            'patterns' => [
                [
                    'reg' => '/^(621096|621098|622150|622151|622181|622188|622199|955100|621095|620062|621285|621798|621799|621797|620529|621622|621599|621674|623218|623219)\d{13}$/',
                    'type' => 'DC',
                ],
                [
                    'reg' => '/^(62215049|62215050|62215051|62218850|62218851|62218849)\d{11}$/',
                    'type' => 'DC',
                ],
                [
                    'reg' => '/^(622812|622810|622811|628310|625919)\d{10}$/',
                    'type' => 'CC',
                ],
            ],
        ],
    ];*/

    public function run()
    {
        $banksFilename = dirname(__DIR__).'/bank-list/Banks.php';
        $banks = include $banksFilename;
        $cardBin = include dirname(__DIR__).'/card-bin/CardBin.php';
        $newData = [];

        foreach ($cardBin as $value) {
            $code = $value['code'];
            if (!array_key_exists($code, $banks)) {
                $newData[$code] = $value['name'];
            }
        }

        $mergeBanks = array_merge($banks, $newData);

        file_put_contents($banksFilename, "<?php\rreturn ".var_export($mergeBanks, true).';');
    }
}

// run
(new MergeBanks())->run();
