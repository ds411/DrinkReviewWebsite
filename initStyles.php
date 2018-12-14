<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

if(!isset(StyleQuery::create()->filterByStyle('IPA')->find()[0])) {
    $s = new Style();
    $s->setStyle('IPA')->setDescription('India pale ale is consectetur adipiscing elit. Morbi sodales ligula lorem, id placerat lacus bibendum ultricies. Pellentesque id nulla rutrum, maximus urna at, fringilla neque. Morbi tempus, mauris at sollicitudin rutrum, elit odio fermentum mauris, at sollicitudin nulla ligula eu augue. Phasellus quis sem sapien. Nullam ac turpis felis. Aliquam id malesuada turpis, eget ornare eros. Nulla facilisi. Sed dignissim non nisl non pretium. Etiam scelerisque facilisis dui, eget aliquet nulla dignissim non. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc gravida hendrerit justo eget eleifend. Sed in nibh fringilla, eleifend purus sit amet, scelerisque sapien. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Duis vehicula libero arcu, sit amet euismod augue hendrerit non.')->save();
}
if(!isset(StyleQuery::create()->filterByStyle('Lager')->find()[0])) {
    $s = new Style();
    $s->setStyle('Lager')->setDescription('Lager is dolor sit amet, consectetur adipiscing elit. Morbi sodales ligula lorem, id placerat lacus bibendum ultricies. Pellentesque id nulla rutrum, maximus urna at, fringilla neque. Morbi tempus, mauris at sollicitudin rutrum, elit odio fermentum mauris, at sollicitudin nulla ligula eu augue. Phasellus quis sem sapien. Nullam ac turpis felis. Aliquam id malesuada turpis, eget ornare eros. Nulla facilisi. Sed dignissim non nisl non pretium. Etiam scelerisque facilisis dui, eget aliquet nulla dignissim non. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc gravida hendrerit justo eget eleifend. Sed in nibh fringilla, eleifend purus sit amet, scelerisque sapien. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Duis vehicula libero arcu, sit amet euismod augue hendrerit non.')->save();
}
if(!isset(StyleQuery::create()->filterByStyle('Stout')->find()[0])) {
    $s = new Style();
    $s->setStyle('Stout')->setDescription('Stout is consectetur adipiscing elit. Morbi sodales ligula lorem, id placerat lacus bibendum ultricies. Pellentesque id nulla rutrum, maximus urna at, fringilla neque. Morbi tempus, mauris at sollicitudin rutrum, elit odio fermentum mauris, at sollicitudin nulla ligula eu augue. Phasellus quis sem sapien. Nullam ac turpis felis. Aliquam id malesuada turpis, eget ornare eros. Nulla facilisi. Sed dignissim non nisl non pretium. Etiam scelerisque facilisis dui, eget aliquet nulla dignissim non. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc gravida hendrerit justo eget eleifend. Sed in nibh fringilla, eleifend purus sit amet, scelerisque sapien. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Duis vehicula libero arcu, sit amet euismod augue hendrerit non.')->save();
}
if(!isset(StyleQuery::create()->filterByStyle('Porter')->find()[0])) {
    $s = new Style();
    $s->setStyle('Porter')->setDescription('Porter is consectetur adipiscing elit. Morbi sodales ligula lorem, id placerat lacus bibendum ultricies. Pellentesque id nulla rutrum, maximus urna at, fringilla neque. Morbi tempus, mauris at sollicitudin rutrum, elit odio fermentum mauris, at sollicitudin nulla ligula eu augue. Phasellus quis sem sapien. Nullam ac turpis felis. Aliquam id malesuada turpis, eget ornare eros. Nulla facilisi. Sed dignissim non nisl non pretium. Etiam scelerisque facilisis dui, eget aliquet nulla dignissim non. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc gravida hendrerit justo eget eleifend. Sed in nibh fringilla, eleifend purus sit amet, scelerisque sapien. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Duis vehicula libero arcu, sit amet euismod augue hendrerit non.')->save();
}
if(!isset(StyleQuery::create()->filterByStyle('Wheat Beer')->find()[0])) {
    $s = new Style();
    $s->setStyle('Wheat Beer')->setDescription('Wheat beer is consectetur adipiscing elit. Morbi sodales ligula lorem, id placerat lacus bibendum ultricies. Pellentesque id nulla rutrum, maximus urna at, fringilla neque. Morbi tempus, mauris at sollicitudin rutrum, elit odio fermentum mauris, at sollicitudin nulla ligula eu augue. Phasellus quis sem sapien. Nullam ac turpis felis. Aliquam id malesuada turpis, eget ornare eros. Nulla facilisi. Sed dignissim non nisl non pretium. Etiam scelerisque facilisis dui, eget aliquet nulla dignissim non. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc gravida hendrerit justo eget eleifend. Sed in nibh fringilla, eleifend purus sit amet, scelerisque sapien. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Duis vehicula libero arcu, sit amet euismod augue hendrerit non.')->save();
}
if(!isset(StyleQuery::create()->filterByStyle('Sour Beer')->find()[0])) {
    $s = new Style();
    $s->setStyle('Sour Beer')->setDescription('Sour beer is consectetur adipiscing elit. Morbi sodales ligula lorem, id placerat lacus bibendum ultricies. Pellentesque id nulla rutrum, maximus urna at, fringilla neque. Morbi tempus, mauris at sollicitudin rutrum, elit odio fermentum mauris, at sollicitudin nulla ligula eu augue. Phasellus quis sem sapien. Nullam ac turpis felis. Aliquam id malesuada turpis, eget ornare eros. Nulla facilisi. Sed dignissim non nisl non pretium. Etiam scelerisque facilisis dui, eget aliquet nulla dignissim non. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc gravida hendrerit justo eget eleifend. Sed in nibh fringilla, eleifend purus sit amet, scelerisque sapien. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Duis vehicula libero arcu, sit amet euismod augue hendrerit non.')->save();
}


?>