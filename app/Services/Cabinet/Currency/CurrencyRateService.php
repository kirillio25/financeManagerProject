<?php 


namespace App\Services\Cabinet\Currency;

class CurrencyRateService
{
    public function getUsdRate(): float
    {
        $xml = simplexml_load_file('https://nationalbank.kz/rss/rates_all.xml');

        foreach ($xml->channel->item as $item) {
            if ((string)$item->title === 'USD') {
                return (float)str_replace(',', '.', (string)$item->description);
            }
        }

        throw new \Exception('Курс USD не найден');
    }
}
