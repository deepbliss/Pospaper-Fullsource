<?php
namespace Pos\Custommodule\Model;

interface CustommoduleMailInterface
{
    public function send($replyTo, array $variables);
}
