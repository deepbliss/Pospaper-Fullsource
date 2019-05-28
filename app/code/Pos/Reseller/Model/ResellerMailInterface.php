<?php
namespace Pos\Reseller\Model;

interface ResellerMailInterface
{
    public function send($replyTo, array $variables);
}
