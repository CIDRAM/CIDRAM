<?php
/**
 * This file is a part of the CIDRAM package.
 * Homepage: https://cidram.github.io/
 *
 * CIDRAM COPYRIGHT 2016 and beyond by Caleb Mazalevskis (Maikuolan).
 *
 * License: GNU/GPLv2
 * @see LICENSE.txt
 *
 * This file: Report orchestrator (last modified: 2020.11.27).
 */

namespace CIDRAM\Core;

class Reporter
{
    /**
     * @var array An array of handlers to use for processing reports.
     */
    private $Handlers = [];

    /**
     * @var array An array of reports to process.
     */
    private $Reports = [];

    /**
     * Adds a new report handler.
     *
     * @param callable $Handler The handler to add.
     */
    public function addHandler(callable $Handler)
    {
        $this->Handlers[] = $Handler;
    }

    /**
     * Adds data to the current report and pushes the queue forward if needed.
     *
     * @param int|array $Categories An ID, or an array of IDs, for the current report.
     * @param string|array $Comments A comment, or an array of comments, for the current report.
     * @param string $IP The IP address associated with the current report.
     */
    public function report($Categories, $Comments, $IP)
    {
        if (!isset($this->Reports[$IP])) {
            $this->Reports[$IP] = ['Categories' => [], 'Comments' => [], 'IP' => $IP];
        }
        if (!is_array($Categories)) {
            $Categories = [$Categories];
        }
        foreach ($Categories as $Category) {
            $this->Reports[$IP]['Categories'][] = $Category;
        }
        if (!is_array($Comments)) {
            $Comments = [$Comments];
        }
        foreach ($Comments as $Comment) {
            $this->Reports[$IP]['Comments'][] = $Comment;
        }
    }

    /**
     * Count reports.
     *
     * @return int The number of reports in the queue.
     */
    public function count(): int
    {
        return count($this->Reports);
    }

    /** Process all reports. */
    public function process()
    {
        /** Iterate through handlers. */
        foreach ($this->Handlers as $Handler) {

            /** Iterate through queued reports. */
            foreach ($this->Reports as $Report) {

                /** Guard. */
                if (empty($Report['Categories']) || empty($Report['Comments']) || empty($Report['IP'])) {
                    continue;
                }

                /** Don't duplicate categories. */
                $Report['Categories'] = array_unique($Report['Categories'], SORT_NUMERIC);

                /** Prepare comments. */
                $Report['Comments'] = sprintf('Automated report (%s). %s', date('c', time()), implode(' ', $Report['Comments']));

                /** Call handler. */
                $Handler($Report);
            }
        }

        /** Flush old reports. */
        $this->Reports = [];
    }
}
