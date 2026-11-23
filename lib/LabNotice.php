<?php
class LabNotice
{
    public $title = 'Notice';
    public $file = '';

    public function __destruct()
    {
        if ($this->file !== '') {
            echo '<div class="alert alert-info mono">' . @file_get_contents($this->file) . '</div>';
        }
    }
}
