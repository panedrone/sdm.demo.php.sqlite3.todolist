<?php

// Code generated by a tool. DO NOT EDIT.
// https://sqldalmaker.sourceforge.net/


class TaskLi
{
    /**
     * @var int
     */
    private $t_id;
    /**
     * @var int
     */
    private $t_priority;
    /**
     * @var string
     */
    private $t_date;
    /**
     * @var string
     */
    private $t_subject;

    public function get_t_id()
    {
        return $this->t_id;
    }

    public function set_t_id($value)
    {
        $this->t_id = $value;
    }

    public function get_t_priority()
    {
        return $this->t_priority;
    }

    public function set_t_priority($value)
    {
        $this->t_priority = $value;
    }

    public function get_t_date()
    {
        return $this->t_date;
    }

    public function set_t_date($value)
    {
        $this->t_date = $value;
    }

    public function get_t_subject()
    {
        return $this->t_subject;
    }

    public function set_t_subject($value)
    {
        $this->t_subject = $value;
    }
}
