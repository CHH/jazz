# Jazz

> When you absolutely, positively have to generate 
> HTML within PHP Code.

Jazz is a very tiny library which turns a nested Array into HTML. 
You could call it a "DSL" if you want. It works well with PHP later
than 5.3.3 but looks best with 5.4's new short array syntax.

I wrote Jazz, because there are always places where you have to 
generate a bit of HTML and don't want to use a template, for the sake
of performance or simplicity.

At first you need only one tag, you think "Fine I'm using a String.".
Then you need an attribute, then a second attribute, maybe a third
attribute. Then you find yourself in a big mess. 

I've written plenty of those code bits:

    $html  = '<div class="widget '.join(" ", $classes).'" id="'.$id.'" style="display: none;" data-widget-name="' . $widgetName . '">';
    $html .= '<ul>' . $this->getSomeListItems() . '</ul>';
    $html .= '</div>';

It's not only hard to grasp, but also plain ugly and also adding
attributes, or adding other sub elements is very messy.

Now relax with some Jazz:

    array_unshift($classes, "widget");

    $attrs = [
        "class" => $classes, 
        "id" => $id, 
        "style" => "display: none;", 
        "data-widget-name" => $widgetName
    ];

    $html = Jazz::render(
        ["#div", $attrs, [
            ["#ul", $this->getSomeListItems()]
        ]]
    );

It's in this case not shorter, but it's easily extendable, and the intent is clear.

## Hello World

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

#### Synopsis

    ["#$tagName", $attributes, $body]

 * `"#$tagName"`, __mandatory__: 
   The first element is the name of the HTML tag, 
   prefixed with a `#`. This is the only mandatory thing for the tag.
 * `$attributes`, __optional__:
   Attributes are an optional array, passed as second entry. Each key in
   the array is the attribute name.
 * `$body`, __optional__:
   The body is either a String or an Array of sub elements. Bodies are
   _not_ escaped!

When attributes are left out, the _second_ element in the tag is treated
as body.

Tags can be written in different forms, just like in HTML:

 * __Short__. For example `["#br"]` becomes `<br>`.
 * __Short__, with Attributes. For example `["#img", ["src" => "/foo/bar.png"]]` 
   becomes `<img src="/foo/bar.png">`.
 * __Short__, with Body: For example `["#p", "Foo"]` becomes
   `<p>Foo</p>`.
 * **Long Form**. For example `["#h1", ["role" => "banner"], "Hello World"]`
   becomes `<h1 role="banner">Hello World</h1>`.

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

