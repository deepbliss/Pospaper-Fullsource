<?php
namespace Pos\CreditApp\Model;

interface CreditAppMailInterface
{

    public function send($replyTo, array $variables);
}
