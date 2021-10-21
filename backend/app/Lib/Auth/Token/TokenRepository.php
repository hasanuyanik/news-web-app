<?php
namespace App\Lib\Auth\Token;

use App\Lib\Database\DatabaseFactory;
use App\Lib\Encoder\Encoder;

class TokenRepository
{
    public function tokenControl(Token $token, $errors): void
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        if ($token->resource_id) $fields["resource_id"] = $token->resource_id;
        if ($token->resource_type) $fields["resource_type"] = $token->resource_type;
        $fields["token"] = $token->token;

        $result = $db->find("token",$fields);

        if ($result != null)
        {
            header('Content-Type: application/json; charset=utf-8', response_code: 401);

            echo json_encode($errors);

            exit;
        }

    }

    public function create(Token $token): string
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["resource_id"] = $token->resource_id;
        $fields["resource_type"] = $token->resource_type;

        $newToken = "";
        while (!$newToken)
        {

            $generatedToken = $this->tokenGenerator();
            $token->token = $generatedToken;

            $this->tokenControl($token, errors: []);

            $newToken = $generatedToken;
            $token->token = "";
            $deleteLastToken = $this->delete($token);

        }

        $fields["token"] = $newToken;
        $fields["created_at"] = $token->created_at;



        $result = $db->add("token",$fields);

        return $newToken;
    }

    public function delete(Token $token): string
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        if ($token->token)
        {
            $fields["token"] = $token->token;
        }
        else
        {
            $fields["resource_id"] = $token->resource_id;
            $fields["resource_type"] = $token->resource_type;
        }



        $result = $db->delete("token",$fields);

        return $result;
    }

    public function tokenGenerator(): string
    {
        $randNum = rand(10000000,1000000000);

        $encoder = new Encoder();
        $token = $encoder->encode($encoder->salt($encoder->encode($randNum)));

        return $token;
    }
}