<?php
namespace App\Trail;

use Carbon\Carbon;

trait SearchText
{

    public function searchTerm(&$query, $search, $fields, $table = null)
    {

        $searchTerms = array_filter(explode(" ", $search), function ($value) {
            return !empty($value);
        });

        $query
            ->where(function ($query) use ($searchTerms, $fields, $table) {

                foreach ($fields as $attribute) {
                    if ($table) {
                        $attribute = $table . '.' . $attribute;
                    }

                    foreach ($searchTerms as $searchTerm) {
                        $sql = "LOWER({$attribute}) LIKE ?";
                        $searchTerm = mb_strtolower($searchTerm, 'UTF8');
                        $query->orWhereRaw($sql, ["%{$searchTerm}%"]);
                    }
                }
            });

    }

    public function searchDateInterval(&$query, $from = null, $to = null, $table = null)
    {
        if ($from || $to) {
            
            $created = $table ? $table . '.created_at' : 'created_at';

            if (\is_string($from)) {
                $data = \explode('-', $from);
                $from = Carbon::createFromDate($data[0], $data[1], $data[2]);
                $from->startOfDay();
            }

            if (\is_string($to)) {
                $data = \explode('-', $to);
                $to = Carbon::createFromDate($data[0], $data[1], $data[2]);
                $to->endOfDay();
            }
            if ($from && $to) {

                $query->whereBetween($created, [$from, $to]);
            } else if ($from) {
                $query->where($created, '>=', $from);
            } else {
                $query->where($created, '<=', $to);
            }

        }
    }
}
