<?php

namespace HeimrichHannot\DatabaseLogBundle\Monolog\Handler;

use Monolog\Handler\RotatingFileHandler;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DatabaseLogHandler extends RotatingFileHandler
{
    public function __construct(ParameterBagInterface $parameterBag)
    {
        $filename = $parameterBag->get("kernel.logs_dir").DIRECTORY_SEPARATOR."query-log.log";
        parent::__construct($filename, 10);
    }


    protected function write(array $record): void
    {
        if (200 === $record['level']) {
            return;
        }

        if (empty($record['context']['sql'])) {
            return;
        }

        /** @noinspection SqlWithoutWhere */
        $filters = [
            'ALTER',
            'CREATE',
            'SELECT',
            'SET',
            'SHOW',
            'TRUNCATE',
            'UNLOCK TABLES',
            'LOCK TABLES',

            'INSERT INTO tl_log',
            'INSERT INTO tl_undo',
            'INSERT INTO tl_version',

            'UPDATE tl_member SET session = ? WHERE',
            'UPDATE tl_search_term'.
            'UPDATE tl_undo',
            'UPDATE tl_user SET session',
            'UPDATE tl_version SET',

            'DELETE FROM tl_search',
            'DELETE FROM tl_search_index',
            'DELETE FROM tl_search_term',


            // project specific
            'INSERT INTO tl_watchlist_item',
            'DELETE FROM tl_watchlist_item',

            'INSERT INTO tl_iso_product_collection_item',
            'UPDATE tl_iso_product_collection_item',
            'DELETE FROM tl_iso_product_collection_item',

            'UPDATE tl_iso_product_collection',

            'INSERT INTO tl_belegungsplan_data',
        ];

        foreach ($filters as $filter) {
            $sql = $this->formatSql($record['context']['sql'] ?? '');

            if (str_starts_with($sql, $filter)) {
                return;
            }
        }

        if (isset($record['context']['sql'])) {
            $sql = '"' . $this->formatSql($record['context']['sql'] ?? '') . '"';
            if (isset($record['context']['params'])) {
                $sql .= ', '.str_replace(array("\r", "\n"), '', var_export($record['context']['params'], true));
            }
            $record['formatted'] = $sql."\n";
        }

        parent::write($record);
    }

    protected function formatSql(string $sql): string
    {
        $sql = str_replace(array("\r", "\n"), '', $sql);
        $sql = preg_replace('/\s+/', ' ', $sql);
        $sql = trim($sql, " \t\0\x0B\"");
        return $sql;
    }
}