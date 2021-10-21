<?php

class User
{
    private $id = 0;
    private $name = '';
    private $email = '';
    private $key = 'не сгенерирован';
    private $photo = 'images/default.png';
    private $status = 0;
    private $activation_code = '';

    public function __construct(array $array)
    {
        $this->id = $array['id'];
        $this->name = $array['name'];
        $this->email = $array['email'];
        $this->key = $array['generated_key'];
        $this->photo = $array['photo'];
        $this->status = $array['status'];
        $this->activation_code = $array['activation_code'];
    }

    public function __get($attr)
    {
        return $this->$attr;
    }

    public function __set($attr, $value)
    {
        $this->$attr = $value;
    }

    public static function toXML(User $user)
    {
        $format = "<%s>%s</%s>\n";
        $string_id = sprintf($format, 'id', $user->id, 'id');
        $string_name = sprintf($format, 'name', $user->name, 'name');
        $string_email = sprintf($format, 'email', $user->email, 'email');
        $string_key = sprintf($format, 'key', $user->key, 'key');
        $string_photo = sprintf($format, 'photo', $user->photo, 'photo');
        $string_status = sprintf($format, 'status', $user->status, 'status');
        $string_activation_code = sprintf($format, 'activation_code', $user->activation_code, 'activation_code');

        return "<user>" . $string_id . $string_name . $string_email . $string_key . $string_photo . $string_status . $string_activation_code . "</user>";
    }

}
