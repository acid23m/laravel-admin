<?php
declare(strict_types=1);

namespace SP\Admin\Log;

use Illuminate\Support\Carbon;
use SP\Admin\Helpers\Formatter;

/**
 * Log parser.
 *
 * @package SP\Admin\Log
 */
class Parser
{
    /**
     * @var string|null Path to log file
     */
    protected ?string $log_path;

    /**
     * Parser constructor.
     */
    public function __construct()
    {
        /** @var string|null $ch */
        $ch = config('admin.log_channel');

        if ($ch !== null) {
            $this->log_path = config("logging.channels.$ch.path");
        }
    }

    /**
     * Parses log file.
     *
     * @return array [items => [datetime, level, message, context, extra], total]
     * @throws \Carbon\Exceptions\InvalidFormatException
     */
    public function parse(): array
    {
        return [
            'items' => $this->getRowData(),
            'total' => $this->total(),
        ];
    }

    /**
     * Reads log file line by line.
     *
     * @return iterable
     */
    protected function readLines(): iterable
    {
        if ($this->log_path === null) {
            return [];
        }

        $f = fopen($this->log_path, 'rb');

        try {
            while ($line = fgets($f)) {
                yield $line;
            }
        } finally {
            fclose($f);
        }
    }

    /**
     * Collects data for rows in table.
     *
     * @return iterable
     * @throws \Carbon\Exceptions\InvalidFormatException
     */
    public function getRowData(): iterable
    {
        $log_item = '';
        $index = 0;

        foreach ($this->readLines() as $line) {
            $log_item .= $line;

            if (str_contains($line, LineFormatter::LINE_END)) {
                $index ++;

                // parses columns
                $log_item = rtrim(trim($log_item), LineFormatter::LINE_END);
                [$datetime, $level, $message, $context, $extra] = explode(LineFormatter::COLUMN_SEPARATOR, $log_item);
                // clears buffer
                $log_item = '';

                // normalizes row data
                yield [
                    'index' => $index,
                    'datetime' => value(static function () use (&$datetime) {
                        /** @var string $datetime */
                        $datetime = trim(trim($datetime), '[]');

                        $dt = Carbon::parse($datetime);
                        $dt->timezone('UTC');

                        return Formatter::isoToLocalDateTime($dt);
                    }),
                    'level' => trim($level),
                    'message' => trim($message),
                    'context' => trim($context),
                    'extra' => trim($extra),
                ];
            }
        }
    }

    /**
     * Number of log items.
     *
     * @return int
     */
    public function total(): int
    {
        $n = 0;

        foreach ($this->readLines() as $line) {
            if (str_contains($line, LineFormatter::LINE_END)) {
                $n ++;
            }
        }

        return $n;
    }

}
