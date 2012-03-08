<?php

namespace Bob;

task("default", array("test"));

desc("Runs PHPUnit Test Suite");
task("test", array("phpunit.xml"), function() {
    sh("phpunit");
});

fileTask("phpunit.xml", array("phpunit.dist.xml"), function() {
    copy("phpunit.dist.xml", "phpunit.xml");
});
