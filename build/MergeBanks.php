<?php

/**
 * 数据来源：
 * https://github.com/hyperjiang/bankcard/blob/master/src/hyperjiang/BankCard/Lists.php.
 */
class MergeBanks
{
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
