<?php

namespace Bob;

task("default", ["test"]);

desc("Runs PHPUnit Test Suite");
task("test", ["phpunit.xml"], function() {
    sh("phpunit");
});

desc("Converts the README to markdown and opens the result in the browser.");
task("readme", function() {
    `redcarpet README.md > README.html`;
    `open README.html`;
});

fileTask("phpunit.xml", ["phpunit.dist.xml"], function() {
    copy("phpunit.dist.xml", "phpunit.xml");
});
