<?php

function get_star_class($n,$x){
    return (ceil($n)%$x === 0) ? ceil($n) : round(($n+$x/2)/$x)*$x;
}
