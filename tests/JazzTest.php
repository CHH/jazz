<?php

class JazzTest extends \PHPUnit_Framework_TestCase
{
    function testSimpleTagWithoutAttributesAndBody()
    {
        $this->assertEquals(
            "<br />",
            Jazz::render(["#br"])
        );
    }

    function testSecondElementCanBeAttributes()
    {
        $this->assertEquals(
            '<img src="/foo/bar.png" />',
            Jazz::render(["#img", ["src" => "/foo/bar.png"]])
        );
    }

    function testTagsCanHaveTextContent()
    {
        $this->assertEquals(
            "<p>Foo</p>",
            Jazz::render(["#p", "Foo"])
        );
    }

    function testRendersEmptyBody()
    {
        $this->assertEquals(
            "<p></p>",
            Jazz::render(["#p", ""])
        );
    }

    function testTagWithAttributesAndBody()
    {
        $this->assertEquals(
            '<h1 role="banner">Unicorns and Rainbows</h1>',
            Jazz::render(
                ["#h1", ["role" => "banner"], "Unicorns and Rainbows"]
            )
        );
    }

    function testTagWithNestedTags()
    {
        $this->assertEquals(
            '<nav class="main"><a href="/">Front Page</a></nav>',
            Jazz::render(["#nav", ["class" => "main"], [
                ["#a", ["href" => "/"], "Front Page"]
            ]])
        );
    }

    function testEncodesTextContent()
    {
        $this->markTestSkipped("Have to think about this feature...");

        $this->assertEquals(
            '<p>This is encoded: &lt;p&gt;</p>',
            Jazz::render(["#p", "This is encoded: <p>"])
        );
    }

    function testNoEscape()
    {
        $this->markTestSkipped("Have to think about this feature...");

        $this->assertEquals(
            '<p>This is not encoded: <a name="bar">Foo</a></p>',
            Jazz::render(
                ["#p", [
                    ["!!", 'This is not encoded: <a name="bar">Foo</a>']
                ]]
            )
        );
    }

    function testJoinsNodeListWithoutRootElement()
    {
        $this->assertEquals(
            "<p>Foo</p><p>Bar</p>",
            Jazz::render([
                ["#p", "Foo"],
                ["#p", "Bar"]
            ])
        );
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    function testThrowsExceptionWhenTooManyElementsInTag()
    {
        Jazz::render(["#p", "foo", "bar", "baz"]);
    }
}
