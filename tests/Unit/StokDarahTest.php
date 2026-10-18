<?php

namespace Tests\Unit;

use App\Models\StokDarah;
use Tests\TestCase;

class StokDarahTest extends TestCase
{
    public function test_stok_darah_label_accessor(): void
    {
        $stok = new StokDarah([
            'golongan_darah' => 'AB',
            'rhesus'         => '+',
            'jumlah'         => 15,
            'min_stock'      => 10,
        ]);

        $this->assertEquals('AB+', $stok->label);
        $this->assertFalse($stok->isLowStock());
    }

    public function test_stok_darah_low_stock_detection(): void
    {
        $stok = new StokDarah([
            'golongan_darah' => 'O',
            'rhesus'         => '-',
            'jumlah'         => 5,
            'min_stock'      => 10,
        ]);

        $this->assertTrue($stok->isLowStock());
    }
}

