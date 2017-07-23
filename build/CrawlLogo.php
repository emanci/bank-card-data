<?php

use GuzzleHttp\Client as GuzzleClient;

require __DIR__.'/vendor/autoload.php';
/**
 * 利用从支付宝页面抓取的银行列表，调用支付宝提供的接口获取对应银行的图标.
 */
class CrawlLogo
{
    public function run()
    {
        $startTime = microtime(true);
        $guzzleClient = new GuzzleClient(array(
            'timeout' => 300,
        ));

        $catalog = include __DIR__.'/../bank-list/BankList.php';
        $base64Filename = __DIR__.'/../bank-logo-base64/LogoList.php';
        $count = 0;

        foreach ($catalog as $shortCode => $bankName) {
            $url = 'https://apimg.alipay.com/combo.png?d=cashier&t='.$shortCode;
            $result = $guzzleClient->request('GET', $url);
            $image = $result->getBody();
            $suffix = 'png';
            $filename = __DIR__."/../bank-logo/{$shortCode}.".$suffix;
            file_put_contents($filename, $image);
            // base64
            $content = 'data:image/png;base64,'.base64_encode($image);
            if (!file_exists($base64Filename)) {
                file_put_contents($base64Filename, "<?php\rreturn array(\r");
            } else {
                $row = "\t"."'".$shortCode."'".' => '."'".$content."',"."\r";
                file_put_contents($base64Filename,  $row, FILE_APPEND);
            }
            ++$count;
        }

        // eof
        file_put_contents($base64Filename,  "\r);", FILE_APPEND);

        $endTime = microtime(true);
        $cost = $endTime - $startTime;
        echo "Save: cost {$cost} seconds, Total : {$count}";
    }
}

$crawlLogo = new CrawlLogo();
$crawlLogo->run();
