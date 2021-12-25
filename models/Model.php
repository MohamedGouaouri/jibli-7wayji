<?php


abstract class Model
{
    abstract public static function all();
    abstract public static function limit($rows);
}