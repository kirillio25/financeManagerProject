<?php 

namespace Tests\Unit\Cabinet;

use Tests\TestCase;
use App\Services\Cabinet\Currency\CurrencyRateService;

class CurrencyRateServiceTest extends TestCase
{
    //проверка курса доллара
    public function it_parses_usd_rate()
    {
        $service = new CurrencyRateService();

        // Пример ручного вызова при реальном XML (можно сделать mock файла через dependency injection)
        $rate = $service->getUsdRate();

        $this->assertIsFloat($rate);
        $this->assertTrue($rate > 0);
    }
}
