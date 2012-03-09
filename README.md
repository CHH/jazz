# Jazz

> When you absolutely, positively have to generate 
> HTML within PHP Code.

Jazz is a very tiny library which turns a nested Array into HTML. 
You could call it a "DSL" if you want. It works well with PHP later
than 5.3.3 but looks best with 5.4's new short array syntax.

Here is a "Hello World" (_in PHP 5.4_):

    <?php

    use Jazz;

    $html = [
        ["#h1", "Hello World"],
        ["#p", ["class" => "intro"], [
            "This is some content for this element.",
            "Here is a second line.",

            ["#a", ["href" => "https://github.com/CHH/Jazz"], [
                "This is a link."
            ]]
        ]]
    ];

    echo Jazz::render($html);
    # <h1>Hello World</h1>
    # <p>This is some content for this element.
    # Here is a second line.
    # <a href="https://github.com/CHH/Jazz">This is a link.</a>
    # </p>

## Syntax

### Tags

A tag is an array of up to three elements (where `$attributes` and
`$body` are optional): 

    ["#$tagName", $attributes, $body]

 * `"#$tagName"`, __mandatory__: 
   The first element is the name of the HTML tag, 
   prefixed with a `#`. This is the only mandatory thing for the tag.
 * `$attributes`, __optional__:
   Attributes are an optional array, passed as second entry. Each key in
   the array is the attribute name.
 * `$body`:
   The body is either a String or an Array of sub elements.

When attributes are left out, the _second_ element in the tag is treated
as body.

Tags can be written in different forms, just like in HTML:

 * Short. For example `["#br"]` becomes `<br />`.
 * Short, with Attributes. For example `["#img", ["src" => "/foo/bar.png"]]` 
   becomes `<img src="/foo/bar.png" />`.
 * Short, with Body: For example `["#p", "Foo"]` becomes
   `<p>Foo</p>`.
 * Long Form: For example `["#h1", ["role" => "banner"], "Hello World"]`
   becomes `<h1 role="banner">Hello World</h1>`.

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

## License

Jazz is licensed under the MIT license, bundled with the Source Code in
the file `LICENSE.txt` as well as contained in the class file.

