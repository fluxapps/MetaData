<?php

namespace SRAG\ILIAS\Plugins\MetaData\Config;

/**
 * Class SimpleTable
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class SimpleTable
{

    /**
     * @var array
     */
    protected $columns = array();
    /**
     * @var array
     */
    protected $rows = array();


    /**
     * @param array $columns
     */
    public function __construct(array $columns = array())
    {
        $this->columns = $columns;
    }


    /**
     * @param $column
     *
     * @return $this
     */
    public function column($column)
    {
        $this->columns[] = $column;

        return $this;
    }


    /**
     * @param array $row
     *
     * @return $this
     */
    public function row(array $row)
    {
        $this->rows[] = $row;

        return $this;
    }


    /**
     * @return string
     */
    public function render()
    {
        $out = "<table class='table table-striped'><thead><tr>";
        foreach ($this->columns as $col) {
            $out .= "<th>{$col}</th>";
        }
        $out .= "</thead></tr><tbody>";
        foreach ($this->rows as $row) {
            $out .= "<tr>";
            foreach ($row as $col) {
                $out .= "<td>{$col}</td>";
            }
            $out .= "</tr>";
        }
        $out .= "</tbody></table>";

        return $out;
    }
}