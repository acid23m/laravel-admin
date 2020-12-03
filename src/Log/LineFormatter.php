<?php
declare(strict_types=1);

namespace SP\Admin\Log;

use Illuminate\Log\Logger;
use Monolog\Formatter\LineFormatter as MonologLineFormatter;

/**
 * Custom LineFormatter for Monolog.
 *
 * @package SP\Admin\Log
 */
class LineFormatter
{
    public const SIMPLE_FORMAT = "[%datetime%] || %channel%.%level_name% || %message% || %context% || %extra% ;;\n";
    public const LINE_END = ';;';
    public const COLUMN_SEPARATOR = '||';

    /**
     * Customizes the given logger instance.
     *
     * @param Logger $logger
     */
    public function __invoke(Logger $logger): void
    {
        /** @var \Monolog\Handler\Handler $handler */
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(
                tap(
                    new MonologLineFormatter(self::SIMPLE_FORMAT, null, true),
                    static fn(MonologLineFormatter $formatter) => $formatter->includeStacktraces()
                )
            );
        }
    }

}
