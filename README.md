# Jazz

> When you absolutely, positively have to generate 
> HTML within PHP Code.

Jazz is a very tiny library for turning a specially
structured Array into HTML. You could call it a "DSL" if you
want.

Here is a "Hello World" (_in PHP 5.4_):

    <?php

    use Jazz;

    $html = [
        ["#h1", "Hello World"],
        ["#p", ["class" => "intro"], [
            "This is some content for this <p> element.",
            "Here is a second line.",

            ["#a", ["href" => "https://github.com/CHH/Jazz"], [
                "This is a link."
            ]]
        ]]
    ];

    echo Jazz::render($html);
    # <h1>Hello World</h1>
    # <p>This is some content for this &lt;p&gt; element.
    # Here is a second line.
    # <a href="https://github.com/CHH/Jazz">This is a link.</a>
    # </p>

Jazz works perfectly fine with PHP 5.3, but due to 5.4's new 
short array notation it's more readable with PHP 5.4.

## Syntax

### Tags

A tag is an array of two or three elements: `["#$tagName", $attributes,
$body]`. The first element is the HTML tag's name, prefixed with a pound
sign ("#"). Attributes are passed as Array at index `1`. Attributes are
optional. Finally, the body is the third element in the Array and can
either be a single String, or an Array with sub elements.

When attributes are left out, the _second_ element in the tag is treated
as body.

Jazz makes self-closed (`/>`) tags when no body is passed.

## Install

Get composer:

    % wget http://getcomposer.org/composer.phar

Require `chh/jazz` in your application's `composer.json`:

    {
        "require": {
            "chh/jazz": "*"
        }
    }

Load composer's class loader:

    <?php

    require_once("vendor/.composer/autoload.php");

