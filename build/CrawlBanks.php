<?php

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;

require __DIR__.'/vendor/autoload.php';

class CrawlBanks
{
    public function run()
    {
        $startTime = microtime(true);
        $goutteClient = new Client();
        $guzzleClient = new GuzzleClient(array(
            'timeout' => 300,
        ));
        $goutteClient->setClient($guzzleClient);

        $url = 'https://ab.alipay.com/i/yinhang.htm';
        $crawler = $goutteClient->request('GET', $url);

        $bankList = [];

        $crawler->filter('li[class="ap-a-cnt-list"]')->each(function ($li, $index) use (&$bankList) {
            $category = $li->filter('h3')->text();
            $li->filter('ul[class="ui-list-icons fn-clear cashier-bank"] > li')->each(function ($bank_li, $bank_index) use (&$category, &$bankList) {
                $bankName = $bank_li->filter('span[class^="icon "]')->text();
                $spanClassName = $bank_li->filter('span[class^="icon "]')->attr('class');
                $shortCode = explode(' ', $spanClassName)[1];
                $bankList[$shortCode] = $bankName;
            });
        });

        $endTime = microtime(true);
        $cost = $endTime - $startTime;
        echo "Crawl down: cost {$cost} seconds.";

        // 保存抓取的数据
        file_put_contents('../bank-list/BankList.php',  "<?php\rreturn ".var_export($bankList, true).';');
        echo '<br/>Crawl and save done.';
    }
}

$crawlBanks = new CrawlBanks();
$crawlBanks->run();
