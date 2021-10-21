<?php
namespace App\Lib;

class Validation
{
    public function ValidationErrorControl(array $validationErrors): void
    {
        if ($validationErrors)
        {
            $result = [
                "validationErrors" => $validationErrors
            ];

            echo json_encode($result);

            exit;
        }
    }
}