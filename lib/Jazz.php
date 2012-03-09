<?php
/*
The MIT License

Copyright (c) 2012 Christoph Hochstrasser

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

if (!extension_loaded("dom")) {
    throw new \RuntimeException(
        "Jazz requires the DOM Extension. Make sure it's enabled."
    );
}

# Converts nested arrays to HTML.
#
# Examples
# 
#   echo Jazz::render(["#h1", "It works"]);
#   # => "<h1>It works</h1>"
#
#   echo Jazz::render(["#br"]);
#   # string("<br>")
#   
#   echo Jazz::render([
#     ["#section", ["class" => "content"], [
#       ["#p", "This is a paragraph."],
#       ["#p", "This is a second paragraph."]
#     ]]
#   ]);
#   # string("<section class="content">
#   # <p>This is a paragraph.</p>
#   # <p>This is a second paragraph.</p>
#   # </section>")
#   
#   echo Jazz::render(["#img", ["src" => "/foo.png"]]);
#   # string("<img src="/foo.png">")
#
#   $ul = ["#ul", []];
#
#   foreach (array("Foo", "Bar", "Baz") as $item) {
#     $ul[1][] = ["#li", $item];
#   }
#
#   echo Jazz::render($ul);
#   # string("<ul><li>Foo</li><li>Bar</li><li>Baz</li></ul>")
#
class Jazz
{
    # Public: Renders the given node. 
    #
    # node     - Tag as Array, Array of sub nodes or a single 
    #            stringable element.
    # document - DOMDocument instance or variable, optional. Use this 
    #            if you want to set custom options on the document.
    #            This is also a reference, so just pass a variable if
    #            you want to get hold of the created DOMDocument for
    #            other purposes.
    # 
    # Returns HTML as String.
    static function render($node, &$document = null)
    {
        $document = $document ?: new \DOMDocument;
        $document->appendChild(static::renderNode($node, $document));

        return $document->saveHTML();
    }

    # Checks if the provided node hash is a tag, by 
    # looking if the first element is a string that starts
    # with a "#".
    #
    # node - Array.
    #
    # Examples
    #
    #   print_r(static::isTag(["#h1", "foo"]));
    #   # => bool(true)
    #
    #   print_r(static::isTag("foo"));
    #   # => bool(false)
    #
    # Returns True or False.
    protected static function isTag($node)
    {
        return is_array($node) and is_string(@$node[0]) and substr($node[0], 0, 1) == "#";
    }

    # Renders a HTML Tag.
    #
    # node - Single Node.
    #
    # Returns a DOMNode.
    protected static function renderNode($node, $document)
    {
        if (static::isTag($node)) {
            # Strip the leading "#"
            $tagName    = substr(array_shift($node), 1);
            $attributes = array();

            if (sizeof($node) > 2) {
                throw new \UnexpectedValueException(
                    "Tags must not consist of more than three elements"
                );
            }

            foreach ($node as $argv) {
                # Is the tag argument an attributes array?
                if (is_array($argv) and count(array_filter(array_keys($argv), "is_string")) > 0) {
                    $attributes = $argv;
                } else {
                    $content = $argv;
                }
            }

            $el = $document->createElement($tagName);

            foreach ($attributes as $attr => $value) {
                if (is_array($value)) $value = join(" ", $value);
                $el->setAttribute($attr, (string) $value);
            }

            if (isset($content)) {
                $el->appendChild(static::renderNode($content, $document));
            }
        } else {
            $el = $document->createDocumentFragment();

            foreach ((array) $node as $child) {
                if (is_array($child)) {
                    $el->appendChild(static::renderNode($child, $document));
                } else {
                    $el->appendChild($document->createTextNode((string) $child));
                }
            }
        }

        return $el;
    }
}
