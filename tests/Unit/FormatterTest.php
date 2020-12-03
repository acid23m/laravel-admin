<?php
declare(strict_types=1);

namespace SP\Admin\Tests\Unit;

use Illuminate\Support\Carbon;
use SP\Admin\Helpers\Formatter;
use SP\Admin\Tests\TestCase;

class FormatterTest extends TestCase
{
    protected bool $createDatabases = false;

    public function testIsoToLocalDate(): void
    {
        $dt = Formatter::isoToLocalDate('2020-01-01 00:10:30');
        self::assertEquals('Jan 1, 2020', $dt);

        $dt = Formatter::isoToLocalDate('2020-01-01');
        self::assertEquals('Jan 1, 2020', $dt);

        $dt = Formatter::isoToLocalDate(
            (new Carbon())->setDay(1)->setMonth(1)->setYear(2020)->startOfDay()
        );
        self::assertEquals('Jan 1, 2020', $dt);
    }

    public function testIsoToLocalDateTime(): void
    {
        $dt = Formatter::isoToLocalDateTime('2020-01-01 00:10:30');
        self::assertEquals('Jan 1, 2020 12:10 AM', $dt);

        $dt = Formatter::isoToLocalDateTime('2020-01-01');
        self::assertEquals('Jan 1, 2020 12:00 AM', $dt);

        $dt = Formatter::isoToLocalDateTime(
            (new Carbon())->setDay(1)->setMonth(1)->setYear(2020)->startOfDay()
        );
        self::assertEquals('Jan 1, 2020 12:00 AM', $dt);
    }

    public function testBooleanToString(): void
    {
        $val = Formatter::booleanToString(true);
        self::assertEquals(__('Yes'), $val);

        $val = Formatter::booleanToString(1);
        self::assertEquals(__('Yes'), $val);

        $val = Formatter::booleanToString(0);
        self::assertEquals(__('No'), $val);

        $val = Formatter::booleanToString('1');
        self::assertEquals(__('Yes'), $val);

        $val = Formatter::booleanToString('0');
        self::assertEquals(__('No'), $val);
    }

    public function testByteSize(): void
    {
        $s = Formatter::byteSize(1);
        self::assertEquals('1 B', $s);

        $s = Formatter::byteSize(1000);
        self::assertEquals('1.00 Kb', $s);

        $s = Formatter::byteSize(1000, ['decimalPlaces' => 0]);
        self::assertEquals('1 Kb', $s);

        $s = Formatter::byteSize(1_000_000);
        self::assertEquals('1.00 Mb', $s);

        $s = Formatter::byteSize(1e9);
        self::assertEquals('1.00 Gb', $s);

        $s = Formatter::byteSize(2048, [
            'binary' => true,
            'decimalPlaces' => 0,
        ]);
        self::assertEquals('2 KiB', $s);
    }

}
