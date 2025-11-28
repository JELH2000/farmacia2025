<?php
class fecha
{
    private $tiempo;

    function _construct(string $fech)
    {
        if ($fech != null) {
            $parse = date_parse($fech);
            $this->tiempo = mktime($parse['hour'], $parse['minut'], $parse['second'], $parse['month'], $parse['day'], $parse['year']);
        } else {
            $parse = date_parse(date('Y-m-d H-i-s'));
            $this->tiempo = mktime($parse['hour'], $parse['minut'], $parse['second'], $parse['month'], $parse['day'], $parse['year']);
        }
    }

    function setFechaString(string $fech): void
    {
        $parse = date_parse($fech);
        $this->tiempo = mktime($parse['hour'], $parse['minut'], $parse['second'], $parse['month'], $parse['day'], $parse['year']);
    }

    function setFechaInteger(int $fech): void
    {
        $this->tiempo = $fech;
    }

    function getFechaString(): string
    {
        return date('Y-m-d H-i-s', $this->tiempo);
    }

    function getFechaFormat(string $format): string
    {
        return date($format, $this->tiempo);
    }

    function getFechaInteger(): int
    {
        return $this->tiempo;
    }
}
